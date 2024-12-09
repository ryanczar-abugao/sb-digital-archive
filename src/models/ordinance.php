<?php

namespace Model;

use PDO;

class Ordinance
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllOrdinances($year = null)
    {
        if ($year == null) {
            $stmt = $this->pdo->prepare("SELECT * FROM ordinances ORDER BY year DESC");
            $stmt->execute();
        } else {            
            $stmt = $this->pdo->prepare("SELECT * FROM ordinances WHERE year = :year ORDER BY year DESC");
            $stmt->execute(['year' => $year]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdinanceYears()
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT year FROM ordinances ORDER BY year DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdinance($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ordinances WHERE ordinanceId = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrdinance($fileInput, $title, $authors, $year, $description, $createdBy)
    {
        $stmt = $this->pdo->prepare("INSERT INTO ordinances (fileInput, title, authors, year, description, createdBy, createdAt) VALUES (:fileInput, :title, :authors, :year, :description, :createdBy, NOW())");
        return $stmt->execute([
            'fileInput' => $fileInput,
            'title' => $title,
            'authors' => $authors,
            'year' => $year,
            'description' => $description,
            'createdBy' => $createdBy
        ]);
    }

    public function updateOrdinance($id, $fileInput, $title, $authors, $year,  $description, $updatedBy)
    {
        $stmt = $this->pdo->prepare("UPDATE ordinances SET fileInput = :fileInput, title = :title, authors = :authors, year = :year, description = :description, updatedBy = :updatedBy WHERE ordinanceId = :id");
        return $stmt->execute([
            'id' => $id,
            'fileInput' => $fileInput,
            'title' => $title,
            'authors' => $authors,
            'year' => $year,
            'description' => $description,
            'updatedBy' => $updatedBy
        ]);
    }

    public function deleteOrdinance($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM ordinances WHERE ordinanceId = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function searchOrdinances($query)
    {
        // Prepare the SQL query with separate LIKE conditions for each column
        $stmt = $this->pdo->prepare(
            "SELECT * FROM ordinances 
            WHERE title LIKE :title 
            OR description LIKE :description 
            OR authors LIKE :authors 
            OR year LIKE :year 
            ORDER BY year DESC"
        );

        // Bind the parameters with wildcards for partial matching
        $stmt->bindValue(':title', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':description', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':authors', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':year', '%' . $query . '%', PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Fetch and return the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
