-- STORED PROCEDURE TO ADD ADMINISTRATOR update_admin
-- http://localhost:8080/admin/encrypt/password

CALL delete_admin ( 
    'username'
);

DELIMITER $$

CREATE PROCEDURE delete_admin (
    IN usernameVar VARCHAR(50)
)
BEGIN 
    DECLARE userIdVar INT;
    
    SELECT  users.userId
    INTO    userIdVar
    FROM    users
    INNER JOIN credentials 
            ON users.userId = credentials.userId
    WHERE   credentials.username = usernameVar;

    DELETE  FROM users
    WHERE   userId = userIdVar;

END$$ 