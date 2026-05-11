# OdontoManage Backend

API REST backend for the **OdontoManage** dental management system. Built with **Symfony 7.4**, **Doctrine ORM**, **JWT authentication**, and **MySQL 8.0+**.

---

## 🏗️ Tech Stack

- **Framework**: Symfony 7.4.*
- **API**: Custom Symfony controllers returning JSON responses
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

```bash
# macOS/Linux
php -m | grep -E "openssl|sodium|pdo_mysql"

# Windows PowerShell
php -m | Select-String "openssl|sodium|pdo_mysql"
```

---

## 🚀 Installation & Setup

### 1. Clone Repository & Install Dependencies

```bash
git clone <your-repo-url>
cd OdontoManage-Backend
composer install
```

### 2. Generate JWT Keys (MANDATORY)

```bash
mkdir -p config/jwt

# macOS/Linux
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

# Windows (PowerShell with OpenSSL installed)
openssl genrsa -out config/jwt/private.pem 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

⚠️ **Do NOT add a passphrase** (development only). Press Enter when prompted.

### 3. Configure Environment Variables

```bash
# macOS/Linux
cp .env .env.local

# Windows PowerShell
Copy-Item .env .env.local
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

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. Load Fixtures (Recommended for Development)

If you want sample data in the database, load the Doctrine fixtures after running the migrations:

```bash
php bin/console doctrine:fixtures:load --no-interaction
```

This command replaces the current data in the database with the fixture data.

### 6. Start the Server

#### Using Symfony CLI (Recommended)
```bash
symfony server:start
```

#### Using PHP Built-in Server
```bash
php -S 127.0.0.1:8000 -t public
```

**API available at**: `http://localhost:8000`

---

## 📁 Project Structure

```
src/
├── Command/           # CLI commands
├── Constants/         # Application constants
├── Controller/        # API endpoints
├── DataFixtures/      # Doctrine fixtures for demo/test data
├── Entity/           # Doctrine entities (models)
├── EventListener/    # Symfony event handlers
├── Repository/       # Doctrine repositories (data access)
├── Service/          # Business logic services
└── Validator/        # Custom validation rules

config/
├── jwt/              # JWT keys (private.pem, public.pem)
├── packages/         # Bundle configurations
└── routes/           # API routing definitions

migrations/           # Database migrations
```

### 🏗️ Controller Architecture

#### PatientController

```mermaid
classDiagram
    class PatientController {
        -PatientRepository patientRepository
        -EntityManagerInterface entityManager
        -SerializerInterface serializer
        -ValidatorInterface validator
        +index() JsonResponse
        +show(Patient) JsonResponse
        +create(Request) JsonResponse
        +update(Patient, Request) JsonResponse
        +patch(Patient, Request) JsonResponse
        +delete(Patient) JsonResponse
        +uploadProfileImage(Patient, Request) JsonResponse
        +removeProfileImage(Patient) JsonResponse
    }
    
    class Patient {
        -int id
        -string nationalId
        -string firstName
        -string lastName
        -DateTime birthDate
        -Odontogram[] odontograms
        -Appointment[] appointments
        -Document[] documents
        -string profileImageName
    }
    
    PatientController --> Patient : manages
    Patient --> Odontogram : has many
    Patient --> Appointment : has many
    Patient --> Document : has many
```

#### OdontogramController

