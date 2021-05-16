<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Image;
use App\Entity\Publication;
use App\Entity\Users;
use App\Form\PublicationType;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("PostMobile")
 */
class PostMobileController extends AbstractController
{
    /**
     * @Route ("/add",name="add_Post")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     */
    public function AddPost(Request $request, NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $pub=new Publication();
        $pub->setTitle($request->get('title'));
        $pub->setContenu($request->get('contenu'));
        $pub->setViews(0);
        $user=new Users();
        $user=$this->getDoctrine()->getRepository(Users::class)->find($request->get('postedby_id'));
        $pub->setPostedby($user);
        $em->persist($pub);
        $em->flush();
        $jsonContent = $Normalizer->normalize($pub,'json',['groups'=>'publications:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/AllPosts", name="AllPosts")
     * @param PublicationRepository $publicationRepository
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function GetAllPosts(PublicationRepository $publicationRepository,NormalizerInterface $normalizer): Response
    {
        $publication=$publicationRepository->findBy(array(),array('createdAt'=>'DESC'));
        foreach($publication as $p)
        {
            $user=new Users();
            $user=$p->getPostedby();
            $p->setPostedbyId($user->getId());
        }
        $jsonContent=$normalizer->normalize($publication,'json',['groups'=>'publicationJson:read']);

        // dd($jsonContent);
        return new JsonResponse(/*json_encode*/($jsonContent));
    }
    /**
     * @Route("/DeleteMailAttach", name="DeleteMailAttach")
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function deleteMailAttach(Request $request, Swift_Mailer $mailer): Response
    {
        $em=$this->getDoctrine()->getManager();
        $pub= $this->getDoctrine()->getRepository(Publication::class)->find($request->get('id'));
        $com=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('publication' =>$pub));

        foreach($com as $c) {

            $em->remove($c);
            $em->flush();
        }
        $em->remove($pub);
        $em->flush();

                $description = $request->get('reasons');
                // Configure Dompdf according to your needs
                $pdfOptions = new Options();
                $pdfOptions->set('defaultFont', 'Arial');
                // Instantiate Dompdf with our options
                $dompdf = new Dompdf($pdfOptions);
                // Retrieve the HTML generated in our twig file
                $html = $this->renderView('publication/emaildetail.html.twig', [
                    'publication' => $pub,
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
                    ->setTo($pub->getPostedby()->getEmail())
                    ->setBody('veuillez trouvez ci-joint les détails de suression de votre article')->attach($attachment);
                $mailer->send($message);
                $this->addFlash('message', 'un mail a été envoyé pour informer le poster');
        return new Response("Done");
            }

    /**
     * @Route ("/postviews",name="postviews")
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @return Response
     */
    public function postviews(PublicationRepository $publicationRepository,Request $request)
    {
        $publication = $publicationRepository->find($request->get('id'));
        $user = $this->getDoctrine()->getRepository(Users::class)->find($request->get('postedby_id'));
        if (!$publication->getSeenby()->contains($user)) {
            $publication->addSeenby($user);
            $publication->setViews($publication->getViews() + 1);
            $this->getDoctrine()->getManager()->flush();
        }
        return new Response("Done");
    }
    /**
     * @Route ("/DeletePost",name="deletePost")
     *
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     */
    public function DeletePost(Request $request, NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $pub= $this->getDoctrine()->getRepository(Publication::class)->find($request->get('id'));
        $com=$this->getDoctrine()->getRepository(Commentaire::class)->findBy(array('publication' =>$pub));

        foreach($com as $c) {
       
            $em->remove($c);
            $em->flush();
        }
        $em->remove($pub);
        $em->flush();
        return new Response("Deleted Successfully");
    }
    /**
     * @Route("/searchPosty ", name="searchPosty",methods={"POST"})
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function searchPosty(Request $request,NormalizerInterface $Normalizer)
    {

        $repository = $this->getDoctrine()->getRepository(Publication::class);
        $requestString=$request->get('title');

        $publications = $repository->findPostByTitle($requestString);
        foreach($publications as $p)
        {
            $user=new Users();
            $user=$p->getPostedby();
            $p->setPostedbyId($user->getId());
        }
        $jsonContent = $Normalizer->normalize($publications, 'json',['groups'=>'publicationJson:read']);
        return new JsonResponse(/*json_encode*/($jsonContent));
    }

    /**
     * @Route("/GetPost",name="GetPost")
     * @param PublicationRepository $publicationRepository
     * @param NormalizerInterface $normalizer
     * @param Request $request
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function GetPost(PublicationRepository $publicationRepository,NormalizerInterface $normalizer,Request $request)
    {
        $publication=$publicationRepository->find($request->get('id'));
        $user=new Users();
        $user=$publication->getPostedby();
        $publication->setPostedbyId($user->getId());
        $jsonContent=$normalizer->normalize($publication,'json',['groups'=>'publicationJson:read']);

        return new JsonResponse(($jsonContent));
    }








    /**
     * @Route("/GetPostComments/{id}", name="commentaire")
     * @param $id
     * @param CommentaireRepository $commentaireRepository
     * @param PublicationRepository $publicationRepository
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function GetPostComments($id,CommentaireRepository $commentaireRepository,PublicationRepository $publicationRepository,NormalizerInterface $normalizer): Response
    {

        $commentaires=$commentaireRepository->findBy(array('publication' => $publicationRepository->find($id)),array('createdAt' => 'ASC'));
        foreach($commentaires as $c)
        {
            $user=new Users();
            $user=$c->getPostedby();
            $c->setPostedbyId($user->getId());
        }
        $jsonContent=$normalizer->normalize($commentaires,'json',['groups'=>'commentJson:read']);
        return new JsonResponse(/*json_encode*/($jsonContent));
    }
    /**
     * @Route ("/DeleteComment",name="delete_Post")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     */
    public function DeleteComment(Request $request, NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $com= $this->getDoctrine()->getRepository(Commentaire::class)->find($request->get('id'));
        $em->remove($com);
        $em->flush();
        return new Response("Deleted Successfully");
    }
    /**
     * @Route ("/addComment",name="add_Comment")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     */
    public function AddComment(Request $request, NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $com=new Commentaire();
        $pub=new Publication();
        $pub=$this->getDoctrine()->getRepository(Publication::class)->find($request->get('publication_id'));
        $user=new Users();
        $user=$this->getDoctrine()->getRepository(Users::class)->find($request->get('postedby_id'));
        $com->setContenu($request->get('contenu'));
        $com->setPublication($pub);
        $com->setPostedby($user);
        $em->persist($com);
        $em->flush();
        //$jsonContent = $Normalizer->normalize($pub,'json',['groups'=>'publications:read']);
        //return new Response(json_encode($jsonContent));
        return new Response("Added Successfully");
    }

    /**
     * @Route ("/UpdatePost",name="UpdatePost")
     * @param Request $request
     * @return Response
     */
    public function UpdatePost(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $pub=$this->getDoctrine()->getRepository(Publication::class)->find($request->get('id'));
        $pub->setTitle($request->get('title'));
        $pub->setContenu($request->get('contenu'));
        $pub->setUpdatedAt(new \DateTime('now'));
        $em->flush();
        return new Response("Updated Successfully");
    }
    /**
     * @Route ("/UpdateComment",name="UpdateComment")
     * @param Request $request
     * @return Response
     */
    public function UpdateComment(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $com=$this->getDoctrine()->getRepository(Commentaire::class)->find($request->get('id'));
        $com->setContenu($request->get('contenu'));
        $em->flush();
        return new Response("Updated Successfully");
    }
}
