<?php

namespace Leaguesim\League\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Leaguesim\League\Service\FixtureService;
use Illuminate\Support\Facades\Validator;

class FixtureController
{

    public function generateFixture(FixtureService $service): JsonResponse
    {

        if (!$service->generateFixture()) {
            return response()->json(
                [
                    'flag' => 'error',
                    'code' => 500,
                    'message' => 'Error generating fixture'
                ]
            );
        }
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'Simulation Created'
            ]
        );
    }

    public function playNextWeek(FixtureService $service): JsonResponse
    {
        if (!$service->playNextWeek()) {
            return response()->json(
                [
                    'flag' => 'error',
                    'code' => 500,
                    'message' => 'Error playing next week'
                ]
            );
        }
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'Week played'
            ]);
    }

    public function playAllWeek(FixtureService $service): JsonResponse
    {
        if (!$service->playAllWeek()) {
            return response()->json(
                [
                    'flag' => 'error',
                    'code' => 500,
                    'message' => 'Error playing all week'
                ]
            );
        }
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'All Week played'
            ]);
    }

    public function getAllWeeksMatches(FixtureService $service): JsonResponse
    {
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'All Week played',
                'data' => $service->getAllWeeksMatches()
            ]
        );
    }

    public function getNextWeekMatches(FixtureService $service): JsonResponse
    {
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'All Week played',
                'data' => $service->getNextWeekMatches()
            ]
        );
    }

    public function editMatch(Request $request, FixtureService $service): JsonResponse
    {

        $data = $request->only(['match_id', 'home_score', 'away_score']);

        $rules = [
            'match_id' => 'required|integer',
            'home_score' => 'required|integer',
            'away_score' => 'required|integer'
        ];
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(
                [
                    'flag' => 'error',
                    'code' => 500,
                    'message' => 'Error editing match',
                    'data' => $validator->errors()
                ]
            );
        }
        if (!$service->updateMatch($data['match_id'], $data['home_score'], $data['away_score'])) {
            return response()->json(
                [
                    'flag' => 'error',
                    'code' => 500,
                    'message' => 'Error editing match'
                ]
            );
        }
        return response()->json(
            [
                'flag' => 'success',
                'code' => 200,
                'message' => 'Match edited'
            ]
        );
    }
}
