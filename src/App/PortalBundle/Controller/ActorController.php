<?php

namespace App\PortalBundle\Controller;

use App\PortalBundle\Form\Type\ActorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\PortalBundle\Entity\Actor;
use App\PortalBundle\Form\Type\ActorSearchForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ActorController extends Controller
{
    /**
     * @Route("/actors", name="actors_list")
     * @Template("@AppPortal/Actor/list.html.twig", vars={"actors"})
     * @ParamConverter("actors", converter="project_collection_converter", options={"manager":"app_portal.actor.manager", "orderby":"lastName"})
     */
    public function listAction(ArrayCollection $actors = null)
    {
        $form = $this->container->get('form.factory')->create(
            new ActorSearchForm(),
            null,
            [
                'action' => $this->generateUrl('actor_search'),
                'method' => 'POST',
                'attr' => ['id' => 'form_recherche']
            ]
        );

        return array(
            'actors' => $actors,
            'nbActorsFound' => true,
            'form' => $form->createView()
        );
    }

    /**
     * @Template("@AppPortal/Actor/partials/actors.html.twig", vars={"actors", "nbActorsFound"})
     * @ParamConverter("actors", converter="project_collection_converter", options={"manager":"app_portal.actor.manager", "orderby":"birthday"})
     */
    public function topAction(ArrayCollection $actors, $max = 5, $nbActorsFound = false)
    {
    }

    /**
     * @Route("/actors/search", name="actor_search", options={"expose"=true})
     * @Method("POST")
     */
    public function searchAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $motcle = $request->request->get('motcle');

            $em = $this->container->get('doctrine')->getManager();

            if ($motcle != '') {
                $actors = $em->getRepository('AppPortalBundle:Actor')->findbyFirstNameOrLastName($motcle);
            } else {
                $actors = $em->getRepository('AppPortalBundle:Actor')->findAll();
            }

            return $this->container->get('templating')->renderResponse(
                '@AppPortal/Actor/partials/actors.html.twig',
                array(
                    'actors' => $actors,
                    'nbActorsFound' => true
                )
            );
        }
    }

    /**
     * @Route("/admin/actors/new", name="actor_new")
     * @Route("/admin/actors/{id}/edit", name="actor_edit")
     * @Template("@AppPortal/Actor/edit.html.twig")
     * @Security("has_role('ROLE_EDITOR')")
     */
    public function newEditAction(Request $request, Actor $actor = null)
    {
        $actorFormHandler = $this->container->get('app_portal.actor.form.handler');

        // we create entity if not exists in database
        if (is_null($actor)) {
            $actor = new Actor();
            $actorFormHandler->setActorFormHandlerStrategy($this->get('app_portal.new_actor.form.handler.strategy'));
        } else { // we get entity from database
            $actorFormHandler->setActorFormHandlerStrategy($this->get('app_portal.update_actor.form.handler.strategy'));
        }

        $form = $actorFormHandler->createForm($actor);

        if ($actorFormHandler->handleForm($form, $actor, $request)) {
            // we add flash messages to stick with context (new or edited object)
            $this->addFlash('success', $actorFormHandler->getMessage());

            return $this->redirectToRoute('actor_edit', array('id' => $actor->getId()));
        }

        return array(
            'form' => $form->createView(),
            'actor' => $actor,
        );
    }

    /**
     * @Route("/admin/actors/{id}/delete", name="actor_delete")
     * @param Actor $actor
     * @ParamConverter("actor", class="AppPortalBundle:Actor")
     * @return RedirectResponse
     */
    public function deleteAction(Actor $actor)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot access this page!');
        }
        $this->container->get('app_portal.actor.manager')->remove($actor);

        return new RedirectResponse($this->container->get('router')->generate('actors_list'));
    }
}
