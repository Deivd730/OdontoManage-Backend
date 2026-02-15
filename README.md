# OdontoManage Backend

Symfony backend for the OdontoManage project.

## Prerequisites

- PHP 8.2 or newer
- Composer 2
- MySQL 8+ (or MariaDB equivalent)
- Symfony CLI (optional, but recommended)

## 1. Clone and Install Dependencies

```bash
git clone <your-repo-url>
cd OdontoManage-Backend
composer install
```

## 2. Configure Environment Variables

Create a local environment file if you do not have one yet:

```bash
cp .env .env.local
```

PowerShell alternative:

```powershell
Copy-Item .env .env.local
```

Set your database connection in `.env.local` using MySQL format:

```dotenv
DATABASE_URL="mysql://DB_USER:DB_PASSWORD@127.0.0.1:3306/DB_NAME?serverVersion=8.0.32&charset=utf8mb4"
```

Notes:
- Replace `DB_USER`, `DB_PASSWORD`, and `DB_NAME` with your real values.
- If you use MariaDB, update `serverVersion` accordingly (for example `10.11.2-MariaDB`).

## 3. Create Database and Run Migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## 4. Run the Project

With Symfony CLI:

```bash
symfony server:start
```

Without Symfony CLI:

```bash
php -S 127.0.0.1:8000 -t public
```

The API/backend will be available at `http://127.0.0.1:8000`.

## 5. Run Tests

```bash
php bin/phpunit
```

## Useful Commands

```bash
php bin/console cache:clear
php bin/console debug:router
php bin/console doctrine:migrations:status
```
