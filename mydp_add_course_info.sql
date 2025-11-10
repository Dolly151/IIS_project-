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

/* =======================================================================
   >>> DOPLŇKY SCHÉMATU + UKÁZKOVÁ DATA (bez IF NOT EXISTS)
   ======================================================================= */
USE mydb;

-- --- přidej mistnost_ID do kurz (pokud chybí) a povol NULL ---
SET @col_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME='kurz' AND COLUMN_NAME='mistnost_ID'
);
SET @sql := IF(@col_exists=0, 'ALTER TABLE kurz ADD COLUMN mistnost_ID INT NULL AFTER garant_ID;', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
ALTER TABLE kurz MODIFY COLUMN mistnost_ID INT NULL;

-- --- unikátní název místnosti (idempotentně) ---
SET @idx_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
  WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='mistnost' AND INDEX_NAME='uq_mistnost_nazev'
);
SET @sql := IF(@idx_exists=0, 'ALTER TABLE mistnost ADD UNIQUE KEY uq_mistnost_nazev (nazev);', 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- --- FK: kurz(mistnost_ID) -> mistnost(ID) s ON DELETE SET NULL ---
SET @fk_name := 'fk_kurz_mistnost';
SET @fk_exists := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA=DATABASE() AND CONSTRAINT_NAME=@fk_name AND TABLE_NAME='kurz'
);
SET @sql := IF(@fk_exists=1, CONCAT('ALTER TABLE kurz DROP FOREIGN KEY ', @fk_name, ';'), 'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @fk_exists2 := (
  SELECT COUNT(*) FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
  WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME='kurz' AND REFERENCED_TABLE_NAME='mistnost'
);
SET @sql := IF(@fk_exists2=0,
  'ALTER TABLE kurz
     ADD CONSTRAINT fk_kurz_mistnost
     FOREIGN KEY (mistnost_ID) REFERENCES mistnost(ID)
     ON UPDATE CASCADE ON DELETE SET NULL;',
  'SELECT 1');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- ======================================================================
-- UKÁZKOVÉ MÍSTNOSTI (vkládá jen chybějící)
-- ======================================================================
INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'A105', 0, 'Přednášková aula', 120
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='A105');

INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'E112', 1, 'Cvičebna', 40
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='E112');

INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'Q301', 2, 'Počítačová učebna', 30
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='Q301');

INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'B203', 1, 'Cvičebna', 50
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='B203');

INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'D020', 2, 'PC lab - Linux', 28
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='D020');

INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'F106', 0, 'Velká aula', 180
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='F106');

INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'C115', 2, 'PC lab - Windows', 32
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='C115');

INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'H014', 1, 'Seminární místnost', 25
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='H014');

-- ======================================================================
-- PŘIŘAZENÍ LEKTORA (uzivatel_ID = 6) KE KURZŮM DLE ZKRATKY
-- ======================================================================
SET @teacher_id := 6;

INSERT INTO lektor_uci_v_kurzu (uzivatel_ID, kurz_ID)
SELECT @teacher_id, k.ID FROM kurz k
WHERE k.zkratka='IIS'
  AND NOT EXISTS (SELECT 1 FROM lektor_uci_v_kurzu l WHERE l.uzivatel_ID=@teacher_id AND l.kurz_ID=k.ID);

INSERT INTO lektor_uci_v_kurzu (uzivatel_ID, kurz_ID)
SELECT @teacher_id, k.ID FROM kurz k
WHERE k.zkratka='IPK'
  AND NOT EXISTS (SELECT 1 FROM lektor_uci_v_kurzu l WHERE l.uzivatel_ID=@teacher_id AND l.kurz_ID=k.ID);

INSERT INTO lektor_uci_v_kurzu (uzivatel_ID, kurz_ID)
SELECT @teacher_id, k.ID FROM kurz k
WHERE k.zkratka='ISA'
  AND NOT EXISTS (SELECT 1 FROM lektor_uci_v_kurzu l WHERE l.uzivatel_ID=@teacher_id AND l.kurz_ID=k.ID);

INSERT INTO lektor_uci_v_kurzu (uzivatel_ID, kurz_ID)
SELECT @teacher_id, k.ID FROM kurz k
WHERE k.zkratka='ITW'
  AND NOT EXISTS (SELECT 1 FROM lektor_uci_v_kurzu l WHERE l.uzivatel_ID=@teacher_id AND l.kurz_ID=k.ID);

