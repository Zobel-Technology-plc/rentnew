<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipmentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = EquipmentCategory::withCount('items')->get();
        return view('equipment.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('equipment.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        EquipmentCategory::create($request->all());

        return redirect()->route('equipment.categories.index')
            ->with('success', 'Equipment category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentCategory $category)
    {
        $category->load('items');
        return view('equipment.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentCategory $category)
    {
        return view('equipment.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update($request->all());

        return redirect()->route('equipment.categories.index')
            ->with('success', 'Equipment category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentCategory $category)
    {
        // Check if category has items
        if ($category->items()->count() > 0) {
            return redirect()->route('equipment.categories.index')
                ->with('error', 'Cannot delete category with associated items.');
        }

        $category->delete();

        return redirect()->route('equipment.categories.index')
            ->with('success', 'Equipment category deleted successfully.');
    }
} 