<?php
namespace Controller;

use Model\History;

class HistoryController {
    private $historyModel;
    private $twig;

    public function __construct($pdo, $twig) {
        $this->historyModel = new History($pdo);
        $this->twig = $twig;
    }

    public function showHistory() {
        $history = $this->historyModel->getAllHistory();
        echo $this->twig->render('admin/history.twig', ['history' => $history]);
    }

    public function createHistory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapter = $_POST['chapter'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $createdBy = $_SESSION['userId'];

            $this->historyModel->createHistory($chapter, $title, $content, $createdBy);

            header("Location: /admin/history");
            exit;
        }

        echo $this->twig->render('admin/create_history.twig');
    }

    public function updateHistory($id) {
        $history = $this->historyModel->getHistory($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $event = $_POST['event'];
            $description = $_POST['description'];
            $this->historyModel->updateHistory($id, $event, $description);
            header('Location: /admin/history');
            exit;
        }
        echo $this->twig->render('admin/update_history.twig', ['history' => $history]);
    }

    public function deleteHistory($id) {
        $this->historyModel->deleteHistory($id);
        header('Location: /admin/history');
        exit;
    }
}
