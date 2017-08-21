<?php

namespace ShoZaSong\Bot\Response;

class ResponseVoiceWrong extends Response
{
    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $chatId = $this->getMessage()->getChat()->getId();
        return $this->responseFactory->sendMessage($chatId, '*Жаль, а что же вы искали?*', 'MARKDOWN');
    }
}