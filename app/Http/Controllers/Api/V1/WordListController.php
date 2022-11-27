<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\WordListRequest;
use App\Http\Resources\V1\WordResource;
use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
     * Fetch wordlist from external URL and store aquired words into DB.
     *
     * @param WordListRequest $request
     * @return JsonResponse
     */
    public function requestImport(WordListRequest $request): JsonResponse
    {
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
