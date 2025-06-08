# 🧾 **Project Documentation: PHP MVC Framework**

This project is a simple PHP-based MVC (Model-View-Controller) framework. It demonstrates the core principles of the MVC pattern with custom implementations for routing, authentication, session management, caching, and more. Below is a detailed guide on the framework’s structure, features, and how to work with it.

---

## 📁 **Directory Structure**

The project follows a clear directory structure designed for easy navigation and scalability.

```
project-root/
│
├── database/
│   └── seed.sql                    # MySQL database dump for project setup.
│
├── public/                         # Publicly accessible files. The entry point is index.php.
│   ├── index.php                   # Front controller, handles all incoming requests.
│   ├── assets/                     # Stores CSS, JavaScript, and other front-end assets.
│   │   ├── css/bootstrap.css       # Bootstrap CSS framework for styling.
│   │   └── js/jquery.min.js        # jQuery library for DOM manipulation.
│
├── src/                            # Contains the core application code.
│   ├── Config/                     # Configuration files for app-level settings.
│   │   ├── 404.php                 # Custom 404 error page.
│   │   ├── App.php                 # Application-level variables and settings.
│   │   ├── AccessDeniedPage.php    # Custom Access Denied page.
│   │   ├── Helpers.php             # Global utility functions.
│   │   └── MenuItems.php           # Array of menu items used in the app.
│   │
│   ├── Controllers/                # Application logic (controllers).
│   │   ├── AuthController.php      # Manages authentication (login, logout, etc.).
│   │   └── HomeController.php      # Home page logic and rendering.
│   │
│   ├── Core/                       # Core system files for the framework.
│   │   ├── Auth.php                # Auth-related logic (login, roles, permissions).
│   │   ├── Bootstrap.php           # Base controller that manages rendering.
│   │   ├── Cache.php               # Cache management functionality.
│   │   ├── Config.php              # Loads and manages application settings.
│   │   ├── Console.php             # CLI commands and utilities.
│   │   ├── Controller.php          # Base controller class with common methods.
│   │   ├── Csrf.php                # CSRF protection logic.
│   │   ├── Database.php            # Database connection and query management.
│   │   ├── Env.php                 # Handles environment variables (e.g., .env).
│   │   ├── Flash.php               # Flash message functionality.
│   │   ├── Log.php                 # Logging class for tracking system activity.
│   │   ├── Logger.php              # File-based logging utility.
│   │   ├── Model.php               # ORM-related logic for database models.
│   │   ├── Router.php              # Route handling (maps requests to controllers).
│   │   ├── PermissionManager.php   # User permission management (access control).
│   │   └── Session.php             # Session management (user sessions).
│   │
│   ├── Models/                     # Database models (e.g., User.php).
│   │   ├── DB.php                  # Database query handler and route definition.
│   │   └── User.php                # User-related operations, such as fetching user data.
│   │
│   ├── Storage/                    # Stores cache and logs.
│   │   ├── Cache/                  # Cached database queries.
│   │   └── Logs/                   # Log files to track system activity and errors.
│   │
│   ├── Urls/                       # URL routing configuration.
│   │   ├── PublicRoutes.php        # Public routes (accessible by everyone).
│   │   └── Route.php               # Route definitions for the app.
│   │
│   ├── Views/                      # View files (HTML + PHP) that render the UI.
│   │   ├── Auth/                   # Auth-related views (login, signup, etc.).
│   │   │   ├── changePassword.php
│   │   │   ├── forgotPassword.php
│   │   │   ├── index.php
│   │   │   ├── login.php
│   │   │   └── signup.php
│   │   │
│   │   ├── Home/                   # Home page views.
│   │   │   └── home.php
│   │   │
│   │   └── Layout/                 # Base layout files (header, footer, etc.).
│   │       └── layout.php
│   │       └── secondLayout.php
│   │
│   └── vendor/                     # Custom middleware classes.
│
├── .env                            # Environment-specific configuration file.
├── .env.example                    # Sample environment configuration for reference.
├── .gitignore                      # Git ignore rules for excluded files.
├── cli.php                         # Command-line interface entry point.
├── composer.json                   # Composer file for dependencies and autoloading.
├── composer.lock                   # Composer lock file to ensure consistent dependency versions.
├── index.html                      # A placeholder file for the public directory.
└── README.md                       # This documentation file.
```

