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
Route::get('form_fields', [App\Http\Controllers\FormFieldController::class, 'index'])->name('form_fields.index');
Route::post('orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');

Route::middleware('guest')->group( function(){
    Route::inertia('login', 'Login')->name('login');
    Route::get('employee/login', function(){
        Inertia::setRootView('employee');
        return Inertia::render('Login');
    })->name('employee.login');
});

Route::get('home', function(){
    if (auth()->user()->user_id) return to_route('dashboard');
    if (auth()->user()->email != 'root@example.com') return to_route('orders.index');
    return to_route('root.users.index');
});

Route::group(['middleware'=>'auth'], function(){
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::resource('form_fields', App\Http\Controllers\FormFieldController::class)->except('index');
    Route::resource('orders', App\Http\Controllers\OrderController::class)->except(['create', 'store']);
    Route::resource('services', App\Http\Controllers\ServiceController::class);
    Route::resource('messages', App\Http\Controllers\MessageController::class);
});

Route::group(['middleware'=>'auth'], function(){
    Route::get('order_services/index', [\App\Http\Controllers\OrderServiceController::class, 'index'])->name('dashboard');
    Route::put('order_services/take', [App\Http\Controllers\OrderServiceController::class, 'take'])->name('order_services.take');
    Route::put('order_services/update', [App\Http\Controllers\OrderServiceController::class, 'update'])->name('order_services.update');
});

Route::name('root.')->prefix('root')->group(function(){
    Route::get('login', function(){
        Inertia::setRootView('root');
        return Inertia::render('Login');
    })
    ->name('login')
    ->middleware('guest');

    Route::resource('users', App\Http\Controllers\UserController::class);
});
