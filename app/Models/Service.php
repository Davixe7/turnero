<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function employees(){
        return $this->belongsToMany(User::class, 'employee_service');
    }

    public function employee(){
        return $this->belongsTo(User::class, 'taken_by');
    }

    public function scopeForUser($query, $user_id){
        return $query->whereHas('employees', function($query) use ($user_id) {
            $query->where('id', $user_id);
        });
    }

    public function scopeAsDemand($query, $service, $order){
        return $query->with('order.fields')
        ->join('order_service', 'order_service.service_id', 'services.id')
        ->join('orders', 'orders.id', '=', 'order_service.order_id')
        ->select('services.*', 'order_service.*', 'services.id as id')
        ->where(['order_service.service_id'=> $service, 'order_id'=>$order])
        ->first();
    }

    public function scopeAvailable($query){
        return $query
        ->join('order_service', 'order_service.service_id', '=', 'services.id')
        ->join('orders', function(JoinClause $join){
            $join
            ->on('orders.service_id', '=', 'services.id')
            ->on('orders.id', '=', 'order_service.order_id');
        })
        ->where('order_service.taken_at', '=', null)
        ->select('services.id', 'services.name', 'order_service.*', 'services.id as id');
    }
}
