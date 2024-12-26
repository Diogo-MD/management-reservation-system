---------------------------------------
-- Criação banco de dados
---------------------------------------
CREATE DATABASE sistema_reserva;
USE sistema_reserva;

---------------------------------------
-- Criação das tabelas
---------------------------------------

CREATE TABLE IF NOT EXISTS evento (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(200) NOT NULL,
sigla VARCHAR(30) NOT NULL UNIQUE,
oferta VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS tipo (
id INT AUTO_INCREMENT PRIMARY KEY,
tipo_sala VARCHAR(150) NOT NULL
);

CREATE TABLE IF NOT EXISTS sala (
id INT AUTO_INCREMENT PRIMARY KEY,
numero VARCHAR(10) NOT NULL,
capacidade INT NOT NULL,
andar INT NOT NULL,
tipo_ID INT,
FOREIGN KEY (tipo_ID) REFERENCES tipo(id)
);

CREATE TABLE IF NOT EXISTS reserva (
id INT AUTO_INCREMENT PRIMARY KEY,
docente VARCHAR(100) DEFAULT NULL,
data_inicio DATE NOT NULL,
data_fim DATE NOT NULL,
horario_inicio TIME NOT NULL,
horario_fim TIME NOT NULL,
dias_semana VARCHAR(30) NOT NULL,
evento_ID INT,
sala_ID INT,
FOREIGN KEY (evento_ID) REFERENCES evento(id),
FOREIGN KEY (sala_ID) REFERENCES sala(id)
);

CREATE TABLE IF NOT EXISTS usuario (
id INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(100) NOT NULL,
senha VARCHAR(100), NOT NULL,
email VARCHAR(100) UNIQUE,
token VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS acesso_salas (
id INT AUTO_INCREMENT PRIMARY KEY,
data_check DATE NOT NULL,
checado boolean NOT NULL,
id_reserva INT NOT NULL
);

ALTER TABLE usuario
	ADD COLUMN roles ENUM("Admin", "Didático", "Gestor");

ALTER TABLE usuario
	ADD COLUMN created_at DATETIME;

ALTER TABLE usuario
	ADD COLUMN updated_at DATETIME;

-- Inserir o tipo de sala
INSERT INTO tipo (tipo_sala)
VALUES ('Sala de Aula'),
    ('Biblioteca'),
    ('Laboratório de Artes'),
    ('Laboratório de Informática'),
    ('Laboratório de Redes'),
    ('Laboratório Makers'),
    ('Sala de Conferência'),
    ('Sala de Reunião'),
    ('Auditório'),
    ('Sala de Música');
-- Inserir salas
INSERT INTO sala (numero, capacidade, andar, tipo_ID)
VALUES ('Sala 1', FLOOR(10 + RAND() * 90), 1, 1),
    ('Sala 2', FLOOR(10 + RAND() * 90), 1, 1),
    ('Sala 3', FLOOR(10 + RAND() * 90), 1, 1),
    ('Sala 4', FLOOR(10 + RAND() * 90), 1, 1),
    ('Sala 5', FLOOR(10 + RAND() * 90), 1, 2),
    ('Sala 6', FLOOR(10 + RAND() * 90), 1, 2),
    ('Sala 7', FLOOR(10 + RAND() * 90), 1, 3),
    ('Sala 8', FLOOR(10 + RAND() * 90), 1, 3),
    ('Sala 9', FLOOR(10 + RAND() * 90), 1, 4),
    ('Sala 10', FLOOR(10 + RAND() * 90), 1, 4),
    ('Sala 11', FLOOR(10 + RAND() * 90), 2, 5),
    ('Sala 12', FLOOR(10 + RAND() * 90), 2, 5),
    ('Sala 13', FLOOR(10 + RAND() * 90), 2, 6),
    ('Sala 14', FLOOR(10 + RAND() * 90), 2, 6),
    ('Sala 15', FLOOR(10 + RAND() * 90), 2, 7),
    ('Sala 16', FLOOR(10 + RAND() * 90), 2, 7),
    ('Sala 17', FLOOR(10 + RAND() * 90), 2, 8),
    ('Sala 18', FLOOR(10 + RAND() * 90), 2, 8),
    ('Sala 19', FLOOR(10 + RAND() * 90), 2, 9),
    ('Sala 20', FLOOR(10 + RAND() * 90), 2, 9),
    ('Sala 21', FLOOR(10 + RAND() * 90), 3, 10),
    ('Sala 22', FLOOR(10 + RAND() * 90), 3, 10),
    ('Sala 23', FLOOR(10 + RAND() * 90), 3, 1),
    ('Sala 24', FLOOR(10 + RAND() * 90), 3, 1),
    ('Sala 25', FLOOR(10 + RAND() * 90), 3, 2),
    ('Sala 26', FLOOR(10 + RAND() * 90), 3, 2),
    ('Sala 27', FLOOR(10 + RAND() * 90), 3, 3),
    ('Sala 28', FLOOR(10 + RAND() * 90), 3, 3),
    ('Sala 29', FLOOR(10 + RAND() * 90), 3, 4),
    ('Sala 30', FLOOR(10 + RAND() * 90), 3, 4),
    ('Sala 31', FLOOR(10 + RAND() * 90), 4, 5),
    ('Sala 32', FLOOR(10 + RAND() * 90), 4, 5),
    ('Sala 33', FLOOR(10 + RAND() * 90), 4, 6),
    ('Sala 34', FLOOR(10 + RAND() * 90), 4, 6),
    ('Sala 35', FLOOR(10 + RAND() * 90), 4, 7),
    ('Sala 36', FLOOR(10 + RAND() * 90), 4, 7),
    ('Sala 37', FLOOR(10 + RAND() * 90), 4, 8),
    ('Sala 38', FLOOR(10 + RAND() * 90), 4, 8),
    ('Sala 39', FLOOR(10 + RAND() * 90), 4, 9),
    ('Sala 40', FLOOR(10 + RAND() * 90), 4, 9),
    ('Sala 41', FLOOR(10 + RAND() * 90), 5, 10),
    ('Sala 42', FLOOR(10 + RAND() * 90), 5, 10),
    ('Sala 43', FLOOR(10 + RAND() * 90), 5, 1),
    ('Sala 44', FLOOR(10 + RAND() * 90), 5, 1),
    ('Sala 45', FLOOR(10 + RAND() * 90), 5, 2),
    ('Sala 46', FLOOR(10 + RAND() * 90), 5, 2),
    ('Sala 47', FLOOR(10 + RAND() * 90), 5, 3),
    ('Sala 48', FLOOR(10 + RAND() * 90), 5, 3),
    ('Sala 49', FLOOR(10 + RAND() * 90), 5, 4),
    ('Sala 50', FLOOR(10 + RAND() * 90), 5, 4);
