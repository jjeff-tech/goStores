<?php
include("./languages/".$_SP_language."/main.php");
$var_staffid = $_SESSION["sess_staffid"];
$fld_arr = $_SESSION["sess_fieldlist"];
$fld_prio = $_SESSION["sess_priority"];
/*Added on 280709 start*/
$var_maxposts = (int)$_SESSION["sess_maxpostperpage"];
$var_maxposts = ($var_maxposts < 1)?1:$var_maxposts;

//echo "XXXXXX".$var_maxposts;
/*Added on 280709 end*/

//echo(count($fld_prio) . "  and " . count($fld_prio[0]) . "<br>");

/////////////////// for sorting
if(isset($_GET["tp"]))
	$var_type = $_GET["tp"];

if(isset($_GET['val']))
	$var_orderby = $_GET['val'];
else	
	$var_orderby = "vRefNo";

$var_sorttype = "DESC";
$var_filename = "s_desc.png";

if($_GET['sorttype'] == 'DESC'){
	$var_sorttype = "ASC";
	$var_filename = "s_asc.png";
}	
else if($_GET['sorttype'] == 'ASC'){
	$var_sorttype = "DESC";
	$var_filename = "s_desc.png";
}


if ($_POST["cmbDepartment"] != "") {
		$var_deptid = $_POST["cmbDepartment"];
}else if ($_GET["cmbDepartment"] != "") {
		$var_deptid = $_GET["cmbDepartment"];
}
if (isset($_POST["cmbRefresh"]) && $_SESSION["sess_refresh"] != $_POST["cmbRefresh"] && $_POST["del"] != "DM" && $_POST["del"] != "DN") {
	$var_refresh = 	$_POST["cmbRefresh"];
	settype($var_refresh,integer);
	$_SESSION["sess_refresh"] = $var_refresh;
	$sql = "Update sptbl_staffs set nRefreshRate='" . addslashes($var_refresh) . "' WHERE nStaffId='$var_staffid'";
	executeQuery($sql,$conn);
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
$arrayDept=array();
$arrayDeptName=array();
$sql = "Select nDeptId,nResponseTime,vDeptDesc from sptbl_depts";
$result = mysql_query($sql,$conn) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
	while($row = mysql_fetch_array($result)) {
		$arrayDept[$row["nDeptId"]] = $row["nResponseTime"];
		$arrayDeptName[$row["nDeptId"]]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
		//$arrayDept[$row["nDeptId"]['name']]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
	}
}

$var_list = "";
if($_POST["del"] == "DN") {
	for($i=0;$i<count($_POST["chkDeleteTickets2"]);$i++) {
		$var_list .= addslashes($_POST["chkDeleteTickets2"][$i]) . ",";
	}
	$var_list = substr($var_list,0,-1);
	$message="";
	deleteChecked($var_list,$message);
}
elseif($_POST["del"] == "DM") {
	for($i=0;$i<count($_POST["chkDeleteTickets"]);$i++) {
		$var_list .= addslashes($_POST["chkDeleteTickets"][$i]) . ",";
	}
	$var_list = substr($var_list,0,-1);
	$message="";
	deleteChecked($var_list,$message);
}

?>


<div class="content_section">


<?php
								$var_date = date("Y-m-d");
								$sql = "Select nRemId from sptbl_reminders where nStaffId='$var_staffid' AND dRemAlert LIKE '$var_date%'";
								$rs_rem = executeSelect($sql,$conn);
								//echo($sql);
								if(mysql_num_rows($rs_rem) > 0) {
									echo("<img src='./../images/reminder.gif' height='25px' width='25px'><br><a href=\"reminders.php?mt=y&stylename=STYLEREMINDERS&styleminus=minus3&styleplus=plus3&\">" . TEXT_VIEW_ALERT . "</a>");
								}
								mysql_free_result($rs_rem);
							?>

