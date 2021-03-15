DROP TABLE IF EXISTS ci_sessions;

CREATE TABLE ci_sessions (
  id varchar(40) NOT NULL,
  ip_address varchar(45) NOT NULL,
  timestamp timestamp NOT NULL DEFAULT 0,
  data blob NOT NULL,
  PRIMARY KEY (id)  
);

CREATE INDEX IF NOT EXISTS session_timestamp_index ON ci_sessions(timestamp);

DROP TABLE IF EXISTS lessons_log;

CREATE TABLE lessons_log (
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  lesson_id INTEGER DEFAULT NULL,
  user_id INTEGER DEFAULT NULL,
  type tinyint(4) NOT NULL DEFAULT 1, -- COMMENT '0 - системный, 1 - пользовательский, 2 - лог' 
  comment mediumtext DEFAULT NULL,
  data mediumtext DEFAULT NULL,
  last_update timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER IF NOT EXISTS lessons_log_last_update UPDATE OF comment, data ON lessons_log
BEGIN
  UPDATE lessons_log SET last_update=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;

CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  login varchar(255) NOT NULL DEFAULT '',
  password varchar(32) NOT NULL DEFAULT '',
  email varchar(255) DEFAULT NULL,
  name varchar(255) NOT NULL DEFAULT '',
  address varchar(255) DEFAULT NULL,
  phones varchar(255) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  parent_id INTEGER NOT NULL DEFAULT 2,
  is_group tinyint(4) NOT NULL DEFAULT 0,
  uniqueid varchar(32) DEFAULT NULL,
  status tinyint(4) NOT NULL DEFAULT 1,
  data text DEFAULT NULL,
  last_update timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  skype varchar(255) DEFAULT NULL
);

CREATE TRIGGER IF NOT EXISTS users_last_update UPDATE OF email, phones ON users
BEGIN
  UPDATE users SET last_update=CURRENT_TIMESTAMP WHERE id=OLD.id;
END;

CREATE TABLE IF NOT EXISTS clients (
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  user_id INTEGER NOT NULL,
  name varchar(50) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  address varchar(255) DEFAULT NULL,
  data text DEFAULT NULL,
  phones varchar(255) DEFAULT NULL,
  email varchar(50) DEFAULT NULL,
  login varchar(255) DEFAULT NULL,
  skype varchar(255) DEFAULT NULL,
  status tinyint(4) NOT NULL DEFAULT 1,
  parent_id INTEGER NOT NULL DEFAULT 0,
  external_id INTEGER DEFAULT NULL,
  create_date datetime DEFAULT NULL,
  place tinyint(4) NOT NULL DEFAULT 0, -- COMMENT '0 - дистанционно, 1 - у ученика, 2 - у преподавателя, 3 - в офисе',
  CONSTRAINT FK_clients_users_id FOREIGN KEY (user_id)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS lessons (
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  user_id INTEGER NOT NULL,
  client_id INTEGER NOT NULL,
  place tinyint(4) NOT NULL DEFAULT 0,
  start_date datetime DEFAULT NULL,
  duration INTEGER DEFAULT NULL,
  cost INTEGER DEFAULT NULL,
  status tinyint(4) NOT NULL DEFAULT 0,
  data mediumtext DEFAULT NULL,
  last_update timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT FK_lessons_clients_id FOREIGN KEY (client_id)
  REFERENCES clients (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT FK_lessons_users_id FOREIGN KEY (user_id)
  REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS finances (
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  user_id INTEGER NOT NULL DEFAULT 0,
  type tinyint(4) DEFAULT 1, -- COMMENT '1 - занятие, 2 - оплата заказа, 3 - транспорт, 4 - материалы, 5 - работы, 6 - накладные расходы'
  math tinyint(4) NOT NULL DEFAULT -1,
  amount INTEGER DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  data text DEFAULT NULL,
  client_id INTEGER DEFAULT NULL,
  lesson_id INTEGER DEFAULT NULL,
  payment_date datetime DEFAULT NULL,
  last_update timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT FK_finances_clients_id FOREIGN KEY (client_id)
  REFERENCES clients (id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT FK_finances_lessons_id FOREIGN KEY (lesson_id)
  REFERENCES lessons (id) ON DELETE SET NULL ON UPDATE RESTRICT
);

CREATE
VIEW IF NOT EXISTS monthly_finance
AS
SELECT
strftime('%Y', `f`.`payment_date`) AS `year`,
strftime('%m', `f`.`payment_date`) AS `month`,
SUM((CASE `f`.`math` WHEN 1 THEN `f`.`amount` ELSE 0 END)) AS `Profit`,
SUM((CASE `f`.`math` WHEN -(1) THEN -(`f`.`amount`) ELSE 0 END)) AS `Loss`,
SUM((`f`.`amount` * `f`.`math`)) AS `Total Profit`FROM `finances` `f` JOIN lessons l ON f.lesson_id = l.id
WHERE l.start_date <= CURRENT_TIMESTAMP
GROUP BY strftime('%Y', `f`.`payment_date`), strftime('%m',`f`.`payment_date`)
ORDER BY strftime('%Y', `f`.`payment_date`) DESC, strftime('%m', `f`.`payment_date`) DESC

CREATE
VIEW IF NOT EXISTS weekly_finance
AS
SELECT
strftime('%Y', `f`.`payment_date`) AS `year`,
strftime('%W', `f`.`payment_date`) AS `week`,
SUM((CASE `f`.`math` WHEN 1 THEN `f`.`amount` ELSE 0 END)) AS `Profit`,
SUM((CASE `f`.`math` WHEN -(1) THEN -(`f`.`amount`) ELSE 0 END)) AS `Loss`,
SUM((`f`.`amount` * `f`.`math`)) AS `Total Profit`FROM `finances` `f` JOIN lessons l ON f.lesson_id = l.id
WHERE l.start_date <= CURRENT_TIMESTAMP
GROUP BY strftime('%Y', `f`.`payment_date`), strftime('%W',`f`.`payment_date`)
ORDER BY strftime('%Y', `f`.`payment_date`) DESC, strftime('%W', `f`.`payment_date`) DESC

INSERT INTO users(id, login, password, email, name, address, phones, description, parent_id, is_group, uniqueid, status, data, last_update, skype) VALUES
(1, '', '', NULL, 'Сотрудники', NULL, NULL, NULL, 0, 1, NULL, 1, NULL, '2014-01-09 12:11:40', NULL);

INSERT INTO users(id, login, password, email, name, address, phones, description, parent_id, is_group, uniqueid, status, data, last_update, skype) VALUES
(2, '', '', '', 'Клиенты', '', '', '', 0, 1, NULL, 1, '{"delivery_id":"0"}', '2014-10-21 14:53:20', NULL);

INSERT INTO users(id, login, password, email, name, address, phones, description, parent_id, is_group, uniqueid, status, data, last_update, skype) VALUES
(3, 'guest', '084e0343a0486ff05530df6c705c8bb4', NULL, 'Демов Демьян Демович', NULL, NULL, 'Демо-пользователь', 1, 0, NULL, 1, '{"filters":{"lesson":{"date_from":"2016-09-12","date_to":"2016-09-19"}}}', '2016-09-25 19:23:35', NULL);