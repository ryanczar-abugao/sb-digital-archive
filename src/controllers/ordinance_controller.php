<?php

namespace Controller;

use Model\Ordinance;
use Constants\CssConstants;
use Helpers\SessionHelpers;

class OrdinanceController
{
    private $ordinanceModel;
    private $twig;
    private $defaultFormAction;
    private $baseDir;
    private $fileBaseName;
    private $cssConstants;
    private $sessionHelper;

    public function __construct($pdo, $twig)
    {
        $this->ordinanceModel = new Ordinance($pdo);
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

        if ($searchQuery) 
        {
            $ordinances = $this->ordinanceModel->searchOrdinances($searchQuery);
        } 
        else 
        {
            $ordinances = $this->ordinanceModel->getAllOrdinances();
        }

        $template = $isAdmin ? 'admin/ordinances.twig' : 'ordinances.twig';
        $context = [
            'ordinances' => $ordinances,
            'css' => $this->cssConstants,
            'currentPage' => 'ordinances',
            'searchQuery' => $searchQuery, // Pass search query to pre-fill the search box
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
        $ordinance = $this->ordinanceModel->getOrdinance($id);

        if (file_exists($ordinance['fileInput'])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header("Content-Disposition: {$contentDisposition}; filename=" . basename($ordinance['fileInput']));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($ordinance['fileInput']));

            ob_clean();
            flush();

            readfile($ordinance['fileInput']);

            exit;
        } else {
            // Handle the error if the file doesn't exist
            header('HTTP/1.0 404 Not Found');
            echo "File not found.";
            exit;
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
