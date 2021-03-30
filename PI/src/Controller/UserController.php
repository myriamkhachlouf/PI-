<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/chatusers", name="users_index")
     */
    public function users(UsersRepository $usersRepository){

        return $this->render('chatusers/index.html.twig',[
            "users"  => $usersRepository->findAll()
        ]);
    }
}
