CREATE TABLE `akumulasi_simpanan` (`person` SMALLINT NOT NULL , `tahun` VARCHAR(4) NOT NULL , `type` ENUM('Wajib','Sukarela','Investasi') NOT NULL , `total` FLOAT NOT NULL ) ENGINE = InnoDB;

ALTER TABLE `person` ADD `status_simpanan` ENUM('generated','custom') NULL DEFAULT NULL AFTER `investasi`;