<?php

require_once __DIR__ . '/vendor/autoload.php';

try {
    $bot = new \ShoZaSong\Bot\Bot(__DIR__ . '/downloads');
    $bot->run();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    Longman\TelegramBot\TelegramLog::error($e);
}