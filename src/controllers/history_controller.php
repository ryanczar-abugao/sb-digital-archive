<?php
namespace Controller;

use Model\Chapter;

class HistoryController {
    private $historyModel;
    private $twig;

    public function __construct($pdo, $twig) {
        $this->historyModel = new Chapter($pdo);
        $this->twig = $twig;
    }

    public function showChapters() {
        $chapters = $this->historyModel->getAllChapters();
        echo $this->twig->render('admin/history.twig', ['chapters' => json_decode($chapters)]);
    }

    public function createChapter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapter = $_POST['chapter'];
            $title = $_POST['title'];
            $createdBy = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;
            $createdAt = date('Y-m-d H:i:s');

            $this->historyModel->createChapter($chapter, $title, $createdBy, $createdAt);

            header("Location: /admin/history");
            exit;
        }

        $chapters = $this->historyModel->getAllChapters();
        echo $this->twig->render('admin/history.twig', ['chapters' => $chapters]);
    }

    public function updateChapter($id) {
        $history = $this->historyModel->getChapter($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapter = $_POST['chapter'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $this->historyModel->updateChapter($id, $chapter, $title, $content);
            header('Location: /admin/history');
            exit;
        }
        echo $this->twig->render('admin/update_history.twig', ['history' => $history]);
    }

    public function deleteChapter($id) {
        $this->historyModel->deleteChapter($id);
        header('Location: /admin/history');
        exit;
    }
}
