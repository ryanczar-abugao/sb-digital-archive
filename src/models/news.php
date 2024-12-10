<?php

namespace Model;

use PDO;

class News
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all news articles
    public function getAllNews()
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            news.*, 
            CONCAT(users.firstName, ' ', users.lastName) AS author, 
            users.firstName AS authorFirstName, 
            users.lastName AS authorLastName, 
            attachments.url AS attachmentUrl,
            attachments.lineNum AS attachmentLineNum
        FROM 
            news
        LEFT JOIN 
            users ON news.userId = users.userId
        LEFT JOIN 
            attachments ON news.newsId = attachments.newsId
        ORDER BY 
            news.createdAt DESC
    ");
        $stmt->execute();
        $newsArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group attachments by newsId
        $groupedNewsArticles = [];
        foreach ($newsArticles as $news) {
            $newsId = $news['newsId'];

            // Ensure each news article is grouped correctly
            if (!isset($groupedNewsArticles[$newsId])) {
                $groupedNewsArticles[$newsId] = $news;
                $groupedNewsArticles[$newsId]['attachments'] = [];
            }

            // Add attachment if available
            if ($news['attachmentUrl']) {
                $groupedNewsArticles[$newsId]['attachments'][] = [
                    'url' => $news['attachmentUrl'],
                    'lineNum' => $news['attachmentLineNum']
                ];
            }
        }

        // Reindex to get a clean array
        return array_values($groupedNewsArticles);
    }

    // Fetch a single news article by ID with attachments
    public function getNewsById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                news.*, 
                users.firstName AS authorFirstName, 
                users.lastName AS authorLastName 
            FROM 
                news 
            LEFT JOIN 
                users ON news.userId = users.userId
            WHERE 
                news.newsId = :id
        ");
        $stmt->execute(['id' => $id]);
        $news = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($news) {
            // Fetch attachments
            $news['attachments'] = $this->getAttachmentsByNewsId($id);
        }

        return $news;
    }

    // Create a new news article
    public function createNews($title, $contents, $createdBy, $userId)
    {
        try {
            $stmt = $this->pdo->prepare("
            INSERT INTO news (title, contents, createdBy, createdAt, userId) 
            VALUES (:title, :contents, :createdBy, NOW(), :userId)
        ");

            // Check if execute is successful
            if ($stmt->execute([
                'title' => $title,
                'contents' => $contents,
                'createdBy' => $createdBy,
                'userId' => $userId,
            ])) {
                return $this->pdo->lastInsertId(); // Return last inserted newsId
            } else {
                throw new \Exception("Failed to insert news article.");
            }
        } catch (\Exception $e) {
            echo "Error creating news article: " . $e->getMessage();
        }
    }


    // Update an existing news article
    public function updateNews($id, $title, $contents, $updatedBy, $userId)
    {
        $stmt = $this->pdo->prepare("
            UPDATE news 
            SET 
                title = :title, 
                contents = :contents, 
                updatedBy = :updatedBy, 
                updatedAt = NOW(), 
                userId = :userId 
            WHERE 
                newsId = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'title' => $title,
            'contents' => $contents,
            'updatedBy' => $updatedBy,
            'userId' => $userId,
        ]);
    }

    // Delete a news article and its attachments
    public function deleteNews($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE newsId = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Add attachments for a news article
    public function addAttachments($newsId, $attachments)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO attachments (newsId, lineNum, url) 
            VALUES (:newsId, :lineNum, :url)
        ");
        foreach ($attachments as $index => $attachment) {
            $stmt->execute([
                'newsId' => $newsId,
                'lineNum' => $index + 1,
                'url' => $attachment,
            ]);
        }
    }

    // Fetch attachments for a news article
    public function getAttachmentsByNewsId($newsId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM attachments WHERE newsId = :newsId ORDER BY lineNum ASC");
        $stmt->execute(['newsId' => $newsId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete attachments for a news article
    public function deleteAttachments($newsId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM attachments WHERE newsId = :newsId");
        return $stmt->execute(['newsId' => $newsId]);
    }
}
