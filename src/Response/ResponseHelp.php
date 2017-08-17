<?php

namespace ShoZaSong\Bot\Response;

class ResponseHelp extends Response
{
    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $chatId = $this->getMessage()->getChat()->getId();

        $helpTextParts = [
            'Чтобы найти песню, напишите несколько слов из нее. Например, отправьте сообщение "hello it\'s me".',
            'Кроме того, можно произнести слова и отправить запись голоса. Попробуйте!',
        ];
        $helpText = implode(PHP_EOL, $helpTextParts);
        return $this->getResponseFactory()->sendMessage($chatId, $helpText);
    }
}