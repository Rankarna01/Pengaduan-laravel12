# Dokumentasi Sistem Pengaduan Masyarakat - Laravel 12

## 📋 Ringkasan Proyek

Sistem E-Pengaduan Desa adalah aplikasi web untuk mengelola laporan keluhan/pengaduan masyarakat. Sistem ini memiliki 2 role utama:
- **Masyarakat** (Pelapor): Dapat membuat, melihat, dan menghapus laporan pengaduan mereka
- **Admin** (Pengelola): Dapat mengelola semua laporan, kategori, user, berita, dan pengaturan sistem

---

## 🏗️ Struktur Database & Model Relationships

### Database Schema

```
Users (pengguna sistem)
├── id
├── name
├── email
├── password
├── role (admin / masyarakat)
├── phone
├── avatar
└── timestamps

Categories (kategori pengaduan)
├── id
├── name
├── slug
├── icon
└── timestamps

Reports (laporan pengaduan)
├── id
├── user_id (FK → Users)
├── category_id (FK → Categories)
├── code (unik: RPT-YYYYMMDD-XXXX)
├── title
├── description
├── location
├── latitude / longitude
├── photo_damage
├── status (pending/process/completed/rejected)
└── timestamps

Responses (tanggapan admin untuk laporan)
├── id
├── report_id (FK → Reports)
├── admin_id (FK → Users)
├── message
├── photo_repair
└── timestamps

News (berita dari admin)
├── id
├── admin_id (FK → Users)
├── title
├── content
├── slug
├── category
├── is_published
├── image
└── timestamps

Settings (pengaturan aplikasi)
├── id
├── key
├── value
├── type
└── timestamps

Notifications (notifikasi sistem)
├── id
├── notifiable_id
├── notifiable_type
├── type
├── data (JSON)
├── read_at
└── created_at
```

### Model Relationships

```
User
  → hasMany(Report)        [Laporan yang dibuat user]
  → hasMany(Response)      [Tanggapan yang dibuat admin]
  → hasMany(News)          [Berita yang dibuat admin]
  → Notifiable             [Menerima notifikasi]

Report
  → belongsTo(User)        [User yang membuat laporan]
  → belongsTo(Category)    [Kategori laporan]
  → hasMany(Response)      [Tanggapan dari admin]

Response
  → belongsTo(Report)      [Laporan yang di-response]
  → belongsTo(User)        [Admin yang merespons]

Category
  → hasMany(Report)        [Laporan dalam kategori ini]

News
  → belongsTo(User)        [Admin yang membuat berita]
```

---

## 🎯 Fitur Utama Sistem

### 1. **PUBLIC / HOME (Tanpa Login)**

**Route**: `/`

**Controller**: `Public\HomeController@index`

**Fitur**:
- Dashboard publik dengan statistik laporan
- Tampilkan total laporan per status (pending, process, completed, rejected)
- Chart laporan per kategori
- Chart laporan per bulan (6 bulan terakhir)
- Persentase laporan yang selesai

**Response**: View template `public.home`

---

### 2. **BERITA (Public)**

**Routes**:
- `GET /berita` → `Public\NewsController@index` - Daftar berita terbaru
- `GET /berita/{slug}` → `Public\NewsController@show` - Detail berita

**Fitur**:
- Menampilkan berita yang sudah dipublikasikan
- Pagination (9 berita per halaman)
- Link ke berita terkait (3 berita terbaru)
- Filter berdasarkan publikasi

---

### 3. **AUTHENTICATION (Auth Routes)**

**Routes**:
- `GET /login` → `Auth\LoginController@showLoginForm`
- `POST /login` → `Auth\LoginController@login`
- `GET /register` → `Auth\RegisterController@showRegistrationForm`
- `POST /register` → `Auth\RegisterController@register`
- `POST /logout` → `Auth\LoginController@logout`

**Flow Login**:
1. User input email & password
2. Autentikasi dengan `Auth::attempt()`
3. Redirect otomatis berdasarkan role:
   - Role **admin** → `/admin/dashboard`
   - Role **masyarakat** → `/member/dashboard`

**Flow Register**:
1. Input: name, email, phone (opsional), password
2. Validasi password minimal 8 karakter + confirmation
3. Hash password & buat user baru dengan role `masyarakat`
4. Auto login & redirect ke member dashboard

---

