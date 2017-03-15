<?php

$db_server = "sql303.byethost31.com";
$db_user = "b31_18875121";
$db_passwd = "MORRISWANG"; 
$db_name = "b31_18875121_CoViewingRoom";

$dsn = "mysql:host=$db_server;dbname=$db_name";
$dbh = new PDO($dsn, $db_user, $db_passwd);
$dbh->exec("SET NAMES utf8");

?>