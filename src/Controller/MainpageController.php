<?php

namespace App\Controller;

use App\Entity\Bookmaker;
use App\Entity\Carousel;
use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainpageController extends AbstractController
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index()
    {

        $emNews = $this->getDoctrine()->getManager()->getRepository(News::class)->getRandom(3);

        $emCarousel = $this->getDoctrine()->getManager()->getRepository(Carousel::class);

         $carousel = $emCarousel->findAll();
       
        return $this->render('mainpage/index.html.twig', [

            'carousel' => $carousel,
            'random_content' => $emNews

        ]);
    }
}
