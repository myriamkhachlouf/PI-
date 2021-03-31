<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Users;
use App\Repository\EvenementRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{
    /**
     * @Route("/calendrier/", name="calendrier")
     */
    public function index(EvenementRepository $liste_events): Response
    {

        $events = $this->getDoctrine()->getRepository(Evenement::class)->findAll();
        $rdvs = [];




            foreach ($events as $event) {

                    $rdvs [] = [
                        'id' => $event->getId(),
                        'date' => $event->getDate()->format('Y-m-d'),
                        'title' => $event->getNom(),

                    ];
            }




        $data = json_encode($rdvs);
        return $this->render('calendrier/index.html.twig', compact('data'));
    }
}
