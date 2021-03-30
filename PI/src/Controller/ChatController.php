<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Message;
use App\Entity\Conversation;
use Symfony\Component\WebLink\Link;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConversationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/inbox/{id}", name="my_inbox" , defaults={"id": null})
     */
    public function inbox($id,ConversationRepository $conversationRepository): Response
    {
        $myConvers=[];
        foreach ($conversationRepository->findAll() as $conv) {
            if ($conv->getUsers()->contains($this->getUser())) {
                $myConvers[]=$conv;
            }
        }
        if ($myConvers == []) {
            return  $this->redirectToRoute("users_index");
        }


        $current=null;
        if ($id != null){
            $current=$conversationRepository->find($id);
        }
        $otherperson=null;
        if ($current != null ){
            foreach($current->getUsers() as $someone){
                if ($this->getUser() !== $someone) {
                    $otherperson=$someone;
                }
            }
        }


        return $this->render('chat/inbox.html.twig',[
            "inboxs"  => $myConvers,
            "current" => $current,
            "otherperson" => $otherperson
        ]);
    }    
    
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/startconv/{id}", name="start_conversation" , defaults={"id": null})
     */
    public function start(Users $user,ConversationRepository $conversationRepository): Response
    {
        if($this->getUser() === $user){
            //dd("You can send urself a message.");
            return $this->redirectToRoute("users_index"); 
        }
        $theirConv=null;
        foreach ($conversationRepository->findAll() as $conv) {
            $contain1=$conv->getUsers()->contains($this->getUser());
            $contain2=$conv->getUsers()->contains($user);
            if ( $contain1 && $contain2) {
                $theirConv=$conv;
            }
        }
        if ($theirConv == null) {
            $theirConv = new Conversation();
            $theirConv->addUser($this->getUser());
            $theirConv->addUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($theirConv);
            $entityManager->flush();
        }
        return $this->redirectToRoute("my_inbox",["id"=> $theirConv->getId()]);
    }


    /**
     * @IsGranted("ROLE_USER")
     * @Route("/sendmessage/{id}", name="send_message" , methods={"POST"})
     */
    public function sendMessage(Users $user,Request $request,PublisherInterface $publisher,
    EntityManagerInterface $manager,ConversationRepository $conversationRepository){

        $data = json_decode($request->getContent(), true);

        $content = $data['content'];
        if(trim($content) == null){
            return new JsonResponse(
                ['code'=>400,'message' => "You cant send an empty message."]
            );
        }else {
            $conversation=$conversationRepository->find($data['converID']);
            $message = new Message(); 
            $message->setIsFrom($this->getUser());
            $message->setIsTo($user);
            $message->setSentAt(new \DateTime());
            $message->setContent($content);
            $message->setConversation($conversation);
        
            $manager->persist($message);
            $manager->flush();

            $serializedMessage = $this->get('serializer')
            ->serialize($message, 'json', ['ignored_attributes' => ['isFrom','isTo','conversation','id']]);

            $topic="msgsFor".$user->getId();
            $update = new Update($topic,
            json_encode(['message'=>json_decode($serializedMessage, true) ]),
            false);
            $publisher($update);

            return new JsonResponse(['code'=>200,'message' => json_decode($serializedMessage, true) ]);
        }
    }


    /**
     * @Route("/discover", name="discover")
     */
    public function discover(Request $request)
    {
        $hubUrl = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new Link('mercure', $hubUrl));
        return $this->json(["OK"]);
    }




}
