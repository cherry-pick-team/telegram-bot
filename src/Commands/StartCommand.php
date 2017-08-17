<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use ShoZaSong\Bot\Response\ResponseHelp;
use ShoZaSong\Bot\Response\ResponseWelcome;

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
    protected $version = '0.2.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $responseWelcome = new ResponseWelcome($this->getMessage());
        $responseWelcome->send();
        $responseHelp = new ResponseHelp($this->getMessage());
        return $responseHelp->send();
    }
}