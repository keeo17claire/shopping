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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center">เติมวัตถุดิบ</td>
  </tr>
  <tr>
    <td align="center">&nbsp;
      <form id="form1" name="form1" method="post" action="ing_add.php">
        <label for="select"></label>
        <select name="select" id="select">
          <?php
do {  
?>
          <option value="<?php echo $row_Rec_ing['IngtName']?>"><?php echo $row_Rec_ing['IngtName']?></option>
          <?php
} while ($row_Rec_ing = mysql_fetch_assoc($Rec_ing));
  $rows = mysql_num_rows($Rec_ing);
  if($rows > 0) {
      mysql_data_seek($Rec_ing, 0);
	  $row_Rec_ing = mysql_fetch_assoc($Rec_ing);
  }
?>
        </select>
        <label for="select2"></label>
        <select name="select2" id="select2">
          <?php
do {  
?>
          <option value="<?php echo $row_Rec_ing['Unit']?>"><?php echo $row_Rec_ing['Unit']?></option>
          <?php
} while ($row_Rec_ing = mysql_fetch_assoc($Rec_ing));
  $rows = mysql_num_rows($Rec_ing);
  if($rows > 0) {
      mysql_data_seek($Rec_ing, 0);
	  $row_Rec_ing = mysql_fetch_assoc($Rec_ing);
  }
?>
        </select>
      </form>
    <p>&nbsp;</p><p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_ing);
?>
