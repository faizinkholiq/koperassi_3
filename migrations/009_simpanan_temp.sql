CREATE TABLE `koperassi`.`simpanan_temp` (`id` INT NOT NULL AUTO_INCREMENT , `simpanan_id` INT NOT NULL , `type` ENUM('Pokok','Wajib','Sukarela') NOT NULL , `person_id` SMALLINT(6) NOT NULL , `balance` FLOAT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;