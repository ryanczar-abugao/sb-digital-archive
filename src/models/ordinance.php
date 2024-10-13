<?php
namespace Model;

use PDO;

class Ordinance {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllOrdinances() {
        $stmt = $this->pdo->prepare("SELECT * FROM ordinances");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdinance($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM ordinances WHERE ordinanceId = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrdinance($ordinanceFile, $title, $authors, $description, $createdBy) {
        $stmt = $this->pdo->prepare("INSERT INTO ordinances (ordinanceFile, title, authors, description, createdBy, createdAt) VALUES (:ordinanceFile, :title, :authors, :description, :createdBy, NOW())");
        return $stmt->execute([
            'ordinanceFile' => $ordinanceFile,
            'title' => $title,
            'authors' => $authors,
            'description' => $description,
            'createdBy' => $createdBy
        ]);
    }

    public function updateOrdinance($id, $ordinanceFile, $title, $authors, $description, $updatedBy) {
        $stmt = $this->pdo->prepare("UPDATE ordinances SET ordinanceFile = :ordinanceFile, title = :title, authors = :authors, description = :description, updatedBy = :updatedBy WHERE ordinanceId = :id");
        return $stmt->execute([
            'id' => $id,
            'ordinanceFile' => $ordinanceFile,
            'title' => $title,
            'authors' => $authors,
            'description' => $description,
            'updatedBy' => $updatedBy
        ]);
    }

    public function deleteOrdinance($id) {
        $stmt = $this->pdo->prepare("DELETE FROM ordinances WHERE ordinanceId = :id");
        return $stmt->execute(['id' => $id]);
    }
}
