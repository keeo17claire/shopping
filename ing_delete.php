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

  // Start a transaction to ensure data integrity
  mysql_query("START TRANSACTION", $iyouwethey_connect);

  // Delete references from the added_ingredient table
  $deleteAddedIngredientSQL = sprintf("DELETE FROM added_ingredient WHERE IngID=%s", $IngID);
  $resultAddedIngredient = mysql_query($deleteAddedIngredientSQL, $iyouwethey_connect);

  if (!$resultAddedIngredient) {
    // If an error occurred, rollback the transaction and show an error message
    mysql_query("ROLLBACK", $iyouwethey_connect);
    die("Error deleting from added_ingredient: " . mysql_error());
  }

  // Delete the ingredient from the ingredient table
  $deleteIngredientSQL = sprintf("DELETE FROM ingredient WHERE IngID=%s", $IngID);
  $resultIngredient = mysql_query($deleteIngredientSQL, $iyouwethey_connect);

  if (!$resultIngredient) {
    // If an error occurred, rollback the transaction and show an error message
    mysql_query("ROLLBACK", $iyouwethey_connect);
    die("Error deleting from ingredient: " . mysql_error());
  }

  // If both deletions were successful, commit the transaction
  mysql_query("COMMIT", $iyouwethey_connect);

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