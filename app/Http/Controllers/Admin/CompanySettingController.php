<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    public function edit()
    {
        \Log::info('Accessing company settings page');
        $settings = CompanySetting::getSettings();
        \Log::info('Settings retrieved', ['settings' => $settings]);
        return view('admin.settings.company', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'currency_code' => 'required|string|size:3',
            'currency_symbol' => 'required|string|max:10',
        ]);

        $settings = CompanySetting::getSettings();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = $path;
        }

        $settings->update([
            'company_name' => $request->company_name,
            'currency_code' => strtoupper($request->currency_code),
            'currency_symbol' => $request->currency_symbol,
        ]);

        return back()->with('success', 'Company settings updated successfully.');
    }
} 