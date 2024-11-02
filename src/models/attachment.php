<?php

namespace Model;

use PDO;

class Attachment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function saveAttachment($chapterId, $url) {
        $stmt = $this->pdo->prepare("INSERT INTO attachments (chapterId, url) VALUES (:chapterId, :url)");
        $stmt->execute([':chapterId' => $chapterId, ':url' => $url]);
    }
}
