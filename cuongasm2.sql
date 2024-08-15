CREATE DATABASE Attendances;
GO

-- Use Attendance database
USE Attendances;
GO

CREATE TABLE Student (
    StudentID INT PRIMARY KEY NOT NULL,
    NameOfStudent VARCHAR(100) NULL,
    Gender VARCHAR(50) NULL
);
GO

-- Create Courses table
CREATE TABLE Courses (
    CourseID INT PRIMARY KEY NOT NULL,
    TeacherID INT NULL,
    CourseName VARCHAR(50) NULL,
    StartDay DATE NULL,
    EndDay DATE NULL
);
GO

-- Create Class table
CREATE TABLE Class (
    ClassID INT PRIMARY KEY NOT NULL,
    ClassName VARCHAR(50) NULL,
    Schedule DATE NULL
);
GO

-- Create Attendance table
CREATE TABLE Attendance (
    AttendanceID INT PRIMARY KEY NOT NULL,
    StudentID INT NULL, -- Foreign Key
    CourseID INT NULL, -- Foreign Key
    Date DATE NULL,
    Status VARCHAR(10) NULL,
    ClassID INT NULL, -- Foreign Key
    Note VARCHAR(50) NULL,
    CONSTRAINT FK_Attendance_StudentID FOREIGN KEY (StudentID) REFERENCES dbo.Student(StudentID),
    CONSTRAINT FK_Attendance_CourseID FOREIGN KEY (CourseID) REFERENCES dbo.Courses(CourseID),
    CONSTRAINT FK_Attendance_ClassID FOREIGN KEY (ClassID) REFERENCES dbo.Class(ClassID)
);
GO

-- Create Enrollment table
CREATE TABLE Enrollment (
    StudentID INT NOT NULL, -- Foreign Key
    CourseID INT NOT NULL, -- Foreign Key
    CONSTRAINT FK_Enrollment_StudentID FOREIGN KEY (StudentID) REFERENCES dbo.Student(StudentID),
    CONSTRAINT FK_Enrollment_CourseID FOREIGN KEY (CourseID) REFERENCES dbo.Courses(CourseID)
);
GO

-- Create Users table
CREATE TABLE Users (
    UserID INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
    Username VARCHAR(100) NULL,
    Password INT NULL,
    Role VARCHAR(50) NULL,
    Gender VARCHAR(50) NULL,
    Phone INT NULL,
    FirstName VARCHAR(100) NULL,
    LastName VARCHAR(100) NULL,
    DateOfBirth DATE NULL
);