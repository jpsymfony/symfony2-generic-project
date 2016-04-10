<?php

namespace App\PortalBundle\Controller;

use App\PortalBundle\Form\Type\MovieFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\Common\Collections\ArrayCollection;
use App\PortalBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MovieController extends Controller
{
    /**
     * @Route("/movies/page/{page}", name="movie_list", defaults={"page" = 1})
     * @Template("@AppPortal/Movie/list.html.twig")
     * @param integer $page
     * @return array of movies and pagination
     */
    public function listAction($page)
    {
        $maxMoviesPerPage = $this->container->getParameter('app_portal.max_movies_per_page');
        $movies = $this->get('app_portal.movie.manager')
            ->getFilteredMovies($maxMoviesPerPage, ($page - 1) * $maxMoviesPerPage);

        $pagination = array(
            'page' => $page,
            'route' => 'movie_list',
            'pages_count' => $this->get('app_portal.actor.manager')->count(),
            'route_params' => array()
        );

        return array(
            'movies' => $movies,
            'pagination' => $pagination,
        );
    }

    /**
     * @Template("@AppPortal/Movie/partials/movies.html.twig", vars={"movies"})
     * @ParamConverter("movies", converter="project_collection_converter", options={"manager":"app_portal.movie.manager", "orderby":"title", "dir":"desc"})
     * @param ArrayCollection $movies
     * @param int $max
     */
    public function topAction(ArrayCollection $movies, $max = 5)
    {
    }

    /**
     * @Route("/movies/{id}/show", name="movie_show")
     * @ParamConverter("movie", class="AppPortalBundle:Movie")
     * @param Movie $movie
     * @return Response
     * @Security("has_role('ROLE_VISITOR')")
     * @Cache(smaxage=600)
     */
    public function showAction(Movie $movie)
    {
        $response = new Response();
        $response->setEtag(md5($movie->getId() . $movie->getUpdatedAt()->format('YmdHis') . microtime(true)));

        return $this->render('@AppPortal/Movie/show.html.twig', ['movie' => $movie], $response);
    }

    /**
     * @Route("/admin/movies/new", name="movie_new")
     * @Route("/admin/movies/{id}/edit", name="movie_edit")
     * @Template("@AppPortal/Movie/edit.html.twig")
     * @param Request $request
     * @param Movie|null $movie
     * @return array|RedirectResponse
     * @ParamConverter("movie", class="AppPortalBundle:Movie")
     * @Security("has_role('ROLE_EDITOR')")
     */
    public function newEditAction(Request $request, Movie $movie = null)
    {
        // we create entity if not exists in database
        if (is_null($movie)) {
            $movie = new Movie();
            $this->getMovieFormHandler()->setMovieFormHandlerStrategy($this->get('app_portal.new_movie.form.handler.strategy'));
        } else {
            // we get entity from database
            $this->getMovieFormHandler()->setMovieFormHandlerStrategy($this->get('app_portal.update_movie.form.handler.strategy'));
        }

        $form = $this->getMovieFormHandler()->createForm($movie);

        if ($this->getMovieFormHandler()->handleForm($form, $movie, $request)) {
            // we add flash messages to stick with context (new or edited object)
            $this->addFlash('success', $this->getMovieFormHandler()->getMessage());

            return $this->redirectToRoute('movie_edit', array('id' => $movie->getId()));
        }

        return array(
            'form' => $form->createView(),
            'movie' => $movie,
        );
    }

    /**
     * @Route("/admin/movies/{id}/delete", name="movie_delete")
     * @ParamConverter("movie", class="AppPortalBundle:Movie")
     * @param Movie $movie
     * @return RedirectResponse
     */
    public function deleteAction(Movie $movie)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot access this page!');
        }
        $this->container->get('app_portal.movie.manager')->remove($movie);
        $this->addFlash('success', $this->get('translator')->trans('film.supprime', ['%title%' => $movie->getTitle()]));

        return new RedirectResponse($this->container->get('router')->generate('movie_list'));
    }

    /**
     * @Template("@AppPortal/Movie/partials/formFilter.html.twig")
     * @param Request $request
     * @return Response
     */
    public function formFilterAction(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(
            new MovieFilterType(),
            $movie,
            [
                'method' => 'GET',
                'action' => $this->generateUrl('movie_search')
            ]
        );

        try {
            $this->getMovieFormHandler()->handleSearchForm($form, $request);
        } catch (\Exception $e) {
            $this->get('logger')->critical($e->getMessage());
            $this->addFlash('error', $e->getMessage());
        }

        return $this->render('@AppPortal/Movie/partials/formFilter.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/movies/search", name="movie_search")
     * @Template("@AppPortal/Movie/list.html.twig")
     * @param Request $request
     * @return array
     */
    public function listSearchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $movies = $em->getRepository('AppPortalBundle:Movie')->getResultFilter(current($request->query->all()));

        return array(
            'movies' => $movies,
        );
    }

    public function getMovieFormHandler()
    {
        return $this->container->get('app_portal.movie.form.handler');
    }
}
