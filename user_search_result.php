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

$colname_Rec_edit_user = "-1";
if (isset($_POST['txtsearch'])) {
  $colname_Rec_edit_user = $_POST['txtsearch'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_edit_user = sprintf("SELECT * FROM `user` WHERE (UserID = %s) OR (FirstName LIKE %s) ORDER BY FirstName ASC", GetSQLValueString($colname_Rec_edit_user, "int"),GetSQLValueString("%" . $colname_Rec_edit_user . "%", "int"));
$Rec_edit_user = mysql_query($query_Rec_edit_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_edit_user = mysql_fetch_assoc($Rec_edit_user);
$totalRows_Rec_edit_user = mysql_num_rows($Rec_edit_user);

$colname_Rec_user1 = "-1";
if (isset($_POST['UserID'])) {
  $colname_Rec_user1 = $_POST['UserID'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_user1 = sprintf("SELECT * FROM `user` WHERE UserID = %s OR Firstname LIKE %s", GetSQLValueString($colname_Rec_user1, "int"),GetSQLValueString("%" . $colname_Rec_user1 . "%", "int"));
$Rec_user1 = mysql_query($query_Rec_user1, $iyouwethey_connect) or die(mysql_error());
$row_Rec_user1 = mysql_fetch_assoc($Rec_user1);
?>
<table width="100%" border="0" cellspacing="5" cellpadding="10">
  <tr>
    <td align="center" valign="middle"><h3>ผลการค้นหาข้อมูลผู้ใช้ พบจำนวน <?php echo $totalRows_Rec_user ?> รายการ</h3></td>
  </tr>
  <tr>
    <td>&nbsp;
      <table border="0" align="center" cellpadding="3" cellspacing="3">
        <tr>
        <td width="195" align="center"><strong>ลำดับ</strong></td>
          <td width="105">UserID</td>
          <td width="88">username</td>
          <td width="88">Password</td>
          <td width="108">FirstName</td>
          <td width="113">LastName</td>
          <td width="164">role</td>
        </tr>
        <?php do { ?>
        <tr>
        <td align="center"><?php echo ($startRow_Rec_int + 1) ?>.</td>
          <td><?php echo $row_Rec_edit_user['UserID']; ?></td>
          <td><?php echo $row_Rec_edit_user['username']; ?></td>
          <td><?php echo $row_Rec_edit_user['Password']; ?></td>
          <td><?php echo $row_Rec_edit_user['FirstName']; ?></td>
          <td><?php echo $row_Rec_edit_user['LastName']; ?></td>
          <td><?php echo $row_Rec_edit_user['role']; ?></td>
        </tr>
        <?php $startRow_Rec_int ++;} while ($row_Rec_edit_user = mysql_fetch_assoc($Rec_edit_user)); ?>
    </table></td>
  </tr>
</table>
<?php
mysql_free_result($Rec_edit_user);
?>
