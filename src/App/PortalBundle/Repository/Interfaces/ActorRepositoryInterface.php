<?php

namespace App\PortalBundle\Repository\Interfaces;

use Pagerfanta\Pagerfanta;

interface ActorRepositoryInterface
{
    /**
     * @param $motcle
     * @return mixed
     */
    public function findbyFirstNameOrLastName($motcle);

    /**
     * @param int $limit
     * @param int $offset
     * @return Pagerfanta
     */
    public function getActors($limit = 20, $offset = 0);
}
