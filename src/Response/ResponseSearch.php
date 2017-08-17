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

        $api = new OurApi;
        $searchResults = $api->search($this->getPhrase(), $this->isVoice());

        if ($searchResults === null) {
            return $this->responseFactory->sendMessage($chatId, 'С нашим сервисом что-то не так, попробуйте поискать чуть позже.');
        } elseif (empty($result)) {
            return $this->responseFactory->sendMessage($chatId, 'Мы не смогли найти ни одной композиции... Сорян :-(');
        } else {
            $i = 0;
            foreach ($result as $song) {
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

                $tmpFile = tempnam(sys_get_temp_dir(), 'album_cover_url');
                file_put_contents($tmpFile, file_get_contents($coverUrl));

                $this->responseFactory->sendActionUploadPhoto($chatId);
                $this->responseFactory->sendPhoto($chatId, $tmpFile, $caption);
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