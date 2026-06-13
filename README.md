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