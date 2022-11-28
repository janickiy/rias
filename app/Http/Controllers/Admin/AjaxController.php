<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\{Pages,News};
use App\Helpers\StringHelpers;
use URL;

class AjaxController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        if ($request->input('action')) {
            switch ($request->input('action')) {
                case 'get_content_slug':

                    $slug = StringHelpers::slug(trim($request->title));
                    $count = Pages::where('slug', 'LIKE%', $slug)->count();
                    $slug = $count > 0 ? $slug . ($count + 1) : $slug;

                    return response()->json(['slug' => $slug]);

                case 'get_news_slug':

                    $slug = StringHelpers::slug(trim($request->title));
                    $count = News::where('slug', 'LIKE%', $slug)->count();
                    $slug = $count > 0 ? $slug . ($count + 1) : $slug;

                    return response()->json(['slug' => $slug]);

            }
        }
    }
}
