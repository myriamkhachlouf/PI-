<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route ("/backoffice")
 */
class BackofficeController extends AbstractController
{
    /**
     * @Route("/{id}", name="adminpublication_delete", methods={"POST","DELETE"})
     * @param Request $request
     * @param Publication $publication
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function delete(Request $request, Publication $publication, Swift_Mailer $mailer): Response
    {

        // if ($this->isCsrfTokenValid('delete' . $publication->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($publication);
        $entityManager->flush();
        if ($this->isGranted('ROLE_ADMIN')) {
            if ($request->isMethod('POST')) {
                $description = $request->request->get('message');

                // Configure Dompdf according to your needs
                $pdfOptions = new Options();
                $pdfOptions->set('defaultFont', 'Arial');

                // Instantiate Dompdf with our options
                $dompdf = new Dompdf($pdfOptions);

                // Retrieve the HTML generated in our twig file
                $html = $this->renderView('publication/emaildetail.html.twig', [
                    'publication' => $publication,
                    'description' => $description
                ]);
                // Load HTML to Dompdf
                $dompdf->loadHtml($html);

                // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
                $dompdf->setPaper('A4', 'portrait');

                // Render the HTML as PDF
                $dompdf->render();
                $output = $dompdf->output();
                file_put_contents("file.pdf", $output);
                $attachment = (new Swift_Attachment())
                    ->setFilename('detail.pdf')
                    ->setContentType('application/pdf')
                    ->setBody(file_get_contents('file.pdf'));

                $message = (new \Swift_Message('Publication Post'))
                    ->setFrom('mahmoud.frikha@esprit.tn')
                    ->setTo($publication->getPostedby()->getEmail())
                    ->setBody('veuillez trouvez ci-joint les détails de suression de votre article')->attach($attachment);
                $mailer->send($message);
                $this->addFlash('message', 'un mail a été envoyé pour informer le poster');
                return $this->redirectToRoute('adminpost');
            }
        }
    }
    /**
     * @param $id
     * @param $publication_id
     * @param CommentaireRepository $commentaireRepository
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @return Response
     * @Route("/delete/{id}/{publication_id}",name="admindelete_comment")
     */
    public function deletecomment($id, $publication_id, CommentaireRepository $commentaireRepository, PublicationRepository $publicationRepository,Request $request): Response
    {
        if (!empty($commentaireRepository)) {
            $commentaire=$commentaireRepository->find($id);
        }
        $publication=$publicationRepository->find($publication_id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commentaire);
        $entityManager->flush();
        if ($this->isGranted('ROLE_ADMIN'))
            return $this->redirectToRoute('adminpost');
            /*return $this->render('publication/index_admin.html.twig',['publications'=>$publicationRepository->findBy(array(),array('createdAt' => 'DESC')),
                'commentaires' => $commentaireRepository->findBy(array('publication' => $publication),array('createdAt' => 'ASC')),
            ]);*/
    }
    /**
     * @Route ("/adminpost",name="adminpost")
     * @param Request $request
     * @param PublicationRepository $publicationRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function adminpost(Request $request,PublicationRepository $publicationRepository,PaginatorInterface $paginator)
    {
        if( $this->isGranted('ROLE_ADMIN'))
        {
            $donnee= $publicationRepository->findBy(array(),array('createdAt' => 'ASC'));
            $publications=$paginator->paginate(
                $donnee,
                $request->query->getInt('page',1),
                4
            );
            return $this->render('publication/index_admin.html.twig', [
                'publications' => $publications,
            ]);

        }
    }
    /**
     * @Route("/backoffice", name="backoffice")
     */
    public function index(): Response
    {
        return $this->render('backoffice/index.html.twig', [
            'controller_name' => 'BackofficeController',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="adminpublication_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Publication $publication
     * @return Response
     */
    public function editpost(Request $request, Publication $publication): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($this->isGranted('ROLE_ADMIN'))
                return $this->redirectToRoute('adminpost');
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }
}
