DROP DATABASE IF EXISTS MedicSchedule;

CREATE DATABASE MedicSchedule;

USE MedicSchedule;

CREATE TABLE ROLES(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Name varchar(20)
);


INSERT INTO ROLES(Name)
VALUES ('Admin'), ('Doctor'), ('User');

CREATE TABLE USERS(
	ID int AUTO_INCREMENT PRIMARY KEY,
    Username varchar(20) Unique NOT NULL,
    Password varchar(255) NOT NULL,
    Email varchar(30) Unique NOT NULL,
    Role_ID int NOT NULL,
    FOREIGN KEY (Role_ID) REFERENCES ROLES(ID)
);

INSERT INTO USERS(Username, Password, Email, Role_ID)
VALUES ('Admin', '$2y$10$5rI9yAaGapGfHLCfj6LUWOPKCa2sSSCFK/JzfuBE1FsNtJ8dPtcLy', 'admin@gmail.com', 1), # admin123
	   ('Doctor', '$2y$10$xqrlT0nBHeGLsW9bg.VJN.7246h87cilCjT5QuHRiwZ9gtJBHPm3C', 'doctor@gmail.com', 2), #Doctor123
       ('mohamed123', '$2y$10$hd8y/0j//73UIqMJKLAAw.vYEChet6MDnao.LxXtmvgAGvgRd7g3O', 'mohamed@gmail.com', 3); #12345678

CREATE TABLE PERSONS(
	ID int AUTO_INCREMENT PRIMARY KEY,
    First_Name varchar(50) NOT NULL,
    Last_Name varchar(50) NOT NULL,
    Birth_Date date NOT NULL,
	Gender ENUM('M', 'F') NOT NULL,
    Phone_Number varchar(20) NOT NULL,
    User_ID int NOT NULL,
    FOREIGN KEY (User_ID) REFERENCES USERS(ID)
);

INSERT INTO PERSONS(First_Name, Last_Name, Birth_Date, Gender, Phone_Number, User_ID)
VALUES ('Mammar', 'Darmech', '2004-01-18', 'M', '0561XXXXXX', 1),
	   ('Abd-El-Hadi', 'Gundaz', '2003-10-20', 'M', '0560XXXXXX', 2),
       ('Ahmed', 'Mohamed', '2010-12-31', 'M', '0554XXXXXX', 3);



CREATE TABLE DOCTORS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	User_ID int NOT NULL UNIQUE,
    Join_Date date,
    isRetired date,
    Salary int NOT NULL,
    FOREIGN KEY (User_ID) REFERENCES USERS(ID)
);

INSERT INTO DOCTORS (User_ID, Join_Date, isRetired, Salary)
VALUES (2, date(now()), null, 1000);

INSERT INTO DOCTORS (User_ID, Join_Date, isRetired, Salary)
SELECT U.ID, CURDATE(), NULL, 1000 FROM USERS U 
JOIN ROLES R ON
U.Role_ID = R.ID
WHERE R.ID = 2;

CREATE TABLE PATIENTS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	User_ID int UNIQUE NOT NULL,
    Allergies varchar(30),
    FOREIGN KEY (User_ID) REFERENCES USERS(ID)
);

INSERT INTO PATIENTS(User_ID, Allergies)
SELECT ID, NULL FROM USERS;

CREATE TABLE APT_STATUS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Name varchar(20) UNIQUE NOT NULL,
    Description text NOT NULL
);

INSERT INTO APT_STATUS(Name, Description)
VALUES  ('Active', 'The appointment is scheduled and active.'),
		('Finished', 'The appointment has been completed.'),
		('Cancelled', 'The appointment has been cancelled.');


CREATE TABLE DAYS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Name varchar(10) NOT NULL
);

INSERT INTO DAYS(Name)
VALUES ('Sunday'), ('Monday'), ('Tuseday'), ('Wedensday'), ('Thursaday'), ('Friday'), ('Saturday');

CREATE TABLE TIME(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Time time NOT NULL
);

INSERT INTO TIME(Time)
VALUES('08:00'),('08:30'),('09:00'),('09:30'),('10:00'),('10:30'),('11:00'),('11:30'),('14:00'),('14:30'),('15:00'),('15:30'),('16:00');

SELECT * FROM TIME;

CREATE TABLE APPOINTMENTS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Doctor_ID int NOT NULL,
	Patient_ID int NOT NULL,
    Date date NOT NULL,
    Time_ID int NOT NULL,
    Visit_Reason text NOT NULL,
    Status_ID int NOT NULL,
	FOREIGN KEY (Time_ID)  REFERENCES TIME(ID),
    FOREIGN KEY (Doctor_ID) REFERENCES DOCTORS(ID),
    FOREIGN KEY (Patient_ID) REFERENCES PATIENTS(ID),
    FOREIGN KEY (Status_ID)  REFERENCES APT_STATUS(ID),
    CONSTRAINT unique_appointment_slot UNIQUE (Date, Time_ID)
);

INSERT INTO APPOINTMENTS(Doctor_ID, Patient_ID, Date, Time_ID, Visit_Reason, Status_ID)
VALUES (1,20, '2025-05-1',5, 'Consultation', 3);

CREATE TABLE BILLING(
	ID int AUTO_INCREMENT PRIMARY KEY,
    Appointement_ID int NOT NULL,
	Amount int DEFAULT(0),
    isPaid date,
    FOREIGN KEY (Appointement_ID) REFERENCES  APPOINTMENTS(ID)
);

