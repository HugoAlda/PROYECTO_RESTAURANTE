CREATE DATABASE db_restaurante;

USE db_restaurante;

CREATE TABLE tbl_camarero (
    id_camarero INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name_camarero VARCHAR(30) NOT NULL,
    surname_camarero VARCHAR(30) NOT NULL,
    username_camarero VARCHAR(30) NOT NULL,
    pwd_camarero CHAR(64) NOT NULL
);

CREATE TABLE tbl_salas (
    id_salas INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name_sala VARCHAR(15) NOT NULL,
    tipo_sala ENUM("Comedor", "Terraza", "Privado") NOT NULL
);

CREATE TABLE tbl_mesas (
    id_mesa INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    n_asientos INT NOT NULL,
    id_sala INT NOT NULL,
    estado_sala ENUM("A", "NA") NOT NULL DEFAULT "NA"
);

CREATE TABLE tbl_historial(
    id_historial INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha_A date NOT NULL,
    fecha_NA date NULL,
    assigned_by INT NOT NULL,
    assigned_to VARCHAR(30) NOT NULL,
    id_mesa INT NOT NULL
);

ALTER TABLE tbl_mesas ADD CONSTRAINT fk_sala_mesa FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_salas);
ALTER TABLE tbl_historial ADD CONSTRAINT fk_camarero_asignado FOREIGN KEY (assigned_by) REFERENCES tbl_camarero(id_camarero);
ALTER TABLE tbl_historial ADD CONSTRAINT fk_mesa_historial FOREIGN KEY (id_mesa) REFERENCES tbl_mesas(id_mesa);

INSERT INTO tbl_camarero (name_camarero, surname_camarero, username_camarero, pwd_camarero)
VALUES
('Carlos', 'García', 'cgarcia', SHA2('Camarero123.', 256)),
('Laura', 'Martínez', 'lmartinez', SHA2('Camarero123.', 256)),
('Ana', 'Sánchez', 'asanchez', SHA2('Camarero123.', 256)),
('Jorge', 'Hernández', 'jhernandez', SHA2('Camarero123.', 256)),
('Elena', 'López', 'elopez', SHA2('Camarero123.', 256));

-- Inserciones en la tabla tbl_salas
INSERT INTO tbl_salas (name_sala, tipo_sala)
VALUES
('Comedor 1', 'Comedor'),
('Terraza 1', 'Terraza'),
('Salón VIP', 'Privado'),
('Comedor 2', 'Comedor'),
('Jardín', 'Terraza'),
('Terraza 2', 'Terraza'),
('Salón VIP 2', 'Privado'),
('Salón romántico', 'Privado'),
('Naturaleza', 'Privado');

-- Inserciones en la tabla tbl_mesas
INSERT INTO tbl_mesas (n_asientos, id_sala) VALUES
(4, 1),
(6, 1),
(8, 1),
(2, 1),
(4, 2),
(6, 2),
(8, 2),
(2, 3),
(4, 3),
(6, 3),
(8, 3),
(4, 4),
(6, 4),
(2, 4),
(8, 5),
(4, 5),
(6, 5),
(2, 5),
(4, 6),
(6, 6),
(8, 6),
(2, 7),
(4, 7),
(6, 7),
(8, 8),
(4, 8),
(6, 8),
(2, 9),
(4, 9),
(6, 9);

INSERT INTO tbl_historial (fecha_A, fecha_NA, assigned_by, assigned_to, id_mesa) 
VALUES 
('2024-03-14 14:20:10', NULL, 1, 'Juanjo', 10),
('2024-05-22 11:10:30', NULL, 5, 'Juana', 15),
('2024-09-15 10:45:15', NULL, 2, 'Canis', 18),
('2024-11-1 08:00:00', NULL, 4, 'Nuestro querido jose', 11),
('2024-03-14 14:20:10', '2024-03-14 14:30:10', 3, 'Panchito', 2); -- para pruebas
