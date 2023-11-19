<?php

namespace Leaguesim\League\Service;

use Illuminate\Support\Collection;
use Leaguesim\League\Repository\TeamRepository;

class LeagueService extends Service
{
    public Collection $teams;

    public Collection $playedMatches;

    public function __construct(TeamRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Gerekli datalarımız çekelim ve hesaplamaya başlıyalım
     * @return true
     */
    public function getLeague(): bool
    {
     $this->teams = $this->repository->getAllTeams()->keyBy('id');
     $this->playedMatches = $this->repository->getPlayedMatches();

     $this->prepareTeamsForCalculation();
     return $this->calculateLeague();
    }

    public function getPrediction(): Collection
    {
        $this->getLeague();

        $fixtureService = new FixtureService($this->repository);
        $fixtureService->simulateNextMatches();
        $this->playedMatches = $fixtureService->games;
        $this->calculateLeague();
        return $this->calculatePrediction();
    }

    private function calculatePrediction(): Collection
    {
        $totalPoints = $this->teams->sum('points');
        foreach ($this->teams as $team) {
            $team->prediction = round(($team->points / $totalPoints) * 100, 2);
            $this->teams->put($team->id, $team);
        }
       $this->teams = $this->teams->sortByDesc('prediction')->map(function ($team) {
            return collect($team)->only(['name', 'image_url', 'prediction']);
        });
        return $this->teams;
    }


    /**
     * Oynanan MAçlara gre Takımlara Puanları verelim.
     * @return true
     */
    private function calculateLeague(): bool
    {
        foreach ($this->playedMatches as $playedMatch) {
            $homeTeam = $this->teams->get($playedMatch->home_team_id);
            $awayTeam = $this->teams->get($playedMatch->away_team_id);

            $homeTeam->score_for += $playedMatch->home_team_score;
            $homeTeam->score_against += $playedMatch->away_team_score;
            $awayTeam->score_for += $playedMatch->away_team_score;
            $awayTeam->score_against += $playedMatch->home_team_score;
            $homeTeam->played += 1;
            $awayTeam->played += 1;

            if ($playedMatch->home_team_score > $playedMatch->away_team_score) {
                $homeTeam->points += 3;
                $homeTeam->wins += 1;
                $awayTeam->losses += 1;
            } elseif ($playedMatch->home_team_score < $playedMatch->away_team_score) {
                $awayTeam->points += 3;
                $awayTeam->wins += 1;
                $homeTeam->losses += 1;
            } else {
                $homeTeam->points += 1;
                $awayTeam->points += 1;
                $homeTeam->draws += 1;
                $awayTeam->draws += 1;
            }

            $this->teams->put($homeTeam->id, $homeTeam);
            $this->teams->put($awayTeam->id, $awayTeam);
        }
        return true;
    }

    /**
     * Takımları Lig Tablosu için hazır Hale getirelim.
     * Bu değerleri Db üzerinde tutmuyoruz Cünkü Burada hesaplamak varken gereksiz veri tutmaya gerek yok
     * @return void
     */
    private function prepareTeamsForCalculation(): void
    {
        foreach ($this->teams as $team) {
            $team->score_for = 0;
            $team->score_against = 0;
            $team->wins = 0;
            $team->losses = 0;
            $team->draws = 0;
            $team->points = 0;
            $team->played = 0;
            $this->teams->put($team->id, $team);
        }
    }
}
