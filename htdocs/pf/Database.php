<?php
// Database connector
// Parameter values are specified in 'config.php' file
class Database {
    private $conn;

    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $this->conn = new PDO($dsn, DB_USER, DB_PASS, $options);
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
