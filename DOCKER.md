# Docker Setup Guide

Quick start guide for running this Laravel application with Docker.

## Prerequisites

- Docker installed
- Docker Compose installed
- Make   for easier commands

## Quick Start

### Option 1: Using Make (Recommended)
```bash
  make setup
```

This will:
- Start all containers
- Install dependencies
- Generate app key
- Run migrations and seeders
- Cache config

Access the application at: http://localhost:8000

### Option 2: Manual Setup
```bash
    docker-compose up -d
    docker-compose exec app composer install
    docker-compose exec app cp .env.docker .env
    docker-compose exec app php artisan key:generate
    docker-compose exec app php artisan migrate:fresh --seed
```

## Available Commands

### Container Management
```bash
    make up          
    make down        
    make restart     
    make logs        
```

### Application Commands
```bash
    make shell       
    make test        
    make fresh       
    make migrate     
    make seed        
```

### Testing Endpoints

Transaction Report:
```bash
  curl http://localhost:8000/api/v1/transaction-report
```

Aggregation:
```bash
  curl http://localhost:8000/api/v1/aggregation
```

Prime Analysis:
```bash
  make prime LIMIT=1000
# Or manually:
  docker-compose exec app php artisan primes:analyze 1000
```

## Services

- **App**: Laravel application (port 8000)
- **Database**: MySQL 8.0 (port 3308)
- **Redis**: Caching (port 6380)

## Database Access

From host machine:
```bash
  mysql -h 127.0.0.1 -P 3308 -u laravel -psecret laravel
```

From inside container:
```bash
  docker-compose exec db mysql -u laravel -psecret laravel
```

### Run Artisan Commands
```bash
  docker-compose exec app php artisan [command]
```

Or with Make:
```bash
    make artisan migrate
    make artisan cache:clear
```

### Run Tests
```bash
  make test
```

### View Logs
```bash
  make logs
```

### Clean Everything
```bash
  make clean
```

This removes all containers, volumes, and cached data.

## Troubleshooting

### Connection Refused Error

Wait 10-20 seconds for MySQL to fully start:
```bash
  docker-compose logs db
```

### Permission Issues
```bash
  docker-compose exec app chown -R www-data:www-data /var/www
```

### Reset Everything
```bash
    make clean
    make setup
```

## Port Configuration

- Application: 8000
- MySQL: 3308 (external) → 3306 (internal) # that to prevent any local ip interference ports
- Redis: 6380 (external) → 6379 (internal) # that to prevent any local ip interference ports

## Environment Variables

See `.env.docker` for Docker-specific configuration.
