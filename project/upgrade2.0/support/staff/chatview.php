<?php
   include("./includes/session.php");
   include("../config/settings.php");
   if( !isset($INSTALLED))
   header("location:../install/index.php") ;
   include("./includes/functions/dbfunctions.php");
   include("./includes/functions/impfunctions.php");
   include("./includes/functions/miscfunctions.php");
   include("./languages/".$_SESSION["sess_language"]."/chat_logs.php");

  $conn = getConnection();
   $chatid = $_GET['cid'];
   $flg = $_GET['flg'];
   if ( $flg == 'c') {
     $sql =" Select c.nChatId,c.tMatter,c.dTimeStart,c.dTimeEnd,u.vUserName as user_staff_name,u.vEmail as user_staff_email, s.vStaffname as staff_name,s.vMail as staff_email ";
     $sql.=" from sptbl_chat c";
     $sql.=" inner join sptbl_users u on c.nUserId = u.nUserId inner join sptbl_staffs s on c.nStaffId=s.nStaffId  ";
     $sql.=" where c.nChatId = '".$chatid."' ";
   } else {
     $sql =" Select o.nChatId,o.tMatter,o.dTimeStart,o.dTimeEnd, ( select  vStaffname from sptbl_staffs where nStaffId=o.nFirstStaffID ) as user_staff_name, ( select  vMail from sptbl_staffs where nStaffId=o.nFirstStaffID ) as user_staff_email,( select  vStaffname from sptbl_staffs where nStaffId=o.nSecondStaffID ) as staff_name,( select vMail from sptbl_staffs where nStaffId=o.nSecondStaffID ) as staff_email ";
     $sql.=" from sptbl_operatorchat o";
     $sql.=" where o.nChatId = '".$chatid."' ";
   }
   $rs = executeSelect($sql,$conn);
   $matter =''; 
   if(mysql_num_rows($rs)>0){
     while($row=mysql_fetch_array($rs)) {
      $stime = $row['dTimeStart'];
	  $etime = $row['dTimeEnd'];
	  $user_staff_name = $row['user_staff_name'];
	  $user_staff_email = $row['user_staff_email'];
	  $staff_name = $row['staff_name'];
	  $staff_email = $row['staff_email'];
	  $matter = $row['tMatter'];
     }
   }
?>
<html>
<head>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="tpprint"></div>
<table class="list_tbl" width='95%'  border='0' align="center" cellspacing="1" bgcolor="#FFFFFF">
  <tr class="heading">
    <td colspan="2" class="whitebasic">&nbsp;</td>
  </tr>
  <tr class="heading">
    <td class="whitebasic"><a class="listing" href="javascript:document.getElementById('tpprint').style.display='none';document.getElementById('btprint').style.display='none';window.print();"><?php echo   TEXT_PRINT?></a></td>
    <td class="whitebasic">&nbsp;</td>
  </tr>
  <tr class="heading"><td class="whitebasic"><?php echo TEXT_START_TIME.":".$stime ?></td><td class="whitebasic"><?php if ($etime=='0000-00-00 00:00:00') echo "Not Completed"; else echo TEXT_END_TIME.":".$etime ?></td></tr>
<tr class="heading"><td class="whitebasic"><?php echo TEXT_USER_STAFF.":"?><br><?php echo TEXT_NAME.":".$user_staff_name ?><br><?php echo TEXT_EMAIL.":".$user_staff_email ?></td><td class="whitebasic"><?php echo TEXT_STAFF.":" ?><br><?php echo TEXT_NAME.":".$staff_name ?><br><?php echo TEXT_EMAIL.":".$staff_email ?></td></tr>
<tr class="listing"><td class="whitebasic" colspan='2'><?php echo $matter?></td></tr>
<tr class="listing">
  <td class="whitebasic" colspan='2'><a class="listing" href="javascript:document.getElementById('tpprint').style.display='none';document.getElementById('btprint').style.display='none';window.print();"><?php echo   TEXT_PRINT?></a></td>
</tr>
</table>
<div id="btprint"></div>
</body>
</html>
