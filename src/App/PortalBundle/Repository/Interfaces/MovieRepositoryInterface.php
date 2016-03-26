<?php

namespace App\PortalBundle\Repository\Interfaces;

interface MovieRepositoryInterface
{
    /**
     * @param $requestVal
     * @return array of movies
     */
    public function getResultFilter($requestVal);
}
