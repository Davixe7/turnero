<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ServiceStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $service;
    public $order;
    public $array_service;


    /**
     * Create a new event instance.
     */
    public function __construct($service, $orderId)
    {
        if( is_object($service) ){
            $this->service == $service;
        }
        if( !is_null($service) && (is_integer($service) || is_string($service)) ){
            $this->service = Service::asDemand($service, $orderId)->toArray();
        }
        $this->array_service = $this->service->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('users.' . $this->array_service['order']['user_id'] . '.services');
    }
}
