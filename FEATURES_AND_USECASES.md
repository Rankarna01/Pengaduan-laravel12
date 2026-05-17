# Feature Guide & Use Cases

## 🎯 User Scenarios & Features

### Scenario 1: Masyarakat Membuat Laporan Pengaduan

**Flow Lengkap:**

1. **Register/Login**
   - Kunjungi `/register` atau `/login`
   - Input email, password, nama, nomor telepon
   - Sistem akan auto-login dan redirect ke member dashboard

2. **Akses Member Dashboard** (`/member/dashboard`)
   - Lihat statistik laporan pribadi
   - 5 laporan terbaru ditampilkan
   - Lihat perincian status laporan

3. **Membuat Laporan Pengaduan** (`/member/laporan` - GET)
   - Klik "Buat Laporan Baru"
   - Form akan memuncul dengan field:
     - **Kategori Laporan** (dropdown dari database)
     - **Judul Laporan** (contoh: "Jalan Rusak di Kampung A")
     - **Deskripsi Detail** (cerita lengkap masalahnya)
     - **Lokasi Kejadian** (alamat atau koordinat)
     - **Foto Kerusakan** (upload gambar bukti, max 5MB)
   
   - **Sistem Otomatis**:
     - Generate kode unik: `RPT-20260517-0001`
     - Upload foto ke folder `storage/app/public/reports/`
     - Set status awal: `PENDING` (menunggu)
     - Simpan ke database
   
   - **Notifikasi**:
     - Sistem mengirim notifikasi ke SEMUA admin
     - Admin akan tahu ada laporan baru masuk
     - Admin bisa lihat di notifikasi atau dashboard

4. **Tracking Laporan Pribadi** (`/member/laporan` - GET)
   - Lihat daftar semua laporan yang sudah dibuat
   - Lihat status real-time:
     - 🟡 **PENDING** = Menunggu admin proses
     - 🔵 **PROCESS** = Sedang diproses
     - 🟢 **COMPLETED** = Selesai/Diperbaiki
     - 🔴 **REJECTED** = Ditolak
   
   - **Lihat Response Admin**:
     - Klik laporan → lihat detail
     - Lihat pesan dari admin
     - Lihat foto bukti perbaikan (jika ada)
     - Lihat tanggal response

5. **Hapus Laporan** (Kondisi Khusus)
   - Hanya bisa hapus laporan dengan status `PENDING`
   - Jika sudah di-process, tidak bisa dihapus
   - Gunakan untuk membatalkan laporan yang salah buat

6. **Menerima Notifikasi**
   - Ketika admin update status laporan → notifikasi ke user
   - Ketika admin beri respons → notifikasi dengan pesan
   - User bisa lihat langsung status terbaru

---

### Scenario 2: Admin Mengelola Laporan

**Flow Lengkap:**

1. **Login Admin** (`/login`)
   - Email: admin@example.com (harus role: admin)
   - Password: sesuai yang di-setup
   - Auto redirect ke `/admin/dashboard`

2. **Dashboard Admin** (`/admin/dashboard`)
   - **Statistik Overview**:
     - Total laporan masuk
     - Breakdown per status
     - Total user & kategori
     - Percentage laporan yang sudah selesai
   
   - **Visualisasi Data**:
     - Chart laporan per bulan (6 bulan terakhir)
     - Chart laporan per kategori
     - Table 8 laporan terbaru dengan detail user

3. **Kelola Laporan** (`/admin/laporan`)
   - **List View**:
     - Tampilkan semua laporan
     - Filter berdasarkan status (pending/process/completed/rejected)
     - Search laporan berdasarkan title atau report code
     - Pagination 10/halaman
   
   - **Detail Laporan** (Klik detail):
     - Info pelapor (nama, email, nomor telepon)
     - Judul & deskripsi laporan
     - Lokasi kejadian
     - Foto kerusakan (dengan link bisa diunduh)
     - Kategori laporan
     - Report code unik
     - **Response History**:
       - Semua response dari admin sebelumnya
       - Siapa yang respons (nama admin)
       - Tanggal & waktu response
       - Message yang dikirim
       - Foto bukti perbaikan (jika ada)

4. **Update Status Laporan** (AJAX)
   - Status yang bisa dipilih:
     - `pending` → `process` → `completed` atau `rejected`
   - Saat update status:
     - Otomatis notifikasi ke pelapor
     - Catatan status disimpan di database
   
   - **Use Cases**:
     - `pending` → `process`: Laporan sudah diterima, mulai proses
     - `process` → `completed`: Perbaikan selesai, bukti foto sudah diambil
     - `pending` → `rejected`: Laporan tidak valid/tidak bisa ditangani
     - `process` → `rejected`: Perbaikan dibatalkan

