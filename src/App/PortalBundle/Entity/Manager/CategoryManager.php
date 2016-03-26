<?php
namespace App\PortalBundle\Entity\Manager;

use App\CoreBundle\Entity\Manager\AbstractGenericManager;
use App\PortalBundle\Entity\Manager\Interfaces\CategoryManagerInterface;
use App\PortalBundle\Repository\CategoryRepository;

class CategoryManager extends AbstractGenericManager implements CategoryManagerInterface
{
    /**
     * CategoryManager constructor.
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }
}
