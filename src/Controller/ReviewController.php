<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Review;
use App\Form\ReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ReviewController extends AbstractController
{
    /**
     * @Route("/review/add_review", name="add_review")
     */
    public function addreview(Request $request): Response
    {
        $formation = new Review();
        $form = $this->createForm(ReviewType::class,$formation);
        $form->handleRequest($request);


        if($form->isSubmitted()&& $form->isValid())
        {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($formation);

            $entityManager->flush();

            return $this->redirectToRoute('liste_review');

        }

        return $this->render("review/addreview.html.twig", [
            "form_title" => "Ajouter un Review",
            "form" => $form->createView(),
        ]);
    }

    /**
     * @Route("/review/liste_review", name="liste_review")
     */
    public function listeReview(Request $request)
    {
        $review = $this->getDoctrine()->getRepository(Review::class)->findAll();


        return $this->render('review/showreview.html.twig', [
            "review" => $review,
        ]);
    }

    /**
     * @Route("/review/detail_review/{id}", name="detail_review")
     */
    public function DetailReview(Request $request, $id, $review):Response
    {
        $review = $this->getDoctrine()->getRepository(Review::class)->find($id);
        return $this->render('review/detailreview.html.twig', [
            "r" => $review,
        ]);
    }

    /**
     * @Route("/review/delete_review/{id}", name="delete_review")
     */
    public function deleteReview(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $review = $entityManager->getRepository(Review::class)->find($id);
        $entityManager->remove($review);
        $entityManager->flush();
        return $this->redirectToRoute('liste_review');
    }
    /**
     * @Route("/review/edit_review/{id}", name="edit_review")
     */
    public function editReview(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $review = $entityManager->getRepository(Review::class)->find($id);
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            return $this->redirectToRoute('liste_review');
        }

        return $this->render("review/editreview.html.twig", [
            "form_title" => "Modifier une review",
            "form" => $form->createView(),
        ]);
    }


}
