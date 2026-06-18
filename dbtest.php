<?php
$host = getenv('DB_HOST');
$name = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

echo "HOST: $host<br>NAME: $name<br>USER: $user<br>";

try {
    $conn = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass);
    echo "CONNECTED OK";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage();
}
