<?php
namespace Controller;

use Model\Member;
use Model\Chapter;

class AboutController {
    private $chapterModel;
    private $memberModel;
    private $twig;

    public function __construct($pdo, $twig) {
        $this->chapterModel = new Chapter($pdo);
        $this->memberModel = new Member($pdo);
        $this->twig = $twig;
    }

    public function showAbout() {
        $chapters = $this->chapterModel->getAllChapters();
        $members = $this->memberModel->getAllMembers();
        
        echo $this->twig->render('about.twig', [
            'chapters' => json_decode($chapters), 
            'members' => $members
        ]);
    }
}
