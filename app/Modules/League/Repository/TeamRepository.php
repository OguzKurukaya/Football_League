<?php

namespace Leaguesim\League\Repository;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Leaguesim\System\Repository\RepositoryAbstracts;

class TeamRepository extends RepositoryAbstracts
{

    public function getAllTeams(): Collection
    {
        return DB::table(self::TEAMS_TABLE)->select()->get();
    }

    public function getAllTeamsId(): Collection
    {
        return DB::table(self::TEAMS_TABLE)->select(['id'])->get();
    }

    public function createFixture(array $fixture): bool
    {
        try {
            return DB::table(self::MATCHES_TABLE)->insert($fixture);
        } catch (Exception $e) {
            //TODO :: LOGGING
            return false;
        }
    }

    public function isFixtureGenerated():bool
    {
        return DB::table(self::MATCHES_TABLE)->count() > 0;
    }

    public function getPlayedMatches(): Collection
    {
        return DB::table(self::MATCHES_TABLE)->where('is_played', '=', true)->get();
    }

    public function getGamesByWeek(bool $onlyNextWeek = false): Collection
    {
        $sql = DB::table(self::MATCHES_TABLE)
            ->select([
                'matches.match_id',
                'matches.home_team_id',
                'matches.away_team_id',
                'matches.home_team_score',
                'matches.away_team_score',
                'matches.is_played',
                'matches.week',
                'home_team.power as home_team_power',
                'away_team.power as away_team_power'
            ]);
        if ($onlyNextWeek) {
            $sql->whereRaw('week = (select min(week) from matches where is_played = false)');
        }
        return $sql->where('is_played', '=', false)
            ->leftJoin(self::TEAMS_TABLE . ' as home_team', 'home_team.id', '=', 'matches.home_team_id')
            ->leftJoin(self::TEAMS_TABLE . ' as away_team', 'away_team.id', '=', 'matches.away_team_id')
            ->get();
    }

    public function getAllWeekMatches(bool $onlyThisWeek = false): Collection
    {
        $sql = DB::table(self::MATCHES_TABLE)
            ->select([
                'matches.match_id',
                'matches.home_team_id',
                'matches.away_team_id',
                'matches.home_team_score',
                'matches.away_team_score',
                'matches.is_played',
                'matches.week',
                'home_team.name as home_team_name',
                'away_team.name as away_team_name',
                'home_team.image_url as home_team_image_url',
                'away_team.image_url as away_team_image_url',

            ])
            ->leftJoin(self::TEAMS_TABLE . ' as home_team', 'home_team.id', '=', 'matches.home_team_id')
            ->leftJoin(self::TEAMS_TABLE . ' as away_team', 'away_team.id', '=', 'matches.away_team_id');

        if ($onlyThisWeek) {
            $sql->whereRaw('week = (select min(week) from matches where is_played = false)');
        }
        return $sql->get();
    }

    public function getLastWeekMatches(): Collection
    {
        $sql = DB::table(self::MATCHES_TABLE)
            ->select([
                'matches.match_id',
                'matches.home_team_id',
                'matches.away_team_id',
                'matches.home_team_score',
                'matches.away_team_score',
                'matches.is_played',
                'matches.week',
                'home_team.name as home_team_name',
                'away_team.name as away_team_name',
                'home_team.image_url as home_team_image_url',
                'away_team.image_url as away_team_image_url',

            ])
            ->leftJoin(self::TEAMS_TABLE . ' as home_team', 'home_team.id', '=', 'matches.home_team_id')
            ->leftJoin(self::TEAMS_TABLE . ' as away_team', 'away_team.id', '=', 'matches.away_team_id')
            ->whereRaw('week = (select max(week) from matches )');
        return $sql->get();
    }

    public function updateGame($data):bool
    {
        return (bool)DB::table(self::MATCHES_TABLE)->where('match_id', '=', $data['match_id'])->update(
            $data
        );
    }
}
