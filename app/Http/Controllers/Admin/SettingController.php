<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Load all settings into an array
        $settings = Setting::getAll();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // We validate general and payment gateway settings
        $request->validate([
            'site_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_whatsapp' => 'nullable|string|max:50',
            'active_payment_gateway' => 'required|string|in:midtrans,duitku',
            
            // Midtrans settings
            'midtrans_merchant_id' => 'nullable|string|max:255',
            'midtrans_client_key' => 'nullable|string|max:255',
            'midtrans_server_key' => 'nullable|string|max:255',
            'midtrans_is_production' => 'nullable|string|in:0,1',
            
            // Duitku settings
            'duitku_merchant_code' => 'nullable|string|max:255',
            'duitku_merchant_key' => 'nullable|string|max:255',
            'duitku_is_production' => 'nullable|string|in:0,1',
        ]);

        // General settings
        Setting::set('site_name', $request->input('site_name', 'RuangRestu'));
        Setting::set('contact_email', $request->input('contact_email', 'support@ruangrestu.com'));
        Setting::set('contact_whatsapp', $request->input('contact_whatsapp', '6281234567890'));
        Setting::set('active_payment_gateway', $request->input('active_payment_gateway', 'midtrans'));

        // Midtrans
        Setting::set('midtrans_merchant_id', $request->input('midtrans_merchant_id'));
        Setting::set('midtrans_client_key', $request->input('midtrans_client_key'));
        Setting::set('midtrans_server_key', $request->input('midtrans_server_key'));
        Setting::set('midtrans_is_production', $request->input('midtrans_is_production', '0'));

        // Duitku
        Setting::set('duitku_merchant_code', $request->input('duitku_merchant_code'));
        Setting::set('duitku_merchant_key', $request->input('duitku_merchant_key'));
        Setting::set('duitku_is_production', $request->input('duitku_is_production', '0'));

        // Handle file upload for site logo if provided
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path);
        }

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
