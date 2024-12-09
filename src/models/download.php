<?php

namespace Model;

use PDO;

class Download
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function logDownload($email, $ordinanceId) {
        $stmt = $this->pdo->prepare("INSERT INTO downloads (email, ordinanceId) VALUES (:email, :ordinanceId)");
        $stmt->execute([
            'email' => $email, 
            'ordinanceId' => $ordinanceId
        ]);
    }
}
