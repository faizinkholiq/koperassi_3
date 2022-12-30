ALTER TABLE `simpanan_pokok` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;
ALTER TABLE `simpanan_wajib` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;
ALTER TABLE `simpanan_sukarela` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;
ALTER TABLE `investasi` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;