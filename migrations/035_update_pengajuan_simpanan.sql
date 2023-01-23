ALTER TABLE `settings_simpanan_sukarela` ADD `type` ENUM('Sukarela', 'Investasi') NULL AFTER `status`;
ALTER TABLE `settings_simpanan_sukarela` RENAME TO `pengajuan_simpanan`;
ALTER TABLE `pengajuan_simpanan` ADD `reason` TEXT NULL AFTER `type`;