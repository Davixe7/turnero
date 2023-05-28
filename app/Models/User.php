<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function admin(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function services(){
        return $this->hasMany(Service::class);
    }

    public function employments(){
        return $this->belongsToMany(Service::class, 'employee_service');
    }

    public function assignments(){
        return $this
                ->belongsToMany(Service::class, 'order_service', 'taken_by')
                ->select(
                    'services.*',
                    'order_service.*',
                    'services.id as id'
                );
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function clients(){
        return $this->hasMany(Client::class);
    }

    public function employees(){
        return $this->hasMany(User::class);
    }

    public function form_fields(){
        return $this->hasMany(FormField::class);

    }

    public function getOrderServicesAttribute(){
        // return $this->belongsToMany(Service::class, 'employee_service')
        // ->join('order_service', 'order_service.service_id', '=', 'services.id')
        // ->select(['services.name', 'order_service.status', 'order_service.comment', 'order_service.order_id'])
        // ->where('order_service.status', '=', 'available')
        // ->get();

        $services = $this->jobs()->pluck('id')->toArray();

        return Order::whereHas('services', function($query) use ($services) {
            $query
            ->whereIn('services.id', $services)
            ->where('status', 'available');
        })
        ->with('services', function($query) use ($services){
            $query
            ->whereIn('services.id', $services)
            ->where('order_service.status', 'available');
        })
        ->get();

        // return
        // DB::table('employee_service')
        // ->join('services', 'services.id', '=', 'employee_service.service_id')
        // ->join('order_service', 'order_service.id', '=', 'order_service.service_id')
        // ->select(['services.name', 'order_service.status', 'order_service.comment'])
        // ->where('employee_service.user_id', $this->id)
        // ->get();
    }
}
