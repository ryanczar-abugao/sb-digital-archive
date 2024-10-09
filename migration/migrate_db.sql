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

CREATE TABLE modules (
    moduleId INT AUTO_INCREMENT PRIMARY KEY,
    selector VARCHAR(50) NOT NULL,
    moduleCode VARCHAR(50) NOT NULL,
    modulePath VARCHAR(50) NOT NULL,
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
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE credentials (
    credentialId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

CREATE TABLE sbmembers (
    memberId INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    middleName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    address VARCHAR(50) NOT NULL,
    position VARCHAR(50) NOT NULL,
    profilePicture VARCHAR(255) NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);

CREATE TABLE ordinances (
    ordinanceId INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    authors VARCHAR(255) NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);


CREATE TABLE history (
    historyId INT AUTO_INCREMENT PRIMARY KEY,
    chapter VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content VARCHAR(255) NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);

INSERT INTO users (firstName, middleName, lastName, gender, position, profilePicture, role, createdAt) 
VALUES 
('Admin', 'Admin', 'Admin', 'male', 'SB Representative', '', 'admin', NOW()),
('John', 'Doe', 'Smith', 'male', 'Member', '', 'member', NOW()),
('Jane', 'Mary', 'Johnson', 'female', 'Secretary', '', 'member', NOW());

INSERT INTO credentials (username, password, createdAt, userId)
VALUES 
('ADMIN', '$2y$10$E8WvOQh1c4b5XjF93D/Ij.pZC4W0jC3P4g38X/6dy2K9YV4zZQn5O', NOW(), 1),  -- 'pass'
('johnsmith', '$2y$10$G8I5tT1q7SuVq0A5GZZlRe.pISHPoUlItdizp6ALM3rvffZjV7ZCa', NOW(), 2),  -- 'johnspassword'
('janemary', '$2y$10$VgFClQLVmbn8mfhb9RJ3xeJHi5pZL2T3IL2Ybe/ElPO0OgGpYEBX2', NOW(), 3);  -- 'janepassword'
