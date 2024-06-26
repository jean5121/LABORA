-- MySQL Script generated by MySQL Workbench
-- Wed Apr 17 22:03:54 2024
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema LAB
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema LAB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `LAB` DEFAULT CHARACTER SET utf8 ;
USE `LAB` ;

-- -----------------------------------------------------
-- Table `LAB`.`tipo_usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`tipo_usuario` (
  `idtipo_usuario` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ctipouser` VARCHAR(45) NULL,
  PRIMARY KEY (`idtipo_usuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`usuario` (
  `idusuario` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_usuario` VARCHAR(100) NULL,
  `usuario` VARCHAR(45) NULL,
  `contrasena` VARCHAR(45) NULL,
  `estado_usu` TINYINT(3) UNSIGNED NOT NULL DEFAULT 1,
  `tipo_usuario` TINYINT(3) UNSIGNED NOT NULL,
  `dni_usu` VARCHAR(8) NULL,
  PRIMARY KEY (`idusuario`),
  INDEX `fk_usuario_tipo_usuario1_idx` (`tipo_usuario` ASC) ,
  CONSTRAINT `fk_usuario_tipo_usuario1`
    FOREIGN KEY (`tipo_usuario`)
    REFERENCES `LAB`.`tipo_usuario` (`idtipo_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`clinica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`clinica` (
  `idclinica` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_cli` VARCHAR(100) NULL,
  `telefono_cli` VARCHAR(9) NULL,
  `direccion_cli` VARCHAR(130) NULL,
  `referencia_cli` VARCHAR(70) NULL,
  `estado_cli` BIT(1) NULL DEFAULT 1,
  `ruc_cli` VARCHAR(12) NULL,
  PRIMARY KEY (`idclinica`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`producto` (
  `idproducto` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_pro` VARCHAR(110) NULL,
  `precio_promedio` FLOAT(2) NULL,
  `estado_pro` TINYINT(3) UNSIGNED NULL DEFAULT 1,
  `cantidad_material` FLOAT(3) NULL,
  PRIMARY KEY (`idproducto`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`odontologo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`odontologo` (
  `idodontologo` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre_odo` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(12) NULL,
  `dni_odo` VARCHAR(9) NULL,
  `direccion_odo` VARCHAR(70) NULL,
  `est_odo` BIT(1) NULL DEFAULT 1,
  `ruc_odonto` VARCHAR(11) NULL,
  PRIMARY KEY (`idodontologo`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`boleta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`boleta` (
  `idboleta` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha_crea` DATETIME NOT NULL,
  `precio_total` FLOAT UNSIGNED NULL,
  `estado_pago` BIT(1) NULL DEFAULT 0,
  `estado_entrega` BIT NULL DEFAULT 0,
  `fecha_entrega` DATE NULL,
  `deuda` FLOAT UNSIGNED NOT NULL,
  `idclinica` SMALLINT(6) UNSIGNED NOT NULL,
  `idusuario_creador` SMALLINT(6) UNSIGNED NOT NULL,
  `idodontologo` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idboleta`),
  INDEX `fk_voleta_clinica_idx` (`idclinica` ASC) ,
  INDEX `fk_voleta_usuario1_idx` (`idusuario_creador` ASC) ,
  INDEX `fk_boleta_odontologo1_idx` (`idodontologo` ASC) ,
  CONSTRAINT `fk_voleta_clinica`
    FOREIGN KEY (`idclinica`)
    REFERENCES `LAB`.`clinica` (`idclinica`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_voleta_usuario1`
    FOREIGN KEY (`idusuario_creador`)
    REFERENCES `LAB`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_boleta_odontologo1`
    FOREIGN KEY (`idodontologo`)
    REFERENCES `LAB`.`odontologo` (`idodontologo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`detalle_boleta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`detalle_boleta` (
  `iddetalle_boleta` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cantidad` SMALLINT(6) UNSIGNED NULL,
  `sub_total` FLOAT UNSIGNED NULL,
  `descripcion` VARCHAR(200) NULL,
  `precio_unidad` FLOAT UNSIGNED NULL,
  `idproducto` SMALLINT(6) UNSIGNED NOT NULL,
  `idvoleta` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`iddetalle_boleta`),
  INDEX `fk_detalle_voleta_producto1_idx` (`idproducto` ASC) ,
  INDEX `fk_detalle_voleta_voleta1_idx` (`idvoleta` ASC) ,
  CONSTRAINT `fk_detalle_voleta_producto1`
    FOREIGN KEY (`idproducto`)
    REFERENCES `LAB`.`producto` (`idproducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_voleta_voleta1`
    FOREIGN KEY (`idvoleta`)
    REFERENCES `LAB`.`boleta` (`idboleta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`medio_pago`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`medio_pago` (
  `idmedio_pago` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cmedio` VARCHAR(60) NULL,
  PRIMARY KEY (`idmedio_pago`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`pagos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`pagos` (
  `idpago` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha_pago` DATETIME NULL,
  `cantidad_pago` FLOAT UNSIGNED NOT NULL,
  `idvoleta` INT UNSIGNED NOT NULL,
  `idusuario` SMALLINT(6) UNSIGNED NOT NULL,
  `idmedio_pago` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`idpago`),
  INDEX `fk_modi_adelanto_voleta1_idx` (`idvoleta` ASC) ,
  INDEX `fk_modi_adelanto_usuario1_idx` (`idusuario` ASC) ,
  INDEX `fk_pagos_medio_pago1_idx` (`idmedio_pago` ASC) ,
  CONSTRAINT `fk_modi_adelanto_voleta1`
    FOREIGN KEY (`idvoleta`)
    REFERENCES `LAB`.`boleta` (`idboleta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_modi_adelanto_usuario1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `LAB`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pagos_medio_pago1`
    FOREIGN KEY (`idmedio_pago`)
    REFERENCES `LAB`.`medio_pago` (`idmedio_pago`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `LAB`.`impresion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `LAB`.`impresion` (
  `idimpresion` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha_impre` DATETIME NULL,
  `idboleta` INT UNSIGNED NOT NULL,
  `idusuario` SMALLINT(6) UNSIGNED NOT NULL,
  `ruc_dni_impre` VARCHAR(12) NULL,
  PRIMARY KEY (`idimpresion`),
  INDEX `fk_impresion_boleta1_idx` (`idboleta` ASC) ,
  INDEX `fk_impresion_usuario1_idx` (`idusuario` ASC) ,
  CONSTRAINT `fk_impresion_boleta1`
    FOREIGN KEY (`idboleta`)
    REFERENCES `LAB`.`boleta` (`idboleta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_impresion_usuario1`
    FOREIGN KEY (`idusuario`)
    REFERENCES `LAB`.`usuario` (`idusuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `LAB`;

DELIMITER $$
USE `LAB`$$
CREATE DEFINER = CURRENT_USER TRIGGER `LAB`.`boleta_BEFORE_UPDATE` BEFORE UPDATE ON `boleta` FOR EACH ROW

CREATE TRIGGER update_trigger 
BEFORE UPDATE ON boleta 
FOR EACH ROW
BEGIN
    IF NEW.deuda = 0 THEN
        SET NEW.estado_pago = 1;
    END IF;
END;$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
