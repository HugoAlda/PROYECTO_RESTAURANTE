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
    tipo_sala VARCHAR(15) NOT NULL
);

CREATE TABLE tbl_mesas (
    id_mesa INT NOT NULL PRIMARY KEY,
    n_asientos INT(2) NOT NULL,
    id_sala INT NOT NULL,
    estado_sala ENUM("V", "R") NOT NULL,
    assigned_by INT NULL,
    assigned_to VARCHAR(30) NULL
);

ALTER TABLE tbl_mesas ADD CONSTRAINT fk_sala_mesa FOREIGN KEY (id_sala) REFERENCES tbl_salas(id_salas);
ALTER TABLE tbl_mesas ADD CONSTRAINT fk_mesa_asignada FOREIGN KEY (assigned_by) REFERENCES tbl_camarero(id_camarero);
