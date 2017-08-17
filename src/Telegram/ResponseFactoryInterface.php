<?php

namespace ShoZaSong\Bot\Telegram;

interface ResponseFactoryInterface
{
    /**
     * @param int $chatId
     * @param string $text
     * @param string|null $parseMode
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendMessage($chatId, $text, $parseMode = null);

    /**
     * @param int $chatId
     * @param string $url
     * @param string $caption
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendPhotoByUrl($chatId, $url, $caption = '');

    /**
     * @param int $chatId
     * @param string $filePath
     * @param string $caption
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendAudio($chatId, $filePath, $caption = '');

    /**
     * @param int $chatId
     * @param string $action
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendAction($chatId, $action);

    /**
     * @param int $chatId
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendActionTyping($chatId); // typing

    /**
     * @param int $chatId
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendActionUploadPhoto($chatId); // upload_photo

    /**
     * @param int $chatId
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    public function sendActionUploadAudio($chatId); // upload_audio
}