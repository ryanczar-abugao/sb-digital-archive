<?php
namespace Model;

use PDO;

class History {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllHistory() {
        $stmt = $this->pdo->prepare("SELECT * FROM history");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistory($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM history WHERE historyId = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createHistory($chapter, $title, $content, $createdBy) {
        $stmt = $this->pdo->prepare("INSERT INTO history (chapter, title, content, createdBy) VALUES (:chapter, :title, :content, :createdBy)");
        return $stmt->execute([
            'chapter' => $chapter,
            'title' => $title,
            'content' => $content,
            'createdBy' => $createdBy
        ]);
    }

    public function updateHistory($id, $chapter, $title, $content) {
        $stmt = $this->pdo->prepare("UPDATE history SET chapter = :chapter, title = :title, content = :content WHERE historyId = :id");
        return $stmt->execute([
            'id' => $id,
            'chapter' => $chapter,
            'title' => $title,
            'content' => $content
        ]);
    }

    public function deleteHistory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM history WHERE historyId = :id");
        return $stmt->execute(['id' => $id]);
    }
}
