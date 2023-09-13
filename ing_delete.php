<?php require_once('Connections/laborders_connect.php'); ?>
<?php require_once('Connections/iyouwethey_connect.php'); ?>
<?php
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
//สร้างrecordset ของ detail_orders

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_requisition_ingredient = "SELECT IngID FROM requisition_ingredient WHERE IngID = colname";
$requisition_ingredient = mysql_query($query_requisition_ingredient, $iyouwethey_connect) or die(mysql_error());
$row_requisition_ingredient = mysql_fetch_assoc($requisition_ingredient);
$totalRows_requisition_ingredient = mysql_num_rows($requisition_ingredient);
//******************

//ตรวจสอบการอ้างอิงในตาราง detail_orders ถ้ามีการอ้างอิงอยู่จะไม่ลบจากตาราง product
if($totalRows_Rec_requisition_ingredient==0){

if ((isset($_GET['iid'])) && ($_GET['iid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM requisition_ingredient WHERE IngID=%s",
                       GetSQLValueString($_GET['iid'], "int"));

  mysql_select_db($database_laborders_connect, $laborders_connect);
  $Result1 = mysql_query($deleteSQL, $laborders_connect) or die(mysql_error());

  $deleteGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
} //end if $totalRows_Rec_detail_orders
else {
	echo "<center><h3>ไม่สามารถลบข้อมูลได้ เนื่องจากมีการอ้างอิงอยู่</h3>";
	echo "<a href='javascript:history.back(-1)'> กลับหน้าเดิม</a></center>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
