<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# To-do app (Laravel+Vue)

![Tests](https://github.com/damianchojnacki/to-do-laravel-vue/actions/workflows/tests.yml/badge.svg)

Visit demo app at https://to-do.damianchojnacki.com.

## Features

- Browse, filter and sort tasks in elegant table view.
- Responsive and user-friendly design powered by `shadcn/vue` components.
- API Docs generated using Laravel Scramble.
- Fully containerized setup for development and production using Docker.
- Continuous Integration (CI) pipeline with GitHub Actions.

## Technologies Used

### Backend
- **Laravel 12** for building a robust REST API.
- **PHPStan** and **PHPUnit** for static analysis and testing.
- **Rector** for automatic refactoring.
- **Laravel Pint** for code linting.
- **FrankenPHP** and **Caddy** server.

### Frontend
- **Vue.js** for building performant user interface.
- **TailwindCSS + shadcn/vue** for styling.
- **ESLint** for TypeScript linting.
- **Vitest** for component testing.

### Infrastructure
- **Laravel Sail** for development environment.
- **Docker** and **Docker Compose** for production environments.
- **GitHub Actions** for CI, running tests and linters.

## Setup Instructions

### Prerequisites

- Docker and Docker Compose installed.
- Laravel Sail alias - see [Laravel documentation](https://laravel.com/docs/11.x/sail#configuring-a-shell-alias)

### Development

#### Automatic method

Run setup script to automatically setup project:
    
```bash
chmod +x ./setup.sh
./setup.sh
```

#### Manual method

1. **Clone the repository:**
   ```bash
   git clone https://github.com/damianchojnacki/to-do-laravel-vue.git
   cd to-do-laravel-vue
   ```

2. **Setup project:**
   ```bash
   cp .env.example .env
   
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    bash -c 'composer install --ignore-platform-reqs; php artisan key:generate'
   ```

2. **Start the development environment:**
   ```bash
   sail up -d
   ```

3. **Populate the database**
   ```bash
   sail artisan migrate --seed
   ```

4. **Access the application:**
    - App: `http://localhost`
    - API: `http://localhost/api`
    - API Docs: `http://localhost/docs`

### Testing

- **Backend tests:**
    ```bash
    sail composer test:unit
    ```

- **Frontend tests:**
    ```bash
    sail npm run test
    ```

- **Static analysis (PHPStan, ESLint):**
    ```bash
    sail composer test:static
    sail npm run lint
    ```

### Deploying to production

1. Fill the required env:
- APP_KEY
- APP_URL
- DATABASE_URL (for sqlite database)

2. Start:
    ```bash
    docker compose -f docker-compose.yml -f docker-compose.prod.yml up -d --wait
    ```

## CI/CD

The project uses GitHub Actions for automated testing and linting:

- **Backend CI Pipeline:**
    - PHPStan for static analysis.
    - PHPUnit for unit tests.

- **Frontend CI Pipeline:**
    - ESLint for linting.
    - Vitest for component tests.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
