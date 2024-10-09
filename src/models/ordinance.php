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

    public function createOrdinance($filename, $title, $authors, $createdBy) {
        $stmt = $this->pdo->prepare("INSERT INTO ordinances (filename, title, authors, createdBy) VALUES (:filename, :title, :authors, :createdBy)");
        return $stmt->execute([
            'filename' => $filename,
            'title' => $title,
            'authors' => $authors,
            'createdBy' => $createdBy
        ]);
    }

    public function updateOrdinance($id, $filename, $title, $authors) {
        $stmt = $this->pdo->prepare("UPDATE ordinances SET filename = :filename, title = :title, authors = :authors WHERE ordinanceId = :id");
        return $stmt->execute([
            'id' => $id,
            'filename' => $filename,
            'title' => $title,
            'authors' => $authors
        ]);
    }

    public function deleteOrdinance($id) {
        $stmt = $this->pdo->prepare("DELETE FROM ordinances WHERE ordinanceId = :id");
        return $stmt->execute(['id' => $id]);
    }
}
