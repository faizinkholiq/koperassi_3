ALTER TABLE `simpanan_pokok` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;
ALTER TABLE `simpanan_wajib` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;
ALTER TABLE `simpanan_sukarela` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;
ALTER TABLE `investasi` ADD `year` VARCHAR(4) NULL AFTER `date`, ADD `month` VARCHAR(2) NULL AFTER `year`;

ALTER TABLE `simpanan_pokok` ADD `posting` BOOLEAN NOT NULL DEFAULT FALSE AFTER `balance`;
ALTER TABLE `simpanan_wajib` ADD `posting` BOOLEAN NOT NULL DEFAULT FALSE AFTER `balance`;
ALTER TABLE `simpanan_sukarela` ADD `posting` BOOLEAN NOT NULL DEFAULT FALSE AFTER `balance`;
ALTER TABLE `investasi` ADD `posting` BOOLEAN NOT NULL DEFAULT FALSE AFTER `balance`;