<?php
namespace Controller;

use Model\Ordinance;

class OrdinanceController {
    private $ordinanceModel;
    private $twig;

    public function __construct($pdo, $twig) {
        $this->ordinanceModel = new Ordinance($pdo);
        $this->twig = $twig;
    }

    public function showOrdinances($isAdmin) {
        $ordinances = $this->ordinanceModel->getAllOrdinances();
        if ($isAdmin) {
            echo $this->twig->render('admin/ordinances.twig', ['ordinances' => $ordinances]);
        } else {
            echo $this->twig->render('ordinances.twig', ['ordinances' => $ordinances]);
        }
    }

    public function createOrdinance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filename = $_FILES['file']['name']; // Assuming you are uploading a file
            $title = $_POST['title'];
            $authors = $_POST['authors'];
            $createdBy = $_SESSION['userId'];

            // Handle file upload (you need to implement this part)
            // move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $filename);

            $this->ordinanceModel->createOrdinance($filename, $title, $authors, $createdBy);
            header('Location: /admin/ordinances');
            exit;
        }
        echo $this->twig->render('admin/create_ordinance.twig');
    }

    public function updateOrdinance($id) {
        $ordinance = $this->ordinanceModel->getOrdinance($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filename = $_FILES['file']['name']; // Handle file upload if needed
            $title = $_POST['title'];
            $authors = $_POST['authors'];

            // Handle file upload if needed
            // move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $filename);

            $this->ordinanceModel->updateOrdinance($id, $filename, $title, $authors);
            header('Location: /admin/ordinances');
            exit;
        }
        echo $this->twig->render('admin/update_ordinance.twig', ['ordinance' => $ordinance]);
    }

    public function deleteOrdinance($id) {
        $this->ordinanceModel->deleteOrdinance($id);
        header('Location: /admin/ordinances');
        exit;
    }
}
