ALTER TABLE `simpanan_pokok` ADD `dk` ENUM('D','K') NOT NULL AFTER `balance`;
ALTER TABLE `simpanan_wajib` ADD `dk` ENUM('D','K') NOT NULL AFTER `balance`;
ALTER TABLE `simpanan_sukarela` ADD `dk` ENUM('D','K') NOT NULL AFTER `balance`;
ALTER TABLE `simpanan_investasi` ADD `dk` ENUM('D','K') NOT NULL AFTER `balance`;