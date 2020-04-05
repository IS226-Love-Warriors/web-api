<?php
class Database{
  
    // specify your own database credentials
    private $host = "us-cdbr-iron-east-04.cleardb.net";
    private $db_name = "heroku_b051a52f2f68249";
    private $username = "b1827c6e04a4e9";
    private $password = "867fdea9";
    public $conn;
  
    // get the database connection
    public function getConnection(){
  
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>