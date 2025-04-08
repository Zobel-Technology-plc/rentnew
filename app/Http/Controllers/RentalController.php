<?php

namespace App\Http\Controllers;

use App\Models\EquipmentItem;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rentals = Rental::with(['user', 'items.equipmentItem'])
            ->latest()
            ->paginate(10);

        return view('rentals.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_active', true)->get();
        $items = EquipmentItem::where('is_active', true)
            ->where('available_quantity', '>', 0)
            ->get();

        return view('rentals.create', compact('users', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'deposit_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.equipment_item_id' => 'required|exists:equipment_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Calculate rental costs and check availability
            $totalAmount = 0;
            $rentalItems = [];

            foreach ($request->items as $item) {
                $equipmentItem = EquipmentItem::findOrFail($item['equipment_item_id']);
                
                if ($equipmentItem->available_quantity < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Insufficient quantity available for {$equipmentItem->name}")
                        ->withInput();
                }

                // Calculate rental duration in days
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $duration = $endDate->diffInDays($startDate);

                // Calculate cost based on duration
                $itemCost = 0;
                if ($duration <= 7) {
                    $itemCost = $equipmentItem->daily_rate * $duration * $item['quantity'];
                } elseif ($duration <= 30) {
                    $itemCost = $equipmentItem->weekly_rate * ceil($duration / 7) * $item['quantity'];
                } else {
                    $itemCost = $equipmentItem->monthly_rate * ceil($duration / 30) * $item['quantity'];
                }

                $totalAmount += $itemCost;
                $rentalItems[] = [
                    'equipment_item_id' => $equipmentItem->id,
                    'quantity' => $item['quantity'],
                    'daily_rate' => $equipmentItem->daily_rate,
                    'weekly_rate' => $equipmentItem->weekly_rate,
                    'monthly_rate' => $equipmentItem->monthly_rate,
                    'total_cost' => $itemCost,
                ];

                // Update available quantity
                $equipmentItem->decrement('available_quantity', $item['quantity']);
            }

            // Create rental record
            $rental = Rental::create([
                'user_id' => $request->user_id,
                'rental_number' => 'RENT-' . strtoupper(uniqid()),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'deposit_amount' => $request->deposit_amount,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Create rental items
            foreach ($rentalItems as $item) {
                $rental->items()->create($item);
            }

            DB::commit();

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Rental created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while creating the rental: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental)
    {
        $rental->load(['user', 'items.equipmentItem', 'payments']);
        return view('rentals.show', compact('rental'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rental $rental)
    {
        if ($rental->status !== 'pending') {
            return redirect()->route('rentals.show', $rental)
                ->with('error', 'Only pending rentals can be updated.');
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'deposit_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.equipment_item_id' => 'required|exists:equipment_items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Restore previous quantities
            foreach ($rental->items as $item) {
                $item->equipmentItem->increment('available_quantity', $item->quantity);
            }

            // Calculate new rental costs and check availability
            $totalAmount = 0;
            $rentalItems = [];

            foreach ($request->items as $item) {
                $equipmentItem = EquipmentItem::findOrFail($item['equipment_item_id']);
                
                if ($equipmentItem->available_quantity < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', "Insufficient quantity available for {$equipmentItem->name}")
                        ->withInput();
                }

                // Calculate rental duration in days
                $startDate = \Carbon\Carbon::parse($request->start_date);
                $endDate = \Carbon\Carbon::parse($request->end_date);
                $duration = $endDate->diffInDays($startDate);

                // Calculate cost based on duration
                $itemCost = 0;
                if ($duration <= 7) {
                    $itemCost = $equipmentItem->daily_rate * $duration * $item['quantity'];
                } elseif ($duration <= 30) {
                    $itemCost = $equipmentItem->weekly_rate * ceil($duration / 7) * $item['quantity'];
                } else {
                    $itemCost = $equipmentItem->monthly_rate * ceil($duration / 30) * $item['quantity'];
                }

                $totalAmount += $itemCost;
                $rentalItems[] = [
                    'equipment_item_id' => $equipmentItem->id,
                    'quantity' => $item['quantity'],
                    'daily_rate' => $equipmentItem->daily_rate,
                    'weekly_rate' => $equipmentItem->weekly_rate,
                    'monthly_rate' => $equipmentItem->monthly_rate,
                    'total_cost' => $itemCost,
                ];

                // Update available quantity
                $equipmentItem->decrement('available_quantity', $item['quantity']);
            }

            // Update rental record
            $rental->update([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'deposit_amount' => $request->deposit_amount,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Delete old rental items
            $rental->items()->delete();

            // Create new rental items
            foreach ($rentalItems as $item) {
                $rental->items()->create($item);
            }

            DB::commit();

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Rental updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while updating the rental: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental)
    {
        if ($rental->status !== 'pending') {
            return redirect()->route('rentals.show', $rental)
                ->with('error', 'Only pending rentals can be deleted.');
        }

        try {
            DB::beginTransaction();

            // Restore quantities
            foreach ($rental->items as $item) {
                $item->equipmentItem->increment('available_quantity', $item->quantity);
            }

            $rental->delete();

            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Rental deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('rentals.show', $rental)
                ->with('error', 'An error occurred while deleting the rental: ' . $e->getMessage());
        }
    }
} 