## 👥 MEMBER ROUTES (Masyarakat / Pelapor)

**Middleware**: `auth, role:masyarakat`

**Prefix**: `/member` | **Name**: `member.*`

### 3.1 Dashboard Member

**Route**: `GET /member/dashboard` → `Member\DashboardController@index`

**Fitur**:
- Tampilkan statistik laporan user sendiri
  - Total laporan
  - Laporan pending
  - Laporan process
  - Laporan completed
  - Laporan rejected
- Daftar 5 laporan terbaru user

**Response**: View `member.dashboard`

---

### 3.2 Kelola Laporan

**Routes**:
```
GET    /member/laporan           → Member\ReportController@index
POST   /member/laporan           → Member\ReportController@store
GET    /member/laporan/{report}  → Member\ReportController@show
DELETE /member/laporan/{report}  → Member\ReportController@destroy
```

**Flow Membuat Laporan**:

1. **GET /member/laporan** - Tampilkan form + daftar laporan user
   - Load kategori dari database
   - Load laporan user dengan responses & admin info
   - Pagination 10 laporan per halaman

2. **POST /member/laporan** - Submit laporan baru
   - **Validasi**:
     - `category_id` (required, exists di categories)
     - `title` (max 255)
     - `description` (required)
     - `location` (max 500)
     - `photo_damage` (optional, image, max 5MB)
   
   - **Data Processing**:
     - Generate unique code: `RPT-YYYYMMDD-XXXX`
     - Upload photo_damage ke storage/public/reports
     - Create Report record dengan status `pending`
   
   - **Notification**:
     - Kirim notifikasi ke semua admin (NewReportNotification)
     - Admin akan tahu ada laporan baru
   
   - **Response**: JSON `{message, status, ...}`

3. **GET /member/laporan/{report}** - Detail laporan
   - Cek: laporan harus milik user sendiri (403 jika not)
   - Load dengan relationships: category, responses.admin
   - **Response**: JSON data laporan

4. **DELETE /member/laporan/{report}** - Hapus laporan
   - Cek: laporan harus milik user sendiri
   - Cek: status harus `pending` (laporan yang sudah diproses tidak bisa dihapus)
   - Delete record
   - **Response**: JSON success/error message

---

## 🔐 ADMIN ROUTES (Admin / Pengelola)

**Middleware**: `auth, role:admin`

**Prefix**: `/admin` | **Name**: `admin.*`

### 4.1 Admin Dashboard

**Route**: `GET /admin/dashboard` → `Admin\DashboardController@index`

**Fitur**:
- **Statistik keseluruhan**:
  - Total laporan
  - Laporan per status (pending, process, completed, rejected)
  - Total user (masyarakat)
  - Total kategori
  - Total berita
  - Persentase laporan completed

- **Chart Data**:
  - Laporan per bulan (6 bulan terakhir) - total & completed
  - Laporan per kategori (count)

- **Data Tabel**:
  - 8 laporan terbaru dengan user & kategori info

**Response**: View `admin.dashboard`

---

### 4.2 Kelola Laporan (Admin)

**Routes**:
```
GET    /admin/laporan              → Admin\ReportController@index
GET    /admin/laporan/{report}     → Admin\ReportController@show
PATCH  /admin/laporan/{report}/status → Admin\ReportController@updateStatus
POST   /admin/laporan/{report}/response → Admin\ReportController@addResponse
DELETE /admin/laporan/{report}     → Admin\ReportController@destroy
```

**Flow Kelola Laporan**:

1. **GET /admin/laporan** - Daftar semua laporan
   - **Filter**:
     - Status: pending, process, completed, rejected
     - Search: berdasarkan title atau code
   
   - **Load Relations**: user, category, responses
   - **Pagination**: 10 laporan per halaman
   - **Response**: View `admin.reports.index`

2. **GET /admin/laporan/{report}** - Detail laporan
   - Load: user, category, responses (dengan admin info)
   - **Response**: JSON full data laporan

3. **PATCH /admin/laporan/{report}/status** - Update status
   - **Input**: status (pending/process/completed/rejected)
   - **Action**:
     - Update status di database
     - **KIRIM NOTIFIKASI** ke user pelapor (ReportStatusUpdatedNotification)
   - **Response**: JSON dengan new_status

