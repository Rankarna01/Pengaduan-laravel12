# API Endpoints & Controllers Detail

## 📍 PUBLIC ENDPOINTS

### 1. Home Dashboard
```
GET /
├─ Controller: App\Http\Controllers\Public\HomeController@index
├─ Auth Required: ❌ No
├─ Response: View (public.home)
└─ Data Returned:
   ├─ stats: [total, pending, process, completed, rejected, percent_completed]
   ├─ categoriesChart: [{label, count, icon}, ...]
   └─ monthlyChart: [{month, count}, ...]
```

### 2. News Index
```
GET /berita
├─ Controller: App\Http\Controllers\Public\NewsController@index
├─ Auth Required: ❌ No
├─ Query Params: (none)
├─ Response: View (public.news.index)
└─ Data Returned:
   ├─ news (paginated 9/page)
   │  └─ with: admin relationship
   └─ Latest order
```

### 3. News Detail
```
GET /berita/{slug}
├─ Controller: App\Http\Controllers\Public\NewsController@show
├─ Auth Required: ❌ No
├─ Route Params: slug (string)
├─ Filters: is_published = true
├─ Response: View (public.news.show)
└─ Data Returned:
   ├─ item: News object
   └─ related: 3 latest related news
```

---

## 🔐 AUTH ENDPOINTS

### 4. Show Login Form
```
GET /login
├─ Controller: App\Http\Controllers\Auth\LoginController@showLoginForm
├─ Auth Required: ❌ (Must be guest)
├─ Middleware: guest
├─ Response: View (auth.login)
└─ Auto Redirect: If already authenticated → by role
```

### 5. Process Login
```
POST /login
├─ Controller: App\Http\Controllers\Auth\LoginController@login
├─ Auth Required: ❌ (Must be guest)
├─ Middleware: guest
├─ Request Body:
│  ├─ email (required, email)
│  ├─ password (required)
│  └─ remember (boolean, optional)
├─ Validation Errors: {email: "Email atau password salah."}
├─ On Success:
│  ├─ Set session
│  ├─ Regenerate token
│  └─ Redirect by role:
│     ├─ admin → /admin/dashboard
│     └─ masyarakat → /member/dashboard
└─ Response: Redirect with success/error
```

### 6. Show Register Form
```
GET /register
├─ Controller: App\Http\Controllers\Auth\RegisterController@showRegistrationForm
├─ Auth Required: ❌ (Must be guest)
├─ Middleware: guest
├─ Response: View (auth.register)
```

### 7. Process Register
```
POST /register
├─ Controller: App\Http\Controllers\Auth\RegisterController@register
├─ Auth Required: ❌ (Must be guest)
├─ Middleware: guest
├─ Request Body:
│  ├─ name (required, string, max:255)
│  ├─ email (required, email, unique)
│  ├─ phone (optional, string, max:20)
│  ├─ password (required, min:8, confirmed)
│  └─ password_confirmation (required if password)
├─ Created User:
│  └─ role: 'masyarakat'
├─ On Success:
│  ├─ Hash password
│  ├─ Auto login
│  └─ Redirect → /member/dashboard
└─ Response: Redirect with success message
```

### 8. Logout
```
POST /logout
├─ Controller: App\Http\Controllers\Auth\LoginController@logout
├─ Auth Required: ✅ Yes
├─ Middleware: auth
├─ On Success:
│  ├─ Destroy session
│  ├─ Invalidate session
│  ├─ Regenerate token
│  └─ Redirect → / (home)
└─ Response: Redirect with success message
```

---

## 👥 MEMBER ENDPOINTS (Masyarakat)

**Base Path**: `/member`
**Middleware**: `auth`, `role:masyarakat`
**Auth Required**: ✅ Yes

### 9. Member Dashboard
```
GET /member/dashboard [NAME: member.dashboard]
├─ Controller: App\Http\Controllers\Member\DashboardController@index
├─ Response: View (member.dashboard)
└─ Data Returned:
   ├─ stats:
   │  ├─ total (COUNT)
   │  ├─ pending (WHERE status = 'pending')
   │  ├─ process (WHERE status = 'process')
   │  ├─ completed (WHERE status = 'completed')
   │  └─ rejected (WHERE status = 'rejected')
   └─ latestReports: Take 5 latest with category
```

