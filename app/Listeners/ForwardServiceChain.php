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
        // $service = $event->order->services()->find($event->service->id);
        // $nextService = Service::whereRelation('orders', 'order_id', '=', $event->order->id)->where('index', '>', $service->index)->first();

        // if ($event->state == 'success') {
        //     $event->order->update(['service_id' => $nextService ? $nextService->id : $event->order->service_id]);
        // }else {
        //     $recepcion = $event->order->services()->whereIndex(0)->first();
        //     $event->order->update(['service_id' => $recepcion->id]);
        // }



        // ServiceAvailable::dispatch($service, $order);
    }
}
