<?php

namespace ShoZaSong\Bot\Response;

use ShoZaSong\Bot\OurApi\OurApi;

class ResponseSearch extends Response
{
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
            $i = 0;
            foreach ($searchResults as $song) {
                if (++$i > 3) {
                    return $this->responseFactory->sendMessage($chatId,
                        sprintf('Больше песен: [%s](%s)',
                            $this->getPhrase(),
                            $api->getFullSearchLink($this->getPhrase())
                        ),
                        'MARKDOWN'
                    );
                }

                $caption = sprintf('"%s", %s (Альбом "%s")', $song['title'], $song['author'], $song['album']['name']);
                $coverUrl = $song['album']['cover_url'];

                $this->responseFactory->sendActionUploadPhoto($chatId);
                $this->responseFactory->sendPhoto($chatId, $coverUrl, $caption);
                $this->responseFactory->sendActionTyping($chatId);

                sleep(3);
            }

            return $this->responseFactory->sendMessage($chatId,
                sprintf('Вот и всё! Больше результатов: [%s](%s)',
                    $this->getPhrase(),
                    $api->getFullSearchLink($this->getPhrase())
                ),
                'MARKDOWN'
            );
        }
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