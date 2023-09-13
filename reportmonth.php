<?php date_default_timezone_set("Asia/Bangkok"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ค้นหารายงานการเบิกวัตถุดิบประจำเดือน</title>
</head>

<body>
<table width="659" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td width="651" align="center"><h2>รายงานการเบิกประจำเดือน</h2></td>
  </tr>
  <tr>
    <td align="center"><form id="form1" name="form1" method="post" action="reportmonth_result.php">
      เดือน-ปี
          <label for="txtmonth"></label>
          <select name="txtmonth" id="txtmonth">
            <option value="01">มกราคม</option>
            <option value="02">กุมภาพันธ์</option>
            <option value="03">มีนาคม</option>
            <option value="04">เมษายน</option>
            <option value="05">พฤษภาคม</option>
            <option value="06">มิถุนายน</option>
            <option value="07">กรกฎาคม</option>
            <option value="08">สิงหาคม</option>
            <option value="09">กันยายน</option>
            <option value="10">ตุลาคม</option>
            <option value="11">พฤศจิกายน</option>
            <option value="12">ธันวาคม</option>
          </select>
         ปี ค.ศ.
         <label for="txtyear"></label>
         <input name="txtyear" type="text" id="txtyear" value="<?php echo date ("Y");?>" size="4" maxlength="4" />
         <input type="submit" name="button" id="button" value="  ตกลง  " />
    </form></td>
  </tr>
</table>
</body>
</html>