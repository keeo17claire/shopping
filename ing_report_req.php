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

$maxRows_Rec_int = 10;
$pageNum_Rec_int = 0;
if (isset($_GET['pageNum_Rec_int'])) {
  $pageNum_Rec_int = $_GET['pageNum_Rec_int'];
}
$startRow_Rec_int = $pageNum_Rec_int * $maxRows_Rec_int;

$colname_Rec_int = "-1";
if (isset($_GET['txtsearch'])) {
  $colname_Rec_int = $_GET['txtsearch'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_int = sprintf("SELECT * FROM ingredient WHERE (IngID = %s) AND (IngtName=%s) ORDER BY IngtName ASC", GetSQLValueString($colname_Rec_int, "int"),GetSQLValueString("%" . $colname_Rec_int . "%", "int"));
$query_limit_Rec_int = sprintf("%s LIMIT %d, %d", $query_Rec_int, $startRow_Rec_int, $maxRows_Rec_int);
$Rec_int = mysql_query($query_limit_Rec_int, $iyouwethey_connect) or die(mysql_error());
$row_Rec_int = mysql_fetch_assoc($Rec_int);

if (isset($_GET['totalRows_Rec_int'])) {
  $totalRows_Rec_int = $_GET['totalRows_Rec_int'];
} else {
  $all_Rec_int = mysql_query($query_Rec_int);
  $totalRows_Rec_int = mysql_num_rows($all_Rec_int);
}
$totalPages_Rec_int = ceil($totalRows_Rec_int/$maxRows_Rec_int)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="67%" border="0" align="center" cellpadding="5" cellspacing="5">
  <tr>
    <td width="100%" colspan="2" align="center"><h3><strong>รายงานจำนวนวัตถุดิบคงเหลือทั้งหมด</strong></h3></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td>ลำดับ</td>
          <td>รหัสวัตถุดิบ</td>
          <td>ชื่อวัตถุดิบ</td>
          <td>จำนวนคงเหลือ</td>
          <td>PurchasePoint</td>
          <td>Unit</td>
          <td>การเบิก</td>
          <td>การเติม</td>
        </tr>
        <?php do { ?>
          <tr>
            <td>&nbsp;<?php echo ($startRow_Rec_int + 1) ?></td>
            <td><?php echo $row_Rec_int['IngID']; ?></td>
            <td><?php echo $row_Rec_int['IngtName']; ?></td>
            <td><?php echo $row_Rec_int['Amount']; ?></td>
            <td><?php echo $row_Rec_int['PurchasePoint']; ?></td>
            <td><?php echo $row_Rec_int['Unit']; ?></td>
            <td><a href="req_detail.php?iid=<?php echo $row_Rec_int['IngID']; ?>">รายละเอียด</a></td>
            <td><a href="add_detail.php?iid=<?php echo $row_Rec_int['IngID']; ?>">รายละเอียด</a></td>
          </tr>
          <?php } while ($row_Rec_int = mysql_fetch_assoc($Rec_int)); ?>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_int);
?>
