<?php
session_start();
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

$result = $DAO->addEvent($username, $title, $startDate, $allDay, $endDate, $startTime, $endTime);
echo json_encode($result);

function isDate($date)
{
    return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date);
}
function isTime($time)
{
    return preg_match("#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#", $time);
}
function dateChecks($startDate, $endDate)
{
    $startYear = substr($startDate, 0, 4);
    $startMonth = substr($startDate, 5, 2);
    $startDay = substr($startDate, 8, 2);
    $endYear = substr($endDate, 0, 4);
    $endMonth = substr($endDate, 5, 2);
    $endDay = substr($endDate, 8, 2);

    return   $startYear  < $endYear || ($startYear == $endYear && $startMonth  < $endMonth) || ($startYear == $endYear && $startMonth == $endMonth && $startDay < $endDay) || ($startYear == $endYear && $startMonth == $endMonth && $startDay == $endDay);
}
function timeChecks($startTime, $endTime)
{
    $startHrs = substr($startTime, 0, 2);
    $startMins = substr($startTime, 3, 2);
    $endHrs = substr($endTime, 0, 2);
    $endMins = substr($endTime, 3, 2);

    return $startHrs < $endHrs || ($startHrs == $endHrs && $startMins < $endMins);
}
