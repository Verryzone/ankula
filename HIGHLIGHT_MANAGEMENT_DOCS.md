# Dashboard Highlights Management

Fitur manajemen highlights dashboard memungkinkan admin untuk mengelola banner/highlight yang ditampilkan di halaman utama dashboard user.

## Fitur yang Tersedia

### Untuk Admin
1. **Melihat Daftar Highlights**: Akses melalui menu "Dashboard Highlights" di sidebar admin
2. **Tambah Highlight Baru**: 
   - Judul highlight
   - Deskripsi (opsional)
   - Gambar (opsional, mendukung PNG, JPG, GIF hingga 2MB)
   - Harga (opsional)
   - Teks tombol dan link tombol
   - Status aktif/nonaktif
   - Urutan tampil
3. **Edit Highlight**: Mengedit semua informasi highlight yang sudah ada
4. **Hapus Highlight**: Menghapus highlight beserta gambarnya
5. **Toggle Status**: Mengaktifkan/menonaktifkan highlight dengan toggle switch
6. **Drag & Drop Sorting**: Mengatur urutan tampil dengan menyeret baris di tabel
7. **Preview Real-time**: Melihat preview highlight saat membuat/mengedit

### Untuk User
- Highlights yang aktif akan ditampilkan di halaman dashboard sesuai urutan yang telah diatur
- Jika tidak ada highlights aktif, akan ditampilkan highlight default
- Mendukung multiple highlights yang akan ditampilkan berurutan

## Akses

### Admin
- URL: `/management/highlight`
- Login sebagai admin diperlukan
- Menu tersedia di sidebar management

### User Dashboard  
- URL: `/` atau `/dashboard`
- Highlights akan tampil otomatis di bagian atas halaman

## Database

Tabel `dashboard_highlights` dengan kolom:
- `title`: Judul highlight
- `description`: Deskripsi highlight (nullable)
- `image_path`: Path gambar highlight (nullable)
- `price`: Harga yang ditampilkan (nullable)
- `button_text`: Teks tombol (default: "Shop Now")
- `button_link`: Link tombol (nullable)
- `is_active`: Status aktif (boolean, default: true)
- `sort_order`: Urutan tampil (integer, default: 0)

## File yang Ditambahkan/Dimodifikasi

### Models
- `app/Models/DashboardHighlight.php`

### Controllers
- `app/Http/Controllers/ManagementHighlightController.php`
- `app/Http/Controllers/dashboardController.php` (modified)

### Views
- `resources/views/management/pages/highlight/index.blade.php`
- `resources/views/management/pages/highlight/create.blade.php`
- `resources/views/management/pages/highlight/edit.blade.php`
- `resources/views/management/layouts/sidebar.blade.php` (modified)
- `resources/views/pages/dashboard/app.blade.php` (modified)

### Routes
- `routes/web.php` (modified)

### Migrations
- `database/migrations/2025_07_18_132225_create_dashboard_highlights_table.php`

### Seeders
- `database/seeders/DashboardHighlightSeeder.php`

## Keamanan

- Validasi upload gambar (type dan size)
- Middleware auth dan role admin untuk akses management
- CSRF protection pada form
- Validasi input dengan Laravel validation

## Dependencies Eksternal

- SortableJS untuk drag & drop functionality
- Axios untuk AJAX requests
- jQuery untuk beberapa DOM manipulation

## Cara Penggunaan

1. Login sebagai admin
2. Buka menu "Dashboard Highlights" di sidebar
3. Klik "Tambah Highlight" untuk membuat highlight baru
4. Isi form dengan informasi yang diinginkan
5. Preview akan tampil real-time saat mengisi form
6. Simpan dan highlight akan muncul di dashboard user
7. Gunakan drag & drop untuk mengatur urutan
8. Toggle switch untuk mengaktifkan/menonaktifkan

## Catatan Teknis

- Gambar disimpan di `storage/app/public/highlights/`
- Highlights diurutkan berdasarkan `sort_order` ascending
- Hanya highlights dengan `is_active = true` yang ditampilkan di user dashboard
- Jika tidak ada highlights aktif, tampilkan highlight default (hardcoded)
