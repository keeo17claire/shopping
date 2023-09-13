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

$colname_Rec_req_report = "-1";
if (isset($_POST['IngID'])) {
  $colname_Rec_req_report = $_POST['IngID'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_req_report = sprintf("SELECT * FROM requisition_ingredient WHERE (IngID = %s) OR (IngID LIKE %s) ORDER BY IngID ASC", GetSQLValueString($colname_Rec_req_report, "int"),GetSQLValueString("%" . $colname_Rec_req_report . "%", "int"));
$Rec_req_report = mysql_query($query_Rec_req_report, $iyouwethey_connect) or die(mysql_error());
$row_Rec_req_report = mysql_fetch_assoc($Rec_req_report);
$totalRows_Rec_req_report = mysql_num_rows($Rec_req_report);

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_rec_req = "SELECT requisition_ingredient.ReqID, requisition_ingredient.Date, ingredient.IngtName, user.username, requisition_ingredient.ReqAmount FROM requisition_ingredient, ingredient, `user` WHERE requisition_ingredient.IngID = ingredient.IngID AND requisition_ingredient.UserID = user.UserID";
$rec_req = mysql_query($query_rec_req, $iyouwethey_connect) or die(mysql_error());
$row_rec_req = mysql_fetch_assoc($rec_req);
$totalRows_rec_req = mysql_num_rows($rec_req);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="100%" border="0" cellspacing="5" cellpadding="10">
  <tr>
    <td align="center"><h3>รายงานการเบิกจ่ายวัตถุดิบ</h3></td>
  </tr>
  <tr>
    <td>ใบเบิกสินค้า</td>
  </tr>
  <tr>
    <td align="center">&nbsp;
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td>รหัสการเบิก</td>
          <td>วันที่เบิก</td>
          <td>ชื่อวัตถุดิบ</td>
          <td>ผู้เบิก</td>
          <td>จำนวนที่เบิก</td>
          <td>&nbsp;</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_Rec_req_report['ReqID']; ?></td>
            <td><?php echo $row_Rec_req_report['Date']; ?></td>
            <td><?php echo $row_Rec_req_report['IngID']; ?></td>
            <td><?php echo $row_Rec_req_report['UserID']; ?></td>
            <td><?php echo $row_Rec_req_report['ReqAmount']; ?></td>
            <td><a href="req_detail_report.php?iid=<?php echo $row_Rec_req_report['ReqID']; ?>">รายละเอียด</a></td>
          </tr>
          <?php } while ($row_Rec_req_report = mysql_fetch_assoc($Rec_req_report)); ?>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_req_report);

mysql_free_result($rec_req);
?>
