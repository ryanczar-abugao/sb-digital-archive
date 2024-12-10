<?php

namespace Controller;

use Model\News;
use Constants\CssConstants;
use Form\FormActions;
use Helpers\SessionHelpers;

class NewsController
{
  private $newsModel;
  private $twig;
  private $cssConstants;
  private $formActions;
  private $sessionHelper;

  public function __construct($pdo, $twig)
  {
    $this->newsModel = new News($pdo);
    $this->cssConstants = new CssConstants();
    $this->sessionHelper = new SessionHelpers();
    $this->twig = $twig;
    $this->formActions = new FormActions("/admin/news");
  }

  // Private method to handle rendering the news template
  private function renderNewsPage($newsArticles, $selectedNews = null, $formAction = null)
  {
    echo $this->twig->render('admin/news.twig', [
      'newsArticles' => $newsArticles,
      'selectedNews' => $selectedNews,
      'css' => $this->cssConstants,
      'formAction' => $formAction,
      'isLoggedIn' => isset($_SESSION['userId']),
      'currentPage' => 'news'
    ]);
  }

  public function showNews()
  {
    session_start();

    $newsArticles = $this->newsModel->getAllNews(); // Use model method to fetch all news articles
    $this->renderNewsPage($newsArticles, null, $this->formActions->create());
  }

  public function showSelectedNews($id)
  {
    session_start();
    $newsArticles = $this->newsModel->getAllNews(); // Fetch all news articles again for consistency
    $selectedNews = $this->newsModel->getNewsById($id); // Fetch the selected news article using model method
    $this->renderNewsPage($newsArticles, $selectedNews, $this->formActions->update($id));
  }

  public function createNews()
  {
    session_start();

    $this->sessionHelper->verifyLoggedUser();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'];
      $contents = $_POST['contents'];
      $createdBy = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;
      $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;

      // Validate image files
      if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        $maxFiles = 3;
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Check number of files
        if (count($_FILES['image']['name']) > $maxFiles) {
          echo "You can only upload a maximum of 3 images.";
          return;
        }

        // Check file sizes
        foreach ($_FILES['image']['size'] as $size) {
          if ($size > $maxSize) {
            echo "Each image must be less than 2MB.";
            return;
          }
        }
      }

      // Create the news article
      $newsId = $this->newsModel->createNews($title, $contents, $createdBy, $userId);

      if (!$newsId) {
        echo "Failed to create news article.";
        return;
      }

      // Handle image uploads
      if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        $uploadsDir = 'uploads/images/';
        if (!is_dir($uploadsDir)) {
          mkdir($uploadsDir, 0755, true);
        }

        $uploadedImages = [];
        foreach ($_FILES['image']['name'] as $index => $imageName) {
          $imageTmpName = $_FILES['image']['tmp_name'][$index];

          // Generate a unique filename for the image
          $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
          $uniqueName = uniqid('news_', true) . '.' . $fileExtension; // Unique ID + file extension
          $imagePath = $uploadsDir . $uniqueName;

          // Move the uploaded file to the uploads directory
          if (move_uploaded_file($imageTmpName, $imagePath)) {
            $uploadedImages[] = $imagePath; // Store the image path in the array
          } else {
            echo "Failed to upload image: " . $imageName;
          }
        }

        // Add the images as attachments
        if (!empty($uploadedImages)) {
          $this->newsModel->addAttachments($newsId, $uploadedImages);
        }
      }

      // Redirect after creation
      header('Location: /admin/news');
      exit;
    }
  }

  public function updateNews($id)
  {
    session_start();
    $news = $this->newsModel->getNewsById($id); // Get the news article by ID
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'];
      $contents = $_POST['contents'];
      $updatedBy = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;
      $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : 1;

      // Update the news article
      $this->newsModel->updateNews($id, $title, $contents, $updatedBy, $userId);

      // Delete the existing attachments for this news article before adding new ones
      $this->newsModel->deleteAttachments($id);

      // Handle image uploads
      $uploadedImages = [];
      if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        $uploadsDir = 'uploads/images/';
        if (!is_dir($uploadsDir)) {
          mkdir($uploadsDir, 0755, true);
        }

        foreach ($_FILES['image']['name'] as $index => $imageName) {
          $imageTmpName = $_FILES['image']['tmp_name'][$index];

          // Generate a unique filename for the image
          $fileExtension = pathinfo($imageName, PATHINFO_EXTENSION);
          $uniqueName = uniqid('news_', true) . '.' . $fileExtension; // Unique ID + file extension
          $imagePath = $uploadsDir . $uniqueName;

          // Move the uploaded file to the uploads directory
          if (move_uploaded_file($imageTmpName, $imagePath)) {
            $uploadedImages[] = $imagePath; // Store the image path in the array
          } else {
            echo "Failed to upload image: " . $imageName;
          }
        }
      }

      // Add the new attachments (images) to the news article
      if (!empty($uploadedImages)) {
        $this->newsModel->addAttachments($id, $uploadedImages);
      }

      // Redirect after update
      header('Location: /admin/news');
      exit;
    }
  }


  public function deleteNews($id)
  {
    // Delete the news article using the model method
    $this->newsModel->deleteNews($id);
    header('Location: /admin/news');
    exit;
  }
}
