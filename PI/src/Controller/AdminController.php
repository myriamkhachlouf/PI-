<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Reclamation;
use App\Entity\Users;
use App\Form\EditUserType;
use App\Form\jugeFormType;
use App\Form\JugementFormationType;
use App\Form\JugementType;
use App\Repository\FormationRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\RegistrationFormType;
use App\Repository\ReclamationRepository;
use App\Repository\UsersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/listU", name="users_list")
     */
    public function listu(UsersRepository $usersRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $users = $usersRepository->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/listU.html.twig', [
            'users' => $users,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);

}


    /**
     * Modifier un utilisateur
     * @Route("/utilisateurs/modifier/{id}",name="modifier_utilisateurs")
     * @param Users $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editUsers($id,Users $user, Request $request)
    {
        $user=$this->getDoctrine()->getRepository(Users::class)->find($id);
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('message','utilisateur modifie avec succes');
            return $this->redirectToRoute("utilisateurs");
        }
        return $this->render('users/editUser.html.twig', [
            'userForm'=>$form->createView()
        ]);
    }

    /**
     * Liste des utilisateurs du site
     *
     * @Route ("/utilisateurs",name="utilisateurs")
     * @param UsersRepository $users
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function usersList( Request $request, PaginatorInterface $paginator){

        $donnees=$this->getDoctrine()->getRepository(Users::class)->findAll();
        $users= $paginator->paginate(
          $donnees,
            $request->query->getInt('page',1),
            4
        );
        return $this->render("admin/users.html.twig",['users'=>$users,
            ]);
    }

    /**
     * Liste des reclamations du site
     *
     * @Route ("/reclamations",name="reclamations",methods={"GET","POST"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function reclamationsList( Request $request, PaginatorInterface $paginator) :Response{
        $em = $this->getDoctrine()->getManager();

        $reclamation1 = $em->getRepository(Reclamation::class)->findAll();
        foreach ($reclamation1 as $r) {

            $now = date_format(new \DateTime('now'), 'Y-m-d');
            $recdate = date_format($r->getDate(), 'Y-m-d');
            $datetime1 = strtotime($now);
            $datetime2 = strtotime($recdate);
            $secs = $datetime1 - $datetime2;// == return sec in difference
            $days = $secs / 86400; //86400 = 60*60*24 : les seconds par jour
            if ($days >= 30 && $r->getEtat()=="Traitée"){
                $r->setEtat("Archivée");
                $em->flush();
            }
        }

        $donnees=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $reclamations= $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            3
        );
        return $this->render("admin/listR.html.twig",['rec'=>$reclamations,
        ]);
    }











    /*****************************Affichage des réclamations qui sont  archivées***********************/

    /**
     * @Route("/show_archive", name="show_archive",methods={"GET"})
     * @return Response
     */
    public function show_Admin_ArchiveAction(Request $request, PaginatorInterface $paginator) :Response
    {

        $em = $this->getDoctrine()->getManager();
        $reclamation1 = $em->getRepository(Reclamation::class)->findBy(['etat' => "Archivée"]);
        $donnees=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $reclamation= $paginator->paginate(
            $reclamation1,
            $request->query->getInt('page',1),
            3
        );

        return $this->render('Admin/List_Archive_Admin.html.twig', ['rec' => $reclamation]);

    }








     /**
      * @Route ("/traiter_reclamation/{id}",name="traiter_reclamation",methods={"GET","POST"})
      * @return Response
      */
    public function traiterReclamationAction(Request $request,$id, PaginatorInterface $paginator):Response
   {

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(Reclamation::class)->find($id);


        $reclamation->setEtat("Traitée");

        $em = $this->getDoctrine()->getManager();
        $em->persist($reclamation);
        $em->flush();
       $donnees=$this->getDoctrine()->getRepository(Reclamation::class)->findAll();
       $reclamations= $paginator->paginate(
           $donnees,
           $request->query->getInt('page',1),
           3
       );
//        $rec1 = $em->getRepository(Reclamation::class)->findAll();
        //return $this->render("admin/ListR.html.twig",['rec' => $reclamations]);
        return $this->redirectToRoute('admin_reclamations', ['rec' => $reclamations]);

    }
//******************************************* Jugement cote admin*******************************//


    public function jugformationAction($id,FormationRepository $formationRepository)
    {

        $sform = $this->createForm(jugeFormType::class,null,
            ['action' => $this->generateUrl('admin_passer_en_jugementformation',["id" => $id])]);
        return $this->render('admin/jugeform.html.twig', [
            'edit_form' => $sform->createView(),
            'coupon' => $formationRepository->find($id)
        ]);
    }
    /**
     * @Route ("/passer_en_jugementformation/{id}", name="passer_en_jugementformation"):
     */
    public function passer_en_jugementformationAction(Request $request,Formation $formation) :Response
    {

        if (!$formation) {
            throw $this->createNotFoundException(' Formation n\'existe pas');
        }
        $form = $this->createForm(jugeFormType::class, $formation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

          //To

            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute("admin_reclamations");
        }

        return $this->render('admin/jugeform.html.twig', [
            'coupon'      => $formation,
            'edit_form'   => $form->createView(),
        ]);




    }


    public function ScoreAction($id,UsersRepository $usersRepository)
    {

        $sform = $this->createForm(JugementType::class,null,
            ['action' => $this->generateUrl('admin_passer_en_jugement',["id" => $id])]);
        return $this->render('admin/edit_score.html.twig', [
            'edit_form' => $sform->createView(),
            'coupon' => $usersRepository->find($id)
        ]);
    }



    /**
     * @Route ("/passer_en_jugement/{id}", name="passer_en_jugement")
     * @IsGranted("ROLE_USER")
     */
    public function passer_en_jugementAction(Request $request,Users $user) :Response
    {

            if (!$user) {
                throw $this->createNotFoundException(' User n\'existe pas');
            }
            $form = $this->createForm(JugementType::class, $user);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()){

                if($user->getScore()>= 5)
                {
                    $user->setEnabled(1);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute("admin_reclamations");
            }

            return $this->render('admin/edit_score.html.twig', [
                'coupon'      => $user,
                'edit_form'   => $form->createView(),
            ]);




    }


}
