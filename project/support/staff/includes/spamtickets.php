<?php
require_once("../includes/decode.php");
        if(!isValid(1)) {
        echo("<script>window.location.href='../invalidkey.php'</script>");
        exit();
        }
//warning message before 10 days
if($glob_date_check == "Y")
{
        echo("<script>alert('" . MESSAGE_LICENCE_EXPIRE . $glob_date_days . MESSAGE_LICENSE_DAYS . "');</script>");
}
//end warning
$var_staffid = $_SESSION["sess_staffid"];
$fld_arr = $_SESSION["sess_fieldlist"];

$lst_dept = $_SESSION['department_ids'] ;  // it is  set in the  dept_overview.php file

$fld_prio = $_SESSION["sess_priority"];

if($_GET["mt"] == "y") {
        $var_numBegin = $_GET["numBegin"];
        $var_start = $_GET["start"];
        $var_begin = $_GET["begin"];
        $var_num = $_GET["num"];
                $var_type = $_GET["tp"];
                $var_stylename = $_GET["stylename"];
                $var_styleminus = $_GET["styleminus"];
                $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
        $var_numBegin = $_POST["numBegin"];
        $var_start = $_POST["start"];
        $var_begin = $_POST["begin"];
        $var_num = $_POST["num"];
                $var_type = $_POST["tp"];
                $var_stylename = $_POST["stylename"];
                $var_styleminus = $_POST["styleminus"];
                $var_styleplus = $_POST["styleplus"];
}

if($_POST["cmbDepartment"] != ""){
                $var_deptid = $_POST["cmbDepartment"];
}else if($_GET["cmbDepartment"] != ""){
                $var_deptid = $_GET["cmbDepartment"];
}

/*//Block - I (populate the allowed departments for the user)
        $lst_dept = "'',";
        $sql = "Select nDeptId from sptbl_staffdept where nStaffId='$var_staffid'";
        $rs_dept = executeSelect($sql,$conn);
        if (mysql_num_rows($rs_dept) > 0) {
                while($row = mysql_fetch_array($rs_dept)) {
                        $lst_dept .= $row["nDeptId"] . ",";
                }
        }
        $lst_dept = substr($lst_dept,0,-1);

        mysql_free_result($rs_dept);
//End Of Block - I*/
//Modification on December 23, 2005
//get response times for user and display the pending time.
$arrayDept=array();
$arrayDeptName=array();
$sql = "Select nDeptId,nResponseTime,vDeptDesc from sptbl_depts";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_array($result)) {
                $arrayDept[$row["nDeptId"]] = $row["nResponseTime"];
                $arrayDeptName[$row["nDeptId"]]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
                //$arrayDept[$row["nDeptId"]['name']]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
        }
}

//Delete Section
$var_list = "";
if($_POST["del"] == "DM") {
	for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
		$var_list .= mysql_real_escape_string($_POST["chkDeleteTickets"][$i]) . ",";
	}
	$var_list = substr($var_list,0,-1);
	$message="";
          $flag_msg = "";
	$qry="delete  from sptbl_spam_tickets where   nSpamTicketId in($var_list)";
    mysql_query($qry);
    $message .= TEXT_SPAM_DELETE_SUCCESS;
      $flag_msg = "class='msg_success'";
}
if($_POST["del"] == "NS") {

    require("../spamfilter/spamfilterclass.php");
    for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
		$var_list .= mysql_real_escape_string($_POST["chkDeleteTickets"][$i]) . ",";
	}
	$var_list = substr($var_list,0,-1);
	$qry="select * from sptbl_spam_tickets where  nSpamTicketId in($var_list)";
	$result_spam = mysql_query($qry) or die(mysql_error());

	if(mysql_num_rows($result_spam) > 0) {
	        $dotreal="../parser";
            $dotdotreal="..";
	        require("../parser/spamparser_include.php");
			while($row_spam = mysql_fetch_array($result_spam)) {
				 $dpostdate = $row_spam['dPostDate'];
			     $emailmarkedasspam=$row_spam['tcontent'];
				
				 if($emailmarkedasspam != ""){
				 	require("../parser/spamparser.php");
				    $message = TEXT_SPAM_REASSIGN;
                                      $flag_msg = "class='msg_success'";
				 }else
					$message = TEXT_SPAM_CANNOT_REASSIGN;
                                   $flag_msg = "class='msg_error'";
   		    }
    }
//	echo $emailmarkedasspam;
//	echo $var_list;
//	exit;
	if($emailmarkedasspam != ""){
	   	$qry="delete  from sptbl_spam_tickets where  nSpamTicketId in($var_list)";
	    mysql_query($qry);
	}
}

$sql = "Select nSpamTicketId,nDeptId,vTitle,tQuestion,dPostDate from sptbl_spam_tickets where 1=1 ";
$qryopt="";
if($var_deptid != ""){
        $arr_dept =        explode(",",$lst_dept);
        $pflag = false;
        for($i=0;$i<count($arr_dept);$i++) {
                if ($var_deptid == $arr_dept[$i]) {
                        $pflag = true;
                        break;
                }
        }
        if ($pflag == true) {
                $qryopt .= " AND nDeptId = '" . mysql_real_escape_string($var_deptid) . "' ";
        }
        else {
                $qryopt .= " AND nDeptId IN($lst_dept) ";
        }
}
else {
        $qryopt .= " AND nDeptId IN($lst_dept) ";
}
$sql .= $qryopt . " Order By dPostDate DESC ";
$_SESSION["sess_backreplyurl"]="";
$var_back="./spamtickets.php?mt=y&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&tp=$var_type&begin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&cmbDepartment=" . urlencode($var_deptid) . "&";
$_SESSION["sess_spamticketbackurl"] = $var_back;
?>

