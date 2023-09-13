<?php  

require_once('Connections/iyouwethey_connect.php');

session_start();

$row_Rec_user = array('role' => '', 'FirstName' => '', 'LastName' => ''); // Initialize the variable

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

if(isset($_SESSION['MM_Username'])) {
  $username = $_SESSION['MM_Username'];
  $query_Rec_user = "SELECT * FROM your_user_table WHERE user_column = '$username'";
  
  // Check if $iyouwethey_connect is a valid mysqli object
  if ($iyouwethey_connect instanceof mysqli) {
    $Rec_user = mysqli_query($iyouwethey_connect, $query_Rec_user);

    if ($Rec_user) {
      $row_Rec_user = mysqli_fetch_assoc($Rec_user);
      if (!$row_Rec_user) {
        echo 'No user found';
        // Handle the case where no user was found
      }
    } else {
      echo 'Query failed: ' . mysqli_error($iyouwethey_connect);
      // Handle the query failure
    }
  } else {
    echo 'Invalid database connection';
    // Handle the invalid database connection
  }
} else {
  echo 'Session variable MM_Username is not set';
  // Handle the case where the session variable is not set
}
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
<table width="60%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td colspan="2" align="center" class="headddd">ค้นหาผู้ใช้งาน</td>
  </tr>
  <tr>
    <td width="52%" valign="middle">ป้อนรหัสหรือชื่อผู้ใช้งาน</td>
    <td width="48%" valign="middle"><label for="textfield"></label>
      <form id="form1" name="form1" method="post" action="user_search_result.php">
        <p>
          <input type="text" name="txtsearch" id="txtsearch" />
        </p>
        <p>
          <input type="submit" name="button" id="button" value="ค้นหา" />
          <input type="submit" name="button2" id="button2" value="ยกเลิก" />
        </p>
      </form></td>
  </tr>
</table>
</body>
</html>