---

## 🔁 **Project Lifecycle**

1. **index.php**:

   * This is the main entry point for all incoming requests.
   * It initializes and bootstraps the application, sets up routing, and delegates control to the appropriate controller and action.

2. **Router**:

   * The router listens for incoming requests and maps them to the correct controller and action (e.g., `/auth/login` → `AuthController@login`).

3. **Controller**:

   * The controller handles the application’s business logic. It processes data, communicates with models, and passes the data to the view for rendering.

4. **View**:

   * Views are PHP files that render the HTML structure of the page. They can include dynamic data passed from controllers using the `render()` method.

5. **Auth**:

   * The authentication system handles login, logout, and access control. It checks if the user is authorized to access specific routes based on their session, roles, and permissions.

6. **Flash/Session**:

   * Flash messages are temporary messages, such as validation errors or success notices, which are stored in the session and displayed once.
   * Sessions store persistent user data (e.g., login status).

7. **Middleware**:

   * Middleware checks certain conditions before the request reaches the controller (e.g., checking if a user is authenticated before accessing a route).

8. **Logger**:

   * The logging system captures system activity and errors and stores them in log files in the `storage/logs/` directory.

9. **Cache**:

   * Caching improves performance by storing query results in the `storage/cache/` directory. Future requests can use the cached data instead of querying the database again.

10. **CSRF**:

    * Cross-Site Request Forgery (CSRF) protection is implemented to secure forms. A CSRF token is added to every form and validated upon submission.

11. **Config**:

    * The `config()` function loads configuration settings from the `.env` file, providing environment-specific settings such as database credentials or app environment mode (development or production).

---

## 🛠️ **Key Features**

### **MVC Architecture**

* **Separation of concerns**:

  * The project uses the MVC pattern, which divides the code into three main sections: **Model** (data), **View** (UI), and **Controller** (business logic).

### **Routing**

* Supports clean and readable routes like `/auth/dashboard` where `auth` is the controller and `dashboard` is the action method.
* Allows easy addition of custom routes and controllers.

### **Views**

* Views are automatically loaded from the `Views/{ControllerName}/{view}.php` directory.
* Bootstrap 4 CSS is included to style the UI, along with jQuery for dynamic interactions.
* Flash messages are displayed with reusable UI components for consistency.

### **Authentication**

* Secure login system with password hashing and user authentication.
* Supports role-based access control, restricting access to certain routes based on user roles.

### **Session & Flash**

* Session handling allows persistence of user data (e.g., login status) across requests.
* Flash messages provide temporary feedback to the user, such as validation errors or success messages.

### **Error Handling**

* Switch between development and production environments using the `config('app_env')` variable.

  * **Development**: Displays detailed error messages.
  * **Production**: Displays a generic "Internal Server Error" message, while detailed logs are saved to a file.

### **Logger**

* Logs system information and errors using a custom `Log` class (`Log::info()`, `Log::error()`).
* Logs are saved in `storage/logs/` for review and debugging.
* A helper function `log_error()` is available for quick logging.

### **Environment Support**

* The `.env` file holds environment-specific variables (e.g., database credentials, app mode).
* Environment variables are accessed using `getEnvData('key')`.

### **App-Level Variables**

* Add custom variables in `src/Config/app.php`.
* These variables can be accessed globally in your application by importing `app/core/config`.

### **CSRF Protection**

* Every form in the application includes a CSRF token, which is automatically validated during the POST request.
* This prevents Cross-Site Request Forgery attacks, ensuring that form submissions are legitimate and not malicious.

