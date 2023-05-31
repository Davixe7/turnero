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
        $service     = $event->service;
        $nextService = Service::whereRelation('orders', 'order_id', '=', $event->service->order->id)->where('index', '>', $service->index)->first();
        $recepcion   = $event->service->order->services()->whereIndex(0)->first();

        if (($event->service->state == 'success') && $nextService) {
            $event->service->order->update(['service_id' => $nextService->id]);
        }else {
            $event->service->order->update(['service_id' => $recepcion->id]);
        }

        ServiceAvailable::dispatch($nextService ? $nextService->id : $recepcion->id, $event->service->order->id);
    }
}