<div class="content_search_container">
						<div class="left rightmargin">
							<select name="cmbDepartment" class="comm_input input_width1" onChange="javascript:changeDepartment();" >
                              <option value=""><?php echo htmlentities(TEXT_DEPT_FILTER); ?></option>
                              <?php
							  	$sql = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from
								sptbl_depts d  inner join sptbl_companies c
								 on d.nCompId = c.nCompId  WHERE d.nDeptId IN($lst_dept) ";

								$lst_dept_opt = "";
								$rs_dept = executeSelect($sql,$conn);
								if (mysql_num_rows($rs_dept) > 0) {
									while($row = mysql_fetch_array($rs_dept)) {
									$lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\">" . htmlentities($row["description"]) . "</option>";

									}
								}
								echo($lst_dept_opt);
							  ?>
                            </select>
						</div>
						
						<div class="right">
						<div class="left topmargin"><?php echo(TEXT_AUTO_REFRESH);?>&nbsp;&nbsp;</div>
						<div class="left">
						<select name="cmbRefresh" class="comm_input input_width1a" onChange="javascript:setRefresh();" >
								  <option value="0"><?php echo(TEXT_NO_REFRESH);?></option>
								  <option value="1" <?php echo(($_POST["cmbRefresh"] == "1")?"Selected":"");?>>1 <?php echo   TEXT_MINUTE?></option>
								  <option value="2" <?php echo(($_POST["cmbRefresh"] == "2")?"Selected":"");?>>2 <?php echo   TEXT_MINUTES?></option>
								  <option value="3" <?php echo(($_POST["cmbRefresh"] == "3")?"Selected":"");?>>3 <?php echo   TEXT_MINUTES?></option>
								  <option value="4" <?php echo(($_POST["cmbRefresh"] == "4")?"Selected":"");?>>4 <?php echo   TEXT_MINUTES?></option>
								  <option value="5" <?php echo(($_POST["cmbRefresh"] == "5")?"Selected":"");?>>5 <?php echo   TEXT_MINUTES?></option>
								  <option value="6" <?php echo(($_POST["cmbRefresh"] == "6")?"Selected":"");?>>6 <?php echo   TEXT_MINUTES?></option>
								  <option value="7" <?php echo(($_POST["cmbRefresh"] == "7")?"Selected":"");?>>7 <?php echo   TEXT_MINUTES?></option>
								  <option value="8" <?php echo(($_POST["cmbRefresh"] == "8")?"Selected":"");?>>8 <?php echo   TEXT_MINUTES?></option>
								  <option value="9" <?php echo(($_POST["cmbRefresh"] == "9")?"Selected":"");?>>9 <?php echo   TEXT_MINUTES?></option>
								  <option value="10" <?php echo(($_POST["cmbRefresh"] == "10")?"Selected":"");?>>10 <?php echo   TEXT_MINUTES?></option>
							  </select>
							<input type="hidden" name="refresh" value="">
							<input type="hidden" name="del" value="">
						</div>
						</div>
						
					<div class="clear"></div>
					</div>


</div>



<div class="content_section">
<div class="content_section_title">
<h4><?php echo HEADING_TICKETS_NEW ?></h4></div>




            <?php if($_POST["del"] == "DM" || $message !="") {
				echo("<div class='content_section_data'><div class='msg_error'>" . $message . "</div></div>");
												}
			?>

 <?php
                    //												if($_POST["del"] == "DN") {
                    //													echo("<tr><td colspan=\"11\" align=\"center\"  class=\"listingmaintext\">" . $message . "</td></tr>");
                    //												}
                                  ?>

                          <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >

                                  <tr >
                                          <th  width="3%" align="center"><input name="checkall1" id="checkall1" type="checkbox" onclick="checkallfn1(<?php echo $var_maxposts;?>)">
                                    </th>
                                    <th width="15%" align="center" ><?php echo TEXT_FOLLOW; ?></th>
                                   <th width="4%">&nbsp;</th>
                                <?php
                                                $cnt = 0;
                                        while($cnt < count($fld_arr)) {
                    /*												 if($var_orderby == $fld_arr[$cnt][0])
                                                $img_path = "<img src=./../images/".$var_filename.">";
                                         else
                                                $img_path = "";
                    */												 ?>
                                        <th align="left"><?php echo constant($fld_arr[$cnt][1]); ?></th>
                                        <?php
                                                $cnt++;
                                        }
                                        //$cnt++;
                                        $cnt += 2;
                                   ?>
                                   <th width="15%" align="left"><?php echo TEXT_DUE; ?></th>
                            </tr>
<?php
							
/// modified on 30-7-07 to department filtering
$qryopt="";

