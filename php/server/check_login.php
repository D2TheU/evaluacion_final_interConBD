<?php
session_start();
require_once("DAO.php");
$DAO = new DAO();

$result = $DAO->verifyLogin($_POST['username'], $_POST['password']);

if ($result['result'] == 'ok') {
    // Start session
    $_SESSION["username"] = $_POST['username'];
}
echo json_encode($result);
