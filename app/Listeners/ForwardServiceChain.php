<?php

namespace App\Listeners;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class ForwardServiceChain
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $order   = Order::find($event->order_id);
        $service = $order->services()->find($event->service_id);
        $nextService = Service::whereRelation('orders', 'order_id', '=', $order->id)->where('index', '>', $service->index)->first();

        if ($event->state == 'success') {
            $order->update(['service_id' => $nextService ? $nextService->id : $order->service_id]);
            return;
        }
        $recepcion = $order->services()->whereIndex(0)->first();
        $order->update(['service_id' => $recepcion->id]);
    }
}
