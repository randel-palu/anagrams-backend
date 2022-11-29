<?php

namespace App\Helpers;

use App\Models\Word;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WordListHelper
{
    private const DEFAULT_FILE_URL = 'http://www.eki.ee/tarkvara/wordlist/lemmad2013.txt';

    private const CHUNK_SIZE = 10000;

    /**
     * Import list of words from external .txt file.
     *
     * @param  string $url
     * @return bool
     */
    public function importList(String $url = self::DEFAULT_FILE_URL): bool
    {
        try {
            Log::debug('Importing wordlist...');

            $fileContents = file_get_contents($url);

            if(gettype($fileContents) !== "string"){
                return false;
            }

            $wordArray = $this->createWordArray($fileContents);

            if(empty($wordArray)) {
                return false;
            }

            $this->insertListIntoDB($wordArray);

            Log::debug('Import was successful!');
            return true;
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }
    }

    /**
     * Return a word array from a string(where the words are separated by EOL).
     *
     * @param  string $content
     * @return array
     */
    private function createWordArray(string $content): array
    {
        $commaSeparated = trim(str_replace(["\r\n", "\n", "\r"], ',', $content), ',');
        return explode(',', $commaSeparated);
    }

    /**
     * Insert wordlist into a DB
     *
     * @param  array $words
     */
    private function insertListIntoDB(Array $words): void
    {
        try {
            Word::truncate();

            foreach (array_chunk($words, self::CHUNK_SIZE) as $chunk) {
                $arr = [];

                foreach ($chunk as $word) {
                    $arr[] = [
                        'word' => $word,
                        'hash' => $this->createHash($word)
                    ];
                }

                DB::table('wordlist')->insertOrIgnore($arr);
            }
        }catch (\Throwable $th){
            Log::error($th);
            Log::debug('Insert wordlist into DB failed!');
        }
    }

    /**
     * Create a hash from a word.
     *
     * @param  string $word
     * @return string
     */
    public function createHash(String $word): string
    {
        if($word === ''){
            return '';
        }

        $source = mb_str_split($word);
        sort($source);

        return implode('', $source);
    }
}
