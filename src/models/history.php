<?php

namespace Model;

use PDO;

class History
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getChapters()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM histories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChaptersWithContents()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM histories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChapter($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM histories WHERE historyId = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createChapter($chapter, $title, $contents, $createdBy, $createdAt)
    {
        $stmt = $this->pdo->prepare("INSERT INTO histories (chapter, title, contents, createdBy, createdAt) VALUES (:chapter, :title, :contents, :createdBy, :createdAt)");

        return $stmt->execute([
            'chapter' => $chapter,
            'title' => $title,
            'contents' => $contents,
            'createdBy' => $createdBy,
            'createdAt' => $createdAt
        ]);
    }

    public function updateChapter($id, $chapter, $title, $contents, $updatedBy)
    {
        $stmt = $this->pdo->prepare("UPDATE histories SET chapter = :chapter, title = :title, contents = :contents, updatedBy = :updatedBy WHERE historyId = :id");
        return $stmt->execute([
            'id' => $id,
            'chapter' => $chapter,
            'title' => $title,
            'contents' => $contents,
            'updatedBy' => $updatedBy,
        ]);
    }

    public function deleteChapter($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM histories WHERE historyId = :id");
        return $stmt->execute(['id' => $id]);
    }
}
