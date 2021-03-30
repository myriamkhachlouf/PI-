<?php

namespace App\Controller;

use App\Entity\Reclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PdfController extends AbstractController



{ public function pdfAction($id)
{
   /* $user = $this->get('security.token_storage')->getToken()->getUser();*/



    $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);




    $html = $this->renderView('reclamation/pdf.html.twig',['reclamation'=>$reclamation]);



    $filename = sprintf('Réclamation.pdf', date('Y-m-d'));

    return new Response(
        $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
        200,
        [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename), 'encoding' => 'utf-8',
        ]
    );
    return $this->redirectToRoute('reclamation_index');


}









}
