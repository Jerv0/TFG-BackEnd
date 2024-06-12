-- Creación de la base de datos
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

DROP DATABASE tfg;

CREATE DATABASE tfg CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

USE tfg;

CREATE TABLE
    usuario (
        id_usuario VARCHAR(255) NOT NULL,
        username VARCHAR(20) NOT NULL UNIQUE,
        pass VARCHAR(255) NOT NULL,
        email VARCHAR(40) NOT NULL UNIQUE,
        dni VARCHAR(10) NOT NULL UNIQUE,
        nombre VARCHAR(30) NOT NULL,
        apellido VARCHAR(30) NOT NULL,
        token VARCHAR(30) NOT NULL,
        dir VARCHAR(40) NOT NULL,
        tel INT (9) NOT NULL,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        usertype VARCHAR(20) NOT NULL CHECK (usertype IN ('paciente', 'supervisor', 'admin')),
        PRIMARY KEY (id_usuario)
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    supervisor (
        id_usuario VARCHAR(255) NOT NULL,
        disponibilidad VARCHAR(40) NOT NULL,
        titulacion VARCHAR(20) NOT NULL,
        salario INT (20) NOT NULL,
        activado INT (1) NOT NULL DEFAULT 0 CHECK (activado IN (0, 1)),
        PRIMARY KEY (id_usuario),
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    paciente (
        id_usuario VARCHAR(255) NOT NULL,
        contact_emerg INT (9) NOT NULL,
        especialidad_requerida VARCHAR(50) NOT NULL,
        medicamentos VARCHAR(20),
        alergias VARCHAR(40),
        id_supervisor VARCHAR(255),
        PRIMARY KEY (id_usuario),
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE,
        FOREIGN KEY (id_supervisor) REFERENCES supervisor (id_usuario) ON DELETE CASCADE
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    responsable (id_usuario VARCHAR(20) NOT NULL, salario INT (20) NOT NULL, PRIMARY KEY (id_usuario), FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario) ON DELETE CASCADE) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    dependencia (id_dependencia VARCHAR(20) NOT NULL, requisitos VARCHAR(20) NOT NULL, tipo VARCHAR(255) NOT NULL, grado VARCHAR(20) NOT NULL CHECK (grado IN ('I', 'II', 'III')), PRIMARY KEY (id_dependencia)) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    tarea (id_tarea VARCHAR(20) NOT NULL, fecha DATE NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion VARCHAR(40), PRIMARY KEY (id_tarea)) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    mensaje (
        id_mensaje INT (11) AUTO_INCREMENT PRIMARY KEY,
        id_sala VARCHAR(255) NOT NULL,
        user_emisor VARCHAR(255) NOT NULL,
        user_receptor VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    envia (
        id_mensaje int (11) NOT NULL,
        id_usuRemitente VARCHAR(20) NOT NULL,
        id_usuDestinatario VARCHAR(20) NOT NULL,
        PRIMARY KEY (id_mensaje, id_usuRemitente, id_usuDestinatario),
        FOREIGN KEY (id_mensaje) REFERENCES mensaje (id_mensaje),
        FOREIGN KEY (id_usuRemitente) REFERENCES usuario (id_usuario),
        FOREIGN KEY (id_usuDestinatario) REFERENCES usuario (id_usuario)
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    tiene (
        id_usuario VARCHAR(20) NOT NULL,
        id_dependencia VARCHAR(20) NOT NULL,
        PRIMARY KEY (id_usuario, id_dependencia),
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
        FOREIGN KEY (id_dependencia) REFERENCES dependencia (id_dependencia)
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    trabaja (
        id_dependencia VARCHAR(20) NOT NULL,
        id_usuario VARCHAR(20) NOT NULL,
        PRIMARY KEY (id_usuario, id_dependencia),
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
        FOREIGN KEY (id_dependencia) REFERENCES dependencia (id_dependencia)
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    asigna (
        id_usuario VARCHAR(20) NOT NULL,
        id_usuario_asignador VARCHAR(20) NOT NULL,
        id_tarea VARCHAR(20) NOT NULL,
        PRIMARY KEY (id_usuario, id_tarea),
        FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
        FOREIGN KEY (id_usuario_asignador) REFERENCES usuario (id_usuario),
        FOREIGN KEY (id_tarea) REFERENCES tarea (id_tarea)
    ) ENGINE = InnoDB CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insertar usuario paciente
INSERT INTO
    usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES
    ('1', 'paciente1', 'pass1', 'paciente1@example.com', '12345678A', 'Juan', 'Pérez', 'token123', 'Calle Mayor, 123', 123456789, 'paciente'),
    ('2', 'paciente2', 'pass2', 'paciente2@example.com', '98765432B', 'Ana', 'Gómez', 'token456', 'Avenida Principal, 456', 987654321, 'paciente'),
    ('3', 'paciente3', 'pass3', 'paciente3@example.com', '65432198C', 'Luis', 'López', 'token789', 'Plaza Central, 789', 654123789, 'paciente');

-- Insertar usuario supervisor
INSERT INTO
    usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES
    ('4', 'supervisor1', 'pass4', 'cuidador1@example.com', '456789123D', 'María', 'Martínez', 'token101', 'Calle San Juan, 101', 456987123, 'supervisor'),
    ('5', 'supervisor2', 'pass5', 'cuidador2@example.com', '789123456E', 'Carlos', 'Rodríguez', 'token202', 'Avenida de la Paz, 202', 789654321, 'supervisor'),
    ('6', 'supervisor3', 'pass6', 'cuidador3@example.com', '321654987F', 'Sofía', 'Sánchez', 'token303', 'Ronda de Segovia, 303', 321987654, 'supervisor');

-- Insertar usuario responsable
INSERT INTO
    usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES
    ('7', 'javi', 'pass7', 'javi@example.com', '159753468G', 'Javi', 'Ruiz', 'token404', 'Calle Mayor, 404', 159357486, 'admin'),
    ('8', 'responsable2', 'pass8', 'responsable2@example.com', '357159468H', 'Laura', 'García', 'token505', 'Avenida del Parque, 505', 357951468, 'admin'),
    ('9', 'responsable3', 'pass9', 'responsable3@example.com', '468357159I', 'Miguel', 'Díaz', 'token606', 'Plaza del Sol, 606', 468159357, 'admin');

-- Insertar paciente
INSERT INTO
    paciente (id_usuario, contact_emerg, especialidad_requerida, medicamentos, alergias)
VALUES
    ('1', 987654321, 'Cardiologia', 'Heparina', 'Polen'),
    ('2', 555555555, 'Reumatologia por artritis', 'Cetirizina', 'Ninguna'),
    ('3', 777777777, 'Oftalmologia por ceguera', 'Cetirizina', 'Acaros');

-- Insertar supervisor
INSERT INTO
    supervisor (id_usuario, disponibilidad, titulacion, salario, activado)
VALUES
    ('4', 'Mañanas', 'Psiquiatra', 6000, 0),
    ('5', 'Tardes', 'Cirujano Plástico', 5500, 0),
    ('6', 'Noches', 'Ginecólogo', 5800, 0);

-- Insertar responsable
INSERT INTO
    responsable (id_usuario, salario)
VALUES
    ('7', 12000),
    ('8', 11000),
    ('9', 10500);

-- Insertar dependencia
INSERT INTO
    dependencia (id_dependencia, requisitos, tipo, grado)
VALUES
    ('dep1', 'Certificado de salud', 'Clínica', 'II'),
    ('dep2', 'Experiencia previa', 'Centro de Rehabilitación', 'III'),
    ('dep3', 'Licencia vigente', 'Centro de Salud', 'I');

-- Insertar tarea
INSERT INTO
    tarea (id_tarea, fecha, titulo, descripcion)
VALUES
    ('tarea1', '2024-05-18', 'Seguimiento de pacientes', 'Realizar visitas domiciliarias'),
    ('tarea2', '2024-05-22', 'Reunión de equipo', 'Discutir nuevos protocolos médicos'),
    ('tarea3', '2024-05-20', 'Evaluación de rendimiento', 'Realizar entrevistas de evaluación');

-- Insertar mensaje
-- Insertar envía
-- Insertar tiene
INSERT INTO
    tiene (id_usuario, id_dependencia)
VALUES
    ('1', 'dep1'),
    ('2', 'dep2'),
    ('3', 'dep3');

-- Insertar trabaja
INSERT INTO
    trabaja (id_dependencia, id_usuario)
VALUES
    ('dep1', '4'),
    ('dep3', '5'),
    ('dep2', '6');

-- Insertar asigna
INSERT INTO
    asigna (id_usuario, id_usuario_asignador, id_tarea)
VALUES
    ('1', '4', 'tarea1'),
    ('1', '5', 'tarea2'),
    ('1', '6', 'tarea3'),
    ('2', '4', 'tarea1'),
    ('2', '5', 'tarea2'),
    ('2', '6', 'tarea3'),
    ('3', '4', 'tarea1'),
    ('3', '5', 'tarea2'),
    ('3', '6', 'tarea3');

COMMIT;