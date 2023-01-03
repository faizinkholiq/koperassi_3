ALTER TABLE `history_simpanan` CHANGE `person_id` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `person_family` CHANGE `person_id` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `pinjaman` CHANGE `person` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `settings_simpanan_sukarela` CHANGE `person_id` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `simpanan_investasi` CHANGE `person` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `simpanan_pokok` CHANGE `person` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `simpanan_sukarela` CHANGE `person` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `simpanan_wajib` CHANGE `person` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `simpanan_temp` CHANGE `person_id` `person` VARCHAR(20) NOT NULL;
ALTER TABLE `koperassi`.`person` ADD UNIQUE `unique_nik` (`nik`);