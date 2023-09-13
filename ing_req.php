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
  $insertSQL = sprintf("INSERT INTO added_ingredient (UserID, AddCode, IngID, `Date`, NumAdded, OriAmount) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserID'], "int"),
                       GetSQLValueString($_POST['AddCode'], "int"),
                       GetSQLValueString($_POST['IngID'], "int"),
                       GetSQLValueString($_POST['Date'], "date"),
                       GetSQLValueString($_POST['NumAdded'], "int"),
                       GetSQLValueString($_POST['OriAmount'], "int"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($insertSQL, $iyouwethey_connect) or die(mysql_error());

  $insertGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO added_ingredient (UserID, AddCode, IngID, `Date`, NumAdded, OriAmount) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserID'], "int"),
                       GetSQLValueString($_POST['AddCode'], "int"),
                       GetSQLValueString($_POST['IngID'], "int"),
                       GetSQLValueString($_POST['Date'], "date"),
                       GetSQLValueString($_POST['NumAdded'], "int"),
                       GetSQLValueString($_POST['OriAmount'], "int"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($insertSQL, $iyouwethey_connect) or die(mysql_error());

  $insertGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO added_ingredient (UserID, AddCode, IngID, `Date`, NumAdded, OriAmount) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserID'], "int"),
                       GetSQLValueString($_POST['AddCode'], "int"),
                       GetSQLValueString($_POST['IngID'], "int"),
                       GetSQLValueString($_POST['Date'], "date"),
                       GetSQLValueString($_POST['NumAdded'], "int"),
                       GetSQLValueString($_POST['OriAmount'], "int"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($insertSQL, $iyouwethey_connect) or die(mysql_error());

  $insertGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO added_ingredient (UserID, AddCode, IngID, `Date`, NumAdded, OriAmount) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserID'], "int"),
                       GetSQLValueString($_POST['AddCode'], "int"),
                       GetSQLValueString($_POST['IngID'], "int"),
                       GetSQLValueString($_POST['Date'], "date"),
                       GetSQLValueString($_POST['NumAdded'], "int"),
                       GetSQLValueString($_POST['OriAmount'], "int"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($insertSQL, $iyouwethey_connect) or die(mysql_error());

  $insertGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_ing = "SELECT * FROM ingredient";
$Rec_ing = mysql_query($query_Rec_ing, $iyouwethey_connect) or die(mysql_error());
$row_Rec_ing = mysql_fetch_assoc($Rec_ing);
$totalRows_Rec_ing = mysql_num_rows($Rec_ing);

$colname_Rec_add = "-1";
if (isset($_POST['from1'])) {
  $colname_Rec_add = $_POST['from1'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_add = sprintf("SELECT * FROM added_ingredient WHERE IngID = %s", GetSQLValueString($colname_Rec_add, "int"));
$Rec_add = mysql_query($query_Rec_add, $iyouwethey_connect) or die(mysql_error());
$row_Rec_add = mysql_fetch_assoc($Rec_add);
$totalRows_Rec_add = mysql_num_rows($Rec_add);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
.heading {	font-family: Kanit;
}
.heading {	font-size: 24px;
}
</style>
</head>

<body>
<table width="100%" height="100" border="0" align="center" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="100" height="100" align="left" bgcolor="#FFFFFF"><h6><img src="image/logo2.png" alt="รูป1" width="143" height="143" align="absmiddle" /> <span class="heading"> <span class="head_color"> ระบบคลังสินค้า I YOU WE THEY</span></span></h6></td>
    <td width="47%" height="57" align="right" valign="middle" bgcolor="#FFFFFF" class="head_color"><span class="head_color"> <img src="image/user (2).png" alt="รูป2" width="30" height="30" align="absmiddle" /><?php echo $row_Rec_user['Status']; ?>:<?php echo $row_Rec_user['FirstName']; ?> <?php echo $row_Rec_user['LastName']; ?> <a href="<?php echo $logoutAction ?>"><img src="image/logout.png" alt="รูปป" width="30" height="30" align="absmiddle" /> Logout</a><a href="<?php echo $logoutAction ?>"></a></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center">เบิกจ่ายวัตถุดิบ</td>
  </tr>
  <tr>
    <td><p>&nbsp;</p>
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">จำนวนที่เพิ่ม:</td>
            <td><input type="text" name="NumAdded" value="" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="บันทึก" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1" />
      </form>
      <p>&nbsp;</p>
<p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_ing);

mysql_free_result($Rec_add);
?>