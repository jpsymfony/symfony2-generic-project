<?php

namespace App\PortalBundle\Repository\Interfaces;

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
     * @return array of actors
     */
    public function getActors($limit = 20, $offset = 0);
}
