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
?>
<?php
if (isset($_POST['txtusername'])) {
  $loginUsername = $_POST['txtusername'];
  $password = $_POST['txtpw'];
  
  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  
  $LoginRS__query = sprintf("SELECT username, Password, role FROM `user` WHERE username=%s AND Password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
  
  $LoginRS = mysql_query($LoginRS__query, $iyouwethey_connect) or die(mysql_error());
  
  if ($row_LoginRS = mysql_fetch_assoc($LoginRS)) {
    $loginStrGroup = $row_LoginRS['role']; // รับค่าบทบาทจากฐานข้อมูล
    $uid = $row_LoginRS ["UserID"];
	
    session_start();
	$_SESSION['uid'] = $uid;
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;

    if ($loginStrGroup == "owner") {
      $MM_redirectLoginSuccess = "owner_home.php"; // หน้าแอดมิน
    } elseif ($loginStrGroup == "staff") {
      $MM_redirectLoginSuccess = "staff_home.php"; // หน้าผู้ใช้ทั่วไป
    }

    header("Location: " . $MM_redirectLoginSuccess );
  } else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบคลังสินค้าI_YOU_WE_THEY</title>
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <td colspan="2" align="center"><p>ระบบคลังสินค้า</p>
      I YOU WE THEY</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><h3><img src="image/logo2.png" width="206" height="206" align="middle" /></h3></td>
    </tr>
    <tr>
      <td width="42%" align="right"><strong>ชื่อผู้ใช้</strong></td>
      <td width="58%"><label for="txtusername"></label>
      <input type="text" name="txtusername" id="txtusername" /></td>
    </tr>
    <tr>
      <td align="right"><strong>รหัสผ่าน</strong></td>
      <td><label for="txtpw"></label>
      <input type="password" name="txtpw" id="txtpw" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="เข้าสู่ระบบ" />
      <input type="reset" name="button2" id="button2" value="  ยกเลิก  " /></td>
    </tr>
  </table>
</form></body>
</html>