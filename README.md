# Saldo Wallet Platform

A production-grade international fintech wallet platform built with Laravel 11.

## Features

- **Multi-Currency Wallet**: Support for USD, COP, EUR, MXN, ARS, BRL.
- **Secure Authentication**: PIN-based transaction security, Password hashing.
- **KYC Verification**: Identity verification workflow (admin approval).
- **Transfers**: Send money via email or phone.
- **Currency Exchange**: Real-time (simulated) exchange rates.
- **Admin Panel**: Manage users, KYC requests, and transactions.
- **API-First**: Full REST API for mobile apps.

## Tech Stack

- **Backend**: Laravel 11, MySQL
- **Frontend**: Blade, TailwindCSS, Alpine.js
- **API**: Laravel Sanctum

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/saldo-wallet.git
   cd saldo-wallet
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment Setup**
   Copy `.env.example` to `.env` and configure your database credentials.
   ```bash
   cp .env.example .env
   ```

4. **Generate Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations & Seeders**
   This will create the database tables and seed currencies + default admin.
   ```bash
   php artisan migrate --seed
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```

## Admin Access

- **URL**: `/admin/dashboard` (or `http://admin.saldo.com.co` if configured)
- **Email**: `admin@saldo.com.co`
- **Password**: `password`

## API Documentation

The API is available at `/api`.
- `POST /api/register`
- `POST /api/login`
- `POST /api/wallet/transfer`
- `GET /api/wallet/balance`

## Domain Setup (Optional)

To use the domain structure (`pay.saldo.com.co`, `admin.saldo.com.co`), configure your local hosts file or web server (Nginx/Apache) to point these domains to the `public` directory.

## License

MIT
