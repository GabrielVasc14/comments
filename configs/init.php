<?php

session_start();
mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL);
ini_set('display_errors', 1);

define("HOST", "localhost"); // The host you want to connect to.
define("USER", "root"); // The database username.
define("PASSWORD", ""); // The database password.
define("DATABASE", "bd_comments"); // The database name.