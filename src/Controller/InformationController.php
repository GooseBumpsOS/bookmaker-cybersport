<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InformationController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {
        return $this->render('information/contacts.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('information/about.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }

    /**
     * @Route("/policy", name="policy")
     */
    public function policy()
    {
        return $this->render('information/policy.html.twig', [
            'controller_name' => 'InformationController',
        ]);
    }
}
