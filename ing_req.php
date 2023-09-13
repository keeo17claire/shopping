<?php session_start();

require_once('Connections/iyouwethey_connect.php');

if (!isset($_SESSION['UserID'])) {
  header("Location: index.php"); // replace "login.php" with the path to your login page
  exit();
} ?>
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

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_user = "SELECT FirstName, LastName, `role` FROM `user` WHERE username = ''";
$Rec_user = mysql_query($query_Rec_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_user = mysql_fetch_assoc($Rec_user);
$totalRows_Rec_user = mysql_num_rows($Rec_user);

$colname_Rec_ing_add = "-1";
if (isset($_GET['iid'])) {
  $colname_Rec_ing_add = $_GET['iid'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_ing_add = sprintf("SELECT * FROM ingredient WHERE IngID = %s", GetSQLValueString($colname_Rec_ing_add, "int"));
$Rec_ing_add = mysql_query($query_Rec_ing_add, $iyouwethey_connect) or die(mysql_error());
$row_Rec_ing_add = mysql_fetch_assoc($Rec_ing_add);
$totalRows_Rec_ing_add = mysql_num_rows($Rec_ing_add);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $newAmount = $_POST['Decrease_Amount'];
  $originalAmount = $row_Rec_ing_add['Amount'];
  $newTotalAmount = $originalAmount - $newAmount;

  // Get the UserID from the session
  $userID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

  // Generate a new AddCode (you will need to replace this with your own logic for generating AddCode)
  $ReqID = rand(10000, 99999);

  // Update the amount in the ingredient table
  $updateSQL = sprintf(
    "UPDATE ingredient SET Amount=%s WHERE IngID=%s",
    GetSQLValueString($newTotalAmount, "int"),
    GetSQLValueString($_POST['IngID'], "int")
  );

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result1 = mysql_query($updateSQL, $iyouwethey_connect) or die(mysql_error());

  $insertSQL = sprintf(
    "INSERT INTO requisition_ingredient (UserID, ReqID, IngID,Date, ReqAmount) VALUES (%s, %s, %s, NOW(), %s)",
    GetSQLValueString($userID, "int"),  // replaced $_POST['UserID'] with $userID
    GetSQLValueString($ReqID, "int"),  // replaced $_POST['AddCode'] with $addCode
    GetSQLValueString($_POST['IngID'], "int"),
    GetSQLValueString($newAmount, "int")
  );

  mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
  $Result2 = mysql_query($insertSQL, $iyouwethey_connect) or die(mysql_error());

  $updateGoTo = "result.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_user = "SELECT FirstName, LastName, `role` FROM `user` WHERE username = ''";
$Rec_user = mysql_query($query_Rec_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_user = mysql_fetch_assoc($Rec_user);
$totalRows_Rec_user = mysql_num_rows($Rec_user);

$colname_Rec_ing_add = "-1";
if (isset($_GET['iid'])) {
  $colname_Rec_ing_add = $_GET['iid'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_ing_add = sprintf("SELECT * FROM ingredient WHERE IngID = %s", GetSQLValueString($colname_Rec_ing_add, "int"));
$Rec_ing_add = mysql_query($query_Rec_ing_add, $iyouwethey_connect) or die(mysql_error());
$row_Rec_ing_add = mysql_fetch_assoc($Rec_ing_add);
$totalRows_Rec_ing_add = mysql_num_rows($Rec_ing_add);

?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
      text-align: center;
    }

    .active,
    .accordion:hover {
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
        <p><img src="image/user (2).png" alt="รูป2" width="30" height="30" align="absbottom" />
          <?php echo $row_Rec_user['Status']; ?>:
          <?php echo $row_Rec_user['FirstName']; ?>
          <?php echo $row_Rec_user['LastName']; ?> <a href="<?php echo $logoutAction ?>"><img src="image/logout.png"
              width="30" height="30" align="absbottom"></a>
        </p>
      </td>
    </tr>
  </table>
  </td>
  </tr>
  </table>
  <p>&nbsp;</p>
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">IngID:</td>
        <td>
          <?php echo $row_Rec_ing_add['IngID']; ?>
        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">IngtName:</td>
        <td>
          <?php echo htmlentities($row_Rec_ing_add['IngtName'], ENT_COMPAT, 'utf-8'); ?>
        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">จำนวนคงเหลือ:</td>
        <td>
          <?php echo htmlentities($row_Rec_ing_add['Amount'], ENT_COMPAT, 'utf-8'); ?>
          <?php echo htmlentities($row_Rec_ing_add['Unit'], ENT_COMPAT, 'utf-8'); ?>
        </td>
      </tr>

      <tr valign="baseline">
        <td nowrap="nowrap" align="right">จำนวนที่ต้องการเบิก:</td>
        <td><input type="text" name="Decrease_Amount" value="" size="32" /></td>
      </tr>


      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" id="button" value="บันทึก" /></td>
      </tr>
    </table>
    <p>
      <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="IngID" value="<?php echo $row_Rec_ing_add['IngID']; ?>" />
    </p>
  </form>
  <p>&nbsp;</p>
</body>

</html>
<?php
mysql_free_result($Rec_user);

mysql_free_result($Rec_ing_add);
?>