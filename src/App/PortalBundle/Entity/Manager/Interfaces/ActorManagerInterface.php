<?php
namespace App\PortalBundle\Entity\Manager\Interfaces;

use App\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use App\PortalBundle\Repository\ActorRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Routing\RouterInterface;

interface ActorManagerInterface extends GenericManagerInterface
{
    /**
     * ActorManager constructor.
     * @param ActorRepository $repository
     */
    public function __construct(ActorRepository $repository);

    /**
     * @param int $limit
     * @param int $offset
     * @return array of actors
     */
    public function getResultFilterPaginated($limit = 20, $offset = 0);

    /**
     * @param $requestVal
     * @return integer
     */
    public function getResultFilterCount($requestVal);

    /**
     * @return FormInterface
     */
    public function getActorSearchForm();

    /**
     * @param FormTypeInterface $searchFormType
     * @return ActorManagerInterface
     */
    public function setSearchFormType(FormTypeInterface $searchFormType);

    /**
     * @param FormFactoryInterface $formFactory
     * @return ActorManagerInterface
     */
    public function setFormFactory($formFactory);

    /**
     * @param RouterInterface $router
     * @return ActorManagerInterface
     */
    public function setRouter($router);
}
