<?php
namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\PortalBundle\Repository\MovieRepository;

interface MovieManagerInterface extends GenericManagerInterface
{
    /**
     * MovieManager constructor.
     * @param MovieRepository $repository
     */
    public function __construct(MovieRepository $repository);
}
