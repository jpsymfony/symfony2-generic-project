<?php

namespace App\CoreBundle\Traits\Repository\Interfaces;

use Doctrine\ORM\Query;

interface TraitSaveInterface
{
    /**
     * @param $entity
     * @param bool $persist
     * @param bool $flush
     */
    public function save($entity, $persist = false, $flush = true);
}
