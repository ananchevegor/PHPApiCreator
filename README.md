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
database_connection(); // Switch between HTTP methods
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
