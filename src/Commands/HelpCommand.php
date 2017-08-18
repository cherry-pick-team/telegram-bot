<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use ShoZaSong\Bot\Response\ResponseHelp;

class HelpCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'help';
    /**
     * @var string
     */
    protected $description = 'Help message';
    /**
     * @var string
     */
    protected $usage = '/help';
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
        $responseHelp = new ResponseHelp($this->getMessage());
        return $responseHelp->send();
    }
}