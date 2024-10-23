<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Services\CustomPageService;
use Inertia\Response as InteriaResponse;
use Symfony\Component\HttpFoundation\Response as sResponse;

class CustomPageController extends Controller
{
    public function __construct(
        private readonly CustomPageService $service,
    ) {
    }

    public function regenerate(string $page): sResponse
    {
        $url = $this->service->regenerate($page);

        return Inertia::location($url);
    }

    public function deactivate(string $page): sResponse
    {
        $deactivated = $this->service->deactivate($page);
        if ($deactivated) {
            return Inertia::location('/');
        } else {
            return back();
        }
    }

    public function show(string $page): InteriaResponse
    {
        $page = $this->service->getPage($page);

        if (!$page) {
            abort(404);
        }

        return Inertia::render('Page', [
            'url' => $page->getUrl(),
            'page' => $page->getPage(),
            'name' => $page->getName(),
            'phone' => $page->getPhone(),
        ]);
    }
}
