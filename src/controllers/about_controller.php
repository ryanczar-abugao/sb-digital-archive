<?php

namespace Controller;

use Model\Member;
use Model\History;
use Constants\CssConstants;

class AboutController
{
    private $historyModel;
    private $memberModel;
    private $twig;
    private $cssConstants;

    public function __construct($pdo, $twig)
    {
        $this->cssConstants = new CssConstants();
        $this->historyModel = new History($pdo);
        $this->memberModel = new Member($pdo);
        $this->twig = $twig;
    }

    public function showAbout()
    {
        $chapters = $this->historyModel->getChaptersWithContents();

        echo $this->twig->render('about.twig', [
            'chapters' => $chapters,
            'css' => $this->cssConstants,
            'currentPage' => 'about'
        ]);
    }

    public function showMembers()
    {
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;
        $termQuery = isset($_GET['term']) ? trim($_GET['term']) : null;

        $members = $searchQuery
            ? $this->memberModel->searchMember($searchQuery)
            : $this->memberModel->getAllMembers($termQuery);

        $groupedMembers = $this->groupMembersByTerm($members);

        $allMembers = $this->memberModel->getAllMembers();
        $groupedTerms = $this->extractUniqueTerms($allMembers);

        echo $this->twig->render('member.twig', [
            'groupedMembers' => $groupedMembers,
            'groupedTerms' => $groupedTerms,
            'selectedTerm' => $termQuery,
            'css' => $this->cssConstants,
            'currentPage' => 'member',
        ]);
    }

    private function groupMembersByTerm(array $members): array
    {
        $grouped = [];
        foreach ($members as $member) {
            $term = $member['term'];
            if (!isset($grouped[$term])) {
                $grouped[$term] = [];
            }
            $grouped[$term][] = $member;
        }
        return $grouped;
    }

    private function extractUniqueTerms(array $members): array
    {
        $uniqueTerms = [];
        foreach ($members as $member) {
            $term = $member['term'];
            if (!isset($uniqueTerms[$term])) {
                $uniqueTerms[$term] = $term;
            }
        }
        return $uniqueTerms;
    }
}
