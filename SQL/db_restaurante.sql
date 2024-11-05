CREATE DATABASE db_restaurante;

USE db_restaurante;

CREATE TABLE tbl_camarero (
    id_camarero INT NOT NULL PRIMARY KEY,
    name_camarero VARCHAR(30) NOT NULL,
    surname_camarero VARCHAR(30) NOT NULL,
    username_camarero VARCHAR(30) NOT NULL,
    pwd_camarero CHAR(64) NOT NULL
);

CREATE TABLE tbl_salas (
    id_salas INT NOT NULL PRIMARY KEY,
    name_sala VARCHAR(15) NOT NULL,
    tipo_sala ENUM("Comedor", "Terraza", "Privado") NOT NULL
);

CREATE TABLE tbl_mesas (
    id_mesa INT NOT NULL PRIMARY KEY,
    n_asientos INT(2) NOT NULL,
    id_sala INT NOT NULL,
    estado_sala ENUM("A", "NA") NOT NULL,
    assigned_by INT NULL,
    assigned_to VARCHAR(30) NULL
);

ALTER TABLE tbl_mesas ADD CONSTRAINT fk_sala_mesa FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_salas);
ALTER TABLE tbl_mesas ADD CONSTRAINT fk_mesa_asignada FOREIGN KEY (assigned_by) REFERENCES tbl_camarero(id_camarero);

INSERT INTO tbl_camarero (id_camarero, name_camarero, surname_camarero, username_camarero, pwd_camarero)
VALUES
(1, 'Carlos', 'García', 'cgarcia', SHA2('Camarero123.', 256)),
(2, 'Laura', 'Martínez', 'lmartinez', SHA2('Camarero123.', 256)),
(3, 'Ana', 'Sánchez', 'asanchez', SHA2('Camarero123.', 256)),
(4, 'Jorge', 'Hernández', 'jhernandez', SHA2('Camarero123.', 256)),
(5, 'Elena', 'López', 'elopez', SHA2('Camarero123.', 256));

-- Inserciones en la tabla tbl_salas
INSERT INTO tbl_salas (id_salas, name_sala, tipo_sala)
VALUES
(1, 'Principal', 'Comedor'),
(2, 'Terraza', 'Terraza'),
(3, 'Salón VIP', 'Privado'),
(4, 'Comedor 2', 'Comedor'),
(5, 'Jardín', 'Terraza');

-- Inserciones en la tabla tbl_mesas
INSERT INTO tbl_mesas (id_mesa, n_asientos, id_sala, estado_sala)
VALUES
(1, 4, 1, 'NA'),
(2, 6, 1, 'NA'),
(3, 2, 2, 'NA'),
(4, 8, 3, 'NA'),
(5, 4, 4, 'NA');
