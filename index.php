<?php

require_once __DIR__ . '/vendor/autoload.php';

$bot_api_key = getenv('TELEGRAM_BOT_KEY');
$bot_username = getenv('TELEGRAM_BOT_NAME');

try {
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    $telegram->addCommandsPaths([
        __DIR__ . '/Commands',
    ]);
    $telegram->enableLimiter();
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    Longman\TelegramBot\TelegramLog::error($e);
}