INSERT INTO BILLING(Appointement_ID, Amount, isPaid)
VALUES (1, 100, NULL);


CREATE TABLE MEDICAL_HISTORY(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Patient_ID int NOT NULL,
    Diagnoisis varchar(50),
	Treatment text,
    FolloUp datetime,
    FOREIGN KEY (Patient_ID) REFERENCES PATIENTS(ID)
);

CREATE TABLE USER_SESSION(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Token varchar(255) NOT NULL UNIQUE,
    User_ID int UNIQUE NOT NULL,
    Expire_Date datetime NOT NULL,
    FOREIGN KEY (User_ID) REFERENCES USERS(ID)
);

INSERT INTO USER_SESSION(Token, User_ID, Expire_Date)
VALUES("$2y$10$41IdZj3gGMZ1Gn5OW4LLGOglcvayhOX8N4jj3rC2yAk2F04nJrhEK", 1, '2024-12-31');



CREATE TABLE WORK_TIME(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Day_ID int NOT NULL,
    Time_ID int NOT NULL,
    FOREIGN KEY (Day_ID) REFERENCES DAYS(ID),
    FOREIGN KEY (Time_ID) REFERENCES TIME(ID)
);

INSERT INTO WORK_TIME(Day_ID, Time_ID)
VALUES  (1,1), (1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11), (1,12),(1,13),
		(2,1), (2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),
		(3,1), (3,2),(3,3),(3,4),(3,5),(3,6),(3,7),(3,8),(3,9),(3,10),(3,11), (3,12),(3,13),
		(4,3),(4,4),(4,5),(4,6),(4,7),(4,8),(4,9),(4,10),(4,11), (4,12),
		(5,1), (5,2),(5,3),(5,4),(5,5),(5,6),(5,7),(5,8);
        
SELECT * FROM DAYS;

SELECT * FROM TIME;

SELECT * FROM WORK_TIME;

SELECT D.Name, T.Time, T.ID AS Time_ID, D.ID AS Day_ID FROM WORK_TIME WT JOIN DAYS D ON D.ID = Day_ID
JOIN TIME T ON T.ID = WT.Time_ID
ORDER BY D.ID ASC, T.Time ASC;

SELECT T.ID, D.ID FROM WORK_TIME WT JOIN DAYS D ON D.ID = Day_ID
JOIN TIME T ON T.ID = WT.Time_ID
ORDER BY D.ID ASC, T.Time ASC;

SELECT * FROM ROLES;

SELECT * FROM Persons;

SELECT * FROM USERS;

SELECT * FROM DOCTORS;

SELECT * FROM PATIENTS;

SELECT * FROM APT_STATUS;

SELECT * FROM APPOINTMENTS;



SELECT Date, Time FROM APPOINTMENTS A JOIN TIME T ON A.Time_ID = T.ID
WHERE Status_ID = 3 and Date = '2026-05-01';

SELECT Time_ID FROM APPOINTMENTS
WHERE Status_ID = 3 and Date = '2026-05-01';

SELECT A.ID , A.Status_ID, CONCAT(P.First_Name, ' ', P.Last_Name) AS Full_Name, A.Visit_Reason, A.Date, S.Description  FROM APPOINTMENTS A JOIN DOCTORS D
ON A.Doctor_ID = D.ID 
JOIN PERSONS P 
ON D.User_ID = P.User_ID JOIN APT_STATUS S 
ON A.Status_ID = S.ID JOIN PATIENTS T
ON T.ID = A.Patient_ID
WHERE  20 = T.ID;

SELECT * FROM BILLING;

SELECT * FROM MEDICAL_HISTORY;

DROP VIEW personal;

CREATE VIEW personal AS
SELECT P.*, TIMESTAMPDIFF(YEAR, Birth_Date, CURDATE()) AS Age, Username, Password, Email, Name as Role
FROM PERSONS P JOIN USERS U ON P.ID = U.ID
JOIN ROLES R ON U.Role_ID = R.ID;

SELECT * from personal;




SELECT * FROM USER_SESSION;



UPDATE USERS
SET Password = '$2y$10$ryVI9dDIrLJXn74lwZRazOwk3goXr1g8KHz8Ag1/XhSJ9g8CXGNu2'
WHERE ID = 1;

DELETE FROM USERS
WHERE ID = 100;

SELECT A.ID , A.Status_ID, CONCAT(P.First_Name, ' ', P.Last_Name) AS Full_Name,
                A.Visit_Reason, A.Date, S.Description
                FROM APPOINTMENTS A JOIN DOCTORS D
                ON A.Doctor_ID = D.ID JOIN PERSONS P 
                ON D.User_ID = P.User_ID JOIN APT_STATUS S 
                ON A.Status_ID = S.ID JOIN PATIENTS T
                ON A.Patient_ID = T.ID WHERE T.ID = 3
                
SELECT A.ID, A.Date, A.Time_ID, A.Visit_Reason, A.Status_ID,  CONCAT(P.First_Name, ' ', P.Last_Name) AS Full_Name
FROM USERS U JOIN PATIENTS T
ON U.ID = T.User_ID JOIN APPOINTMENTS A
ON T.ID = A.Patient_ID JOIN DOCTORS D
ON A.Doctor_ID = D.ID JOIN PERSONS P
ON D.User_ID = P.User_ID;


JOIN PERSONS P
ON U.ID = P.User_ID JOIN APPOINTMENTS A
ON T.ID = A.Patient_ID  JOIN DOCTORS D
ON A.Doctor_ID = D.ID;
