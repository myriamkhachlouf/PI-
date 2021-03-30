<?php

namespace App\Controller;

use App\Entity\Users;

use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register/{role}", name="app_register")
     */
    public function register($role,Request $request, UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator,
                                \Swift_Mailer $mailer): Response
    {

        $user = new Users();
        $user->addRole($role);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //generation du token d activation
            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $message = (new \Swift_Message('Activation de votre compte'))
                // On attribue l'expéditeur
                ->setFrom('khedmtech@gmail.com')
                // On attribue le destinataire
                ->setTo($user->getEmail())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/activation/{token}", name="activation")
     */
    public function activation($token, UsersRepository $usersRepo){
        //si un utilisateur a ce token
        $user=$usersRepo->findOneBy(['activation_token'=>$token]);
        //si aucun utilisateur n'existe avec ce token
        if(!$user) {
            //Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n existe pas');
        }
            //On supprime le token
            $user->setActivationToken(null);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //on envoie un message flash
            $this->addFlash('message','Vous avez bien active votre compte');

            //retour a l'accueil
        return $this->redirectToRoute('first');

    }
}
