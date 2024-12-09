<?php

namespace Controller;

use Model\Ordinance;
use Model\Download;
use Constants\CssConstants;
use Helpers\SessionHelpers;

class OrdinanceController
{
    private $ordinanceModel;
    private $downloadModel;
    private $twig;
    private $defaultFormAction;
    private $baseDir;
    private $fileBaseName;
    private $cssConstants;
    private $sessionHelper;

    public function __construct($pdo, $twig)
    {
        $this->ordinanceModel = new Ordinance($pdo);
        $this->downloadModel = new Download($pdo);
        $this->cssConstants = new CssConstants();
        $this->sessionHelper = new SessionHelpers();
        $this->twig = $twig;
        $this->defaultFormAction = "/admin/ordinances/create";
        $this->baseDir = "C://xampp/htdocs/sb-digital-archive/public";
        $this->fileBaseName = "ordinance_";
    }

    public function showOrdinances($isAdmin)
    {
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;
        $yearQuery = isset($_GET['year']) ? trim($_GET['year']) : null;

        if ($searchQuery)
        {
            $ordinances = $this->ordinanceModel->searchOrdinances($searchQuery);
        } 
        else 
        {
            $ordinances = $this->ordinanceModel->getAllOrdinances($yearQuery);
        }

        $ordinanceYears = $this->ordinanceModel->getOrdinanceYears();

        $template = $isAdmin ? 'admin/ordinances.twig' : 'ordinances.twig';
        $context = [
            'ordinances' => $ordinances,
            'ordinanceYears' => $ordinanceYears,
            'selectedYear' => $yearQuery,
            'css' => $this->cssConstants,
            'currentPage' => 'ordinances',
            'searchQuery' => $searchQuery
        ];

        if ($isAdmin) {
            session_start();
            $this->sessionHelper->verifyLoggedUser();
            $context['formAction'] = $this->defaultFormAction;
            $context['isLoggedIn'] = isset($_SESSION['userId']);
        }

        echo $this->twig->render($template, $context);
    }

    public function showSelectedOrdinance($isAdmin, $id)
    {
        $ordinances = $this->ordinanceModel->getAllOrdinances();
        $selectedOrdinance = $this->ordinanceModel->getOrdinance($id);
        if ($isAdmin) {
            session_start();

            $this->sessionHelper->verifyLoggedUser();

            echo $this->twig->render('admin/ordinances.twig', [
                'ordinances' => $ordinances,
                'selectedOrdinance' => $selectedOrdinance,
                'formAction' => "/admin/ordinances/update/$id",
                'css' => $this->cssConstants,
                'isLoggedIn' => isset($_SESSION['userId']),
                'currentPage' => 'ordinances'
            ]);
        } else {
            echo $this->twig->render('ordinances.twig', [
                'ordinances' => $ordinances,
                'css' => $this->cssConstants,
                'currentPage' => 'ordinances'
            ]);
        }
    }

    public function createOrdinance()
    {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $authors = $_POST['authors'];
            $year = $_POST['year'];
            $description = $_POST['description'];
            $createdBy = $_SESSION['userId'];

            if (!empty($_FILES['fileInput']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['fileInput']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['fileInput']['tmp_name'], $fullPath);
                $fileInput = $fullPath;
            }

            $this->ordinanceModel->createOrdinance($fileInput, $title, $authors, $year, $description, $createdBy);
            header('Location: /admin/ordinances');
            exit;
        }

        $ordinances = $this->ordinanceModel->getAllOrdinances();
        echo $this->twig->render('admin/ordinances.twig', [
            'ordinances' => $ordinances,
            'formAction' => $this->defaultFormAction,
            'css' => $this->cssConstants,
            'isLoggedIn' => isset($_SESSION['userId']),
            'currentPage' => 'ordinances'
        ]);
    }

    public function updateOrdinance($id)
    {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        $selectedOrdinance = $this->ordinanceModel->getOrdinance($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $authors = $_POST['authors'];
            $year = $_POST['year'];
            $description = $_POST['description'];
            $updatedBy = $_SESSION['userId'];

            $fileInput = $selectedOrdinance['fileInput'];
            if (!empty($_FILES['fileInput']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['fileInput']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['fileInput']['tmp_name'], $fullPath);
                $fileInput = $fullPath;
            }

            $this->ordinanceModel->updateOrdinance($id, $fileInput, $title, $authors, $year, $description, $updatedBy);
            header('Location: /admin/ordinances');
            exit;
        }

        $ordinances = $this->ordinanceModel->getAllOrdinances();
        echo $this->twig->render('admin/ordinances.twig', [
            'ordinances' => $ordinances,
            'formAction' => $this->defaultFormAction,
            'css' => $this->cssConstants,
            'isLoggedIn' => isset($_SESSION['userId']),
            'currentPage' => 'ordinances'
        ]);
    }

    public function readOrdinanceFile($id, $contentDisposition)
    {
        $ordinanceId = (int)$id;
    
        $ordinance = $this->ordinanceModel->getOrdinance($ordinanceId);
        if (!$ordinance) {
            http_response_code(404);
            echo json_encode(['error' => 'Ordinance not found.']);
            return;
        }

        if ($contentDisposition == "attachment") {
            $data = json_decode(file_get_contents('php://input'), true);
    
            if (!isset($data['email'], $id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Email and ordinance ID are required.']);
                return;
            }
        
            $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid email address.']);
                return;
            }
            $this->downloadModel->logDownload($email, $ordinanceId);
        }        
    
        $filePath = $ordinance['fileInput'];
        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: ' . $contentDisposition . '; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'File not found.']);
        }
    }

    public function deleteOrdinance($id)
    {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        $this->ordinanceModel->deleteOrdinance($id);
        header('Location: /admin/ordinances');
        exit;
    }
}
