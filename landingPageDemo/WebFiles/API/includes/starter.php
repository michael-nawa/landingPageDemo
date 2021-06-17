<?php
/* Starting the session */
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "id15761410_martindb";

// $servername = "localhost";
// $username = "id15761410_martincpproject";
// $password = "#computingProject2077";
// $database = "id15761410_martindb";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$connection  = mysqli_connect($servername, $username, $password)
    or die("Error, database could not be created");


mysqli_select_db($connection, $database);


//defaults
define('IMG_PATH', '../assets/img/');
define('PDF_PATH', '../assets/pdf/');

//fetching file urls
// define('GET_IMG_Path', 'assets/img/');
// define('GET_PDF_Path', 'assets/pdf/');

define('GET_IMG_Path', 'https://mwila-university.000webhostapp.com/MartinDB/assets/img/');
define('GET_PDF_Path', 'https://mwila-university.000webhostapp.com/MartinDB/assets/pdf/');


$response = [];
$results = null;
$page_num = 1;
$start = 0;
$limit = 15;
$ImgName = null;
$PdfName = null;
