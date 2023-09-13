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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE `user` SET Password=%s, UserName=%s, Status=%s, FirstName=%s, LastName=%s WHERE UserID=%s",
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['UserName'], "text"),
                       GetSQLValueString($_POST['Status'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['UserID'], "int"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($updateSQL, $iyouwethey_connect) or die(mysql_error());

  $updateGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Rec_edit_user = "-1";
if (isset($_GET['iid'])) {
  $colname_Rec_edit_user = $_GET['iid'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_edit_user = sprintf("SELECT * FROM `user` WHERE UserID = %s", GetSQLValueString($colname_Rec_edit_user, "int"));
$Rec_edit_user = mysql_query($query_Rec_edit_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_edit_user = mysql_fetch_assoc($Rec_edit_user);
$totalRows_Rec_edit_user = mysql_num_rows($Rec_edit_user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center">แก้ไขข้อมูลผู้ใช้งาน</td>
  </tr>
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center">
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">ชื่อผู้ใช้:</td>
          <td><input type="text" name="UserName" value="<?php echo htmlentities($row_Rec_edit_user['UserName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">รหัสผ่าน:</td>
          <td><input type="text" name="Password2" value="<?php echo htmlentities($row_Rec_edit_user['Password'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">ชื่อ:</td>
          <td><input type="text" name="FirstName" value="<?php echo htmlentities($row_Rec_edit_user['FirstName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">นามสกุลล:</td>
          <td><input type="text" name="LastName" value="<?php echo htmlentities($row_Rec_edit_user['LastName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
        <tr valign="baseline">
          <td height="26" align="right" nowrap="nowrap">&nbsp;</td>
          <td><input type="submit" value="บันทึก" />
            <input name="Reset" type="reset" value="ยกเลิก" /></td>
          </tr>
        </table>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="UserID" value="<?php echo $row_Rec_edit_user['UserID']; ?>" />
    </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_edit_user);
?>
