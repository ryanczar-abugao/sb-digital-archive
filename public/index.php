<?php
require '../config/imports.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Controller\HomeController;
use Controller\OrdinanceController;
use Controller\MemberController;
use Controller\HistoryController;
use Controller\AboutController;
use Controller\AuthController;
use Controller\DashboardController;
use Controller\NewsController;

use Constants\CssConstants;

$loader = new FilesystemLoader('../src/views');
$twig = new Environment($loader);

$homeController = new HomeController($pdo, $twig);
$ordinanceController = new OrdinanceController($pdo, $twig);
$memberController = new MemberController($pdo, $twig);
$newsController = new NewsController($pdo, $twig);
$historyController = new HistoryController($pdo, $twig);
$aboutController = new AboutController($pdo, $twig);
$authController = new AuthController($pdo);
$dashboardController = new DashboardController($twig);

$routes = [
    '/' => function () use ($homeController) {
        $homeController->showHomePage();
    },
    '/news' => function () use ($newsController) {
        $newsController->showNews();
    },
    '/ordinances' => function () use ($ordinanceController) {
        $ordinanceController->showOrdinances(0);
    },
    '/ordinances/download' => function ($id) use ($ordinanceController) {
        $ordinanceController->readOrdinanceFile($id, "attachment");
    },
    '/ordinances/year' => function ($year) use ($ordinanceController) {
        $ordinanceController->showOrdinances(0, $year);
    },
    '/ordinances/preview' => function ($id) use ($ordinanceController) {
        $ordinanceController->readOrdinanceFile($id, "inline");
    },
    '/member' => function () use ($aboutController) {
        $aboutController->showMembers();
    },
    '/about' => function () use ($aboutController) {
        $aboutController->showAbout();
    },
    '/admin/login' => function () use ($authController, $twig) {
        $data = $authController->login();
        echo $twig->render('admin/login.twig', $data);
    },
    '/admin/logout' => function () use ($authController) {
        $authController->logout();
    },
    '/admin/dashboard' => function () use ($dashboardController) {
        $dashboardController->showDashboard();
    },
    '/admin/news' => function () use ($newsController) {
        $newsController->showNews();
    },
    '/admin/news/create' => function () use ($newsController) {
        $newsController->createNews();
    },
    '/admin/news/edit' => function ($id) use ($newsController) {
        $newsController->showSelectedNews($id);
    },
    '/admin/news/update' => function ($id) use ($newsController) {
        $newsController->updateNews($id);
    },
    '/admin/news/delete' => function ($id) use ($newsController) {
        $newsController->deleteNews($id);
    },
    '/admin/members' => function () use ($memberController) {
        $memberController->showMembers();
    },
    '/admin/ordinances' => function () use ($ordinanceController) {
        $ordinanceController->showOrdinances(1);
    },
    '/admin/ordinances/create' => function () use ($ordinanceController) {
        $ordinanceController->createOrdinance();
    },
    '/admin/ordinances/edit' => function ($id) use ($ordinanceController) {
        $ordinanceController->showSelectedOrdinance(1, $id);
    },
    '/admin/ordinances/update' => function ($id) use ($ordinanceController) {
        $ordinanceController->updateOrdinance($id);
    },
    '/admin/ordinances/delete' => function ($id) use ($ordinanceController) {
        $ordinanceController->deleteOrdinance($id);
    },
    '/admin/history' => function () use ($historyController) {
        $historyController->showChapters();
    },
    '/admin/history/create' => function () use ($historyController) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $historyController->createChapter();
        } else {
            header('Location: /admin/history');
        }
    },
    '/admin/history/edit' => function ($id) use ($historyController) {
        $historyController->showSelectedChapter($id);
    },
    '/admin/history/update' => function ($id) use ($historyController) {
        $historyController->updateChapter($id);
        $historyController->showSelectedChapter($id);
    },
    '/admin/history/delete' => function ($id) use ($historyController) {
        $historyController->deleteChapter($id);
    },
    '/admin/members/create' => function () use ($memberController) {
        $memberController->createMember();
    },
    '/admin/members/edit' => function ($id) use ($memberController) {
        $memberController->showSelectedMember($id);
    },
    '/admin/members/update' => function ($id) use ($memberController) {
        $memberController->updateMember($id);
    },
    '/admin/members/delete' => function ($id) use ($memberController) {
        $memberController->deleteMember($id);
    },
    '/admin/encrypt' => function ($password) use ($twig) {
        $cssConstants = new CssConstants();
        echo $twig->render('encrypt_pass.twig', [
            'css' => $cssConstants,
            'hashedPassword' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
];

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$matched = false;

foreach ($routes as $route => $action) {
    if ($uri === $route) {
        $action();
        $matched = true;
        break;
    }

    $routeWIthId = strpos($route, '/edit') !== false ||
        strpos($route, '/update') !== false ||
        strpos($route, '/delete') !== false ||
        strpos($route, '/download') !== false ||
        strpos($route, '/preview') !== false;

    if ($routeWIthId) {
        if (preg_match('#^' . preg_quote($route, '#') . '/(\d+)$#', $uri, $matches)) {
            $action($matches[1]);
            $matched = true;
            break;
        }
    }
}

if (!$matched) {
    http_response_code(404);
    echo "404 Not Found";
}
