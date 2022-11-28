<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WordListRequest;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use App\Helpers\WordListHelper;

class WordListController extends Controller
{
    /**
     * Pagination size.
     *
     * @var int
     */
    private const PAGINATION_SIZE = 30;

    /**
     * @OA\Get(
     *     path="/api/V1/wordlist",
     *     summary="Get the paginated wordlist.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="page",
     *                     type="integer"
     *                 ),
     *                 example={"page": 3}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"current_page": 3,
     *                          "data": {
     *                              {
     *                                  "id":61,
     *                                  "word":"aadlipreili",
     *                                  "hash":"aadeiiillpr"
     *                              },
     *                              {
     *                                  "id": 62,
     *                                  "word": "aadlipäritolu",
     *                                  "hash": "aadiilloprtuä"
     *                              }
     *                          },
     *                          "first_page_url": "http://localhost/api/v1/wordlist?page=1",
     *                          "from": 61,
     *                          "last_page": 5935,
     *                          "last_page_url": "http://localhost/api/v1/wordlist?page=5935",
     *                          "links": {
     *                              {
     *                                  "url":"http://localhost/api/v1/wordlist?page=2",
     *                                  "label":"&laquo; Previous",
     *                                  "active":"false"
     *                              },
     *                              {
     *                                  "id": "http://localhost/api/v1/wordlist?page=1",
     *                                  "label": "1",
     *                                  "active": "false"
     *                              }
     *                          },
     *                          "next_page_url": "http://localhost/api/v1/wordlist?page=4",
     *                          "path": "http://localhost/api/v1/wordlist",
     *                          "per_page": 30,
     *                          "prev_page_url": "http://localhost/api/v1/wordlist?page=2",
     *                          "to": 90,
     *                          "total": 178028,
     *                  },
     *                  summary=""
     *              )
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $res = Word::query()->paginate(self::PAGINATION_SIZE);

        return response()->json($res, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/V1/wordlist",
     *     summary="Post the wordlist URL for data import.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="url",
     *                     type="string"
     *                 ),
     *                 example={"url":"http://www.eki.ee/tarkvara/wordlist/lemmad2013.txt"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"message": "wordlist imported successfully"},
     *                  summary=""
     *              )
     *         )
     *     )
     * )
     *
     * Fetch wordlist from external URL and store aquired words into DB.
     *
     * @param WordListRequest $request
     * @return JsonResponse
     */
    public function requestImport(WordListRequest $request): JsonResponse
    {
        // TODO: checks to see if valid URL, and file can be accessed
        // TODO: file and text format checks
        $wordlistUrl = $request->get('url');

        if ((new WordListHelper())->importList($wordlistUrl)) {
            return response()->json([
                'message' => 'wordlist imported successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'wordlist import failed'
        ], 500);
    }
}
