<?php

namespace App\PortalBundle\Repository;

use App\PortalBundle\Repository\Interfaces\CategoryRepositoryInterface;
use App\CoreBundle\Traits\Repository\Interfaces\TraitRepositoryInterface;
use App\CoreBundle\Traits\Repository\TraitRepository;
use App\CoreBundle\Traits\Repository\TraitSave;


class CategoryRepository extends \Doctrine\ORM\EntityRepository implements CategoryRepositoryInterface, TraitRepositoryInterface
{
    use TraitRepository;
    
    use TraitSave;
}
