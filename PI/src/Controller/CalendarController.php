<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Repository\StageRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendrier_stage/", name="calendrier_stage")
     */
    public function index(StageRepository $liste_stages): Response
    {

        $stages = $this->getDoctrine()->getRepository(Stage::class)->findAll();
        $rdvs = [];




        foreach ($stages as $stage) {

            $rdvs [] = [
                'id' => $stage->getId(),
                'start' => $stage->getDateDebut()->format('Y-m-d'),
                'end' => $stage->getDateFin()->format('Y-m-d'),
                'title' => $stage->getTypeDuStage(),

            ];
        }




        $data = json_encode($rdvs);
        return $this->render('calendrier/index.html.twig', compact('data'));
    }
}
