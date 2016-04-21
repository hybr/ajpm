<?php
session_start();
$_SESSION['testKey'] = "0b111";
 echo $_SESSION['database_name'];
 echo "<br>";
 echo $_SERVER['SERVER_NAME'];
 echo "<br>";
 echo $_SESSION['mongo_database'];
?>
