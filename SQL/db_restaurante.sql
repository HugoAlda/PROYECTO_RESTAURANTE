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
    estado_sala ENUM("A", "NA") NOT NULL DEFAULT "NA",
    assigned_by INT NULL,
    assigned_to VARCHAR(30) NULL
);

ALTER TABLE tbl_mesas ADD CONSTRAINT fk_sala_mesa FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_salas);
ALTER TABLE tbl_mesas ADD CONSTRAINT fk_mesa_asignada FOREIGN KEY (assigned_by) REFERENCES tbl_camarero(id_camarero);

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

