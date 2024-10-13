<?php
require '../vendor/autoload.php';
require '../config/database.php';
require '../src/models/ordinance.php';
require '../src/models/member.php';
require '../src/models/chapter.php';
require '../src/controllers/ordinance_controller.php';
require '../src/controllers/member_controller.php';
require '../src/controllers/history_controller.php';
require '../src/controllers/about_controller.php';
require '../src/controllers/auth_controller.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Controller\OrdinanceController;
use Controller\MemberController;
use Controller\HistoryController;
use Controller\AboutController;
use App\Controller\AuthController;

// Set up Twig
$loader = new FilesystemLoader('../src/views');
$twig = new Environment($loader);

// Create instances of controllers
$ordinanceController = new OrdinanceController($pdo, $twig);
$memberController = new MemberController($pdo, $twig);
$historyController = new HistoryController($pdo, $twig);
$aboutController = new AboutController($pdo, $twig);
$authController = new AuthController($pdo);

// Example routing
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        echo $twig->render('home.twig');
        break;
    case '/ordinances':
        $ordinanceController->showOrdinances(0);
        break;
    case '/about':
        $aboutController->showAbout();
        break;
    case '/admin/login':
        $data = $authController->login();
        echo $twig->render('admin/login.twig', $data);
        break;
    case '/admin/logout':
        $authController->logout();
        break;
    case '/admin/dashboard':
        session_start();
        if (!isset($_SESSION['userId'])) {
            header('Location: /admin/login');
            exit;
        }
        echo $twig->render('admin/dashboard.twig');
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
    case preg_match('/\/admin\/history\/update\/(\d+)/', $uri, $matches) ? true : false:
        $historyController->updateChapter($matches[1]);
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
        $ordinanceController->downloadOrdinance($matches[1]);
        break;     
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
?>
