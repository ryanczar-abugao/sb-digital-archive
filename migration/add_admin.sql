-- STORED PROCEDURE TO ADD ADMINISTRATOR add_admin
-- http://localhost:8080/admin/encrypt/password

CALL add_admin (
    'Virgilio', 
    'Bro', 
    'Emman', 
    'female', 
    'Vice Mayor', 
    'username', 
    '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO'
);

DELIMITER $$

CREATE PROCEDURE add_admin (
    IN firstName VARCHAR(50),
    IN middleName VARCHAR(50),
    IN lastName VARCHAR(50),
    IN gender VARCHAR(50),
    IN position VARCHAR(50),
    IN username VARCHAR(50),
    IN pass VARCHAR(255)
)
BEGIN 
    DECLARE userId INT;
    DECLARE credentialId INT;

    INSERT INTO `users`
        (`firstName`, `middleName`, `lastName`, `gender`, `position`, `fileInput`, `role`, `createdAt`, `updatedBy`, `updatedAt`) 
    VALUES 
        (firstName, middleName, lastName, gender, position, '', 'admin', NOW(), NULL, NULL);

    SET userId = LAST_INSERT_ID();

    INSERT INTO `credentials`
        (`username`, `password`, `createdAt`, `updatedAt`, `userId`) 
    VALUES 
        (username, pass, NOW(), NULL, userId);
END$$