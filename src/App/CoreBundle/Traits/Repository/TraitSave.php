<?php

namespace App\CoreBundle\Traits\Repository;

trait TraitSave
{
    /**
     * {@inheritdoc}
     */
    public function save($entity, $persist = false, $flush = true)
    {
        if ($persist) {
            $this->_em->persist($entity);
        }

        if ($flush) {
            $this->_em->flush();
        }

        return $entity;
    }
}
