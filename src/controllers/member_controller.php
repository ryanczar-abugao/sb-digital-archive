<?php
namespace Controller;

use Model\Member;

class MemberController {
    private $memberModel;
    private $twig;

    public function __construct($pdo, $twig) {
        $this->memberModel = new Member($pdo);
        $this->twig = $twig;
    }

    public function showMembers() {
        $members = $this->memberModel->getAllMembers();
        echo $this->twig->render('admin/members.twig', ['members' => $members]);
    }

    public function createMember() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and process input
            $firstName = $_POST['firstName'];
            $middleName = $_POST['middleName'] ?? '';
            $lastName = $_POST['lastName'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $position = $_POST['position'];
            $createdBy = $_SESSION['userId']; // Assuming user ID from session

            // Handle file upload
            $profilePicture = '';
            if (!empty($_FILES['profilePicture']['name'])) {
                $targetDir = "../public/uploads/";
                $targetFile = $targetDir . basename($_FILES['profilePicture']['name']);
                move_uploaded_file($_FILES['profilePicture']['tmp_name'], $targetFile);
                $profilePicture = $targetFile;
            }

            // Insert into database
            $this->memberModel->createMember($firstName, $middleName, $lastName, $gender, $address, $position, $profilePicture, $createdBy);

            header("Location: /admin/members");
            exit;
        }

        echo $this->twig->render('admin/create_member.twig');
    }

    public function updateMember($id) {
        $member = $this->memberModel->getMember($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and process input
            $firstName = $_POST['firstName'];
            $middleName = $_POST['middleName'] ?? '';
            $lastName = $_POST['lastName'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $position = $_POST['position'];
            $createdBy = $_SESSION['userId']; // Assuming user ID from session

            // Handle file upload
            $profilePicture = $member['profilePicture']; // Default to existing picture
            if (!empty($_FILES['profilePicture']['name'])) {
                $targetDir = "../public/uploads/";
                $targetFile = $targetDir . basename($_FILES['profilePicture']['name']);
                move_uploaded_file($_FILES['profilePicture']['tmp_name'], $targetFile);
                $profilePicture = $targetFile; // Update to new picture
            }

            $this->memberModel->updateMember($id, $firstName, $middleName, $lastName, $gender, $address, $position, $profilePicture);
            header('Location: /admin/members');
            exit;
        }

        echo $this->twig->render('admin/update_member.twig', ['member' => $member]);
    }

    public function deleteMember($id) {
        $this->memberModel->deleteMember($id);
        header('Location: /admin/members');
        exit;
    }
}
