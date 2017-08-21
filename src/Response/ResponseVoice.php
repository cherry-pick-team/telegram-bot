<?php

namespace ShoZaSong\Bot\Response;

use Longman\TelegramBot\Request;

class ResponseVoice extends Response
{
    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $this->responseFactory->sendMessage($chatId, 'Мы начинаем обрабатывать звуковое сообщение...');

        $voice = $this->getMessage()->getVoice();
        $fileId = $voice->getFileId();
        $voiceData = Request::getFile(['file_id' => $fileId,]);

        if ($voiceData->isOk()) {
            $voiceFile = $voiceData->getResult();
            $isOk = Request::downloadFile($voiceFile);

            if ($isOk) {
                $this->responseFactory->sendMessage($chatId, 'Мы получили звуковое сообщение...');
            }
        }

        return $this->responseFactory->sendMessage($chatId, 'Едем дальше?');
    }
}