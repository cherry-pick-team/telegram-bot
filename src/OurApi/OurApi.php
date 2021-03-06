<?php

namespace ShoZaSong\Bot\OurApi;

use GuzzleHttp\Client;

class OurApi implements OurApiInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param string $phrase
     * @param bool $voice
     * @return array|null
     */
    public function search($phrase, $voice = false)
    {
        $uri = 'search';
        $args = [
            'query' => $phrase,
            'strict' => $voice ? '1' : '0',
        ];

        /**
         * @var \GuzzleHttp\Psr7\Response $response
         */
        $response = $this->getClient()->get($uri, [
            'query' => $args,
        ]);

        $arr = json_decode($response->getBody()->getContents(), true);

        if ($arr && is_array($arr) && count($arr) > 0) {
            if (array_key_exists('code', $arr) &&
                array_key_exists('fields', $arr) &&
                array_key_exists('message', $arr)
            ) {
                return [];
            }

            return $arr;
        }

        return null;
    }

    /**
     * @param string $file
     * @param string|null $fileName
     * @return array|\Psr\Http\Message\ResponseInterface
     */
    public function searchByVoice($file, $fileName = null)
    {
        $uri = 'search/voice';
        $response = $this->getClient()->post($uri, [
            'multipart' => [
                [
                    'name' => 'voice',
                    'contents' => fopen($file, 'r'),
                    'filename' => $fileName === null ? basename($file) : $fileName,
                ],
            ],
        ]);

        $arr = json_decode($response->getBody()->getContents(), true);

        if ($arr && is_array($arr) && count($arr) > 0) {
            if (array_key_exists('code', $arr) &&
                array_key_exists('fields', $arr) &&
                array_key_exists('message', $arr)
            ) {
                return [];
            }

            return $arr;
        }

        return null;
    }

    /**
     * @param string $phrase
     * @param bool $voice
     * @return string
     */
    public function getFullSearchLink($phrase, $voice = false)
    {
        return 'https://zsong.ru/search/' . ($voice ? 'voice/' : '') . rawurlencode($phrase);
    }

    /**
     * @return string
     */
    protected function getBaseApiUrl()
    {
        return 'https://zsong.ru/api/v2/';
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client([
                'base_uri' => $this->getBaseApiUrl(),
            ]);
        }
        return $this->client;
    }

    /**
     * @param string $id
     * @param int $start
     * @param int $end
     * @return string
     */
    public function getCropUrl($id, $start, $end)
    {
        return "https://zsong.ru/crop/get_song/?id=$id&from_ms=$start&to_ms=$end";
    }
}