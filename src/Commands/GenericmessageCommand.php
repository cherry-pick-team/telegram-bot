<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use ShoZaSong\Bot\Response\ResponseSearch;
use ShoZaSong\Bot\Response\ResponseVoice;

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
        if ($this->getMessage()->getVoice()) {
            $responseVoice = new ResponseVoice($this->getMessage());
            $responseVoice->setTelegram($this->getTelegram());
            return $responseVoice->send();
        }

        $responseSearch = new ResponseSearch($this->getMessage());
        $responseSearch->setPhrase($this->getMessage()->getText());
        return $responseSearch->send();
    }
}