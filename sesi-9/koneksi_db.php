<?php
// PDO connection to MySQL database
$host = '127.0.0.1'; // localhost or 127.0.0.1
$db   = 'bootcamp8';
$user = 'root';
$pass = 'new_password';
$charset = 'utf8mb4'; // character set for the database connection

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; // Data Source Name for PDO connection

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch results as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false, // disable emulation of prepared statements
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
    //  echo "Connected successfully";
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>