<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Repository\Interfaces\CategoryRepositoryInterface;

interface CategoryManagerInterface
{
    /**
     * CategoryManager constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository);
    
    public function remove($entity);

    public function find($category);
}
