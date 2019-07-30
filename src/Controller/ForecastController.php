<?php

namespace App\Controller;

use App\Entity\Forecast;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForecastController extends AbstractController
{

    /**
     * @Route("/forecast", name="forecast")
     */
    public function index()
    {

        $em = $this->getDoctrine()->getManager()->getRepository(Forecast::class);

        $dbData = $em->findAll();

        return $this->render('forecast/index.html.twig', [
            'dbData' => $dbData,
        ]);
    }

}
