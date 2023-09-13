<?php

require_once('Connections/iyouwethey_connect.php');

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);

if (isset($_GET['id'])) {
  $IngID = GetSQLValueString($_GET['id'], "int");
  $query_requisition_ingredient = "SELECT IngID FROM ingredient WHERE IngID = $IngID";
} else {
  die("IngID not provided in the query string.");
}

$requisition_ingredient = mysql_query($query_requisition_ingredient, $iyouwethey_connect) or die(mysql_error());
$totalRows_requisition_ingredient = mysql_num_rows($requisition_ingredient);

if ($totalRows_requisition_ingredient > 0) {

  $deleteSQL = sprintf("DELETE FROM ingredient WHERE IngID=%s", $IngID);

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($deleteSQL, $iyouwethey_connect) or die(mysql_error());

  $deleteGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
} else {
  echo "<center><h3>ไม่สามารถลบข้อมูลได้ เนื่องจากมีการอ้างอิงอยู่</h3>";
  echo "<a href='javascript:history.back(-1)'> กลับหน้าเดิม</a></center>";
}
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Untitled Document</title>
</head>

<body>
</body>

</html>
<?php
mysql_free_result($requisition_ingredient);
?>