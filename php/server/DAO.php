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

    public function verifyLogin($email, $password)
    {
        $this->connectDB();

        $sql="SELECT chrPassword AS password FROM tblusers WHERE chrEmail = :email LIMIT 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
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
                    'result'=> 'failed',
                    'message' => 'Password and username do not match.'
                );
            }
        } else {
            $response = array(
                'result'=> 'failed',
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
                'result'=> 'failed',
                'message' => 'Account already exists.'
            );
        }
        $this->conn = null;
        return $response;
    }

    public function getEvents($email)
    {
        $this->connectDB();

        $sql="SELECT
            intIDEvent AS id,
            chrTitle AS title,
            CONCAT(dtdStart, 'T',chrStartTime,':00') AS 'start',
            CONCAT(dtdEnd, 'T',chrEndTime,':00') AS 'end',
            intAllDay AS allDay
        FROM tblevents
        WHERE chrFKUserEmail = :email
        AND intActivo = 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $response = array(
            'result'=> 'ok',
            'message' => 'Event added.',
            'events' => array()
        );
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['allDay'] = $row['allDay'] == 1 ? true : false;
            $response['events'][] = $row;
        }
        $this->conn = null;
        return $response;
    }

    public function addEvent($email, $title, $startDate, $endDate, $startTime, $endTime, $allDay)
    {
        $this->connectDB();

        $sql="SELECT * FROM tblusers WHERE chrEmail = :email LIMIT 1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $response = array();
        if ($stmt->rowCount()) {
            $sql="INSERT INTO tblevents (chrTitle, chrFKUserEmail, dtdStart, dtdEnd, chrStartTime, chrEndTime, intAllDay)
            VALUES
            (:title, :email, :startDate, :endDate, :startTime, :endTime, :allDay);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);
            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            $stmt->bindParam(':allDay', $allDay, PDO::PARAM_INT);
            $stmt->execute();

            $response = array(
                'result'=> 'ok',
                'id' => $this->conn->lastInsertId(),
                'message' => 'Event added.'
            );
        } else {
            $response = array(
                'result'=> 'failed',
                'message' => 'User not found.'
            );
        }
        $this->conn = null;
        return $response;
    }

    public function updateEvent($email, $eventID, $startDate, $endDate, $startTime, $endTime)
    {
        $this->connectDB();

        $sql="UPDATE tblevents
        SET dtdStart = :startDate, chrStartTime = :startTime, dtdEnd = :endDate, chrEndTime = :endTime
        WHERE intIDEvent = :eventID
        AND chrFKUserEmail = :email;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
        $stmt->bindParam(':startDate', $startDate, PDO::PARAM_INT);
        $stmt->bindParam(':endDate', $endDate, PDO::PARAM_INT);
        $stmt->bindParam(':startTime', $startTime, PDO::PARAM_INT);
        $stmt->bindParam(':endTime', $endTime, PDO::PARAM_INT);
        $stmt->execute();

        $response = array(
            'result'=> 'ok',
            'message' => 'Event updated.'
        );

        $this->conn = null;
        return $response;
    }

    public function deleteEvent($email, $eventID)
    {
        $this->connectDB();

        $sql="UPDATE tblevents
        SET intActivo = 0
        WHERE intIDEvent = :eventID
        AND chrFKUserEmail = :email;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
        $stmt->execute();

        $response = array(
            'result'=> 'ok',
            'message' => 'Event deleted.'
        );

        $this->conn = null;
        return $response;
    }
}
