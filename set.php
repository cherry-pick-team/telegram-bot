<?php

$bot_api_key = getenv('TELEGRAM_BOT_KEY');
$bot_username = getenv('TELEGRAM_BOT_NAME');

$hook_url = 'https://zsong.ru/telegram-bot';
try {
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}