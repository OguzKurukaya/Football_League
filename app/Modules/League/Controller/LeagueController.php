<?php

namespace Leaguesim\League\Controller;

use Illuminate\Http\JsonResponse;
use Leaguesim\League\Service\LeagueService;

class LeagueController
{

    public function getLeague(LeagueService $service): JsonResponse
    {
        if (!$service->getLeague())
        {
            return response()->json(
                [
                    'flag' => 'error',
                    'code' => 500,
                    'message' => 'Error getting league',
                    'data' => null
                ]
            );
        }

        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'League retrieved',
                'data' => $service->teams->sortBy([['points', 'desc'], ['avarage', 'desc']])->values()
            ]
        );
    }

    public function getPrediction(LeagueService $service): JsonResponse
    {
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'Prediction retrieved',
                'data' => $service->getPrediction()
            ]
        );
    }
}
