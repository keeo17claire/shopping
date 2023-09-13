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

$maxRows_Rec_user = 10;
$pageNum_Rec_user = 0;
if (isset($_GET['pageNum_Rec_user'])) {
  $pageNum_Rec_user = $_GET['pageNum_Rec_user'];
}
$startRow_Rec_user = $pageNum_Rec_user * $maxRows_Rec_user;

$colname_Rec_user = "-1";
if (isset($_POST['txtsearch'])) {
  $colname_Rec_user = $_POST['txtsearch'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_user = sprintf("SELECT * FROM `user` WHERE FirstName LIKE %s OR UserID=%s ORDER BY FirstName ASC", GetSQLValueString("%" . $colname_Rec_user . "%", "text"),GetSQLValueString($colname_Rec_user, "text"));
$query_limit_Rec_user = sprintf("%s LIMIT %d, %d", $query_Rec_user, $startRow_Rec_user, $maxRows_Rec_user);
$Rec_user = mysql_query($query_limit_Rec_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_user = mysql_fetch_assoc($Rec_user);

if (isset($_GET['totalRows_Rec_user'])) {
  $totalRows_Rec_user = $_GET['totalRows_Rec_user'];
} else {
  $all_Rec_user = mysql_query($query_Rec_user);
  $totalRows_Rec_user = mysql_num_rows($all_Rec_user);
}
$totalPages_Rec_user = ceil($totalRows_Rec_user/$maxRows_Rec_user)-1;
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
</head>

<body>
<table width="100%" height="100" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="135" align="left" bgcolor="#FFFFFF"><img src="image/logo2.png" alt="รูป1" width="101" height="101"
          align="absmiddle" /></td>
      <td width="1156" height="100" align="left" valign="top" bgcolor="#FFFFFF">
        <h6><span class="TH"> <span class="head_color"> <span class="headddd">ระบบคลังสินค้า <br>
              </span></span></span><span class="TH"><span class="head_color"><span class="headddd">I YOU WE
                THEY</span></span></span></h6>
      </td>
      <td width="578" align="right" valign="middle" bgcolor="#FFFFFF" class="head_color">
        <p>&nbsp;&nbsp;</p>
        <p><img src="image/user (2).png" alt="รูป2" width="30" height="30" align="absbottom" /><?php echo $row_Rec_user['role']; ?>:
          <?php echo $row_Rec_user['FirstName']; ?>
          <?php echo $row_Rec_user['LastName']; ?><a href="<?php echo $logoutAction ?>"><img src="image/logout.png"
              width="30" height="30" align="absbottom"></a>
        </p>
      </td>
    </tr>
  </table>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="5" cellpadding="10">
  <tr>
    <td align="center" valign="middle"><h3>ผลการค้นหาข้อมูลผู้ใช้ พบจำนวน <?php echo $totalRows_Rec_user ?> รายการ</h3></td>
  </tr>
  <tr>
    <td>&nbsp;
      <table width="838" border="0" align="center" cellpadding="3" cellspacing="3">
        <tr>
          <td width="152" align="center">ลำดับ</td>
          <td width="232" align="center">ชื่อ</td>
          <td width="192" align="center">นามสกุล</td>
          <td width="113" align="center">สถานะ</td>
          <td width="101">&nbsp;</td>
        </tr>
        <?php do { ?>
          <tr>
            <td align="center">&nbsp;<?php echo ($startRow_Rec_user + 1) ?></td>
            <td align="center"><?php echo $row_Rec_user['FirstName']; ?></td>
            <td align="center"><?php echo $row_Rec_user['LastName']; ?></td>
            <td align="center"><?php echo $row_Rec_user['role']; ?></td>
            <td><a href="user_edit.php?iid=<?php echo $row_Rec_user['UserID']; ?>"><img src="image/edit-text.png" width="51" height="51" /></a><a href="ing_delete.php?iid=<?php echo $row_Rec_user['UserID']; ?>"><img src="image/delete.png" width="50" height="50" /></a></td>
          </tr>
          <?php $startRow_Rec_user++;} while ($row_Rec_user = mysql_fetch_assoc($Rec_user)); ?>
    </table></td>
  </tr>
</table>
     <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_user);
?>
