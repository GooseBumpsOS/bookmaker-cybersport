<?php

namespace App\Controller;

use App\Entity\Bookmaker;
use App\Entity\Carousel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainpageController extends AbstractController
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index()
    {

        $emCarousel = $this->getDoctrine()->getManager()->getRepository(Carousel::class);

         $carousel = $emCarousel->findAllasArray();
       
        return $this->render('mainpage/index.html.twig', [

            'carousel' => $carousel,

        ]);
    }
}
