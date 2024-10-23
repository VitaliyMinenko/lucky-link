<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Dto\PageDto;
use App\Http\Dto\RegistrationDto;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomPageService
{
    /**
     * @param RegistrationDto $registrationDto
     * @return string
     */
    public function handle(RegistrationDto $registrationDto): string
    {
        $page = new Page();
        $page->uniqId = uniqid();
        $page->phone = $registrationDto->getPhone();
        $page->name = $registrationDto->getName();
        $page->save();

        return $this->getCustomUrl($page->uniqId);
    }

    /**
     * @param string $page
     * @return string
     */
    public function regenerate(string $page): string
    {
        $pageModel = Page::where('uniqId', $page)->first();
        $pageModel->uniqId = uniqid();
        $pageModel->save();

        return $this->getCustomUrl($pageModel->uniqId);
    }

    /**
     * @param $page
     * @return bool
     */
    public function deactivate($page): bool
    {
        try {
            $pageModel = Page::where('uniqId', $page)->firstOrFail();
            $pageModel->activated = false;

            return $pageModel->save();
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    /**
     * @param string $page
     * @return PageDto|null
     */
    public function getPage(string $page): ?PageDto
    {
        $dateThreshold = Carbon::now()->subDays(7);
        $pageModel = Page::where('uniqId', $page)
            ->where('created_at', '>=', $dateThreshold)
            ->where('activated', true)
            ->first(['name', 'phone', 'uniqId']);

        return $pageModel ? new PageDto(
            $pageModel->name,
            $pageModel->phone,
            $this->getCustomUrl($pageModel->uniqId),
            $pageModel->uniqId
        ) : null;
    }

    /**
     * @param string $uniqId
     * @return string
     */
    private function getCustomUrl(string $uniqId): string
    {
        $pageUrl = config('app.custom_url');
        $baseUrl = url('/');

        return $baseUrl . $pageUrl . $uniqId;
    }
}
