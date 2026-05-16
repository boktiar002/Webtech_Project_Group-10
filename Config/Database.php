<?php
class Database {
    private $host = "localhost";
    private $db_name = "blog_news_project";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        if ($this->conn instanceof mysqli) {
            return $this->conn;
        }

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            $this->conn->set_charset("utf8mb4");
        } catch (mysqli_sql_exception $exception) {
            die("Connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }

    public function connection() {
        return $this->getConnection();
    }
}
?>
