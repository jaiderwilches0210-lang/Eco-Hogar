CREATE DATABASE IF NOT EXISTS eco_hogar;
USE eco_hogar;

-- Tabla: rol
CREATE TABLE IF NOT EXISTS rol (
  idRol int(11) NOT NULL AUTO_INCREMENT,
  nomRol varchar(50) NOT NULL,
  desRol varchar(200) NOT NULL,
  PRIMARY KEY (idRol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: estado_producto (Implementación del Soft Delete)
CREATE TABLE IF NOT EXISTS estado_producto (
  idEst int(11) NOT NULL AUTO_INCREMENT,
  nomEst varchar(50) NOT NULL,
  PRIMARY KEY (idEst)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  idUsu int(11) NOT NULL AUTO_INCREMENT,
  nomUsu varchar(50) NOT NULL,
  email_Usu varchar(50) NOT NULL,
  clave varchar(100) NOT NULL,
  idRolFK int(11) NOT NULL,
  PRIMARY KEY (idUsu),
  KEY relacionRolUsuario_fk (idRolFK)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: categoria_producto
CREATE TABLE IF NOT EXISTS categoria_producto (
  idCat int(11) NOT NULL AUTO_INCREMENT,
  nomCat varchar(100) NOT NULL,
  PRIMARY KEY (idCat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: productos
CREATE TABLE IF NOT EXISTS productos (
  idPro int(11) NOT NULL AUTO_INCREMENT,
  idCatFK int(11) NOT NULL,
  nomPro varchar(100) NOT NULL,
  desPro text NOT NULL,
  preUni decimal(10,2) NOT NULL,
  preVen decimal(10,2) NOT NULL,
  FecReg date NOT NULL,
  stoAct int(11) NOT NULL,
  umbMinSo int(11) NOT NULL,
  idEstProEnumFK int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (idPro),
  KEY produ_fk (idCatFK),
  KEY productoEstado_fk (idEstProEnumFK)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: articuloinventariable
CREATE TABLE IF NOT EXISTS articuloinventariable (
  nomPro varchar(100) NOT NULL,
  fecReg date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: movimientos
CREATE TABLE IF NOT EXISTS movimientos (
  idMov int(11) NOT NULL AUTO_INCREMENT,
  idUsuFK int(11) NOT NULL,
  idProFK int(11) NOT NULL,
  tipMo int(11) NOT NULL,
  cantSto int(11) NOT NULL,
  fecMov date NOT NULL,
  razEgre text NOT NULL,
  PRIMARY KEY (idMov),
  KEY moviUsu_fk (idUsuFK),
  KEY movi_fk (idProFK)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: auditoria_operaciones
CREATE TABLE IF NOT EXISTS auditoria_operaciones (
  idReg int(11) NOT NULL AUTO_INCREMENT,
  idUsuFK int(11) NOT NULL,
  idMovFK int(11) NOT NULL,
  detCam text NOT NULL,
  fecMov date NOT NULL,
  PRIMARY KEY (idReg),
  KEY audiConUsu_fk (idUsuFK),
  KEY audiConMov_fk (idMovFK)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: configuracion_reporte
CREATE TABLE IF NOT EXISTS configuracion_reporte (
  idRepor int(11) NOT NULL AUTO_INCREMENT,
  idUsuFK int(11) NOT NULL,
  nomRep varchar(100) NOT NULL,
  forGen varchar(100) NOT NULL,
  idProFK int(11) NOT NULL,
  PRIMARY KEY (idRepor),
  KEY configRepor_fk (idUsuFK),
  KEY configuracion_reporte_fk (idProFK)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla: inventario
CREATE TABLE IF NOT EXISTS inventario (
  idInv int(11) NOT NULL AUTO_INCREMENT,
  idProFK int(11) NOT NULL,
  stoMin int(11) NOT NULL,
  stoMax int(11) NOT NULL,
  estInv int(11) NOT NULL,
  EstadoActivo tinyint(1) NOT NULL,
  PRIMARY KEY (idInv),
  KEY inventario_fk (idProFK)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- FOREIGN KEYS
-- =============================================

ALTER TABLE usuarios
  ADD CONSTRAINT relacionRolUsuario_fk FOREIGN KEY (idRolFK) REFERENCES rol (idRol);

ALTER TABLE productos
  ADD CONSTRAINT produ_fk FOREIGN KEY (idCatFK) REFERENCES categoria_producto (idCat),
  ADD CONSTRAINT productoEstado_fk FOREIGN KEY (idEstProEnumFK) REFERENCES estado_producto (idEst);

ALTER TABLE movimientos
  ADD CONSTRAINT moviUsu_fk FOREIGN KEY (idUsuFK) REFERENCES usuarios (idUsu),
  ADD CONSTRAINT movi_fk FOREIGN KEY (idProFK) REFERENCES productos (idPro);

ALTER TABLE auditoria_operaciones
  ADD CONSTRAINT audiConUsu_fk FOREIGN KEY (idUsuFK) REFERENCES usuarios (idUsu),
  ADD CONSTRAINT audiConMov_fk FOREIGN KEY (idMovFK) REFERENCES movimientos (idMov);

ALTER TABLE configuracion_reporte
  ADD CONSTRAINT configRepor_fk FOREIGN KEY (idUsuFK) REFERENCES usuarios (idUsu),
  ADD CONSTRAINT configuracion_reporte_fk FOREIGN KEY (idProFK) REFERENCES productos (idPro);

ALTER TABLE inventario
  ADD CONSTRAINT inventario_fk FOREIGN KEY (idProFK) REFERENCES productos (idPro);

-- =============================================
-- DATOS INICIALES
-- =============================================

START TRANSACTION;

INSERT INTO rol (idRol, nomRol, desRol) VALUES
(1, 'admin', 'Administrador del sistema'),
(2, 'usuario', 'Usuario general');

INSERT INTO estado_producto (idEst, nomEst) VALUES
(1, 'Activo'),
(2, 'Inactivo');

INSERT INTO categoria_producto (idCat, nomCat) VALUES
(1, 'Tecnología'),
(2, 'Ropa'),
(3, 'Hogar');

COMMIT;
  