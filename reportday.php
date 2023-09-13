<?php 
require_once('Connections/iyouwethey_connect.php'); 

if(isset($_POST['textday'])) {
    $date = $_POST['textday'];

    // Convert the date to a format that matches your database column type
    $formattedDate = date('Y-m-d', strtotime($date));

    // Create the SQL query
    $query = "SELECT * FROM requisition_ingredient WHERE DATE(Date) = '$formattedDate'";

    // Execute the query
    $result = mysqli_query($iyouwethey_connect, $query);

    if(!$result) {
        die('Query Failed' . mysqli_error($iyouwethey_connect));
    }

    // Fetch the results
    while($row = mysqli_fetch_assoc($result)) {
        // Here you can display each row of data in a table or other format
        echo 'UserID: ' . $row['UserID'] . ', ReqID: ' . $row['ReqID'] . ', IngID: ' . $row['IngID'] . ', Date: ' . $row['Date'] . ', ReqAmount: ' . $row['ReqAmount'] . '<br>';
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="659" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td width="651" align="center"><h2>รายงานประจำวัน</h2></td>
  </tr>
  <tr>
    <td align="center"><form id="form1" name="form1" method="post" action="reportday_result.php">
      วัน-เดือน-ปี
      <input type="date" name="textday" id="textday" />
      <input type="submit" name="button" id="button" value="  ตกลง  " />
    </form></td>
  </tr>
</table>
</body>
</html>