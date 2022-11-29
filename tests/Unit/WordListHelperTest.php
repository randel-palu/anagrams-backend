<?php

namespace Tests\Unit;

use App\Helpers\WordListHelper;
use PHPUnit\Framework\TestCase;

class WordListHelperTest extends TestCase
{
    /**
     * Test createHash.
     * string(consisting of multibyte characters) should be returned sorted.
     *
     * @return void
     */
    public function test_that_hash_is_string_with_sorted_characters()
    {
        $sortedWord = (new WordListHelper())->createHash('b8½£{ø€ł€a$3ª£ˆæÓ');
        $this->assertEquals('$38ab{££ª½Óæøłˆ€€', $sortedWord);
    }

    /**
     * Test createWordArray.
     * EOL separated words in a string, should return an array of words.
     *
     * @return void
     */
    public function test_create_a_word_array_from_eol_separated_wordlist()
    {
        $input = "first\r\nsecond\nthird\r fourth";
        $expected = ['first', 'second', 'third', ' fourth'];

        $wordListHelperReflection = new \ReflectionClass(WordListHelper::class);
        $method = $wordListHelperReflection->getMethod('createWordArray');
        $result = $method->invoke(new (WordListHelper::class), $input);

        $this->assertEquals($expected, $result);
    }
}
