CREATE DATABASE IF NOT EXISTS `referat_questions` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `referat_questions`;

CREATE TABLE IF NOT EXISTS students (
  faculty_number VARCHAR(8) NOT NULL PRIMARY KEY,
  student_name VARCHAR(128) NOT NULL,
  faculty  VARCHAR(128),
  major VARCHAR(128),
  course VARCHAR(16),
  group_number VARCHAR(16)
);

CREATE TABLE IF NOT EXISTS tests (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  owner_fn VARCHAR(8) NOT NULL REFERENCES students(faculty_number),
  topic INT NOT NULL,
  test_type VARCHAR(32) NOT NULL,
  results_sum INT NOT NULL,
  times_taken INT NOT NULL
);

CREATE TABLE IF NOT EXISTS admins (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  adminName VARCHAR(128) NOT NULL UNIQUE,
  pass VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS questions (
  question_number INT NOT NULL,
  test_id INT NOT NULL REFERENCES tests(id),
  question_text VARCHAR(512) NOT NULL,
  correct_answer VARCHAR(512) NOT NULL,
  wrong_answer_1 VARCHAR(512) NOT NULL, 
  wrong_answer_2 VARCHAR(512) NOT NULL,
  wrong_answer_3 VARCHAR(512) NOT NULL,
  difficulty INT NOT NULL,
  response_correct VARCHAR(512) NOT NULL,
  response_wrong VARCHAR(512) NOT NULL,
  more_info VARCHAR(1024) NOT NULL,
  PRIMARY KEY(test_id, question_number)
);

CREATE TABLE IF NOT EXISTS testTaken (
  test_id INT NOT NULL REFERENCES tests(id),
  taker_fn VARCHAR(8) NOT NULL REFERENCES students(faculty_number),
  result INT NOT NULL,
  PRIMARY KEY(test_id, taker_fn)
);

CREATE TABLE IF NOT EXISTS topicRating (
  owner_fn VARCHAR(8) NOT NULL REFERENCES students(faculty_number),
  topic INT NOT NULL,
  topic_name VARCHAR(512) NOT NULL,
  rating INT NOT NULL,
  PRIMARY KEY(owner_fn)
);

CREATE TABLE IF NOT EXISTS presence (
  fn VARCHAR(8) NOT NULL REFERENCES students(faculty_number),
  topic INT NOT NULL,
  present VARCHAR(3) NOT NULL,
  PRIMARY KEY(fn, topic)
);