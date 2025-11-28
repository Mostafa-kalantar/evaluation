# Evaluation

* Auth with Admin/User roles
* CRUD Evaluations

> **This is a minimal project using standard CRUD REST APIs, implemented by Laravel + Inertia + Vue3 with full Swagger documentation. There is also a Dockerfile in the project root.**

**🎯 Technologies:**
- PHP 8.3
- Laravel 12
- Laravel Sanctum
- Inertia
- Vue.js
- Swagger Documentation

**⚡ Main actions:**
- Authenticate user by Authorization token
- Check user role and scope
- CRUD an evaluation entity by users/admins
- Users can create/read/update/delete their own evaluations
- Admins can manage all evaluations

---

### Installation

#### 1. Install dependencies and run local servers:

1. Install npm packages:
```bash
npm i
```

2. Build the front-end:
```bash
npm run build
```

3. Install PHP dependencies:
```bash
composer install
```

4. Rename .env.example to .env
```bash
mv .env.example .env
```

5. Prepare database (create if not exists, run migrations, import data):
```bash
php artisan db:prepare
```

6. Run the PHP server:
```bash
php artisan serve
```

---
#### 2. Running with Docker
1. Build Docker image:
```bash
docker build -t laravel-app .
```
2. Run container:
```bash
docker run -it -p 9000:9000 -v $(pwd):/var/www/html laravel-app
```
3. Access the app at: http://localhost:9000

### Swagger Documentation

> The full API documentation is available at:
[Swagger Documentation](http://localhost:9000/api/documentation)
---
