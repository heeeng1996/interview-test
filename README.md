# Laravel Sanctum E-Commerce API (UUID Architecture)

A secure, high-performance RESTful API ecosystem engineered using Laravel. This platform features stateless, token-driven authentication via Laravel Sanctum, asynchronous and secure cryptographic email verification loops, and a highly optimized product management catalog containing decoupled search metrics, dynamic calculations, and soft-deletion tracking.

---

## Architectural Core Design & Database Ledger

The system moves away from typical incremental sequential row IDs. It uses **UUIDs (Universally Unique Identifiers)** for all primary keys across the database schema, improving horizontal scaling security and database-merging safety across distributed systems.

* **Users Table Ledger:** Stores account records, manages encrypted passwords via `bcrypt`, and maintains timestamp records for verification state tracking (`email_verified_at`).
* **Categories Table Ledger:** Functions as a one-to-many (`hasMany`) root bucket containing products grouped under structural department tags.
* **Suppliers Table Ledger:** Houses vendor contact registries linked to items through an explicit many-to-many (`belongsToMany`) relationship.
* **Product-Supplier Pivot Bridge:** A middle pivot bridge (`product_supplier`) linking `product_uuid` string identifiers to `supplier_uuid` references.
* **Products Table Ledger:** The transactional model featuring:
    * **Soft Deletes:** Implements `deleted_at` filters ensuring broken catalog relationships do not break historic sales summaries.
    * **Eloquent Filter Scopes:** Processes multi-criteria input grids inside single index queries (`category_id`, `min_price`, `max_price`, and custom `stock_level` groupings).
    * **Computed Accessor:** A dynamic calculator appending a virtual `final_price` property using active `discount` metrics before generating output structures.

---

## Installation, Setup & Environment Provisioning

Follow these steps sequentially to deploy your local instance of the application.

### 1. Engine Prerequisites
Ensure your infrastructure matches the following system configuration metrics:
* **PHP Engine:** Version `>= 8.2`
* **Composer:** Dependency Manager Version `>= 2.5`
* **Database Engine:** MySQL `>= 8.0` or MariaDB `>= 10.4`
* **SMTP Mail Server:** Access to an active mail trap/sandbox collector (e.g., Mailpit, Mailtrap)

### 2. File Initialization & Dependency Mapping
Clone the repository to your workspace, navigate into the root folder, and compile your local dependency maps:
```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 3. Database & Mail Setup
Open your freshly created .env file in your preferred text editor and configure your database socket credentials and SMTP mail loop settings:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@ecommerce-api.local"
```

(Note: Create an empty database schema in your database engine matching the DB_DATABASE value specified above before continuing).

### 4. Database Ledger Migrations & Seeding
Execute the schema migration builder to generate the tables using UUID architectures and run the database seeds to populate initial configuration records:
```bash
php artisan migrate:fresh --seed
```

### 5. Link Storage Directory (Optional)
If your product management layer handles physical image or asset uploads, link the public storage disk to your web root:
```bash
php artisan storage:link
```

### 6. Launch the Local Development Server
Boot up Laravel's integrated development server engine:
```bash
php artisan serve
```
The application interface will initialize locally at http://127.0.0.1:8000.

### Automated Testing
This application ships with a comprehensive test suite covering validation boundaries, route access guards, and cascading relationship rules. To run the automated feature tests, run:
```bash
php artisan test
```