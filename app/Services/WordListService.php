<?php

namespace App\Services;

use App\Services\FileParserService;
use App\Actions\HashCreator;
use App\Models\Word;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WordListService
{
    /**
     * FileParserService.
     *
     * @var FileParserService
     */
    private FileParserService $fileParser;

    /**
     * HashCreator.
     *
     * @var HashCreator
     */
    private HashCreator $hashCreator;

    /**
     * DB chunk size.
     *
     * @var int
     */
    private const CHUNK_SIZE = 10000;

    /**
     * Constructor.
     *
     * @param FileParserService $fileParser
     * @param HashCreator $hashCreator
     */
    public function __construct(FileParserService $fileParser, HashCreator $hashCreator)
    {
        $this->fileParser = $fileParser;
        $this->hashCreator = $hashCreator;
    }

    /**
     * Import and save words list into the DB.
     *
     * @param String $url
     * @return bool
     */
    public function import(String $url): bool
    {
        try {
            $wordsArray = $this->fileParser->parseFile($url);
            // save words into the DB
            $this->store($wordsArray);

            return true;
        } catch (\Throwable $th) {
            Log::error($th);

            return false;
        }
    }

    /**
     * Save words array into the DB.
     *
     * @param array $words
     * @return void
     */
    private function store(array $words): void
    {
        if(empty($words)){
            return;
        }

        try {
            // TODO: get transaction working
            DB::beginTransaction();
//            DB::table('wordlist')->truncate();
            DB::table('wordlist')->delete();

            foreach (array_chunk($words, self::CHUNK_SIZE) as $chunk) {
                $arr = [];

                foreach ($chunk as $word) {
                    $arr[] = [
                        'word' => $word,
                        'hash' => $this->hashCreator->create($word)
                    ];
                }

                DB::table('wordlist')->insertOrIgnore($arr);
                Log::debug('Insert wordlist into DB succeeded !');
            }
            DB::commit();
        }catch (\Throwable $th){
            DB::rollBack();
            Log::error($th);
            Log::debug('Insert wordlist into DB failed !');
        }
    }
}
