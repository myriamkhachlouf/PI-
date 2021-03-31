<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\Users;
use App\Repository\CommentaireRepository;
use App\Repository\PublicationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentaireController
 * @package App\Controller
 * @Route("/commentaire")
 */
class CommentaireController extends AbstractController
{
    //edit comment
    /**
     * @param $id
     * @param CommentaireRepository $commentaireRepository
     * @param PublicationRepository $publicationRepository
     * @return Response
     * @Route ("/edit/{id}/{commentaire_id}",name="edit_comment")
     */
    public function editcomment($id,$commentaire_id, CommentaireRepository $commentaireRepository, PublicationRepository $publicationRepository,Request $request)
    {
        if ($request->isMethod('POST')) {
            $publication = $publicationRepository->find($id);
            $commentaire= $commentaireRepository->find($commentaire_id);
            $commentaire->setContenu($request->request->get('message'));
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('publication/blogdetail.html.twig',['publication'=>$publication,
            'commentaires' => $commentaireRepository->findBy(array('publication' => $publicationRepository->find($id)),array('createdAt' => 'ASC')),
        ]);
    }
//to the comment editing page
    /**
     * @Route("/editform/{id}/{publication_id}", name="editform")
     * @param $id
     * @param $publication_id
     * @param CommentaireRepository $commentaireRepository
     * @param PublicationRepository $publicationRepository
     * @return Response
     */
    public function editform($id,$publication_id,CommentaireRepository $commentaireRepository, PublicationRepository $publicationRepository): Response
    {
        $commentaire=$commentaireRepository->find($id);
        $publication=$publicationRepository->find($publication_id);

        return $this->render('commentaire/editform.html.twig',['publication'=>$publication,
            'commentaire' => $commentaire,
        ]);
    }

//
    /**
     * @Route("/{id}", name="commentaire")
     * @param $id
     * @param CommentaireRepository $commentaireRepository
     * @param PublicationRepository $publicationRepository
     * @return Response
     */
    public function index($id,CommentaireRepository $commentaireRepository,PublicationRepository $publicationRepository): Response
    {
        $publication=$publicationRepository->find($id);
        return $this->render('commentaire/postcomments.html.twig', [
            'commentaires' => $commentaireRepository->findBy(array('publication' => $publicationRepository->find($id)),array('createdAt' => 'ASC')),
            'publication'=>$publication,
        ]);
    }
//deleting comment
    /**
     * @param $id
     * @param Commentaire $commentaire
     * @param $publication_id
     * @param CommentaireRepository $commentaireRepository
     * @param $publicationRepository
     * @return Response
     * @Route("/delete/{id}/{publication_id}",name="delete_comment")
     *
     */
    public function delete($id, $publication_id, CommentaireRepository $commentaireRepository, PublicationRepository $publicationRepository,Request $request): Response
    {
        if (!empty($commentaireRepository)) {
            $commentaire=$commentaireRepository->find($id);
        }
        $publication=$publicationRepository->find($publication_id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($commentaire);
        $entityManager->flush();

        if ($this->isGranted('ROLE_ENTREPRISE') or $this->isGranted('ROLE_CANDIDAT'))
        return $this->render('publication/blogdetail.html.twig',['publication'=>$publication,
            'commentaires' => $commentaireRepository->findBy(array('publication' => $publication),array('createdAt' => 'ASC')),
        ]);
        /*else if ($this->isGranted('ROLE_ADMIN'))
            return $this->render('publication/index_admin.html.twig',['publications'=>$publicationRepository->findBy(array(),array('createdAt' => 'DESC')),
                'commentaires' => $commentaireRepository->findBy(array('publication' => $publication),array('createdAt' => 'ASC')),
            ]);*/
    }
//new comment

    /**
     * @Route ("/new/{id}",name="commentaire_new", methods={"get","post"})
     * @param $id
     * @param Request $request
     * @param $publicationRepository
     * @param $commentaireRepository
     * @return Response
     */
    public function new($id, Request $request, PublicationRepository $publicationRepository,CommentaireRepository $commentaireRepository)
    {
        if ($request->isMethod('POST')) {
            $message = $request->request->get('message');
            $user=$this->getUser();
            $commentaire=new Commentaire();
            $commentaire->setContenu($message);
            $em=$this->getDoctrine()->getManager();
            $publication=$this->getDoctrine()->getRepository(Publication::class)->find($id);
            $em->persist($publication);
            $em->flush();
            $commentaire->setPublication($publication);
            $commentaire->setPostedby($user);

            $em->persist($commentaire);
            $em->flush();
        }
        return $this->render('publication/blogdetail.html.twig',['publication'=>$publication,
        'commentaires' => $commentaireRepository->findBy(array('publication' => $publicationRepository->find($id)),array('createdAt' => 'ASC')),
    ]);
    }
}