5. **Tambah Response/Tanggapan** (Dokumen Perbaikan)
   - **Input**:
     - Message (contoh: "Jalan sudah kami perbaiki tanggal 15 Mei")
     - Foto Perbaikan (bukti visual, max 5MB)
     - Status baru laporan (pending/process/completed/rejected)
   
   - **Otomatis**:
     - Upload foto ke `storage/app/public/responses/`
     - Simpan response ke database
     - Update status laporan
     - Notifikasi ke pelapor dengan pesan & foto bukti
   
   - **Use Case**:
     - Admin kirim pesan: "Jalan sudah diperbaiki"
     - Admin upload foto: Foto jalan yang sudah diperbaiki
     - Set status: "completed"
     - Pelapor mendapat notifikasi + bisa lihat bukti foto

6. **Kelola Kategori** (`/admin/kategori`)
   - **CRUD Kategori**:
     - Tambah kategori baru (contoh: Jalan Rusak, Air Bersih, dll)
     - Edit nama kategori
     - Hapus kategori (jika tidak ada laporan)
     - Lihat jumlah laporan per kategori
   
   - **Icon**:
     - Setiap kategori bisa punya icon/emoji
     - Tampil di form laporan & dashboard

7. **Kelola User** (`/admin/pengguna`)
   - **List Masyarakat**:
     - Daftar semua user yang terdaftar
     - Filter berdasarkan nama/email
     - Lihat tanggal registrasi
   
   - **Create User Baru**:
     - Buat akun masyarakat manual
     - Input: nama, email, password, nomor telepon
     - Gunakan untuk create akun yang belum bisa self-register
   
   - **Edit User**:
     - Update nama, email, nomor telepon, password
   
   - **Delete User**:
     - Hapus akun & semua laporan terkait (cascade)

8. **Kelola Berita** (`/admin/berita`)
   - **Create Berita**:
     - Judul berita
     - Content lengkap (bisa HTML/text)
     - Kategori (Umum, Pengumuman, dll)
     - Upload gambar featured (max 3MB)
     - Publish atau Draft
   
   - **List Berita**:
     - Semua berita yang dibuat
     - Filter & search
     - Sort by latest
   
   - **Edit/Delete**:
     - Ubah konten berita
     - Hapus berita
   
   - **Public View** (`/berita`):
     - Hanya berita dengan `is_published=true` yang tampil
     - Paginate 9/halaman
     - Klik detail → lihat full content + related news

9. **Export Laporan PDF** (`/admin/export/reports-pdf`)
   - Tombol "Download PDF"
   - Sistem generate PDF landscape A4
   - Berisi: semua laporan + detail + responses
   - File: `laporan-kerusakan-2026-05-17.pdf`
   
   - **Use Case**:
     - Arsip laporan
     - Laporan ke pimpinan/camat
     - Dokumentasi bulanan/tahunan

10. **Pengaturan Sistem** (`/admin/pengaturan`)
    - Update logo aplikasi
    - Nama sistem
    - Deskripsi
    - Kontak email/telepon
    - Pengaturan lainnya
    - Semua setting tersimpan di table `settings`

11. **Notifikasi Admin** (`/admin/notifikasi`)
    - **List Notifikasi**:
      - Notifikasi laporan baru masuk
      - Pagination 10/halaman
      - Lihat notifikasi baru yang belum dibaca
    
    - **Baca Notifikasi**:
      - Klik notifikasi → marked as read
      - Auto redirect ke laporan terkait (filter by code)
      - Bisa langsung lihat laporan yang notified
    
    - **Mark All as Read**:
      - Tandai semua notifikasi sudah dibaca
      - Clearing notification inbox

---

## 💡 Advanced Features

### 1. Report Code Generation
```
Format: RPT-YYYYMMDD-XXXX
Contoh: RPT-20260517-0001, RPT-20260517-0002

Logika:
- RPT = Report prefix
- YYYYMMDD = Tanggal (20260517 = 17 Mei 2026)
- XXXX = Auto increment per hari (4 digit, padded with 0)

Keuntungan:
- Unik dan tidak bisa duplikat
- Readable untuk laporan tersusun per hari
- Bisa tracking dari report code berapa banyak laporan masuk per hari
```

### 2. Photo Upload & Storage
```
Directory Structure:
storage/app/public/
├─ reports/
│  ├─ wHxY1234567890.jpg (foto kerusakan)
│  └─ aB2c3456789012.png
├─ responses/
│  ├─ xY1234567890ab.jpg (foto perbaikan)
│  └─ zZ9876543210cd.png
├─ news/
│  └─ image_id_timestamp.jpg
└─ settings/
   └─ logo_id_timestamp.png

File Naming:
- Laravel auto-generate hash filename
- Original extension tetap
- Secure: file path di database, URL dari asset()

Access:
- Via: asset('storage/reports/filename.jpg')
- Note: public/storage harus symlink ke storage/app/public
```

