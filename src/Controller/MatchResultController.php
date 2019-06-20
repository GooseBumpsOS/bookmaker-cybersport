<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MatchResultController extends AbstractController
{
    /**
     * @Route("/match_result", name="match_result")
     */
    public function index()
    {
        return $this->render('match_result/index.html.twig', [
            'controller_name' => 'MatchResultController',
        ]);
    }
}
