<?php
namespace Controller;

use Model\Ordinance;

class OrdinanceController {
    private $ordinanceModel;
    private $twig;
    private $defaultFormAction;
    private $baseDir;
    private $fileBaseName;

    public function __construct($pdo, $twig) {
        $this->ordinanceModel = new Ordinance($pdo);
        $this->twig = $twig;
        $this->defaultFormAction = "/admin/ordinances/create";
        $this->baseDir = "C://xampp/htdocs/sb-digital-archive/public";
        $this->fileBaseName = "ordinance_";
    }

    public function showOrdinances($isAdmin) {
        $ordinances = $this->ordinanceModel->getAllOrdinances();
        if ($isAdmin) {
            echo $this->twig->render('admin/ordinances.twig', [
                'ordinances' => $ordinances,
                'formAction' => $this->defaultFormAction
            ]);
        } else {
            echo $this->twig->render('ordinances.twig', [
                'ordinances' => $ordinances
            ]);
        }
    }

    public function showSelectedOrdinance($isAdmin, $id) {
        $ordinances = $this->ordinanceModel->getAllOrdinances();
        $selectedOrdinance = $this->ordinanceModel->getOrdinance($id);
        if ($isAdmin) {
            echo $this->twig->render('admin/ordinances.twig', [
                'ordinances' => $ordinances,
                'selectedOrdinance' => $selectedOrdinance,
                'formAction' => "/admin/ordinances/update/$id"
            ]);
        } else {
            echo $this->twig->render('ordinances.twig', [
                'ordinances' => $ordinances
            ]);
        }
    }

    public function createOrdinance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
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
                $ordinanceFile = $targetPath;
            }

            $this->ordinanceModel->createOrdinance($ordinanceFile, $title, $authors, $description, $createdBy);
            header('Location: /admin/ordinances');
            exit;
        }

        $ordinances = $this->ordinanceModel->getAllOrdinances();
        echo $this->twig->render('admin/ordinances.twig', [
            'ordinances' => $ordinances,
            'formAction' => $this->defaultFormAction
        ]);
    }

    public function updateOrdinance($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
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
                $ordinanceFile = $targetPath;
            }

            $this->ordinanceModel->updateOrdinance($id, $ordinanceFile, $title, $authors, $description, $updatedBy);
            header('Location: /admin/ordinances');
            exit;
        }
        
        $ordinances = $this->ordinanceModel->getAllOrdinances();
        echo $this->twig->render('admin/ordinances.twig', [
            'ordinances' => $ordinances,
            'formAction' => $this->defaultFormAction
        ]);
    }

    public function downloadOrdinance($id) {
        $ordinance = $this->ordinanceModel->getOrdinance($id);

        if(file_exists($ordinance['ordinanceFile'])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($ordinance['ordinanceFile']));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($ordinance['ordinanceFile']['name']));
            readfile($ordinance['ordinanceFile']['name']);

            exit;
        }
    }

    public function deleteOrdinance($id) {
        $this->ordinanceModel->deleteOrdinance($id);
        header('Location: /admin/ordinances');
        exit;
    }
}