4. **POST /admin/laporan/{report}/response** - Tambah tanggapan/respons
   - **Input**:
     - `message` (required)
     - `photo_repair` (optional, image max 5MB)
     - `status` (required - status laporan yg baru)
   
   - **Action**:
     - Upload photo_repair ke storage/public/responses
     - Create Response record
     - Update Report status ke status yang diberikan
     - **KIRIM NOTIFIKASI** ke user dengan pesan response
   
   - **Response**: JSON dengan response data & new_status

5. **DELETE /admin/laporan/{report}** - Hapus laporan
   - Delete report & semua responses terkait (cascade)
   - **Response**: JSON success

---

### 4.3 Kelola User (Masyarakat)

**Routes**:
```
GET    /admin/pengguna         → Admin\UserController@index
POST   /admin/pengguna         → Admin\UserController@store
GET    /admin/pengguna/{user}  → Admin\UserController@show
PUT    /admin/pengguna/{user}  → Admin\UserController@update
DELETE /admin/pengguna/{user}  → Admin\UserController@destroy
```

**Flow**:

1. **GET /admin/pengguna** - Daftar user masyarakat
   - Filter role: hanya `masyarakat`
   - Search: name, email
   - Pagination: 10 per halaman
   - **Response**: View `admin.users.index`

2. **POST /admin/pengguna** - Tambah user
   - **Input**: name, email, phone, password (min 8)
   - Create user dengan role `masyarakat`
   - **Response**: JSON user data

3. **GET /admin/pengguna/{user}** - Detail user
   - **Response**: JSON user object

4. **PUT /admin/pengguna/{user}** - Update user
   - **Input**: name, email, phone, password (opsional)
   - Hash password jika diisi
   - **Response**: JSON user updated

5. **DELETE /admin/pengguna/{user}** - Hapus user
   - Delete user & semua reports/responses terkait (cascade)
   - **Response**: JSON success

---

### 4.4 Kelola Kategori

**Routes**:
```
GET    /admin/kategori         → Admin\CategoryController@index
POST   /admin/kategori         → Admin\CategoryController@store
GET    /admin/kategori/{category}  → Admin\CategoryController@show
PUT    /admin/kategori/{category}  → Admin\CategoryController@update
DELETE /admin/kategori/{category}  → Admin\CategoryController@destroy
```

**Flow**:

1. **GET /admin/kategori** - Daftar kategori
   - Load dengan count reports per kategori
   - Latest order
   - Pagination: 10 per halaman
   - **Response**: View `admin.categories.index`

2. **POST /admin/kategori** - Tambah kategori
   - **Input**: name (unique), icon (opsional)
   - Slug auto-generated dari name
   - **Response**: JSON kategori

3. **GET /admin/kategori/{category}** - Detail
   - **Response**: JSON category object

4. **PUT /admin/kategori/{category}** - Update
   - **Input**: name, icon
   - Slug auto-update
   - **Response**: JSON updated category

5. **DELETE /admin/kategori/{category}** - Hapus
   - Delete kategori & semua reports terkait (cascade)
   - **Response**: JSON success

---

### 4.5 Kelola Berita

**Routes**:
```
GET    /admin/berita         → Admin\NewsController@index
POST   /admin/berita         → Admin\NewsController@store
GET    /admin/berita/{news}  → Admin\NewsController@show
POST   /admin/berita/{news}  → Admin\NewsController@update
DELETE /admin/berita/{news}  → Admin\NewsController@destroy
```

**Flow**:

1. **GET /admin/berita** - Daftar berita
   - Search: berdasarkan title
   - Load admin (pembuat) info
   - Latest order
   - Pagination: 10 per halaman
   - **Response**: View `admin.news.index`

2. **POST /admin/berita** - Tambah berita
   - **Input**:
     - title (max 255)
     - content (required)
     - category (opsional, default "Umum")
     - is_published (boolean, default true)
     - image (opsional, max 3MB)
   
   - **Action**:
     - Upload image ke storage/public/news
     - Create News dengan admin_id = auth()->id()
     - Slug auto-generated
   
   - **Response**: JSON news data

3. **GET /admin/berita/{news}** - Detail
   - **Response**: JSON news object

4. **POST /admin/berita/{news}** - Update berita
   - **Input**: title, content, category, is_published, image
   - Upload image baru jika ada
   - **Response**: JSON updated news

5. **DELETE /admin/berita/{news}** - Hapus berita
   - **Response**: JSON success

---

### 4.6 Export Laporan ke PDF

