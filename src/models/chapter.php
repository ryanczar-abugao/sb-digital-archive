<?php

namespace Model;

use PDO;

class Chapter
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllChapters()
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                c.chapterId,
                c.chapter,
                c.title,
                c.createdBy,
                c.createdAt,
                c.userId,
                cc.contentId,
                cc.leftImage,
                cc.rightImage,
                cc.content
            FROM 
                chapters c 
            LEFT JOIN 
                chaptercontents cc ON c.chapterId = cc.chapterId
        ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedChapters = [];

        foreach ($results as $row) {
            $chapterId = $row['chapterId'];

            // Initialize chapter if not already done
            if (!isset($groupedChapters[$chapterId])) {
                $groupedChapters[$chapterId] = [
                    'chapterId' => $chapterId,
                    'chapter' => $row['chapter'],
                    'title' => $row['title'],
                    'createdBy' => $row['createdBy'],
                    'createdAt' => $row['createdAt'],
                    'userId' => $row['userId'],
                    'contents' => []
                ];
            }

            // Add content to the chapter's contents
            if ($row['contentId']) {
                $groupedChapters[$chapterId]['contents'][] = [
                    'contentId' => $row['contentId'],
                    'leftImage' => $row['leftImage'],
                    'rightImage' => $row['rightImage'],
                    'content' => $row['content']
                ];
            }
        }

        // Re-index the array
        $groupedChapters = array_values($groupedChapters);

        // Convert to JSON
        return json_encode($groupedChapters, JSON_PRETTY_PRINT);
    }

    public function getChapter($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM chapters WHERE chapterId = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createChapter($chapter, $title, $createdBy, $createdAt)
    {
        $stmt = $this->pdo->prepare("INSERT INTO chapters (chapter, title, createdBy, createdAt) VALUES (:chapter, :title, :createdBy, :createdAt)");
        return $stmt->execute([
            'chapter' => $chapter,
            'title' => $title,
            'createdBy' => $createdBy,
            'createdAt' => $createdAt
        ]);
    }

    public function updateChapter($id, $chapter, $title)
    {
        $stmt = $this->pdo->prepare("UPDATE chapters SET chapter = :chapter, title = :title WHERE chapterId = :id");
        return $stmt->execute([
            'id' => $id,
            'chapter' => $chapter,
            'title' => $title
        ]);
    }

    public function deleteChapter($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM chapters WHERE chapterId = :id");
        return $stmt->execute(['id' => $id]);
    }
}
