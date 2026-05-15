<?php
class Database {
    private $host = "localhost";
    private $db_name = "blog_news_project";
    private $username = "root";
    private $password = "";
    public $conn;

    // কনস্ট্রাকটর ব্যবহার করলে getConnection() কল করা সহজ হয়
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // ইউনিকোড সাপোর্টের জন্য নিচের লাইনটি যোগ করতে পারেন (বাংলা টেক্সট ঠিক রাখতে)
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>