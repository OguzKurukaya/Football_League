<?php

namespace Leaguesim\League\Controller;

use Illuminate\Http\JsonResponse;
use Leaguesim\League\Service\FixtureService;
use Leaguesim\League\Service\TeamsCrudService;

class TeamController
{


    public function getAllTeams(TeamsCrudService $service): JsonResponse
    {
        return response()->json([
            'flag' => 'success',
            'code' => 200,
            'message' => 'Teams retrieved successfully',
            'data' => $service->getAllTeams()
        ]);
    }

}
