<?php

namespace App\Controller;

define('password_cms', '$2y$10$2QlKFibkLUq5N71PyAhhrug6JyycE/0XpEx4pGw0DfEjybEe7.sWO');

use App\Entity\Carousel;
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

        if (password_verify($session->get('password'), password_cms))
            return $this->redirectToRoute('cms');

        if ($request->request->count() != 0)
            if ($request->request->get('login') == 'toor' && password_verify($request->request->get('password'), password_cms)) {
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

        if (!password_verify($session->get('password'), password_cms))
            return $this->redirectToRoute('cms-login');

        if ($request->request->count() != 0)
        {
            $dbNews = new News();
            $dbSeo = new Seo();

            try{

                $dbNews->setDate(new \DateTime(date("d-M-Y")));
                $dbNews->setImg($this->_uploadFile());
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

            } catch (\Exception $e){

                echo $e->getMessage();
                die();

            }




        }

        $em = $this->getDoctrine()->getManager()->getRepository(News::class);

        return $this->render('cms/cms.html.twig', [
            'rows' => $em->getByPage(1),
        ]);
    }

    /**
     * @Route("/cms-slider", name="cms-slider")
     */
    public function slider_cms(Request $request)
    {
        $session = new Session();

        if (!password_verify($session->get('password'), password_cms))
            return $this->redirectToRoute('cms-login');

        $emSlider = $this->getDoctrine()->getManager()->getRepository(Carousel::class);

        if ($request->request->get('truncate'))
            $emSlider->clearTable();

        if ($request->request->get('img'))
        {
            $dbCarousel = new Carousel();

            $dbCarousel->setImg($request->request->get('img'));
            $dbCarousel->setText($request->request->get('text'));
            $dbCarousel->setHeaderText($request->request->get('header'));

            $this->getDoctrine()->getManager()->persist($dbCarousel);
            $this->getDoctrine()->getManager()->flush();
        }

        $slider_data = $emSlider->findAll();


        return $this->render('cms/slider_cms.html.twig',[


            'slider_data' => $slider_data,

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

    private function _uploadFile(){


        $allowed_filetypes = array('.jpg','.jpeg' ,'.png', '.svg'); // Допустимые типы файлов
        $max_filesize = 15728640; // Максимальный размер файла в байтах .
        $upload_path_full = $this->getParameter('img_dir'); // Папка, куда будут загружаться файлы .
        $upload_path = 'assets/images/news-img/';
        $filename = $_FILES['userfile']['name']; // В переменную $filename заносим имя файла (включая расширение).
        $ext = substr($filename, strpos($filename, '.'), strlen($filename) - 1); // В переменную $ext заносим расширение загруженного файла.
        $randomFileName = md5(time());
        if (!in_array($ext, $allowed_filetypes)) // Сверяем полученное расширение со списком допутимых расширений.
            die('Данный тип файла не поддерживается. Напишите в директ мне это - ' . $ext);
        if (filesize($_FILES['userfile']['tmp_name']) > $max_filesize) // Проверим размер загруженного файла.
            die('Фаил слишком большой.');
        if (!is_writable($upload_path_full)) // Проверяем, доступна ли на запись папка.
            die('Невозможно загрузить фаил в папку. Установите права доступа - 777.');
// Загружаем фаил в указанную папку.
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $upload_path_full . $randomFileName . $ext)) {

            return $upload_path . $randomFileName . $ext;

        }
            else die('File ERROR');



    }
}
