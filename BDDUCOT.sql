CREATE DATABASE ucot;
USE ucot;

-- 1. TABLAS DE USUARIOS Y AUTENTICACIÓN

-- Tabla base para credenciales de acceso
CREATE TABLE auth (
    id_auth INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) NOT NULL DEFAULT 'cliente',
    PRIMARY KEY (id_auth)
);

-- Tabla para datos personales de Estudiantes
CREATE TABLE perfiles_estudiantes (
    id_estudiante INT NOT NULL AUTO_INCREMENT,
    id_auth INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_estudiante),
    FOREIGN KEY (id_auth) REFERENCES auth(id_auth) ON DELETE CASCADE
);

-- Tabla para el Profesor (Superuser)
-- Se separa para tener control total sobre sus datos específicos
CREATE TABLE perfil_profesor (
    id_profesor INT NOT NULL AUTO_INCREMENT,
    id_auth INT NOT NULL,
    nombre_profesor VARCHAR(50) NOT NULL,
    apellido_profesor VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_profesor),
    FOREIGN KEY (id_auth) REFERENCES auth(id_auth) ON DELETE CASCADE
);

-- 2. TABLAS DE OPERACIÓN (HORARIOS Y CITAS)

CREATE TABLE horarios (
    id_horario INT NOT NULL AUTO_INCREMENT,
    id_profesor INT NOT NULL, -- Ahora apunta a la tabla perfil_profesor
    week_day ENUM('LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    estado ENUM('Disponible', 'Reservado', 'No_trabaja') NOT NULL,
    PRIMARY KEY (id_horario),
    FOREIGN KEY (id_profesor) REFERENCES perfil_profesor(id_profesor)
);

CREATE TABLE citas (
    id_cita INT NOT NULL AUTO_INCREMENT,
    id_alumno INT NOT NULL,     -- Apunta a perfiles_estudiantes
    id_profesor INT NOT NULL,   -- Apunta a perfil_profesor
    fecha_hora_inicio DATETIME NOT NULL,
    duracion_min INT NOT NULL,
    materia VARCHAR(100),
    estado_cita ENUM('confirmado', 'reprogramado', 'cancelado', 'pendiente') NOT NULL,
    PRIMARY KEY (id_cita),
    FOREIGN KEY (id_alumno) REFERENCES perfiles_estudiantes(id_estudiante),
    FOREIGN KEY (id_profesor) REFERENCES perfil_profesor(id_profesor)
);

-- 3. TABLAS DE PAGOS Y FEEDBACK
CREATE TABLE pago_estatico (
    id_pago VARCHAR(20) NOT NULL, -- ID de transacción o referencia
    id_cita INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_pago DATE NOT NULL,
    screenshot VARCHAR(255),
    estado_pago ENUM('pendiente', 'confirmado', 'rechazado') NOT NULL,
    fecha_confirmacion DATETIME,
    PRIMARY KEY (id_pago),
    FOREIGN KEY (id_cita) REFERENCES citas(id_cita)
);

CREATE TABLE feedback (
    id_feedback INT NOT NULL AUTO_INCREMENT,
    id_cita INT NOT NULL,
    id_alumno INT NOT NULL,
    id_profesor INT NOT NULL,
    puntuacion TINYINT NOT NULL CHECK (puntuacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_evaluacion DATETIME NOT NULL,
    PRIMARY KEY (id_feedback),
    FOREIGN KEY (id_cita) REFERENCES citas(id_cita),
    FOREIGN KEY (id_alumno) REFERENCES perfiles_estudiantes(id_estudiante),
    FOREIGN KEY (id_profesor) REFERENCES perfil_profesor(id_profesor)
);

SHOW TABLES;
