<?php
require_once("DAO.php");
$DAO = new DAO();

print_r($DAO->createUser('Pedro Bread', 'pb@mail.com', '123456', '1998-01-20'));
echo "<br>";
print_r($DAO->createUser('Arnoldo Suaresnagera', 'ar.su@mail.com', 'password', '1947-07-30'));
echo "<br>";
print_r($DAO->createUser('Felipe Rios', 'fel.os@mail.com', 'password123', '1981-12-08'));
