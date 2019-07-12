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
            $crawler = new Crawler($this->_getRawHtml('https://www.marathonbet.ru/su/betting/e-Sports/' . $game_name[$c]));

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

    private function _getRawHtml($url){

        $ch = curl_init(); // Инициализация сеанса
        curl_setopt($ch, CURLOPT_URL, $url); // Куда данные послать
        curl_setopt($ch, CURLOPT_HEADER, 0); // получать заголовки
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Говорим скрипту, чтобы он следовал за редиректами которые происходят во время авторизации
        curl_setopt ($ch, CURLOPT_HTTPHEADER, [

            'GET /su/betting/e-Sports/lol HTTP/1.1',
            'Host: www.marathonbet.ru',
            'Cookie: panbet.openeventnameseparately=true; panbet.openadditionalmarketsseparately=false; puid=rBkp8V0F1gmvXiigYDKQAg==; _ga=GA1.2.927663478.1560663563; _ym_uid=1560663563815002094; _ym_d=1560663563; _dvp=0:jwyisg05:yDYNabhZbS_aurqXB4gmDULXPlzImWej; _gid=GA1.2.1669121284.1562545860; _dc_gtm_UA-55273062-1=1; _gat_UA-55273062-15=1; top100_id=t1.-1.728492642.1562545860060; JSESSIONID=web3~2502B28A1E2D282907FB5031C7FA1BAC; SESSION_KEY=8d58ebafbaac46f4a628aaf0476acf3d; _ym_visorc_21139201=w; _ym_visorc_46160313=w; _ym_isad=1; _dvs=0:jxtngm3t:hI8cyK5bxW9BgL5OSQ0lgGnZtcfqTB2f; fingerprint2=6a69fde2f1750ef78785f80def843157; last_visit=1562535065651::1562545865651; SyncTimeData={"offset":-18,"timestamp":1562545866134}',
            'Upgrade-Insecure-Requests: 1'


        ]); // это необходимо, чтобы cURL не высылал заголовок на ожидание
        $tempRes = curl_exec($ch);
        curl_close($ch); // Завершаем сеанс

        return $tempRes;


    }
}
