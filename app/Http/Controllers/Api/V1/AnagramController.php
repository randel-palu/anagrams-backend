<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AnagramRequest;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Helpers\WordListHelper;

class AnagramController extends Controller
{
    /**
     * Find all the anagrams of the requested word.
     *
     * @param AnagramRequest $request
     * @return JsonResponse
     */
    public function anagrams(AnagramRequest $request): JsonResponse
    {
        $requestedWord = $request->get('word');

        $searchableHash = (new WordListHelper())->createHash($requestedWord);
        $anagrams = Word::query()->whereRaw("BINARY `hash`='$searchableHash'")->get();

        return response()->json([
           'message' => 'success',
           'data' => $anagrams,
           'status' => 200
        ], 200);
    }
}
