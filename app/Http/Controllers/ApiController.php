<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Dto\RegistrationDto;
use App\Http\Services\LuckyService;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use JMS\Serializer\SerializerInterface;
use App\Http\Requests\RegistrationRequest;
use App\Http\Services\CustomPageService;

class ApiController extends Controller
{
    public function __construct(
        private readonly CustomPageService $service,
        private readonly LuckyService $luckyServiceService,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $registrationDto = $this->serializer->deserialize(
            json_encode($request->validated()),
            RegistrationDto::class,
            'json'
        );

        return response()->json(
            $this->service->handle($registrationDto)
        );
    }

    public function lucky(Request $request): JsonResponse
    {
        $page = $request->get('page');
        $drawResult = $this->luckyServiceService->handle($page);

        return response()->json(
            $this->serializer->serialize($drawResult, 'json')
        );
    }

    public function history(Request $request): JsonResponse
    {
        $page = $request->get('page');
        $page = Page::where('uniqId', $page)->first();
        $lastDraws = Cache::get($page->id, []);

        return response()->json(
            $this->serializer->serialize($lastDraws, 'json')
        );
    }
}
