<?php 
$host = 'localhost';
$port = '3306';
$dbname = 'sportholter';
$username = 'root';
$password = '';

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);

                
return $mysqli;
?>