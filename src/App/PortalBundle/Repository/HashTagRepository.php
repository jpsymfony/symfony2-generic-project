<?php

namespace App\PortalBundle\Repository;

use App\PortalBundle\Repository\Interfaces\HashTagRepositoryInterface;
use App\CoreBundle\Traits\Repository\Interfaces\TraitRepositoryInterface;
use App\CoreBundle\Traits\Repository\TraitRepository;
use App\CoreBundle\Traits\Repository\TraitSave;

class HashTagRepository extends \Doctrine\ORM\EntityRepository implements HashTagRepositoryInterface, TraitRepositoryInterface
{
    use TraitRepository;
    
    use TraitSave;
}
