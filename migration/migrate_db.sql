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
    createdBy VARCHAR(50) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedBy VARCHAR(50) NULL,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);

CREATE TABLE chaptercontents (
    contentId INT AUTO_INCREMENT PRIMARY KEY,
    leftImage VARCHAR(255) NOT NULL,
    rightImage VARCHAR(255) NOT NULL,
    content VARCHAR(255) NOT NULL,
    chapterId INT,
    FOREIGN KEY (chapterId) REFERENCES chapters(chapterId) ON DELETE SET NULL
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

INSERT INTO chapters (chapter, title, createdBy, createdAt, userId)
VALUES
('Chapter 1', 'The Founding of Cityville', 'Jane Mary', NOW(), 3),
('Chapter 2', 'Expansion of Cityville', 'Admin', NOW(), 1);

INSERT INTO chaptercontents (leftImage, rightImage, content, chapterId)
VALUES
(NULL, NULL, 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Itaque voluptatibus aspernatur ut illo fuga, culpa numquam voluptatum voluptates, ipsum accusantium ratione possimus ex reprehenderit et odit. Libero officia iste velit.', 1),
(NULL, NULL, 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Itaque voluptatibus aspernatur ut illo fuga, culpa numquam voluptatum voluptates, ipsum accusantium ratione possimus ex reprehenderit et odit. Libero officia iste velit.', 1),
(NULL, NULL, 'In 1920, Cityville expanded...', 2);

INSERT INTO credentials (username, password, createdAt, userId)
VALUES 
('ADMIN', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 1),  -- 'pass'
('johnsmith', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 2),  -- 'pass'
('janemary', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 3);  -- 'pass'
