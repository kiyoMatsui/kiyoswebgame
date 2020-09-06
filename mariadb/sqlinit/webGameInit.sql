--  Sample webgame database
--  Copyright (C) 2020
--  birth_date  DATE            NOT NULL DEFAULT '0000-00-00',
--  gender      ENUM ('M','F')  NOT NULL, 

CREATE DATABASE IF NOT EXISTS mainwebgame CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP USER IF EXISTS 'kiyoad'@'adminer.webgame_backend';
CREATE USER 'kiyoad'@'adminer.webgame_backend' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON mainwebgame.* TO 'kiyoad'@'adminer.webgame_backend';

DROP USER IF EXISTS 'kiyo'@'php.webgame_backend';
CREATE USER 'kiyo'@'php.webgame_backend' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON mainwebgame.* TO 'kiyo'@'php.webgame_backend';
USE mainwebgame;

CREATE TABLE IF NOT EXISTS register (
  accountid INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  activation_code varchar(255) NOT NULL,
  PRIMARY KEY (accountid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS accounts (
  accountid INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  loggedin TINYINT(1) DEFAULT 0 NOT NULL,
  PRIMARY KEY (accountid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS deleted_accounts (
  accountid INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  deleted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (accountid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS admins (
  id INT(11) NOT NULL AUTO_INCREMENT,
  accounts__accountid INT(11) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  activated TINYINT(1) DEFAULT 0 NOT NULL,
  PRIMARY KEY (id),
  KEY accountid (accounts__accountid),
  CONSTRAINT accounts_ibfk_1 FOREIGN KEY (accounts__accountid) REFERENCES accounts (accountid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS servers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  server_admin INT(11) NOT NULL,
  server_description TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  playing TINYINT(1),
  players INT(11) DEFAULT 0,
  ip_address VARCHAR(96) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY server_admin (server_admin),
  CONSTRAINT accounts_ibfk_2 FOREIGN KEY (server_admin) REFERENCES accounts (accountid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;