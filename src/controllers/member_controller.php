<?php
namespace Controller;

use Model\Member;
use Constants\CssConstants;
use Helpers\SessionHelpers;

class MemberController{
    private $memberModel;
    private $twig;
    private $defaultFormAcion;
    private $baseDir;
    private $fileBaseName;
    private $cssConstants;
    private $sessionHelper;

    public function __construct($pdo, $twig) {
        $this->memberModel = new Member($pdo);
        $this->cssConstants = new CssConstants();
        $this->sessionHelper = new SessionHelpers();
        $this->twig = $twig;
        $this->defaultFormAcion = '/admin/members/create';
        $this->baseDir = "C://xampp/htdocs/sb-digital-archive/public";
        $this->fileBaseName = "member_";
    }

    public function showMembers() {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        $members = $this->memberModel->getAllMembers();

        $groupedMembers = [];
        foreach ($members as $member) {
            $term = $member['term'];
            if (!isset($groupedMembers[$term])) {
                $groupedMembers[$term] = [];
            }
            $groupedMembers[$term][] = $member;
        }

        echo $this->twig->render('admin/members.twig', [
            'groupedMembers' => $groupedMembers, 
            'formAction' => $this->defaultFormAcion,
            'css' => $this->cssConstants,
            'isLoggedIn' => isset($_SESSION['userId']), 
            'currentPage' => 'members'
        ]);
    }

    public function showSelectedMember($id) {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        $members = $this->memberModel->getAllMembers();
        $member = $this->memberModel->getMember($id);

        $groupedMembers = [];
        foreach ($members as $member) {
            $term = $member['term'];
            if (!isset($groupedMembers[$term])) {
                $groupedMembers[$term] = [];
            }
            $groupedMembers[$term][] = $member;
        }

        echo $this->twig->render('admin/members.twig', [
            'groupedMembers' => $groupedMembers, 
            'selectedMember' => $member,
            'formAction' => "/admin/members/update/$id",
            'css' => $this->cssConstants,
            'isLoggedIn' => isset($_SESSION['userId']), 
            'currentPage' => 'members'
        ]);
    }
    
    public function createMember() {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userId'])) {
            // Validate and process input
            $firstName = $_POST['firstName'];
            $middleName = $_POST['middleName'] ?? '';
            $lastName = $_POST['lastName'];
            $description = $_POST['description'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $position = $_POST['position'];
            $createdBy = $_SESSION['userId']; 
            $termStart = $_POST['termStart']; 
            $termEnd = $_POST['termEnd']; 

            // Handle file upload
            $fileInput = '';
            if (!empty($_FILES['fileInput']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['fileInput']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['fileInput']['tmp_name'], $fullPath);
                $fileInput = $targetPath;
            }

            // Insert into database
            $this->memberModel->createMember($firstName, $middleName, $lastName, $description, $gender, $address, $position, $fileInput, $createdBy, $termStart, $termEnd);

            header("Location: /admin/members");
            exit;
        }

        $members = $this->memberModel->getAllMembers();
        echo $this->twig->render('admin/members.twig', [
            'members' => $members, 
            'formAction' => $this->defaultFormAcion,
            'css' => $this->cssConstants,
            'isLoggedIn' => isset($_SESSION['userId']), 
            'currentPage' => 'members'
        ]);
    }

    public function updateMember($id) {
        session_start();

        $this->sessionHelper->verifyLoggedUser();

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
            $updatedBy = $_SESSION['userId']; 
            $termStart = $_POST['termStart']; 
            $termEnd = $_POST['termEnd']; 

            // Handle file upload
            $fileInput = $selectedMember['fileInput']; // Default to existing picture
            if (!empty($_FILES['fileInput']['name'])) {
                $targetDir = "/uploads/";
                $fileExtension = pathinfo($_FILES['fileInput']['name'], PATHINFO_EXTENSION); // Get the file extension
                $uniqueFileName = uniqid($this->fileBaseName, true) . '.' . $fileExtension;
                $targetPath = $targetDir . $uniqueFileName;
                $fullPath = $this->baseDir . $targetDir . $uniqueFileName;
                move_uploaded_file($_FILES['fileInput']['tmp_name'], $fullPath);
                $fileInput = $targetPath;
            }
            

            $this->memberModel->updateMember($id, $firstName, $middleName, $lastName, $description, $gender, $address, $position, $fileInput, $updatedBy, $termStart, $termEnd);
            header("Location: /admin/members/edit/$id");
            exit;
        }

        $members = $this->memberModel->getAllMembers();
        echo $this->twig->render('admin/members.twig', [
            'members' => $members, 
            'selectedMember' => $selectedMember,
            'formAction' => $this->defaultFormAcion,
            'css' => $this->cssConstants,
            'isLoggedIn' => isset($_SESSION['userId']), 
            'currentPage' => 'members'
        ]);
    }

    public function deleteMember($id) {
        session_start();
        
        $this->sessionHelper->verifyLoggedUser();

        $this->memberModel->deleteMember($id);
        header('Location: /admin/members');
        exit;
    }
}
