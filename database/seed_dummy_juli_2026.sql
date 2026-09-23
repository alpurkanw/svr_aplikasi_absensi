USE payroll_db;
SET FOREIGN_KEY_CHECKS = 1;
START TRANSACTION;

INSERT INTO employees (employee_code, nik, name, email, join_date, position_name, department_name, employment_status, is_active, bank_account_number, bank_name, payroll_status) VALUES
('EMP-0001','3270000000000001','Andi Pratama','emp0001@example.test','2025-01-01','Staff Finance','Finance','TETAP',1,'12345678901','BCA',1),
('EMP-0002','3270000000000002','Budi Santoso','emp0002@example.test','2025-01-01','Staff Operasional','Operasional','TETAP',1,'12345678902','BCA',1),
('EMP-0003','3270000000000003','Citra Lestari','emp0003@example.test','2025-01-01','Staff HR','HR','KONTRAK',1,'12345678903','BCA',1),
('EMP-0004','3270000000000004','Dedi Kurniawan','emp0004@example.test','2025-01-01','Staff IT','IT','TETAP',1,'12345678904','BCA',1),
('EMP-0005','3270000000000005','Eka Putri','emp0005@example.test','2025-01-01','Staff Operasional','Operasional','HARIAN',1,'12345678905','BCA',1),
('EMP-0006','3270000000000006','Fajar Hidayat','emp0006@example.test','2025-01-01','Staff Sales','Sales','TETAP',1,'12345678906','BCA',1),
('EMP-0007','3270000000000007','Gita Maharani','emp0007@example.test','2025-01-01','Staff Finance','Finance','KONTRAK',1,'12345678907','BCA',1),
('EMP-0008','3270000000000008','Hendra Wijaya','emp0008@example.test','2025-01-01','Staff Gudang','Gudang','TETAP',1,'12345678908','BCA',1),
('EMP-0009','3270000000000009','Indah Permata','emp0009@example.test','2025-01-01','Staff HR','HR','TETAP',1,'12345678909','BCA',1),
('EMP-0010','3270000000010000','Joko Susilo','emp0010@example.test','2025-01-01','Staff Sales','Sales','HARIAN',1,'12345678910','BCA',1)
ON DUPLICATE KEY UPDATE name=VALUES(name), email=VALUES(email), department_name=VALUES(department_name), employment_status=VALUES(employment_status), is_active=1;

INSERT INTO work_shifts (name, check_in_time, check_out_time, late_tolerance_minutes, minimum_overtime_minutes, early_leave_enabled, work_days)
SELECT 'Shift Normal','08:00:00','17:00:00',10,30,1,'1,2,3,4,5'
WHERE NOT EXISTS (SELECT 1 FROM work_shifts WHERE name='Shift Normal');
INSERT INTO work_shifts (name, check_in_time, check_out_time, late_tolerance_minutes, minimum_overtime_minutes, early_leave_enabled, work_days)
SELECT 'Shift Siang','09:00:00','18:00:00',10,30,1,'1,2,3,4,5'
WHERE NOT EXISTS (SELECT 1 FROM work_shifts WHERE name='Shift Siang');

INSERT IGNORE INTO employee_fingerprint_mappings (employee_id, fingerprint_user_id, device_sn)
SELECT id, CONCAT('FP-',LPAD(SUBSTRING(employee_code,5),4,'0')), 'U4500-SIMULATOR-001'
FROM employees WHERE employee_code LIKE 'EMP-00%';
INSERT IGNORE INTO employee_shift_assignments (employee_id, shift_id, effective_from)
SELECT e.id,s.id,'2026-01-01' FROM employees e CROSS JOIN work_shifts s
WHERE e.employee_code LIKE 'EMP-00%' AND s.name='Shift Normal'
AND NOT EXISTS (SELECT 1 FROM employee_shift_assignments x WHERE x.employee_id=e.id AND x.effective_from='2026-01-01');

