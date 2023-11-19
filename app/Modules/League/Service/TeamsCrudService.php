<?php

namespace Leaguesim\League\Service;

use Illuminate\Support\Collection;
use Leaguesim\League\Repository\TeamRepository;

class TeamsCrudService extends Service
{
    public function __construct(TeamRepository $repository = null)
    {
        if ($repository == null)
            $repository = new TeamRepository();
        parent::__construct($repository);
    }

    public function getAllTeams() : Collection
    {
        return $this->repository->getAllTeams();
    }

    public function getALlTeamsID(): array
    {
        return $this->repository->getAllTeamsId()->toArray();
    }

}
