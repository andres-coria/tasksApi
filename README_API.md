# 🧩 Tasks API – Laravel 11 + Sanctum

A simple and secure RESTful API built with **Laravel** for managing tasks.  
It includes authentication using **Laravel Sanctum**, validation, filtering by status, and automated tests.

---

## 🚀 Features

- User registration and token-based authentication (Sanctum)
- CRUD operations for tasks
- Filter tasks by status (`PENDING`, `IN_PROGRESS`, `DONE`)
- Request validation and error handling
- Fully tested using PHPUnit

---

## 🧱 Requirements

Before you start, make sure you have:

- **PHP ≥ 8.2**
- **Composer ≥ 2.x**
- **SQLite extension** enabled
- **Laravel CLI** (optional)

---

## ⚙️ Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/<your-username>/tasksApi.git
   cd tasksApi

2. **Install dependencies**

    composer install

3. **Copy the example environment file**

    cp .env.example .env

4. **Open .env and update these lines:**

    Configure environment for in-memory SQLite

    APP_NAME=TasksAPI
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://127.0.0.1:8000

    DB_CONNECTION=sqlite
    #comment the line below for a persistent DB
    DB_DATABASE=:memory:

    # Sanctum settings
    SANCTUM_STATEFUL_DOMAINS=127.0.0.1:8000
    SESSION_DRIVER=array
    SESSION_LIFETIME=120

    ⚠️ Using DB_DATABASE=:memory: creates an in-memory database each time the app starts.
    It’s ideal for development and testing, but not persistent. Please also try testing with a persistent DB by removing or comenting the line "DB_DATABASE=:memory:"

5. **Generate the application key**

    php artisan key:generate


5. **Run database migrations**

    php artisan migrate 
    
    
    #OR php artisan migrate:fresh

▶️ Running the Application

    Start the Laravel development server:

    php artisan serve

    👉 http://127.0.0.1:8000


🔐 Authentication Workflow

    All protected routes require authentication via Laravel Sanctum tokens.

    1️⃣ Register a new user

    POST /api/register

        {
            "name": "John Doe",
            "email": "john@example.com",
            "password": "secret123"
        }

    2️⃣ Login and get token

    POST /api/login

        {
            "email": "john@example.com",
            "password": "secret123"
        }

    response: 
        {
            "token": "1|P4vWvHq2UQp8JztmJZz7..."
        }

    3️⃣ Access protected routes

    Include the token in your request header:

    Authorization: Bearer <your_token>

    Example:

        GET /api/tasks
        Authorization: Bearer 1|P4vWvHq2UQp8JztmJZz7...

📡 API Endpoints

    Authentication
    
    Method	Endpoint	Description
    POST	/api/register	Register new user
    POST	/api/login	Login and get token

    Tasks

    Method	Endpoint	Description
    GET	/api/tasks	List all tasks
    GET	/api/tasks?status=DONE	Filter tasks by status
    POST	/api/tasks	Create new task
    GET	/api/tasks/{id}	Get task by ID
    PUT	/api/tasks/{id}	Update task
    DELETE	/api/tasks/{id}	Delete task
    

🧪 Running Tests

    All feature and authentication tests are included under /tests/Feature.

    Run all tests:

    php artisan test

    Included Tests:
        User registration and login
        Sanctum token authentication
        Access control for protected routes
        CRUD operations for tasks


🧰 Troubleshooting

        Error: Class "DOMDocument" not found
        ➡️ Enable the PHP XML extension:
    
            sudo apt install php-xml

        Error: could not find driver (for SQLite)
        ➡️ Enable SQLite extension:

            sudo apt install php-sqlite3


📁 Project Structure

        app/
        ├── Http/
        │    ├── Controllers/Api
        │    │     ├── AuthController.php
        │    │     └── TaskController.php
        │    └── Middleware/
        ├── Models/
        │    ├── User.php
        │    └── Task.php
        database/
        ├── factories/
        ├── migrations/
        tests/
        ├── Feature/
        │    ├── AuthControllerTest.php
        │    └── TaskControllerTest.php

👤 Author

    Andrés Coria
    📧 andresrcoria@gmail.com