if($var_deptid != ""){
		$arr_dept =	explode(",",$lst_dept);
		$pflag = false;
		for($i=0;$i<count($arr_dept);$i++) {
			if ($var_deptid == $arr_dept[$i]) {
				$pflag = true;
				break;
			}
		}
		if ($pflag == true) {
			$qryopt .= " AND t.nDeptId = '" . addslashes($var_deptid) . "' ";
		}
		else {
			$qryopt .= " AND t.nDeptId IN($lst_dept) ";
		}
}
else {
	$qryopt .= " AND t.nDeptId IN($lst_dept) ";
}
/////
    $var_time=time();
    $cntr = 1;
    $count=0;

    $sql = "Select t.*,rp.nStaffId,rp.nUserId as rpuserid  from sptbl_tickets t left join sptbl_replies rp on (rp.dDate=t.dLastAttempted and rp.nTicketId=t.nTicketId) where t.vDelStatus='0' AND t.nLabelId='0' AND t.vStatus='open'  ";
    $sql .= $qryopt . " Order By t.dLastAttempted DESC ";
    $_SESSION['next_sql_new'] = $sql;
    $sql .= " Limit 0,5 ";

//							echo $sql;

    $rs = executeSelect($sql,$conn);
    $cntr = 1;

    // it is for next and previous ticket
            $startvalue=0;
            if($begin=="" and $numBegin==""){
                    $startvalue=0;
            }else if($begin==""){
                    $startvalue=(($numBegin-1)*10);
            }else{
                    $startvalue=$begin;
            }
            $newcount=0;
    /////
    $newTkts = mysql_num_rows($rs);
    if (mysql_num_rows($rs) > 0) {
            while($row = mysql_fetch_array($rs)) {
                    $limitstart=$startvalue+$newcount;

                    $lastanswerd=TEXT_LA;
                    if($row['nStaffId']>0){
                      $lastanswerd=TEXT_LAS;
                    }else if($row['rpuserid']>0){
                      $lastanswerd=TEXT_LAU;
                    }

                    $Viewersarray = explode(',',$row["vViewers"]);
                    if(in_array($var_staffid, $Viewersarray)){
                        $viewedClass = "class='readTK'";
                    }else{
                        $viewedClass = "class='unreadTK'";
                    }
    ?>

                                              <tr align="left"  <?php echo $viewedClass;?> >
                                                  <td align="center"  width="5%">
                                                        <input type="checkbox" name="chkDeleteTickets2[]" id="chkDeleteTickets2<?php echo($cntr);?>" value="<?php echo($row["nTicketId"]); ?>" >
                                                </td>
                                                <td align="center"  width="0%">
                                                        <img class="imgFollow" name="imgFollow[]" id="<?php echo($row["nTicketId"]); ?>"   border="0" src="./../images/star-grey.png">
                                                 </td>
                                                <?php 

                                                $sql = "Select * from sptbl_priorities where nPriorityValue='" . addslashes($row["vPriority"]) . "'";
                                                $res_prior_feacher = executeSelect($sql, $conn);
                                                if (mysql_num_rows($res_prior_feacher) > 0) {
                                                    $row_prior_feacher = mysql_fetch_array($res_prior_feacher);
                                                    $ticketColor = $row_prior_feacher["vTicketColor"];
                                                    $prioritieIcon = $row_prior_feacher["vPrioritie_icon"];
                                                }

                                                if ($prioritieIcon != "") {

                                                    $filePath = "../ticketPriorLogo/" . $prioritieIcon;
                                                } else {
                                                    $filePath = "../ticketPriorLogo/noicon.jpg";
                                                }

                                                for($i=0;$i < count($fld_arr);$i++) {

                                                switch($fld_arr[$i][0]) {
                                                         case "vPriority":
                                                                for($j=0;$j < count($fld_prio);$j++) {
                                                                        //echo($fld_prio[$j][0] . " and " . $row[($fld_arr[$i][0])] . " and " . $fld_prio[$j][2]);
                                                                        if ($fld_prio[$j][0] == $row[($fld_arr[$i][0])]) {
                                                                                echo ("<td align=\"center\" width=10% class=\"subtbl\" style='background-color:$ticketColor;'><table width=\"100%\" cellpadding=\"0\" border=\"0\" class='innertable1' ><tr><td class='user_priority_icon'  align='left'><img src='$filePath'></td>
                                                                                        <td  bgcolor=" . $fld_prio[$j][1] . " align='center'><a href=\"viewticket.php?limitval=$limitstart&stat=new&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class=''>" . $fld_prio[$j][2] . "</a></td></tr></table></td>");
                                                                        }
                                                                }
                                                                break;
                                                         case "dPostDate":
                                                                 echo "<td width='11%'><a href=\"viewticket.php?limitval=$limitstart&stat=new&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . date("m-d-Y",strtotime($row[($fld_arr[$i][0])])) . "</a></td>";
                                                                 break;
                                                        case "nLockStatus":
                                                                         echo "<td  width='4%'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . (($row[($fld_arr[$i][0])] == "1")?TEXT_LOCK_YES:TEXT_LOCK_NO) . "</a></td>";
                                                                         break;
                                                        case "vRefNo":
                                                                //echo "<td width='1%'  style=\"word-break:break-all;\" align=center><span  id=\"link".$count."\"  onMouseOver=\"displayAd($count," . $row["nTicketId"] . ");\" onMouseOut=\"hideAd();\" ><a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'><img src='./../images/ticketdetails.gif' border=0></a></span></td>";
                                                                echo "<td width='1%'  style=\"word-break:break-all;\" align=center><a id=\"".$row["nTicketId"]."x".$row["nUserId"]."\" href=\"#\" class='tooltip'><img src='./../images/ticketdetails.gif' border=0></a></td>";
                                                                echo "<td width='13%'><a href=\"viewticket.php?limitval=$limitstart&stat=new&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) ."<br>".$lastanswerd. "</a></td>";
                                                                break;
                                                        case "vTitle":
                                                                echo "<td width='30%' style=\"word-break:break-all;\"><div style=\"\"><span style=\"width:100%;height:100%;\" id=\"link".$count."\" ><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) ."<br>".$arrayDeptName[$row["nDeptId"]]. "</a></span></div></td>";
//													  	echo "<td width=48%><a href=\"viewticket.php?limitval=$limitstart&stat=new&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'><span style=\"width:100%;height:100%;\" id=\"link".$count."\">" .  htmlentities($row[($fld_arr[$i][0])]) ."</span><br>".$arrayDeptName[$row["nDeptId"]]. "</a></td>";
                                                                break;
                                                    default:
                                                                echo "<td width=10%><a href=\"viewticket.php?limitval=$limitstart&stat=new&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) . "</a></td>";
                                                                break;
                                                 }
                                                  ?>
                                                <?php
                                                 }
                                                ?>
                                                <td align="left"  width="6%">
                                                <?php
                                                        //First parameter is the response time for the department which is taken from the array
                                                        //populated with deptId -->  response time
                                                        //Second parameter is the time stamp same for all the ten records shown in this page
                                                        //Third parameter is the ticket post date.
                                                        if($row["vStatus"] == "open") {
                                                                echo("<a href=\"viewticket.php?limitval=$limitstart&stat=new&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . getResponseTime($arrayDept[$row["nDeptId"]],$var_time,strtotime($row["dLastAttempted"])) . "</a>");
                                                        }
                                                ?>
                                                </td>
                                            </tr>
<?php
	$cntr++;
	$count++;
	$newcount++;
	}
}
mysql_free_result($rs);
if($newTkts > 0)
{
?>
<tr align="left">
<td colspan="4"><a href="javascript:deleteTickets(1,<?php echo $var_maxposts;?>);" ><?php echo TEXT_DELTE_TICKETS; ?></a></td>
<td  height="1" colspan="<?php echo $cnt - 3; ?>" align=right><A href="newtickets.php?cmbDepartment=<?php echo($var_deptid); ?>"><?php echo TEXT_MORE;?>...</A></td>
</tr>
<?php 
} 
?>
										  </table>
										  
</div>
										  
										
										  
												
<div class="content_section">

                 




			  
			 
<?php 
	if($var_deptid != ""){
		$sql = "Select vDeptDesc from sptbl_depts where nDeptId='$var_deptid'";
		$rs_dept = executeSelect($sql,$conn);
		if(mysql_num_rows($rs_dept) > 0) {
			while($row = mysql_fetch_array($rs_dept)) {
				$dept_name = htmlentities($row["vDeptDesc"]);
			}	
		}
		mysql_free_result($rs_dept);
	}else
		$dept_name = TEXT_DEPARTMENT_FILTER;
?>
                 
<div class="content_section_title">
<h4><?php echo HEADING_TICKETS_ASSIGNED." ( ".$dept_name." )"; ?></h4></div>
				 
			      
<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
                                              <tr align="left">
                                                <th width="3%" align="center"><input name="checkall" id="checkall" type="checkbox"  onclick="checkallfn(<?php echo $var_maxposts;?>)">
                                                </th>
                                                <th width="15%" align="center" ><?php echo TEXT_FOLLOW; ?></th>
                                                <th width="4%">&nbsp;</th>
                                                <?php
                                                        $cnt = 0;
                                                while($cnt < count($fld_arr)) {
                                                /*													 if($var_orderby == $fld_arr[$cnt][0])
                                                                $img_path = "<img src=./../images/".$var_filename.">";
                                                         else
                                                                $img_path = "";
                                                */?>
                                                <th align="left"><?php echo constant($fld_arr[$cnt][1]); ?></th>
                                                <?php
                                                        $cnt++;
                                                }
                                                //$cnt++;
                                                $cnt += 2;
                                           ?>
                                           <th align="left" width="15%" ><?php echo TEXT_DUE ?></th>

                                    </tr>
<?php
$sql = "Select t.*,rp.nStaffId,rp.nUserId as rpuserid from sptbl_tickets t  left join sptbl_replies rp on (rp.dDate=t.dLastAttempted and rp.nTicketId=t.nTicketId)  where t.vDelStatus='0' AND t.nOwner='$var_staffid' AND t.vStatus!='closed'";
/*
$qryopt="";

if($var_deptid != ""){
		$arr_dept =	explode(",",$lst_dept);
		$pflag = false;
		for($i=0;$i<count($arr_dept);$i++) {
			if ($var_deptid == $arr_dept[$i]) {
				$pflag = true;
				break;
			}
		}
		if ($pflag == true) {
			$qryopt .= " AND t.nDeptId = '" . addslashes($var_deptid) . "' ";
		}
		else {
			$qryopt .= " AND t.nDeptId IN($lst_dept) ";
		}
}
else {
	$qryopt .= " AND t.nDeptId IN($lst_dept) ";
}
*/
//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;

$_SESSION["sess_backreplyurl"]="";
$var_back="./staffmain.php?begin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&cmbDepartment=" . urlencode($var_deptid) . "&";
$_SESSION["sess_ticketbackurl"] = $var_back;
$sql .= $qryopt . " Order By t.dLastAttempted DESC ";
//$sql .= $qryopt . " Order By t." . $var_orderby . " ".$var_sorttype." Limit 0,5 ";

$_SESSION['next_sql'] = $sql;
$sql .= " Limit 0,5 ";
//echo $sql;
$rs = executeSelect($sql,$conn);
$var_time=time();
$cntr = 1;
//$count=0;
$tskToMe = mysql_num_rows($rs);
if (mysql_num_rows($rs) > 0) {

// it is for next and previous ticket
	$startvalue=0;
	if($begin=="" and $numBegin==""){
		$startvalue=0;
	}else if($begin==""){
		$startvalue=(($numBegin-1)*10);
	}else{
		$startvalue=$begin;
	}
	$newcount=0;
/////

	while($row = mysql_fetch_array($rs)) {
	    $limitstart=$startvalue+$newcount;
        
		$lastanswerd=TEXT_LA;
		if($row['nStaffId']>0){
		  $lastanswerd=TEXT_LAS;
		}else if($row['rpuserid']>0){
		  $lastanswerd=TEXT_LAU;
		}
                
                $Viewersarray = explode(',',$row["vViewers"]);
                if(in_array($var_staffid, $Viewersarray)){
                    $viewedClass = "class='readTK'";
                }else{
                    $viewedClass = "class='unreadTK'";
                }


?>

                                              <tr align="left"  <?php echo $viewedClass;?>>
                                                <td align="center">
                                                        <input type="checkbox" name="chkDeleteTickets[]" id="chkDeleteTickets<?php echo($cntr);?>" value="<?php echo($row["nTicketId"]); ?>">
                                                </td>
                                                <td align="center">
                                                        <img class="imgFollow" name="imgFollow[]" id="<?php echo($row["nTicketId"]); ?>"   border="0" src="./../images/star-grey.png">
                                                 </td>
                                                <?php

                                                $sql = "Select * from sptbl_priorities where nPriorityValue='" . addslashes($row["vPriority"]) . "'";
                                                $res_prior_feacher = executeSelect($sql, $conn);
                                                if (mysql_num_rows($res_prior_feacher) > 0) {
                                                    $row_prior_feacher = mysql_fetch_array($res_prior_feacher);
                                                    $ticketColor = $row_prior_feacher["vTicketColor"];
                                                    $prioritieIcon = $row_prior_feacher["vPrioritie_icon"];
                                                }

                                                if ($prioritieIcon != "") {

                                                    $filePath = "../ticketPriorLogo/" . $prioritieIcon;
                                                } else {
                                                    $filePath = "../ticketPriorLogo/noicon.jpg";
                                                }

                                                for($i=0;$i < count($fld_arr);$i++) {
                                                    switch($fld_arr[$i][0]) {

                                                        case "vPriority":
                                                                         for($j=0;$j < count($fld_prio);$j++) {
                                                                                //echo($fld_prio[$j][0] . " and " . $row[($fld_arr[$i][0])] . " and " . $fld_prio[$j][2]);
                                                                                if ($fld_prio[$j][0] == $row[($fld_arr[$i][0])]) {
                                                                                        echo ("<td align=\"center\" width=10% class=\"subtbl\" style='background-color:$ticketColor;'><table width=\"100%\" cellpadding=\"0\" border=\"0\" class='innertable1' ><tr><td class='user_priority_icon'  align='left'><img src='$filePath'></td>
                                                                                                <td bgcolor=" . $fld_prio[$j][1] . " align='center'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class=''>" . $fld_prio[$j][2] . "</a></td></tr></table></td>");
                                                                                }
                                                                         }
                                                                         break;
                                                         case "dPostDate":
                                                                         echo "<td width='12%'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . date("m-d-Y",strtotime($row[($fld_arr[$i][0])])) . "</a></td>";
                                                                         break;
                                                         case "nLockStatus":
                                                                         echo "<td width='4%'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . (($row[($fld_arr[$i][0])] == "1")?TEXT_LOCK_YES:TEXT_LOCK_NO) . "</a></td>";
                                                                         break;
                                                         case "vRefNo":
                                                                        //echo "<td width='1%'  style=\"word-break:break-all;\" align=center><span  id=\"link".$count."\"  onMouseOver=\"displayAd($count," . $row["nTicketId"] . ");\" onMouseOut=\"hideAd();\" ><a href=\"viewticket.php?mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'><img src='./../images/ticketdetails.gif' border=0></span></a></td>";
                                                                        echo "<td width='1%'  style=\"word-break:break-all;\" align=center><a id=\"".$row["nTicketId"]."x".$row["nUserId"]."\" href=\"#\" class='tooltip'><img src='./../images/ticketdetails.gif' border=0></a></td>";
                                                                        echo "<td width='13%'><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) ."<br>".$lastanswerd."</a></td>";
                                                                        break;
                                                        case "vTitle":
                                                                        echo "<td width=30%  style=\"word-break:break-all;\"><div style=\"\"><span style=\"width:100%;height:100%;\"><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) .	"</span><br>".$arrayDeptName[$row["nDeptId"]]. "</a></div></td>";
                                                                        break;
                                                          default:
                                                                        echo "<td width=10%><a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row[($fld_arr[$i][0])]) . "</a></td>";
                                                                        break;
                                                 }
                                                  ?>
                                                <?php

                                                 }
                                                ?>
                                                <td align="left"  width="6%">
                                                <?php
                                                        //First parameter is the response time for the department which is taken from the array
                                                        //populated with deptId -->  response time
                                                        //Second parameter is the time stamp same for all the ten records shown in this page
                                                        //Third parameter is the ticket post date.
                                                        if($row["vStatus"] != "closed") {
                                                                echo("<a href=\"viewticket.php?limitval=$limitstart&mt=y&tk=" . $row["nTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" . getResponseTime($arrayDept[$row["nDeptId"]],$var_time,strtotime($row["dLastAttempted"])) . "</a>");
                                                        }
                                                ?>
                                                </td>
                                            </tr>
<?php
	$cntr++;
	$count++;
	$newcount++;
	}
}
mysql_free_result($rs);
if ($tskToMe > 0)
{
?>
<tr align="left">
<td colspan="4"><a href="javascript:deleteTickets(0,<?php echo $var_maxposts;?>);"  ><?php echo TEXT_DELTE_TICKETS; ?></a></td>
<td colspan="<?php echo $cnt - 3; ?>" align=right  height="1"><a href="assignedtickets.php?cmbDepartment=<?php echo($var_deptid); ?>"><?php echo TEXT_MORE;?>...</a></td>
</tr>
<?php } ?>
										  </table>				  
				  
				  
				  
				  
               


</div>