<?php require_once('Connections/iyouwethey_connect.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
  $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
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

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?"))
    $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0)
    $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: " . $MM_restrictGoTo);
  exit;
}
?>
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

$colname_Rec_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_Rec_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_iyouwethey_connect, $iyouwethey_connect);
$query_Rec_user = sprintf("SELECT FirstName, LastName, role FROM `user` WHERE username = %s", GetSQLValueString($colname_Rec_user, "text"));
$Rec_user = mysql_query($query_Rec_user, $iyouwethey_connect) or die(mysql_error());
$row_Rec_user = mysql_fetch_assoc($Rec_user);
$totalRows_Rec_user = mysql_num_rows($Rec_user);
?>
<!DOCTYPE html>
<meta charset="UTF-8">

<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <p>
          <img src="image/user (2).png" alt="รูป2" width="30" height="30" align="absbottom" />
          <?php echo $row_Rec_user['role']; ?>:
          <?php echo $row_Rec_user['FirstName']; ?>
          <?php echo $row_Rec_user['LastName']; ?>
          &nbsp;
          <a href="<?php echo $logoutAction ?>">
            <img src="image/logout.png" width="30" height="30" align="absbottom">
          </a>
        </p>
      </td>
    </tr>
  </table>
  </td>
  </tr>
  </table>
  <h2>
    <center>
      <p class="headddd">เมนู</p>
    </center>
  </h2>
  <button class="accordion"><img src="image/loupe (1).png" width="40" height="40" align="absmiddle">ค้นหา</button>
  <div class="panel">
    <a href="ing_search.php" formmethod="post"><button class="accordion">ค้นหาวัตถุดิบ</button></a>
    <a href="user_search.php" formmethod="post"><button class="accordion">ค้นหาผู้ใช้</button></a>
  </div>

  <button class="accordion"><img src="image/exposure.png" width="40" height="40" align="absmiddle">
    จัดการวัตถุดิบ</button>
  <div class="panel">
    <a href="ing_insert.php" formmethod="post"><button class="accordion">เพิ่มวัตถุดิบใหม่</button></a>
    <a href="ing_search.php" formmethod="post"><button class="accordion">แก้ไข/ลบข้อมูลวัตถุดิบ</button></a>
    <a href="ing_add_req_search.php" formmethod="post"><button class="accordion">เติม/เบิกวัตถุดิบ</button></a>
  </div>
  <button class="accordion"><img src="image/user (2).png" width="40" height="40" align="absmiddle">
    จัดการผู้ใช้</button>
  <div class="panel">
    <a href="user_insert.php" formmethod="post"><button class="accordion">เพิ่มผู้ใช้ใหม่</button></a>
    <a href="user_search.php" formmethod="post"><button class="accordion">แก้ไข/ข้อมูลผู้ใช้</button></a>
  </div>


  <script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
        }
      });
    }
  </script>

</body>

</html>
<?php
mysql_free_result($Rec_user);
?>