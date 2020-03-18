<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "users";
  
    // object properties
    public $id;
    public $username;
    public $email;
    public $firstname;
    public $lastname;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create product
    function create(){
        $q = "INSERT INTO
                " . $this->table_name . " (id, username, password, email, firstname, lastname) VALUES (:id, :username, :password, :email, :firstname, :lastname) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);

        // bind values
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        
        $stmt->execute();
        print_r($stmt);
        return $stmt;
        
    }
}
?>
