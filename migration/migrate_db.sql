DROP DATABASE IF EXISTS sbarchive;

CREATE DATABASE sbarchive;

USE sbarchive;

CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    middleName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    position VARCHAR(50) NOT NULL,
    fileInput VARCHAR(255) NOT NULL,
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
    fileInput VARCHAR(255) NOT NULL,    
    termStart DATE DEFAULT NULL,
    termEnd DATE DEFAULT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    createdBy VARCHAR(50) NOT NULL,
    updatedBy VARCHAR(50) NULL,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);

CREATE TABLE ordinances (
    ordinanceId INT AUTO_INCREMENT PRIMARY KEY,
    fileInput VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    authors VARCHAR(255) NOT NULL,
    createdBy VARCHAR(50) NOT NULL,
    year INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedBy VARCHAR(50) NULL,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userId INT,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE SET NULL
);


CREATE TABLE histories (
    historyId INT AUTO_INCREMENT PRIMARY KEY,
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
    historyId INT,
    ordinanceId INT,
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (historyId) REFERENCES histories(historyId) ON DELETE CASCADE,
    FOREIGN KEY (ordinanceId) REFERENCES ordinances(ordinanceId) ON DELETE CASCADE
);

CREATE TABLE downloads (
    downloadId INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(80) NOT NULL,
    downloadedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ordinanceId INT,
    FOREIGN KEY (ordinanceId) REFERENCES ordinances(ordinanceId) ON DELETE SET NULL
);

INSERT INTO users (firstName, middleName, lastName, gender, position, fileInput, role, createdAt) 
VALUES 
('Admin', 'Admin', 'Admin', 'male', 'SB Representative', '', 'admin', NOW()),
('John', 'Doe', 'Smith', 'male', 'Member', '', 'member', NOW()),
('Jane', 'Mary', 'Johnson', 'female', 'Secretary', '', 'member', NOW());

INSERT INTO `sbmembers` (`memberId`, `firstName`, `middleName`, `lastName`, `description`, `gender`, `address`, `position`, `fileInput`, `termStart`, `termEnd`, `createdAt`, `createdBy`, `updatedBy`, `updatedAt`, `userId`) VALUES
(1, 'Michael', 'J', 'Johnson', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'male', '456 Oak St, Cityville', 'Treasurer', '/uploads/member_67402d2e26f046.65157700.png', '2019-11-22', '2024-11-22', NOW(), 'Admin', '1', '2024-11-22 07:05:18', 1),
(2, 'Lisa', 'M', 'Brown', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'female', '789 Pine St, Cityville', 'Member', '/uploads/member_67402d37d53d23.99522681.png', '2019-11-22', '2024-11-22', NOW(), 'Admin', '1', '2024-11-22 07:05:27', 2),
(3, 'Sen', 'J', 'Johnson', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'male', '456 Oak St, Cityville', 'Treasurer', '/uploads/member_67402d2e26f046.65157700.png', '2024-11-22', '2029-11-22', NOW(), 'Admin', '1', '2024-11-22 07:05:18', 1),
(4, 'Tuy', 'M', 'Brown', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'female', '789 Pine St, Cityville', 'Member', '/uploads/member_67402d37d53d23.99522681.png', '2024-11-22', '2029-11-22', NOW(), 'Admin', '1', '2024-11-22 07:05:27', 1),
(5, 'Rey', 'J', 'Johnson', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'male', '456 Oak St, Cityville', 'Treasurer', '/uploads/member_67402d2e26f046.65157700.png', '2029-11-22', '2040-11-22', NOW(), 'Admin', '1', '2024-11-22 07:05:18', 1),
(6, 'Mar', 'M', 'Brown', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'female', '789 Pine St, Cityville', 'Member', '/uploads/member_67402d37d53d23.99522681.png', '2029-11-22', '2040-11-22', NOW(), 'Admin', '1', '2024-11-22 07:05:27', 1);

INSERT INTO histories (chapter, title, contents, createdBy, createdAt, userId)
VALUES
('Chapter 1', 'The Founding of Cityville', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'Jane Mary', NOW(), 3),
('Chapter 2', 'Expansion of Cityville', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum saepe, veniam est nemo odit doloremque necessitatibus quos modi eum asperiores.', 'Admin', NOW(), 1);

INSERT INTO credentials (username, password, createdAt, userId)
VALUES 
('ADMIN', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 1),  -- 'pass'
('johnsmith', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 2),  -- 'pass'
('janemary', '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO', NOW(), 3);  -- 'pass'

INSERT INTO `ordinances` (`ordinanceId`, `fileInput`, `title`, `description`, `authors`, `createdBy`, `year`, `createdAt`, `updatedBy`, `updatedAt`, `userId`) VALUES
(1, 'C://xampp/htdocs/sb-digital-archive/public/uploads/ordinance_674009cc252bd9.82274581.pdf', 'Ordinance 2024-001', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolorum quibusdam cupiditate mollitia provident quia sed? A, quae? Dolorem sint laudantium corporis impedit, nulla autem vel, rerum perspiciatis, consequatur sunt praesentium suscipit. At', 'Hon Audie Concina et. al.', '1', 2024, '2024-11-22 04:34:20', '1', '2024-11-22 04:34:20', NULL),
(2, 'C://xampp/htdocs/sb-digital-archive/public/uploads/ordinance_67400e8e3dc3f8.33501519.pdf', 'Ordinance 2023-002', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto dolorum quibusdam cupiditate mollitia provident quia sed? A, quae? Dolorem sint laudantium corporis impedit, nulla autem vel, rerum perspiciatis, consequatur sunt praesentium suscipit. At', 'Hon Audie Concina et. al.', '1', 2023, '2024-11-22 04:54:38', NULL, '2024-11-22 04:54:38', NULL),
(3, 'C://xampp/htdocs/sb-digital-archive/public/uploads/ordinance_67401e524efc52.90617653.pdf', 'Ordinance 2024-0023', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Totam maxime magni obcaecati repudiandae assumenda incidunt! Enim aliquam, autem nobis dolores dicta laboriosam magni necessitatibus deleniti accusamus expedita explicabo quisquam error! Ipsa sapie', 'Hon Audie Concina et. al.', '1', 2024, '2024-11-22 06:01:54', NULL, '2024-11-22 06:01:54', NULL),
(4, 'C://xampp/htdocs/sb-digital-archive/public/uploads/ordinance_67402c7ad13d71.01005895.pdf', 'Ordinance 2022-001', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Totam maxime magni obcaecati repudiandae assumenda incidunt! Enim aliquam, autem nobis dolores dicta laboriosam magni necessitatibus deleniti accusamus expedita explicabo quisquam error! Ipsa sapie', 'Hon Audie Concina et. al.', '1', 2022, '2024-11-22 07:02:18', NULL, '2024-11-22 07:02:18', NULL),
(5, 'C://xampp/htdocs/sb-digital-archive/public/uploads/ordinance_67402ccd374a90.95517618.pdf', 'Ordinance 2023-0015', 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Totam maxime magni obcaecati repudiandae assumenda incidunt! Enim aliquam, autem nobis dolores dicta laboriosam magni necessitatibus deleniti accusamus expedita explicabo quisquam error! Ipsa sapie', 'Hon Audie Concina et. al.', '1', 2023, '2024-11-22 07:03:41', NULL, '2024-11-22 07:03:41', NULL);