<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeOrders extends Command
{
    protected $signature = 'consume:orders';
    protected $description = 'Consume order messages from RabbitMQ';

    public function handle()
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),      // host
            env('RABBITMQ_PORT'),      // port
            env('RABBITMQ_USER'),      // username
            env('RABBITMQ_PASSWORD'),  // password
            '/',                       // vhost
            false,                     // insist
            'AMQPLAIN',                // login_method
            null,                      // login_response
            'en_US',                   // locale
            30,                        // read_timeout
            30,                        // write_timeout
            null,                      // context
            0,                         // keepalive (0 = disabled per Windows)
            10                         // heartbeat
        );



        $channel = $connection->channel();
        $channel->queue_declare(env('RABBITMQ_QUEUE'), false, true, false, false);

        $this->info("Waiting for messages...");

        $callback = function ($msg) {

            $data = json_decode($msg->body, true);
            $this->info("Received: " . $msg->body);

            Order::updateOrCreate(
                ['order_id' => $data['order_id']],
                [
                    'customer'         => $data['customer'],
                    'total'            => $data['total'],
                    'order_created_at' => $data['created_at'] ?? now(),
                ]
            );

            // ACK compatibile con tutte le versioni
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };


        $channel->basic_consume(
            env('RABBITMQ_QUEUE'),
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while (true) {
            $channel->wait();
        }
    }
}
