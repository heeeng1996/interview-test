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