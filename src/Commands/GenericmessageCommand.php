<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Genericmessage';
    /**
     * @var string
     */
    protected $description = 'Handle generic message';
    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $voice = $message->getVoice();

        if ($voice) {
            Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Слушаем...',
            ]);

            Request::sendChatAction([
                'chat_id' => $chat_id,
                'action' => 'typing',
            ]);

            $file = Request::getFile([
                'file_id' => $voice->getFileId(),
            ]);

            if ($file->isOk()) {
                $voiceFileUrl = $file->getResult();
                Request::downloadFile($voiceFileUrl);
            }
        }

        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => sprintf('Получили "%s"', var_export($message, true)),
        ]);
    }
}