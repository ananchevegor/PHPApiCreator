PHPApiCreator - README
PHPApiCreator is a PHP library that makes creating scalable APIs simple and efficient. It allows you to manage database connections, handle different HTTP methods (GET, PUT, PATCH, DELETE), and structure API responses in JSON format. Below is a sample use case of the library for quickly setting up an API with database support.

Features
Easy Database Integration: Connect to a MySQL database effortlessly.
HTTP Method Handling: Supports GET, PUT, PATCH, DELETE requests.
RESTful Design: Follows REST principles for API structure.
JSON Responses: Automatically sets headers and outputs in JSON.
Error Handling: Centralized and consistent error responses.
Installation

Clone the repository:
git clone https://github.com/ananchevegor/PHPApiCreator.git


Install dependencies via Composer:
composer require ananchev/php-api-creator


Include the library in your project:

<?php
require_once 'PhpApiCreator.php';
header('Content-Type: application/json; charset=utf-8');
require_once("../../config.php");

// Extract current endpoint name from the file
$currentEndpoint = pathinfo(__FILE__, PATHINFO_FILENAME);

// Initialize the API with database credentials
$api = new PHPApiCreator($db_host, $db_name, $db_user, $db_pass);

// Establish a database connection
$connection = $api->database_connection();

// Switch between HTTP methods
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
Explanation
Database Connection: The API connects to a MySQL database using credentials provided in the config.php file.
Routing: Based on the HTTP request method, the appropriate API function (GET, PUT, PATCH, DELETE) is called.
Dynamic Endpoints: The $currentEndpoint variable captures the endpoint name dynamically from the filename, making it reusable across different API endpoints.
Error Handling: If an unsupported request method is used, a standardized error message is returned.
HTTP Methods
GET: Fetch data from the database.


echo $api->GET($_GET, $connection, $currentEndpoint);
PUT: Update an existing record.


$inputs = file_get_contents("php://input");
echo $api->PUT($inputs, $connection, $currentEndpoint);
PATCH: Partially update an existing record.


$inputs = file_get_contents("php://input");
echo $api->PATCH($inputs, $connection, $currentEndpoint);
DELETE: Delete a record from the database.


$inputs = file_get_contents("php://input");
echo $api->DELETE($inputs, $connection, $currentEndpoint);
Sample Configuration
Ensure your config.php file contains the correct database credentials:


<?php
$db_host = 'localhost';
$db_name = 'your_database_name';
$db_user = 'your_username';
$db_pass = 'your_password';
?>


License
This project is licensed under the MIT License. See the LICENSE file for details.

Support
If you encounter any issues or have questions, please open an issue on the GitHub repository or reach out to us via email.
