<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\NewsComment;
use App\Entity\Seo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{

    /**
     * @Route("/news-{page<\d+>}", name="news")
     */
    public function index($page)
    {
        $em = $this->getDoctrine()->getManager()->getRepository(News::class);

        $pageData = $em->getByPage($page);


        return $this->render('news/index.html.twig', [
            'page' => $page,
            'last_page' => ceil($em->getTableCount() / 9),
            'content' => $pageData
        ]);
    }

    /**
     * @Route("/news/{slug}", name="slug")
     */
    public function single($slug, Request $request)
    {
        if ($request->request->count() != 0) { //блок отвечает за добавления комментария в базу

            if ($request->request->get('g-recaptcha-response'))
            {
                $url = 'https://www.google.com/recaptcha/api/siteverify?';

                $query = http_build_query([

                    'secret' => '6LfBGLEUAAAAALn6qFzJy8saIK7wsAId7uldcrW3',
                    'response' => $request->request->get('g-recaptcha-response'),
                    'remoteip' => $request->getClientIp()
                ]);

               $recaptchaData =  json_decode(file_get_contents($url . $query), true);

               if($recaptchaData['success'])
               {
                   $dbComment = new NewsComment();

                   $dbComment->setComment($request->request->get('msg'));
                   $dbComment->setNewsName($slug);
                   $dbComment->setTime(time());
                   $dbComment->setUserName($request->request->get('name'));

                   $this->getDoctrine()->getManager()->persist($dbComment);
                   $this->getDoctrine()->getManager()->flush();

               }

            }



        }

        $emNews = $this->getDoctrine()->getManager()->getRepository(News::class);
        $emNewsComment = $this->getDoctrine()->getManager()->getRepository(NewsComment::class);
        $emSeo = $this->getDoctrine()->getManager()->getRepository(Seo::class);

        $content = $emNews->findOneBy(['slug' => $slug]);

        if ($content == null) //проверка на существование записи в бд
            throw $this->createNotFoundException('Not found 404');

        $comment = $emNewsComment->findBy(['news_name' => $slug],['id' => 'DESC']);
        $seo = $emSeo->findOneBy(['news_name' => $slug]);

        return $this->render('news/single-blog.html.twig', [
            'content' => $content,
            'random_content' => $emNews->getRandom(4),
            'comment' => $comment,
            'seo' => $seo
        ]);
    }
}
