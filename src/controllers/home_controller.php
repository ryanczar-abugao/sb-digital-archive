<?php

namespace Controller;

use Model\News;
use Constants\CssConstants;

class HomeController
{
    private $newsModel;
    private $twig;
    private $cssConstants;

    public function __construct($pdo, $twig)
    {
        $this->newsModel = new News($pdo);
        $this->cssConstants = new CssConstants();
        $this->twig = $twig;
    }

    public function showHomePage()
    {
        // Fetch latest news articles from the model
        $newsArticles = $this->newsModel->getAllNews();

        // Render the homepage with news articles
        echo $this->twig->render('home.twig', [
            'newsArticles' => $newsArticles,
            'css' => $this->cssConstants,
            'currentPage' => 'home'
        ]);
    }
}
