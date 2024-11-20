<?php
require '../config/imports.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Controller\OrdinanceController;
use Controller\MemberController;
use Controller\HistoryController;
use Controller\AboutController;
use Controller\AuthController;
use Controller\AttachmentController;
use Controller\DashboardController;

use Constants\CssConstants;

$loader = new FilesystemLoader('../src/views');
$twig = new Environment($loader);

$ordinanceController = new OrdinanceController($pdo, $twig);
$memberController = new MemberController($pdo, $twig);
$historyController = new HistoryController($pdo, $twig);
$aboutController = new AboutController($pdo, $twig);
$authController = new AuthController($pdo);
$attachmentController = new AttachmentController($pdo);
$dashboardController = new DashboardController($twig);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        $cssConstants = new CssConstants();
        echo $twig->render('home.twig', [ 
            'css' => $cssConstants, 
            'currentPage' => 'home'
        ]);
        break;

    case '/ordinances':
        $ordinanceController->showOrdinances(0);
        break;

    case '/about':
        $aboutController->showAbout();
        break;
        
    case '/member':
        $aboutController->showMembers();
        break;

    case '/admin/login':
        $data = $authController->login();
        echo $twig->render('admin/login.twig', $data);
        break;

    case '/admin/logout':
        $authController->logout();
        break;

    case '/admin/dashboard':
        $dashboardController->showDashboard();
        break;

    case '/admin/members':
        $memberController->showMembers();
        break;

    case '/admin/members/create':
        $memberController->createMember();
        break;

    case preg_match('/\/admin\/members\/edit\/(\d+)/', $uri, $matches) ? true : false:
        $memberController->showSelectedMember($matches[1]);
        break;

    case preg_match('/\/admin\/members\/update\/(\d+)/', $uri, $matches) ? true : false:
        $memberController->updateMember($matches[1]);
        break;

    case preg_match('/\/admin\/members\/delete\/(\d+)/', $uri, $matches) ? true : false:
        $memberController->deleteMember($matches[1]);
        break;

    case '/admin/history':
        $historyController->showChapters();
        break;

    case '/admin/history/create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapterId = $historyController->createChapter();

            if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
                $attachmentController->handleFileUploads($chapterId, $_FILES['attachments']);
            }
        } else {
            header('Location: /admin/history');
        }
        break;

    case preg_match('/\/admin\/history\/edit\/(\d+)/', $uri, $matches) ? true : false:
        $historyController->showSelectedChapter($matches[1]);
        break;

    case preg_match('/\/admin\/history\/update\/(\d+)/', $uri, $matches) ? true : false:
        $chapterId = $matches[1];
        $historyController->updateChapter($chapterId);

        if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
            $attachmentController->handleFileUploads($chapterId, $_FILES['attachments']);
        }

        $historyController->showSelectedChapter($chapterId);
        break;

    case preg_match('/\/admin\/history\/delete\/(\d+)/', $uri, $matches) ? true : false:
        $historyController->deleteChapter($matches[1]);
        break;

    case '/admin/ordinances':
        $ordinanceController->showOrdinances(1);
        break;

    case '/admin/ordinances/create':
        $ordinanceController->createOrdinance();
        break;

    case preg_match('/\/admin\/ordinances\/edit\/(\d+)/', $uri, $matches) ? true : false:
        $ordinanceController->showSelectedOrdinance(1, $matches[1]);
        break;

    case preg_match('/\/admin\/ordinances\/update\/(\d+)/', $uri, $matches) ? true : false:
        $ordinanceController->updateOrdinance($matches[1]);
        break;

    case preg_match('/\/admin\/ordinances\/delete\/(\d+)/', $uri, $matches) ? true : false:
        $ordinanceController->deleteOrdinance($matches[1]);
        break;

    case preg_match('/\/ordinances\/download\/(\d+)/', $uri, $matches) ? true : false:
        $ordinanceController->readOrdinanceFile($matches[1], "attachment");
        break;

    case preg_match('/\/ordinances\/preview\/(\d+)/', $uri, $matches) ? true : false:
        $ordinanceController->readOrdinanceFile($matches[1], "inline");
        break;

    case 'attachment/create':
        if (preg_match('/\/attachment\/update\/(\d+)/', $uri, $matches)) {
            $attachmentController->handleFileUploads($matches[1], $_FILES['attachments']);
        }
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
