<?php
// 'user' object
class Users{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
    public $role;
    public $language;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    public function readAll()
    {
        $sql = "SELECT id, firstname, lastname, email, password, role, language 
                FROM " . $this->table_name. "
                ORDER BY id_user";
        $query = $this->conn->prepare($sql);

        $query->execute();

        return $query;
    }
     
    // create new user record
    function create(){

        if(empty($this->firstname) || 
        empty($this->lastname) ||
        empty($this->email) ||
        empty($this->firstname) ||
        empty($this->password) ||
        empty($this->role) ||
        empty($this->language)) {
            return false;
        }
     
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    password = :password,
                    role = :role,
                    language = :language";
                    
        // prepare the query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->role=htmlspecialchars(strip_tags($this->role));
        $this->language=htmlspecialchars(strip_tags($this->language));
     
        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
     
        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $password_hash);
     
        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }
         

}