<?php

namespace App\Controller;

use App\Entity\Bookmaker;
use App\Entity\BookmakerRating;
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
        if (!$request->request->count())
            throw $this->createNotFoundException('Not found 404');

        $game = $request->request->get('game');
        $result = [];

        $emBookmaker = $this->getDoctrine()->getManager()->getRepository(Bookmaker::class);
        $emBookmakerRatin = $this->getDoctrine()->getManager()->getRepository(BookmakerRating::class);

        if ($game == 'all')
        {

            $bookmakerMain = $emBookmaker->findAll();

                   for($i=0;$i<count($bookmakerMain);$i++)
                    $avg[] = $emBookmakerRatin->getAvgOfMark($bookmakerMain[$i]->getName());

                   for($i=0;$i<count($bookmakerMain);$i++)
                    array_push($result, ['name' => $bookmakerMain[$i]->getName(), 'rating' => $avg[$i], 'link' => $this->_russianToTranslit($bookmakerMain[$i]->getName())] );

        } else{

            $bookmakerMain = $emBookmaker->findAllByGame($game);

            for($i=0;$i<count($bookmakerMain);$i++)
             $avg[] = $emBookmakerRatin->getAvgOfMark($bookmakerMain[$i]['name']);

            for($i=0;$i<count($bookmakerMain);$i++)
             array_push($result, ['name' => $bookmakerMain[$i]['name'], 'rating' => $avg[$i], 'link' => $this->_russianToTranslit($bookmakerMain[$i]['name'])] );

        }


        return new JsonResponse($result);
    }

    private function _russianToTranslit($str)
        {

            $gost = array(
                "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
                "е"=>"e", "ё"=>"yo","ж"=>"j","з"=>"z","и"=>"i",
                "й"=>"i","к"=>"k","л"=>"l", "м"=>"m","н"=>"n",
                "о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t",
                "у"=>"y","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch",
                "ш"=>"sh","щ"=>"sh","ы"=>"i","э"=>"e","ю"=>"u",
                "я"=>"ya",
                "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
                "Е"=>"E","Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"I",
                "Й"=>"I","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
                "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
                "У"=>"Y","Ф"=>"F","Х"=>"H","Ц"=>"C","Ч"=>"Ch",
                "Ш"=>"Sh","Щ"=>"Sh","Ы"=>"I","Э"=>"E","Ю"=>"U",
                "Я"=>"Ya",
                "ь"=>"","Ь"=>"","ъ"=>"","Ъ"=>"",
                "ї"=>"j","і"=>"i","ґ"=>"g","є"=>"ye",
                "Ї"=>"J","І"=>"I","Ґ"=>"G","Є"=>"YE"

            );
            return strtr($str, $gost);

        }


}