### 10. List Reports (Member)
```
GET /member/laporan [NAME: member.reports.index]
├─ Controller: App\Http\Controllers\Member\ReportController@index
├─ Filters:
│  └─ WHERE user_id = auth()->id()
├─ Response: View (member.reports.index)
├─ Load Relations: category, responses.admin
├─ Pagination: 10/page
└─ Data Returned:
   ├─ reports: Paginated reports
   └─ categories: All categories (for form)
```

### 11. Create Report
```
POST /member/laporan [NAME: member.reports.store]
├─ Controller: App\Http\Controllers\Member\ReportController@store
├─ Content-Type: multipart/form-data (for file upload)
├─ Request Body:
│  ├─ category_id (required, exists:categories,id)
│  ├─ title (required, string, max:255)
│  ├─ description (required, string)
│  ├─ location (required, string, max:500)
│  └─ photo_damage (nullable, image, jpg|jpeg|png|webp, max:5120KB)
├─ Processing:
│  ├─ Generate code: RPT-YYYYMMDD-XXXX
│  ├─ Upload photo → storage/app/public/reports/
│  ├─ Create Report (status: 'pending')
│  ├─ Fetch all admins
│  └─ Send notification to all admins (NewReportNotification)
├─ Response: JSON
│  ├─ message: "Laporan berhasil dikirim!"
│  ├─ status: "success"
│  └─ report: Report object
└─ HTTP Code: 200
```

### 12. Show Report Detail
```
GET /member/laporan/{report} [NAME: member.reports.show]
├─ Controller: App\Http\Controllers\Member\ReportController@show
├─ Route Binding: Report (implicit)
├─ Authorization:
│  └─ ABORT 403 if report.user_id != auth()->id()
├─ Load Relations: category, responses.admin
├─ Response: JSON
│  └─ Full Report object with nested data
└─ HTTP Code: 200 or 403
```

### 13. Delete Report
```
DELETE /member/laporan/{report} [NAME: member.reports.destroy]
├─ Controller: App\Http\Controllers\Member\ReportController@destroy
├─ Route Binding: Report
├─ Authorization: ABORT 403 if not report owner
├─ Business Rules:
│  ├─ Status must be 'pending'
│  └─ Return 422 if already processed
├─ Response: JSON
│  ├─ message: "Laporan berhasil dihapus." or error message
│  ├─ status: "success" or "error"
│  └─ HTTP Code: 200 or 422
└─ Cascade: Delete related responses too
```

---

## 🔐 ADMIN ENDPOINTS (Admin)

**Base Path**: `/admin`
**Middleware**: `auth`, `role:admin`
**Auth Required**: ✅ Yes

### 14. Admin Dashboard
```
GET /admin/dashboard [NAME: admin.dashboard]
├─ Controller: App\Http\Controllers\Admin\DashboardController@index
├─ Response: View (admin.dashboard)
└─ Data Returned:
   ├─ stats:
   │  ├─ total_reports
   │  ├─ pending, process, completed, rejected
   │  ├─ total_users (role='masyarakat')
   │  ├─ total_categories
   │  ├─ total_news
   │  └─ percent_completed
   ├─ latestReports: 8 reports with user & category
   ├─ monthlyChart: 6 months {month, total, completed}
   └─ categoryChart: {name, reports_count}
```

### 15. List Reports (Admin)
```
GET /admin/laporan [NAME: admin.reports.index]
├─ Controller: App\Http\Controllers\Admin\ReportController@index
├─ Query Params:
│  ├─ status: pending|process|completed|rejected (optional)
│  └─ search: by title or code (optional)
├─ Load Relations: user, category, responses
├─ Pagination: 10/page
├─ Order: latest (DESC created_at)
├─ Response: View (admin.reports.index)
└─ Data Returned: Filtered reports with all relations
```

