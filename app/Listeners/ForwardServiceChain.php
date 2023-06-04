<?php

namespace App\Listeners;

use App\Events\ServiceAvailable;
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
        $service     = Service::asDemand($event->service['id'], $event->service['order_id']);
        $nextService = Service::whereRelation('orders', 'order_id', '=', $service->order_id)->where('index', '>', $service->index)->first();
        $recepcion   = $service->order->services()->whereIndex(0)->first();

        $nextService = $nextService && ($service->state == 'success')
                       ? $nextService
                       : $recepcion;

        $service->order->update(['service_id' => $nextService->id]);

        ServiceAvailable::dispatch($nextService ? $nextService->id : $recepcion->id, $service->order->id);
    }
}
