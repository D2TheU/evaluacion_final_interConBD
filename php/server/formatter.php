<?php
function isDate($date)
{
    return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date);
}
function isTime($time)
{
    $pregTxt = "#([0-1]{1}[0-9]{1}|[2]{1}[0-3]{1}):[0-5]{1}[0-9]{1}#";
    return preg_match($pregTxt, $time);
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

function getTimeFormat($time)
{
    if (isTime($time)) {
        if (strlen($time) > 5) {
            return substr($time, 0, 5);
        }
        return $time;
    } else {
        return "00:00";
    }
}
