<?php
namespace Ananchev\PhpApiCreator;
class PHPApiCreator
{
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;


    public function __construct(string $db_host, string $db_name, string $db_user, string $db_pass)
    {
        $this->db_host = $db_host;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
    }



    public function database_connection()
    {
        return mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
    }

    public function GET(array $GET, \mysqli $connection, string $currentEndpoint)
    {


        if (!$connection) {
            die("Connection error: " . mysqli_connect_error());
        }
        if ($this->table_exist($connection, $currentEndpoint) === false) {
            die("Table not exist");
        }   


        $endpoint_conclusion = array();
        $endpoint_conclusion["table"] = $currentEndpoint;
        $endpoint_conclusion["time"] = time();
        $endpoint_conclusion["payload"] = [];
        $column_name = array();
         
        $query_get_column_name = "DESCRIBE ".$currentEndpoint;
        $sql_get_column_name = @mysqli_query($connection, $query_get_column_name);
        while ($row = @mysqli_fetch_array($sql_get_column_name)) {
            if (isset($GET["select"])) {
                $select_array = explode(",", $GET["select"]);
                foreach ($select_array as $value) {
                    if ($value == $row["Field"]) {
                        array_push($column_name, $row["Field"]);
                    }
                } 
            }else {
                array_push($column_name, $row["Field"]);
            }
            
        }
        $select = "*";
        if (isset($GET["select"])) {
            $select = implode(", ", $column_name);
        }
        $query_get = "SELECT ".mysqli_real_escape_string($connection, $select)." FROM ".$currentEndpoint;
        foreach ($GET as $key => $value) {
            switch ($key) {
                case 'order':
                    $query_get .= " ORDER BY ".mysqli_real_escape_string($connection, $value)." ".mysqli_real_escape_string($connection, $GET["sort"]);
                    break;
                case 'limit':
                    $query_get .= " LIMIT ".mysqli_real_escape_string($connection, $value);
                    break;
                case 'where':
                    if (stripos($value, 'and')) {
                        $matches_array = explode(" and ", $value);
                        $query_get .= " WHERE ";
                        foreach ($matches_array as $i => $m) {
                            if ($i > 0) {
                                $query_get .= " AND ";
                            }
                            if (!stripos($m, 'like')) {
                                preg_match("/(.+)\s+eq\s+'(.+)'/", $m, $matches);
                                $query_get .= mysqli_real_escape_string($connection, $matches[1])."='".mysqli_real_escape_string($connection, $matches[2])."'";
                            }else {
                                preg_match("/(.+)\s+like\s+'(.+)'/", $m, $matches);
                                $query_get .= mysqli_real_escape_string($connection, $matches[1])." LIKE '%".mysqli_real_escape_string($connection, $matches[2])."%'";
                            }
                        }
                    }else {
                        $matches_array = explode(" or ", $value);
                        $query_get .= " WHERE ";
                        foreach ($matches_array as $i => $m) {
                            if ($i > 0) {
                                $query_get .= " OR ";
                            }
                            if (!stripos($m, 'like')) {
                                preg_match("/(.+)\s+eq\s+'(.+)'/", $m, $matches);
                                $query_get .= mysqli_real_escape_string($connection, $matches[1])."='".mysqli_real_escape_string($connection, $matches[2])."'";
                            }else {
                                preg_match("/(.+)\s+like\s+'(.+)'/", $m, $matches);
                                $query_get .= mysqli_real_escape_string($connection, $matches[1])." LIKE '%".mysqli_real_escape_string($connection, $matches[2])."%'";
                            }
                        }
                    }
                    
                    break;
                default:
                    $query_get .= "";
                    break;
            }
        }
    
        $sql_get = @mysqli_query($connection, $query_get);
    
        while ($row = @mysqli_fetch_array($sql_get)) {
            $array_json = array();
            for ($i=0; $i < count($row); $i++) { 
                if ($column_name[$i] != "") {
                    $array_json[$column_name[$i]] = $row[$i];
                }
            }
            array_push($endpoint_conclusion["payload"], $array_json);
        }
        return json_encode($endpoint_conclusion, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }


    public function POST($rawData, \mysqli $connection, string $currentEndpoint)
    {

        if (!$connection) {
            die("Connection error: " . mysqli_connect_error());
        }
        if ($this->table_exist($connection, $currentEndpoint) === false) {
            die("Table not exist");
        }   
        $endpoint_conclusion = array();
        $endpoint_conclusion["table"] = $currentEndpoint;
        $endpoint_conclusion["time"] = time();
        $endpoint_conclusion["payload"] = [];
        $data = json_decode($rawData, true);
        $data_keys = array_keys($data);
        $query_put = "INSERT INTO ".$currentEndpoint."(";
        foreach ($data_keys as $index => $key) {
            if ($index == (count($data_keys)-1)) {
                $query_put .= $key;
            }else {
                $query_put .= $key.", ";
            }
        }
        $query_put .= ") VALUES(";
        $count_index = 0;
        foreach ($data as $index => $value) {
            if ($count_index == (count($data)-1)) {
                $query_put .= "'".mysqli_real_escape_string($connection, $value)."'";
            }else {
                $query_put .= "'".mysqli_real_escape_string($connection, $value)."', ";
            }
            $count_index++;
        }
        $query_put .= ");";
        try {
            @mysqli_query($connection, $query_put);
            http_response_code(201); 
            $endpoint_conclusion["payload"] = ["success" => "Data successful inserted"];
        } catch (\Throwable $th) {
            $endpoint_conclusion["payload"] = ["error" => "Data not inserted"];
        }
        return json_encode($endpoint_conclusion, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }


    public function PATCH($rawData, \mysqli $connection, string $currentEndpoint)
    {

        if (!$connection) {
            die("Connection error: " . mysqli_connect_error());
        }
        if ($this->table_exist($connection, $currentEndpoint) === false) {
            die("Table not exist");
        }   
        $endpoint_conclusion = array();
        $endpoint_conclusion["table"] = $currentEndpoint;
        $endpoint_conclusion["time"] = time();
        $endpoint_conclusion["payload"] = [];
        $data = json_decode($rawData, true);
        $query_patch = "UPDATE ".$currentEndpoint." SET ";
    
        foreach ($data["data"] as $key => $value) {
            $query_patch .= $key ." = '".$value."', ";
        }
        $query_patch = rtrim($query_patch, ', ');
        $condition_count = 0;
        foreach ($data["condition"] as $key => $value) {
            if ($condition_count > 0) {
                $query_patch .= " AND ".mysqli_real_escape_string($connection, $key)."='".mysqli_real_escape_string($connection, $value)."'";
            }else {
                $query_patch .= " WHERE ".mysqli_real_escape_string($connection, $key)."='".mysqli_real_escape_string($connection, $value)."'";
            }
            
            $condition_count++;
        }
    
        try {
            @mysqli_query($connection, $query_patch);
            $endpoint_conclusion["payload"] = ["success" => "Data successful updated"];
        } catch (\Throwable $th) {
            $endpoint_conclusion["payload"] = ["error" => "Data not updated"];
        }
        return json_encode($endpoint_conclusion, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function DELETE($rawData, \mysqli $connection, string $currentEndpoint)
    {

        if (!$connection) {
            die("Connection error: " . mysqli_connect_error());
        }
        if ($this->table_exist($connection, $currentEndpoint) === false) {
            die("Table not exist");
        }   
        $endpoint_conclusion = array();
        $endpoint_conclusion["table"] = $currentEndpoint;
        $endpoint_conclusion["time"] = time();
        $endpoint_conclusion["payload"] = [];
        $data = json_decode($rawData, true);
        $query_delete = "DELETE FROM ".$currentEndpoint." ";
        $condition_count = 0;
        foreach ($data["condition"] as $key => $value) {
            if ($condition_count > 0) {
                $query_delete .= " AND ".mysqli_real_escape_string($connection, $key)."='".mysqli_real_escape_string($connection, $value)."'";
            }else {
                $query_delete .= " WHERE ".mysqli_real_escape_string($connection, $key)."='".mysqli_real_escape_string($connection, $value)."'";
            }
            
            $condition_count++;
        }
    
        try {
            @mysqli_query($connection, $query_delete);
            $endpoint_conclusion["payload"] = ["success" => "Data successful deleted"];
        } catch (\Throwable $th) {
            $endpoint_conclusion["payload"] = ["error" => "Data not deleted"];
        }
        return json_encode($endpoint_conclusion, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }


    private function table_exist(\mysqli $connection, string $tableName)
    {
        if (!$connection) {
            die("Connection error: " . mysqli_connect_error());
        }
        $sql = "SHOW TABLES LIKE '$tableName'";

        $result = @mysqli_query($connection, $sql);

        if (@mysqli_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
        @mysqli_close($connection);
    }

    private function token($SERVER)
    {
        if(!isset($SERVER["HTTP_AUTHORIZATION"]))
        {
            http_response_code(401); 
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Token is invalid. Incorrect exeprion'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            exit();
        }
        
        $token_access = substr(getallheaders()['Authorization'], 7);

        // Your code for Authorization

        return $this;
    }

    public function SERVER_REQUEST($SERVER, string $token_code, string $currentEndpoint, \mysqli $connection)
    {   
        switch ($SERVER['REQUEST_METHOD']) {
            case 'GET':
                echo $this->token($SERVER, $token_code)->GET($_GET, $connection, $currentEndpoint);
                break;
            case 'POST':
                echo $this->token($SERVER, $token_code)->POST($_POST, $connection, $currentEndpoint);
                break;
            case 'PATCH':
                $inputs = file_get_contents("php://input");
                echo $this->token($SERVER, $token_code)->PATCH($inputs, $connection, $currentEndpoint);
                break;
            case 'DELETE':
                $inputs = file_get_contents("php://input");
                echo $this->token($SERVER, $token_code)->DELETE($inputs, $connection, $currentEndpoint);
                break;
            default:
                http_response_code(405); 
                echo json_encode(array(
                    "table" => "none",
                    "time" => time(),
                    "payload" => array(
                        "error" => "The server does not support this type of request"
                    )
                ));
                break;
        }
    }
}
?>