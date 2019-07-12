<?php

namespace App\Controller;

use App\Entity\Bonus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BonusController extends AbstractController
{
    /**
     * @Route("/bonus", name="bonus")
     */
    public function index()
    {

        $bg_img_array = ['https://cdn.pixabay.com/photo/2017/05/08/02/22/game-2294201_960_720.jpg', 'https://cdn.pixabay.com/photo/2018/04/11/17/11/background-3311042_960_720.jpg', 'https://cdn.pixabay.com/photo/2018/02/28/21/44/technology-3189176_960_720.jpg', 'https://cdn.pixabay.com/photo/2019/07/06/20/31/joystick-4321216_960_720.jpg', 'https://cdn.pixabay.com/photo/2019/04/15/11/42/fortnite-4129124_960_720.jpg', 'https://cdn.pixabay.com/photo/2017/06/29/10/28/games-2453777_960_720.jpg'];

        $em = $this->getDoctrine()->getManager()->getRepository(Bonus::class);

        $data = $em->findAll();

        return $this->render('bonus/index.html.twig', [
            'dbData' => $data,
            'bgImg' => $bg_img_array,
        ]);
    }
}