<form name="frmDetail" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">
<div class="content_section">

                  <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                         <tr>
                            <td colspan="5" align="center">          
                             <select name="cmbDepartment" class="comm_input input_width1" onChange="javascript:changeDepartment();" style="width:200px ">
                              <option value=""><?php echo htmlentities(TEXT_DEPARTMENT_FILTER); ?></option>
                              <?php
                                                                  $sql2 = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from
                                                                sptbl_depts d  inner join sptbl_companies c
                                                                 on d.nCompId = c.nCompId  WHERE d.nDeptId IN($lst_dept) ";
                                                                $lst_dept_opt = "";
                                                                $rs_dept = executeSelect($sql2,$conn);
                                                                if (mysql_num_rows($rs_dept) > 0) {
                                                                        while($row = mysql_fetch_array($rs_dept)) {
                                                                                $lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\" >" . htmlentities($row["description"]) . "</option>";
                                                                        }
                                                                }
                                                                echo($lst_dept_opt);
                                                          ?>
                            </select>     </td>
                          </tr>
						  <div class="content_section_title">
	<h3><?php echo TEXT_SPAM_TICKETS ?></h3>
	</div>
                          
                        </table>
                         
							<div style="overflow:auto">							  
							 
                                          <table width="100%"  border="0" cellpadding="2" cellspacing="1" class="list_tbl"  >
										   <?php
											if($_POST["del"] == "DM" or $_POST["del"] == "NS") {
												echo("<tr><td align=\"center\"  class=\"errormessage\" colspan=5><div ".$flag_msg.">" . $message . "</div></td></tr>");
											}
										  ?>

                                            <tr align="left"  class="whitebasic">
												<th width="3%" align="center"><input name="checkall" id="checkall" type=checkbox class=checkbox onclick="checkallfn()">
											   </th>
                                               <th width="80%" class="listing" style="text-decoration:none;">
                                                 <?php echo "<b>".TEXT_SPAM_TICKETS_TITLE."</b>";?>

											   </th>
											   <th class="listing" style="text-decoration:none;">
											     <?php echo "<b>".TEXT_SPAM_TICKETS_DATE."</b>";?>
											   </th>
										    </tr>
<?php
$var_maxposts = (int)$_SESSION["sess_maxpostperpage"];
$var_maxposts = ($var_maxposts < 1)?1:$var_maxposts;

//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
$totalrows = mysql_num_rows(executeSelect($sql,$conn));
settype($totalrows,integer);
settype($var_begin,integer);
settype($var_num,integer);
settype($var_numBegin,integer);
settype($var_start,integer);

$var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
 if(($totalrows <= $var_calc_begin)) {
         $var_nor = $var_maxposts;
        $var_nol = 10;
         if($var_num > $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin;
                $var_begin = $var_begin - $var_nor;
        }
        elseif($var_num == $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin - $var_nol;
                $var_begin = $var_calc_begin - $var_nor;
                $var_start="";
        }
 }

$navigate = pageBrowser($totalrows,10,$var_maxposts,"&mt=y&tp=$var_type&cmbDepartment=$var_deptid&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&",$var_numBegin,$var_start,$var_begin,$var_num);
$sql = $sql.$navigate[0];
$var_time=time();
$cntr = 1;
$count=0;
$rs = executeSelect($sql,$conn);
while($row = mysql_fetch_array($rs)) {
?>

                                              <tr align="left"  class="whitebasic" >
											    <td align="center">
													<input type="checkbox" name="chkDeleteTickets[]" id="chkDeleteTickets<?php echo($cntr);?>" value="<?php echo($row["nSpamTicketId"]); ?>" class="checkbox">
												</td>
                                              <?php
											    echo "<td width='80%'><a href=\"spamdetails.php?mt=y&spamticketid=" . $row["nSpamTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row['vTitle']) ."</a></td>";
											  	echo "<td width='20%'><a href=\"spamdetails.php?mt=y&spamticketid=" . $row["nSpamTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  date("m-d-Y  H:i:s",strtotime(htmlentities($row['dPostDate']))) ."</a></td>";
											  ?>
                                            </tr>
<?php
$cntr++;
$count++;
}
mysql_free_result($rs);
?>
                                                                                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
																											<tr class="whitebasic">
																											<td colspan="2" align="left" ><br><a href="javascript:deleteTickets(0);"  class="listing" style="text-decoration:none;"><?php echo TEXT_DELTE_TICKETS; ?></a>&nbsp;
																											&nbsp;&nbsp;<a href="javascript:notspamTickets(0);"  class="listing" style="text-decoration:none;"><?php echo TEXT_MARKS_AS_NOTSPAM_TICKETS; ?></a></td>


																											</tr>
																											
                                                                                                                <tr align="left" class="whitebasic">
                                                                                                                   <td><div class="pagination_info"><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></div></td>
																												   </tr>
																												   <tr align="left" class="whitebasic">
                                                                        <td><div class="pagination_links"><?php echo($navigate[2]); ?>
                                                                                                  <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
                                                                                                  <input type="hidden" name="start" value="<?php echo   $var_start?>">
                                                                                                  <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
                                                                                                  <input type="hidden" name="num" value="<?php echo   $var_num?>">
                                                                                                   <input type="hidden" name="mt" value="y">
                                                                                                   <input type="hidden" name="tp" value="<?php echo($var_type); ?>">
                                                                                                   <input type="hidden" name="postback" value="">
                                                                                                  <input type="hidden" name="id" value="">
																								   <input type="hidden" name="del" value="">
                                                                                                   <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                                                                   <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                                                                   <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
																								   </div>
                                                                        </td>

                                                                                                                </tr>
                                                                                                        </table>

                                                                                             
                                          </table>
							</div>							  
							
						

                  
</div>
</form>