<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts   = ['created_at'=>'datetime:Y-m-d H:i:s'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function services(){
        return $this->belongsToMany(Service::class)
        ->select('services.*', 'order_service.*', 'services.id as id')
        ->orderBy('services.index');
    }
}
