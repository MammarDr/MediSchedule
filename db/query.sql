
CREATE TABLE ROLES(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Name varchar(20)
);

INSERT INTO ROLES(Name)
VALUES ('Admin'), ('Doctor'), ('User');

SELECT * FROM ROLES;

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

SELECT * FROM USERS;

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

SELECT * FROM PERSONS;

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

SELECT * FROM DOCTORS;

CREATE TABLE PATIENTS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	User_ID int UNIQUE NOT NULL,
    Allergies varchar(30),
    FOREIGN KEY (User_ID) REFERENCES USERS(ID)
);

INSERT INTO PATIENTS(User_ID, Allergies)
SELECT ID, NULL FROM USERS;

SELECT * FROM PATIENTS;

CREATE TABLE APT_STATUS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Name varchar(20) UNIQUE NOT NULL,
    Description text NOT NULL
);

INSERT INTO APT_STATUS(Name, Description)
VALUES  ('Active', 'The appointment is scheduled and active.'),
		('Finished', 'The appointment has been completed.'),
		('Cancelled', 'The appointment has been cancelled.');
        
SELECT * FROM APT_STATUS;

CREATE TABLE DAYS(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Name varchar(10) NOT NULL
);

INSERT INTO DAYS(Name)
VALUES ('Sunday'), ('Monday'), ('Tuseday'), ('Wedensday'), ('Thursaday'), ('Friday'), ('Saturday');

SELECT * FROM DAYS;

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

#INSERT INTO APPOINTMENTS(Doctor_ID, Patient_ID, Date, Time_ID, Visit_Reason, Status_ID)
#VALUES  (2,1, '2025-05-03',1, 'Consultation', 1);

SELECT * FROM APPOINTMENTS;

CREATE TABLE BILLING(
	ID int AUTO_INCREMENT PRIMARY KEY,
    Appointment_ID int NOT NULL,
	Amount int DEFAULT(1500),
    isPaid date,
    FOREIGN KEY (Appointment_ID) REFERENCES  APPOINTMENTS(ID)
);

#INSERT INTO BILLING(Appointement_ID, Amount, isPaid)
#VALUES (1, 100, NULL);

SELECT * FROM BILLING;

DELETE FROM BILLING WHERE Appointment_ID = 2;

CREATE TABLE MEDICAL_HISTORY(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Patient_ID int NOT NULL,
    Diagnoisis varchar(50),
	Treatment text,
    FolloUp datetime,
    FOREIGN KEY (Patient_ID) REFERENCES PATIENTS(ID)
);

SELECT * FROM MEDICAL_HISTORY;

CREATE TABLE USER_SESSION(
	ID int AUTO_INCREMENT PRIMARY KEY,
	Token varchar(255) NOT NULL UNIQUE,
    User_ID int UNIQUE NOT NULL,
    Expire_Date datetime NOT NULL,
    FOREIGN KEY (User_ID) REFERENCES USERS(ID)
);

SELECT * FROM USER_SESSION;