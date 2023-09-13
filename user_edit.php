<?php require_once('Connections/iyouwethey_connect.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
  $updateSQL = sprintf("UPDATE `user` SET username=%s, Password=%s, FirstName=%s, LastName=%s, `role`=%s WHERE UserID=%s",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['role'], "text"),
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
<title>ระบบคลังสินค้า I YOU WE THEY</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@500&family=Prompt&display=swap" rel="stylesheet">
  <style type="text/css">
		body{
    font-family: 'Kanit', sans-serif;
  }
	.TH .head_color .headddd {
	color: #28324D;
}
  .head_color p {
	color: #28324D;
}
  .TH .head_color .headddd {
	font-size: 24px;
}
  .TH .head_color .headddd {
	font-size: 18px;
}
  .TH .head_color .headddd {
	font-size: 24px;
}
  </style>



<body>
<table width="100%" height="100" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="135" align="left" bgcolor="#FFFFFF"><img src="image/logo2.png" alt="รูป1" width="101" height="101" align="absmiddle" /></td> 
      <td width="1156" height="100" align="left" valign="top" bgcolor="#FFFFFF"><h6><span class="TH"> <span class="head_color"> <span class="headddd">ระบบคลังสินค้า <br>
      </span></span></span><span class="TH"><span class="head_color"><span class="headddd">I YOU WE THEY</span></span></span></h6></td>
      <td width="578" align="right" valign="middle" bgcolor="#FFFFFF" class="head_color"><p>&nbsp;&nbsp;</p>
        <p><img src="image/user (2).png" alt="รูป2" width="30" height="30" align="absbottom" /><?php echo $row_Rec_user['role']; ?>: <?php echo $row_Rec_user['FirstName']; ?><?php echo $row_Rec_user['LastName']; ?><a href="<?php echo $logoutAction ?>"><img src="image/logout.png" width="30" height="30" align="absbottom"></a></p></td> 
  </tr>
</table></td>
    </tr>
  </table>
<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center" class="headddd"><strong>แก้ไขข้อมูลผู้ใช้งาน</strong></td>
  </tr>
  <tr>
    <td>&nbsp;
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center">
          <tr valign="baseline">
            <td width="96" align="right" nowrap="nowrap">Username:</td>
            <td width="419"><input type="text" name="username" value="<?php echo htmlentities($row_Rec_edit_user['username'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              *</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">Password:</td>
            <td><input type="text" name="Password" value="<?php echo htmlentities($row_Rec_edit_user['Password'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
            *<br /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ชื่อจริง:</td>
            <td><input type="text" name="FirstName" value="<?php echo htmlentities($row_Rec_edit_user['FirstName'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              *</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">นามสกุล:</td>
            <td><input type="text" name="LastName" value="<?php echo htmlentities($row_Rec_edit_user['LastName'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              *</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">สถานะ:</td>
            <td><input type="text" name="role" value="<?php echo htmlentities($row_Rec_edit_user['role'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              *user / owner เท่านั้น</td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="บันทึก" />
            <input type="reset" name="Reset" id="button" value="ยกเลิก" /></td>
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
