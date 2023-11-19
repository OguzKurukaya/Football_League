<?php

namespace Leaguesim\System\Controllers;

use Illuminate\Http\JsonResponse;
use Leaguesim\System\Services\SystemService;

class SystemController
{


    public function ping(): JsonResponse
    {
        return response()->json([
            'flag' => 'success',
            'code' => 200,
            'message' => 'pong'
        ]);
    }

    public function reset(SystemService $service): JsonResponse
    {
        if (!$service->resetAll())
        {
            return response()->json(
                [
                    'flag' => 'error',
                    'code' => 500,
                    'message' => 'Error resetting'
                ]
            );
        }
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'Reset successful'
            ]
        );

    }

}
