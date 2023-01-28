<?php 

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json");

$baseUrl = "http://localhost:8000";

$host = "localhost";

$db   = "laravel";

$user = "root";

$pass = "";

$port = "3306";

$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";

try 
{
    $db = new \PDO($dsn, $user, $pass);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} 
catch (\PDOException $e) 
{
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
