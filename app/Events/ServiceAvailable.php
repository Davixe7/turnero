<?php

namespace App\Events;

use App\Models\Service;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use PhpParser\Node\Expr\Cast\Object_;

class ServiceAvailable implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $service;

    /**
     * Create a new event instance.
     */

    public function __construct($service, $orderId = null)
    {
        if( is_object($service) ){
            $this->service == $service;
        }
        if( !is_null($orderId) && (is_integer($service) || is_string($service)) ){
            $this->service = Service::asDemand($service, $orderId)->toArray();
        }

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('users.' . $this->service['order']['user_id'] . '.services');
    }
}
