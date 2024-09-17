# PHPApiCreator

**PHPApiCreator** is a PHP library designed to simplify the creation of scalable APIs. It supports managing database connections, handling various HTTP methods (GET, PUT, PATCH, DELETE), and structuring API responses in JSON format. Below is a quick guide on how to set up and use the library.

## Features

- **Easy Database Integration**: Connect to a MySQL database effortlessly.
- **HTTP Method Handling**: Supports GET, PUT, PATCH, DELETE requests.
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
$connection = $api->database_connection(); // Switch between HTTP methods
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo $api->GET($_GET, $connection, $currentEndpoint);
        break;
    case 'PUT':
        $inputs = file_get_contents("php://input");
        echo $api->PUT($inputs, $connection, $currentEndpoint);
        break;
    case 'PATCH':
        $inputs = file_get_contents("php://input");
        echo $api->PATCH($inputs, $connection, $currentEndpoint);
        break;
    case 'DELETE':
        $inputs = file_get_contents("php://input");
        echo $api->DELETE($inputs, $connection, $currentEndpoint);
        break;
    default:
        echo json_encode(array(
            "table" => "none",
            "time" => time(),
            "payload" => array(
                "error" => "The server does not support this type of request"
            )
        ));
        break;
}
?>
```


# Explanation

- **Database Connection:** The API connects to a MySQL database using credentials provided in the `config.php` file.
- **Routing:** Based on the HTTP request method, the appropriate API function (`GET`, `PUT`, `PATCH`, `DELETE`) is called.
- **Dynamic Endpoints:** The `$currentEndpoint` variable dynamically captures the endpoint name from the filename, making it reusable for different API endpoints.
- **Error Handling:** If an unsupported request method is used, a standardized error message is returned.

## HTTP Methods

- **GET:** Fetch data from the database.

 ```php
 echo $api->GET($_GET, $connection, $currentEndpoint);
 ```

- **PUT:** Update an existing record.

 ```php
 $inputs = file_get_contents("php://input");
 echo $api->PUT($inputs, $connection, $currentEndpoint);
 ```

- **PATCH:** Partially update an existing record.

 ```php
 $inputs = file_get_contents("php://input");
 echo $api->PATCH($inputs, $connection, $currentEndpoint);
 ```

- **DELETE:** Delete a record from the database.

 ```php
 $inputs = file_get_contents("php://input");
 echo $api->DELETE($inputs, $connection, $currentEndpoint);
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
