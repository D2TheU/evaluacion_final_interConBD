<?php
session_start();
require_once("DAO.php");
$DAO = new DAO();

$username = $_SESSION['username'];

echo json_encode($DAO->getEvents($username));
