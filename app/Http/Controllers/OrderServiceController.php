<?php

namespace App\Http\Controllers;

use App\Events\ServiceStatusChanged;
use App\Events\ServiceTaken;
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
        $service  = auth()->user()->assignments()->where('finished_at', null)->with('order.fields')->first();
        $services = auth()->user()->employments()->available()->with('order.fields')->get();
        $history  = auth()->user()->assignments()->whereNotNull('finished_at')->with('order.fields')->get();
        return Inertia::render('Dashboard', compact('history', 'service', 'services'));
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

        broadcast(new ServiceTaken($request->service_id, $request->order_id))->toOthers();

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

        ServiceStatusChanged::dispatch($request->service_id, $request->order_id);

        return to_route('dashboard');
    }

}
