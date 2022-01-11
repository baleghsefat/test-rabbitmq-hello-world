<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// we declare the queue here, as well. 
// Because we might start the consumer before the publisher,
// we want to make sure the queue exists before we try to consume messages from it.
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

// messages are sent asynchronously from the server to the clients.

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
  };
  
$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

