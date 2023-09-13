<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_iyouwethey_connect = "localhost";
$database_iyouwethey_connect = "iyouwethey_db";
$username_iyouwethey_connect = "root";
$password_iyouwethey_connect = "";

// Connect to MySQL
$iyouwethey_connect = mysql_pconnect($hostname_iyouwethey_connect, $username_iyouwethey_connect, $password_iyouwethey_connect) or trigger_error(mysql_error(), E_USER_ERROR);

// Check if the connection was successful
if ($iyouwethey_connect) {
    // Set the character set to UTF-8
    mysql_set_charset("utf8", $iyouwethey_connect);
} else {
    // Handle connection error
    die("Database connection failed: " . mysql_error());
}
?>

