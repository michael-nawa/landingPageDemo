<?php
/* Starting the session */
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "landingpagedemo";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$connection  = mysqli_connect($servername, $username, $password)
    or die("Error, database could not be created");

mysqli_select_db($connection, $database);

define('GET_PATH', '../../../assets/img/');