INSERT INTO lektor_uci_v_kurzu (uzivatel_ID, kurz_ID)
SELECT @teacher_id, k.ID FROM kurz k
WHERE k.zkratka='IDS'
  AND NOT EXISTS (SELECT 1 FROM lektor_uci_v_kurzu l WHERE l.uzivatel_ID=@teacher_id AND l.kurz_ID=k.ID);

-- ======================================================================
-- TERMÍNY + HODNOCENÍ DEMO (student @student_id)
-- ======================================================================
SET @student_id := 7;

-- výchozí místnost pro termíny
INSERT INTO mistnost (nazev, typ, popis, kapacita)
SELECT 'A105', 0, 'Default room', 100
WHERE NOT EXISTS (SELECT 1 FROM mistnost WHERE nazev='A105');

-- IIS (kurz_ID = 9)
INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IIS - Přednáška 1', 0, '2025-11-15 10:00:00', 'Úvod', 0, 200, 9, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=9 AND nazev='IIS - Přednáška 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IIS - Cvičení 1', 1, '2025-11-17 14:00:00', 'Základy', 0, 30, 9, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=9 AND nazev='IIS - Cvičení 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IIS - Zkouška', 2, '2025-12-10 09:00:00', 'Finální test', 0, 200, 9, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=9 AND nazev='IIS - Zkouška');

-- IUS (kurz_ID = 4)
INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IUS - Přednáška 1', 0, '2025-11-16 09:00:00', 'Úvod', 0, 200, 4, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=4 AND nazev='IUS - Přednáška 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IUS - Cvičení 1', 1, '2025-11-18 13:30:00', 'Use case', 0, 30, 4, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=4 AND nazev='IUS - Cvičení 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IUS - Zkouška', 2, '2025-12-12 10:00:00', 'Test', 0, 200, 4, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=4 AND nazev='IUS - Zkouška');

-- IDS (kurz_ID = 10)
INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IDS - Přednáška 1', 0, '2025-11-19 11:00:00', 'ER model', 0, 200, 10, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=10 AND nazev='IDS - Přednáška 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IDS - Cvičení 1', 1, '2025-11-20 12:30:00', 'SQL základy', 0, 30, 10, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=10 AND nazev='IDS - Cvičení 1');

INSERT INTO termin (nazev, typ, datum, popis, hodnoceni, kapacita, kurz_ID, mistnost_ID)
SELECT 'IDS - Zkouška', 2, '2025-12-15 09:00:00', 'Finální test', 0, 200, 10, (SELECT ID FROM mistnost WHERE nazev='A105')
WHERE NOT EXISTS (SELECT 1 FROM termin WHERE kurz_ID=10 AND nazev='IDS - Zkouška');

-- zapiš studenta @student_id do kurzů 9,4,10 (pokud ještě není)
INSERT INTO student_navstevuje_kurz (uzivatel_ID, kurz_ID)
SELECT @student_id, k.ID FROM kurz k
WHERE k.ID IN (9,4,10)
  AND NOT EXISTS (SELECT 1 FROM student_navstevuje_kurz s WHERE s.uzivatel_ID=@student_id AND s.kurz_ID=k.ID);

-- přidej ukázkové body pro všechny výše založené termíny (jen pokud chybí)
INSERT INTO uzivatel_ma_hodnoceni (uzivatel_ID, termin_ID, body, datum)
SELECT @student_id, t.ID, 15, NOW()
FROM termin t
WHERE t.kurz_ID=9 AND t.nazev IN ('IIS - Přednáška 1','IIS - Cvičení 1','IIS - Zkouška')
  AND NOT EXISTS (SELECT 1 FROM uzivatel_ma_hodnoceni u WHERE u.uzivatel_ID=@student_id AND u.termin_ID=t.ID);

INSERT INTO uzivatel_ma_hodnoceni (uzivatel_ID, termin_ID, body, datum)
SELECT @student_id, t.ID, 10, NOW()
FROM termin t
WHERE t.kurz_ID=4 AND t.nazev IN ('IUS - Přednáška 1','IUS - Cvičení 1','IUS - Zkouška')
  AND NOT EXISTS (SELECT 1 FROM uzivatel_ma_hodnoceni u WHERE u.uzivatel_ID=@student_id AND u.termin_ID=t.ID);

INSERT INTO uzivatel_ma_hodnoceni (uzivatel_ID, termin_ID, body, datum)
SELECT @student_id, t.ID, 12, NOW()
FROM termin t
WHERE t.kurz_ID=10 AND t.nazev IN ('IDS - Přednáška 1','IDS - Cvičení 1','IDS - Zkouška')
  AND NOT EXISTS (SELECT 1 FROM uzivatel_ma_hodnoceni u WHERE u.uzivatel_ID=@student_id AND u.termin_ID=t.ID);
