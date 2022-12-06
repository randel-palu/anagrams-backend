<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\HashCreator;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AnagramRequest;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AnagramController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/V1/anagram",
     *     summary="Find the anagrams of the given word.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="word",
     *                     type="string"
     *                 ),
     *                 example={"word":"kabe"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "success": "true",
     *                      "data": {
     *                          {
     *                              "id":11355,
     *                              "word":"ebak",
     *                              "hash":"abek"
     *                          },
     *                          {
     *                              "id": 33269,
     *                              "word": "kabe",
     *                              "hash": "abek"
     *                          }
     *                      },
     *                  },
     *                  summary=""
     *              )
     *         )
     *     )
     * )
     *
     * Find all the anagrams of the requested word.
     *
     * @param AnagramRequest $request
     * @return JsonResponse
     */
    public function anagrams(AnagramRequest $request, HashCreator $hashCreator): JsonResponse
    {
        $requestedWord = $request->get('word');

        $searchableHash = $hashCreator->create($requestedWord);
        $anagrams = Word::query()->whereRaw("BINARY `hash`='$searchableHash'")->get();

        return response()->json([
           'data' => $anagrams,
           'success' => true
        ], 200);
    }
}
