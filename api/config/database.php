<?php
class Database{
  
    // specify your own database credentials
    private $host = "us-cdbr-iron-east-04.cleardb.net/heroku_b051a52f2f68249";
    private $db_name = "minischool_db";
    private $username = "heroku_b051a52f2f68249";
    private $password = "867fdea9%%";
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