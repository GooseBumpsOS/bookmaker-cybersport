<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BonusController extends AbstractController
{
    /**
     * @Route("/bonus", name="bonus")
     */
    public function index()
    {
        return $this->render('bonus/index.html.twig', [
            'controller_name' => 'BonusController',
        ]);
    }
}
