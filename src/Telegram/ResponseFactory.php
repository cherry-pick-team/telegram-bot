<?php

namespace ShoZaSong\Bot\Telegram;

use Longman\TelegramBot\Request;

class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function sendMessage($chatId, $text, $parseMode = null)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        if (null !== $parseMode) {
            $data['parse_mode'] = $parseMode;
        }

        return Request::sendMessage($data);
    }

    /**
     * {@inheritdoc}
     */
    public function sendPhoto($chatId, $photo, $caption = '')
    {
        $data = [
            'chat_id' => $chatId,
            'photo' => $photo,
        ];

        if (!empty($caption)) {
            $data['caption'] = $caption;
        }

        return Request::sendPhoto($data);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAudio($chatId, $audio, $caption = '', array $args = [])
    {
        $data = [
            'chat_id' => $chatId,
            'audio' => $audio,
        ];

        $data = array_replace($data, $args);

        if (!empty($caption)) {
            $data['caption'] = $caption;
        }

        return Request::sendAudio($data);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAction($chatId, $action)
    {
        return Request::sendChatAction([
            'chat_id' => $chatId,
            'action' => $action,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function sendActionTyping($chatId)
    {
        return $this->sendAction($chatId, 'typing');
    }

    /**
     * {@inheritdoc}
     */
    public function sendActionUploadPhoto($chatId)
    {
        return $this->sendAction($chatId, 'upload_photo');
    }

    /**
     * {@inheritdoc}
     */
    public function sendActionUploadAudio($chatId)
    {
        return $this->sendAction($chatId, 'upload_audio');
    }
}