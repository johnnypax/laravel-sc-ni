<?php

namespace App\Http\Controllers;

use App\Services\RabbitMQService;

class OrderTestController extends Controller
{
    public function random(RabbitMQService $mq)
    {
        // array di nomi possibili
        $names = [
            'Giovanni', 'Francesca', 'Luca', 'Marco', 'Elena',
            'Sara', 'Andrea', 'Paolo', 'Alice', 'Giorgia'
        ];

        // scegli un nome casuale
        $customer = $names[array_rand($names)];

        // genera importo tra 5 e 200 €
        $total = rand(5, 200);

        // pacchetto ordine
        $order = [
            'order_id'    => uniqid('ord_'),
            'customer'    => $customer,
            'total'       => $total,
            'created_at'  => now()->toISOString(),
        ];

        // pubblica sulla coda
        $mq->publish(env('RABBITMQ_QUEUE'), $order);

        return response()->json([
            'status' => 'queued',
            'order'  => $order
        ]);
    }
}
