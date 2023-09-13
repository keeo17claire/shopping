<?php
date_default_timezone_set("Asia/Bangkok");
require_once('Connections/iyouwethey_connect.php');

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    global $iyouwethey_connect;

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

$maxRows_Rec_req_report = 10;
$pageNum_Rec_req_report = 0;
if (isset($_GET['pageNum_Rec_req_report'])) {
    $pageNum_Rec_req_report = $_GET['pageNum_Rec_req_report'];
}
$startRow_Rec_req_report = $pageNum_Rec_req_report * $maxRows_Rec_req_report;

$colname_Rec_req_report = "-1";
if (isset($_GET['id'])) {
    $colname_Rec_req_report = $_GET['id'];
}

// Perform the database query
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_req_report = sprintf("SELECT * FROM requisition_ingredient, ingredient, `user` WHERE (ReqID = %s) AND (requisition_ingredient.IngID = ingredient.IngID) AND (requisition_ingredient.UserID = user.UserID)", GetSQLValueString($colname_Rec_req_report, "int"));
$query_limit_Rec_req_report = sprintf("%s LIMIT %d, %d", $query_Rec_req_report, $startRow_Rec_req_report, $maxRows_Rec_req_report);
$Rec_req_report = mysql_query($query_limit_Rec_req_report, $iyouwethey_connect);

// Check for query errors
if (!$Rec_req_report) {
    die("Query error: " . mysql_error());
}

$row_Rec_req_report = mysql_fetch_assoc($Rec_req_report);

if (isset($_GET['totalRows_Rec_req_report'])) {
    $totalRows_Rec_req_report = $_GET['totalRows_Rec_req_report'];
} else {
    $all_Rec_req_report = mysql_query($query_Rec_req_report, $iyouwethey_connect);

    // Check for query errors
    if (!$all_Rec_req_report) {
        die("Query error: " . mysql_error());
    }

    $totalRows_Rec_req_report = mysql_num_rows($all_Rec_req_report);
}
$totalPages_Rec_req_report = ceil($totalRows_Rec_req_report / $maxRows_Rec_req_report) - 1;
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta charset="UTF-8">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>รายงานการเบิกจ่ายวัตถุดิบ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h3>รายงานการเบิกจ่ายวัตถุดิบ</h3>
    <div>
        <h4>ใบเบิกสินค้า</h4>
        <table>
            <tr>
                <th>รหัสใบเบิก</th>
                <td><?php echo $row_Rec_req_report['ReqID']; ?></td>
            </tr>
            <tr>
                <th>วันที่เบิก</th>
                <td><?php echo $row_Rec_req_report['Date']; ?></td>
            </tr>
            <tr>
                <th>ผู้เบิก</th>
                <td><?php echo $row_Rec_req_report['username']; ?></td>
            </tr>
        </table>
    </div>
    <div>
        <h4>รายการเบิก</h4>
        <table>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อวัตถุดิบ</th>
                <th>จำนวนที่เบิก</th>
                <th>จำนวนคงเหลือ</th>
                <th>หน่วย</th>
            </tr>
            <?php
            $counter = 1;
            do { ?>
                <tr>
                    <td><?php echo $counter; ?></td>
                    <td><?php echo $row_Rec_req_report['IngID']; ?></td>
                    <td><?php echo $row_Rec_req_report['ReqAmount']; ?></td>
                    <td><?php echo $row_Rec_req_report['Amount']; ?></td>
                    <td><?php echo $row_Rec_req_report['Unit']; ?></td>
                </tr>
            <?php
            $counter++;
            } while ($row_Rec_req_report = mysql_fetch_assoc($Rec_req_report));
            ?>
        </table>
    </div>
    <div align="right">วันที่พิมพ์ <?php echo date("d/m/Y H:i:s", strtotime("now")) ?>
        <input name="print" type="submit" id="print" value="Print" onclick="window.print()" />
    </div>
</body>

</html>