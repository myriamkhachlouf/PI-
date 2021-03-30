<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/formation")
 */
class FormationController extends Controller
{
    /**
     * @Route("/", name="formation_index", methods={"GET"})
     * @param FormationRepository $formationRepository
     * @return Response
     */
    public function index(FormationRepository $formationRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $allformations=$formationRepository->findAll();
            $formations= $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allformations,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );


        return $this->render('formation/index.html.twig', [
            'formations' => $formations
        ]);
    }

    /**
     * @Route("/new", name="formation_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formation_show", methods={"GET"})
     * @param Formation $formation
     * @return Response
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formation_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Formation $formation
     * @return Response
     */
    public function edit(Request $request, Formation $formation): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formation_delete", methods={"DELETE"})
     * @param Request $request
     * @param Formation $formation
     * @return Response
     */
    public function delete(Request $request,
                           Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formation_index');
    }
    /**
     * @Route("/{id}", name="formation_show", methods={"GET"})
     * @param Formation $formation
     * @return Response
     */
    /**
     * @Route("/tri", name="tri", methods={"GET"})
     */
    public function tri(FormationRepository $formation,Request $request): Response
    {
        $allformations=$formation->listOrderByNote();
        $formations = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allformations,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        return $this->render('formation/index.html.twig', [
            'formation' => $formations,
        ]);
    }
}
