<?php
// 'user' object
class Users
{

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
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readAll()
    {
        $sql = "SELECT *
                FROM " . $this->table_name . "";

        $query = $this->conn->prepare($sql);

        $query->execute();

        return $query;
    }

    public function checkEmail()
    {

        // query to check if email exists
        $sql = "SELECT id, firstname, lastname, password
                FROM " . $this->table_name . "
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($sql);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(1, $this->email);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];
            $this->role = $row['role'];
            $this->language = $row['language'];

            return true;
        }

        return false;
    }

    // create new user record
    public function create()
    {

        if (empty($this->firstname) ||
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
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->language = htmlspecialchars(strip_tags($this->language));

        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':language', $this->language);

        // hash the password before saving to database
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bindParam(':password', $password_hash);

        // execute the query, also check if query was successful
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update()
    {

        // if password needs to be updated
        //$password_set=!empty($this->password) ? ", password = :password" : "";

        // if no posted password, do not update the password
        $sql = 'UPDATE ' . $this->table_name . '
                SET
                    id = :id,
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email,
                    password = :password,
                    role = :role,
                    language = :language
                WHERE id = :id';

        // prepare the query
        $stmt = $this->conn->prepare($sql);

        // sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->language = htmlspecialchars(strip_tags($this->language));

        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind the values from the form
        $stmt->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        //$stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
        $stmt->bindParam(':role', $this->role, PDO::PARAM_STR);
        $stmt->bindParam(':language', $this->language, PDO::PARAM_STR);

        // hash the password before saving to database
        if (!empty($this->password)) {
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
