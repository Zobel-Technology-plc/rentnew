<?php

namespace App\Http\Controllers;

use App\Models\EquipmentItem;
use App\Models\MaintenanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MaintenanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = MaintenanceRecord::with('equipmentItem')
            ->latest()
            ->paginate(10);

        return view('maintenance.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = EquipmentItem::where('is_active', true)->get();
        return view('maintenance.create', compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'equipment_item_id' => 'required|exists:equipment_items,id',
            'type' => 'required|in:preventive,corrective,emergency',
            'scheduled_date' => 'required|date|after:today',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $record = MaintenanceRecord::create([
                'equipment_item_id' => $request->equipment_item_id,
                'maintenance_number' => 'MAINT-' . strtoupper(uniqid()),
                'type' => $request->type,
                'scheduled_date' => $request->scheduled_date,
                'description' => $request->description,
                'notes' => $request->notes,
                'status' => 'scheduled',
            ]);

            if ($request->hasFile('attachments')) {
                $attachments = [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('maintenance-attachments', 'public');
                    $attachments[] = $path;
                }
                $record->update(['attachments' => $attachments]);
            }

            DB::commit();

            return redirect()->route('maintenance.show', $record)
                ->with('success', 'Maintenance record created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while creating the maintenance record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceRecord $record)
    {
        $record->load('equipmentItem');
        return view('maintenance.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceRecord $record)
    {
        if ($record->status !== 'scheduled') {
            return redirect()->route('maintenance.show', $record)
                ->with('error', 'Only scheduled maintenance records can be edited.');
        }

        $items = EquipmentItem::where('is_active', true)->get();
        return view('maintenance.edit', compact('record', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaintenanceRecord $record)
    {
        if ($record->status !== 'scheduled') {
            return redirect()->route('maintenance.show', $record)
                ->with('error', 'Only scheduled maintenance records can be updated.');
        }

        $validator = Validator::make($request->all(), [
            'equipment_item_id' => 'required|exists:equipment_items,id',
            'type' => 'required|in:preventive,corrective,emergency',
            'scheduled_date' => 'required|date|after:today',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $record->update([
                'equipment_item_id' => $request->equipment_item_id,
                'type' => $request->type,
                'scheduled_date' => $request->scheduled_date,
                'description' => $request->description,
                'notes' => $request->notes,
            ]);

            if ($request->hasFile('attachments')) {
                $attachments = $record->attachments ?? [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('maintenance-attachments', 'public');
                    $attachments[] = $path;
                }
                $record->update(['attachments' => $attachments]);
            }

            DB::commit();

            return redirect()->route('maintenance.show', $record)
                ->with('success', 'Maintenance record updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while updating the maintenance record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Complete a maintenance record.
     */
    public function complete(Request $request, MaintenanceRecord $record)
    {
        if ($record->status !== 'scheduled') {
            return redirect()->route('maintenance.show', $record)
                ->with('error', 'Only scheduled maintenance records can be completed.');
        }

        $validator = Validator::make($request->all(), [
            'completed_date' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $record->update([
                'completed_date' => $request->completed_date,
                'cost' => $request->cost,
                'notes' => $request->notes,
                'status' => 'completed',
            ]);

            if ($request->hasFile('attachments')) {
                $attachments = $record->attachments ?? [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('maintenance-attachments', 'public');
                    $attachments[] = $path;
                }
                $record->update(['attachments' => $attachments]);
            }

            DB::commit();

            return redirect()->route('maintenance.show', $record)
                ->with('success', 'Maintenance record completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred while completing the maintenance record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceRecord $record)
    {
        if ($record->status !== 'scheduled') {
            return redirect()->route('maintenance.show', $record)
                ->with('error', 'Only scheduled maintenance records can be deleted.');
        }

        try {
            DB::beginTransaction();
            $record->delete();
            DB::commit();

            return redirect()->route('maintenance.index')
                ->with('success', 'Maintenance record deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('maintenance.show', $record)
                ->with('error', 'An error occurred while deleting the maintenance record: ' . $e->getMessage());
        }
    }
} 