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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
