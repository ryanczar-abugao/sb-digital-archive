<?php

namespace Model;

use PDO;

class Member
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllMembers($term = null)
    {
        if ($term == null) {
            $stmt = $this->pdo->prepare("SELECT *, CONCAT(YEAR(termStart), ' - ', YEAR(termEnd)) AS term FROM sbmembers ORDER BY termStart DESC");
            $stmt->execute();
        } else {
            $stmt = $this->pdo->prepare("SELECT *, CONCAT(YEAR(termStart), ' - ', YEAR(termEnd)) AS term FROM sbmembers WHERE CONCAT(YEAR(termStart), '-', YEAR(termEnd)) LIKE :term");
            $stmt->bindValue(':term', '%' . $term . '%', PDO::PARAM_STR);
            $stmt->execute();
        }

        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($members as &$member) {
            $termStart = \DateTime::createFromFormat('Y-m-d', $member['termStart']);
            $termEnd = \DateTime::createFromFormat('Y-m-d', $member['termEnd']);

            if ($termStart && $termEnd) {
                $member['termStart'] = $termStart->format('Y');
                $member['termEnd'] = $termEnd->format('Y');
                $member['term'] = $member['termStart'] . '-' . $member['termEnd'];
            }
        }

        return $members;
    }

    public function getMember($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM sbmembers WHERE memberId = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createMember($firstName, $middleName, $lastName, $description, $gender, $address, $position, $fileInput, $createdBy, $termStart, $termEnd)
    {
        $stmt = $this->pdo->prepare("INSERT INTO sbmembers (firstName, middleName, lastName, description, gender, address, position, fileInput, createdBy, termStart, termEnd) VALUES (:firstName, :middleName, :lastName, :description, :gender, :address, :position, :fileInput, :createdBy, :termStart, :termEnd)");
        return $stmt->execute([
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'description' => $description,
            'gender' => $gender,
            'address' => $address,
            'position' => $position,
            'fileInput' => $fileInput,
            'createdBy' => $createdBy,
            'termStart' => $termStart,
            'termEnd' => $termEnd
        ]);
    }

    public function updateMember($id, $firstName, $middleName, $lastName, $description, $gender, $address, $position, $fileInput, $updatedBy, $termStart, $termEnd)
    {
        $stmt = $this->pdo->prepare("UPDATE sbmembers SET firstName = :firstName, middleName = :middleName, lastName = :lastName, description = :description, gender = :gender, address = :address, position = :position, fileInput = :fileInput, updatedBy = :updatedBy, updatedAt = NOW(), termStart = :termStart, termEnd = :termEnd WHERE memberId = :id");
        return $stmt->execute([
            'id' => $id,
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'description' => $description,
            'gender' => $gender,
            'address' => $address,
            'position' => $position,
            'fileInput' => $fileInput,
            'updatedBy' => $updatedBy,
            'termStart' => $termStart,
            'termEnd' => $termEnd
        ]);
    }

    public function deleteMember($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sbmembers WHERE memberId = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function searchMember($query)
    {
        // Prepare the SQL query with separate LIKE conditions for each column
        $stmt = $this->pdo->prepare(
            "SELECT *, CONCAT(termStart, ' - ', termEnd) AS term FROM sbmembers 
            WHERE CONCAT(firstName, ' ', lastName) LIKE :firstAndLastNameQUery
            OR CONCAT(firstName, ' ', middleName, ' ', lastName) LIKE :fullnameQUery"
        );

        // Bind the parameters with wildcards for partial matching
        $stmt->bindValue(':firstAndLastNameQUery', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->bindValue(':fullnameQUery', '%' . $query . '%', PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($members as &$member) {
            $termStart = \DateTime::createFromFormat('Y-m-d', $member['termStart']);
            $termEnd = \DateTime::createFromFormat('Y-m-d', $member['termEnd']);

            // Check if parsing is successful and format dates
            if ($termStart && $termEnd) {
                $member['termStart'] = $termStart->format('F j, Y');
                $member['termEnd'] = $termEnd->format('F j, Y');
                $member['term'] = $member['termStart'] . ' to ' . $member['termEnd'];
            }
        }

        return $members;
    }
}
