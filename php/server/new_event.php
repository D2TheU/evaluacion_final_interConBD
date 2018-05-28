<?php
session_start();
include("formatter.php");
require_once("DAO.php");
$DAO = new DAO();

$username = $_SESSION['username'];

if (!isset($_POST['titulo']) || !isset($_POST['start_date']) || !isset($_POST['allDay']) || !isset($_POST['end_date']) || !isset($_POST['start_hour']) || !isset($_POST['end_hour'])) {
    echo json_encode(array('result' => 'failed', 'message' => 'Missing value.'));
    exit;
}
$title = $_POST['titulo'];
$startDate = $_POST['start_date'];
$allDay = $_POST['allDay'] == 'true' ? 1 : 0;
$endDate = $_POST['end_date'] == '' ? '0000-00-00' : $_POST['end_date'];
$startTime = $_POST['start_hour'] == '' ? '00:00' : $_POST['start_hour'];
$endTime = $_POST['end_hour'] == '' ? '00:00' : $_POST['end_hour'];

if (!isDate($startDate) || (!isDate($endDate) && $allDay == 0)) {
    echo json_encode(array('result' => 'failed', 'message' => 'Incorrect date format.'));
    exit;
}
if ($allDay == 0 && !dateChecks($startDate, $endDate)) {
    echo json_encode(array('result' => 'failed', 'message' => 'End date must be after or the same as start date.'));
    exit;
}
if ($allDay == 0 && (!isTime($startTime) || !isTime($endTime))) {
    echo json_encode(array('result' => 'failed', 'message' => 'Incorrect time format.'));
    exit;
}
if ($allDay == 0 && !timeChecks($startTime, $endTime)) {
    echo json_encode(array('result' => 'failed', 'message' => 'End time must be after start time.'));
    exit;
}

$result = $DAO->addEvent($username, $title, $startDate, $endDate, $startTime, $endTime, $allDay);
echo json_encode($result);
