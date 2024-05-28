-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS tfg;
USE tfg;

-- Eliminación de tablas si existen
DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS paciente;
DROP TABLE IF EXISTS supervisor;
DROP TABLE IF EXISTS responsable;
DROP TABLE IF EXISTS dependencia;
DROP TABLE IF EXISTS tarea;
DROP TABLE IF EXISTS mensaje;
DROP TABLE IF EXISTS envia;
DROP TABLE IF EXISTS tiene;
DROP TABLE IF EXISTS trabaja;

CREATE TABLE usuario (
    id_usuario VARCHAR(20) NOT NULL,
    username VARCHAR(20) NOT NULL, 
    pass VARCHAR(20) NOT NULL,
    mail VARCHAR(40) NOT NULL, 
    dni VARCHAR(10) NOT NULL,
    apellido VARCHAR(30) NOT NULL,
    token VARCHAR(30) NOT NULL, 
    dir VARCHAR(40) NOT NULL,
    tel INT(9) NOT NULL,
    usertype ENUM('paciente', 'supervisor', 'admin') NOT NULL,

    PRIMARY KEY (id_usuario)
);

CREATE TABLE paciente (
    id_usuario VARCHAR(20) NOT NULL,
    contact_emerg INT(9) NOT NULL,
    especialidad_requerida VARCHAR(50) NOT NULL,
    medicamentos VARCHAR(20), 
    alergias VARCHAR(40), 
 
    PRIMARY KEY (id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);


CREATE TABLE supervisor (
    id_usuario VARCHAR(20) NOT NULL,
    disponibilidad VARCHAR(40) NOT NULL,
    titulacion VARCHAR(20) NOT NULL,
    salario DECIMAL(20), 
 
    PRIMARY KEY (id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);


CREATE TABLE responsable (
    id_usuario VARCHAR(20) NOT NULL,
    salario DECIMAL(20) NOT NULL, 
 
    PRIMARY KEY (id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

CREATE TABLE dependencia (
    id_dependencia VARCHAR(20) NOT NULL,
    requisitos VARCHAR(20) NOT NULL, 
    tipo VARCHAR(255) NOT NULL,
    grado ENUM('I', 'II', 'III') NOT NULL,
 
    PRIMARY KEY (id_dependencia)
);

CREATE TABLE tarea (
    id_tarea VARCHAR(20) NOT NULL,
    fecha_ini DATE NOT NULL, 
    fecha_fin DATE,
    titulo VARCHAR(255) NOT NULL,
    descripcion VARCHAR(40),
 
    PRIMARY KEY (id_tarea)
);

CREATE TABLE mensaje (
    id_mensaje VARCHAR(20) NOT NULL, 
    contenido VARCHAR(255) NOT NULL,
 
    PRIMARY KEY (id_mensaje)
);


CREATE TABLE envia (
    id_mensaje VARCHAR(20) NOT NULL,
    id_usuario VARCHAR(20) NOT NULL,
    marca_tiempo TIMESTAMP NOT NULL,

    PRIMARY KEY (id_mensaje, id_usuario),
    FOREIGN KEY (id_mensaje) REFERENCES mensaje(id_mensaje) on DELETE CASCADE, 
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)

);

CREATE TABLE tiene (
    id_usuario VARCHAR(20) NOT NULL,
    id_dependencia VARCHAR(20) NOT NULL,
 
    PRIMARY KEY (id_usuario, id_dependencia), 
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_dependencia) REFERENCES dependencia(id_dependencia)

);

CREATE TABLE trabaja (
    id_dependencia VARCHAR(20) NOT NULL,
    id_usuario VARCHAR(20) NOT NULL,
 
    PRIMARY KEY (id_usuario, id_dependencia),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_dependencia) REFERENCES dependencia(id_dependencia)
 
);

-- Insertar pacientes
INSERT INTO usuario (id_usuario, username, pass, mail, dni, apellido, token, dir, tel, usertype)
VALUES ('1', 'paciente1', 'pass1', 'paciente1@example.com', '12345678A', 'Pérez', 'token123', 'Calle Mayor, 123', 123456789, 'paciente'),
       ('2', 'paciente2', 'pass2', 'paciente2@example.com', '98765432B', 'Gómez', 'token456', 'Avenida Principal, 456', 987654321, 'paciente'),
       ('3', 'paciente3', 'pass3', 'paciente3@example.com', '65432198C', 'López', 'token789', 'Plaza Central, 789', 654123789, 'paciente');

-- Insertar cuidadores
INSERT INTO usuario (id_usuario, username, pass, mail, dni, apellido, token, dir, tel, usertype)
VALUES ('4', 'cuidador1', 'pass4', 'cuidador1@example.com', '456789123D', 'Martínez', 'token101', 'Calle San Juan, 101', 456987123, 'supervisor'),
       ('5', 'cuidador2', 'pass5', 'cuidador2@example.com', '789123456E', 'Rodríguez', 'token202', 'Avenida de la Paz, 202', 789654321, 'supervisor'),
       ('6', 'cuidador3', 'pass6', 'cuidador3@example.com', '321654987F', 'Sánchez', 'token303', 'Ronda de Segovia, 303', 321987654, 'supervisor');

-- Insertar responsables
INSERT INTO usuario (id_usuario, username, pass, mail, dni, apellido, token, dir, tel, usertype)
VALUES ('7', 'responsable1', 'pass7', 'responsable1@example.com', '159753468G', 'Fernández', 'token404', 'Calle Mayor, 404', 159357486, 'admin'),
       ('8', 'responsable2', 'pass8', 'responsable2@example.com', '357159468H', 'García', 'token505', 'Avenida del Parque, 505', 357951468, 'admin'),
       ('9', 'responsable3', 'pass9', 'responsable3@example.com', '468357159I', 'Díaz', 'token606', 'Plaza del Sol, 606', 468159357, 'admin');


INSERT INTO paciente VALUES ('1', 987654321, 'Cardiologia', 'Heparina', 'Polen');
INSERT INTO paciente VALUES ('2', 555555555, 'Reumatologia por artritis', 'Cetirizina', 'Ninguna');
INSERT INTO paciente VALUES ('3', 777777777, 'Oftalmologia por ceguera', 'Cetirizina', 'Acaros');

INSERT INTO supervisor VALUES ('4', 'Mañanas', 'Psiquiatra', 6000);
INSERT INTO supervisor VALUES ('5', 'Tardes', 'Cirujano Plástico', 5500);
INSERT INTO supervisor VALUES ('6', 'Noches', 'Ginecólogo', 5800);

INSERT INTO responsable VALUES ('7', 12000);
INSERT INTO responsable VALUES ('8', 11000);
INSERT INTO responsable VALUES ('9', 10500);

INSERT INTO dependencia VALUES ('dep1', 'Certificado de salud', 'Clínica', 'II');
INSERT INTO dependencia VALUES ('dep2', 'Experiencia previa', 'Centro de Rehabilitación', 'III');
INSERT INTO dependencia VALUES ('dep3', 'Licencia vigente', 'Centro de Salud', 'I');

INSERT INTO tarea VALUES ('tarea1', '2024-05-18', '2024-05-25', 'Seguimiento de pacientes', 'Realizar visitas domiciliarias');
INSERT INTO tarea VALUES ('tarea2', '2024-05-22', '2024-05-28', 'Reunión de equipo', 'Discutir nuevos protocolos médicos');
INSERT INTO tarea VALUES ('tarea3', '2024-05-20', '2024-05-27', 'Evaluación de rendimiento', 'Realizar entrevistas de evaluación');

INSERT INTO mensaje VALUES ('mensaje2', 'Buenos días, ¿cómo puedo ayudarte?');
INSERT INTO mensaje VALUES ('mensaje3', 'Recuerda tomar tu medicamento');
INSERT INTO mensaje VALUES ('mensaje4', 'Estoy en camino');

INSERT INTO envia VALUES ('mensaje2', '2', CURRENT_TIMESTAMP);
INSERT INTO envia VALUES ('mensaje3', '4', CURRENT_TIMESTAMP);
INSERT INTO envia VALUES ('mensaje4', '3', CURRENT_TIMESTAMP);

INSERT INTO tiene VALUES ('1', 'dep1');
INSERT INTO tiene VALUES ('2', 'dep2');
INSERT INTO tiene VALUES ('3', 'dep3');

INSERT INTO trabaja VALUES ('dep1', '4');
INSERT INTO trabaja VALUES ('dep3', '5');
INSERT INTO trabaja VALUES ('dep2', '6');