<?php

use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('orders/create', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
Route::post('orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');

Route::group(['middleware'=>'auth'], function(){
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('services', App\Http\Controllers\ServiceController::class);
    Route::resource('orders', App\Http\Controllers\OrderController::class)->except(['create', 'store']);
    Route::resource('clients', App\Http\Controllers\ClientController::class);

    Route::put('order_services/take', [App\Http\Controllers\OrderServiceController::class, 'take'])->name('order_services.take');
    Route::put('order_services/update', [App\Http\Controllers\OrderServiceController::class, 'update'])->name('order_services.update');

    Route::get('dashboard', function(){
        Inertia::setRootView('employee');
        $history  = auth()->user()->assignments()->whereNotNull('finished_at')->with('order')->get();
        $service  = auth()->user()->assignments()->where('finished_at', null)->with('order')->first();
        $services = auth()->user()->employments()->available()->with('order')->get();
        return Inertia::render('Dashboard', compact('services', 'service', 'history'));
    })->name('dashboard');
});

Route::get('home', function(){
    return auth()->user()->user_id
    ? to_route('dashboard')
    : to_route('orders.index');
});

Route::middleware('guest')->group( function(){
    Route::inertia('login', 'Login')->name('login');
    Route::get('employee/login', function(){
        Inertia::setRootView('employee');
        return Inertia::render('Login');
    });
});
