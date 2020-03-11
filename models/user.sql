DROP USER IF EXISTS 'bienvenido'@'localhost';

CREATE USER 'bienvenido'@'localhost' IDENTIFIED BY '12345';

GRANT SELECT, INSERT, UPDATE,DELETE  ON banco.* TO 'bienvenido'@'localhost';