PRAGMA foreign_keys=ON;
.header ON
.mode columns

DROP TABLE IF EXISTS Club;
CREATE TABLE Club (
    idClub INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    UNIQUE(idClub)
);

DROP TABLE IF EXISTS Discipline;
CREATE TABLE Discipline (
    idDiscipline INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    UNIQUE(idDiscipline)
);

DROP TABLE IF EXISTS Apparatus;
CREATE TABLE Apparatus (
    idApparatus INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    idDiscipline INT,
    FOREIGN KEY (idDiscipline) REFERENCES Discipline(idDiscipline),
    UNIQUE(idApparatus)
);

DROP TABLE IF EXISTS CoachingStaff;
CREATE TABLE CoachingStaff (
    idCoachingStaff INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    genre VARCHAR(1) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    function VARCHAR(255) NOT NULL,
    UNIQUE(idCoachingStaff),
    UNIQUE(mobile),
    UNIQUE(email),
    CHECK(email LIKE '%@%')
);

DROP TABLE IF EXISTS Athlete;
CREATE TABLE Athlete (
    idAthlete INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    birthday DATE NOT NULL,
    genre VARCHAR(1) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    ageGroup VARCHAR(255) NOT NULL,
    UNIQUE(idAthlete),
    UNIQUE(mobile),
    UNIQUE(email),
    CHECK(email LIKE '%@%')
);

DROP TABLE IF EXISTS ConditionTest;
CREATE TABLE ConditionTest (
    idConditionTest INTEGER PRIMARY KEY AUTOINCREMENT,
    idAthlete INT,
    idCoachingStaff INT,
    weight FLOAT NOT NULL,
    height FLOAT NOT NULL,
    backFlexibility FLOAT NOT NULL,
    verticalThrust FLOAT NOT NULL,
    dateTest DATE NOT NULL,
    FOREIGN KEY (idAthlete) REFERENCES Athlete(idAthlete),
    FOREIGN KEY (idCoachingStaff) REFERENCES CoachingStaff(idCoachingStaff),
    UNIQUE(idConditionTest),
    CHECK(weight > 0),
    CHECK(height > 0),
    CHECK(backFlexibility > 0),
    CHECK(verticalThrust > 0),
    CHECK(dateTest LIKE '%-%-%')
);

DROP TABLE IF EXISTS TrainingReg;
CREATE TABLE TrainingReg (
    idTrainingReg INTEGER PRIMARY KEY AUTOINCREMENT,
    idCoachingStaff INT,
    idAthlete INT,
    performance FLOAT NOT NULL,
    dateTrainingReg DATE NOT NULL,
    FOREIGN KEY (idCoachingStaff) REFERENCES CoachingStaff(idCoachingStaff),
    FOREIGN KEY (idAthlete) REFERENCES Athlete(idAthlete),
    UNIQUE(idTrainingReg),
    CHECK(performance >= 0 AND performance <= 10),
    CHECK(dateTrainingReg LIKE '%-%-%')
);

DROP TABLE IF EXISTS Notes;
CREATE TABLE Notes (
    idCoachingStaff INT,
    idTrainingReg INT,
    description TEXT NOT NULL,
    FOREIGN KEY (idCoachingStaff) REFERENCES CoachingStaff(idCoachingStaff),
    FOREIGN KEY (idTrainingReg) REFERENCES TrainingReg(idTrainingReg),
    PRIMARY KEY (idCoachingStaff, idTrainingReg)
);

DROP TABLE IF EXISTS Competition;
CREATE TABLE Competition (
    idCompetition INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    local VARCHAR(255) NOT NULL,
    startTime DATETIME NOT NULL,
    endTime DATETIME NOT NULL,
    description TEXT NOT NULL,
    UNIQUE(idCompetition)
);

DROP TABLE IF EXISTS Result;
CREATE TABLE Result (
    idCompetition INT,
    idAthlete INT,
    idApparatus INT,
    place INT NOT NULL,
    score FLOAT NOT NULL,
    FOREIGN KEY (idCompetition) REFERENCES Competition(idCompetition),
    FOREIGN KEY (idAthlete) REFERENCES Athlete(idAthlete),
    FOREIGN KEY (idApparatus) REFERENCES Apparatus(idApparatus),
    PRIMARY KEY (idCompetition, idAthlete, idApparatus)
);

