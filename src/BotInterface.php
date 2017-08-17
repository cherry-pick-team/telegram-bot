<?php

namespace ShoZaSong\Bot;

interface BotInterface
{
    /**
     * Runs bot
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function run();
}