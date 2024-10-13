<?php
namespace Model;

use PDO;

class Member {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMembers() {
        $stmt = $this->pdo->prepare("SELECT * FROM sbmembers");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMember($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM sbmembers WHERE memberId = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createMember($firstName, $middleName, $lastName, $description, $gender, $address, $position, $profilePicture, $createdBy) {
        $stmt = $this->pdo->prepare("INSERT INTO sbmembers (firstName, middleName, lastName, description, gender, address, position, profilePicture, createdBy) VALUES (:firstName, :middleName, :lastName, :gender, :address, :position, :profilePicture, :createdBy)");
        return $stmt->execute([
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'description' => $description,
            'gender' => $gender,
            'address' => $address,
            'position' => $position,
            'profilePicture' => $profilePicture,
            'createdBy' => $createdBy
        ]);
    }

    public function updateMember($id, $firstName, $middleName, $lastName, $description, $gender, $address, $position, $profilePicture, $updatedBy) {
        $stmt = $this->pdo->prepare("UPDATE sbmembers SET firstName = :firstName, middleName = :middleName, lastName = :lastName, description = :description, gender = :gender, address = :address, position = :position, profilePicture = :profilePicture, updatedBy = :updatedBy, updatedAt = NOW() WHERE memberId = :id");
        return $stmt->execute([
            'id' => $id,
            'firstName' => $firstName,
            'middleName' => $middleName,
            'lastName' => $lastName,
            'description' => $description,
            'gender' => $gender,
            'address' => $address,
            'position' => $position,
            'profilePicture' => $profilePicture,
            'updatedBy' => $updatedBy
        ]);
    }

    public function deleteMember($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sbmembers WHERE memberId = :id");
        return $stmt->execute(['id' => $id]);
    }
}
