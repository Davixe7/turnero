<?php

namespace App\Http\Controllers;

use App\Events\ServiceStatusChanged;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Inertia::setRootView('employee');
        $service  = auth()->user()->assignments()->where('finished_at', null)->with('order')->first();
        $services = auth()->user()->employments()->available()->with('order')->get();
        $history  = auth()->user()->assignments()->whereNotNull('finished_at')->with('order')->get();
        return Inertia::render('Dashboard', compact('services', 'service', 'history'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function take(Request $request)
    {
        $request->validate([
            'order_id'   => 'required',
            'service_id' => 'required',
        ]);

        DB::table('order_service')
        ->where('order_id', $request->order_id)
        ->where('service_id', $request->service_id)
        ->update(['taken_by' => auth()->id(), 'taken_at' => now()]);

        return to_route('dashboard');
    }

    public function update(Request $request)
    {
        $request->validate([
            'order_id'   => 'required',
            'service_id' => 'required',
            'state'     => 'required'
        ]);

        DB::table('order_service')
        ->where('order_id', $request->order_id)
        ->where('service_id', $request->service_id)
        ->update(['state' => $request->state, 'finished_at'=>now()]);

        ServiceStatusChanged::dispatch($request->order_id, $request->service_id, $request->state);

        return to_route('dashboard');
    }

}
