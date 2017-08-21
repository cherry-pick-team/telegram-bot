<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

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
        $this->getUpdate()->getCallbackQuery();

        Request::sendMessage(['chat_id' => $this->getUpdate()->getMessage()->getChat()->getId(), 'text' => var_export($this->getUpdate(), 1)]);

        return Request::answerCallbackQuery(['callback_query_id' => $this->getUpdate()->getCallbackQuery()->getId()]);
    }

}