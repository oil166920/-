CREATE DATABASE attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE attendance;

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subject_name VARCHAR(100) NOT NULL
);

CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  status VARCHAR(10) NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  CONSTRAINT fk_attendance_student FOREIGN KEY (student_id) REFERENCES students(id),
  CONSTRAINT fk_attendance_subject FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

INSERT INTO students(name) VALUES ('นักเรียนทดสอบ');
INSERT INTO subjects(subject_name) VALUES ('คณิตศาสตร์');
