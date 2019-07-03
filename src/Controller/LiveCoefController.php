<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LiveCoefController extends AbstractController
{
    /**
     * @Route("/result", name="result")
     */
    public function index()
    {
        return $this->render('result/index.html.twig', [
            'controller_name' => 'LiveCoefController',
        ]);
    }
}
