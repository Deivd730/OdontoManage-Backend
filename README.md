# OdontoManage Backend

Symfony API backend for the **OdontoManage** project.

---

## 📋 Prerequisites

Before starting, make sure you have:

- PHP **8.2 or newer**
- Composer 2
- MySQL 8+ (or MariaDB equivalent)
- Symfony CLI (optional but recommended)

---

## 🔐 Required PHP Extensions (IMPORTANT)

This project uses JWT authentication.

Make sure the following PHP extensions are enabled:

- `openssl`
- `sodium`

Check with:

```bash
php -m


You must see:

openssl
sodium

⚠️ Windows (XAMPP) Users Only

If you are using XAMPP on Windows:

Open:

C:\xampp\php\php.ini


Enable these lines (remove ; if present):

extension=openssl
extension=sodium


Restart Apache from the XAMPP Control Panel.

Verify again:

php -m

🚀 1. Clone and Install Dependencies
git clone <your-repo-url>
cd OdontoManage-Backend
composer install

🔐 2. Generate JWT Keys (MANDATORY)

Each developer must generate their own JWT keys locally.

Create the folder:

mkdir config/jwt

🖥 Windows (XAMPP)
C:\xampp\apache\bin\openssl.exe genrsa -out config/jwt/private.pem 4096
C:\xampp\apache\bin\openssl.exe rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

🍎 Mac / Linux
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem


⚠️ Do NOT add a passphrase (development only).

⚙️ 3. Configure Environment Variables

Copy .env to .env.local:

Mac / Linux
cp .env .env.local

PowerShell (Windows)
Copy-Item .env .env.local

Edit .env.local

Set your database connection:

DATABASE_URL="mysql://DB_USER:DB_PASSWORD@127.0.0.1:3306/DB_NAME?serverVersion=8.0.32&charset=utf8mb4"


Replace:

DB_USER

DB_PASSWORD

DB_NAME

⚠️ JWT variables are already configured in .env.
Do NOT modify them.

🗄 4. Create Database and Run Migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

▶ 5. Run the Project
With Symfony CLI (recommended)
symfony server:start

Without Symfony CLI
php -S 127.0.0.1:8000 -t public


The backend will be available at:

http://127.0.0.1:8000

🧪 Run Tests
php bin/phpunit

📌 Important Notes

/config/jwt/*.pem is ignored by Git.

Each developer must generate their own keys.

Never commit .pem files.

.env is shared and committed.

.env.local is personal and not committed.

Do NOT store production secrets in committed files.

🔎 Useful Commands
php bin/console cache:clear
php bin/console debug:router
php bin/console doctrine:migrations:status

✅ Setup Checklist

If everything is correct:

composer install runs without errors

openssl and sodium are enabled

JWT keys exist in config/jwt

Database is created successfully

Server starts without errors

You are ready to start development.


---