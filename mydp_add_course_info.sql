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

USE mydb;



-- >>> ==============================================================
-- >>> ==============================================================
-- >>> ==============================================================
-- >>> SKRIPT PRO NAHRANI UKAZKOVYCH DAT K ZOBRAZENI HODNOCENI STUDENTA
-- >>> ==============================================================
-- >>> ==============================================================


USE mydb;
-- >>> UPRAV: ID studenta, kterému chceš přidat známky
SET @student_id := 7;

-- 1) výchozí místnost (kvůli FK v tabulce termin)
INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'A105', 0, 'Default room', 100
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='A105');

-- 2) vytvoř pár termínů pro tři kurzy (IIS=9, IUS=4, IDS=10)
-- typ: 0=Přednáška, 1=Cvičení, 2=Zkouška

-- IIS (ID=9)
INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IIS – Přednáška 1', 0, '2025-11-15 10:00:00', 'Úvod', 0, 200, 9, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=9 AND typ=0 AND nazev='IIS – Přednáška 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IIS – Cvičení 1', 1, '2025-11-17 14:00:00', 'Základy', 0, 30, 9, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=9 AND typ=1 AND nazev='IIS – Cvičení 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IIS – Zkouška', 2, '2025-12-10 09:00:00', 'Finální test', 0, 200, 9, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=9 AND typ=2 AND nazev='IIS – Zkouška');

-- IUS (ID=4)
INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IUS – Přednáška 1', 0, '2025-11-16 09:00:00', 'Úvod', 0, 200, 4, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=4 AND typ=0 AND nazev='IUS – Přednáška 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IUS – Cvičení 1', 1, '2025-11-18 13:30:00', 'Use case', 0, 30, 4, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=4 AND typ=1 AND nazev='IUS – Cvičení 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IUS – Zkouška', 2, '2025-12-12 10:00:00', 'Test', 0, 200, 4, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=4 AND typ=2 AND nazev='IUS – Zkouška');

-- IDS (ID=10)
INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IDS – Přednáška 1', 0, '2025-11-19 11:00:00', 'ER model', 0, 200, 10, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=10 AND typ=0 AND nazev='IDS – Přednáška 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IDS – Cvičení 1', 1, '2025-11-20 12:30:00', 'SQL základy', 0, 30, 10, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=10 AND typ=1 AND nazev='IDS – Cvičení 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IDS – Zkouška', 2, '2025-12-15 09:00:00', 'Finální test', 0, 200, 10, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=10 AND typ=2 AND nazev='IDS – Zkouška');

-- 3) zapiš studenta do těchto kurzů (pokud není)
INSERT INTO student_navstevuje_kurz (uzivatel_ID, kurz_ID)
SELECT @student_id, k.ID FROM kurz k
WHERE k.ID IN (9,4,10)
  AND NOT EXISTS (
    SELECT 1 FROM student_navstevuje_kurz s
    WHERE s.uzivatel_ID=@student_id AND s.kurz_ID=k.ID
  );

-- 4) přidej ukázkové body do uzivatel_ma_hodnoceni (1 záznam pro každý termín výše)
-- IIS
INSERT INTO uzivatel_ma_hodnoceni (uzivatel_ID, termin_ID, body, datum)
SELECT @student_id, t.ID, 15, NOW()
FROM termin t
WHERE t.kurz_ID=9 AND t.nazev IN ('IIS – Přednáška 1','IIS – Cvičení 1','IIS – Zkouška')
  AND NOT EXISTS (
    SELECT 1 FROM uzivatel_ma_hodnoceni u
    WHERE u.uzivatel_ID=@student_id AND u.termin_ID=t.ID
  );

-- IUS
INSERT INTO uzivatel_ma_hodnoceni (uzivatel_ID, termin_ID, body, datum)
SELECT @student_id, t.ID, 10, NOW()
FROM termin t
WHERE t.kurz_ID=4 AND t.nazev IN ('IUS – Přednáška 1','IUS – Cvičení 1','IUS – Zkouška')
  AND NOT EXISTS (
    SELECT 1 FROM uzivatel_ma_hodnoceni u
    WHERE u.uzivatel_ID=@student_id AND u.termin_ID=t.ID
  );

-- IDS
INSERT INTO uzivatel_ma_hodnoceni (uzivatel_ID, termin_ID, body, datum)
SELECT @student_id, t.ID, 12, NOW()
FROM termin t
WHERE t.kurz_ID=10 AND t.nazev IN ('IDS – Přednáška 1','IDS – Cvičení 1','IDS – Zkouška')
  AND NOT EXISTS (
    SELECT 1 FROM uzivatel_ma_hodnoceni u
    WHERE u.uzivatel_ID=@student_id AND u.termin_ID=t.ID
  );
