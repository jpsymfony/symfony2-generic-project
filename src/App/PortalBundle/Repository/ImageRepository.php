<?php

namespace App\PortalBundle\Repository;

use Doctrine\ORM\EntityRepository;
use App\CoreBundle\Traits\Repository\TraitRepository;
use App\CoreBundle\Traits\Repository\TraitSave;

class ImageRepository extends EntityRepository
{
    use TraitRepository;

    use TraitSave;
}