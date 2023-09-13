<?php date_default_timezone_set("Asia/Bangkok"); ?>
<?php require_once('Connections/iyouwethey_connect.php'); ?>
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

$maxRows_Rec_req_report = 10;
$pageNum_Rec_req_report = 0;
if (isset($_GET['pageNum_Rec_req_report'])) {
  $pageNum_Rec_req_report = $_GET['pageNum_Rec_req_report'];
}
$startRow_Rec_req_report = $pageNum_Rec_req_report * $maxRows_Rec_req_report;

$colname_Rec_req_report = "-1";
if (isset($_POST['iid'])) {
  $colname_Rec_req_report = $_POST['iid'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_req_report = sprintf("SELECT requisition_ingredient.ReqID, requisition_ingredient.Date, ingredient.IngtName, user.username, requisition_ingredient.ReqAmount, ingredient.Amount, ingredient.Unit FROM requisition_ingredient, ingredient, `user` WHERE (ReqID = %s) AND (requisition_ingredient.IngID = ingredient.IngID) AND (requisition_ingredient.UserID = user.UserID)", GetSQLValueString($colname_Rec_req_report, "int"));
$query_limit_Rec_req_report = sprintf("%s LIMIT %d, %d", $query_Rec_req_report, $startRow_Rec_req_report, $maxRows_Rec_req_report);
$Rec_req_report = mysql_query($query_limit_Rec_req_report, $iyouwethey_connect) or die(mysql_error());
$row_Rec_req_report = mysql_fetch_assoc($Rec_req_report);

if (isset($_GET['totalRows_Rec_req_report'])) {
  $totalRows_Rec_req_report = $_GET['totalRows_Rec_req_report'];
} else {
  $all_Rec_req_report = mysql_query($query_Rec_req_report);
  $totalRows_Rec_req_report = mysql_num_rows($all_Rec_req_report);
}
$totalPages_Rec_req_report = ceil($totalRows_Rec_req_report/$maxRows_Rec_req_report)-1;
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
    <td><p>ใบเบิกสินค้า
    </p>
      <table width="100%" border="0" cellspacing="5" cellpadding="10">
        <tr>
        <td width="13%">รหัสใบเบิก: </td>
        <td width="87%"><?php echo $row_Rec_req_report['ReqID']; ?></td>
      </tr>
      <tr>
        <td>วันที่เบิก: </td>
        <td><?php echo $row_Rec_req_report['Date']; ?></td>
      </tr>
      <tr>
        <td>ผู้เบิก:</td>
        <td><?php echo $row_Rec_req_report['IngtName']; ?></td>
      </tr>
</table></td>
  </tr>
  <tr>
    <td align="left"><p>รายการเบิก
      </p>
      <table border="0" cellpadding="3" cellspacing="3" align="center">
        <tr>
          <td>ลำดับ</td>
          <td>ชื่อวัตถุดิบ</td>
          <td>จำนวนที่เบิก</td>
          <td>จำนวนคงเหลือ</td>
          <td>หน่วย</td>
        </tr>
        <?php do { ?>
          <tr>
            <td>&nbsp;<?php echo ($startRow_Rec_req_report + 1) ?>.</td>
            <td><?php echo $row_Rec_req_report['IngID']; ?></td>
            <td><?php echo $row_Rec_req_report['ReqAmount']; ?></td>
            <td><?php echo $row_Rec_req_report['Amount']; ?></td>
            <td><?php echo $row_Rec_req_report['Unit']; ?></td>
          </tr>
          <?php } while ($row_Rec_req_report = mysql_fetch_assoc($Rec_req_report)); ?>
    </table>
    
  </tr>
  <tr>
    <td align="right">วันที่พิมพ์ <?php echo date ("d/m/Y H:i:s", strtotime("now"))?>
    <input name="print" type="submit" id="print" value="Print" onclick="window.print()"/> 
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_req_report);


?>
