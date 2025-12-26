# PHPApiCreator

**PHPApiCreator** is a PHP library designed to simplify the creation of scalable APIs. It provides a structured way to manage database connections, handle HTTP methods (**GET**, **POST**, **PATCH**, **DELETE**), and return consistent **JSON** responses.

---

## Features

- **Easy Database Integration**: Connect to a MySQL database effortlessly.
- **HTTP Method Handling**: Supports GET, POST, PATCH, DELETE requests.
- **RESTful Design**: Follows REST principles for API structure.
- **JSON Responses**: Automatically sets headers and outputs in JSON.
- **Error Handling**: Centralized and consistent error responses.

---

## Installation

### Install via Composer

```bash
composer require ananchev/php-api-creator
````

### Or clone the repository

```bash
git clone https://github.com/ananchevegor/PHPApiCreator.git
```

---

## Quick Start

Create an endpoint file (example: `country.php`) and add:

```php
<?php

$currentEndpoint = "country";
$api = new PHPApiCreator($db_host, $db_name, $db_user, $db_pass);

$connection = $api->database_connection();

echo $api->SERVER_REQUEST($_SERVER, $token_code, $currentEndpoint, $connection);
```

---

## How it works

### Database Connection

```php
$connection = $api->database_connection();
```

### Routing

The router selects the correct handler based on the HTTP method:

* `GET` → fetch data
* `POST` → update an existing record
* `PATCH` → partially update an existing record
* `DELETE` → delete a record

```php
echo $api->SERVER_REQUEST($_SERVER, $token_code, $currentEndpoint, $connection);
```

### Dynamic Endpoints

`$currentEndpoint` defines the endpoint/table name:

```php
$currentEndpoint = "country";
```

### Error Handling

Unsupported request methods return a standardized JSON error response.

---

## HTTP Methods

### GET

```php
$this->token($SERVER, $token_code)->GET($_GET, $connection, $currentEndpoint);
```

### POST

```php
$this->token($SERVER, $token_code)->POST($_POST, $connection, $currentEndpoint);
```

### PATCH

```php
$inputs = file_get_contents("php://input");
$this->token($SERVER, $token_code)->PATCH($inputs, $connection, $currentEndpoint);
```

### DELETE

```php
$inputs = file_get_contents("php://input");
$this->token($SERVER, $token_code)->DELETE($inputs, $connection, $currentEndpoint);
```

---

## Configuration

Example `config.php`:

```php
<?php
$host = "localhost";
$dbname = "your_database";
$user = "your_username";
$pass = "your_password";
```

---

## Query Example

Endpoint for table `country`:

```php
$currentEndpoint = "country";
```

### Request

```text
https://your-domain.com/webservices/country?where=name like 'Rus' and iso eq 'RU'
```

### Response

```json
{
  "table": "country",
  "time": 1726581512,
  "payload": [
    {
      "id": "177",
      "iso": "RU",
      "name": "RUSSIAN FEDERATION",
      "nicename": "Russian Federation",
      "iso3": "RUS",
      "numcode": "643",
      "phonecode": "70"
    }
  ]
}
```

---

## Recommended Project Structure

```text
project-root/
  webservices/
    country.php
    users.php
    orders.php
  config.php
  vendor/
```
