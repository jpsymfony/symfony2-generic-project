<?php
namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\PortalBundle\Repository\CategoryRepository;

interface CategoryManagerInterface extends GenericManagerInterface
{
    /**
     * CategoryManager constructor.
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository);
}
