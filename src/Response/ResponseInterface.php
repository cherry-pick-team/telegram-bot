<?php

namespace ShoZaSong\Bot\Response;

use Longman\TelegramBot\Entities\ServerResponse;

interface ResponseInterface
{
    /**
     * @return ServerResponse
     */
    public function send();
}