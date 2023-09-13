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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO ingredient (IngID, Ing_TypeName, IngtName, Price, Amount, Unit, PurchasePoint) VALUES (%s, %s, %s, %s, %s, %s, %s)",
  GetSQLValueString($_POST['IngID'], "int"),
  GetSQLValueString($_POST['Ing_TypeName'], "text"),
  GetSQLValueString($_POST['IngtName'], "text"),
  GetSQLValueString($_POST['Price'], "double"),
  GetSQLValueString($_POST['Amount'], "int"),
  GetSQLValueString($_POST['Unit'], "text"),
  GetSQLValueString($_POST['PurchasePoint'], "int")
);

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
 $insertSQL = sprintf("INSERT INTO ingredient (IngID, Ing_TypeName, IngtName, Price, Amount, Unit, PurchasePoint) VALUES (%s, %s, %s, %s, %s, %s, %s)",
  GetSQLValueString($_POST['IngID'], "int"),
  GetSQLValueString($_POST['Ing_TypeName'], "text"),
  GetSQLValueString($_POST['IngtName'], "text"),
  GetSQLValueString($_POST['Price'], "double"),
  GetSQLValueString($_POST['Amount'], "int"),
  GetSQLValueString($_POST['Unit'], "text"),
  GetSQLValueString($_POST['PurchasePoint'], "int")
);

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
  $insertSQL = sprintf("INSERT INTO ingredient (IngID, Ing_TypeID, IngtName, Price, Amount, Unit, PurchasePoint) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['IngID'], "int"),
                       GetSQLValueString($_POST['Ing_TypeID'], "int"),
                       GetSQLValueString($_POST['IngtName'], "text"),
                       GetSQLValueString($_POST['Price'], "double"),
                       GetSQLValueString($_POST['Amount'], "int"),
                       GetSQLValueString($_POST['Unit'], "text"),
                       GetSQLValueString($_POST['PurchasePoint'], "int"));

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
$query_Rec_ingtn = "SELECT Ing_TypeID, Ing_TypeName FROM ingredient_type";
$Rec_ingtn = mysql_query($query_Rec_ingtn, $iyouwethey_connect) or die(mysql_error());
$row_Rec_ingtn = mysql_fetch_assoc($Rec_ingtn);
$totalRows_Rec_ingtn = mysql_num_rows($Rec_ingtn);

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
        <p><img src="image/user (2).png" alt="รูป2" width="30" height="30" align="absbottom" /><?php echo $row_Rec_user['Status']; ?>:<?php echo $row_Rec_user['FirstName']; ?> <?php echo $row_Rec_user['LastName']; ?>&nbsp; <a href="<?php echo $logoutAction ?>"><img src="image/logout.png" width="30" height="30" align="absbottom"></a></p></td> 
  </tr>
</table></td>
    </tr>
  </table>
<p class="headddd" align="center">เพิ่มวัตถุดิบใหม่</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">ประเภทวัตถุดิบ:</td>
      <td><select name="Ing_TypeID">
        <?php 
do {  
?>
        <option value="<?php echo $row_Rec_ingtn['Ing_TypeID']?>" ><?php echo $row_Rec_ingtn['Ing_TypeName']?></option>
        <?php
} while ($row_Rec_ingtn = mysql_fetch_assoc($Rec_ingtn));
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">ชื่อวัตถุดิบ:</td>
      <td><input type="text" name="IngtName" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">ราคา (บาท):</td>
      <td><input type="text" name="Price" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">จำนวนคงเหลือ:</td>
      <td><input type="text" name="Amount" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">หน่วย:</td>
      <td><input type="text" name="Unit" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">จุดสั่งซื้อ:</td>
      <td><input type="text" name="PurchasePoint" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="บันทึก" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form2" />
</form>
<p>&nbsp;</p>

</body>
</html>
<?php
mysql_free_result($Rec_ingtn);

mysql_free_result($Rec_user);
?>
