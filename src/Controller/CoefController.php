<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CoefController extends AbstractController
{
    /**
     * @Route("/coef", name="coef")
     */
    public function index()
    {
        return $this->render('coef/index.html.twig', [
            'controller_name' => 'CoefController',
        ]);
    }
}
