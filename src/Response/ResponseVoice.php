<?php

namespace ShoZaSong\Bot\Response;

use Longman\TelegramBot\Entities\File;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class ResponseVoice extends Response
{
    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $chatId = $this->getMessage()->getChat()->getId();

        $voice = $this->getMessage()->getVoice();
        $fileId = $voice->getFileId();
        $voiceData = Request::getFile(['file_id' => $fileId,]);

        if ($voiceData->isOk()) {

            $voiceFile = $voiceData->getResult();
            /**
             * @var File $voiceFile
             */
            $isOk = Request::downloadFile($voiceFile);

            if ($isOk) {
                $this->responseFactory->sendMessage($chatId, 'Мы получили звуковое сообщение...');

                $filePath = $this->telegram->getDownloadPath() . $voiceData->getFilePath();

                $this->responseFactory->sendMessage($chatId, 'File saved: ' . $filePath);
//                $ourApi = new OurApi;
//                $ourApi->searchByVoice($filePath);
            }
        }

        return $this->responseFactory->sendMessage($chatId, 'Что-то пошло не так...');
    }

    /**
     * @param Telegram $telegram
     */
    public function setTelegram($telegram)
    {
        $this->telegram = $telegram;
    }
}