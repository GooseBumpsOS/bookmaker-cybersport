<?php

namespace App\Controller;

define('password', '$2y$10$2QlKFibkLUq5N71PyAhhrug6JyycE/0XpEx4pGw0DfEjybEe7.sWO');

use App\Entity\News;
use App\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CmsController extends AbstractController
{
    /**
     * @Route("/cms-login", name="cms-login")
     */
    public function index(Request $request)
    {
        $wrongPassOrLogin = false;

        $session = new Session();

        if (password_verify($session->get('password'), password))
            return $this->redirectToRoute('cms');

        if ($request->request->count() != 0)
            if ($request->request->get('login') == 'toor' && password_verify($request->request->get('password'), password)) {
                $session->start();
                $session->set('password', $request->request->get('password'));
                return $this->redirectToRoute('cms');

            } else $wrongPassOrLogin = true;

        return $this->render('cms/login.html.twig', [
            'wrongPassOrLogin' => $wrongPassOrLogin,
        ]);
    }

    /**
     * @Route("/cms", name="cms")
     */
    public function cms(Request $request)
    {
        $session = new Session();

        if (!password_verify($session->get('password'), password))
            return $this->redirectToRoute('cms-login');

        if ($request->request->count() != 0)
        {
            $dbNews = new News();
            $dbSeo = new Seo();

            $dbNews->setDate(new \DateTime(date("d-M-Y")));
            $dbNews->setImg($request->request->get('img'));
            $dbNews->setText($request->request->get('text'));
            $dbNews->setTitle($request->request->get('title'));
            $dbNews->setSlug($this->_translate($request->request->get('title')));
            $dbSeo->setNewsName($dbNews->getSlug());
            $dbSeo->setAlt($request->request->get('alt'));
            $dbSeo->setDescription($request->request->get('description'));
            $dbSeo->setHtmlTitle($request->request->get('html_title'));

            $this->getDoctrine()->getManager()->persist($dbNews);
            $this->getDoctrine()->getManager()->persist($dbSeo);

            $this->getDoctrine()->getManager()->flush();


        }

        $em = $this->getDoctrine()->getManager()->getRepository(News::class);

        return $this->render('cms/cms.html.twig', [
            'rows' => $em->getByPage(1),
        ]);
    }

    private function _translate($str){

        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"'","э"=>"e","ю"=>"yu","я"=>"ya",
            "."=>"_"," "=>"_","?"=>"_","/"=>"_","\\"=>"_",
            "*"=>"_",":"=>"_","\*"=>"_","\""=>"_","<"=>"_",
            ">"=>"_","|"=>"_"
        );
        return strtr($str,$tr);

    }
}
