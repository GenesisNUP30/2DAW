CREATE DATABASE IF NOT EXISTS proyecto_1eval;
USE proyecto_1eval;

CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nif_cif VARCHAR(20) NOT NULL,
    persona_contacto VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    direccion TEXT NOT NULL,
    poblacion VARCHAR(100) NOT NULL,
    codigo_postal CHAR(5) NOT NULL,
    provincia CHAR(2) NOT NULL,
    descripcion TEXT NOT NULL,
    anotaciones_anteriores TEXT,
    estado ENUM('B', 'P', 'R', 'C') NOT NULL DEFAULT 'P',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    operario_encargado VARCHAR(50),
    fecha_realizacion DATE NOT NULL,
    anotaciones_posteriores TEXT
);

-- Trigger para evitar que se modifique la fecha de creación
DELIMITER $$

CREATE TRIGGER tr_no_modificar_fecha_creacion
BEFORE UPDATE ON tareas
FOR EACH ROW
BEGIN
    SET NEW.fecha_creacion = OLD.fecha_creacion;
END$$

DELIMITER ;