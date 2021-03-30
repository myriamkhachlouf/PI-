<?php

namespace App\Controller;


use Symfony\Component\Security\Core\Security;
use App\Entity\Formation;
use App\Entity\Reclamation;
use App\Entity\Users;
use App\Form\Reclamation2Type;
use App\Form\Reclamation3Type;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Doctrine\DBAL\Types\TextType;
use Knp\Snappy\Pdf;
use App\Service\FileUploader;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use phpDocumentor\Reflection\DocBlock\Description;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType as DateAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Twig\Environment;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;





/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
    private $tokenStorage;


    public function __construct(DompdfWrapperInterface $wrapper,TokenStorageInterface $tokenStorage)
    {
        $this->wrapper = $wrapper;
        $this->tokenStorage = $tokenStorage;

    }


/******************************** affichage des reclamations coté candidat **************************/

    /**
     * @Route("/reclamation_index", name="reclamation_index")
     */
    public function index(Request $request, PaginatorInterface $paginator) :Response// Nous ajoutons les paramètres requis
    {
        $usern = $this->tokenStorage->getToken()->getUser();
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findBy(['user'=>$usern]);
        $array=array();

        foreach ($reclamation as $r) {


            if ($r->getEtat() != "Archivée") {
                $array[] = $r;


            }
        }

        $reclamations = $paginator->paginate(
            $array, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    /**
     * @Route("/d", name="testChart",methods={"GET"})
     */
    public function testChartAction(): Response

    {

        // $em= $this->getDoctrine()->getManager();

        //  $query = $em->createQuery('SELECT r.etat as statut,count(r.etat) as nb FROM App\Entity\Reclamation r GROUP BY r.etat');
        //  $resultat=$query->getResult();
        //  $data=array();
        /*   foreach ($resultat as $values){
               $a=array($values['statut'],intval($values['nb']));
               array_push($data,$a);
           }
           if ( $data == null   ){
               return $this->render("Admin/404.html.twig");
           }else {
               $pieChart->getData()->setArrayToDataTable($data, true);
               $pieChart->getOptions()->setIs3D(true);
               $pieChart->getOptions()->setTitle("Pourcentages des réclamations par statut");
               $pieChart->getOptions()->setHeight(500);
               $pieChart->getOptions()->setWidth(900);
               $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
               $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
               $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
               $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
               $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

   */
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
                ['Work',     11],
                ['Eat',      2],
                ['Commute',  2],
                ['Watch TV', 2],
                ['Sleep',    7]
            ]
        );
        $pieChart->getOptions()->setTitle('My Daily Activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('dashboard.html.twig', [
            "piechart" => $pieChart,
        ]);
        //belehi trah njarbou n3adiw des données kima jé momken ytala3 fiha fer8a la7dha bark
    }


    // partie crud candidat //


