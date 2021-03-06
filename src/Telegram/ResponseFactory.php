<?php

namespace ShoZaSong\Bot\Telegram;

use Longman\TelegramBot\Request;

class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @var bool
     */
    protected $removeKeyboard = false;

    /**
     * {@inheritdoc}
     */
    public function sendMessage($chatId, $text, $parseMode = null, array $args = [])
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $text,
        ];

        if (null !== $parseMode) {
            $data['parse_mode'] = $parseMode;
        }

        $data = array_replace($data, $args);

        if ($this->removeKeyboard && !array_key_exists('reply_markup', $data)) {
            $data['reply_markup'] = [
                'remove_keyboard' => true,
            ];
            $this->removeKeyboard = false;
        }

        return Request::sendMessage($data);
    }

    /**
     * {@inheritdoc}
     */
    public function sendPhoto($chatId, $photo, $caption = '', array $args = [])
    {
        $data = [
            'chat_id' => $chatId,
            'photo' => $photo,
        ];

        if (!empty($caption)) {
            $data['caption'] = $caption;
        }

        $data = array_replace($data, $args);

        if ($this->removeKeyboard && !array_key_exists('reply_markup', $data)) {
            $data['reply_markup'] = [
                'remove_keyboard' => true,
            ];
            $this->removeKeyboard = false;
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

        if (!empty($caption)) {
            $data['caption'] = $caption;
        }

        $data = array_replace($data, $args);

        if ($this->removeKeyboard && !array_key_exists('reply_markup', $data)) {
            $data['reply_markup'] = [
                'remove_keyboard' => true,
            ];
            $this->removeKeyboard = false;
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

    /**
     * @param mixed $removeKeyboard
     */
    public function setRemoveKeyboard($removeKeyboard)
    {
        $this->removeKeyboard = $removeKeyboard;
    }
}