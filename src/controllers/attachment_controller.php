<?php
namespace Controller;

use Model\Attachment;

class AttachmentController {
    private $attachmentModel;

    public function __construct($pdo) {
        $this->attachmentModel = new Attachment($pdo);
    }

    public function handleFileUploads($id, $files) {
        $uploadsDir = __DIR__ . '/../../public/uploads/'; // Ensure this path is correct

        // Ensure the uploads directory exists
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        foreach ($files['tmp_name'] as $key => $tmpName) {
            if ($files['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = basename($files['name'][$key]);
                $targetFile = $uploadsDir . $fileName;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    // Save attachment info to the database
                    $this->attachmentModel->saveAttachment($id, '/uploads/' . $fileName);
                } else {
                    // Handle file move error
                    error_log("Failed to move uploaded file: $fileName");
                }
            } else {
                // Handle upload error
                error_log("Upload error for file: " . $files['name'][$key] . ", Error Code: " . $files['error'][$key]);
            }
        }
    }
}