DROP TABLE IF EXISTS AthleteDiscipline;
CREATE TABLE AthleteDiscipline (
    idAthlete INT,
    idDiscipline INT,
    PRIMARY KEY (idAthlete, idDiscipline),
    FOREIGN KEY (idAthlete) REFERENCES Athlete(idAthlete),
    FOREIGN KEY (idDiscipline) REFERENCES Discipline(idDiscipline)
);

DROP TABLE IF EXISTS CoachingStaffDiscipline;
CREATE TABLE CoachingStaffDiscipline (
    idCoachingStaff INT,
    idDiscipline INT,
    PRIMARY KEY (idCoachingStaff, idDiscipline),
    FOREIGN KEY (idCoachingStaff) REFERENCES CoachingStaff(idCoachingStaff),
    FOREIGN KEY (idDiscipline) REFERENCES Discipline(idDiscipline)
);

-- Insert data into the tables
INSERT INTO Club (idClub, name, address)
VALUES (1, 'Associação Académica de Espinho ', 'Praçeta Arquiteto Jerónimo Reis, 3800-125 Espinho');

INSERT INTO Discipline (idDiscipline, name, type)
VALUES (1, 'Ginástica Acrobática', 'Exibição'),
       (2, 'Trampolins', 'Formação'),
       (3, 'Trampolins', 'Competição'),
       (4, 'Ginástica Rítmica', 'Competição'),
       (5, 'Ginástica Rítmica', 'Exibição'),
       (6, 'Ginástica Acrobática', 'Competição'),
       (7, 'Ginástica Rítmica', 'Formação');

INSERT INTO Apparatus (idApparatus, name, idDiscipline)
VALUES (1, 'Trampolim Individual', 2),
       (2, 'Duplo Mini Trampolim', 2),
       (3, 'Trampolim Sincronizado', 2),
       (4, 'Tumbling', 2);

INSERT INTO CoachingStaff (idCoachingStaff, name, birthday, genre, mobile, email, password, address, function)
VALUES (1, 'Afonso Mota', '2001-06-19', 'M', '912345678', 'afonso@email.com', '123456', 'Rua do Afonso', 'Treinador'),
       (2, 'Eugénia Mota', '2004-10-05', 'F', '912345679', 'eugenia@email.com', '123456', 'Rua da Eugénia', 'Treinador');

INSERT INTO Athlete (
    idAthlete, name, birthday, genre, mobile, email, password, address, ageGroup)
VALUES (1, 'Santiago Ramos', '2005-06-19', 'M', '912345670', 'santiago@email.com', '12234567', 'Rua do Santiago', 'Juvenil'),
       (2, 'Mariana Silva', '2006-10-05', 'F', '912345671', 'mariana@email.com', '12234567', 'Rua da Mariana', 'Sénior');

INSERT INTO TrainingReg (idTrainingReg, idCoachingStaff, idAthlete, performance, dateTrainingReg) 
VALUES (1, 1, 1, 10, '2024-03-27'), 
       (2, 2, 2, 9, '2024-03-27'),
       (3, 1, 2, 8, '2024-03-27'),
       (4, 2, 1, 7, '2024-03-27');

INSERT INTO Competition (
    idCompetition,
    name,
    local,
    startTime,
    endTime,
    description
)
VALUES (
    1,
    'Oeiras Trampoline Cup',
    'Oeiras',
    '2024-03-23 09:00:00',
    '2024-03-23 18:00:00',
    'Competição de trampolins'
);

INSERT INTO Result (idCompetition, idAthlete, idApparatus, place, score) 
VALUES (1, 1, 2, 2, 42.5);

-- Correct foreign key values for the second result entry
-- Adjust idCompetition to match the existing competition
INSERT INTO Result (idCompetition, idAthlete, idApparatus, place, score) 
VALUES (1, 2, 1, 12, 75.45);

INSERT INTO Notes (idCoachingStaff, idTrainingReg, description)
VALUES (2, 1, 'Não fez preparação física');

-- -- Registo de treino do atleta com id 1 e respetivas notas
-- SELECT TrainingReg.*, Notes.description
-- FROM TrainingReg
-- LEFT JOIN Notes ON TrainingReg.idTrainingReg = Notes.idTrainingReg
-- WHERE TrainingReg.idAthlete = 1;

INSERT INTO CoachingStaffDiscipline (idCoachingStaff, idDiscipline)
VALUES (1, 3);

.save project.db
