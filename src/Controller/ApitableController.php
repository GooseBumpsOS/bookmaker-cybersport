<?php

namespace App\Controller;

use App\Entity\Bookmaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApitableController extends AbstractController
{
    /**
     * @Route("/api", name="apitable")
     */
    public function index(Request $request)
    {
        if ($request->request->count())
            throw $this->createNotFoundException('Not found 404');

        $emBookmaker = $this->getDoctrine()->getManager()->getRepository(Bookmaker::class);

       $data = $emBookmaker->findAllByGame($request->query->get('game'));

        return new JsonResponse($data);
    }


}

//array(1) {
//  [0]=>
//  object(App\Entity\Bookmaker)#826 (5) {
//    ["id":"App\Entity\Bookmaker":private]=>
//    int(1)
//    ["name":"App\Entity\Bookmaker":private]=>
//    string(16) "Париматч"
//    ["games":"App\Entity\Bookmaker":private]=>
//    string(14) "Dota 2; CS:GO;"
//    ["img":"App\Entity\Bookmaker":private]=>
//    string(3) "img"
//    ["info":"App\Entity\Bookmaker":private]=>
//    string(29) "Лучшая компания"
//  }
//}
