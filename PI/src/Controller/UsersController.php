<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\EditUserType;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Security\Core\Security;



class UsersController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function privatePage() : Response
    {
        $user = $this->security->getUser();
    }
    /**
     * @Route("/show", name="show")
     */
    public function show(Security $security): Response
    {
        $user = $this->security->getUser();
        return $this->render('users/showUser.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Modifier un utilisateur
     * @Route("/utilisateur/modifier/{id}",name="modifier_utilisateur")
     * @param Users $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editUser($id,Users $user, Request $request)
    {
        $user=$this->getDoctrine()->getRepository(Users::class)->find($id);
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('message','utilisateur modifie avec succes');
            return $this->redirectToRoute("show");
        }
        return $this->render('users/editUser.html.twig', [
            'userForm'=>$form->createView()
            ]);

    }









}


