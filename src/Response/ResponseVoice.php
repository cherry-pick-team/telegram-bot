<?php

namespace ShoZaSong\Bot\Response;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use ShoZaSong\Bot\OurApi\OurApi;

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
            $isOk = Request::downloadFile($voiceFile);

            if ($isOk) {
                $this->responseFactory->sendMessage($chatId, 'Мы получили звуковое сообщение...');

                $filePath = $this->telegram->getDownloadPath() . $voiceFile->getFilePath();

                $this->responseFactory->sendMessage($chatId, 'File saved: ' . $filePath);
                $ourApi = new OurApi;
                $searchData = $ourApi->searchByVoice($filePath);

                $this->responseFactory->sendMessage($chatId, var_export($searchData, 1));
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