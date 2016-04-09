<?php

namespace App\PortalBundle\Repository\Interfaces;

interface MovieRepositoryInterface
{
    /**
     * @param $requestVal
     * @return array of movies
     */
    public function getResultFilter($requestVal);

    /**
     * @param int $limit
     * @param int $offset
     * @return array of movies
     */
    public function getMovies($limit = 20, $offset = 0);
}