**Route**: `GET /admin/export/reports-pdf` → `Admin\ExportController@reportsPdf`

**Fitur**:
- Export semua laporan (latest order)
- Load: user, category, responses
- Generate PDF dengan dompdf
- Download file: `laporan-kerusakan-YYYY-MM-DD.pdf`
- Layout: Landscape A4

---

### 4.7 Pengaturan Aplikasi

**Routes**:
```
GET /admin/pengaturan  → Admin\SettingController@index
PUT /admin/pengaturan  → Admin\SettingController@update
```

**Flow**:

1. **GET /admin/pengaturan** - Form pengaturan
   - Load semua settings dari DB
   - Key-value pairs untuk form
   - **Response**: View `admin.settings.index`

2. **PUT /admin/pengaturan** - Update settings
   - **Input**: berbagai setting fields + logo_url
   
   - **Action**:
     - Update semua setting key-value di DB
     - Jika ada upload logo:
       - Delete logo lama dari storage
       - Upload logo baru ke storage/public/settings
       - Save path ke settings table
   
   - **Response**: Redirect dengan success message

---

### 4.8 Notifikasi Admin

**Routes**:
```
GET  /admin/notifikasi              → Admin\NotificationController@index
GET  /admin/notifikasi/{id}/read    → Admin\NotificationController@markAsRead
POST /admin/notifikasi/read-all     → Admin\NotificationController@markAllAsRead
```

**Flow**:

1. **GET /admin/notifikasi** - Daftar notifikasi
   - Load notifikasi milik admin yang login
   - Pagination: 10 per halaman
   - **Response**: View `admin.notifications.index`

2. **GET /admin/notifikasi/{id}/read** - Tandai dibaca
   - Mark notifikasi sebagai read
   - Redirect ke laporan index dengan search code dari notifikasi
   - Memudahkan admin langsung melihat laporan terkait

3. **POST /admin/notifikasi/read-all** - Tandai semua dibaca
   - Mark semua unread notifications sebagai read
   - **Response**: Redirect dengan success message

---

## 🔔 Sistem Notifikasi

### Tipe Notifikasi

1. **NewReportNotification**
   - **Trigger**: Ketika masyarakat submit laporan baru
   - **Recipient**: Semua admin
   - **Data**: Report code, title, user name
   - **Aksi Admin**: Baca notifikasi → auto redirect ke report

2. **ReportStatusUpdatedNotification**
   - **Trigger**: Ketika admin update status laporan atau tambah response
   - **Recipient**: User pelapor
   - **Data**: Report code, new status, response message (jika ada)
   - **Aksi User**: Melihat status laporan mereka ter-update

---

## 📊 Flow Diagram Laporan

```
┌─────────────────────────────────────────────────────────────┐
│              MASYARAKAT (Pelapor)                            │
├─────────────────────────────────────────────────────────────┤
│  1. Register → Login                                         │
│  2. Buat Laporan                                             │
│     - Pilih kategori                                         │
│     - Isi title, deskripsi, lokasi                          │
│     - Upload foto kerusakan                                  │
│     - Submit → Status: PENDING                              │
│  3. Lihat Laporan Mereka                                    │
│     - Lihat status laporan                                  │
│     - Lihat response dari admin                             │
│     - Bisa hapus laporan (hanya status pending)            │
│  4. Notifikasi                                              │
│     - Terima notifikasi status update dari admin            │
└─────────────────────────────────────────────────────────────┘
                          ↓ Notification
         ┌─────────────────────────────────────────┐
         │      DATABASE (Reports Table)            │
         │   Status: pending → process →            │
         │   completed/rejected                     │
         └─────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│              ADMIN (Pengelola)                               │
├─────────────────────────────────────────────────────────────┤
│  1. Dashboard                                                │
│     - Lihat statistik laporan                               │
│     - Chart status, per kategori, per bulan                 │
│  2. Kelola Laporan                                          │
│     - Filter berdasarkan status                             │
│     - Search laporan                                        │
│     - Detail laporan + response history                     │
│     - Update status: pending → process                      │
│     - Tambah response (message + foto perbaikan)            │
│     - Status update → completed/rejected                    │
│  3. Kelola Kategori                                         │
│     - CRUD kategori laporan                                 │
│  4. Kelola User                                             │
│     - CRUD user masyarakat                                  │
│  5. Kelola Berita                                           │
│     - CRUD berita publik                                    │
│  6. Export                                                   │
│     - Export laporan ke PDF                                 │
│  7. Pengaturan                                              │
│     - Update logo, nama sistem, dll                         │
│  8. Notifikasi                                              │
│     - Terima notifikasi laporan baru                        │
│     - Auto redirect ke laporan terkait                      │
└─────────────────────────────────────────────────────────────┘
```

