<?php

namespace App\Controller;

use App\Entity\Bookmaker;
use App\Entity\BookmakerRating;
use SimpleXMLElement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    /**
     * @Route("/api", name="apitable")
     */
    public function bookmakerTable(Request $request)
    {
        if (!$request->request->count())
            throw $this->createNotFoundException('Not found 404');

        $game = $request->request->get('game');
        $result = [];

        $emBookmaker = $this->getDoctrine()->getManager()->getRepository(Bookmaker::class);
        $emBookmakerRating = $this->getDoctrine()->getManager()->getRepository(BookmakerRating::class);

        if ($game == 'all') {

            $bookmakerMain = $emBookmaker->findAll();

            for ($i = 0; $i < count($bookmakerMain); $i++)
                $avg[] = $emBookmakerRating->getAvgOfMark($bookmakerMain[$i]->getName());

            for ($i = 0; $i < count($bookmakerMain); $i++)
                array_push($result, ['name' => $bookmakerMain[$i]->getName(), 'rating' => $avg[$i], 'link' => $this->_russianToTranslit($bookmakerMain[$i]->getName()), 'link_to_site' => $bookmakerMain[$i]->getLink(), 'logo' => $bookmakerMain[$i]->getImg()]);

        } else {

            $bookmakerMain = $emBookmaker->findAllByGame($game);

            for ($i = 0; $i < count($bookmakerMain); $i++)
                $avg[] = $emBookmakerRating->getAvgOfMark($bookmakerMain[$i]['name']);

            for ($i = 0; $i < count($bookmakerMain); $i++)
                array_push($result, ['name' => $bookmakerMain[$i]['name'], 'rating' => $avg[$i], 'link' => $this->_russianToTranslit($bookmakerMain[$i]['name']), 'link_to_site' => $bookmakerMain[$i]['link'], 'logo' => $bookmakerMain[$i]['img']]);

        }


        return new JsonResponse($result);
    }

    private function _russianToTranslit($str)
    {

        $gost = array(
            "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d",
            "е" => "e", "ё" => "yo", "ж" => "j", "з" => "z", "и" => "i",
            "й" => "i", "к" => "k", "л" => "l", "м" => "m", "н" => "n",
            "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t",
            "у" => "y", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch",
            "ш" => "sh", "щ" => "sh", "ы" => "i", "э" => "e", "ю" => "u",
            "я" => "ya",
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D",
            "Е" => "E", "Ё" => "Yo", "Ж" => "J", "З" => "Z", "И" => "I",
            "Й" => "I", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
            "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
            "У" => "Y", "Ф" => "F", "Х" => "H", "Ц" => "C", "Ч" => "Ch",
            "Ш" => "Sh", "Щ" => "Sh", "Ы" => "I", "Э" => "E", "Ю" => "U",
            "Я" => "Ya",
            "ь" => "", "Ь" => "", "ъ" => "", "Ъ" => "",
            "ї" => "j", "і" => "i", "ґ" => "g", "є" => "ye",
            "Ї" => "J", "І" => "I", "Ґ" => "G", "Є" => "YE"

        );
        return strtr($str, $gost);

    }

    /**
     * @Route("/match_result/api", name="match_result_api")
     */
    public function matchResult(Request $request)
    {
//        if (!$request->request->count())
//            throw $this->createNotFoundException('Not found 404');

        $result = [];
        $crawler = new Crawler(file_get_contents('https://www.cybersport.ru/base/match?status=' . $request->request->get('status') . '&page=1'));

        if ($request->request->get('status') != 'future')
            for ($i = 0; $i < $crawler->filter('.matche__date > time')->count(); $i++) {
                $time = $crawler->filter('.matche__date > time')->eq($i)->text();
                $game_logo = explode(' ', $crawler->filter('.matche__date > i')->eq($i)->attr('class'));
                $game_logo = explode('--', $game_logo[array_key_last($game_logo)]);
                $game_logo = $this->_logoStrToImg($game_logo[array_key_last($game_logo)]);
                $command_1 = $crawler->filter('.team__name > .d--phone-inline-block')->eq($i)->text();
                $command_2 = $crawler->filter('.team__name > .d--phone-inline-block')->eq($i + 1)->text();//eq => +1
                $score = $crawler->filter('.matches__link > span')->eq($i)->text() . ' : ' . $crawler->filter('.matches__link > span')->eq($i + 1)->text();

                array_push($result, ['time' => $time, 'command_1' => $command_1, 'command_2' => $command_2, 'score' => $score, 'logo' => $game_logo]);

            }
        else
            for ($i = 0; $i < $crawler->filter('.matche__date > time')->count(); $i++) {
                $time = $crawler->filter('.matche__date > time')->eq($i)->text();
                $game_logo = explode(' ', $crawler->filter('.matche__date > i')->eq($i)->attr('class'));
                $game_logo = explode('--', $game_logo[array_key_last($game_logo)]);
                $game_logo = $this->_logoStrToImg($game_logo[array_key_last($game_logo)]);
                $command_1 = $crawler->filter('.team__name > .d--phone-inline-block')->eq($i)->text();
                $command_2 = $crawler->filter('.team__name > .d--phone-inline-block')->eq($i + 1)->text();//eq => +1
                $score = 'VS';

                array_push($result, ['time' => $time, 'command_1' => $command_1, 'command_2' => $command_2, 'score' => $score, 'logo' => $game_logo]);

            }


        return new JsonResponse($result);
    }

    private function _logoStrToImg($game)
    {

        $name_link = ['dota2' => 'https://i.pinimg.com/originals/2d/cd/80/2dcd80c6f5a21a437313adde93b373d8.png', 'cs-go' => 'https://ih0.redbubble.net/image.455817861.0192/mp,650x642,gloss,f8f8f8,t-pad,750x1000,f8f8f8.jpg', 'lol' => 'https://i.pinimg.com/originals/30/0e/58/300e58c8416a68dcfcf1761501348243.jpg', 'pubg' => 'https://i.pinimg.com/originals/f7/43/c4/f743c45a69f00a4d6254ce42f3803dd1.jpg', 'warcraft-iii' => 'https://bnetproduct-a.akamaihd.net//fb2/ac48e177da0ca6377b8ba8f275c6700b-warcraft3-reforged-enus-1000x500.png', 'overwatch' => 'https://images-na.ssl-images-amazon.com/images/I/41f4npQhADL._SX425_.jpg'];

        return strtr($game, $name_link);

    }

    /**
     * @Route("/coef/api", name="coef_api")
     */
    public function coef(Request $request)
    {
        if (!$request->request->count())
            throw $this->createNotFoundException('Not found 404');

        $data = [];
        $searchGame = $request->request->get('game');
        $xmlData = new SimpleXMLElement(file_get_contents('http://sportsbookfeed.com/sportxml'));

        for ($i=0; $i < $xmlData->Sport->Event->count(); $i++)
        {
            $team = explode('vs', $xmlData->Sport->Event[$i]->Match['Name']);
            try{
            $team_1 = $team[0];
            $team_2 = $team[1];} catch (\Exception $e){
                continue;
            }
            $game = $this->_pregStrToLogo($xmlData->Sport->Event[$i]['Name']);
            $coef_1 = $xmlData->Sport->Event[$i]->Match->Bet->Odd[0]['Value'];
            $coef_2 = $xmlData->Sport->Event[$i]->Match->Bet->Odd[$xmlData->Sport->Event[0]->Match->Bet->Odd->count() - 1]['Value'];
            $date = date_format(date_create($xmlData->Sport->Event[$i]->Match['StartDate']), "d-m-Y H:i");
            array_push($data, ['team_1' => (string)$team_1, 'team_2' => (string)$team_2, 'game' => $game, 'coef_1' => (string)$coef_1, 'coef_2' => (string)$coef_2, 'date' => $date]);

        }

        if ($searchGame != 'All')
            $data = array_filter($data, function ($v) use ($searchGame){

                return $v['game'][1] == $searchGame;

            });


        return new JsonResponse(array_values($data));

    }

    private function _pregStrToLogo($str)
    {

        try{
            $game = explode(',', $str)[0];
        } catch (\Exception $e){

            $game = 'NaN';

        }

        switch ($game) {

            case 'Dota 2':
                return ['assets/images/game_logo/dota.svg', $game];

            case 'CS:GO':
                return ['assets/images/game_logo/cs.jpg', $game];

            case 'League of Legends':
                return ['assets/images/game_logo/lol.jpg', $game];

            case 'Counter-Strike':
                return ['assets/images/game_logo/cs.jpg', $game];

            case 'Overwatch':
                return ['assets/images/game_logo/overwatch.jpg', $game];

            case 'Rainbow Six':
                return ['assets/images/game_logo/rainbow_six.jpg', $game];

            case 'Fortnite':
                return ['assets/images/game_logo/Fortnite.jpg', $game];

            case 'WarCraft III':
                return ['assets/images/game_logo/w3.jpg', $game];

            case 'StarCraft II':
                return ['assets/images/game_logo/starcraft_logo.png', $game];

            case 'Call of Duty':
                return ['assets/images/game_logo/cd.png', $game];

            case 'FIFA':
                return ['assets/images/game_logo/FIFA.png', $game];

            case 'NBA 2k18':
                return ['assets/images/game_logo/nba.png', $game];

            case 'King of Glory':
                return ['assets/images/game_logo/kgglory.png', $game];

            case 'Battlegrounds':
                return ['assets/images/game_logo/pubg.jpg', $game];

            default:
                return ['assets/images/game_logo/no_game.svg', $game];




        }


    }


}