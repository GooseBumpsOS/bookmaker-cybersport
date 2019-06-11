<?php

namespace App\Controller;

define('password', '$2y$10$2QlKFibkLUq5N71PyAhhrug6JyycE/0XpEx4pGw0DfEjybEe7.sWO');

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CmsController extends AbstractController
{
    /**
     * @Route("/cms-login", name="cms-login")
     */
    public function index(Request $request)
    {
        $wrongPassOrLogin = false;

        $session = new Session();

        if (password_verify($session->get('password'), password))
            return $this->redirectToRoute('cms');

        if ($request->request->count() != 0)
            if ($request->request->get('login') == 'toor' && password_verify($request->request->get('password'), password)){
                $session->start();
                $session->set('password', $request->request->get('password'));

            } else $wrongPassOrLogin = true;





        return $this->render('cms/login.html.twig', [
            'wrongPassOrLogin' => $wrongPassOrLogin,
        ]);
    }

    /**
     * @Route("/cms", name="cms")
     */
    public function cms()
    {
        $session = new Session();

        if (!password_verify($session->get('password'), password))
           return $this->redirectToRoute('cms-login');

        return $this->render('dump.html.twig', [
            'var' => 'OK',
        ]);
    }
}
