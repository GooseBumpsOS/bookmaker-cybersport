<?php

namespace App\Controller;

use App\Entity\Bookmaker;
use App\Entity\BookmakerRating;
use App\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookmakerlistController extends AbstractController
{
    /**
     * @Route("/bookmaker", name="bookmakerlist")
     */
    public function index()
    {

        return $this->render('bookmakerlist/index.html.twig', [
            'controller_name' => 'BookmakerlistController',
        ]);
    }

    /**
     * @Route("/bookmaker/{bookmakerName}", name="bookmakerlist-rating")
     */
    public function rating($bookmakerName, Request $request)
    {
        if ($request->request->count() != 0) { //блок отвечает за добавления комментария в базу

            $dbComment = new BookmakerRating();

            $dbComment->setUserName($request->request->get('name'));
            $dbComment->setComment($request->request->get('msg'));
            $dbComment->setMark($request->request->get('rating'));
            $dbComment->setBookmakerName($this->_translitToRussian($bookmakerName));


            $this->getDoctrine()->getManager()->persist($dbComment);
            $this->getDoctrine()->getManager()->flush();

        }
        $emBookmaker = $this->getDoctrine()->getManager()->getRepository(Bookmaker::class);
        $emBookmakerRatin = $this->getDoctrine()->getManager()->getRepository(BookmakerRating::class);
        $emSeo = $this->getDoctrine()->getManager()->getRepository(Seo::class);

        try{

            $content = $emBookmaker->findOneBy(['name' => $this->_translitToRussian($bookmakerName)]);
            $rating = $emBookmakerRatin->findBy(['bookmaker_name' => $this->_translitToRussian($bookmakerName)]);
            $seo = $emSeo->findOneBy(['news_name' => $this->_translitToRussian($bookmakerName)]);
            $avg = $emBookmakerRatin->getAvgOfMark($this->_translitToRussian($bookmakerName));

        } catch (\TypeError $error){

            $content = $emBookmaker->findOneBy(['name' => $bookmakerName]);
            $rating = $emBookmakerRatin->findBy(['bookmaker_name' => $bookmakerName]);
            $seo = $emSeo->findOneBy(['news_name' => $bookmakerName]);
            $avg = $emBookmakerRatin->getAvgOfMark($bookmakerName);

        } finally {

            $games = $this->_makeGameArray($content->getGames());
        }


        return $this->render('bookmakerlist/rating.html.twig', [
            'content' => $content,
            'rating' => $rating,
            'avg' => round($avg, 1),
            'seo' => $seo,
            'games' => $games,
        ]);
    }

    private function _translitToRussian($str)
    {

        $tr = ["a" => "а", "b" => "б", "v" => "в", "g" => "г", "d" => "д", "e" => "е", "yo" => "ё",
            "j" => "ж", "z" => "з", "i" => "и", "i" => "й", "k" => "к",
            "l" => "л", "m" => "м", "n" => "н", "o" => "о", "p" => "п", "r" => "р", "s" => "с", "t" => "т",
            "y" => "у", "f" => "ф", "h" => "х", "c" => "ц",
            "ch" => "ч", "sh" => "ш", "sh" => "щ", "i" => "ы", "e" => "е", "u" => "у", "ya" => "я", "A" => "А", "B" => "Б",
            "V" => "В", "G" => "Г", "D" => "Д", "E" => "Е", "Yo" => "Ё", "J" => "Ж", "Z" => "З", "I" => "И", "I" => "Й", "K" => "К", "L" => "Л",
            "M" => "М", "N" => "Н", "O" => "О", "P" => "П", "R" => "Р", "S" => "С", "T" => "Т", "Y" => "Ю", "F" => "Ф", "H" => "Х", "C" => "Ц",
            "Ch" => "Ч", "Sh" => "Ш", "Sh" => "Щ", "I" => "Ы", "E" => "Е", "U" => "У", "Ya" => "Я", "'" => "ь", "'" => "Ь", "''" => "ъ",
            "''" => "Ъ", "j" => "ї", "i" => "и", "g" => "ґ", "ye" => "є", "J" => "Ї", "I" => "І", "G" => "Ґ", "YE" => "Є"];

        return strtr($str, $tr);

    }

    private function _makeGameArray($games){

        $result_explode =  explode(';', $games);

        $result = array_filter($result_explode, function($element) {
            return !empty($element);
        });

        return $result;


    }
}
