#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$token = $argv[1];

$conn = new \Rde\Telegram\Connection($token);

echo json_encode($conn->me), PHP_EOL;

$reply = new \Rde\Telegram\Structure();

$reply->{'chat_id'} = (int) $argv[2];

$reply->{'text'} = $argv[3];

echo $reply, PHP_EOL;

if ($conn->sendMessage($reply)) echo "\e[32mok\e[m", PHP_EOL;
