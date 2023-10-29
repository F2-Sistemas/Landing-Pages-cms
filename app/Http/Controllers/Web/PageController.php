<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page\Page;

class PageController extends Controller
{
    public function show(Request $request, string $pageSlug)
    {
        $query = Page::where('slug', $pageSlug)
            ->whereNot('published', false);

        $page = $query->firstOrFail();

        if ($page->only_auth) {
            abort_unless(auth()->user(), 404);
        }

        $view = $page?->getView() ?: 'tail-single::pages.landing_01';

        abort_unless($view, 404, __('models.errors.common.erros.generic_error_title'));

        return view($view, [
            'page' => $page,
        ]);
    }
}
