<?php

namespace App\Services;

class FileParserService
{
    /**
     * Accepted file extensions.
     *
     * @var array
     */
    private const ACCEPTED_EXTENSION = ['txt'];

    /**
     * Parse text file.
     *
     * @param  string $url
     * @return array
     */
    public function parseFile(string $url): array
    {
        // current file extension not-allowed
        if(!in_array(pathinfo($url)['extension'], self::ACCEPTED_EXTENSION, true)){
            return [];
        }

        $fileContents = $this->fetchFileContent($url);

        return $this->createWordsArray($fileContents);
    }

    /**
     * Return words array from a string(where the words are separated by EOL).
     *
     * @param  string $content
     * @return array
     */
    private function createWordsArray(string $content): array
    {
        // TODO: use mb_split() instead
        $commaSeparated = trim(str_replace(["\r\n", "\n", "\r"], ',', $content), ',');

        return explode(',', $commaSeparated);
    }

    /**
     * Fetch file content.
     *
     * @param string $url
     * @return false|string
     */
    private function fetchFileContent(string $url): false|string
    {
        return file_get_contents($url);
    }
}