### 3. Notification System
```
Type 1: NewReportNotification
├─ Trigger: User submit laporan baru
├─ Recipient: Semua admin
├─ Data: {report_code, title, user_name, user_email}
├─ Storage: Database (notifications table)
├─ UI: Badge di admin.notifikasi
└─ Action: Klik → redirect ke laporan

Type 2: ReportStatusUpdatedNotification
├─ Trigger: Admin update status OR add response
├─ Recipient: User pelapor
├─ Data: {report_code, new_status, response_message}
├─ Storage: Database (notifications table)
└─ Use: User bisa track status laporan mereka real-time
```

### 4. Role-Based Access Control
```
Routes Protection:
┌─────────────────────────────────────────┐
│ Public (No Auth)                         │
├─────────────────────────────────────────┤
│ GET /                                    │
│ GET /berita, /berita/{slug}             │
│ GET /login, /register (if guest)        │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Member Routes (auth + role:masyarakat)  │
├─────────────────────────────────────────┤
│ GET /member/dashboard                   │
│ GET /member/laporan*                    │
│ POST /member/laporan (create)           │
│ DELETE /member/laporan/{id} (pending)   │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ Admin Routes (auth + role:admin)        │
├─────────────────────────────────────────┤
│ GET /admin/* (all admin endpoints)      │
│ POST /admin/* (CRUD operations)         │
│ PATCH /admin/laporan/{id}/status        │
│ DELETE /admin/* (delete operations)     │
└─────────────────────────────────────────┘

Enforcement:
- Middleware 'role:masyarakat' → abort 403 if not
- Middleware 'role:admin' → abort 403 if not
- Model ownership check: if (report.user_id != auth()->id()) abort 403
```

### 5. Search & Filter Features
```
Report Filter (Admin):
├─ Status filter:
│  ├─ pending: WHERE status = 'pending'
│  ├─ process: WHERE status = 'process'
│  ├─ completed: WHERE status = 'completed'
│  └─ rejected: WHERE status = 'rejected'
└─ Search: WHERE title LIKE '%keyword%' OR code LIKE '%keyword%'

User Search (Admin):
├─ Search: WHERE name LIKE '%keyword%' OR email LIKE '%keyword%'
└─ Filter: WHERE role = 'masyarakat'

News Search (Admin):
└─ Search: WHERE title LIKE '%keyword%'
```

### 6. Dashboard Analytics
```
Member Dashboard Stats:
├─ Total reports (COUNT)
├─ Pending (WHERE status='pending')
├─ Process (WHERE status='process')
├─ Completed (WHERE status='completed')
├─ Rejected (WHERE status='rejected')
└─ Latest 5 reports

Admin Dashboard Stats:
├─ Total reports
├─ Per status breakdown
├─ Total users (masyarakat)
├─ Total categories
├─ Total news
├─ Percent completed (completed/total * 100)
├─ Charts:
│  ├─ Line chart: laporan per bulan (6 bulan)
│  └─ Bar chart: laporan per kategori
├─ Latest 8 reports table
└─ Real-time updates possible with polling/websocket

Home Dashboard (Public):
├─ Total reports
├─ Per status breakdown
├─ Percent completed
├─ Bar chart: laporan per kategori
└─ Line chart: laporan per bulan
```

### 7. Cascade Delete & Data Integrity
```
When DELETE User (role:masyarakat):
├─ user record → DELETE
├─ user.reports → DELETE (cascade)
│  └─ report.responses → DELETE (cascade)
└─ Total impact: User + all reports + all responses gone

When DELETE Report:
├─ report record → DELETE
└─ report.responses → DELETE (cascade)

When DELETE Category:
├─ category record → DELETE
└─ category.reports → DELETE (cascade)

This ensures referential integrity in database.
```

### 8. Form Validation & Error Handling
```
Frontend:
├─ HTML5 required, type, max-length
└─ JavaScript validation (AJAX form)

Backend (Laravel):
├─ request->validate()
├─ Custom validation messages in Indonesian
├─ Return 422 Unprocessable Entity if failed
├─ JSON error response with field-level errors
└─ Example:
   {
     "message": "Validation failed",
     "errors": {
       "category_id": ["Kategori wajib dipilih."],
       "title": ["Judul laporan wajib diisi."],
       "photo_damage": ["Ukuran foto maksimal 5MB."]
     }
   }
```

---

## 🎨 UI/UX Features

### 1. Responsive Design
- Mobile-first approach
- Tablet & desktop optimized
- Touch-friendly buttons/forms
- Readable typography

