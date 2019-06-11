<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookmakerlistController extends AbstractController
{
    /**
     * @Route("/bookmaker", name="bookmakerlist")
     */
    public function index()
    {

        return $this->render('bookmakerlist/index.html.twig', [
            'controller_name' => 'BookmakerlistController',
        ]);
    }
}
