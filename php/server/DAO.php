<?php
class DAO
{
    private $servername = "localhost";
    private $username = "dueynn";
    private $password = "YudoRtFnIziUwANA";
    private $dbname = "calendar";
    private $conn;

    public function __construct()
    {
    }

    private function connectDB()
    {
        $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password) or die("OOPs something went wrong");
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function verifyLogin($username, $password)
    {
        $this->connectDB();

        $sql="SELECT chrPassword AS password FROM tblusers WHERE chrEmail = :email LIMIT 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $username, PDO::PARAM_STR);
        $stmt->execute();

        $response = array();
        if ($stmt->rowCount()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $result['password'])) {
                $response = array(
                    'result'=> 'ok',
                    'message' => 'Login successful.'
                );
            } else {
                $response = array(
                    'result'=> 'fail',
                    'message' => 'Password and username do not match.'
                );
            }
        } else {
            $response = array(
                'result'=> 'fail',
                'message' => 'Username and password do not match.'
            );
        }
        $this->conn = null;
        return $response;
    }

    public function createUser($fullname, $email, $password, $dateBirth)
    {
        $this->connectDB();

        $sql="SELECT * FROM tblusers WHERE chrEmail = :email LIMIT 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $response = array();
        if (!$stmt->rowCount()) {
            $sql="INSERT INTO tblusers (chrEmail, chrFullName, chrPassword, dtdBirth)
            VALUES (:email, :fullname, :password, :dateBirth);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
            $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindParam(':dateBirth', $dateBirth, PDO::PARAM_STR);
            $stmt->execute();

            $response = array(
                'result'=> 'ok',
                'message' => 'Account has been created.'
            );
        } else {
            $response = array(
                'result'=> 'fail',
                'message' => 'Account already exists.'
            );
        }
        $this->conn = null;
        return $response;
    }
}
