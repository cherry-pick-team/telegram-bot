<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/search" command
 */
class SearchCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'search';
    /**
     * @var string
     */
    protected $description = 'Search for a song by lyrics words';
    /**
     * @var string
     */
    protected $usage = '/search <words>';
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
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $words = trim($message->getText(true));

        if ($words !== '') {
            $result = $this->getSearchResults($words);

            if ($result === null) {
                return Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Something is wrong with service, please try your query again later',
                ]);
            } elseif (empty($result)) {
                return Request::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'We couldn\'t find any songs... Sorry :-(',
                ]);
            } else {
                $i = 0;
                foreach ($result as $song) {
                    if (++$i > 3) {
                        return Request::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'More search results: ' . $this->getSearchUrl($words),
                        ]);
                    }

                    $caption = '"' . $song['title'] . '" by ' . $song['author'];

                    $coverUrl = $song['album']['cover_url'];

                    $tmpFile = tempnam(sys_get_temp_dir(), 'album_cover_url');
                    file_put_contents($tmpFile, file_get_contents($coverUrl));

                    Request::sendChatAction([
                        'chat_id' => $chat_id,
                        'action' => 'typing',
                    ]);

                    Request::sendPhoto([
                        'chat_id' => $chat_id,
                        'caption' => $caption . ' (Album "' . $song['album']['name'] . '")',
                    ], $tmpFile);

                    Request::sendChatAction([
                        'chat_id' => $chat_id,
                        'action' => 'typing',
                    ]);

                    sleep(3);
                }

                return Request::sendMessage([
                    'chat_id' => $chat_id,
                    'parse_type' => 'markdown',
                    'text' => 'That\'s all! Full search results: [' . $words . '](' . $this->getSearchUrl($words) . ')',
                ]);
            }
        }

        $usage = 'Command usage: ' . $this->getUsage();
        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => $usage,
        ]);
    }

    /**
     * @param string $query
     * @return null
     */
    protected function getSearchResults($query)
    {
        $response = file_get_contents(
            $this->getApiSearchUrl($query)
        );

        if ($response) {
            $response = json_decode($response, true);

            if ($response && is_array($response) && count($response) > 0) {
                if (array_key_exists('code', $response) &&
                    array_key_exists('fields', $response) &&
                    array_key_exists('message', $response)
                ) {
                    return [];
                }

                return $response;
            }
        }

        return null;
    }

    /**
     * @param string $query
     * @return string
     */
    protected function getApiSearchUrl($query)
    {
        return 'https://zsong.ru/api/v2/search?query=' . urlencode($query);
    }

    /**
     * @param string $query
     * @return string
     */
    protected function getSearchUrl($query)
    {
        return 'https://zsong.ru/search/' . rawurlencode($query);
    }
}