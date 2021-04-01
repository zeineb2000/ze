<?php

namespace App\Controller;

use App\Entity\Discount;
use App\Form\DiscountType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\Routing\Annotation\Route;

class DiscountController extends AbstractController
{
    /**
     * @Route("/discount/liste_discount", name="liste_discount")
     */
    public function listeDiscount(Request $request)
    {
        $discount = $this->getDoctrine()->getRepository(Discount::class)->findAll();
        return $this->render('discount/index.html.twig', [
            "discount" => $discount,
        ]);
    }
    /**
     * @Route("/discount/add_discount", name="add_discount")
     */
    public function addDiscount(Request $request ): Response
    {
        $discount = new Discount();
        $form = $this->createForm(DiscountType::class,$discount);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($discount);

            $entityManager->flush();
            $this->addFlash('info','added successfully');

            /**$flashy->success('Event created!', '');*/



            return $this->redirectToRoute('liste_discount');

        }

        return $this->render("discount/adddiscount.html.twig", [
            "form_title" => "Ajouter un discount",
            "form" => $form->createView(),
        ]);
    }
    /**
     * @Route("/discount/delete_discount/{id}", name="delete_discount")
     */
    public function deleteDiscount(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $discount = $entityManager->getRepository(Discount::class)->find($id);
        $entityManager->remove($discount);
        $entityManager->flush();
        return $this->redirectToRoute('liste_discount');
    }
    /**
     * @Route("/discount/edit_discount/{id}", name="edit_discount")
     */
    public function editDiscount(Request $request, int $id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $discount = $entityManager->getRepository(Discount::class)->find($id);
        $form = $this->createForm(DiscountType::class, $discount);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            $this->addFlash('info','updated succssefully');
            return $this->redirectToRoute('liste_discount');
        }

        return $this->render("discount/editdiscount.html.twig", [
            "form_title" => "Modifier une discount",
            "form" => $form->createView(),
        ]);

    }
}

