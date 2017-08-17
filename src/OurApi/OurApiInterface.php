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

    /**
     * @param string $id
     * @param int $start
     * @param int $end
     * @return string
     */
    public function getCropUrl($id, $start, $end);
}