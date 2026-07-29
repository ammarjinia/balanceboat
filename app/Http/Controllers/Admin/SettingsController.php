<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PlatformSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $depositAutoDiscount = PlatformSetting::get('deposit_auto_discount_pct', 5);

        return view('admin.settings.index', compact('depositAutoDiscount'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'deposit_auto_discount_pct' => 'required|numeric|min:0|max:100',
        ]);

        PlatformSetting::set('deposit_auto_discount_pct', $request->input('deposit_auto_discount_pct'));

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }
}
