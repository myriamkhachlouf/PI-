<?php

namespace App\Controller;

use App\Entity\GrilleEvaluation;
use App\Entity\Entretien;
use App\Form\GrilleEvaluationType;
use App\Repository\GrilleEvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/grille/evaluation")
 */
class GrilleEvaluationController extends AbstractController
{
    /**
     * @Route("/grille_evaluation_index", name="grille_evaluation_index", methods={"GET"})
     */
    public function index(GrilleEvaluationRepository $grilleEvaluationRepository): Response
    {
        return $this->render('grille_evaluation/index.html.twig', [
            'grille_evaluations' => $grilleEvaluationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="grille_evaluation_new", methods={"GET","POST"})
     */
    public function new(Request $request,$id): Response
    {
        $grilleEvaluation = new GrilleEvaluation();
        $entretien = $this->getDoctrine()->getRepository(Entretien::class)->find($id);
        $grilleEvaluation->SetEntretien($entretien);
        $form = $this->createForm(GrilleEvaluationType::class, $grilleEvaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($grilleEvaluation);
            $entityManager->flush();

            return $this->redirectToRoute('grille_evaluation_index');
        }

        return $this->render('grille_evaluation/new.html.twig', [
            'grille_evaluation' => $grilleEvaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grille_evaluation_show", methods={"GET"})
     */
    public function show(GrilleEvaluation $grilleEvaluation): Response
    {
        return $this->render('grille_evaluation/show.html.twig', [
            'grille_evaluation' => $grilleEvaluation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="grille_evaluation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, GrilleEvaluation $grilleEvaluation): Response
    {
        $form = $this->createForm(GrilleEvaluationType::class, $grilleEvaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('grille_evaluation_index');
        }

        return $this->render('grille_evaluation/edit.html.twig', [
            'grille_evaluation' => $grilleEvaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="grille_evaluation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GrilleEvaluation $grilleEvaluation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grilleEvaluation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($grilleEvaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('grille_evaluation_index');
    }
}
