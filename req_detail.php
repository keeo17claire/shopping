<?php date_default_timezone_set("Asia/Bangkok"); ?>
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

$maxRows_Rec_req = 10;
$pageNum_Rec_req = 0;
if (isset($_GET['pageNum_Rec_req'])) {
  $pageNum_Rec_req = $_GET['pageNum_Rec_req'];
}
$startRow_Rec_req = $pageNum_Rec_req * $maxRows_Rec_req;

$colname_Rec_req = "-1";
if (isset($_GET['iid'])) {
  $colname_Rec_req = $_GET['iid'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_req = sprintf("SELECT * FROM requisition_ingredient WHERE IngID = %s", GetSQLValueString($colname_Rec_req, "int"));
$query_limit_Rec_req = sprintf("%s LIMIT %d, %d", $query_Rec_req, $startRow_Rec_req, $maxRows_Rec_req);
$Rec_req = mysql_query($query_limit_Rec_req, $iyouwethey_connect) or die(mysql_error());
$row_Rec_req = mysql_fetch_assoc($Rec_req);

if (isset($_GET['totalRows_Rec_req'])) {
  $totalRows_Rec_req = $_GET['totalRows_Rec_req'];
} else {
  $all_Rec_req = mysql_query($query_Rec_req);
  $totalRows_Rec_req = mysql_num_rows($all_Rec_req);
}
$totalPages_Rec_req = ceil($totalRows_Rec_req/$maxRows_Rec_req)-1;

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_req_detail = "SELECT requisition_ingredient.ReqID, requisition_ingredient.Date, ingredient.IngtName, user.username, requisition_ingredient.ReqAmount, ingredient.Unit FROM requisition_ingredient, ingredient, `user` WHERE requisition_ingredient.IngID = ingredient.IngID AND requisition_ingredient.UserID = user.UserID";
$Rec_req_detail = mysql_query($query_Rec_req_detail, $iyouwethey_connect) or die(mysql_error());
$row_Rec_req_detail = mysql_fetch_assoc($Rec_req_detail);
$totalRows_Rec_req_detail = mysql_num_rows($Rec_req_detail);
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
    <td align="center"><h3>รายงานรายละเอียดการเบิก <?php echo $row_Rec_req_detail['IngtName']; ?></h3></td>
  </tr>
  <tr>
    <td align="right">รายงานผล ณ วันที่ <?php echo date ("d/m/Y H:i:s", strtotime("now"))?></td>
  </tr>
  <tr>
    <td align="right"><table border="0" align="center" cellpadding="3" cellspacing="3">
        <tr>
          <td>ลำดับ</td>
          <td>วันที่</td>
          <td>ผู้เบิก</td>
          <td>จำนวนคงเหลือ</td>
          <td>หน่วย</td>
        </tr>
        <?php do { ?>
          <tr>
            <td><?php echo $row_Rec_req['UserID']; ?></td>
            <td><?php echo $row_Rec_req_detail['Date']; ?></td>
            <td><?php echo $row_Rec_req_detail['username']; ?></td>
            <td><?php echo $row_Rec_req_detail['ReqAmount']; ?></td>
            <td><?php echo $row_Rec_req_detail['Unit']; ?></td>
          </tr>
          <?php } while ($row_Rec_req = mysql_fetch_assoc($Rec_req)); ?>
    </table>
    <p>&nbsp;</p></td>
    
  </tr>
  <tr>
    <td align="right"><input name="print" type="submit" id="print" value="Print" onclick="window.print()"/></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_req);

mysql_free_result($Rec_req_detail);
?>
