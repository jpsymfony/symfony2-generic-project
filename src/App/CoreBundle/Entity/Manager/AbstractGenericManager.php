<?php

namespace App\CoreBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\CoreBundle\Repository\AbstractGenericRepository;
use Pagerfanta\Pagerfanta;

abstract class AbstractGenericManager implements GenericManagerInterface
{
    /**
     * @var AbstractGenericRepository $repository
     */
    protected $repository;

    /**
     * @inheritdoc
     */
    public function count($enabled = false) 
    {
        return $this->repository->count($enabled);
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

    /**
     * @inheritdoc
     */
    public function isTypeMatch($labelClass)
    {
        return $labelClass === $this->getLabel();
    }

    /**
     * @inheritdoc
     */
    abstract public function getLabel();

    public function getPagination($page, $route, $maxPerPage)
    {
        return array(
            'page' => $page,
            'route' => $route,
            'pages_count' => ceil($this->count() / $maxPerPage),
        );
    }
}