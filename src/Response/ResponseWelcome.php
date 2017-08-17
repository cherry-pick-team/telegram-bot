<?php

namespace ShoZaSong\Bot\Response;

use Longman\TelegramBot\Entities\ServerResponse;

class ResponseWelcome extends Response
{
    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $from = $this->getMessage()->getFrom();

        $welcomeText = 'Привет';

        $username = trim($from->getUsername());
        if (!empty($username)) {
            $welcomeText .= ', ' . $username;
        }

        $welcomeText .= '!' . PHP_EOL;
        $welcomeText .= 'Это ШоЗаСонг. Мы ищем музыкальные композиции по словам из них.';

        return $this->getResponseFactory()->sendMessage($chatId, $welcomeText);
    }
}