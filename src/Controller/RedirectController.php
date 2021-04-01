<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Formation;
use App\Entity\Review;

class RedirectController extends AbstractController
{

    /**
     * @Route("/frontacceuil", name="frontacceuil")
     */
    public function frontend(): Response
    {
        return $this->render('frontend/frontbody.html.twig', [
            'controller_name' => 'RedirectController',
        ]);
    }
    /**
     * @Route("/backacceuil", name="backacceuil")
     */
    public function backend(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findAll();
        $reviews = $this->getDoctrine()->getRepository(Review::class)->findAll();
        $place = 0;
        foreach($formations as $formation) {
            $place = $place + $formation->getPlace();
        }
        $rating = 0;
        if (count($reviews) != 0) {
            foreach($reviews as $review) {
                $rating = $rating + $review->getRating();
            }
            $rating = $rating/count($reviews);
        }
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'RedirectController',
            'user_number' => count($users),
            'formation_number' => count($formations),
            'rating' => $rating,
            'place' => $place,
            'review_number' => count($reviews)
        ]);
    }

}
