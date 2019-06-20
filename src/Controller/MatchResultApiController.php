<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchResultApiController extends AbstractController
{
    /**
     * @Route("/match_result/api", name="match_result_api")
     */
    public function index(Request $request)
    {
//        if (!$request->request->count())
//            throw $this->createNotFoundException('Not found 404');

        $result = [];
        $crawler = new Crawler( file_get_contents('https://www.cybersport.ru/base/match?status='. $request->query->get('status') .'&page=1'));

        if ($request->query->get('status') != 'future')
            for ($i = 0; $i < $crawler->filter('.matche__date > time')->count(); $i++)
            {
                $time = $crawler->filter('.matche__date > time')->eq($i)->text();
                $game_logo = explode(' ', $crawler->filter('.matche__date > i')->eq($i)->attr('class'));
                $game_logo = explode('--', $game_logo[array_key_last($game_logo)]);
                $game_logo = $this->_logoStrToImg($game_logo[array_key_last($game_logo)]);
                $command_1 = $crawler->filter('.team__name > .d--phone-inline-block')->eq($i)->text();
                $command_2 =  $crawler->filter('.team__name > .d--phone-inline-block')->eq($i+1)->text();//eq => +1
                $score = $crawler->filter('.matches__link > span')->eq($i)->text() . ' : '. $crawler->filter('.matches__link > span')->eq($i+1)->text();

                array_push($result, ['time' => $time, 'command_1' => $command_1, 'command_2' => $command_2, 'score' => $score, 'logo' => $game_logo]);

            }
        else
            for ($i = 0; $i < $crawler->filter('.matche__date > time')->count(); $i++)
            {
                $time = $crawler->filter('.matche__date > time')->eq($i)->text();
                $game_logo = explode(' ', $crawler->filter('.matche__date > i')->eq($i)->attr('class'));
                $game_logo = explode('--', $game_logo[array_key_last($game_logo)]);
                $game_logo = $this->_logoStrToImg($game_logo[array_key_last($game_logo)]);
                $command_1 = $crawler->filter('.team__name > .d--phone-inline-block')->eq($i)->text();
                $command_2 =  $crawler->filter('.team__name > .d--phone-inline-block')->eq($i+1)->text();//eq => +1
                $score = 'VS';

                array_push($result, ['time' => $time, 'command_1' => $command_1, 'command_2' => $command_2, 'score' => $score, 'logo' => $game_logo]);

            }


    return new JsonResponse($result);
    }

    private function _logoStrToImg($game){

        $name_link = ['dota2' => 'https://i.pinimg.com/originals/2d/cd/80/2dcd80c6f5a21a437313adde93b373d8.png', 'cs-go' => 'https://ih0.redbubble.net/image.455817861.0192/mp,650x642,gloss,f8f8f8,t-pad,750x1000,f8f8f8.jpg', 'lol' => 'https://i.pinimg.com/originals/30/0e/58/300e58c8416a68dcfcf1761501348243.jpg', 'pubg' => 'https://i.pinimg.com/originals/f7/43/c4/f743c45a69f00a4d6254ce42f3803dd1.jpg', 'warcraft-iii' => 'https://bnetproduct-a.akamaihd.net//fb2/ac48e177da0ca6377b8ba8f275c6700b-warcraft3-reforged-enus-1000x500.png', 'overwatch' => 'https://images-na.ssl-images-amazon.com/images/I/41f4npQhADL._SX425_.jpg'];

        return strtr($game, $name_link);

    }
}
