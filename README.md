# Laravel Shopify App

A full-stack application for managing Shopify products with Laravel backend and Vue.js frontend.

## Prerequisites

- Docker and Docker Compose
- Shopify store credentials (API Key and Access Token)
- MySQL database

## Installation

### 1. Set File Permissions

Change ownership of the project files to your user:

```bash
sudo chown -R $USER .
```

### 2. Build Docker Containers

Build all Docker services:

```bash
docker compose build
```

### 3. Environment Configuration

Create the `.env` file from the example:

```bash
cp .env.example .env
```

Edit `.env` and fill in the required variables:

- Database credentials
- Shopify API credentials (Store URL, API Key, Access Token)
- App URL and configuration

### 4. Install Backend Dependencies

Install Composer dependencies:

```bash
docker compose run --rm backend composer install
```

### 5. Generate Application Key

Generate the Laravel application key:

```bash
docker compose run --rm backend php artisan key:generate
```

### 6. Database Setup

Create a MySQL database with the name specified in your `.env` file (default: `laravel_shopify_app`)

### 7. Run Migrations

Run database migrations:

```bash
docker compose run --rm backend php artisan migrate
```

### 8. Sync Shopify Products

Sync products from your Shopify store:

```bash
docker compose run --rm backend php artisan shopify:sync-products
```

## Running the Application

Start all services:

```bash
docker-compose up -d
```

The application will be available at:

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:8000
- **Nginx Proxy**: http://laravel-shopify-app.local

## Services

- **db**: MySQL 8 database (port 3306)
- **backend**: Laravel API (port 8000)
- **frontend**: Vue.js + Vite (port 5173)
- **nginx-proxy**: Nginx reverse proxy (port 80)
- **nginx**: Application web server

## Project Structure

```
.
├── backend/          # Laravel application
├── frontend/         # Vue.js application
├── .docker/           # Docker configuration files
├── docker-compose.yml
└── README.md
```

## Available Commands

### Backend

```bash
# Run artisan commands
docker compose run --rm backend php artisan [command]

# Run composer commands
docker compose run --rm backend composer [command]

# Run tests
docker compose run --rm backend php artisan test
```

### Frontend

```bash
# Install npm dependencies
docker compose run --rm frontend npm install

# Run development server
docker compose run --rm frontend npm run dev

# Build for production
docker compose run --rm frontend npm run build
```

## Development

### Backend

- **GraphQL API endpoint**: `/graphql`

### Frontend

- Built with **Vue 3 + TypeScript**
- **Vite** for fast development
- **TailwindCSS** for styling

## Troubleshooting

### Permission Issues

If you encounter permission issues, ensure Docker has proper access:

```bash
sudo chown -R $USER:$USER .
chmod -R 755 storage bootstrap/cache
```

### Database Connection Issues

Verify your database credentials in `.env` and ensure the MySQL container is running:

```bash
docker compose ps
```

## License

This project is open-sourced software licensed under the MIT license.