### **Caching**

* Database query results are cached in files stored in the `storage/cache/` directory.
* When the same query is requested, the system checks the cache first to see if the data is available, improving performance by avoiding repeated database queries.
* Caching behavior can be configured through the app’s settings in the `.env` file.

### **CLI Support**

* The framework includes a Command Line Interface (CLI) for administrative tasks:

  * **Start the local server**: Run `php cli.php serve` to start a local development server.
  * **Database Seeding**: Run `php cli.php db:seed` to populate the database with initial data. This is useful for setting up the application for development or testing.

### **Built-in Bootstrap and jQuery**

* The project comes pre-configured with **Bootstrap 4** for responsive design and **jQuery** for client-side interactivity.
* These libraries are automatically included in the public assets, so you don’t need to manually set them up in your views.

---


## 🧪 Usage Examples

### Load a Config Value

```php
Config::reset();
Config::load([...]);
Config::set('key', 'value');
Config::get('key', 'default');

```

### route example
```php
$router->action('/dashboard', 'Home@dashboard')->permission(['view_users', 'edit_posts'], 'permissions')->method(['GET']);
```
dashboard is url name
'Home@dashboard' => Home is controler name and dashboard is action name
permission(['view_users', 'edit_posts'], 'permissions') => here this ['view_users', 'edit_posts'] can be array or sting it decide if the user has permission or not.
'permissions' => this will decide is this a roles or permissions default is roles
method(['GET']) => we can setup the url method, if the method didn't metch it will through an error.


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
```
OR
```php
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
```
OR
```php
set_flash('success', 'Logged in successfully!');
get_flash('success');
has_flash('success');
flash_message('error', get_flash('errors'));
```

### CSRF

```php
csrf_token(); // Generates or retrieves token
verify_csrf_token($_POST['_csrf']); // Validates submitted token
```

### Auth

```php
is_logged_in();
current_user();
current_user_id();
current_user_role();
user_has_role($userModel); // return true/false
user_can($permission); // eg user_can('action_name') // get from the permission table
```
OR
```php
Auth::isAuthenticated();
Auth::getUser();
Auth::login($user); // eg. admin
Auth::logout();
Auth::getUserId();
Auth::getRole();
Auth::hasRole(); // return true/false
Auth::can($permission); // eg Auth::can('action_name') // get from the permission table

