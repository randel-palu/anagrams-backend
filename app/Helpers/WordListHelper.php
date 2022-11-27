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
    public function importList(String $url = self::DEFAULT_FILE_URL)
    {
        try {
            Log::debug('Importing wordlist...');

            //TODO: add url and file format check
            $commaSeparated = trim(str_replace(["\r\n", "\n", "\r"], ',', file_get_contents($url)), ',');
            $wordArray = explode(',', $commaSeparated);

            if(empty($wordArray)) {
                return false;
            }

            $this->insertListIntoDB($wordArray);

            Log::debug('Import was successful!');
        } catch (\Throwable $th) {
            Log::error($th);
            return false;
        }

        return true;
    }

    /**
     * Insert wordlist into a DB
     *
     * @param  array $words
     */
    public function insertListIntoDB(Array $words): void
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
    public function createHash(String $word)
    {
        if($word === ''){
            return '';
        }

        $source = mb_str_split($word);
        sort($source);

        return implode('', $source);
    }
}
