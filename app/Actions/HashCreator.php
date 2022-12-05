<?php

namespace App\Actions;

class HashCreator
{
    /**
     * Create a hash from a word.
     *
     * @param  string $word
     * @return string
     */
    public function create(String $word): string
    {
        if($word === ''){
            return '';
        }

        $source = mb_str_split($word);
        sort($source);

        return implode('', $source);
    }
}