```

### Env

```php
getEnvData('Key_In_ENV')
```

### Get and Set cache

```php
cache_put('users_list', $user_model, 600); // cache for 10 mins
cache_get('users_list');
```

---

# 📘 Query Capabilities with Custom PHP Model

I created these ORM method to customise as possible


## 🔹 Basic Select Queries

### 1. **Retrieve All Records**

```php
User::find()->get();
```

SQL: `SELECT * FROM users`

### 2. **Select Specific Columns**

```php
User::find()->select(['id', 'name'])->get();
```

SQL: `SELECT id, name FROM users`

* Select all:

  ```php
  User::select()->get();
  ```
* Select specific columns:

  ```php
  User::select(['id', 'name'])->get();
  ```


* Find by ID:

  ```php
  User::find(1);
  ```
* More simple: return 1 row

  ```php
  User::get(1);
  ```
* More simple: return all

  ```php
  User::get();
  ```


## 🔹 Where Conditions

### 3. **Simple Where**

```php
User::find()->where('status', '=', 'active')->get();
```

SQL: `SELECT * FROM users WHERE status = 'active'`

### 4. **Multiple AND Conditions**

```php
User::find()->where('status', '=', 'active')->andWhere('type', '=', 'admin')->get();
```

SQL: `SELECT * FROM users WHERE status = 'active' AND type = 'admin'`

### 5. **OR Where**

```php
User::find()->where('status', '=', 'active')->orWhere('type', '=', 'editor')->get();
```

SQL: `SELECT * FROM users WHERE status = 'active' OR type = 'editor'`

### 6. **Where In**

```php
User::find()->whereIn('id', [1, 2, 3])->get();
```

SQL: `SELECT * FROM users WHERE id IN (1, 2, 3)`

### 7. **Where Null / Not Null**

```php
User::find()->whereNull('deletedat')->get();
User::find()->whereNotNull('emailverifiedat')->get();
```

### 8. **Raw Where / Custom SQL**

```php
User::find()->whereRaw('LENGTH(name) > ?', [5])->get();
```

### 9. **Grouped Where Conditions**

```php
User::groupWhere(function($q) {
    $q->where('status', '=', 'active')->orWhere('type', '=', 'editor');
})->get();
```

SQL: `SELECT * FROM users WHERE (status = 'active' OR type = 'editor')`

## 🔹 Aggregates

### 10. **Count / Sum / Min / Max / Avg**

```php
User::find()->count()->get();
User::find()->sum('salary')->get();
User::find()->avg('rating')->get();
User::find()->min('age')->get();
User::find()->max('points')->get();
```

---

## 🔹 Joins

### 11. **Inner Join / Left Join / Right Join / Full Join**

```php
Post::find()->innerJoin('users', 'posts.userid', '=', 'users.id')->get();
Post::find()->leftJoin('comments', 'posts.id', '=', 'comments.postid')->get();
Post::find()->rightJoin('users', 'posts.userid', '=', 'users.id')->get();
Post::find()->fullJoin('comments', 'posts.id', '=', 'comments.postid')->get();
Post::find()->join('comments', 'posts.id', '=', 'comments.postid')->get();
```
OR
```php
Post::select()->innerJoin('users', 'posts.userid', '=', 'users.id')->get();
Post::select()->leftJoin('comments', 'posts.id', '=', 'comments.postid')->get();
Post::select()->rightJoin('users', 'posts.userid', '=', 'users.id')->get();
Post::select()->fullJoin('comments', 'posts.id', '=', 'comments.postid')->get();
Post::select()->join('comments', 'posts.id', '=', 'comments.postid')->get();
```

SQL: `SELECT * FROM posts INNER JOIN users ON posts.userid = users.id`

---

## 🔹 Group By / Having

### 12. **Group By**

```php
User::find()->groupBy('role')->get();
```

### 13. **Having**

```php
User::find()->groupBy('role')->having('COUNT(id)', '>', 10)->get();
```

---

## 🔹 Ordering and Pagination

### 14. **Order By**

```php
User::find()->orderBy('createdat', 'DESC')->get();
```
OR
```php
User::select()->orderBy('createdat', 'DESC')->get();
```

### 15. **Limit & Offset**

```php
User::find()->limit(10)->offset(20)->get();
```
OR
```php
User::select()::limit(10)->offset(20)->get();
```

---

## 🔹 First / One / Column

### 16. **Get First Result**

```php
User::find()->first();
```

### 17. **Get Single Column Values**

```php
User::find()->column();
```

---

## 🔹 Raw Select & SQL Debug

### 18. **Raw Select Expression**

```php
User::find()->rawSelect('COUNT(*) as totalusers')->get();
```

### 19. **Debug SQL Query**

```php
User::find()->where('status', '=', 'active')->sql();
```
OR
```php
User::find()->where('status', '=', 'active')->toSql();
```

---

## 🔹 Insert / Update / Delete

### 20. **Insert Record**

```php
$user = new User(['name' => 'John', 'email' => 'john@example.com']);
$user->save();
```

### 21. **Insert Multi Record**

```php
$users = [
    ['name' => 'Alice', 'email' => 'alice@example.com', 'role' => 'admin'],
    ['name' => 'Bob', 'email' => 'bob@example.com', 'role' => 'editor'],
    ['name' => 'Charlie', 'email' => 'charlie@example.com', 'role' => 'viewer']
];

