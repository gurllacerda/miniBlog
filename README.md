# miniBlog

A small Laravel-based blog API with Sanctum authentication and a minimal frontend toolchain (Vite + Tailwind). Designed for quick development and demonstration purposes.

## Tech stack
- PHP (Laravel)
- Laravel Sanctum (API authentication)
- Vite + Tailwind CSS
- Composer / NPM

## Requirements
- PHP 8.x
- Composer
- Node.js + npm
- A supported database (MySQL, SQLite, etc.)

## Quick setup

1. Copy environment file and set your secrets:
   ```sh
   cp .env.example .env
   ```
   See [.env.example](.env.example).

2. Install PHP dependencies:
   ```sh
   composer install
   ```

3. Install frontend dependencies and build:
   ```sh
   npm install
   npm run dev
   ```

4. Generate application key and run migrations:
   ```sh
   php artisan key:generate
   php artisan migrate
   ```

5. Start local server:
   ```sh
   php artisan serve
   ```

## Running tests
Run the test suite with PHPUnit:
```sh
./vendor/bin/phpunit
```
Configuration: [phpunit.xml](phpunit.xml).

## API (excerpt)
Routes are defined in [routes/api.php](routes/api.php).

- GET /posts — list posts  
  Controller: [`App\Http\Controllers\Api\PostController`](app/Http/Controllers/Api/PostController.php)

- GET /posts/{post} — show single post  
  Controller: [`App\Http\Controllers\Api\PostController`](app/Http/Controllers/Api/PostController.php)

- POST /posts — create post (requires Sanctum auth)  
  Controller: [`App\Http\Controllers\Api\PostController`](app/Http/Controllers/Api/PostController.php)

- POST /login — authenticate (API)  
  Controller: [`App\Http\Controllers\Auth\AuthenticatedSessionController`](app/Http/Controllers/Auth/AuthenticatedSessionController.php)

- POST /register — register new user  
  Controller: [`App\Http\Controllers\Auth\RegisteredUserController`](app/Http/Controllers/Auth/RegisteredUserController.php)

Protected user route:
- GET /user (middleware: `auth:sanctum`) — returns authenticated user (see [routes/api.php](routes/api.php)).

Example curl (login):
```sh
curl -X POST http://127.0.0.1:8000/api/login -d "email=user@example.com&password=secret"
```

## Useful files
- CLI entry: [artisan](artisan)  
- PHP deps: [composer.json](composer.json)  
- Frontend deps: [package.json](package.json)  
- Routes: [routes/api.php](routes/api.php)  
- Example env: [.env.example](.env.example)  
- Tests config: [phpunit.xml](phpunit.xml)

## Notes & recommendations
- Use Laravel Sanctum tokens or SPA cookies depending on client.
- Keep sensitive values in `.env` and out of version control.
- Add API request validation and proper resource/transformer responses for production.

## License
Add your preferred license here.