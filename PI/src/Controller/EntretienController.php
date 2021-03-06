<?php

namespace App\Controller;

use App\Entity\Entretien;
use App\Form\EntretienType;
use App\Repository\EntretienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/entretien")
 */
class EntretienController extends Controller
{
    /**
     * @Route("/trieren_index", name="trieren_index", methods={"GET"})
     */
    public function index1(EntretienRepository $entretienRepository,Request $request): Response
    {$allentretiens = $entretienRepository->listOrderByDate();
        $entretiens = $this->get('knp_paginator')->paginate(
        // Doctrine Query, not results
            $allentretiens,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );
        return $this->render('entretien/index.html.twig', [
            'entretiens' => $entretiens,
        ]);
    }

    /**
     * @Route("/", name="entretien_index", methods={"GET"})
     */
    public function index(EntretienRepository $entretienRepository,Request $request, PaginatorInterface $paginator): Response
    { $allentretiens = $entretienRepository->findAll();
        $entretiens = $this->get('knp_paginator')->paginate(
    // Doctrine Query, not results
        $allentretiens,
        // Define the page parameter
        $request->query->getInt('page', 1),
        // Items per page
        3
    );

        return $this->render('entretien/index.html.twig', [
            'entretiens' => $entretiens
        ]);
    }


    /**
     * @Route("/stat_entretien", name="stat_entretien", methods={"GET"})
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @param $entretienscount
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function senddata(Request $request, NormalizerInterface $Normalizer )
    {
        $repository=$this->getDoctrine()->getRepository(Entretien::class);
        //$data=json_decode($request->getContent(),true);
        $entretiens=$repository->getstat();
        foreach ($entretiens as $entretien){
            $mois[]= $entretiens['month'];
            $entretienscount[]=$entretiens['num'];
        }
        $month=[];
        $count=[];
        $i =0;
        if  (in_array(strval($i), $mois))
            $count[$i-1]=$entretienscount[array_search(strval($i), $mois)];
        else {
            $count[$i-1]=0;
        }

        $jsonContent = $Normalizer->normalize($entretiens, 'json');
        //return new Response(json_encode($jsonContent));
        return $this->json(['mois'=>$month,'entretienscount'=>$count]);

    }



    /**
     * @Route("/new", name="entretien_new", methods={"GET","POST"})
     */
    public function new(Request $request, \Swift_Mailer $mailer): Response
    {
        $entretien = new Entretien();
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entretien->setConfirmation(false);
            $entityManager->persist($entretien);
            $entityManager->flush();

            //mail
            $message = (new \Swift_Message('Mail de confirmation'))
                ->setFrom('slim.maali@esprit.tn')
                ->setTo('myriam.khachlouf@esprit.tn')//$entretien->getCadidature()->getCandidat()->getEmail()
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'emails/confirmation.html.twig',
                        ['name' => $entretien->getCadidature()->getCandidat()->getNom(), 'idE' => $entretien->getId()]
                    ),
                    'text/html'
                );

            $mailer->send($message);


            return $this->redirectToRoute('entretien_index');
        }

        return $this->render('entretien/new.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entretien_show", methods={"GET"})
     */
    public function show(Entretien $entretien): Response
    {
        return $this->render('entretien/show.html.twig', [
            'entretien' => $entretien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="entretien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Entretien $entretien): Response
    {
        $form = $this->createForm(EntretienType::class, $entretien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('entretien_index');
        }

        return $this->render('entretien/edit.html.twig', [
            'entretien' => $entretien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="entretien_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Entretien $entretien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entretien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($entretien);
            $entityManager->flush();
        }

        return $this->redirectToRoute('entretien_index');
    }

    /**
     * @Route("/confirmer/{id}", name="confirmation_entretien", methods={"GET"})
     */
    public function confirmation_entretien(EntretienRepository $entretienRepository,Request $request, Entretien $entretien): Response
    {
        $allentretiens = $entretienRepository->findAll();
        $entretiens = $this->get('knp_paginator')->paginate(
            // Doctrine Query, not results
            $allentretiens,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            3
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entretien->setConfirmation(true);
        $entityManager->persist($entretien);
        $entityManager->flush();

        return $this->render('entretien/index.html.twig', [
            'entretiens' => $entretiens
        ]);
    }





}
