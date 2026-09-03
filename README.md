# HCIS Payroll Presensi API (CodeIgniter 3)

REST API JSON untuk menerima data presensi dari Local Agent Python offline-first. API ini menggunakan project CodeIgniter 3 yang sudah ada, MySQL/MariaDB, dan Bearer Token.

## Role Aplikasi

Untuk tahap awal perusahaan kecil, aplikasi menggunakan satu role aktif: `ADMIN`. Admin mengelola master karyawan, attendance, payroll, kasbon, laporan, dan setting. Kolom `level` pada `tbl_user` adalah peninggalan aplikasi lama dan tidak digunakan.

## Kebutuhan

- PHP 7.4 atau PHP 8.x dengan ekstensi `mysqli`
- MySQL/MariaDB
- Apache dengan `mod_rewrite` (XAMPP)
- CodeIgniter 3.x

## Instalasi di XAMPP

1. Salin project ke `D:\xampp\htdocs\devel\payroll_app` atau folder web server lain.
2. Buat database, misalnya:

```sql
CREATE DATABASE payroll_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. Jalankan [`database.sql`](database.sql) pada database tersebut melalui phpMyAdmin atau MySQL CLI.
4. Atur koneksi di [`application/config/database.php`](application/config/database.php):

```php
'database' => 'payroll_db',
'username' => 'root',
'password' => '',
```

5. Atur token di [`application/config/config.php`](application/config/config.php). Untuk production, gunakan environment variable `API_TOKEN` dari Apache/server sehingga token tidak tersimpan di source code.
6. Aktifkan Apache `mod_rewrite` dan pastikan `AllowOverride All` aktif untuk folder project.
7. Sesuaikan `RewriteBase` pada [`.htaccess`](.htaccess) dengan lokasi folder. Untuk instalasi ini nilainya `/devel/payroll_app/`.

`database.sql` aman dijalankan ulang. File tersebut menambahkan layer employee, mapping fingerprint, shift, `attendance_daily`, adjustment, payroll, kasbon ledger, audit log, dan `tbl_user` kompatibel dengan login CI3. Tabel `presensi` tetap menjadi raw source dan tidak diubah oleh attendance engine.

## Endpoint

Base URL instalasi ini: `http://localhost/devel/payroll_app`

- `GET /health` tanpa token
- `POST /api/v1/presensi/upload` dengan Bearer Token
- `GET /api/v1/presensi?page=1&limit=50&user_id=EMP-1001&start_date=2026-08-01&end_date=2026-08-31`
- `GET /api/v1/presensi/{id}` dengan Bearer Token
- `GET /admin/employees` untuk master karyawan (login admin)
- `GET /admin/attendance` untuk rekap attendance (login admin)
- `POST /admin/attendance/process` untuk memproses raw punch ke `attendance_daily`

Jika rewrite belum aktif, gunakan `index.php`, contohnya `/index.php/api/v1/presensi`.

## Health check

```powershell
Invoke-RestMethod -Uri "http://localhost/devel/payroll_app/health" -Method Get
```

Response:

```json
{ "status": "ok" }
```

## Upload dengan PowerShell

```powershell
$headers = @{
    Authorization = "Bearer change-this-token"
    "Content-Type" = "application/json"
}

$body = @{
    user_id = "EMP-1001"
    timestamp = "2026-08-25 14:30:00"
    device_sn = "U4500-SIMULATOR-001"
    template_hash = "test-reference"
    client_event_id = "test-event-001"
} | ConvertTo-Json

Invoke-RestMethod `
    -Uri "http://localhost/devel/payroll_app/api/v1/presensi/upload" `
    -Method Post `
    -Headers $headers `
    -Body $body
```

Request kedua dengan `client_event_id` yang sama mengembalikan HTTP 200 dan record lama. Request pertama mengembalikan HTTP 201.

Contoh response sukses:

```json
{
	"success": true,
	"message": "Attendance uploaded successfully",
	"data": {
		"id": 1,
		"user_id": "EMP-1001",
		"timestamp": "2026-08-25 14:30:00",
		"device_sn": "U4500-SIMULATOR-001",
		"client_event_id": "test-event-001"
	}
}
```

Error memakai format berikut: `{"success":false,"message":"Error description"}` dengan status 400, 401, 404, 422, atau 500 sesuai kasus. Token tidak pernah ditulis ke log dan fingerprint mentah tidak diterima atau disimpan.

## Perubahan Local Agent

Payload lama perlu menambahkan ID unik per event sebelum dikirim. ID tersebut harus tetap sama ketika retry event yang sama, misalnya dibuat saat record presensi lokal dibuat:

```python
import uuid

payload = {
    "user_id": user_id,
    "timestamp": timestamp,
    "device_sn": device_sn,
    "template_hash": template_hash,
    "client_event_id": local_row["client_event_id"] or str(uuid.uuid4()),
}
```

Simpan `client_event_id` ke SQLite lokal sebelum melakukan retry. Dengan begitu API dapat membedakan event baru dan pengiriman ulang.

## Attendance Engine

Jika `presensi.user_id` bernilai seperti `EMP-1001`, engine mencocokkannya ke `employees.employee_code`. Untuk user ID mesin, gunakan `employee_fingerprint_mappings`. Atur shift dan assignment employee sebelum memilih **Proses Attendance**. Engine membaca FIRST dan LAST punch per employee pada tanggal yang dipilih, menghitung toleransi keterlambatan, pulang cepat, dan lembur minimum, lalu menyimpan hasil ke `attendance_daily`. Employee tanpa mapping tidak diproses dan raw punch tetap dipertahankan.

## Pengujian

Uji manual dapat dilakukan melalui PowerShell, Postman, curl, atau endpoint CI3. PHPUnit belum tersedia pada project lama ini; lint PHP dapat dijalankan dengan:

```powershell
D:\xampp\php\php.exe -l application\controllers\api\Presensi.php
```
