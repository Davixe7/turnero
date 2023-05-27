<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeAvailable($query){
        return $query
                ->join('order_service', 'order_service.service_id', '=', 'services.id')
                ->join('orders', 'orders.service_id', '=', 'services.id')
                ->where('order_service.taken_by', null)
                ->select('services.*', 'order_service.*', 'orders.*');
    }
}
