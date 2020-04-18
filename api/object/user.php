<?php
class User{
  
    // database connection and table name
    private $conn;
    private $table_name = "users";
  
    // object properties
    public $id;
    public $email;
    public $password;
    public $account_type;
    public $user_id;
    public $first_name;
    public $last_name;
    public $grade_year_level;
    public $acad_year;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read users
    function read(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    //get one record
    function readOne(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE email='" . $this->email . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    function readOneById(){
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id='" . $this->user_id . "'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create product
    function userCreate(){
        $q = "INSERT INTO
                " . $this->table_name . " (email, password, account_type, user_id, first_name, last_name, grade_year_level, acad_year) 
                VALUES (:email, :password, :account_type, :user_id, :first_name, :last_name, :grade_year_level, :acad_year) ";
  
        // prepare query
        $stmt = $this->conn->prepare($q);
        // bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":account_type", $this->account_type);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":grade_year_level", $this->grade_year_level);
        $stmt->bindParam(":acad_year", $this->acad_year);
        
        $stmt->execute();
        return $stmt;
        
    }
}
?>
