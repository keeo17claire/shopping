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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO `user` (UserID, Password, UserName, Status, FirstName, LastName) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserID'], "int"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['UserName'], "text"),
                       GetSQLValueString($_POST['Status'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($insertSQL, $iyouwethey_connect) or die(mysql_error());

  $insertGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO `user` (UserID, username, Password, Status, FirstName, LastName, `role`) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserID'], "int"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['Status'], "text"),
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['role'], "text"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($insertSQL, $iyouwethey_connect) or die(mysql_error());

  $insertGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_Rec_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Rec_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_user = sprintf("SELECT Status, FirstName, LastName FROM `user` WHERE username = %s", GetSQLValueString($colname_Rec_user, "text"));
$Rec_user = mysql_query($query_Rec_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_user = mysql_fetch_assoc($Rec_user);
$totalRows_Rec_user = mysql_num_rows($Rec_user);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
  text-align:center;
}

.active, .accordion:hover {
  background-color: #ccc;
}

.accordion:after {
  content: '\002B';
  color: #777;
  font-weight: bold;
  float: right;
  margin-left: 5px;
  
}

.active:after {
  content: "\2212";
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  
}
.TH {
	font-family: "TH Sarabun New";
	font-size: 24px;
}

.head_color {
	color: #28324D;
	font-family: Kanit;
	font-size: 24px;
}
.head_color {
	font-size: 16px;
}
.headddd {
	font-size: 24px;
	font-family: Kanit;
	color: #28324D;
}
</style>
</head>

<body>
<table width="100%" height="100" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
      <td width="135" align="left" bgcolor="#FFFFFF"><img src="image/logo2.png" alt="รูป1" width="101" height="101" align="absmiddle" /></td> 
      <td width="1156" height="100" align="left" valign="top" bgcolor="#FFFFFF"><h6><span class="TH"> <span class="head_color"> <span class="headddd">ระบบคลังสินค้า <br>
      </span></span></span><span class="TH"><span class="head_color"><span class="headddd">I YOU WE THEY</span></span></span></h6></td>
      <td width="578" align="right" valign="middle" bgcolor="#FFFFFF" class="head_color"><p>&nbsp;&nbsp;</p>
        <p><img src="image/user (2).png" alt="รูป2" width="30" height="30" align="absbottom" /><?php echo $row_Rec_user['Status']; ?>: <?php echo $row_Rec_user['FirstName']; ?><?php echo $row_Rec_user['LastName']; ?><a href="<?php echo $logoutAction ?>"><img src="image/logout.png" width="30" height="30" align="absbottom"></a></p></td> 
  </tr>
</table></td>
    </tr>
  </table>
<table width="70%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center">เพิ่มผู้ใช้งาน</td>
  </tr>
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
      <table align="center">
        <tr valign="baseline">
          <td width="96" align="right" nowrap="nowrap">Username:</td>
          <td width="269"><input type="text" name="username" value="" size="32" />
            *</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">Password:</td>
          <td><input type="text" name="Password" value="" size="32" />
            *</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">ชื่อจริง:</td>
          <td><input type="text" name="FirstName" value="" size="32" />
            *</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">นามสกุล:</td>
          <td><input type="text" name="LastName" value="" size="32" />
            *</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">สถานะ:</td>
          <td><input type="text" name="role" value="" size="32" /> 
            user / owner</td>
        </tr>
        <tr valign="baseline">
          <td nowrap="nowrap" align="right">&nbsp;</td>
          <td><input type="submit" value="บันทึก" />
            <input type="reset" name="Reset" id="button" value="ยกเลิก" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form2" />
  </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php

mysql_free_result($Rec_user);
?>