### 16. Show Report Detail (Admin)
```
GET /admin/laporan/{report} [NAME: admin.reports.show]
├─ Controller: App\Http\Controllers\Admin\ReportController@show
├─ Route Binding: Report
├─ Load Relations: user, category, responses.admin
├─ Response: JSON
│  └─ Full report object with all nested data
└─ HTTP Code: 200
```

### 17. Update Report Status
```
PATCH /admin/laporan/{report}/status [NAME: admin.reports.status]
├─ Controller: App\Http\Controllers\Admin\ReportController@updateStatus
├─ Route Binding: Report
├─ Request Body:
│  └─ status (required, in:pending,process,completed,rejected)
├─ Validation:
│  └─ status: in:pending,process,completed,rejected
├─ Processing:
│  ├─ Update report.status in DB
│  ├─ Send notification to report.user (ReportStatusUpdatedNotification)
│  └─ Include report code & new status in notification
├─ Response: JSON
│  ├─ message: "Status laporan berhasil diperbarui."
│  ├─ status: "success"
│  └─ new_status: updated status string
└─ HTTP Code: 200
```

### 18. Add Response to Report
```
POST /admin/laporan/{report}/response [NAME: admin.reports.response]
├─ Controller: App\Http\Controllers\Admin\ReportController@addResponse
├─ Route Binding: Report
├─ Content-Type: multipart/form-data
├─ Request Body:
│  ├─ message (required, string)
│  ├─ photo_repair (nullable, image, jpg|jpeg|png|webp, max:5120KB)
│  └─ status (required, in:pending,process,completed,rejected)
├─ Processing:
│  ├─ Upload photo_repair → storage/app/public/responses/
│  ├─ Create Response:
│  │  ├─ report_id: {report}
│  │  ├─ admin_id: auth()->id()
│  │  ├─ message: input message
│  │  └─ photo_repair: file path
│  ├─ Update report.status to input status
│  ├─ Send notification to report.user (ReportStatusUpdatedNotification)
│  │  └─ Include: report code, new status, response message
│  └─ Load response with admin relationship
├─ Response: JSON
│  ├─ message: "Tanggapan berhasil ditambahkan."
│  ├─ status: "success"
│  ├─ response: Response object with admin
│  └─ new_status: report's current status
└─ HTTP Code: 200
```

### 19. Delete Report (Admin)
```
DELETE /admin/laporan/{report} [NAME: admin.reports.destroy]
├─ Controller: App\Http\Controllers\Admin\ReportController@destroy
├─ Route Binding: Report
├─ Processing:
│  └─ Delete report (cascade → responses too)
├─ Response: JSON
│  ├─ message: "Laporan berhasil dihapus."
│  └─ status: "success"
└─ HTTP Code: 200
```

---

## 👤 USER MANAGEMENT ENDPOINTS (Admin)

### 20. List Users (Masyarakat only)
```
GET /admin/pengguna [NAME: admin.users.index]
├─ Controller: App\Http\Controllers\Admin\UserController@index
├─ Query Params:
│  └─ search: by name or email (optional)
├─ Filters: WHERE role = 'masyarakat'
├─ Pagination: 10/page
├─ Order: latest (DESC created_at)
├─ Response: View (admin.users.index)
└─ Data Returned: Filtered users
```

### 21. Create User
```
POST /admin/pengguna [NAME: admin.users.store]
├─ Controller: App\Http\Controllers\Admin\UserController@store
├─ Request Body:
│  ├─ name (required, string, max:255)
│  ├─ email (required, email, unique)
│  ├─ phone (optional, string, max:20)
│  └─ password (required, string, min:8)
├─ Processing:
│  ├─ Hash password
│  └─ Create User (role: 'masyarakat')
├─ Response: JSON
│  ├─ message: "Pengguna berhasil ditambahkan."
│  ├─ status: "success"
│  └─ user: User object
└─ HTTP Code: 200
```

