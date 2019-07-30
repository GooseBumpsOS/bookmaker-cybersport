<?php


namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;

class Forecast
{
    const dbTable = 'forecast';
    const dbName = 'cyber-sport';
    const dbPass = 'g)^}YJy%`TFw9ner:3q>y@8RK;47=^3x';
    const dbUser = 'symfony';

    private $_pdo;
    private $_parseData;

    function __construct(){

        $host = '127.0.0.1';
        $db   = Forecast::dbName;
        $user = Forecast::dbUser;
        $pass = Forecast::dbPass;
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $this->_pdo = new \PDO($dsn, $user, $pass);

        $this->_parse();

    }

    private function _parse(){

        $parseData = [];
        $url = 'https://stavkiprognozy.ru/prognozy/kibersport/';

        $crawler = new Crawler($this->_getRawHtml($url));

        for ($i = 0; $i < $crawler->filter('.announce-item-body')->count() * 2; $i += 2) {
            $date = $crawler->filter('.announce-item-time')->eq(intdiv($i, 2))->text();

            $team_1 = $crawler->filter('.single-announce-team-title')->eq($i)->text();

            $team_2 = $crawler->filter('.single-announce-team-title')->eq($i + 1)->text();

            $link = $crawler->filter('.not-link')->eq(intdiv($i, 2))->attr('href');

            $insideCrawelr = new Crawler($this->_getRawHtml($url . $link));

            $score = $insideCrawelr->filter('.list-info-prop')->text();


            $game = $this->getGameLogo($crawler->filter('.announce-item-category-link')->eq(intdiv($i, 2))->attr('title'));

            array_push($parseData, ['date' => $date, 'team_1' => $team_1, 'team_2' => $team_2, 'score' => $score, 'game' => $game]);

        }

        $this->_parseData = $parseData;

    }

    private function getGameLogo($game){

        if(preg_match('#CS:GO#', $game)) return 'assets/images/game_logo/cs.jpg';
        if(preg_match('#Dota 2#', $game)) return 'assets/images/game_logo/dota.svg';

        return $game;

    }

    public function compare(){

        $dataDb = $this->_pdo->query('SELECT date, team_1 ,team_2, score, game FROM ' . Forecast::dbTable)->fetchAll(\PDO::FETCH_ASSOC);
        $parseData = $this->_parseData;

        if(array_diff($dataDb[0], $parseData[0]) != null)
        {

            $this->_insertIntoTable();
            $this->_postNews($parseData['0']);

            return true;
        }
        else return false;

    }

    private function _insertIntoTable(){
        $localData = $this->_parseData;

        $this->_pdo->query("TRUNCATE `".Forecast::dbName."`.`". Forecast::dbTable."`")->execute();

        for ($i = 0; $i < count($localData); $i++)
            $this->_pdo->prepare("INSERT INTO ". Forecast::dbTable ." (id, team_1, team_2, date, score, game) VALUES (NULL, ?, ?, ?, ?, ?)")->execute([$localData[$i]['team_1'], $localData[$i]['team_2'], $localData[$i]['date'], $localData[$i]['score'], $localData[$i]['game']]);


    }

    private function _getRawHtml($url){

        $ch = curl_init(); // Инициализация сеанса
        curl_setopt($ch, CURLOPT_URL, $url); // Куда данные послать
        curl_setopt($ch, CURLOPT_HEADER, 0); // получать заголовки
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Говорим скрипту, чтобы он следовал за редиректами которые происходят во время авторизации
        $tempRes = curl_exec($ch);
        curl_close($ch); // Завершаем сеанс

        return $tempRes;
    }

    private function _postNews($newsData){


    }

}

