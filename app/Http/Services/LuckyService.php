<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Dto\DrawDto;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class LuckyService
{
    private const MIN_NUMBER = 1;
    private const MAX_NUMBER = 1000;
    private const THRESHOLD_HIGH = 900;
    private const THRESHOLD_MEDIUM = 600;
    private const THRESHOLD_LOW = 300;
    private const PERCENTAGE_HIGH = 0.7;
    private const PERCENTAGE_MEDIUM = 0.5;
    private const PERCENTAGE_LOW = 0.3;
    private const PERCENTAGE_MIN = 0.1;

    /**
     * @param string $page
     * @return DrawDto
     */
    public function handle(string $page): DrawDto
    {
        $page = Page::where('uniqId', $page)->first();
        $drawResult = $this->draw();
        $this->storeInCache($drawResult, $page);

        return $drawResult;
    }

    /**
     * @return DrawDto
     */
    private function draw(): DrawDto
    {
        $number = rand(self::MIN_NUMBER, self::MAX_NUMBER);
        $winner = false;

        if ($number % 2 === 0) {
            $winner = true;
            if ($number > self::THRESHOLD_HIGH) {
                $amount = $number * self::PERCENTAGE_HIGH;
            } elseif ($number > self::THRESHOLD_MEDIUM) {
                $amount = $number * self::PERCENTAGE_MEDIUM;
            } elseif ($number > self::THRESHOLD_LOW) {
                $amount = $number * self::PERCENTAGE_LOW;
            } else {
                $amount = $number * self::PERCENTAGE_MIN;
            }
        } else {
            $amount = 0;
        }

        return new DrawDto($number, $winner, $amount);
    }

    /**
     * @param DrawDto $drawResult
     * @param Page $page
     * @return void
     */
    private function storeInCache(DrawDto $drawResult, Page $page): void
    {
        $lastDraws = Cache::get($page->id, []);
        $lastDraws[] = $drawResult;
        if (count($lastDraws) > 3) {
            array_shift($lastDraws);
        }
        Cache::put($page->id, $lastDraws);
    }
}