### 2. Status Badges with Colors
```
🟡 PENDING  (Yellow)    = Menunggu
🔵 PROCESS  (Blue)      = Sedang Diproses
🟢 COMPLETED (Green)    = Selesai
🔴 REJECTED (Red)       = Ditolak
```

### 3. User Avatars
```
Generated via: https://ui-avatars.com/api/
- If user.avatar exists: use uploaded avatar
- Else: auto-generate from user name
- Example: ?name=John+Doe&background=2563eb&color=fff
```

### 4. Pagination
```
List Views:
├─ Member reports: 10/page
├─ Admin reports: 10/page
├─ Admin users: 10/page
├─ Admin categories: 10/page
├─ Admin news: 10/page
├─ Admin notifications: 10/page
├─ Public news: 9/page
└─ Laravel paginate() with withQueryString()
```

### 5. AJAX Forms
```
Implementation:
├─ Form submit via AJAX
├─ No page reload
├─ Real-time response
├─ JSON response parsing
├─ Error handling & display
└─ Success notifications

Endpoints:
├─ POST /member/laporan (create report)
├─ POST /admin/kategori (create category)
├─ POST /admin/berita (create news)
├─ POST /admin/pengguna (create user)
├─ PATCH /admin/laporan/{id}/status (update status)
└─ POST /admin/laporan/{id}/response (add response)
```

---

## 📋 Workflow Examples

### Example 1: Complete Report Workflow

```
Day 1:
├─ 09:00 Masyarakat A membuat laporan "Jalan Rusak di Dusun B"
├─ 09:05 Admin notified → see 1 new report
├─ 10:00 Admin view report details + photos
└─ 10:15 Admin update status PENDING → PROCESS

Day 2:
├─ 08:00 Admin A mengunjungi lokasi
├─ 14:00 Admin A start repair pekerjaan
└─ 16:00 Admin A add response:
   ├─ Message: "Jalan sudah mulai perbaikan"
   ├─ Photo: foto tempat kerja
   └─ Status: PROCESS (tetap)
   → Masyarakat A notified about response

Day 3:
├─ 10:00 Admin A finish repair
├─ 10:30 Admin A add response:
   ├─ Message: "Perbaikan jalan selesai, mohon verifikasi"
   ├─ Photo: foto jalan yang sudah diperbaiki
   └─ Status: COMPLETED
   → Masyarakat A notified: COMPLETED with photo proof

Result:
├─ Masyarakat A puas: lihat status, lihat bukti foto
├─ Admin: punya documentation lengkap
└─ System: recorded full history
```

### Example 2: Search & Export Workflow

```
Admin Monthly Report Task:
├─ Go to /admin/laporan
├─ Filter: status = COMPLETED
├─ Search: category = JALAN_RUSAK
├─ Get 25 completed reports
├─ Go to /admin/export/reports-pdf
├─ Download: laporan-kerusakan-2026-05-17.pdf
├─ PDF contains: all reports + details + photos
└─ Report to camat/pimpinan
```

### Example 3: User Management Workflow

```
New User Onboarding:
├─ User visits /register
├─ Fill form: name, email, phone, password
├─ Submit → auto login
├─ Redirect to /member/dashboard
├─ See empty dashboard (no reports yet)
├─ Click "Buat Laporan Baru"
├─ Fill report form
├─ Submit → report created
├─ See report in dashboard with status PENDING
└─ Wait for admin response

User Management (Admin):
├─ Go to /admin/pengguna
├─ View all users
├─ Add new user manually:
   ├─ Name: Budi Santoso
   ├─ Email: budi@example.com
   ├─ Password: hash-generated
   └─ Role: masyarakat
├─ Edit user data
├─ Delete user (cascade all reports)
```

---

## 🔒 Security Features

### 1. Password Security
- Hash dengan bcrypt (BCRYPT_ROUNDS=12)
- Validation: min 8 characters
- Only hashed stored in DB
- Never in logs or responses

### 2. CSRF Protection
- Laravel middleware: VerifyCsrfToken
- CSRF token in all forms
- X-CSRF-TOKEN header for AJAX

### 3. Authorization
- Role-based middleware
- Model ownership checks
- abort(403) for unauthorized access
- Middleware precedence: guest, auth, role

### 4. File Upload Security
- Whitelist extensions: jpg, jpeg, png, webp
- Max file sizes enforced: 5MB reports, 3MB news
- Stored in private directory
- Served via storage facade (no direct access)

### 5. SQL Injection Prevention
- Eloquent ORM (parameterized queries)
- Validated input before query
- Prepared statements everywhere

### 6. XSS Prevention
- Blade template auto-escaping
- Input validation & sanitization
- No raw HTML in user input (except admin news content)

---

*Documentation created: May 17, 2026*
*For development reference and training purposes*
