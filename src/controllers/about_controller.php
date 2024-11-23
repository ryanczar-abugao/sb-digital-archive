<?php
namespace Controller;

use Model\Member;
use Model\History;
use Constants\CssConstants;

class AboutController {
    private $historyModel;
    private $memberModel;
    private $twig;
    private $cssConstants;

    public function __construct($pdo, $twig) {
        $this->cssConstants = new CssConstants();
        $this->historyModel = new History($pdo);
        $this->memberModel = new Member($pdo);
        $this->twig = $twig;
    }

    public function showAbout() {
        $chapters = $this->historyModel->getChaptersWithContents();
        
        echo $this->twig->render('about.twig', [
            'chapters' => $chapters, 
            'css' => $this->cssConstants, 
            'currentPage' => 'about'
        ]);
    }

    public function showMembers() {
        $members = $this->memberModel->getAllMembers();

        $groupedMembers = [];
        foreach ($members as $member) {
            $term = $member['term'];
            if (!isset($groupedMembers[$term])) {
                $groupedMembers[$term] = [];
            }
            $groupedMembers[$term][] = $member;
        }
        
        echo $this->twig->render('member.twig', [
            'groupedMembers' => $groupedMembers,
            'css' => $this->cssConstants, 
            'currentPage' => 'member'
        ]);
    }
}
