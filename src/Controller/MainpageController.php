<?php

namespace App\Controller;

use App\Entity\Bookmaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainpageController extends AbstractController
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager()->getRepository(Bookmaker::class);

        $sort_by_games = ['dota' => $em->findAllByGame('Dota 2'), 'cs' => $em->findAllByGame('CS:GO')];

        return $this->render('mainpage/index.html.twig', [
            'bookmaker' => $sort_by_games,
        ]);
    }
}