### 22. Show User Detail
```
GET /admin/pengguna/{user} [NAME: admin.users.show]
├─ Controller: App\Http\Controllers\Admin\UserController@show
├─ Route Binding: User
├─ Response: JSON
│  └─ User object
└─ HTTP Code: 200
```

### 23. Update User
```
PUT /admin/pengguna/{user} [NAME: admin.users.update]
├─ Controller: App\Http\Controllers\Admin\UserController@update
├─ Route Binding: User
├─ Request Body:
│  ├─ name (required, string, max:255)
│  ├─ email (required, email, unique except current)
│  ├─ phone (optional, string, max:20)
│  └─ password (optional, string, min:8)
├─ Processing:
│  ├─ Hash password only if provided
│  └─ Update user
├─ Response: JSON
│  ├─ message: "Data pengguna berhasil diperbarui."
│  ├─ status: "success"
│  └─ user: Updated user object
└─ HTTP Code: 200
```

### 24. Delete User
```
DELETE /admin/pengguna/{user} [NAME: admin.users.destroy]
├─ Controller: App\Http\Controllers\Admin\UserController@destroy
├─ Route Binding: User
├─ Processing:
│  └─ Delete user (cascade → reports, responses)
├─ Response: JSON
│  ├─ message: "Pengguna berhasil dihapus."
│  └─ status: "success"
└─ HTTP Code: 200
```

---

## 🏷️ CATEGORY MANAGEMENT ENDPOINTS (Admin)

### 25. List Categories
```
GET /admin/kategori [NAME: admin.categories.index]
├─ Controller: App\Http\Controllers\Admin\CategoryController@index
├─ Query Params: (none)
├─ Load: withCount('reports')
├─ Pagination: 10/page
├─ Order: latest
├─ Response: View (admin.categories.index)
└─ Data Returned: Categories with report count
```

### 26. Create Category
```
POST /admin/kategori [NAME: admin.categories.store]
├─ Controller: App\Http\Controllers\Admin\CategoryController@store
├─ Request Body:
│  ├─ name (required, string, max:100, unique)
│  └─ icon (nullable, string, max:10)
├─ Processing:
│  ├─ Validate
│  └─ Create Category (slug auto-generated)
├─ Response: JSON
│  ├─ message: "Kategori berhasil ditambahkan."
│  ├─ status: "success"
│  └─ category: Category object
└─ HTTP Code: 200
```

### 27. Show Category Detail
```
GET /admin/kategori/{category} [NAME: admin.categories.show]
├─ Controller: App\Http\Controllers\Admin\CategoryController@show
├─ Route Binding: Category
├─ Response: JSON
│  └─ Category object
└─ HTTP Code: 200
```

### 28. Update Category
```
PUT /admin/kategori/{category} [NAME: admin.categories.update]
├─ Controller: App\Http\Controllers\Admin\CategoryController@update
├─ Route Binding: Category
├─ Request Body:
│  ├─ name (required, string, max:100, unique except current)
│  └─ icon (nullable, string, max:10)
├─ Processing:
│  └─ Update (slug auto-updated)
├─ Response: JSON
│  ├─ message: "Kategori berhasil diperbarui."
│  ├─ status: "success"
│  └─ category: Updated category object
└─ HTTP Code: 200
```

### 29. Delete Category
```
DELETE /admin/kategori/{category} [NAME: admin.categories.destroy]
├─ Controller: App\Http\Controllers\Admin\CategoryController@destroy
├─ Route Binding: Category
├─ Processing:
│  └─ Delete (cascade → reports)
├─ Response: JSON
│  ├─ message: "Kategori berhasil dihapus."
│  └─ status: "success"
└─ HTTP Code: 200
```

---

## 📰 NEWS MANAGEMENT ENDPOINTS (Admin)

### 30. List News
```
GET /admin/berita [NAME: admin.news.index]
├─ Controller: App\Http\Controllers\Admin\NewsController@index
├─ Query Params:
│  └─ search: by title (optional)
├─ Load Relations: admin
├─ Pagination: 10/page
├─ Order: latest
├─ Response: View (admin.news.index)
└─ Data Returned: Filtered news
```

