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
mysql_set_charset('utf8', $iyouwethey_connect);

$colname_Rec_int = "-1";
if (isset($_POST['txtsearch'])) {
  $colname_Rec_int = $_POST['txtsearch'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_int = sprintf("SELECT * FROM ingredient WHERE (IngtName LIKE %s) OR (IngID = %s) ORDER BY IngtName ASC", GetSQLValueString("%" . $colname_Rec_int . "%", "text"), GetSQLValueString($colname_Rec_int, "text"));
$Rec_int = mysql_query($query_Rec_int, $iyouwethey_connect) or die(mysql_error());
$row_Rec_int = mysql_fetch_assoc($Rec_int);
$totalRows_Rec_int = mysql_num_rows($Rec_int);
$colname_Rec_int = "-1";
if (isset($_POST['txtsearch'])) {
  $colname_Rec_int = $_POST['txtsearch'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_int = sprintf("SELECT * FROM ingredient WHERE (IngtName LIKE %s) OR (IngID = %s) ORDER BY IngtName ASC", GetSQLValueString("%" . $colname_Rec_int . "%", "text"), GetSQLValueString($colname_Rec_int, "text"));
$Rec_int = mysql_query($query_Rec_int, $iyouwethey_connect) or die(mysql_error());
$row_Rec_int = mysql_fetch_assoc($Rec_int);
$totalRows_Rec_int = mysql_num_rows($Rec_int);

$colname_Rec_user = "-1";
if (isset($_POST['txtlogin'])) {
  $colname_Rec_user = $_POST['txtlogin'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_user = sprintf("SELECT * FROM `user` WHERE UserID = %s ORDER BY UserName ASC", GetSQLValueString($colname_Rec_user, "int"));
$Rec_user = mysql_query($query_Rec_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_user = mysql_fetch_assoc($Rec_user);
$totalRows_Rec_user = mysql_num_rows($Rec_user);
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
  </style>
</head>

<body>
  <table width="100%" height="100" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="135" align="left" bgcolor="#FFFFFF"><img src="image/logo2.png" alt="รูป1" width="101" height="101"
          align="absmiddle" /></td>
      <td width="1156" height="100" align="left" valign="top" bgcolor="#FFFFFF">
        <h4><span class="TH"> <span class="head_color"> <span class="headddd">ระบบคลังสินค้า <br>
              </span></span></span><span class="TH"><span class="head_color"><span class="headddd">I YOU WE
                THEY</span></span></span></h4>
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
  </td>
  </tr>
  </table>
  <table width="67%" border="0" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td width="100%" colspan="2" align="center">
        <h3><strong>ผลการค้นหาวัตถุดิบ พบจำนวน
            <?php echo $totalRows_Rec_int ?> รายการ
          </strong></h3>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <table width="1228" border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td width="195" align="center"><strong>ลำดับ</strong></td>
            <td width="177" align="center"><strong>ชื่อวัตถุดิบ</strong></td>
            <td width="132" align="right"><strong>ราคา (บาท)</strong></td>
            <td width="153" align="right"><strong>จำนวนคงเหลือ</strong></td>
            <td width="212" align="right"><strong>จุดสั่งซื้อ</strong></td>
            <td width="121" align="right"><strong>หน่วย</strong></td>
            <td width="131" align="right">&nbsp;</td>
          </tr>
          <?php $startRow_Rec_int = 0;
          do { ?>
            <tr>
              <td align="center">
                <?php echo ($startRow_Rec_int + 1) ?>.
              </td>
              <td align="center">
                <?php echo $row_Rec_int['IngtName']; ?>
              </td>
              <td align="right">
                <?php echo $row_Rec_int['Price']; ?>
              </td>
              <td align="right">
                <?php echo $row_Rec_int['Amount']; ?>
              </td>
              <td align="right">
                <?php echo $row_Rec_int['PurchasePoint']; ?>
              </td>
              <td align="right">
                <?php echo $row_Rec_int['Unit']; ?>
              </td>
              <td align="center"><a href="ing_edit.php?iid=<?php echo $row_Rec_int['IngID']; ?>"><img
                    src="image/edit-text.png" width="51" height="51" /></a>
                <a href="ing_delete.php?id=<?php echo $row_Rec_int['IngID']; ?>">
                  <img src="image/delete.png" width="50" height="50" />
                </a>
              </td>
            </tr>
            <?php $startRow_Rec_int++;
          } while ($row_Rec_int = mysql_fetch_assoc($Rec_int)); ?>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>
<?php
mysql_free_result($Rec_int);

mysql_free_result($Rec_user);
?>