<?php
session_start();
require_once("DAO.php");
$DAO = new DAO();

$username = $_SESSION['username'];
$eventID = $_POST['eventID'];

echo json_encode($DAO->deleteEvent($username, $eventID));