### 31. Create News
```
POST /admin/berita [NAME: admin.news.store]
├─ Controller: App\Http\Controllers\Admin\NewsController@store
├─ Content-Type: multipart/form-data
├─ Request Body:
│  ├─ title (required, string, max:255)
│  ├─ content (required, string)
│  ├─ category (nullable, string, max:100, default: "Umum")
│  ├─ is_published (boolean, default: true)
│  └─ image (nullable, image, jpg|jpeg|png|webp, max:3072KB)
├─ Processing:
│  ├─ Upload image → storage/app/public/news/
│  └─ Create News (admin_id: auth()->id(), slug auto-generated)
├─ Response: JSON
│  ├─ message: "Berita berhasil ditambahkan."
│  ├─ status: "success"
│  └─ news: News object
└─ HTTP Code: 200
```

### 32. Show News Detail
```
GET /admin/berita/{news} [NAME: admin.news.show]
├─ Controller: App\Http\Controllers\Admin\NewsController@show
├─ Route Binding: News
├─ Response: JSON
│  └─ News object
└─ HTTP Code: 200
```

### 33. Update News
```
POST /admin/berita/{news} [NAME: admin.news.update]
├─ Controller: App\Http\Controllers\Admin\NewsController@update
├─ Route Binding: News
├─ Content-Type: multipart/form-data
├─ Request Body:
│  ├─ title (required, string, max:255)
│  ├─ content (required, string)
│  ├─ category (nullable, string, max:100)
│  ├─ is_published (boolean)
│  └─ image (nullable, image, jpg|jpeg|png|webp, max:3072KB)
├─ Processing:
│  ├─ If image: upload to storage/app/public/news/
│  └─ Update news (slug auto-updated)
├─ Response: JSON
│  ├─ message: "Berita berhasil diperbarui."
│  ├─ status: "success"
│  └─ news: Updated news object
└─ HTTP Code: 200
```

### 34. Delete News
```
DELETE /admin/berita/{news} [NAME: admin.news.destroy]
├─ Controller: App\Http\Controllers\Admin\NewsController@destroy
├─ Route Binding: News
├─ Processing:
│  └─ Delete news
├─ Response: JSON
│  ├─ message: "Berita berhasil dihapus."
│  └─ status: "success"
└─ HTTP Code: 200
```

---

## 📊 EXPORT ENDPOINTS (Admin)

### 35. Export Reports to PDF
```
GET /admin/export/reports-pdf [NAME: admin.export.reports-pdf]
├─ Controller: App\Http\Controllers\Admin\ExportController@reportsPdf
├─ Query Params: (none)
├─ Processing:
│  ├─ Fetch all reports: latest order
│  ├─ Load relations: user, category, responses
│  ├─ Generate PDF via dompdf
│  └─ Layout: Landscape A4
├─ Response: PDF Download
│  ├─ Filename: laporan-kerusakan-YYYY-MM-DD.pdf
│  └─ Content-Type: application/pdf
└─ HTTP Code: 200
```

---

## ⚙️ SETTINGS ENDPOINTS (Admin)

### 36. Show Settings Form
```
GET /admin/pengaturan [NAME: admin.settings.index]
├─ Controller: App\Http\Controllers\Admin\SettingController@index
├─ Processing:
│  └─ Fetch all settings, key as index
├─ Response: View (admin.settings.index)
└─ Data Returned: Settings keyBy('key')
```

### 37. Update Settings
```
PUT /admin/pengaturan [NAME: admin.settings.update]
├─ Controller: App\Http\Controllers\Admin\SettingController@update
├─ Content-Type: multipart/form-data
├─ Request Body:
│  ├─ Various setting fields (dynamic)
│  ├─ logo_url (nullable, image file)
│  └─ _token, _method (form fields)
├─ Processing:
│  ├─ For each setting field:
│  │  └─ updateOrCreate(key, value)
│  ├─ If logo_url uploaded:
│  │  ├─ Delete old logo from storage
│  │  ├─ Upload new logo → storage/app/public/settings/
│  │  └─ updateOrCreate logo_url setting
├─ Response: Redirect
│  └─ Back with success message
└─ HTTP Code: 302
```

