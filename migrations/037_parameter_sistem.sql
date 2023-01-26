CREATE TABLE `parameter_sistem` (`person` SMALLINT NOT NULL , `year` VARCHAR(4) NULL , PRIMARY KEY (`person`)) ENGINE = InnoDB;
ALTER TABLE `parameter_sistem` ADD `month` VARCHAR(2) NULL AFTER `year`;