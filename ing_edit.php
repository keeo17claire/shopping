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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ingredient SET Ing_TypeID=%s, IngtName=%s, Price=%s, Amount=%s, Unit=%s, PurchasePoint=%s WHERE IngID=%s",
                       GetSQLValueString($_POST['Ing_TypeID'], "int"),
                       GetSQLValueString($_POST['IngtName'], "text"),
                       GetSQLValueString($_POST['Price'], "double"),
                       GetSQLValueString($_POST['Amount'], "int"),
                       GetSQLValueString($_POST['Unit'], "text"),
                       GetSQLValueString($_POST['PurchasePoint'], "int"),
                       GetSQLValueString($_POST['IngID'], "int"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($updateSQL, $iyouwethey_connect) or die(mysql_error());

  $updateGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ingredient SET Ing_TypeID=%s, IngtName=%s, Price=%s, Amount=%s, Unit=%s, PurchasePoint=%s WHERE IngID=%s",
                       GetSQLValueString($_POST['Ing_TypeID'], "int"),
                       GetSQLValueString($_POST['IngtName'], "text"),
                       GetSQLValueString($_POST['Price'], "double"),
                       GetSQLValueString($_POST['Amount'], "int"),
                       GetSQLValueString($_POST['Unit'], "text"),
                       GetSQLValueString($_POST['PurchasePoint'], "int"),
                       GetSQLValueString($_POST['IngID'], "int"));

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($updateSQL, $iyouwethey_connect) or die(mysql_error());
}

$colname_Rec_ing = "-1";
if (isset($_GET['iid'])) {
  $colname_Rec_ing = $_GET['iid'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_ing = sprintf("SELECT * FROM ingredient WHERE IngID = %s", GetSQLValueString($colname_Rec_ing, "int"));
$Rec_ing = mysql_query($query_Rec_ing, $iyouwethey_connect) or die(mysql_error());
$row_Rec_ing = mysql_fetch_assoc($Rec_ing);
$totalRows_Rec_ing = mysql_num_rows($Rec_ing);

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_ingtype = "SELECT * FROM ingredient_type";
$Rec_ingtype = mysql_query($query_Rec_ingtype, $iyouwethey_connect) or die(mysql_error());
$row_Rec_ingtype = mysql_fetch_assoc($Rec_ingtype);
$totalRows_Rec_ingtype = mysql_num_rows($Rec_ingtype);
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
    <td align="center"><strong>แก้ไขข้อมูลวัตถุดิบ</strong></td>
  </tr>
  <tr>
    <td align="center"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1"> 
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">IngID:</td>
            <td><?php echo $row_Rec_ing['IngID']; ?></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ประเภทวัตถุดิบ</td>
            <td><select name="Ing_TypeID">
              <?php
do {  
?>
              <option value="<?php echo $row_Rec_ingtype['Ing_TypeID']?>"<?php if (!(strcmp($row_Rec_ingtype['Ing_TypeID'], htmlentities($row_Rec_ing['Ing_TypeID'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_Rec_ingtype['Ing_TypeName']?></option>
              <?php
} while ($row_Rec_ingtype = mysql_fetch_assoc($Rec_ingtype));
  $rows = mysql_num_rows($Rec_ingtype);
  if($rows > 0) {
      mysql_data_seek($Rec_ingtype, 0);
	  $row_Rec_ingtype = mysql_fetch_assoc($Rec_ingtype);
  }
?>
            </select></td>
          </tr>
          <tr> </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ชื่อวัตถุดิบ</td>
            <td><input type="text" name="IngtName" value="<?php echo htmlentities($row_Rec_ing['IngtName'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">ราคา</td>
            <td><input type="text" name="Price" value="<?php echo htmlentities($row_Rec_ing['Price'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">จำนวนคงเหลือ</td>
            <td><input type="text" name="Amount" value="<?php echo htmlentities($row_Rec_ing['Amount'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">หน่วย</td>
            <td><input type="text" name="Unit" value="<?php echo htmlentities($row_Rec_ing['Unit'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">จุดสั่งซื้อ</td>
            <td><input type="text" name="PurchasePoint" value="<?php echo htmlentities($row_Rec_ing['PurchasePoint'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" value="บันทึก" />
            <input type="reset" name="Reset" id="button" value="ยกเลิก" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="IngID" value="<?php echo $row_Rec_ing['IngID']; ?>" />
      </form>
    <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($Rec_ing);

mysql_free_result($Rec_ingtype);
?>
