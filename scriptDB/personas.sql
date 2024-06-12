REATE DATABASE IF NOT EXISTS tfg;
USE tfg;

-- Eliminación de tablas si existen
DROP TABLE IF EXISTS asigna;
DROP TABLE IF EXISTS trabaja;
DROP TABLE IF EXISTS tiene;
DROP TABLE IF EXISTS envia;
DROP TABLE IF EXISTS mensaje;
DROP TABLE IF EXISTS tarea;
DROP TABLE IF EXISTS dependencia;
DROP TABLE IF EXISTS responsable;
DROP TABLE IF EXISTS supervisor;
DROP TABLE IF EXISTS paciente;
DROP TABLE IF EXISTS usuario;

CREATE TABLE usuario (
    id_usuario VARCHAR(20) NOT NULL,
    username VARCHAR(20) NOT NULL, 
    pass VARCHAR(20) NOT NULL,
    email VARCHAR(40) NOT NULL, 
    dni VARCHAR(10) NOT NULL,
    nombre VARCHAR(30) NOT NULL, 
    apellido VARCHAR(30) NOT NULL,
    token VARCHAR(30) NOT NULL, 
    dir VARCHAR(40) NOT NULL,
    tel INT(9) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    usertype VARCHAR(20) NOT NULL CHECK (usertype IN ('paciente', 'supervisor', 'admin')),
     
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
    salario INT(20) NOT NULL,
    activado INT(1) NOT NULL DEFAULT 0 CHECK (activado IN (0, 1)),
 
    PRIMARY KEY (id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

CREATE TABLE responsable (
    id_usuario VARCHAR(20) NOT NULL,
    salario INT(20) NOT NULL, 
 
    PRIMARY KEY (id_usuario),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

CREATE TABLE dependencia (
    id_dependencia VARCHAR(20) NOT NULL,
    requisitos VARCHAR(20) NOT NULL, 
    tipo VARCHAR(255) NOT NULL,
    grado VARCHAR(20) NOT NULL CHECK (grado IN ('I', 'II', 'III')),
 
    PRIMARY KEY (id_dependencia)
);

CREATE TABLE tarea (
    id_tarea VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descripcion VARCHAR(40),
 
    PRIMARY KEY (id_tarea)
);

CREATE TABLE mensaje (
    id_mensaje VARCHAR(20) NOT NULL, 
    id_sala VARCHAR(20) NOT NULL,
    contenido VARCHAR(255) NOT NULL,
    marca_tiempo TIMESTAMP NOT NULL,

    PRIMARY KEY (id_mensaje)
);

CREATE TABLE envia (
    id_mensaje VARCHAR(20) NOT NULL,
    id_usuRemitente VARCHAR(20) NOT NULL,
    id_usuDestinatario VARCHAR(20) NOT NULL,
    
    PRIMARY KEY (id_mensaje, id_usuRemitente, id_usuDestinatario),
    FOREIGN KEY (id_mensaje) REFERENCES mensaje(id_mensaje), 
    FOREIGN KEY (id_usuRemitente) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_usuDestinatario) REFERENCES usuario(id_usuario)
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

CREATE TABLE asigna (
    id_usuario VARCHAR(20) NOT NULL, 
    id_usuario_asignador VARCHAR(20) NOT NULL, 
    id_tarea VARCHAR(20) NOT NULL, 

    PRIMARY KEY (id_usuario, id_tarea),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_usuario_asignador) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_tarea) REFERENCES tarea(id_tarea)
);

-- Insertar usuario paciente
INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('1', 'paciente1', 'pass1', 'paciente1@example.com', '12345678A', 'Juan', 'Pérez', 'token123', 'Calle Mayor, 123', 123456789, 'paciente');

INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('2', 'paciente2', 'pass2', 'paciente2@example.com', '98765432B', 'Ana', 'Gómez', 'token456', 'Avenida Principal, 456', 987654321, 'paciente');

INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('3', 'paciente3', 'pass3', 'paciente3@example.com', '65432198C', 'Luis', 'López', 'token789', 'Plaza Central, 789', 654123789, 'paciente');

-- Insertar usuario supervisor
INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('4', 'supervisor1', 'pass4', 'cuidador1@example.com', '456789123D', 'María', 'Martínez', 'token101', 'Calle San Juan, 101', 456987123, 'supervisor');

INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('5', 'supervisor2', 'pass5', 'cuidador2@example.com', '789123456E', 'Carlos', 'Rodríguez', 'token202', 'Avenida de la Paz, 202', 789654321, 'supervisor');

INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('6', 'supervisor3', 'pass6', 'cuidador3@example.com', '321654987F', 'Sofía', 'Sánchez', 'token303', 'Ronda de Segovia, 303', 321987654, 'supervisor');

-- Insertar usuario responsable
INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('7', 'responsable1', 'pass7', 'responsable1@example.com', '159753468G', 'Fernando', 'Fernández', 'token404', 'Calle Mayor, 404', 159357486, 'admin');

INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('8', 'responsable2', 'pass8', 'responsable2@example.com', '357159468H', 'Laura', 'García', 'token505', 'Avenida del Parque, 505', 357951468, 'admin');

INSERT INTO usuario (id_usuario, username, pass, email, dni, nombre, apellido, token, dir, tel, usertype)
VALUES ('9', 'responsable3', 'pass9', 'responsable3@example.com', '468357159I', 'Miguel', 'Díaz', 'token606', 'Plaza del Sol, 606', 468159357, 'admin');

-- Insertar paciente
INSERT INTO paciente VALUES ('1', 987654321, 'Cardiologia', 'Heparina', 'Polen');
INSERT INTO paciente VALUES ('2', 555555555, 'Reumatologia por artritis', 'Cetirizina', 'Ninguna');
INSERT INTO paciente VALUES ('3', 777777777, 'Oftalmologia por ceguera', 'Cetirizina', 'Acaros');

-- Insertar supervisor
INSERT INTO supervisor VALUES ('4', 'Mañanas', 'Psiquiatra', 6000, 0);
INSERT INTO supervisor VALUES ('5', 'Tardes', 'Cirujano Plástico', 5500, 0);
INSERT INTO supervisor VALUES ('6', 'Noches', 'Ginecólogo', 5800, 0);

-- Insertar responsable
INSERT INTO responsable VALUES ('7', 12000);
INSERT INTO responsable VALUES ('8', 11000);
INSERT INTO responsable VALUES ('9', 10500);

-- Insertar dependencia
INSERT INTO dependencia VALUES ('dep1', 'Certificado de salud', 'Clínica', 'II');
INSERT INTO dependencia VALUES ('dep2', 'Experiencia previa', 'Centro de Rehabilitación', 'III');
INSERT INTO dependencia VALUES ('dep3', 'Licencia vigente', 'Centro de Salud', 'I');

-- Insertar tarea
INSERT INTO tarea VALUES ('tarea1', '2024-05-18', 'Seguimiento de pacientes', 'Realizar visitas domiciliarias');
INSERT INTO tarea VALUES ('tarea2', '2024-05-22', 'Reunión de equipo', 'Discutir nuevos protocolos médicos');
INSERT INTO tarea VALUES ('tarea3', '2024-05-20', 'Evaluación de rendimiento', 'Realizar entrevistas de evaluación');

-- Insertar mensaje
INSERT INTO mensaje VALUES ('mensaje2', 'sala1', 'Buenos días, ¿cómo puedo ayudarte?', CURRENT_TIMESTAMP);
INSERT INTO mensaje VALUES ('mensaje3', 'sala1', 'Recuerda tomar tu medicamento', CURRENT_TIMESTAMP);
INSERT INTO mensaje VALUES ('mensaje4', 'sala1', 'Estoy en camino', CURRENT_TIMESTAMP);

-- Insertar envía
INSERT INTO envia VALUES ('mensaje2', '2', '3');
INSERT INTO envia VALUES ('mensaje3', '4', '2');
INSERT INTO envia VALUES ('mensaje4', '3', '4');

-- Insertar tiene
INSERT INTO tiene VALUES ('1', 'dep1');
INSERT INTO tiene VALUES ('2', 'dep2');
INSERT INTO tiene VALUES ('3', 'dep3');

-- Insertar trabaja
INSERT INTO trabaja VALUES ('dep1', '4');
INSERT INTO trabaja VALUES ('dep3', '5');
INSERT INTO trabaja VALUES ('dep2', '6');

-- Insertar asigna
INSERT INTO asigna VALUES ('1', '4', 'tarea1');
INSERT INTO asigna VALUES ('1', '5', 'tarea2');
INSERT INTO asigna VALUES ('1', '6', 'tarea3');
INSERT INTO asigna VALUES ('2', '4', 'tarea1');
INSERT INTO asigna VALUES ('2', '5', 'tarea2');
INSERT INTO asigna VALUES ('2', '6', 'tarea3');
INSERT INTO asigna VALUES ('3', '4', 'tarea1');
INSERT INTO asigna VALUES ('3', '5', 'tarea2');
INSERT INTO asigna VALUES ('3', '6', 'tarea3');

COMMIT;