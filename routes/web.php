<?php

use App\Events\ServiceAvailable;
use App\Events\ServiceStatusChanged;
use App\Models\Asistbot;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
    if (auth()->user()->hasRole('employee')) return to_route('dashboard');
    if (auth()->user()->hasRole('admin')) return to_route('orders.index');
    Inertia::setRootView('root');
    return to_route('root.users.index');
});

Route::group(['middleware'=>'auth'], function(){
    Route::resource('employees', App\Http\Controllers\EmployeeController::class);
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::resource('form_fields', App\Http\Controllers\FormFieldController::class)->except('index');
    Route::resource('orders', App\Http\Controllers\OrderController::class)->except(['create', 'store']);
    Route::resource('services', App\Http\Controllers\ServiceController::class);
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

Route::get('dispatch', function(){
    ServiceStatusChanged::dispatch(5, 5);
});

Route::get('orderservice', function(Request $request){
    return auth()->user()->employments()->available()->with('order')->get();
    Service::with('order')
            ->join('order_service', 'order_service.service_id', 'services.id')
            ->join('orders', 'orders.id', '=', 'order_service.order_id')
            ->select('services.*', 'order_service.*', 'services.id as id')
            ->where(['order_service.service_id'=> $request->service_id, 'order_id'=>$request->order_id])
            ->first();
});


Route::get('test', function(){
    return Service::join('order_service', 'order_service.service_id', 'services.id')
    ->join('orders', function(JoinClause $join){
        $join
        ->on('orders.id', 'order_service.order_id')
        ->on('orders.service_id', 'services.id');
    })
    ->select(['services.*', 'order_service.*', 'orders.*'])
    ->where(['order_service.service_id'=> 2])
    ->get();
});

Route::get('messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
Route::get('messages/scan', [App\Http\Controllers\MessageController::class, 'scan'])->name('messages.scan');
Route::get('messages/edit-phone', [App\Http\Controllers\MessageController::class, 'editPhone'])->name('messages.edit_phone');
Route::put('messages/update-phone', [App\Http\Controllers\MessageController::class, 'updatePhone'])->name('messages.update_phone');
Route::put('messages/logout', [App\Http\Controllers\MessageController::class, 'logout'])->name('messages.logout');

Route::get('asistbot', function(Request $request){
    Storage::append('app/logs/webhook.log', json_encode($request->all()));
    return true;
});
