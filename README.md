# 🧾 Project Documentation: PHP MVC Framework

## 📁 Directory Structure

```
project-root/
│
├── public/                  # Publicly accessible files (entry point: index.php)
│   ├── index.php
│   ├── assets/             
│   │   ├── css/bootstrap.css # Bootstrap styles
│   │   └── js/jquery.min.js  # jQuery
│   │
├── src/
│   ├── Config/
│   │   ├── 404.php           # Custom 404 page
│   │   ├── App.php           # we can setup app level variable here
│   │   ├── Helpers.php       # Global level function can be define here
│   │   └── MenuItems.php     # menu items are define here as array
│   │
│   ├── Controllers/         # Application logic
│   │   └── AuthController.php
│   │
│   ├── Core/                # Core system (framework components)
│   │   ├── App.php          # App bootstrapper
│   │   ├── Controller.php   # Base controller with rendering support
│   │   ├── Cache.php        # cache related function are here
│   │   ├── Csrf.php         # Csrf related function are here
│   │   ├── Bootstrap.php    # Base controller with rendering support
│   │   ├── Env.php          # env variable set or get internal logic
│   │   ├── Router.php       # Route handling
│   │   ├── Auth.php         # Auth logic (login, roles, permissions)
│   │   ├── Session.php      # session & normal session management
│   │   ├── Flash.php        # flash related function are here
│   │   ├── Helpers.php      # Global utility functions (flash_message, config, etc.)
│   │   ├── Config.php       # Loads App variables (set/get settings)
│   │   ├── Log.php          # Log facade
│   │   └── Logger.php       # File-based logger
│   │
│   ├── Models/              # Database models (User.php etc.)
│   │
│   ├── Urls/
│   │   ├── Route.php                  # we can define route here
│   │   └── PublicRoutes.php           # we can define public route here in array form
│   │
│   ├── Storage/
│   │   ├── Cache/                    # Cached DB responses
│   │   └── Logs/                    # Error log files
│   │
│   ├── Views/               # HTML + PHP view files
│   │   └── Auth/
│   │       └── dashboard.php
│   │
│   └── Middleware/          # Custom middleware classes
│
├── .env                     # Environment config
├── .htaccess                # Routes all requests to index.php
├── composer.json            # Autoload + dependency file
└── README.md
```

---

## 🔁 Project Lifecycle

1. **index.php**: The front controller. Bootstraps the app, parses the URL, and delegates to `App::run()`.
2. **Router**: Routes the request to the correct controller and action.
3. **Controller**: Executes business logic, and sends data to a view.
4. **View**: A PHP file that renders HTML and uses `render()` with `extract($data)` to display content.
5. **Auth**: Manages login, logout, and checks for roles/permissions.
6. **Flash/Session**: Temporary data like login errors or validation messages.
7. **Middleware**: Checks access before entering controller (e.g. `auth`, `guest`).
8. **Logger**: Logs any debug or error messages to a file.
9. **Cache**: Caches query responses in `storage/cache/`.
10. **CSRF**: Secure form submissions using tokens.
11. **Config**: Uses `.env` and `config()` for environment-specific logic.

---

## 🛠️ Features

Most of the features are divided into two parts, one classes and one global functions
for class function we have to import using use /app/core
but global fucntio can be use anywhere without importing anything
if we create any function in helper class it will become global function  

### ✅ MVC Architecture

* Clean folder separation for models, views, controllers.

### ✅ Routing

* Supports clean URL routing like `/auth/dashboard` => `Auth@dashboard`. auth is controller name and dashboard is action name.

### ✅ Views

* Auto-loads `Views/ControllerName/view.php`
* Uses Bootstrap CSS for UI
* Has flash messages with reusable UI components

### ✅ Authentication

* Secure login with password hashing
* Role-based access control

### ✅ Session & Flash

* Persistent user data (e.g., login info)
* Flash messages for user feedback (e.g., success, error, validation errors)

### ✅ Error Handling

* `config('app_env')` switch between `dev` and `prod`

  * dev: shows errors on screen
  * prod: shows generic "Internal Server Error" + logs full details

### ✅ Logger

* Custom logger class (`Log::info()`, `Log::error()`)
* Shortcut helper `log_error()` available
* Logs to file in `storage/logs/`

### ✅ Environment Support

* `.env` file parsed using `getEnvData('key')` Env file
* Use `DB_HOST`, `APP_ENV`, `CACHE_ENABLED`, etc.

### ✅ App Level Variable

* Add your variable in src/Config/app
* and it can be use anywhere just import app/core/config.

### ✅ CSRF Protection

* Tokens added to every form
* Validated on POST automatically

### ✅ Caching

* Save DB responses to files in `storage/cache`
* Load from cache if available (configurable)

---

## 🧪 Usage Examples

### Load a Config Value

```php

Config::reset();
Config::load([...]);
Config::set('key', 'value');
Config::get('key', 'default');

```

### Render a View

```php
$this->render('dashboard', ['title' => 'Welcome']);
```

### Hide layout

```php
$this->layout = false;
```

### use custom layout

```php
$this->useLayout = "layout name";
```

### Hide Menuitems

```php
$this->menuItems = false;
```

### Log an Error

```php
$message = "Something went wrong";
log_info($message)
log_debug($message)
log_warning($message)
log_error($message)
log_critical($message)

or 

Logger::info($message)
Logger::debug($message)
Logger::warning($message)
Logger::error($message)
Logger::critical($message)

```

### Flash Message

```php
Flash::set('success', 'Logged in successfully!');
Flash::has('errors');
Flash::get('errors');
or
set_flash('success', 'Logged in successfully!');
get_flash('success');
has_flash('success');
flash_message('error', get_flash('errors'));
```

### CSRF

```php
csrf_token(); // Generates or retrieves token
verify_csrf($_POST['_csrf']); // Validates submitted token
```


### Auth

```php
login_check();
auth_user();
user_role($role_name); // eg. admin
user_has_role($userModel);
user_id();
user_can($permission); // eg user_can('action_name') // get from the permission table

or

Auth::check();
Auth::user();
Auth::role($role_name); // eg. admin
Auth::hasRole($userModel);
Auth::userId();
Auth::can($permission); // eg Auth::can('action_name') // get from the permission table

```

### Env

```php
getEnvData('Key_In_ENV')
```

### Get and Set cache

```php
cache_get('users_list');
cache_put('users_list', $user_model, 600); // cache for 10 mins
```
---

## 🚀 How to Run

1. Clone the project.
2. Point your Apache or Nginx root to `public/`
3. Make sure `.htaccess` is working for pretty URLs.
4. create the database using the dump in sql/dump.sql.
5. Update DB config in `.env`
6. Navigate to `/signup` or `/login`

---

## 📥 Contributions

This project is built from scratch and open to improvement. Contributions and refactors are welcome!

---

## 🧑‍💻 Author

You.

---

## 📝 Final Notes

* Everything is autoloaded via Composer (`composer dump-autoload`)
* Add helpers via `"files"` in `composer.json`
* Bootstrap is optional but recommended for UI consistency
* Project follows SOLID principles
