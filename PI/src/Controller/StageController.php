<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\UsersRepository;
use App\Repository\OffreRepository;
use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stage")
 */
class StageController extends Controller
{
    /**
     * @Route("/", name="stage_index", methods={"GET","POST"})
     * @param OffreRepository $offreRepository
     * @param StageRepository $stageRepository
     * @param UsersRepository $EnreRepository
     * @param Request $request
     * @return Response
     */
    public function index(StageRepository $stageRepository,OffreRepository $offreRepository, Request $request,UsersRepository $EnreRepository): Response
    {   $entreprise = $EnreRepository->findAll();
        $offre = $offreRepository->findAll();
        $stage = $stageRepository->findAll();

        if($request->isMethod("POST"))
        {
            $value=$request->get('Recherche');
            $stage = $this->getDoctrine()->getRepository(Offre::class)->Recherche($value);

            return $this->render('stage/recherche.html.twig', [
                'stages' => $stage,
                'offres' => $offre,
                'entreprises' =>$entreprise,
            ]);

        }
        $allstages =$stageRepository->findAll();
        $stage = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allstages,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('stage/index.html.twig', [
            'stages' => $stage,
            'offres' => $offre,
            'entreprises' =>$entreprise,

        ]);
    }
    /**
     * @Route("/aman", name="aman", methods={"GET","POST"})
     * @param OffreRepository $offreRepository
     * @param StageRepository $stageRepository
     * @param UsersRepository $EnreRepository
     * @param Request $request
     * @return Response
     */
    public function tri (StageRepository $stageRepository,OffreRepository $offreRepository, Request $request,UsersRepository $EnreRepository): Response
    {
        $entreprise = $EnreRepository->findAll();
        $offre = $offreRepository->findAll();
        $stage = $stageRepository->listOrderByTypeStage();

        if($request->isMethod("POST"))
        {
            $value=$request->get('Recherche');
            $stage = $this->getDoctrine()->getRepository(Offre::class)->Recherche($value);

            return $this->render('stage/recherche.html.twig', [
                'stages' => $stage,
                'offres' => $offre,
                'entreprises' =>$entreprise,
            ]);

        }
        $allstages =$stageRepository->findAll();
        $stage = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allstages,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            4
        );
        return $this->render('stage/index.html.twig', [
            'stages' => $stage,
            'offres' => $offre,
            'entreprises' =>$entreprise,

        ]);
    }
        /**
     * @Route("/new", name="stage_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stage = new Stage();
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stage);
            $entityManager->flush();

            return $this->redirectToRoute('stage_index');
        }

        return $this->render('stage/new.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stage_show", methods={"GET"})
     */
    public function show(Stage $stage): Response
    {
        return $this->render('stage/show.html.twig', [
            'stage' => $stage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stage $stage): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stage_index');
        }

        return $this->render('stage/edit.html.twig', [
            'stage' => $stage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stage_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Stage $stage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stage_index');
    }
}
