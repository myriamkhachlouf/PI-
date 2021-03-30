<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{


    /**
     * @Route("/first", name="first", methods={"GET"})
     * @param OffreRepository $offreRepository
     * @return Response
     */
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('first/index.html.twig');

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
     * @Route("/blog", name="blog")
     */
    public function blog(): Response
    {
        return $this->render('blog.html.twig', [
            'controller_name' => 'FirstController',
        ]);
    }
    /**
     * @Route("/blogdetails", name="blogdetails")
     */
    public function blog_details(): Response
    {
        return $this->render('blog-details.html.twig', [
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
        return $this->render('dashboard.html.twig', [
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
}
