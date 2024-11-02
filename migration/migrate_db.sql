DROP DATABASE IF EXISTS sbarchive;

CREATE DATABASE sbarchive;

USE sbarchive;

CREATE TABLE systemsettings (
    systemSettingsId INT AUTO_INCREMENT PRIMARY KEY,
    systemName VARCHAR(50) NOT NULL,
    contactNum VARCHAR(50) NOT NULL,
    address VARCHAR(50) NOT NULL,
    logo VARCHAR(255) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    middleName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    position VARCHAR(50) NOT NULL,
    profilePicture VARCHAR(255) NOT NULL,
    role ENUM('admin', 'member') DEFAULT 'member',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedBy VARCHAR(50) NULL,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE credentials (
    credentialId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

CREATE TABLE sbmembers (
    memberId INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    middleName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    description VARCHAR(255) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    address VARCHAR(50) NOT NULL,
    position VARCHAR(50) NOT NULL,
    profilePicture VARCHAR(255) NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedBy VARCHAR(50) NULL,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);

CREATE TABLE ordinances (
    ordinanceId INT AUTO_INCREMENT PRIMARY KEY,
    ordinanceFile VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    authors VARCHAR(255) NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedBy VARCHAR(50) NULL,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);


CREATE TABLE chapters (
    chapterId INT AUTO_INCREMENT PRIMARY KEY,
    chapter VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    contents TEXT NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedBy VARCHAR(50) NULL,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);

CREATE TABLE attachments (
    attachmentId INT AUTO_INCREMENT PRIMARY KEY,
    chapterId INT,
    ordinanceId INT,
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (chapterId) REFERENCES chapters(chapterId) ON DELETE CASCADE,
    FOREIGN KEY (ordinanceId) REFERENCES ordinances(ordinanceId) ON DELETE CASCADE
);

INSERT INTO systemsettings (systemName, contactNum, address, logo, createdAt)
VALUES
('SB Archive System', '123-456-7890', '123 Main St, Cityville', 'logo.png', NOW());

INSERT INTO users (firstName, middleName, lastName, gender, position, profilePicture, role, createdAt) 
VALUES 
('Admin', 'Admin', 'Admin', 'male', 'SB Representative', '', 'admin', NOW()),
('John', 'Doe', 'Smith', 'male', 'Member', '', 'member', NOW()),
('Jane', 'Mary', 'Johnson', 'female', 'Secretary', '', 'member', NOW());

INSERT INTO sbmembers (firstName, middleName, lastName, description, gender, address, position, profilePicture, createdBy, createdAt, userId)
VALUES
('Michael', 'J', 'Johnson', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'male', '456 Oak St, Cityville', 'Treasurer', 'michael.png', 'Admin', NOW(), 1),
('Lisa', 'M', 'Brown', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'female', '789 Pine St, Cityville', 'Member', 'lisa.png', 'Admin', NOW(), 2);

INSERT INTO chapters (chapter, title, contents, createdBy, createdAt, userId)
VALUES
('Chapter 1', 'The Founding of Cityville', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'Jane Mary', NOW(), 3),
('Chapter 2', 'Expansion of Cityville', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'Admin', NOW(), 1);

INSERT INTO credentials (username, password, createdAt, userId)
VALUES 
('ADMIN', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 1),  -- 'pass'
('johnsmith', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 2),  -- 'pass'
('janemary', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 3);  -- 'pass'
