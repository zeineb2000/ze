<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Review;
use App\Entity\Search;
use App\Form\FormationType;
use App\Form\SearchType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\Routing\Annotation\Route;

class FormationController extends AbstractController
{
    /**
     * @Route("/formation/liste_formation", name="liste_formation")
     */
    public function listeFormation(Request $request)
    {
        $formation = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        return $this->render('formation/Affichef.html.twig', [
            "formation" => $formation,
        ]);
    }
    /**
     * @Route("/formation/detail_formation/{id}", name="detail_formation")
     */
    public function DetailFormation(Request $request,$id)
    {
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);


        return $this->render('formation/detailformation.html.twig', [
            "f" => $formation,
        ]);
    }
    /**
     * @Route("/formation/add_formation", name="add_formation")
     */
    public function addFormation(Request $request ): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class,$formation);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($formation);

            $entityManager->flush();

            $this->addFlash('info','added successfully');


            /**$flashy->success('Event created!', '');*/

            return $this->redirectToRoute('liste_formation');

        }


        return $this->render("formation/addformation.html.twig", [
            "form_title" => "Ajouter un formation",
            "form" => $form->createView(),
        ]);
    }
    /**
     * @Route("/formation/delete_fomration/{id}", name="delete_formation")
     */
    public function deleteFormation(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formation = $entityManager->getRepository(Formation::class)->find($id);
        $entityManager->remove($formation);
        $entityManager->flush();
        return $this->redirectToRoute('liste_formation');
    }
    /**
     * @Route("/formation/edit_formation/{id}", name="edit_formation")
     */
    public function editFormation(Request $request, int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $formation = $entityManager->getRepository(Formation::class)->find($id);
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash('info','updated succssefully');
            return $this->redirectToRoute('liste_formation');

        }

        return $this->render("formation/editformation.html.twig", [
            "form_title" => "Modifier une formation",
            "form" => $form->createView(),

        ]);

    }
    /**
     * @Route("/formation/listformation", name="list_formationfront")
     */
    public function listformationfront(Request $request ,PaginatorInterface $paginator)
    {
        $donnee= $this->getDoctrine()->getRepository(Formation::class)->findAll();
        // Paginate the results of the query
        $formation = $paginator->paginate(
        // Doctrine Query, not results
            $donnee,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        $reviews = $this->getDoctrine()->getRepository(Review::class)->findAll();
        foreach($reviews as $review) {
            foreach($formation as $f) {
                if($review->getIdFormation()->getId() == $f->getId()) {
                    $f->rating = $review->getRating();
                }
            }
        }
        foreach($formation as $f) {
            $f->new_price = $f->getPrix() * (100 - $f->getIddiscount()->getPercentege()) / 100;
        }
        return $this->render('formation/Afficherf.html.twig', [
            "formation" => $formation,
            "reviews"=>$reviews
        ]);
    }
    /**
     * @Route("/formation/review", name="formationfront_review")
     */
    public function formationfrontReview(Request $request)
    {
        $review = new Review();
        $rate = $request->request->get('rate');
        $rate = $rate ?? 0.5;
        $review->setRating($rate);
        $idformation = $request->request->get('idformation');
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($idformation);
        $review->setIdFormation($formation);
        $review->setDescription("");

        // save
        $doct = $this->getDoctrine()->getManager();
        $doct->persist($review);
        $doct->flush();
        return $this->redirectToRoute('list_formationfront', ['id'=> $idformation]);
    }
    /**
     * @Route("/formation/listformation/list", name="list_formationfront_list")
     */
    public function listformationfrontlist(Request $request, PaginatorInterface $paginator)
    {
        $donnee= $this->getDoctrine()->getRepository(Formation::class)->findAll();
        // Paginate the results of the query
        $formation = $paginator->paginate(
        // Doctrine Query, not results
            $donnee,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        $reviews = $this->getDoctrine()->getRepository(Review::class)->findAll();
        foreach($reviews as $review) {
            foreach($formation as $f) {
                if($review->getIdFormation()->getId() == $f->getId()) {
                    $f->rating = $review->getRating();
                }
            }
        }
        return $this->render('formation/Afficherflist.html.twig', [
            "formation" => $formation,
        ]);
    }
    /**
     * @Route("/formation/listASCformation", name="list_formationfrontASC")
     */
    public function Sortformationfront(Request $request)
    {
        $formation = $this->getDoctrine()->getRepository(Formation::class)->findBy(array(),array('prix'=>'ASC'));

        return $this->render('formation/listefAsc.html.twig', [
            "formation" => $formation,
        ]);
    }
    /**
     * @Route("/formation/listDESCformation", name="list_formationfrontDESC")
     */
    public function SortformationfrontD(Request $request)
    {
        $formation = $this->getDoctrine()->getRepository(Formation::class)->findBy(array(),array('prix'=>'DESC'));

        return $this->render('formation/listeDsc.html.twig', [
            "formation" => $formation,
        ]);
    }

    /**
     *  @Route("/stat", name="stat")
     */
    public function indexAction()
    {
        $pieChart = new PieChart();
        $repository = $this->getDoctrine()->getRepository(   Formation::class);
        $formation=$repository->findAll();
         $pieChart->getData()->setArrayToDataTable(
            [['Titre', 'prix'],
                ['Work',     11],
                ['Eat',      2],
                ['Commute',  2],
                ['Watch TV', 2],
                ['Sleep',    7]
            ]
        );
        $pieChart->getOptions()->setTitle('My formations Activities');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('formation/chart.html.twig', array('piechart' => $pieChart, 'formation' => $formation));
    }
    /**
     * @Route("/formation/recherche_formation", name="ajaxsearch")
     */
    public function searchAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $formation = $em->getRepository(Formation::class)->findEntitiesByString($requestString);
        if(!$formation)
        {
            $result['formation']['error']="formation introuvable :( ";

        }else{
            $result['formation']=$this->getRealEntities($formation);
        }
        return new Response(json_encode($result));

    }
    public function getRealEntities($formation){
        foreach ($formation as $formation){
            $realEntities[$formation->getId()] = [$formation->getTitre(), $formation->getImageName()];
        }
        return $realEntities;
    }
    /**
     * @Route("/formation/payement/{idformation}", name="payment")
     */
    public function paymentAction(Request $request , int  $idformation )
    {

        $form=$em=$this->getDoctrine()->getManager()->getRepository(Formation::class)->find($idformation);



        $amount=$form->getPrix();


        if ($request->isMethod("GET")) {

            \Stripe\Stripe::setApiKey("sk_test_51IXEsZDIEzaiQ6dmbvdRrpqPHzDoo7bgi3QOWoynS4FLSSC4i4Szn1eUvA7TWIYaCK02sJT4mBPcp3N4oo9lO5JX00pFNgfyRo");


            //dump($amount);
            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $charge = \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'description' => 'Example charge',
                'source' => 'tok_visa',
            ]);


            return $this->render('formation/payment.html.twig', array('id'=>$idformation) );
        }
        return $this->redirectToRoute('list_formationfront_list') ;


    }
    /**
     * @Route("/formation/participatinF/{id}", name="participerF")
     */
    public function participer(Request $request , int  $id )
    {


        $entityManager = $this->getDoctrine()->getManager();
        $formation = $entityManager->getRepository(Formation::class)->find($id);
        $formation->setPlace($formation->getPlace()-1);
        $entityManager->flush();
        $basic  = new \Nexmo\Client\Credentials\Basic('8cc58ac6', '0RMQjyijS9UtwhXt');
        $client = new \Nexmo\Client($basic);
        $message = $client->message()->send([
            'to' => '21653860038',
            'from' => 'Seek&work',
            'text' => 'your formation is addedd successfully'
        ]);

        return $this->redirectToRoute('list_formationfront');



    }







}

