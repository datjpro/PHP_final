<?php

class Database {
private $host = "localhost";
private $db_name = "my_store";
private $username = "root";
private $password = "";
public $conn;
public function getConnection() {
$this->conn = null;
try {
$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
$this->conn->exec("set names utf8");
} catch(PDOException $exception) {
echo "Connection error: " . $exception->getMessage();
}
return $this->conn;
}
}

// class Database {
//     private $host = "localhost";
//     private $db_name = "my_store";
//     private $username = "root";
//     private $password = "";
//     private $port = "3308"; // Thêm cổng 3308
//     public $conn;
    
//     public function getConnection() {
//         $this->conn = null;
//         try {
//             $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
//             $this->conn->exec("set names utf8");
//         } catch(PDOException $exception) {
//             echo "Connection error: " . $exception->getMessage();
//         }
//         return $this->conn;
//     }
// }
