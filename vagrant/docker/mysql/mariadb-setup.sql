UPDATE mysql.user SET password = password('root') WHERE user = 'root';
CREATE USER docker;
GRANT ALL PRIVILEGES ON *.* To 'docker' IDENTIFIED BY 'docker';
flush privileges;
CREATE DATABASE pon;