User::insertMany($users);
```


### 22. **Update Record**

```php
$user = User::find(1)->first();
$user->email = 'new@example.com';
$user->save();
```
OR
```php
$users = User::find()->get();
foreach ($users as $user) {
    $user->is_active = 0;
    $user->save();
}
```


### 23. **Delete by ID**

```php
User::delete(id);
```
OR
```php
$user = User::find(1)->first();
$user->delete();
```
OR
```php
$users = User::find()->get();
foreach ($users as $user) {
    $user->delete();
}
```

### 24. **Delete With Condition**

```php
User::find()->where('role', '=', 'guest')->deleteWhere();
```

---

## 🔹 Transactions

```php
Model::beginTransaction();
// ... operations
Model::commit();
// or
Model::rollback();
```

---

## 🔹 Special Options

### 25. **Enable Distinct**

```php
User::find()->distinct()->get();
```

### 26. **Get JSON Result Format**

```php
User::find()->asJson()->get();
```

---

## ORM Summary

With this model, you can construct advanced and complex SQL queries like:

* `SELECT`, `WHERE`, `JOIN`, `GROUP BY`, `HAVING`, `ORDER BY`, `LIMIT`
* Aggregate functions: `COUNT`, `SUM`, `MIN`, `MAX`, `AVG`
* Raw SQL conditions and debugging
* Data mutation operations like `INSERT`, `UPDATE`, `DELETE`
* Fluent builder-like method chaining
* Full transaction support

---
## 🛠️ **Setup and Installation**

### Prerequisites

Before you begin, ensure that you have the following software installed:

* **PHP 7.4 or higher**
* **Composer** (for managing dependencies)
* **MySQL** or **MariaDB**

### Step 1: Clone the Repository

Clone the project to your local machine:

```bash
git clone https://github.com/bisht-yashwant/php_fam.git
cd php-mvc-framework
```

### Step 2: Install Dependencies

Use Composer to install the required PHP dependencies:

```bash
composer install
```

This will install all the necessary dependencies listed in the `composer.json` file.

### Step 3: Configure Environment Variables

Create a `.env` file from the example:

```bash
cp .env.example .env
```

Then, open the `.env` file and update the configuration based on your environment (e.g., database credentials, app environment, etc.).

### Step 4: Set Up the Database

Import the database schema and sample data by running:

```bash
mysql -u yourusername -p yourdatabase < database/seed.sql
```

Or, alternatively, use the provided CLI tool:

```bash
php cli.php db:seed
```

### Step 5: Start the Development Server

You can use the built-in PHP server to run the application:

```bash
php -S localhost:8000 -t public
```

Or, alternatively, use the provided CLI tool:

```bash
php cli.php serve
```

6. **Access the App:**
   Visit `http://localhost:8000` in your browser

Try routes like:

* `/signup`
* `/login`

---

## 📝 **Contributing**

If you want to contribute to the framework, feel free to open issues or submit pull requests. Here's how you can contribute:

1. **Fork the Repository**: Create your own fork of the project on GitHub.
2. **Create a Branch**: Create a new branch for your feature or bug fix.
3. **Make Changes**: Implement your changes and make sure to write tests if applicable.
4. **Submit a Pull Request**: Once you're ready, submit a pull request with a clear description of what you've done.

---

## 📄 **Licensing**

This project is open-source and available under the MIT License. See the `LICENSE` file for more details.

---

## 🧑‍💻 Author

Developed by **Yashwant Bisht** — originally started as an ORM testing project, it now powers flexible full-stack PHP applications with custom routing, caching, and middleware systems.

---

## 📝 Final Notes

* Autoloaded via Composer (following PSR-4 structure)
* Views use Bootstrap for quick UI prototyping
* Framework emphasizes **SOLID**, **DRY**, and clean code practices
* Minimal boilerplate, high extensibility — made for developers who love control
* Ideal for small-to-medium web applications, admin panels, or learning MVC