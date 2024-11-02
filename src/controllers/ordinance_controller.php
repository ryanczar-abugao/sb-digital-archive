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
        $ordinances = $this->ordinanceModel->getAllOrdinances();
        if ($isAdmin) {
            session_start();

            $this->sessionHelper->verifyLoggedUser();

            echo $this->twig->render('admin/ordinances.twig', [
                'ordinances' => $ordinances,
                'formAction' => $this->defaultFormAction,
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
            $description = $_POST['description'];
            $createdBy = $_SESSION['userId'];

            if (!empty($_FILES['ordinanceFile']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['ordinanceFile']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['ordinanceFile']['tmp_name'], $fullPath);
                $ordinanceFile = $fullPath;
            }

            $this->ordinanceModel->createOrdinance($ordinanceFile, $title, $authors, $description, $createdBy);
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $authors = $_POST['authors'];
            $description = $_POST['description'];
            $updatedBy = $_SESSION['userId'];

            if (!empty($_FILES['ordinanceFile']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['ordinanceFile']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['ordinanceFile']['tmp_name'], $fullPath);
                $ordinanceFile = $fullPath;
            }

            $this->ordinanceModel->updateOrdinance($id, $ordinanceFile, $title, $authors, $description, $updatedBy);
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

        if (file_exists($ordinance['ordinanceFile'])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header("Content-Disposition: {$contentDisposition}; filename=" . basename($ordinance['ordinanceFile']));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($ordinance['ordinanceFile']));

            ob_clean();
            flush();

            readfile($ordinance['ordinanceFile']);

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
