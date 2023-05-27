<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = auth()->user()->orders()->with(['client', 'services'])->get();
        return Inertia::render('Orders', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $admins = User::with('services')->get();
        return Inertia::render('CreateOrder', compact('admins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'dni'     => 'required',
            'name'    => 'required',
            'phone'   => 'required',
        ]);

        $client = Client::create($request->except(['id', 'services']));

        $recepcion = Service::where(['user_id' => $request->user_id, 'index' => '0'])->first();

        $lastOrder = Order::where('user_id', $request->user_id)->latest()->first();
        $order  = Order::create([
            'index'      => $lastOrder ? $lastOrder->index + 1 : 1,
            'client_id'  => $client->id,
            'user_id'    => $request->user_id,
            'service_id' => $recepcion->id
        ]);

        $services  = array_merge($request->services, [$recepcion->id]);
        $order->services()->attach($services);
        return to_route('orders.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('services.employee', 'client');
        return Inertia::render('OrderDetails', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
