<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';
    /**
     * @var string
     */
    protected $description = 'Welcome message';
    /**
     * @var string
     */
    protected $usage = '/start';
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
        $from = $message->getFrom();

        $welcomeText = 'Hello, ' . $from->getUsername() . '! ' . PHP_EOL;
        $welcomeText .= 'This is ShoZaSong. Try using /search to find some songs.';
        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => $welcomeText,
        ]);
    }
}