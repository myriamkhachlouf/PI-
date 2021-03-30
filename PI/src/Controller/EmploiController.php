<?php

namespace App\Controller;

use App\Entity\Emploi;
use App\Entity\Offre;
use App\Entity\Users;
use App\Form\EmploiType;
use App\Repository\EmploiRepository;
use App\Repository\UsersRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/emploi")
 */
class EmploiController extends Controller
{
    /**
     * @Route("/", name="emploi_index", methods={"GET","POST"})
     * @param OffreRepository $offreRepository
     * @param EmploiRepository $emploiRepository
     * @param UsersRepository $EnreRepository
     * @param Request $request
     * @return Response
     */
    public function index(EmploiRepository $emploiRepository,OffreRepository $offreRepository, Request $request,UsersRepository $EnreRepository): Response
    {   $entreprise = $EnreRepository->findAll();
        $offre = $offreRepository->findAll();
        $emploi = $emploiRepository->findAll();

        if($request->isMethod("POST"))
        {
            $value=$request->get('Recherche');
            $emploi = $this->getDoctrine()->getRepository(Emploi::class)->Recherche($value);

            return $this->render('emploi/recherche.html.twig', [
                'emplois' => $emploi,
                'offres' => $offre,
                'entreprises' =>$entreprise,
            ]);

        }
        $allemplois =$emploiRepository->findAll();
        $emploi = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allemplois,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('emploi/index.html.twig', [
            'emplois' => $emploi,
            'offres' => $offre,
            'entreprises' =>$entreprise,

        ]);
    }
    /**
     * @Route("/aman", name="aman", methods={"GET","POST"})
     * @param OffreRepository $offreRepository
     * @param EmploiRepository $emploiRepository
     * @param UsersRepository $EnreRepository
     * @param Request $request
     * @return Response
     */
    public function tri (EmploiRepository $emploiRepository,OffreRepository $offreRepository, Request $request,UsersRepository $EnreRepository): Response
    {
        $entreprise = $EnreRepository->findAll();
        $offre = $offreRepository->findAll();
        $emploi = $emploiRepository->listOrderBySalaire();

        if($request->isMethod("POST"))
        {
            $value=$request->get('Recherche');
            $emploi = $this->getDoctrine()->getRepository(Offre::class)->Recherche($value);

            return $this->render('emploi/recherche.html.twig', [
                'emplois' => $emploi,
                'offres' => $offre,
                'entreprises' =>$entreprise,
            ]);

        }
        $allemplois =$emploiRepository->findAll();
        $emploi = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allemplois,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('emploi/index.html.twig', [
            'emplois' => $emploi,
            'offres' => $offre,
            'entreprises' =>$entreprise,

        ]);
    }

    /**
     * @Route("/new", name="emploi_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $emploi = new Emploi();
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($emploi);
            $entityManager->flush();

            return $this->redirectToRoute('emploi_index');
        }

        return $this->render('emploi/new.html.twig', [
            'emploi' => $emploi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emploi_show", methods={"GET"})
     */
    public function show(Emploi $emploi): Response
    {
        return $this->render('emploi/show.html.twig', [
            'emploi' => $emploi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="emploi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Emploi $emploi): Response
    {
        $form = $this->createForm(EmploiType::class, $emploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('emploi_index');
        }

        return $this->render('emploi/edit.html.twig', [
            'emploi' => $emploi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="emploi_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Emploi $emploi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emploi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($emploi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('emploi_index');
    }
}
