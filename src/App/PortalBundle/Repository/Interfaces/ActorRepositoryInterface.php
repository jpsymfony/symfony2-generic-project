<?php

namespace App\PortalBundle\Repository\Interfaces;

interface ActorRepositoryInterface
{
    /**
     * @param $motcle
     * @return mixed
     */
    public function findbyFirstNameOrLastName($motcle);
}