/************************************** affichage des candidats ***************************/

    /**
     * @Route("/listedescandidats", name="listedescandidats", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */

    public function listdescandidatsAction()
    {

        $usern = $this->tokenStorage->getToken()->getUser();

        $users=$this->getDoctrine()->getManager()->getRepository(Users::class)->findAll();
        return $this->render('reclamation/listedescandidats.html.twig', [

            'users' => $users,
            'ap'=>$usern
    ]);
    }


    /**
     * @Route("/listedesentreprises", name="listedesentreprises", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function listedesentreprisesAction(){

        $usern = $this->tokenStorage->getToken()->getUser();

        $users=$this->getDoctrine()->getManager()->getRepository(Users::class)->findAll();
        return $this->render('reclamation/listedesentreprises.html.twig', [

            'users' => $users,
            'ap'=>$usern
        ]);


    }

    /**
     * @Route("/listedesformations", name="listedesformations", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function listedesformationsAction(){

        $usern = $this->tokenStorage->getToken()->getUser();

        $formations=$this->getDoctrine()->getManager()->getRepository(Formation::class)->findAll();
        return $this->render('reclamation/listedesformations.html.twig', [

            'formations' => $formations
        ]);


    }

    /**
     * @Route("/newcandidat", name="newcandidat", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newcandidat(Request $request,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();



        $test = $request->query->get('id');
        $test=$em->getRepository(Users::class)->find($test);
        $test2 = $request->query->get('id');
        $form = $this->createForm(Reclamation2Type::class,$reclamation)

            ->add('objet',ChoiceType::class, [
                'choices' => [
                    'Commentaire inapproprié' => 'Commentaire inapproprié',
                    'Acte abusive' => 'Acte abusive',
                    'Autre' => 'Autre',
                ]])



            ->add('description'/*TextType::class,['label'=>'Description de reclamation',
                'attr'=>['palceholder'=>'Veuillez ecrire la description ici..','required'=>true]]*/)

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label'=>'télècharger photo',
                'allow_delete' => true,
                'delete_label' => 'Supprimer image',
                'download_label' => 'Télècharger un fichier',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptchaUserRegistration',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again',
                    ]),
                ],
            ))

            // ... ///
        ;
        $form->handleRequest($request);

        $reclamation->setEtat("En attente");
        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->tokenStorage->getToken()->getUser(); //token storage tjib biha user
            $reclamation->setUser($user);
            $reclamation->setDate(new \DateTime('now'));
            $reclamation->setTarget($test);
            $reclamation->setTarget_id($test2);
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $flashy->success('Event created!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);


    }

    /************************************ ajouter une reclamation coté candidat*************************/


    /**
     * @Route("/newformation", name="newformation", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newformation(Request $request,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();



        $test = $request->query->get('id');
        $test=$em->getRepository(Formation::class)->find($test);
        $test2 = $request->query->get('id');
        $form = $this->createForm(ReclamationType::class,$reclamation)

            ->add('objet',ChoiceType::class, [
                'choices' => [
                    'Formation mal détalliée' => 'Formation mal détalliée',
                    'Formation mal passée' => 'Formation mal passée',
                    'Autre' => 'Autre',
                ]])



            ->add('description'/*TextType::class,['label'=>'Description de reclamation',
                'attr'=>['palceholder'=>'Veuillez ecrire la description ici..','required'=>true]]*/)

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label'=>'télècharger photo',
                'allow_delete' => true,
                'delete_label' => 'Supprimer image',
                'download_label' => 'Télècharger un fichier',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptchaUserRegistration',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again',
                    ]),
                ],
            ))

            // ... ///
        ;
        $form->handleRequest($request);

        $reclamation->setEtat("En attente");
        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->tokenStorage->getToken()->getUser(); //token storage tjib biha user
            $reclamation->setUser($user);
            $reclamation->setDate(new \DateTime('now'));
            $reclamation->setTargetf($test);
            $reclamation->setTarget_idf($test2);
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $flashy->success('Event created!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);


    }

    /**
     * @Route("/newentreprise", name="newentreprise", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function newentreprise(Request $request,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reclamation = new Reclamation();



        $test = $request->query->get('id');
        $test=$em->getRepository(Users::class)->find($test);
        $test2 = $request->query->get('id');


        $form = $this->createForm(Reclamation3Type::class,$reclamation)

        ->add('objet',ChoiceType::class, [
        'choices' => [
            'Entretien mal fait' => 'Entretien mal fait',
            'Rendez-vous annulé' => 'Rendez-vous annulé',
            'Autre' => 'Autre',
        ]])
            ->add('description'/*TextType::class,['label'=>'Description de reclamation',
                'attr'=>['palceholder'=>'Veuillez ecrire la description ici..','required'=>true]]*/)

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label'=>'télècharger photo',
                'allow_delete' => true,
                'delete_label' => 'Supprimer image',
                'download_label' => 'Télècharger un fichier',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])
            ->add('captchaCode', CaptchaType::class, array(
                'captchaConfig' => 'ExampleCaptchaUserRegistration',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again',
                    ]),
                ],
            ))

            // ... ///
        ;
        $form->handleRequest($request);

        $reclamation->setEtat("En attente");
        if ($form->isSubmitted() && $form->isValid()) {

//            $file = $form->get('image')->getData();
//            $fileName= md5(uniqid()).'.'.$file->guessExtension();
//            $file->move($this->getParameter('uploads_directory'), $fileName);
//            $reclamation->setImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->tokenStorage->getToken()->getUser(); //token storage tjib biha user
            $reclamation->setUser($user);
            $reclamation->setDate(new \DateTime('now'));
            $reclamation->setTarget($test);
            $reclamation->setTarget_id($test2);
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $flashy->success('Event created!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);


    }





/*************************************PDF**************************/

    /**
     * @Route("/pdf/{id}", name="reclamation_pdf", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function pdfAction($id, Pdf $pdf)
    {
        /* $user = $this->get('security.token_storage')->getToken()->getUser();*/



        $reclamation=$this->getDoctrine()->getRepository(Reclamation::class)->find($id);




        //$html = $this->renderView('reclamation/pdf.html.twig');





      /*  return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="export.pdf"'
            )
        );*/

     //   return $this->redirectToRoute('reclamation_index');
        $html = $this->renderView('reclamation/pdf.html.twig',["rec"=>$reclamation]);
        $this->wrapper->getStreamResponse($html, 'document.pdf')->send();

        return $this->redirectToRoute('reclamation_index');

        //return new PdfResponse($pdf->getOutputFromHtml($html), 'invoice.pdf');


    }


    /**
     * @Route("/{id}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reclamation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reclamation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reclamation $reclamation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index');
    }

    /**
     * @Route("/SearchByEtat", name="SearchByEtat")
     */
    public function SearchByEtat(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Reclamation::class);
        $requestString=$request->get('searchValue');
        $reclamation = $repository->findByEtat($requestString);
        $jsonContent = $Normalizer->normalize($reclamation, 'json',['groups'=>'reclamations:read']);
        $retour=json_encode($jsonContent);
        return new Response($retour);

    }
    /**
     * @Route("/trierindex", name="trier_index")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function TrierParDateQB(Request $request, PaginatorInterface $paginator) :Response// Nous ajoutons les paramètres requis
    {
        $donnees =$this->getDoctrine()->getRepository(Reclamation::class)->OrderByDateQB();

        $reclamations =  $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->redirectToRoute('reclamation_index', ['reclamations' => $reclamations]);

    }


/*return $this->render('reclamation/index.html.twig', [
'reclamations' => $reclamations,
]);*/






}