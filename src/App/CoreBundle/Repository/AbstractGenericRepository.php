<?php

namespace App\CoreBundle\Repository;

use App\CoreBundle\Traits\Repository\Interfaces\TraitRepositoryInterface;
use App\CoreBundle\Traits\Repository\Interfaces\TraitSaveInterface;
use App\CoreBundle\Traits\Repository\TraitRepository;
use App\CoreBundle\Traits\Repository\TraitSave;
use Doctrine\ORM\EntityRepository;

class AbstractGenericRepository extends EntityRepository implements TraitRepositoryInterface, TraitSaveInterface
{
    use TraitRepository;

    use TraitSave;
}