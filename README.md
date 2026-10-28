# Store Order & Inventory Mini-System

## 1. Project Overview

This is a Laravel application designed for a retail counter to manage products, customers, orders, and inventory. It provides a web interface and REST APIs for order creation, customer order history, and low-stock reporting.

## 2. Requirements

- PHP and Composer
- MySQL or MariaDB
- Laravel dependencies installed using Composer
- Laragon or another compatible local development environment

Check the installed versions using:

```bash
php -v
php artisan --version
composer --version
```

## 3. Installation and Setup

1. Clone the private repository:

```bash
git clone https://github.com/Ramji-RS/store-order-inventory.git
cd store-order-inventory
```

2. Install dependencies:

```bash
composer install
```

3. Create the environment file:

```bash
copy .env.example .env
```

4. Generate the application key:

```bash
php artisan key:generate
```

5. Create a MySQL database named `store_inventory` and configure the database credentials in `.env`.

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=store_inventory
DB_USERNAME=root
DB_PASSWORD=
```

Update these values according to your local environment.

6. Run database migrations and seed sample data:

```bash
php artisan migrate --seed
```

7. Clear application caches:

```bash
php artisan optimize:clear
```

## 4. Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

Open the local URL displayed by Artisan, usually `http://127.0.0.1:8000`.

Check registered routes using:

```bash
php artisan route:list
```

## 5. Queue Processing

The application dispatches a `SendOrderConfirmation` job after an order transaction commits successfully.

For asynchronous queue processing, configure a supported queue driver in `.env` and start the worker:

```bash
php artisan queue:work
```

The job simulates an order confirmation by writing details to the Laravel log. It does not send a real email.

Log location:

`storage/logs/laravel.log`

## 6. API Endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| POST | `/api/v1/orders` | Create an order |
| GET | `/api/v1/customers/orders?email=...` | Retrieve customer order history |
| GET | `/api/v1/products/low-stock` | Retrieve products below the stock threshold |

## 7. Assumptions and Design Decisions

### Customer identification
Customer email addresses are trimmed and normalized to lowercase before lookup. Existing customers are reused by email, and their stored name is not automatically overwritten.

### Product prices and tax
The server uses product prices and tax percentages stored in the database. Client-submitted prices are not trusted, preventing clients from manipulating order totals.

### Order quantities
An order must contain at least one item. Quantities must be positive integers, product IDs must exist, and duplicate product IDs within a request are rejected.

### Inventory consistency
Order creation, order-item insertion, and stock deduction are performed inside a database transaction. Product rows are locked during stock validation using `lockForUpdate()` to help prevent overselling when concurrent requests occur. This behavior depends on a database engine that supports row-level locking, such as MySQL with InnoDB.

### Monetary calculations
Order totals are calculated on the server. Monetary values are stored in decimal database columns. The implementation uses integer cents for relevant calculations to reduce floating-point rounding errors. Tax rounding is applied at the individual order-line level.

### Low-stock threshold
The low-stock API accepts a configurable threshold. The intended default is `5`, and products are considered low-stock when their quantity is strictly less than the threshold.

### Order confirmation
A queued job simulates confirmation through Laravel logging instead of requiring an external email service. The job is dispatched after the order transaction commits.

### Authentication
Authentication is not included unless implemented separately. This is an assignment-scope assumption, not a recommendation for a production inventory application.

### Concurrency testing
Row locking is implemented to protect stock updates. A dedicated concurrent test against the intended database engine is needed to demonstrate race-condition handling; sequential tests alone do not prove concurrency safety.

## 8. Testing

Run the automated test suite:

```bash
php artisan test
```

Run the order creation feature test, if present:

```bash
php artisan test tests/Feature/CreateOrderTest.php
```

Tests should cover successful order creation, totals, stock deduction, invalid quantities, insufficient stock, and queue job dispatch.

## 9. AI Prompt Log

Screenshots of prompts actually used during development are stored in the `prompts/` directory. These document how AI assistance was used during implementation and debugging.

## 10. Security

- Do not commit `.env` or secret credentials.
- Keep `.env.example` free of real passwords.
- Do not run `php artisan migrate:fresh --seed` on a database containing data that must be preserved.
- Verify all tests and configuration before deployment.