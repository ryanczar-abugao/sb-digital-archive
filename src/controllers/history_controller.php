<?php
namespace Controller;

use Model\History;
use Constants\CssConstants;
use Form\FormActions;
use Helpers\SessionHelpers;

class HistoryController {
    private $historyModel;
    private $twig;
    private $cssConstants;
    private $formActions;
    private $sessionHelper;


    public function __construct($pdo, $twig) {
        $this->historyModel = new History($pdo);
        $this->cssConstants = new CssConstants();
        $this->sessionHelper = new SessionHelpers();
        $this->twig = $twig;
        $this->formActions = new FormActions("/admin/history");
    }

    public function showChapters() {
        session_start();
        
        $this->sessionHelper->verifyLoggedUser();

        $chapters = $this->historyModel->getChapters();
        echo $this->twig->render('admin/history.twig', [
            'chapters' => $chapters,
            'css' => $this->cssConstants,
            'formAction' => $this->formActions->create(),
            'isLoggedIn' => isset($_SESSION['userId']), 
            'currentPage' => 'history'
        ]);
    }

    public function showSelectedChapter($id) {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        $chapters = $this->historyModel->getChapters();
        $selectedChapter = $this->historyModel->getChapter($id);
        echo $this->twig->render('admin/history.twig', [
            'chapters' => $chapters,
            'selectedChapter' => $selectedChapter,
            'css' => $this->cssConstants,
            'formAction' => $this->formActions->update($id),
            'isLoggedIn' => isset($_SESSION['userId']), 
            'currentPage' => 'history'
        ]);
    }

    public function createChapter() {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapter = $_POST['chapter'];
            $title = $_POST['title'];
            $createdBy = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;
            $createdAt = date('Y-m-d H:i:s');
            $contents = $_POST['contents'];

            $this->historyModel->createChapter($chapter, $title, $contents, $createdBy, $createdAt);
            $chapters = $this->historyModel->getChapters();
            echo $this->twig->render('admin/history.twig', [
                'chapters' => $chapters,
                'css' => $this->cssConstants,
                'formAction' => $this->formActions->create(),
                'isLoggedIn' => isset($_SESSION['userId']), 
                'currentPage' => 'history'
            ]);
        }
    }

    public function updateChapter($id) {
        $history = $this->historyModel->getChapter($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $chapter = $_POST['chapter'];
            $title = $_POST['title'];
            $contents = $_POST['contents'];
            $updatedBy = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;
            return $this->historyModel->updateChapter($id, $chapter, $title, $contents, $updatedBy);
        }
    }

    public function deleteChapter($id) {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        $this->historyModel->deleteChapter($id);
        header('Location: /admin/history');
        exit;
    }
}
