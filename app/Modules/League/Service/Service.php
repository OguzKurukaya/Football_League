<?php

namespace Leaguesim\League\Service;

use Leaguesim\League\Repository\TeamRepository;

class Service
{
    public TeamRepository $repository;

    public function __construct(TeamRepository $repository)
    {
        $this->repository = $repository;
    }

}
