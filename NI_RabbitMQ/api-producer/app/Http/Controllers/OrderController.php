<?php

namespace App\Http\Controllers;

use App\Services\RabbitMQService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request, RabbitMQService $mq)
    {
        $data = $request->validate([
            'customer' => 'required|string',
            'total'    => 'required|numeric'
        ]);

        $message = [
            'order_id' => uniqid('ord_'),
            'customer' => $data['customer'],
            'total'    => $data['total'],
            'created_at' => now()->toISOString()
        ];

        $mq->publish(env('RABBITMQ_QUEUE'), $message);

        return response()->json([
            'status' => 'queued',
            'data'   => $message
        ], 201);
    }
}
