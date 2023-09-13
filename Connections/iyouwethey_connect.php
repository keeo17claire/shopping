<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_iyouwethey_connect = "localhost";
$database_iyouwethey_connect = "iyouwethey_db";
$username_iyouwethey_connect = "root";
$password_iyouwethey_connect = "12345678";
$iyouwethey_connect = mysql_pconnect($hostname_iyouwethey_connect, $username_iyouwethey_connect, $password_iyouwethey_connect) or trigger_error(mysql_error(),E_USER_ERROR); 
?>