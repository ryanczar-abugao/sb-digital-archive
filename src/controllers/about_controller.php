<?php
namespace Controller;

use Model\Member;
use Model\Chapter;
use Constants\CssConstants;

class AboutController {
    private $chapterModel;
    private $memberModel;
    private $twig;
    private $cssConstants;

    public function __construct($pdo, $twig) {
        $this->cssConstants = new CssConstants();
        $this->chapterModel = new Chapter($pdo);
        $this->memberModel = new Member($pdo);
        $this->twig = $twig;
    }

    public function showAbout() {
        $chapters = $this->chapterModel->getChaptersWithContents();
        
        echo $this->twig->render('about.twig', [
            'chapters' => $chapters, 
            'css' => $this->cssConstants, 
            'currentPage' => 'about'
        ]);
    }

    public function showMembers() {
        $members = $this->memberModel->getAllMembers();
        
        echo $this->twig->render('member.twig', [
            'members' => $members,
            'css' => $this->cssConstants, 
            'currentPage' => 'member'
        ]);
    }
}
