<?php

namespace App\Controller;

use App\Repository\CommentaireRepository;
/*use App\Repository\OffreRepository;*/

use App\Repository\PublicationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{


    /**
     * @Route("/brr", name="first")
     * @param Request $request
     * @param PublicationRepository $publicationRepository
     * @param PaginatorInterface $paginator
     */
    public function index(Request $request,PublicationRepository $publicationRepository,PaginatorInterface $paginator)
    {

        //$user = $this->getUser();
        //$session=$request->getSession();
        // $session->set("role",$user);
        //$session->set("user_id",1);
        //if ($user== NULL)


        if ( $this->isGranted('ROLE_ENTREPRISE') OR  $this->isGranted('ROLE_CANDIDAT'))
            return $this->render('publication/index_cand.html.twig', [
                'publications' => $publicationRepository->findBy(array(),array('createdAt' => 'ASC'),3),
            ]);

        return $this->render('publication/index_cand.html.twig', [
            'publications' => $publicationRepository->findBy(array(),array('createdAt' => 'DESC'),3),
        ]);
    }
    /**
     * @Route("/first", name="brr", methods={"GET"})
     * @return Response
     */
    public function banana(): Response
    {
        return $this->render('first/index.html.twig');

    }

    /**
     * @Route("/blog", name="blog")
     * @param $publicationRepository
     * @return Response
     */
    public function blog(PublicationRepository $publicationRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $donnees= $publicationRepository->findAll();
        $publication= $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('publication/allblogcand.html.twig', [
            'publications' =>$publication ,

        ]);
    }

    /**
     * @Route("/blogdetails/{id}", name="blogdetails")
     * @param $id
     * @param PublicationRepository $publicationRepository
     * @param CommentaireRepository $commentaireRepository
     * @return Response
     */
    public function blog_details($id,PublicationRepository $publicationRepository,CommentaireRepository $commentaireRepository): Response
    {
        //if ($this->isGranted('ROLE_CANDIDAT') or $this->isGranted('ROLE_ENTREPRISE')) {
        // $user = $this->getUser();
        $publication = $publicationRepository->find($id);
        /* if (!$publication->getSeenby()->contains($user)) {
             $publication->addSeenby($user);
             $publication->setViews($publication->getViews() + 1);
             $this->getDoctrine()->getManager()->flush();
         }*/
        //  }
        return $this->render('publication/blogdetail.html.twig', [
            'publication' => $publicationRepository->find($id),
            'commentaires' => $commentaireRepository->findBy(array('publication' => $publicationRepository->find($id)),array('createdAt' => 'ASC'))

        ]);
    }
    /**
     * @Route("/jobs", name="jobs")
     */
    public function jobs(): Response
    {
        return $this->render('jobs.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('about-us.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('contact.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/jobdetails", name="jobdetails")
     */
    public function job_details(): Response
    {
        return $this->render('job-details.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/team", name="team")
     */
    public function team(): Response
    {
        return $this->render('team.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/terms", name="terms")
     */
    public function terms(): Response
    {
        return $this->render('terms.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/testimonials", name="testimonials")
     */
    public function testimonials(): Response
    {
        return $this->render('testimonials.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('publication/statistic.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/icons", name="icons")
     */
    public function icons(): Response
    {
        return $this->render('icons.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/map", name="map")
     */
    public function map(): Response
    {
        return $this->render('map.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/notification", name="notification")
     */
    public function notification(): Response
    {
        return $this->render('notifications.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/rtl", name="rtl")
     */
    public function rtl(): Response
    {
        return $this->render('rtl.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/tables", name="tables")
     */
    public function tables(): Response
    {
        return $this->render('tables.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/typography", name="typography")
     */
    public function typography(): Response
    {
        return $this->render('typography.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/upgrade", name="upgrade")
     */
    public function upgrade(): Response
    {
        return $this->render('upgrade.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/user", name="user")
     */
    public function userr(): Response
    {
        return $this->render('user.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/recherche", name="user")
     */
    public function recherche(): Response
    {
        return $this->render('offre/recherche.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }


    /**
     * @Route("/dashboard2", name="dashboard2")
     */
    public function dashboard2(): Response
    {
        return $this->render('dashboard2.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }

}
