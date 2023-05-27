<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ServiceStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order_id;
    public $service_id;
    public $state;

    /**
     * Create a new event instance.
     */
    public function __construct($order_id, $service_id, $state)
    {
        $this->order_id        = $order_id;
        $this->service_id      = $service_id;
        $this->state           = $state;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