---

## 🔔 NOTIFICATION ENDPOINTS (Admin)

### 38. List Notifications
```
GET /admin/notifikasi [NAME: admin.notifications.index]
├─ Controller: App\Http\Controllers\Admin\NotificationController@index
├─ Processing:
│  ├─ Fetch auth()->user()->notifications()
│  └─ Paginate 10/page
├─ Response: View (admin.notifications.index)
└─ Data Returned: Notification collection
```

### 39. Mark Single Notification as Read
```
GET /admin/notifikasi/{id}/read [NAME: admin.notifications.read]
├─ Controller: App\Http\Controllers\Admin\NotificationController@markAsRead
├─ Route Params: id (notification ID)
├─ Processing:
│  ├─ Find notification
│  ├─ Mark as read
│  └─ Extract report_code from notification data
├─ Response: Redirect
│  └─ To: admin.reports.index with search param
│     └─ Automatically filters reports by code
└─ HTTP Code: 302
```

### 40. Mark All Notifications as Read
```
POST /admin/notifikasi/read-all [NAME: admin.notifications.read-all]
├─ Controller: App\Http\Controllers\Admin\NotificationController@markAllAsRead
├─ Processing:
│  └─ Mark all unread notifications as read
├─ Response: Redirect
│  └─ Back with success message
└─ HTTP Code: 302
```

---

## 📝 RESPONSE CODES & PATTERNS

### Success Responses
```json
{
  "message": "Success message in Indonesian",
  "status": "success",
  "data": {} // Optional: returned data
}
```

### Error Responses
```json
{
  "message": "Error message",
  "status": "error"
}
```

### Validation Errors (422)
```json
{
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"],
    "another_field": ["Error message 1", "Error message 2"]
  }
}
```

### Authorization Errors (403)
```
HTTP 403 Forbidden
```

### Server Errors (500)
```
HTTP 500 Internal Server Error
```

---

## 🔗 Important Model Methods

### Report Model
```php
// Static method to generate unique code
Report::generateCode() // Returns: RPT-YYYYMMDD-XXXX

// Accessors
$report->status_label    // Returns human-readable status
$report->status_color    // Returns color code for UI
$report->photo_damage_url // Returns full asset URL
```

### User Model
```php
// Helper methods
$user->isAdmin()         // Check if admin
$user->isMasyarakat()    // Check if masyarakat
$user->avatar_url        // Get avatar URL (or default gravatar)

// Relations
$user->reports()         // User's reports
$user->responses()       // User's responses (as admin)
$user->news()           // User's news (as admin)
```

### Response Model
```php
// Accessors
$response->photo_repair_url // Returns full asset URL for repair photo
```

---

## 📎 File Upload Paths

```
storage/app/public/
├─ reports/          → photo_damage (dari member)
├─ responses/        → photo_repair (dari admin)
├─ news/             → image (berita)
└─ settings/         → logo_url dan files lainnya
```

**Note**: Symlink `public/storage` → `storage/app/public` harus ada!

---

## 🎫 Validation Rules Summary

### Report Validation
```php
'category_id'   => 'required|exists:categories,id'
'title'         => 'required|string|max:255'
'description'   => 'required|string'
'location'      => 'required|string|max:500'
'photo_damage'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
```

### User Validation
```php
'name'     => 'required|string|max:255'
'email'    => 'required|email|unique:users'
'phone'    => 'nullable|string|max:20'
'password' => 'required|string|min:8'
```

### Category Validation
```php
'name'  => 'required|string|max:100|unique:categories,name'
'icon'  => 'nullable|string|max:10'
```

### News Validation
```php
'title'        => 'required|string|max:255'
'content'      => 'required|string'
'category'     => 'nullable|string|max:100'
'is_published' => 'boolean'
'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072'
```

---

*Documentation updated: May 17, 2026*