INSERT IGNORE INTO payroll_components (code,name,component_type,calculation_type) VALUES
('TUNJ_TETAP','Tunjangan Tetap','EARNING','FIXED'),
('TUNJ_TRANSPORT','Tunjangan Transport','EARNING','FIXED'),
('LEMBUR','Lembur','EARNING','PER_MINUTE'),
('POT_TELAT','Potongan Keterlambatan','DEDUCTION','RANGE'),
('POT_ABSEN','Potongan Absensi','DEDUCTION','FIXED'),
('POT_KASBON','Potongan Kasbon','DEDUCTION','FIXED');
INSERT INTO employee_payroll_components (employee_id,component_id,amount,effective_from)
SELECT e.id,c.id,500000,'2026-01-01' FROM employees e JOIN payroll_components c ON c.code='TUNJ_TETAP'
WHERE e.employee_code LIKE 'EMP-00%' AND NOT EXISTS (SELECT 1 FROM employee_payroll_components x WHERE x.employee_id=e.id AND x.component_id=c.id AND x.effective_from='2026-01-01');
INSERT INTO employee_payroll_components (employee_id,component_id,amount,effective_from)
SELECT e.id,c.id,300000,'2026-01-01' FROM employees e JOIN payroll_components c ON c.code='TUNJ_TRANSPORT'
WHERE e.employee_code LIKE 'EMP-00%' AND NOT EXISTS (SELECT 1 FROM employee_payroll_components x WHERE x.employee_id=e.id AND x.component_id=c.id AND x.effective_from='2026-01-01');

DROP PROCEDURE IF EXISTS seed_dummy_attendance;
DELIMITER //
CREATE PROCEDURE seed_dummy_attendance()
BEGIN
    DECLARE current_date_value DATE DEFAULT '2026-07-01';
    DECLARE employee_number INT DEFAULT 1;
    DECLARE code_value VARCHAR(20);
    DECLARE in_value DATETIME;
    DECLARE out_value DATETIME;
    DECLARE late_value INT;
    DECLARE overtime_value INT;
    WHILE current_date_value <= '2026-07-31' DO
        IF DAYOFWEEK(current_date_value) BETWEEN 2 AND 6 THEN
            SET employee_number = 1;
            WHILE employee_number <= 10 DO
                SET code_value = CONCAT('EMP-',LPAD(employee_number,4,'0'));
                SET in_value = DATE_ADD(CONCAT(current_date_value,' 08:00:00'), INTERVAL ((employee_number * 2) + MOD(DAY(current_date_value),3)) MINUTE);
                SET out_value = DATE_ADD(CONCAT(current_date_value,' 17:00:00'), INTERVAL (MOD(employee_number,4) * 10 + MOD(DAY(current_date_value),4) * 3) MINUTE);
                SET late_value = GREATEST(0, TIMESTAMPDIFF(MINUTE, CONCAT(current_date_value,' 08:10:00'), in_value));
                SET overtime_value = IF(TIMESTAMPDIFF(MINUTE, CONCAT(current_date_value,' 17:00:00'), out_value) >= 30, TIMESTAMPDIFF(MINUTE, CONCAT(current_date_value,' 17:00:00'), out_value), 0);
                INSERT IGNORE INTO presensi (user_id, timestamp, device_sn, template_hash, client_event_id) VALUES
                    (code_value,in_value,'U4500-SIMULATOR-001',CONCAT('dummy-hash-',code_value),CONCAT('dummy-',code_value,'-',DATE_FORMAT(current_date_value,'%Y%m%d'),'-in')),
                    (code_value,out_value,'U4500-SIMULATOR-001',CONCAT('dummy-hash-',code_value),CONCAT('dummy-',code_value,'-',DATE_FORMAT(current_date_value,'%Y%m%d'),'-out'));
                INSERT IGNORE INTO attendance_daily (employee_id,attendance_date,shift_id,check_in,check_out,late_minutes,early_leave_minutes,overtime_minutes,attendance_status,source,processed_at)
                SELECT e.id,current_date_value,s.id,in_value,out_value,late_value,0,overtime_value,IF(late_value > 0,'TERLAMBAT','HADIR'),'DUMMY','2026-08-25 10:00:00'
                FROM employees e JOIN employee_shift_assignments a ON a.employee_id=e.id JOIN work_shifts s ON s.id=a.shift_id
                WHERE e.employee_code COLLATE utf8mb4_unicode_ci = code_value COLLATE utf8mb4_unicode_ci;
                SET employee_number = employee_number + 1;
            END WHILE;
        END IF;
        SET current_date_value = DATE_ADD(current_date_value, INTERVAL 1 DAY);
    END WHILE;
END//
DELIMITER ;
CALL seed_dummy_attendance();
DROP PROCEDURE IF EXISTS seed_dummy_attendance;

COMMIT;
