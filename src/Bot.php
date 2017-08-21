<?php

namespace ShoZaSong\Bot;

use Longman\TelegramBot\Telegram;

class Bot implements BotInterface
{
    /**
     * @var Telegram
     */
    protected $telegram;

    /**
     * Bot constructor.
     */
    public function __construct($downloadsPath = null)
    {
        $this->initTelegram();

        if (null === $downloadsPath) {
            $downloadsPath = sys_get_temp_dir();
        }

        $this->telegram->setDownloadPath($downloadsPath);
    }

    /**
     * Initializes Telegram object
     */
    protected function initTelegram()
    {
        $bot_api_key = getenv('TELEGRAM_BOT_KEY');
        $bot_username = getenv('TELEGRAM_BOT_NAME');

        $telegram = new Telegram($bot_api_key, $bot_username);
        $telegram->addCommandsPaths([
            __DIR__ . '/Commands',
        ]);
        $telegram->enableLimiter();

        $this->telegram = $telegram;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->telegram->handle();
    }
}