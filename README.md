# PHPApiCreator

**PHPApiCreator** is a PHP library designed to simplify the creation of scalable APIs. It supports managing database connections, handling various HTTP methods (GET, POST, PATCH, DELETE), and structuring API responses in JSON format. Below is a quick guide on how to set up and use the library.

## Features

- **Easy Database Integration**: Connect to a MySQL database effortlessly.
- **HTTP Method Handling**: Supports GET, POST, PATCH, DELETE requests.
- **RESTful Design**: Follows REST principles for API structure.
- **JSON Responses**: Automatically sets headers and outputs in JSON.
- **Error Handling**: Centralized and consistent error responses.

## Installation

1. **Clone the repository**:

   ```bash
   git clone https://github.com/ananchevegor/PHPApiCreator.git

2. **Install dependencies via Composer:**:

   ```bash
   composer require ananchev/php-api-creator

# PHP API Endpoint Setup Using PHPApiCreator

## Usage

Here is an example of setting up a PHP API endpoint using PHPApiCreator:

```php
$currentEndpoint = "endpointname";
$api = new PHPApiCreator($db_host, $db_name, $db_user, $db_pass);
$connection = $api->database_connection();
echo $api->SERVER_REQUEST($_SERVER, $token_code, $currentEndpoint, $connection);
?>
```


# Explanation

- **Database Connection:** The API connects to a MySQL database using credentials provided in the `config.php` file.
- **Routing:** Based on the HTTP request method, the appropriate API function (`GET`, `POST`, `PATCH`, `DELETE`) is called.
- **Dynamic Endpoints:** The `$currentEndpoint` variable dynamically captures the endpoint name from the filename, making it reusable for different API endpoints.
- **Error Handling:** If an unsupported request method is used, a standardized error message is returned.

## HTTP Methods

- **GET:** Fetch data from the database.

 ```php
$this->token($SERVER, $token_code)->GET($_GET, $connection, $currentEndpoint);
 ```

- **POST:** Update an existing record.

 ```php
$this->token($SERVER, $token_code)->POST($_POST, $connection, $currentEndpoint);
 ```

- **PATCH:** Partially update an existing record.

 ```php
$inputs = file_get_contents("php://input");
$this->token($SERVER, $token_code)->PATCH($inputs, $connection, $currentEndpoint);
 ```

- **DELETE:** Delete a record from the database.

 ```php
$inputs = file_get_contents("php://input");
$this->token($SERVER, $token_code)->DELETE($inputs, $connection, $currentEndpoint);
 ```

## Sample Configuration

Ensure your `config.php` file contains the correct database credentials:

```php
// Example configuration
$host = 'localhost';
$dbname = 'your_database';
$user = 'your_username';
$pass = 'your_password';
```
## Query Exemple

table_name - country
```php
$currentEndpoint = 'country';
```
```query
https://your-domain.com/webservices/country?where=name like 'Rus' and iso eq 'RU'
```

## Answer

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


