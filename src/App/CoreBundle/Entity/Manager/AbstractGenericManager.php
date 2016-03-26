<?php

namespace App\CoreBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\CoreBundle\Repository\AbstractGenericRepository;

class AbstractGenericManager implements GenericManagerInterface
{
    /**
     * @var AbstractGenericRepository $repository
     */
    protected $repository;

    /**
     * @inheritdoc
     */
    public function __construct(AbstractGenericRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    public function remove($entity)
    {
        $this->repository->remove($entity);
    }

    /**
     * @inheritdoc
     */
    public function all($result = "object", $maxResults = null, $orderby = '', $dir = 'ASC')
    {
        return $this->repository->findAllByEntity($result, $maxResults, $orderby, $dir);
    }

    /**
     * @inheritdoc
     */
    public function find($entity)
    {
        return $this->repository->find($entity);
    }

    /**
     * @inheritdoc
     */
    public function save($entity, $persist = false, $flush = true)
    {
        return $this->repository->save($entity, $persist, $flush);
    }
}