-- === Rozvrhové sloupce do Kurzu (1 slot / kurz) ===
ALTER TABLE `mydb`.`kurz`
  ADD COLUMN `den` TINYINT NULL COMMENT '1=Po, 2=Út, 3=St, 4=Čt, 5=Pá' AFTER `garant_ID`,
  ADD COLUMN `vyuka_od` TIME NULL AFTER `den`,
  ADD COLUMN `vyuka_do` TIME NULL AFTER `vyuka_od`;

-- (volitelné) ukázkové naplnění rozvrhu pro seedované kurzy (IDs 1..12)
UPDATE `mydb`.`kurz` SET den=1, vyuka_od='10:00:00', vyuka_do='11:30:00' WHERE ID=1;   -- IZP
UPDATE `mydb`.`kurz` SET den=2, vyuka_od='09:00:00', vyuka_do='10:30:00' WHERE ID=2;   -- IDM
UPDATE `mydb`.`kurz` SET den=3, vyuka_od='08:30:00', vyuka_do='10:00:00' WHERE ID=3;   -- ILG
UPDATE `mydb`.`kurz` SET den=4, vyuka_od='09:00:00', vyuka_do='10:50:00' WHERE ID=4;   -- IUS
UPDATE `mydb`.`kurz` SET den=1, vyuka_od='12:00:00', vyuka_do='13:30:00' WHERE ID=5;   -- IEL
UPDATE `mydb`.`kurz` SET den=2, vyuka_od='11:00:00', vyuka_do='12:30:00' WHERE ID=6;   -- INC
UPDATE `mydb`.`kurz` SET den=3, vyuka_od='10:30:00', vyuka_do='12:00:00' WHERE ID=7;   -- IOS
UPDATE `mydb`.`kurz` SET den=4, vyuka_od='11:00:00', vyuka_do='12:30:00' WHERE ID=8;   -- ITW
UPDATE `mydb`.`kurz` SET den=1, vyuka_od='14:00:00', vyuka_do='15:30:00' WHERE ID=9;   -- IIS
UPDATE `mydb`.`kurz` SET den=2, vyuka_od='13:00:00', vyuka_do='14:30:00' WHERE ID=10;  -- IDS
UPDATE `mydb`.`kurz` SET den=3, vyuka_od='13:00:00', vyuka_do='14:30:00' WHERE ID=11;  -- IPK
UPDATE `mydb`.`kurz` SET den=4, vyuka_od='14:00:00', vyuka_do='15:30:00' WHERE ID=12;  -- ISA
