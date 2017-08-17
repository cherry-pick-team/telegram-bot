<?php

namespace ShoZaSong\Bot\OurApi;

interface OurApiInterface
{
    /**
     * @param string $phrase
     * @param bool $voice
     * @return array
     */
    public function search($phrase, $voice = false);

    /**
     * @param string $file
     * @return array
     */
    public function searchByVoice($file);

    /**
     * @param string $phrase
     * @param bool $voice
     * @return string
     */
    public function getFullSearchLink($phrase, $voice = false);
}