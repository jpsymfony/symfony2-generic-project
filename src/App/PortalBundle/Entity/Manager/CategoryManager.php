<?php
namespace App\PortalBundle\Entity\Manager;

use App\PortalBundle\Entity\Manager\Interfaces\CategoryManagerInterface;
use App\PortalBundle\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryManager implements CategoryManagerInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * CategoryManager constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    
    public function remove($entity)
    {
        $this->categoryRepository->remove($entity);
    }

    public function find($category)
    {
        return $this->categoryRepository->find($category);
    }
}
