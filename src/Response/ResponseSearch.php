<?php

namespace ShoZaSong\Bot\Response;

use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use ShoZaSong\Bot\OurApi\OurApi;

class ResponseSearch extends Response
{
    const FEEDBACK_YES = 'feedback_yes';
    const FEEDBACK_NO = 'feedback_no';

    /**
     * @var string
     */
    protected $phrase;

    /**
     * @var bool
     */
    protected $voice = false;

    /**
     * {@inheritdoc}
     */
    public function send()
    {
        $chatId = $this->getMessage()->getChat()->getId();

        if (trim($this->getPhrase()) === '') {
            return $this->responseFactory->sendMessage($chatId, 'Пустой поисковый запрос...');
        }

        $api = new OurApi;
        $searchResults = $api->search($this->getPhrase(), $this->isVoice());

        if ($searchResults === null) {
            return $this->responseFactory->sendMessage($chatId, 'С нашим сервисом что-то не так, попробуйте поискать чуть позже.');
        } elseif (empty($searchResults)) {
            return $this->responseFactory->sendMessage($chatId, 'Мы не смогли найти ни одной композиции... Сорян :-(');
        } else {
            $searchName = $this->isVoice() ? 'Голосовой поиск' : 'Поиск';
            $this->responseFactory->sendMessage($chatId, sprintf('*%s*: %s', $searchName, $this->getPhrase()), 'MARKDOWN');

            $hasMore = false;
            $i = 0;
            foreach ($searchResults as $song) {
                if (++$i > 3) {
                    $hasMore = true;
                    return $this->askFeedback($hasMore);
                }

                $caption = sprintf('"%s", %s (Альбом "%s")', $song['title'], $song['author'], $song['album']['name']);
                $coverUrl = $song['album']['cover_url'];

                $this->responseFactory->sendActionUploadPhoto($chatId);
                $this->responseFactory->sendPhoto($chatId, $coverUrl, $caption, [
                    'disable_notification' => true,
                ]);
                $this->responseFactory->sendActionTyping($chatId);

                $chunks = $song['lyrics_chunks'];
                $j = 0;
                foreach ($chunks as $chunk) {
                    if (++$j > 2) {
                        $hasMore = true;
                        break;
                    }

                    $cropUrl = $api->getCropUrl($song['mongo_id'], $chunk['start'], $chunk['end']);
                    $chunkLyrics = implode(PHP_EOL, $chunk['lyrics']);

                    $audio = file_get_contents($cropUrl);

                    $tmpFile = tempnam(sys_get_temp_dir(), 'audio');
                    file_put_contents($tmpFile, $audio);

                    $this->responseFactory->sendAudio($chatId, Request::encodeFile($tmpFile), $chunkLyrics, [
                        'performer' => $song['author'],
                        'title' => $song['title'],
                        'disable_notification' => true,
                    ]);
                }

                sleep(1);
            }

            return $this->askFeedback($hasMore);
        }
    }

    /**
     * @param bool $hasMore
     * @return \Longman\TelegramBot\Entities\ServerResponse
     */
    protected function askFeedback($hasMore = false)
    {
        $chatId = $this->getMessage()->getChat()->getId();
        $api = new OurApi;

        $message = '*Вот и всё!* Нашлось?';
        $dataEnd = ':' . $this->getPhrase() . ':' . ($this->isVoice() ? '1' : '0');
        $buttons = [
            [
                'text' => '👍',
                'callback_data' => self::FEEDBACK_YES . $dataEnd,
            ],
            [
                'text' => '👎',
                'callback_data' => self::FEEDBACK_NO . $dataEnd,
            ],
        ];

        if ($hasMore) {
            $buttons[] = [
                'text' => 'Больше результатов',
                'url' => $api->getFullSearchLink($this->getPhrase(), $this->isVoice()),
            ];
        }

        $keyboard = new InlineKeyboard([
            $buttons,
        ]);

        return $this->responseFactory->sendMessage($chatId, $message, 'MARKDOWN', [
            'reply_markup' => $keyboard,
        ]);
    }

    /**
     * @return string
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * @param string $phrase
     */
    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * @return bool
     */
    public function isVoice()
    {
        return $this->voice;
    }

    /**
     * @param bool $voice
     */
    public function setVoice($voice)
    {
        $this->voice = $voice;
    }
}