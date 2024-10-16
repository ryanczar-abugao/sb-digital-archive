<?php
namespace Controller;

use Model\Member;

class MemberController {
    private $memberModel;
    private $twig;
    private $defaultFormAcion;
    private $baseDir;
    private $fileBaseName;

    public function __construct($pdo, $twig) {
        $this->memberModel = new Member($pdo);
        $this->twig = $twig;
        $this->defaultFormAcion = '/admin/members/create';
        $this->baseDir = "C://xampp/htdocs/sb-digital-archive/public";
        $this->fileBaseName = "member_";
    }

    public function showMembers() {
        $members = $this->memberModel->getAllMembers();
        echo $this->twig->render('admin/members.twig', [
            'members' => $members, 
            'formAction' => $this->defaultFormAcion
        ]);
    }

    public function showSelectedMember($id) {
        $members = $this->memberModel->getAllMembers();
        $member = $this->memberModel->getMember($id);
        echo $this->twig->render('admin/members.twig', [
            'members' => $members, 
            'selectedMember' => $member,
            'formAction' => "/admin/members/update/$id"
        ]);
    }
    
    public function createMember() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userId'])) {
            // Validate and process input
            $firstName = $_POST['firstName'];
            $middleName = $_POST['middleName'] ?? '';
            $lastName = $_POST['lastName'];
            $description = $_POST['description'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $position = $_POST['position'];
            $createdBy = $_SESSION['userId']; // Assuming user ID from session

            // Handle file upload
            $profilePicture = '';
            if (!empty($_FILES['profilePicture']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['profilePicture']['tmp_name'], $fullPath);
                $profilePicture = $targetPath;
            }

            // Insert into database
            $this->memberModel->createMember($firstName, $middleName, $lastName, $description, $gender, $address, $position, $profilePicture, $createdBy);

            header("Location: /admin/members");
            exit;
        }

        $members = $this->memberModel->getAllMembers();
        echo $this->twig->render('admin/members.twig', [
            'members' => $members, 
            'formAction' => $this->defaultFormAcion
        ]);
    }

    public function updateMember($id) {
        session_start();
        $selectedMember = $this->memberModel->getMember($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userId'])) {
            // Validate and process input
            $firstName = $_POST['firstName'];
            $middleName = $_POST['middleName'] ?? '';
            $lastName = $_POST['lastName'];
            $description = $_POST['description'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $position = $_POST['position'];
            $updatedBy = $_SESSION['userId']; // Assuming user ID from session

            // Handle file upload
            $profilePicture = $selectedMember['profilePicture']; // Default to existing picture
            if (!empty($_FILES['profilePicture']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['profilePicture']['tmp_name'], $fullPath);
                $profilePicture = $targetPath;
            }

            $this->memberModel->updateMember($id, $firstName, $middleName, $lastName, $description, $gender, $address, $position, $profilePicture, $updatedBy);
            header("Location: /admin/members/edit/$id");
            exit;
        }

        $members = $this->memberModel->getAllMembers();
        echo $this->twig->render('admin/members.twig', [
            'members' => $members, 
            'selectedMember' => $selectedMember,
            'formAction' => $this->defaultFormAcion
        ]);
    }

    public function deleteMember($id) {
        $this->memberModel->deleteMember($id);
        header('Location: /admin/members');
        exit;
    }
}
