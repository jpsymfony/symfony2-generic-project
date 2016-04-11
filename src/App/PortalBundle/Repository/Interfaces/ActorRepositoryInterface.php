<?php

namespace App\PortalBundle\Repository\Interfaces;

use Pagerfanta\Pagerfanta;

interface ActorRepositoryInterface
{
    public function getResultFilterCount($requestVal);

    public function getResultFilterPaginated($requestVal, $limit = 20, $offset = 0);

    public function getQueryResultFilter($requestVal);

    /**
     * @param int $limit
     * @param int $offset
     * @return Pagerfanta
     */
    public function getActors($limit = 20, $offset = 0);
}
