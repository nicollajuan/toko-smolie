# TOKO SMOLIE PROJECT - COMPLETE ANALYSIS

## Project Overview
**Project Name:** Toko Smolie Gift Shop
**Framework:** Laravel 11
**Purpose:** E-commerce platform for gift/souvenir sales with admin, cashier, and customer interfaces
**Key Feature:** Digital menu system, cart management, transactions, delivery options, and reviews

---

## 1. USER MODEL & AUTHENTICATION

### User Model (`app/Models/User.php`)
**Extends:** `Authenticatable` (Laravel's base auth class)

**Fields/Attributes:**
```php
- id: Primary key (auto-increment)
- name: User's full name (string)
- email: Email (unique, required)
- password: Hashed password
- username: Unique username (string, nullable)
- usertype: Role identifier (string) - Default: 'pembeli'
  * Values: 'pembeli' (customer), 'admin', 'kasir' (cashier)
- jenis_kelamin: Gender (string, enum) - 'Laki-laki' or 'Perempuan' (nullable)
- alamat: Address (text, nullable)
- no_hp: Phone number (string, max 20, nullable)
- foto: Profile photo filename (string, nullable)
- profile_photo: Profile photo path (string, max 2048, nullable)
- email_verified_at: Email verification timestamp (nullable)
- remember_token: Remember me token
- created_at: Timestamp
- updated_at: Timestamp
```

**Hidden Fields:**
- password
- remember_token

**Casts:**
- email_verified_at → datetime
- password → hashed

**Relationships:**
- NONE explicitly defined (but implicitly related to Transaksi, Review, Message)

**Important Notes:**
- Users can be: Pembeli (customers), Admin, or Kasir (cashier staff)
- Profile can include photo uploaded to `public/img/user/`
- No explicit relationships defined in model (should add them)

---

## 2. TRANSAKSI MODEL & RELATIONSHIPS

### Transaksi Model (`app/Models/Transaksi.php`)
**Table Name:** `transaksi`

**Fields:**
```php
- id: Primary key
- user_id: Foreign key to users (nullable, cascade delete)
- nama_pembeli: Customer's name (string)
- no_hp: Phone number (string, max 20, nullable)
- metode_pembayaran: Payment method (string) - 'tunai' or 'qris'
- jenis_pesanan: Order type (string) - 'dine_in', 'takeaway', or 'delivery'
- alamat_pengiriman: Delivery address (text, nullable - only if delivery)
- detail_rumah: House details/notes (string, nullable - for delivery)
- kode_transaksi: Unique transaction code (string) - Format: 'TRX-{timestamp}{random}'
- total_harga: Total price (integer/decimal)
- status: Order status (string, enum) - 'pending' or 'selesai' (default: 'pending')
- created_at: Timestamp
- updated_at: Timestamp
```

**Relationships:**
```php
- hasOne(Review): One review per transaction
- belongsTo(User): Owner of the transaction
```

**Related Table: `detail_transaksi`** (Order Details)
```php
- id: Primary key
- transaksi_id: Foreign key to transaksi (cascade delete)
- produk_id: Foreign key to produk
- jumlah: Quantity (integer)
- catatan: Notes/remarks (text, nullable - added in migration)
- subtotal: Item subtotal (decimal)
- created_at: Timestamp
- updated_at: Timestamp
```

**Current Issues:**
- Model uses `protected $guarded = []` (vulnerable - should use $fillable)
- Should add relationship: `hasMany(DetailTransaksi)`

---

## 3. DATABASE MIGRATIONS OVERVIEW

### Key Migrations:

#### 1. **Users Table** (`0001_01_01_000000_create_users_table.php`)
- Basic user structure with usertype field
- Default usertype: 'pembeli'

#### 2. **Transaksi Tables** (`2025_11_25_042659_create_transaksis_table.php`)
- Creates main `transaksi` table
- Creates `detail_transaksi` child table
- Foreign key relationships with cascade delete

#### 3. **Payment Method** (`2025_12_07_033131_add_metode_pembayaran_to_transaksi.php`)
- Adds `metode_pembayaran` field
- Default: 'tunai' (cash)
- Options: 'tunai' or 'qris'

#### 4. **Extended Transaction Fields** (`2026_03_17_160932_update_kolom_transaksi_baru.php`)
- Adds all delivery/order details:
  * nama_pembeli
  * no_hp
  * jenis_pesanan
  * alamat_pengiriman
  * detail_rumah
  * kode_transaksi
  * Includes checks to avoid duplicate columns

#### 5. **User Profile Extensions** (`2025_12_15_060152_add_profile_fields_to_users_table.php`)
- Adds: username, profile_photo
- Unique username constraint

#### 6. **User Details** (`2026_03_10_152919_add_detail_kolom_to_users_table.php`)
- Adds: jenis_kelamin, alamat, no_hp
- For complete user profile

#### 7. **Reviews** (`2025_12_15_072921_create_reviews_table.php`)
- Stores customer reviews for transactions

#### 8. **Messages/Chat** (`2026_04_07_111700_create_messages_table.php`)
- For customer-admin communication
- Fields: user_id, pesan, pengirim (pembeli/admin), is_read

### Other Models & Tables:

**Kategori Model** (`app/Models/Kategori.php`)
- Table: `kategori`
- Fields: id, nama_kategori
- Relationship: hasMany(Produk)

**Produk Model** (`app/Models/Produk.php`)
- Table: `produk`
- Fields: id, nama_produk, harga, stock, gambar, status, kategori_id
- Relationship: belongsTo(Kategori)

**Review Model** (`app/Models/Review.php`)
- Relationships:
  * belongsTo(User)
  * belongsTo(Transaksi, 'transaksi_id')

**Message Model** (`app/Models/Message.php`)
- Table: `messages`
- Fields: id, user_id, pesan, pengirim, is_read
- Relationship: belongsTo(User)

---

## 4. AUTHENTICATION & LOGIN IMPLEMENTATION

### Login/Register Views
**Location:** `resources/views/auth/`

**Files:**
- `login.blade.php` - Custom login page with Smolie branding
  * Uses Bootstrap 5
  * Poppins font family
  * Red/coral color scheme (#C0392B)
  * Split layout with image on left, form on right
  * Responsive design
  
- `register.blade.php` - Registration page (exists but content not shown)

### Authentication Routes (`routes/web.php`)
- **Login/Register:** Uses Laravel Fortify (configured in `app/Providers/FortifyServiceProvider.php`)
- **Fortify Configuration:** `config/fortify.php`

### Login Flow:
1. User submits credentials to login view
2. Fortify handles authentication
3. If authenticated, user is stored in session
4. HomeController redirects based on usertype:
   - `pembeli` (customer) → Redirected to `pembeli.index`
   - `admin` or `kasir` → Redirected to dashboard (home.blade.php)

### Middleware Protection:
- `auth`: Protects routes requiring login
- `AksesAdmin`: Restricts access to admin/kasir only (custom middleware)
- `Kasir`: Additional kasir-specific middleware

---

## 5. HOME/DASHBOARD & ADMIN PAGES

### Home Controller (`app/Http/Controllers/HomeController.php`)
```php
- index(): Routes user based on role
  * If role == 'user' (or 'pembeli'): Redirect to pembeli.index
  * Else (admin/kasir): Return home.blade.php dashboard
```

### Dashboard View (`resources/views/home.blade.php`)
- Styled with hero banner (gradient red/coral)
- Menu card system with hover effects
- Responsive grid layout
- Displays different menu options for admin/kasir staff
- Components include:
  * Product management
  * Category management
  * Transaction reports
  * Chat interface

### Admin Views Structure (`resources/views/admin/`)
- **reviews/**: Review management pages
- Other admin functionality integrated into main dashboard

---

## 6. PROFILE & USER PAGES

### Profile Controller (`app/Http/Controllers/ProfileController.php`)
**Methods:**
```php
- index(): Display user profile
  * View: profile.blade.php
  * Shows user info with profile photo
  
- edit(): Show edit form
  * View: profile/edit.blade.php
  * Form for updating profile
  
- update(): Save profile changes
  * Validates: name, email, foto, password
  * Handles file upload for profile photo
  * Creates img/user directory if needed
  * File naming: {timestamp}_{sanitized_filename}
  * Deletes old photo if new one uploaded
  * Updates password only if provided
```

### Profile Views (`resources/views/profile/`)

#### `profile.blade.php` (View Profile)
- Extends: layouts/master
- Layout: Two-column design
  * Left: Profile photo (circular with online indicator)
  * Right: User details
- Displays: Name, email, phone, address, gender
- Includes: Edit profile button
- Shows role badge (admin/pembeli/kasir)
- Uses theme variables (--theme-primary, --theme-secondary)

#### `profile/edit.blade.php` (Edit Profile)
- Two-column layout:
  * Left: Photo preview
  * Right: Edit form
- Form fields:
  * Foto (file upload)
  * Nama Lengkap
  * Email
  * Password (optional)
  * Password Confirmation
- Uses POST method (not PUT despite PUT in route)
- Handles file upload to `public/img/user/`
- Shows validation errors

### Routes:
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
```

---

## 7. PAYMENT IMPLEMENTATION

### Payment Methods Supported:
1. **Tunai (Cash)** - Default payment method
2. **QRIS** - Indonesian digital payment standard

### Payment Fields in Transaksi:
```php
metode_pembayaran: string (default: 'tunai')
- Enum values: 'tunai', 'qris'
```

### Payment Handling in Checkout (`PembeliController::checkout()`)
- User selects payment method during checkout
- Validated with: `'metode_pembayaran' => 'required|in:tunai,qris'`
- Stored in transaksi table
- Can be used for payment processing or display purposes

### Current Status:
- **NO INTEGRATED PAYMENT GATEWAY** (Midtrans, Stripe, PayPal, etc.)
- QRIS is stored but not actively processed
- No external payment API integration
- Payment is recorded but collection happens offline

### Potential Payment Flow:
```
User selects QRIS → Generate QRIS code → Display to user → 
User scans & pays → Manual or automated verification → Status update
```

---

## 8. TRANSACTION & CHECKOUT FLOW

### Customer Journey:

#### 1. **Browse Products** (`PembeliController::index()`)
- Route: `GET /`
- Shows all products with categories
- Products loaded with categories
- No purchase yet

#### 2. **Add to Cart** (`PembeliController::addToCart()`)
- Route: `POST /add-to-cart/{id}`
- Stores in Laravel Session
- Handles customizations:
  * Warna (Color)
  * Kemasan (Packaging) - Tile (+Rp1000), Box (+Rp2500)
  * Ekstra (Extras) - Sablon (+Rp500), Kartu Ucapan (+Rp300)
  * Catatan Desain (Design notes)
  * Upload file desain
- Cart structure:
  ```php
  [
    product_id => [
      'name' => product name,
      'quantity' => qty,
      'price' => final price (with add-ons),
      'image' => product image,
      'note' => customization notes
    ]
  ]
  ```

#### 3. **View Cart** (`PembeliController::cart()`)
- Route: `GET /cart`
- View: `pembeli/cart.blade.php`
- Shows all items with customizations
- Update quantities
- Remove items
- Select items for checkout

#### 4. **Set Delivery Type** (`PembeliController::setLayanan()`)
- Route: `GET /set-layanan/{tipe}`
- Options: 'dine_in', 'takeaway', 'delivery'
- Stored in session: `jenis_pesanan`

#### 5. **Checkout** (`PembeliController::checkout()`)
- Route: `POST /checkout`
- **Requirements:**
  * Must be logged in
  * Cart not empty
  * Stock available for all items
  
- **Form Validation:**
  ```php
  - no_hp: required, numeric
  - metode_pembayaran: required, in:tunai,qris
  - alamat_pengiriman: required if delivery
  - detail_rumah: optional, for delivery
  ```

- **Process:**
  1. Validate all items have stock
  2. Calculate total price
  3. Begin DB transaction
  4. Create header transaksi record
  5. Create detail_transaksi for each item
  6. Update product stock
  7. Commit transaction
  8. Clear cart from session
  9. Redirect to confirmation

- **Transaction Code Generation:**
  ```php
  'TRX-' . time() . rand(100,999)
  // Example: TRX-1713586947456
  ```

#### 6. **Order History** (`PembeliController::riwayat()`)
- Route: `GET /riwayat-pesanan`
- View: `pembeli/riwayat.blade.php`
- Shows customer's past transactions
- Ordered by created_at desc
- Can view/print invoice

#### 7. **Invoice/Struk**
- Admin struk (thermal printer): `TransaksiController::cetakStruk()`
- Customer invoice (A4 PDF): `TransaksiController::cetakInvoice()`
- PDF generation using Barryvdh\DomPDF
- Custom paper sizes for struk

---

## 9. ADMIN/KASIR MANAGEMENT

### Admin Dashboard Routes (`routes/web.php`)
```php
// Products
GET /tampil-produk - List products
GET /tambah-produk - Create form
POST /tampil-produk - Store
GET /produk/edit/{id} - Edit form
POST /produk/edit/{id} - Update
POST /produk/delete/{id} - Delete

// Categories
GET /tampil-kategori - List
GET /tambah-kategori - Create form
POST /tampil-kategori - Store
GET /kategori/edit/{id} - Edit form
POST /kategori/edit/{id} - Update
POST /kategori/delete/{id} - Delete

// Transactions
GET /transaksi - List all transactions
POST /transaksi/selesai/{id} - Mark as complete
GET /transaksi/struk/{id} - Print thermal receipt

// Reports
GET /laporan - View reports
GET /laporan/export/excel - Export Excel
GET /laporan/export/pdf - Export PDF

// Reviews
GET /admin/ulasan - View customer reviews

// Chat
GET /admin/chat - Chat with customers
GET /admin/chat/messages/{user_id} - Get messages
```

### Controllers Involved:
- `ProdukController`: CRUD operations for products
- `KategoriController`: CRUD operations for categories
- `TransaksiController`: Transaction management
- `LaporanController`: Report generation and exports
- `ReviewController`: Review management
- `ChatController`: Customer communication

### Admin Middleware:
- Protected by `['auth', AksesAdmin::class]` or just `['auth']`
- Requires user to be authenticated
- AksesAdmin checks for admin/kasir role

---

## 10. VIEW STRUCTURE

### Layout Components (`resources/views/layouts/`)
- `master.blade.php`: Main layout template
- `navbar.blade.php`: Navigation bar
- `footer.blade.php`: Footer
- `flash-message.blade.php`: Flash/notification messages
- `validasi.blade.php`: Validation error display

### Customer Views (`resources/views/pembeli/`)
- `index.blade.php`: Product catalog/menu
- `cart.blade.php`: Shopping cart
- `riwayat.blade.php`: Order history
- `invoice_pdf.blade.php`: PDF invoice template

### Admin/Manager Views (`resources/views/admin/`)
- `reviews/`: Review management
- Additional admin pages for products, categories, etc.

### Product Views (`resources/views/produk/`)
- Product management templates

### Category Views (`resources/views/kategori/`)
- Category management templates

### Transaksi Views (`resources/views/transaksi/`)
- `index.blade.php`: Transaction list
- `struk.blade.php`: Thermal receipt template

### Report Views (`resources/views/laporan/`)
- Report templates

### Other Views:
- `home.blade.php`: Dashboard
- `profile.blade.php`: User profile view
- `profile/edit.blade.php`: Profile edit form
- `welcome.blade.php`: Welcome/landing page

---

## 11. KEY FEATURES IMPLEMENTED

✅ **Authentication**
- Laravel Fortify integration
- Role-based user types (pembeli, admin, kasir)

✅ **Product Management**
- CRUD operations
- Category system
- Stock management
- Product images
- Product status (aktif/non-aktif)

✅ **Shopping Cart**
- Session-based cart
- Item customization (color, packaging, extras)
- Custom design notes and file uploads
- Dynamic pricing based on add-ons

✅ **Transactions**
- Order creation with details
- Multiple delivery options (dine-in, takeaway, delivery)
- Payment method selection (cash, QRIS)
- Unique transaction codes
- Order status tracking (pending, selesai)

✅ **User Profile**
- Profile view and edit
- Photo upload
- Personal information (name, email, phone, address, gender)
- Password management

✅ **Reporting**
- Transaction reports
- Excel export
- PDF export
- Chart generation

✅ **Reviews**
- Customer reviews/ratings
- Review management by admin

✅ **Messaging/Chat**
- Customer-admin communication
- Message read status

✅ **Admin Dashboard**
- Transaction overview
- Product/Category management
- Report generation
- Customer communication
- Review management

---

## 12. POTENTIAL GAPS & IMPROVEMENTS NEEDED

❌ **Missing/Incomplete Features:**
1. No integrated payment gateway for QRIS
2. TransactionController referenced but doesn't exist
3. Models lack proper relationships (should add hasMany, etc.)
4. No email notifications for orders
5. No order status email updates
6. Limited inventory management (no stock alerts)
7. No customer delivery tracking
8. No admin verification of orders before processing
9. No payment verification system
10. Chat system basic (no file sharing in chat)
11. No SMS notifications
12. No multi-language support

❌ **Security Concerns:**
1. Transaksi model uses `$guarded = []` instead of `$fillable`
2. No rate limiting on API endpoints
3. File upload validation could be stricter
4. QRIS payment not verified server-side
5. No CSRF protection mentioned for some routes

❌ **Best Practice Issues:**
1. Routes could be organized in route groups better
2. Some legacy routes still exist (tanpa-login versions)
3. Mix of REST and non-REST conventions
4. No API versioning if APIs exist
5. Limited error handling in controllers

---

## 13. CONFIGURATION FILES

**Key Config Files:**
- `config/app.php`: App configuration
- `config/auth.php`: Authentication settings
- `config/database.php`: Database connection
- `config/fortify.php`: Laravel Fortify settings
- `config/excel.php`: Maatwebsite Excel
- `config/filesystems.php`: File storage
- `config/mail.php`: Email settings
- `config/session.php`: Session configuration

**Middleware:**
- `app/Http/Middleware/AksesAdmin.php`: Admin access control
- `app/Http/Middleware/Kasir.php`: Cashier access control
- Built-in: auth, web, etc.

---

## 14. DEPLOYMENT NOTES

**Dependencies:**
- Laravel 11
- PHP 8.0+
- Database: MySQL/MariaDB/PostgreSQL
- Barryvdh\DomPDF for PDF generation
- Maatwebsite\Excel for Excel export
- Laravel Fortify for authentication

**Setup Steps:**
1. `composer install`
2. `cp .env.example .env`
3. `php artisan key:generate`
4. Configure database in .env
5. `php artisan migrate`
6. Create storage/app/public link: `php artisan storage:link`
7. `npm install && npm run build` (for frontend assets)
8. Start: `php artisan serve`

**File Permissions:**
- `storage/` directory must be writable
- `bootstrap/cache/` must be writable
- `public/img/` directories must be writable

---

## 15. DATABASE SCHEMA SUMMARY

**Main Tables:**
- users (authentication & user data)
- transaksi (orders)
- detail_transaksi (order items)
- kategori (product categories)
- produk (products)
- reviews (customer reviews)
- messages (customer-admin chat)

**Support Tables:**
- password_reset_tokens
- sessions
- cache
- jobs
- failed_jobs

---

## Conclusion

This is a **complete e-commerce platform** for a gift shop with:
- Full customer ordering system
- Admin/Kasir management dashboard
- Product and category management
- Order tracking
- Customer reviews
- Basic messaging system
- Report generation

**Main area for enhancement:** Integrate a proper payment gateway for QRIS payment processing and add automated order notifications.
