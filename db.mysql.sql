DROP DATABASE rams;
CREATE DATABASE rams
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

USE rams;

CREATE TABLE ci_sessions (
  id varchar(40) NOT NULL,
  ip_address varchar(45) NOT NULL,
  timestamp int(10) UNSIGNED NOT NULL DEFAULT 0,
  data blob NOT NULL,
  PRIMARY KEY (id),
  INDEX ci_sessions_timestamp (timestamp)
)
ENGINE = INNODB
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

CREATE TABLE lessons_log (
  id int(11) NOT NULL AUTO_INCREMENT,
  lesson_id int(11) DEFAULT NULL,
  user_id int(11) DEFAULT NULL,
  type tinyint(4) NOT NULL DEFAULT 1 COMMENT '0 - системный, 1 - пользовательский, 2 - лог',
  comment mediumtext DEFAULT NULL,
  data mediumtext DEFAULT NULL,
  last_update timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  login varchar(255) NOT NULL DEFAULT '',
  password varchar(32) NOT NULL DEFAULT '',
  email varchar(255) DEFAULT NULL,
  name varchar(255) NOT NULL,
  address varchar(255) DEFAULT NULL,
  phones varchar(255) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  parent_id int(11) NOT NULL DEFAULT 2,
  is_group tinyint(4) NOT NULL DEFAULT 0,
  uniqueid varchar(32) DEFAULT NULL,
  status tinyint(4) NOT NULL DEFAULT 1,
  data text DEFAULT NULL,
  last_update timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  skype varchar(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 6
AVG_ROW_LENGTH = 277
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE clients (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  name varchar(50) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  address varchar(255) DEFAULT NULL,
  data text DEFAULT NULL,
  phones varchar(255) DEFAULT NULL,
  email varchar(50) DEFAULT NULL,
  login varchar(255) DEFAULT NULL,
  skype varchar(255) DEFAULT NULL,
  status tinyint(4) NOT NULL DEFAULT 1,
  parent_id int(11) NOT NULL DEFAULT 0,
  external_id int(11) DEFAULT NULL,
  create_date datetime DEFAULT NULL,
  place tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - дистанционно, 1 - у ученика, 2 - у преподавателя, 3 - в офисе',
  PRIMARY KEY (id),
  CONSTRAINT FK_clients_users_id FOREIGN KEY (user_id)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 153
AVG_ROW_LENGTH = 744
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

CREATE TABLE lessons (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  client_id int(11) NOT NULL,
  place tinyint(4) NOT NULL DEFAULT 0,
  start_date datetime DEFAULT NULL,
  duration int(11) DEFAULT NULL,
  cost int(11) DEFAULT NULL,
  status tinyint(4) NOT NULL DEFAULT 0,
  data mediumtext DEFAULT NULL,
  last_update timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT FK_lessons_clients_id FOREIGN KEY (client_id)
  REFERENCES clients (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_lessons_users_id FOREIGN KEY (user_id)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 2439
AVG_ROW_LENGTH = 144
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE TABLE finances (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL DEFAULT 0,
  type tinyint(4) DEFAULT 1 COMMENT '1 - занятие, 2 - оплата заказа, 3 - транспорт, 4 - материалы, 5 - работы, 6 - накладные расходы',
  math tinyint(4) NOT NULL DEFAULT -1,
  amount int(11) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  data text DEFAULT NULL,
  client_id int(11) DEFAULT NULL,
  lesson_id int(11) DEFAULT NULL,
  payment_date datetime DEFAULT NULL,
  last_update timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT FK_finances_clients_id FOREIGN KEY (client_id)
  REFERENCES clients (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT FK_finances_lessons_id FOREIGN KEY (lesson_id)
  REFERENCES lessons (id) ON DELETE SET NULL ON UPDATE RESTRICT
)
ENGINE = INNODB
AUTO_INCREMENT = 2416
AVG_ROW_LENGTH = 96
CHARACTER SET utf8
COLLATE utf8_general_ci;

CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW monthly_finance
AS
SELECT
  year(`f`.`payment_date`) AS `year`,
  month(`f`.`payment_date`) AS `month`,
  SUM((`f`.`amount` * `f`.`math`)) AS `SUM(f.amount * f.math)`
FROM `finances` `f`
GROUP BY year(`f`.`payment_date`),
         month(`f`.`payment_date`)
ORDER BY year(`f`.`payment_date`) DESC, month(`f`.`payment_date`) DESC;

CREATE OR REPLACE
DEFINER = 'root'@'localhost'
VIEW weekly_finance
AS
SELECT
  year(`f`.`payment_date`) AS `year`,
  week(`f`.`payment_date`, 0) AS `week`,
  SUM((`f`.`amount` * `f`.`math`)) AS `SUM(f.amount * f.math)`
FROM `finances` `f`
GROUP BY year(`f`.`payment_date`),
         week(`f`.`payment_date`, 0)
ORDER BY year(`f`.`payment_date`) DESC, week(`f`.`payment_date`, 0) DESC;

INSERT INTO users(id, login, password, email, name, address, phones, description, parent_id, is_group, uniqueid, status, data, last_update, skype) VALUES
(1, '', '', NULL, 'Сотрудники', NULL, NULL, NULL, 0, 1, NULL, 1, NULL, '2014-01-09 12:11:40', NULL);
INSERT INTO users(id, login, password, email, name, address, phones, description, parent_id, is_group, uniqueid, status, data, last_update, skype) VALUES
(2, '', '', '', 'Клиенты', '', '', '', 0, 1, NULL, 1, '{"delivery_id":"0"}', '2014-10-21 14:53:20', NULL);
INSERT INTO users(id, login, password, email, name, address, phones, description, parent_id, is_group, uniqueid, status, data, last_update, skype) VALUES
(3, 'guest', '084e0343a0486ff05530df6c705c8bb4', NULL, 'Демов Демьян Демович', NULL, NULL, 'Демо-пользователь', 1, 0, NULL, 1, '{"filters":{"lesson":{"date_from":"2016-09-12","date_to":"2016-09-19"}}}', '2016-09-25 19:23:35', NULL);