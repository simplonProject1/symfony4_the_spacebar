<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Response;
// use Twig\Environment;

class ArticleController extends AbstractController
{
    /**
     * Currently unused: just showing a controller with a constructor!
     */
    private $isDebug;

    public function __construct(bool $isDebug, Client $slack)
    {
        $this->isDebug = $isDebug;
    }
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }
    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug, SlackClient $slack, EntityManagerInterface $em)
    {
        if ($slug === 'khaaaaaan') {
            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
        }

        $repository = $em->getRepository(Article::class);
        /** @var Article $article */
        $article = $repository->findOneBy(['slug' => $slug]);
        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }

        // dump($article);die;

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];


        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }
    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger)
    {
        // TODO - actually heart/unheart the article!
        $logger->info('Article is being hearted!');
        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}


//
//namespace App\Controller;
//
//use Michelf\MarkdownInterface;
//use Psr\Log\LoggerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Cache\Adapter\AdapterInterface;
//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//// use Twig\Environment;
//
//class ArticleController extends AbstractController
//{
//    /**
//     * @Route("/", name="app_homepage")
//     */
//    public function homepage()
//    {
//        return $this->render('article/homepage.html.twig');
//    }
//
//    /**
//     * @Route("/news/{slug}", name="article_show")
//     */
//    public function show($slug, MarkdownInterface $markdown, AdapterInterface $cache)
//    {
//        $comments = [
//            'I ate a normal rock once. It did NOT taste like bacon!',
//            'Woohoo! I\'m going on an all-asteroid diet!',
//            'I like bacon too! Buy some from my site! bakinsomebacon.com',
//        ];
//
//        //  dump($slug, $this);
//
//        $articleContent = <<<EOF
//Spicy **jalapeno bacon** ipsum dolor amet veniam shank in dolore. Ham hock nisi landjaeger cow, lorem proident [beef ribs](https://baconipsum.com/) aute enim veniam ut cillum pork chuck picanha. Dolore reprehenderit labore minim pork belly spare ribs cupim short loin in. Elit exercitation eiusmod dolore cow turkey shank eu pork belly meatball non cupim.
//
//Laboris **beef** ribs fatback fugiat eiusmod jowl kielbasa alcatra dolore velit ea ball tip. Pariatur laboris sunt venison, et laborum dolore minim non meatball. Shankle eu flank aliqua shoulder, capicola biltong frankfurter boudin cupim officia. Exercitation fugiat consectetur ham. Adipisicing picanha shank et filet mignon pork belly ut ullamco. Irure velit turducken ground round doner incididunt occaecat lorem meatball prosciutto quis strip steak.
//
//Meatball adipisicing ribeye bacon strip steak eu. Consectetur ham hock pork hamburger enim strip steak mollit quis officia meatloaf tri-tip swine. Cow ut reprehenderit, buffalo incididunt in filet mignon strip steak pork belly aliquip capicola officia. Labore deserunt esse chicken lorem shoulder tail consectetur cow est ribeye adipisicing. Pig hamburger pork belly enim. Do porchetta minim capicola irure pancetta chuck fugiat.
//
//EOF;
//        // $articleContent = $markdown->transform($articleContent);
//
//        $item = $cache->getItem('markdown_'.md5($articleContent));
//        if (!$item->isHit()) {
//            $item->set($articleContent = $markdown->transform($articleContent));
//            $cache->save($item);
//        }
//        $articleContent = $item->get();
//
//        // dump($markdown);die;
//
//        return $this->render('article/show.html.twig', [  // tutaj $this to twig object, ale skąd wiadomo?
//            'title' => ucwords(str_replace('-', ' ', $slug)),
//            'articleContent' => $articleContent,
//            'slug' => $slug,
//            'comments' => $comments,
//        ]);
//    }
//
////    public function show($slug, Environment $twigEnvironment)
////    {
////        $comments = [
////            'I ate a normal rock once. It did NOT taste like bacon!',
////            'Woohoo! I\'m going on an all-asteroid diet!',
////            'I like bacon too! Buy some from my site! bakinsomebacon.com',
////        ];
////
//////        dump($slug, $this);
////
////        $html = $twigEnvironment->render('article/show.html.twig', [
////            'title' => ucwords(str_replace('-', ' ', $slug)),
////            'slug' => $slug,
////            'comments' => $comments,
////        ]);
////
////        return new Response($html);
////    }
//
//    /**
//     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
//     */
//    public function toggleArticleHeart ($slug, LoggerInterface $logger)
//    {
//        $logger->info('Article is being hearted');
//
//        // TODO - actually heart/unheart the article
//
//        return new JsonResponse(['hearts' => rand(5, 100)]);
//    }
//}