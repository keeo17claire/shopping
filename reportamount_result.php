<?php
require_once('Connections/iyouwethey_connect.php');

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

$searchQuery = isset($_GET['txtsearch']) ? $_GET['txtsearch'] : '';

// Create a connection
$conn = new mysqli($hostname_iyouwethey_connect, $username_iyouwethey_connect, $password_iyouwethey_connect, $database_iyouwethey_connect);

// Set the charset to UTF-8
$conn->set_charset("utf8");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "SELECT IngID, IngtName, Amount FROM ingredient WHERE IngID LIKE ? OR IngtName LIKE ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
$searchWildcard = "%" . $searchQuery . "%";
$stmt->bind_param('ss', $searchWildcard, $searchWildcard);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch data as associative array
$rows = $result->fetch_all(MYSQLI_ASSOC);

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
        <h3>รายงานจำนวนวัตถุดิบคงเหลือทั้งหมด</h3>
      </td>
    </tr>

    <tr>
      <td align="center">&nbsp;
        <table border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td>รหัสวัตถุดิบ</td>
            <td>ชื่อวัตถุดิบ</td>
            <td>จำนวนคงเหลือ</td>
            <!-- <td>&nbsp;</td> -->
          </tr>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td>
                <?php echo $row['IngID']; ?>
              </td>
              <td>
                <?php echo $row['IngtName']; ?>
              </td>
              <td>
                <?php echo $row['Amount']; ?>
              </td>
              <!-- <td><a href="req_detail_report.php?iid=<?php echo $row['IngID']; ?>">รายละเอียด</a></td> -->
            </tr>
          <?php endforeach; ?>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>
<?php
mysqli_free_result($result);
?>