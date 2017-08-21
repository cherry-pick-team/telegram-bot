<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use ShoZaSong\Bot\Response\ResponseSearch;
use ShoZaSong\Bot\Response\ResponseVoice;
use ShoZaSong\Bot\Response\ResponseVoiceWrong;

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

        if (ResponseVoice::TEXT_ON_WRONG) {
            $responseVoiceWrong = new ResponseVoiceWrong($this->getMessage());
            return $responseVoiceWrong->send();
        }

        $responseSearch = new ResponseSearch($this->getMessage());

        $text = $this->getMessage()->getText();
        $isVoice = false;

        if (preg_match('/^"(.+)"$/', $text, $matches)) {
            $text = $matches[1];
            $isVoice = true;
        }

        $responseSearch->setPhrase($text);
        $responseSearch->setVoice($isVoice);
        return $responseSearch->send();
    }
}