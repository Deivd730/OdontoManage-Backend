# OdontoManage Backend

API REST backend for the **OdontoManage** dental management system. Built with **Symfony 7.4** and **MySQL 8.0+**.

---

## 🏗️ Tech Stack

- **Framework**: Symfony 7.4.*
- **Database**: MySQL 8.0+ (or MariaDB 10.11+)
- **Authentication**: JWT (Lexik JWT Authentication Bundle 3.2)
- **ORM**: Doctrine ORM 3.6
- **PHP**: 8.2+
- **File Upload**: Vich Uploader Bundle 2.9
- **CORS**: Nelmio CORS Bundle 2.6

---

## 📋 Prerequisites

Make sure you have installed:

- **PHP 8.2+** with extensions:
  - `openssl` (JWT support)
  - `sodium` (encryption)
  - `pdo_mysql` (MySQL)
  - `ctype` and `iconv`
- **Composer 2** (dependency manager)
- **MySQL 8.0+** or **MariaDB 10.11+** (database)
- **Symfony CLI** (optional but recommended)

### ✅ Verify PHP Extensions

#### Windows (PowerShell)

```powershell
php -m | Select-String -Pattern "openssl|sodium|pdo_mysql"
```

#### macOS (Terminal)

```bash
php -m | grep -E "openssl|sodium|pdo_mysql"
```

---

## 🚀 Installation & Setup

### 1. Clone Repository & Install Dependencies

#### Windows (PowerShell)

```powershell
git clone <your-repo-url>
cd OdontoManage-Backend
composer install
```

#### macOS (Terminal)

```bash
git clone <your-repo-url>
cd OdontoManage-Backend
composer install
```

### 2. Generate JWT Keys (MANDATORY)

#### Windows (PowerShell)

```powershell
New-Item -ItemType Directory -Force -Path config/jwt | Out-Null

openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

If `openssl` is not recognized on Windows/XAMPP, use:

```powershell
& "C:\xampp\apache\bin\openssl.exe" genrsa -out config/jwt/private.pem 4096
& "C:\xampp\apache\bin\openssl.exe" rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

Verify keys were created:

```powershell
Test-Path config/jwt/private.pem
Test-Path config/jwt/public.pem
```

#### macOS (Terminal)

```bash
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

Verify keys were created:

```bash
test -f config/jwt/private.pem && echo "private.pem OK"
test -f config/jwt/public.pem && echo "public.pem OK"
```

⚠️ **Do NOT add a passphrase** (development only). Press Enter when prompted.

### 3. Configure Environment Variables

#### Windows (PowerShell)

```powershell
Copy-Item .env .env.local
```

#### macOS (Terminal)

```bash
cp .env .env.local
```

Edit `.env.local` and set your database URL:

```env
# MySQL 8.0+
DATABASE_URL="mysql://app:password@127.0.0.1:3306/odontomanage?serverVersion=8.0.32&charset=utf8mb4"

# Or MariaDB 10.11+
# DATABASE_URL="mysql://app:password@127.0.0.1:3306/odontomanage?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```

Replace `app` (user), `password`, and `odontomanage` (database name) with your actual credentials.

**JWT configuration is already set in `.env` — do NOT modify it.**

### 4. Create Database & Run Migrations

```powershell
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Start the Server

#### Using Symfony CLI (Recommended)
```powershell
symfony server:start
```

#### Using PHP Built-in Server
```powershell
php -S 127.0.0.1:8000 -t public
```

**API available at**: `http://localhost:8000`

---

## 📁 Project Structure

```text
src/
├── Command/           # CLI commands
├── Constants/         # Application constants
├── Controller/        # API endpoints
├── DataFixtures/      # Test data
├── Entity/            # Doctrine entities (models)
├── EventListener/     # Symfony event handlers
├── Repository/        # Doctrine repositories (data access)
├── Service/           # Business logic services
└── Validator/         # Custom validation rules

config/
├── jwt/               # JWT keys (private.pem, public.pem)
├── packages/          # Bundle configurations
└── routes/            # API routing definitions

migrations/            # Database migrations
```

---

## 🔑 Bundles & Features

| Bundle | Purpose |
|--------|---------|
| **Doctrine ORM** | Database abstraction & ORM |
| **JWT Auth** | Token-based authentication |
| **Vich Uploader** | File/image upload management |
| **CORS** | Cross-Origin Resource Sharing |

---

## 🛠️ Useful Commands

```powershell
# Clear cache
php bin/console cache:clear

# View all routes
php bin/console debug:router

# Check migrations status
php bin/console doctrine:migrations:status

# Create new migration
php bin/console make:migration

# Generate new entity
php bin/console make:entity

# Install dependencies
composer install

# Update dependencies
composer update
```

---

## ⚠️ Important Notes

| File/Folder | Status | Action |
|------------|--------|--------|
| `/config/jwt/*.pem` | ❌ Ignored by Git | Each developer generates locally |
| `.env` | ✅ Committed | Shared configuration defaults |
| `.env.local` | ❌ Not committed | Personal overrides (DO NOT commit) |
| `.env.dev` | ✅ Committed | Development-specific defaults |

---

## 📝 Environment Files

- **`.env`**: Default configuration (committed)
- **`.env.local`**: Your personal overrides (NOT committed — use for secrets)
- **`.env.dev`**: Development-specific values (committed)
- **`.env.test`**: Testing configuration (committed)

---

## 🔒 Security Best Practices

- ✅ Never commit `.pem` files or sensitive `.env.local`
- ✅ Use `.env` for defaults, `.env.local` for secrets
- ✅ Regenerate JWT keys regularly in production
- ✅ Keep Symfony and dependencies updated: `composer update`
- ✅ Use HTTPS in production

---

## 📚 API Base URL

Once the server is running, access:
- **Main API**: `http://localhost:8000/api`

---

## 🐛 Troubleshooting

### Database connection failed

#### Windows (PowerShell)

```powershell
# Check MySQL/MariaDB service status (names may vary)
Get-Service *mysql*,*mariadb*

# Or test database access via CLI
mysql -u root -p

# Verify DATABASE_URL in .env.local
php bin/console dbal:run-sql "SELECT 1"
```

#### macOS (Terminal)

```bash
# Check MySQL/MariaDB service status (Homebrew)
brew services list

# Or test database access via CLI
mysql -u root -p

# Verify DATABASE_URL in .env.local
php bin/console dbal:run-sql "SELECT 1"
```

Useful references:
- [Doctrine ORM](https://www.doctrine-project.org/)
- [JWT Bundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

---

## 👥 Contributors

OdontoManage Backend Development Team

---

**Last Updated**: May 1, 2026 | **Framework**: Symfony 7.4 | **Database**: MySQL 8.0+
