CREATE DATABASE ariko_test;

CREATE USER 'ariko_test'@'%';

GRANT ALL PRIVILEGES ON `ariko_test`.* TO 'ariko_test'@'%';
ALTER USER 'ariko_test'@'%';