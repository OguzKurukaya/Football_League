<?php

namespace Leaguesim\League\Service;


use Illuminate\Support\Collection;
use Leaguesim\League\Repository\TeamRepository;

class FixtureService extends Service
{
    public Collection  $games;
    public function __construct(TeamRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Lig fixture yaratmak için Round Robin Algoritmasını Kullanalım.
     * Bu sayede her takım her hafta farklı bir takımla oynar.
     * @return bool
     */
    public function generateFixture(): bool
    {
        $service = new TeamsCrudService();
        $teams = $service->getAllTeamsId();
        unset($service);
        $teamCount = count($teams);
        //Toplam Takım Sayısı Tek sayı Gelirse Her hafta birisi kazandı sayılacak.
        if ($teamCount % 2 === 1) {
            $teams[] = null;
            $teamCount += 1;
        }

        // Bunu burada alalım daha sonra foreach iinde habire hesaplamıyalım.
        $halfTeamCount = $teamCount / 2;
        $schedule = [];


        //Her takımın ilk yarıda oynıyacagı maç sayısı (n-1) dir
        for ($round = 1; $round <= ($teamCount - 1) * 2; $round += 1) {
            foreach ($teams as $key => $team) {
                /*
                 * Takımların sadece yarısı ile hesap yapalım.
                 * Bunun nedeni Bir son bir baş alacagımızdan dolayı takımların yarısını hesapladıgımızda
                 * Kalan yarısıda maç yapmıs olacaklar.
                 */
                if ($key >= $halfTeamCount) {
                    break;
                }

                /**
                 * Takımlar hep aynı sırayla evinde oynamaması için her iki maçtan birinde yerini değişelim.
                 */
                if (($round % 2 === 1) && ($key == 0)) {
                    $home = $team->id;
                    $away = $teams[$teamCount - $key - 1]->id;
                } else {
                    $away = $team->id;
                    $home = $teams[$teamCount - $key - 1]->id;
                }
                $match = [
                    'home_team_id' => $home,
                    'away_team_id' => $away,
                    'week' => $round,
                ];
                $schedule[] = $match;
            }
            // Takımların yerlerini değiştirelim.
            $teams = $this->shift($teams);
        }

        return $this->repository->createFixture($schedule);
    }

    public function playNextWeek(): bool
    {
        $this->games = $this->repository->getGamesByWeek(onlyNextWeek: true);
        foreach ($this->games as $weekGame) {
            if (!$this->simulateWeekGame($weekGame))
                return false;
        }
        return true;
    }

    public function simulateNextMatches():bool
    {
        $this->games = $this->repository->getGamesByWeek();
        foreach ($this->games as $weekGame) {
            if (!$this->simulateWeekGame($weekGame, is_simulation: true))
                return false;
        }
        return true;
    }

    public function playAllWeek(): bool
    {
        $this->games = $this->repository->getGamesByWeek();
        foreach ($this->games as $weekGame) {
            if (!$this->simulateWeekGame($weekGame))
                return false;
        }
        return true;
    }

    public function updateMatch(int $match_id, int $home_score, int $away_score): bool
    {
        return $this->repository->updateGame(
            [
                'match_id' => $match_id,
                'home_team_score' => $home_score,
                'away_team_score' => $away_score,
                'is_played' => true,
            ]
        );
    }

    public function getAllWeeksMatches(): array
    {
        return stdToArray($this->repository->getAllWeekMatches());
    }

    public function getNextWeekMatches(): array
    {
        return stdToArray($this->repository->getAllWeekMatches(true));
    }

    private function simulateWeekGame($weekGame, bool $is_simulation = false): bool
    {

        $homeRandom = $is_simulation ? 1: rand(0, 100);
        $awayRandom = $is_simulation ? 1: rand(0, 100);

        // Adjust team powers based on random values
        $homeTeamPower = $weekGame->home_team_power * $homeRandom;
        $awayTeamPower = $weekGame->away_team_power * $awayRandom;

        // Calculate total power
        $totalPower = $homeTeamPower + $awayTeamPower;

        // Calculate scores and round to the nearest integer
        $homeTeamScore = (int)round(($homeTeamPower / $totalPower) * 5);
        $awayTeamScore = (int)round(($awayTeamPower / $totalPower) * 5);

        // Update the weekGame object with the calculated scores
        $weekGame->home_team_score = $homeTeamScore;
        $weekGame->away_team_score = $awayTeamScore;
        $weekGame->is_played = true;
        if (!$is_simulation)
            return $this->updateGame($weekGame);
        return true;
    }

    private function updateGame($weekGame): bool
    {
        if (!is_array($weekGame))
            $weekGame = stdToArray($weekGame);
        if (isset($weekGame['home_team_power']))
            unset($weekGame['home_team_power']);
        if (isset($weekGame['away_team_power']))
            unset($weekGame['away_team_power']);
       return $this->repository->updateGame($weekGame);
    }


    /**
     * Arrayın 0. hanesini sabit tutup diğer elemanları
     * Saat yonunun tersine dogru yer değiştirelim.
     * Bu sayede her takımın her hafta farklı bir takımla oynar.
     * @param $items
     * @return array
     */
    private function shift($items): array
    {
        $secondIndex = $items[1];
        unset($items[1]);
        $items = array_values($items);
        $items[] = $secondIndex;
        return $items;
    }
}
