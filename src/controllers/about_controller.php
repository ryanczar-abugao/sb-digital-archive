<?php
namespace Controller;

use Model\Member;
use Model\History;

class AboutController {
    private $historyModel;
    private $memberModel;
    private $twig;

    public function __construct($pdo, $twig) {
        $this->historyModel = new History($pdo);
        $this->memberModel = new Member($pdo);
        $this->twig = $twig;
    }

    public function showAbout() {
        $history = $this->historyModel->getAllHistory();
        $members = $this->memberModel->getAllMembers();
        echo $this->twig->render('about.twig', [
            'histories' => $history, 
            'members' => $members
        ]);
    }
}
