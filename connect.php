<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "newblog";
$charset = "utf8";

$dbcon = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select database
if (!mysqli_select_db($dbcon, $dbname)) {
    die("Database selection failed: " . mysqli_error($dbcon));
}

// Set charset
if (!mysqli_set_charset($dbcon, $charset)) {
    die("Setting charset failed: " . mysqli_error($dbcon));
}
