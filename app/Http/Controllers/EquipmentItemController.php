<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use App\Models\EquipmentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipmentItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = EquipmentItem::with('category')->get();
        return view('equipment.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = EquipmentCategory::where('is_active', true)->get();
        return view('equipment.items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:equipment_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:equipment_items',
            'description' => 'nullable|string',
            'daily_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|string|in:new,good,fair,poor',
            'location' => 'nullable|string|max:255',
            'specifications' => 'nullable|array',
            'images' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['available_quantity'] = $data['quantity'];
        
        EquipmentItem::create($data);

        return redirect()->route('equipment.items.index')
            ->with('success', 'Equipment item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentItem $item)
    {
        $item->load('category');
        return view('equipment.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentItem $item)
    {
        $categories = EquipmentCategory::where('is_active', true)->get();
        return view('equipment.items.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentItem $item)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:equipment_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:equipment_items,code,' . $item->id,
            'description' => 'nullable|string',
            'daily_rate' => 'required|numeric|min:0',
            'weekly_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|string|in:new,good,fair,poor',
            'location' => 'nullable|string|max:255',
            'specifications' => 'nullable|array',
            'images' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // Calculate the difference in quantity and adjust available_quantity
        $quantityDiff = $data['quantity'] - $item->quantity;
        $data['available_quantity'] = $item->available_quantity + $quantityDiff;
        
        $item->update($data);

        return redirect()->route('equipment.items.index')
            ->with('success', 'Equipment item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentItem $item)
    {
        // Check if item has associated rentals
        if ($item->rentalItems()->exists()) {
            return redirect()->route('equipment.items.index')
                ->with('error', 'Cannot delete item with associated rentals.');
        }

        $item->delete();

        return redirect()->route('equipment.items.index')
            ->with('success', 'Equipment item deleted successfully.');
    }
} 