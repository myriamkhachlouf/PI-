<?php

namespace App\Controller;


use App\Entity\Image;
use App\Entity\Publication;
use App\Entity\Users;
use App\Form\PublicationType;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/publication")
 */
class PublicationController extends AbstractController
{

     /**
     * @Route ("/normalsearch",name="normalsearch",methods={"POST"})
     * @param Request $request
     * @param PublicationRepository $publicationRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function normalsearch(Request $request,PublicationRepository $publicationRepository,PaginatorInterface $paginator)
    {
        if ($request->isMethod('POST')) {
            $message = $request->request->get('search');
            $donnee=$publicationRepository->findPostByTitle($message);
            $publication=$paginator->paginate(
                $donnee,
                $request->query->getInt('page',1),
                4
            );

            return $this->render('publication/index.html.twig', [
                'publications' => $publication,
            ]);
        }
    }
    
    /**
     * @Route ("/senddata",name="senddata")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function senddata(Request $request,NormalizerInterface $Normalizer)
    {
        $repository=$this->getDoctrine()->getRepository(Publication::class);
        //$data=json_decode($request->getContent(),true);
        $publications=$repository->getstat();
        foreach ($publications as $publication){
            $mois[]= $publication['month'];
            $publicationcount[]=$publication['num'];
        }
        $month=[];
        $count=[];
        /** @var integer $i */
        for ($i=1; $i<13; $i++)
        {
            switch ($i) {
                case 1:
                    $month[$i-1]="Jan";
    break;
                case 2:
                    $month[$i-1]="Feb";
    break;
                case 3:
                    $month[$i-1]="Mar";
    break;
                case 4:
                    $month[$i-1]="Apr";
                    break;
                case 5:
                    $month[$i-1]="May";
                    break;
                case 6:
                    $month[$i-1]="Jun";
                    break;
                case 7:
                    $month[$i-1]="Jul";
                    break;
                case 8:
                    $month[$i-1]="Aug";
                    break;
                case 9:
                    $month[$i-1]="Sep";
                    break;
                case 10:
                    $month[$i-1]="Oct";
                    break;
                case 11:
                    $month[$i-1]="Nov";
                    break;
                case 12:
                    $month[$i-1]="Dec";
                    break;
            }

         if  (in_array(strval($i), $mois))
             $count[$i-1]=$publicationcount[array_search(strval($i), $mois)];
         else {
             $count[$i-1]=0;
         }
        }
        $jsonContent = $Normalizer->normalize($publications, 'json');
       //return new Response(json_encode($jsonContent));
    return $this->json(['mois'=>$month,'publicationcount'=>$count]);

    }

    /**
     * @Route("/searchPostx ", name="searchPostx",methods={"POST"})
     * @param Request $request
     * @param NormalizerInterface $Normalizer

     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function searchPostx(Request $request,NormalizerInterface $Normalizer)
    {

        $repository = $this->getDoctrine()->getRepository(Publication::class);
        $data=json_decode($request->getContent(),true);

        $requestString=$data['searchValue'];
        $publications = $repository->findPostByTitle($requestString);
        $jsonContent = $Normalizer->normalize($publications, 'json',['groups'=>'publications:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route ("/views/{id}/{user_id}",name="views")
     * @param $id
     * @param $user_id
     * @param PublicationRepository $publicationRepository
     * @param CommentaireRepository $commentaireRepository
     * @return JsonResponse
     */
    public function views($id, $user_id, PublicationRepository $publicationRepository, CommentaireRepository $commentaireRepository)
    {
        $publication = $publicationRepository->find($id);
        $user = $this->getDoctrine()->getRepository(Users::class)->find($user_id);
        if (!$publication->getSeenby()->contains($user)) {
            $publication->addSeenby($user);
            $publication->setViews($publication->getViews() + 1);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->Json(['views'=>$publication->getViews()]);
    }


    /**
     * @Route("/new", name="publication_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);
        $user = new Users();
        $user=$this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $mainImage= $form->get('mainImage')->getData();
            $fichier= md5(uniqid() .'.'. $mainImage->guessExtension());
            $mainImage->move($this->getParameter('main_directory'),$fichier);
            $coverImage= $form->get('coverImage')->getData();
            $fichier1= md5(uniqid() .'.'. $coverImage->guessExtension());
            $coverImage->move($this->getParameter('cover_directory'),$fichier1);
            $img=new Image();
            $img->setMainUrl($fichier);
            $img->setCoverUrl($fichier1);
            $publication->setImage($img);
            $publication->setPostedby($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('publication_index');
        }

        return $this->render('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param PublicationRepository $publicationRepository
     * @return Response
     * @Route ("/deleteform/{id}",name="deleteform")
     */
public function deleteform($id,PublicationRepository $publicationRepository)
{
    return $this->render('publication/suppressiondetail.html.twig', [
        'publication' => $publicationRepository->find($id),
    ]);
}

    /**
     * @Route("/{id}", name="publication_delete", methods={"POST","DELETE"})
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
            if ($this->isGranted('ROLE_ENTREPRISE') or $this->isGranted('ROLE_CANDIDAT'))
                return $this->redirectToRoute('publication_index');

    }


    /**
     * @Route("/searchbyday",name="search_day")
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     *
     */
    public function searchday(PublicationRepository $publicationRepository,Request $request,PaginatorInterface $paginator)
    {


        $donnee = $publicationRepository->getPublicationByDay();
        $publication = $paginator->paginate(
            $donnee,
            $request->query->getInt('page', 1),
            4
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publication,
        ]);
    }

    /**
     * @Route("/searchbymonth",name="search_month")
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     *
     */
    public function searchmonth(PublicationRepository $publicationRepository, Request $request, PaginatorInterface $paginator)
    {
        $donnee = $publicationRepository->getPublicationByMonth();
        $publication=$paginator->paginate(
            $donnee,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publication,
        ]);
    }

    /**
     * @Route("/searchbyyear",name="search_year")
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     *
     */
    public function searchyear(PublicationRepository $publicationRepository, Request $request, PaginatorInterface $paginator)
    {
        $donnee = $publicationRepository->getPublicationByYear();
        $publication=$paginator->paginate(
            $donnee,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publication,
        ]);
    }
    /**
     * @Route ("/tridatemodification",name="tri_datemodification")
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     *
     */
    public function tridatemodification(PublicationRepository $publicationRepository, Request $request, PaginatorInterface $paginator)
    {
        $donnee=$publicationRepository->getPublicationByDateModification();
        $publication=$paginator->paginate(
            $donnee,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publication,
        ]);

    }
    /**
     * @Route("/tridatecreation",name="tri_datecreation")
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function tridatecreation(PublicationRepository $publicationRepository, Request $request, PaginatorInterface $paginator)
    {
        $donnee=$publicationRepository->getPublicationByDateCreation();
        $publication=$paginator->paginate(
            $donnee,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publication,
        ]);

    }
    /**
     * @Route("/triparnom",name="triparnom")
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function triparNom(PublicationRepository $publicationRepository,Request $request,PaginatorInterface $paginator)
    {
        $donnee=$publicationRepository->getPublicationByName();
        $publication=$paginator->paginate(
            $donnee,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publication,
        ]);

    }

    /**
     * @Route("/", name="publication_index", methods={"GET"})
     * @param PublicationRepository $publicationRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(PublicationRepository $publicationRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $donnee=$publicationRepository->findBy(array(),array('createdAt'=>'DESC'));
        $publication=$paginator->paginate(
          $donnee,
          $request->query->getInt('page',1),
          4
        );
        return $this->render('publication/index.html.twig', [
            'publications' => $publication,
        ]);
    }


    /**
     * @Route("/{id}", name="publication_show", methods={"GET"})
     * @param Publication $publication
     * @return Response
     */
    public function show(Publication $publication): Response
    {
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="publication_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Publication $publication
     * @return Response
     */
    public function edit(Request $request, Publication $publication): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            if ($this->isGranted('ROLE_ENTREPRISE'))
            return $this->redirectToRoute('publication_index');
            /*if ($this->isGranted('ROLE_ADMIN'))
                return $this->redirectToRoute('adminpost');*/
        }

        return $this->render('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }




}

