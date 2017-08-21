<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use ShoZaSong\Bot\Response\ResponseSearch;

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
        $callback = $this->getCallbackQuery();
        $callbackId = $callback->getId();
        $callbackData = explode(':', $callback->getData());

        $text = '';
        switch ($callbackData[0]) {
            case ResponseSearch::FEEDBACK_YES:
                $text = 'Спасибо! 💕';
                break;
            case ResponseSearch::FEEDBACK_NO:
                $text = 'Виноваты, исправимся! 🙄';
                break;
        }

        return Request::answerCallbackQuery(['callback_query_id' => $callbackId, 'text' => $text,]);
    }

}