```mermaid
classDiagram
    class OdontogramController {
        -OdontogramRepository odontogramRepository
        -PatientRepository patientRepository
        -PathologyRepository pathologyRepository
        -TreatmentRepository treatmentRepository
        -ToothRepository toothRepository
        -EntityManagerInterface entityManager
        -SerializerInterface serializer
        -ValidatorInterface validator
        +index() JsonResponse
        +show(Odontogram) JsonResponse
        +getByPatient(int) JsonResponse
        +create(Request) JsonResponse
        +update(Odontogram, Request) JsonResponse
        +patch(Odontogram, Request) JsonResponse
        +delete(Odontogram) JsonResponse
    }
    
    class Odontogram {
        -int id
        -Patient patient
        -Appointment appointment
        -string type
        -ToothPathology[] toothPathologies
        -ToothTreatment[] toothTreatments
        -BridgeTreatment[] bridgeTreatments
    }
    
    class ToothPathology {
        -Tooth tooth
        -Pathology pathology
        -int toothFace
    }
    
    class ToothTreatment {
        -Treatment treatment
        -int toothNumber
        -int toothFace
        -string status
    }
    
    class BridgeTreatment {
        -Treatment treatment
        -int startTooth
        -int endTooth
        -string status
    }
    
    OdontogramController --> Odontogram : manages
    Odontogram --> ToothPathology : has many
    Odontogram --> ToothTreatment : has many
    Odontogram --> BridgeTreatment : has many
```

#### AppointmentController

```mermaid
classDiagram
    class AppointmentController {
        -AppointmentRepository appointmentRepository
        -PatientRepository patientRepository
        -DentistRepository dentistRepository
        -TreatmentRepository treatmentRepository
        -BoxRepository boxRepository
        -ClinicSchedulePolicy clinicSchedulePolicy
        -EntityManagerInterface entityManager
        -SerializerInterface serializer
        -ValidatorInterface validator
        +index(Request) JsonResponse
        +show(Appointment) JsonResponse
        +create(Request) JsonResponse
        +getByPatient(int) JsonResponse
        +getByDentist(int) JsonResponse
        +getByBox(int) JsonResponse
        +getAvailableDentistsByTreatment(int, Request) JsonResponse
        +getAvailableTreatmentsByDentist(int) JsonResponse
    }
    
    class Appointment {
        -int id
        -Patient patient
        -Dentist dentist
        -Treatment treatment
        -Box box
        -DateTime visitDate
        -string consultationReason
    }
    
    class Treatment {
        -int id
        -string name
        -int durationMinutes
        -Dentist[] dentists
    }
    
    AppointmentController --> Appointment : manages
    Appointment --> Patient : references
    Appointment --> Dentist : references
    Appointment --> Treatment : references
    Appointment --> Box : references
```
#### DocumentController

```mermaid
classDiagram
    class DocumentController {
        -DocumentRepository documentRepository
        -PatientRepository patientRepository
        -EntityManagerInterface entityManager
        -SerializerInterface serializer
        +getByPatient(int) JsonResponse
        +upload(Request) JsonResponse
        +delete(Document) JsonResponse
    }
    
    class Document {
        -int id
        -Patient patient
        -string type
        -string name
        -DateTime captureDate
        -string documentFile
    }
    
    DocumentController --> Document : manages
```
---

## 🔑 Bundles & Features

| Bundle | Purpose |
|--------|---------|
| **API Platform** | REST API generation from entities |
| **Doctrine ORM** | Database abstraction & ORM |
| **JWT Auth** | Token-based authentication |
| **Vich Uploader** | File/image upload management |
| **CORS** | Cross-Origin Resource Sharing |
| **Messenger** | Asynchronous message handling |

---

## 🛠️ Useful Commands

```bash
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

## 📚 API Documentation

This project does not include API Platform-generated documentation. Route definitions live in the controllers under `src/Controller/` and the application exposes JSON endpoints directly.

---

## 🐛 Troubleshooting

### Database connection failed
```bash
# Check MySQL is running (macOS with Homebrew)
brew services list

# Or check via MySQL CLI
mysql -u root -p

# Verify DATABASE_URL in .env.local
php bin/console dbal:run-sql "SELECT 1"
```
- [Doctrine ORM](https://www.doctrine-project.org/)
- [JWT Bundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

---

### OdontoManage Frontend
[OdontoManage-Frontend](https://github.com/Deivd730/OdontoManage-Frontend)

---

## 👥 Contributors

OdontoManage Backend Development Team

---

**Last Updated**: April 29, 2026 | **Framework**: Symfony 7.4 | **Database**: MySQL 8.0+


