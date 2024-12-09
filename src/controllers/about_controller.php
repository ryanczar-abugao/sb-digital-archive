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

        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;
        $termQuery = isset($_GET['term']) ? trim($_GET['term']) : null;

        if ($searchQuery)
        {
            $members = $this->memberModel->searchMember($searchQuery);
        } 
        else 
        {
            $members = $this->memberModel->getAllMembers($termQuery);
        }

        $groupedMembers = [];
        foreach ($members as $member) {
            $term = $member['term'];
            if (!isset($groupedMembers[$term])) {
                $groupedMembers[$term] = [];
            }
            $groupedMembers[$term][] = $member;
        }

        $termss = $this->memberModel->getAllMembers();
        $groupedTerms = [];
        foreach ($termss as $terms) {
            $term = $terms['term'];
            if (!isset($groupedTerms[$term])) {
                $groupedTerms[$term] = [];
            }
            $groupedTerms[$term][] = $term;
        }
                
        echo $this->twig->render('member.twig', [
            'groupedMembers' => $groupedMembers,
            'groupedTerms' => $groupedTerms,
            'selectedTerm' => $termQuery,
            'css' => $this->cssConstants, 
            'currentPage' => 'member'
        ]);
    }
}
