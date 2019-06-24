<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LiveCoefApiController extends AbstractController
{
    /**
     * @Route("/live_coef/api", name="result_api")
     */
    public function index()
    {
        $command_name = [];
        $time = [];
        $coeff = [];
        $result = [];

        $game_name = ['lol', 'dota 2', 'cs:go', 'overwatch'];

        for ($c = 0; $c < count($game_name); $c++)
        {
            $crawler = new Crawler(file_get_contents('https://www.marathonbet.ru/su/betting/e-Sports/' . $game_name[$c]));

            $command_name_crawler = $crawler->filter('.member-link > span');
            $time_crawler = $crawler->filter('.date');
            $coeff_crawler = $crawler->filter('.height-column-with-price');

            for ($i=0; $i < $command_name_crawler->count(); $i+=2)
            {
                array_push($command_name, [$command_name_crawler->eq($i)->text(), $command_name_crawler->eq($i+1)->text()]);
                array_push($time, $time_crawler->eq(intdiv($i, 2))->text()); //тк у нас время в половине записей
                array_push($coeff, [$coeff_crawler->eq($i)->text(),  $coeff_crawler->eq($i+1)->text()]);
            }

            array_unique($time);

            array_push($result, ['name' => $command_name, 'time' => $time, 'coeff' => $coeff]);

            array_unshift($result);

        }


        return new JsonResponse($result);

    }
}
