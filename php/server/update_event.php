<?php
session_start();
include("formatter.php");
require_once("DAO.php");
$DAO = new DAO();

$username = $_SESSION['username'];
$eventID = $_POST['eventID'];
$startDate = $_POST['start_date'];
$endDate = $_POST['end_date'] == 'Invalid da' || $_POST['end_date'] == "" ? '0000-00-00' : $_POST['end_date'];
$startTime = getTimeFormat($_POST['start_hour']);
$endTime = getTimeFormat($_POST['end_hour']);

echo json_encode($DAO->updateEvent($username, $eventID, $startDate, $endDate, $startTime, $endTime));