---

## 📁 Route Summary

### Public Routes
```
GET  /                          → Home (Dashboard publik)
GET  /berita                    → Daftar berita
GET  /berita/{slug}             → Detail berita
```

### Auth Routes
```
GET  /login                     → Form login
POST /login                     → Process login
GET  /register                  → Form register
POST /register                  → Process register
POST /logout                    → Logout (auth required)
```

### Member Routes (Masyarakat)
```
GET    /member/dashboard        → Dashboard member
GET    /member/laporan          → Daftar laporan
POST   /member/laporan          → Buat laporan
GET    /member/laporan/{id}     → Detail laporan
DELETE /member/laporan/{id}     → Hapus laporan
```

### Admin Routes
```
GET    /admin/dashboard                           → Dashboard
GET    /admin/laporan                             → Daftar laporan
GET    /admin/laporan/{id}                        → Detail laporan
PATCH  /admin/laporan/{id}/status                 → Update status
POST   /admin/laporan/{id}/response               → Tambah response
DELETE /admin/laporan/{id}                        → Hapus laporan
GET    /admin/pengguna                            → Daftar user
POST   /admin/pengguna                            → Tambah user
GET    /admin/pengguna/{id}                       → Detail user
PUT    /admin/pengguna/{id}                       → Update user
DELETE /admin/pengguna/{id}                       → Hapus user
GET    /admin/kategori                            → Daftar kategori
POST   /admin/kategori                            → Tambah kategori
GET    /admin/kategori/{id}                       → Detail kategori
PUT    /admin/kategori/{id}                       → Update kategori
DELETE /admin/kategori/{id}                       → Hapus kategori
GET    /admin/berita                              → Daftar berita
POST   /admin/berita                              → Tambah berita
GET    /admin/berita/{id}                         → Detail berita
POST   /admin/berita/{id}                         → Update berita
DELETE /admin/berita/{id}                         → Hapus berita
GET    /admin/export/reports-pdf                  → Export PDF
GET    /admin/pengaturan                          → Form pengaturan
PUT    /admin/pengaturan                          → Update pengaturan
GET    /admin/notifikasi                          → Daftar notifikasi
GET    /admin/notifikasi/{id}/read                → Mark as read
POST   /admin/notifikasi/read-all                 → Mark all as read
```

---

## 🔐 Authorization & Middleware

### Middleware Applied
- `auth`: Untuk routes yang memerlukan login
- `guest`: Untuk login/register (redirect jika sudah login)
- `role:masyarakat`: Hanya untuk user dengan role masyarakat
- `role:admin`: Hanya untuk user dengan role admin

### Method Checking
- **User ownership**: Saat masyarakat akses laporan mereka
- **Role-based access**: Masing-masing route dilindungi middleware role

---

## 💾 Key Features Summary

✅ **Multi-role system** (Admin & Masyarakat)
✅ **Report lifecycle** (pending → process → completed/rejected)
✅ **Photo upload** (damage photo & repair photo)
✅ **Notifications** (New report & Status updates)
✅ **Dashboard analytics** (charts & statistics)
✅ **Search & filtering** (reports & users)
✅ **PDF export** (all reports)
✅ **News management** (publish berita)
✅ **Category management** (organize reports)
✅ **User management** (CRUD users)
✅ **Settings management** (customize system)

---

## 🛠️ Developer Notes

- **Code Generation**: Report code auto-generated dengan format `RPT-YYYYMMDD-XXXX`
- **Slug Generation**: Category & News slug auto-generated dari name (Spatie)
- **File Storage**: Photos stored di `storage/app/public/` (make sure symlink)
- **Notifications**: Using Laravel's notification system (database driver)
- **PDF**: Using dompdf package via barryvdh/laravel-dompdf
- **Image Handling**: Supported formats: jpg, jpeg, png, webp

---

*Dokumentasi dibuat: May 17, 2026*
*Laravel 12 | MySQL Database | Blade Templates*
