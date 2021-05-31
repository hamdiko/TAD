<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all();

        $settings = $settings->mapWithKeys(function($setting, $key) {
            return [$setting->key => $setting];
        });        

        return response()->json([
            'status'      => true,
            'settings'    => $settings,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        return response()->json([
            'status'      => true,
            'setting'    => $setting,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Setting\StoreSettingRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSettingRequest $request)
    {
        $settings = $request->settings ?? [];

        collect($settings)->each(function ($value, $key) {
            Setting::create([
                'key'      => $key,
                'value'    => $value,
                'added_by' => auth()->id()
            ]);
        });

        return response()->json([
            'status'      => true,
            'settings'    => 'Settings have been saved successfully.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\Setting\UpdateSettingRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSettingRequest $request, Setting $setting)
    {
        $setting->update([
            'value' => $request->value,
        ]);

        return response()->json([
            'status'      => true,
            'settings'    => 'Settings have been updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param App\Models\Setting $setting
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return response()->json([
            'status'      => true,
            'message'    => 'Settings have been deleted successfully.',
        ]);
    }
}
