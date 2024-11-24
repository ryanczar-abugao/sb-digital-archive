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

    public function getAllMembers()
    {
        $stmt = $this->pdo->prepare("SELECT *, CONCAT(termStart, ' - ', termEnd) AS term FROM sbmembers ORDER BY term DESC");
        $stmt->execute();
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format the termStart and termEnd dates
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
}
