-- STORED PROCEDURE TO ADD ADMINISTRATOR update_admin
-- http://localhost:8080/admin/encrypt/password

CALL update_admin ( 
    'username',
    'Virgilio', 
    'Bro', 
    'Emman', 
    'female', 
    'Vice Mayor', 
    'username', 
    '$2y$10$K3XJzPSMJhJ0MKhOT5sYGeUtWCYXdhR1qf..mktD23vW2b6C5TPzO'
);

DELIMITER $$

CREATE PROCEDURE update_admin (
    IN usernameVar VARCHAR(50),
    IN firstNameVar VARCHAR(50),
    IN middleNameVar VARCHAR(50),
    IN lastNameVar VARCHAR(50),
    IN genderVar VARCHAR(50),
    IN positionVar VARCHAR(50),
    IN newUsernameVar VARCHAR(50),
    IN passVar VARCHAR(255)
)
BEGIN 
    DECLARE userIdVar INT;
    DECLARE credentialIdVar INT;
    
    SELECT  users.userId 
    INTO    userIdVar
    FROM    users
    INNER JOIN credentials 
            ON users.userId = credentials.userId
    WHERE   credentials.username = usernameVar;

    SELECT  credentialId 
    INTO    credentialIdVar
    FROM    credentials
    WHERE   username = usernameVar;

    UPDATE  users
    SET     firstName = firstNameVar
            , middleName = middleNameVar
            , lastName = lastNameVar
            , gender = genderVar
            , position = positionVar
    WHERE   userId = userIdVar;

    UPDATE  credentials
    SET     username = newUsernameVar
            , password = passVar
    WHERE   credentialId = credentialIdVar;

END$$ 