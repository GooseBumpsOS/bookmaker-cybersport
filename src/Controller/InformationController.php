<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InformationController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        if ($request->request->count() != 0)
        {

            file_put_contents('questions.txt', $request->request->get('fio') . '      ' . $request->request->get('email') .'   '  .  $request->request->get('msg'), FILE_APPEND);

        }

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
