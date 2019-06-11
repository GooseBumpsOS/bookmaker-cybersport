<?php

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{

    /**
     * @Route("/news-{page<\d+>}", name="news")
     */
    public function index($page)
    {
        $em = $this->getDoctrine()->getManager()->getRepository(News::class);

        return $this->render('news/index.html.twig', [
            'page' => $page,
            'last_page' => (int)$em->getTableCount(),
            'content' => $em->findBy([], ['id' => 'DESC'])
        ]);
    }

    /**
     * @Route("/news/{slug}", name="slug")
     */
    public function single($slug)
    {
        return $this->render('news/single-blog.html.twig', [
            'slug' => $slug,
        ]);
    }
}
