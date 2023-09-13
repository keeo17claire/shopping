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
$date = isset($_POST['textday']) ? GetSQLValueString($_POST['textday'], "date") : GetSQLValueString(date('Y-m-d'), "date");

$query_rec_req = sprintf("SELECT requisition_ingredient.ReqID, requisition_ingredient.Date, ingredient.IngtName, user.username, requisition_ingredient.ReqAmount FROM requisition_ingredient JOIN ingredient ON requisition_ingredient.IngID = ingredient.IngID JOIN user ON requisition_ingredient.UserID = user.UserID WHERE DATE(requisition_ingredient.Date) = %s", $date);

$rec_req = mysql_query($query_rec_req, $iyouwethey_connect) or die(mysql_error());
$row_rec_req = mysql_fetch_assoc($rec_req);
$totalRows_rec_req = mysql_num_rows($rec_req);

?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Untitled Document</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    h3 {
      margin-bottom: 20px;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table,
    th,
    td {
      border: 1px solid black;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f4f4f4;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    a {
      color: #000;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>

</head>

<body>
  <table width="100%" border="0" cellspacing="5" cellpadding="10">
    <tr>
      <td align="center">
        <h3>รายงานการเบิกวัตถุดิบประจำวัน</h3>
      </td>
    </tr>

    <tr>
      <td align="center">&nbsp;
        <table border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td>รหัสการเบิก</td>
            <td>วันที่เบิก</td>
            <td>ชื่อวัตถุดิบ</td>
            <td>ผู้เบิก</td>
            <td>จำนวนที่เบิก</td>
            
          </tr>
          <?php do { ?>
            <tr>
              <td>
                <?php echo $row_rec_req['ReqID']; ?>
              </td>
              <td>
                <?php echo $row_rec_req['Date']; ?>
              </td>
              <td>
                <?php echo $row_rec_req['IngtName']; ?>
              </td>
              <td>
                <?php echo $row_rec_req['username']; ?>
              </td>
              <td>
                <?php echo $row_rec_req['ReqAmount']; ?>
              </td>
              
            </tr>
          <?php } while ($row_rec_req = mysql_fetch_assoc($rec_req)); ?>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>
<?php
mysql_free_result($rec_req);
?>