<?php
require_once("../includes/decode.php");
if(!isValid(1)) {
    echo("<script>window.location.href='../invalidkey.php'</script>");
    exit();
}

//warning message before 10 days
if($glob_date_check == "Y") {
    echo("<script>alert('" . MESSAGE_LICENCE_EXPIRE . $glob_date_days . MESSAGE_LICENSE_DAYS . "');</script>");
}
//end warning

$var_staffid = $_SESSION["sess_staffid"];
$fld_arr = $_SESSION["sess_fieldlist"];
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

if($_POST["cmbDepartment"] != "") {
    $var_deptid = $_POST["cmbDepartment"];
}else if($_GET["cmbDepartment"] != "") {
    $var_deptid = $_GET["cmbDepartment"];
}

//Block - I (populate the allowed departments for the user)
/*	$lst_dept = "'',";
	$sql = "Select nDeptId from sptbl_staffdept where nStaffId='$var_staffid'";
	$rs_dept = executeSelect($sql,$conn);
	if (mysql_num_rows($rs_dept) > 0) {
		while($row = mysql_fetch_array($rs_dept)) {
			$lst_dept .= $row["nDeptId"] . ",";
		}
	}
	$lst_dept = substr($lst_dept,0,-1);

	mysql_free_result($rs_dept);
*/
//End Of Block - I
$arrayDept=array();
$arrayDeptName=array();
$sql = "Select nDeptId,nResponseTime,vDeptDesc from sptbl_depts";
$result = mysql_query($sql) or die(mysql_error());
if(mysql_num_rows($result) > 0) {
    while($row = mysql_fetch_array($result)) {
        $arrayDept[$row["nDeptId"]] = $row["nResponseTime"];
        $arrayDeptName[$row["nDeptId"]]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
        ///$arrayDept[$row["nDeptId"]['name']]=TEXT_DEPT1.$row["vDeptDesc"].TEXT_DEPT2;
        //echo "sss<br>".$row["nDeptId"].$arrayDept[$row["nDeptId"]['name']];
    }
}

//echo "name==".$arrayDeptName[1];

//Delete ticket section
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
    $sqlFilter = "Select * from sptbl_lookup where vLookUpName ='spamfiltertype'";
    $resultFilter = executeSelect($sqlFilter,$conn);
    $rowFilterType = mysql_fetch_array($resultFilter);
    $filtertype=$rowFilterType['vLookUpValue'];


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
            $_REQUEST['cat']='notspam';
            $_REQUEST['docid']="notspamticket_".time().$row_spam['nSpamTicketId'];

            if($filtertype=="SUBJECT") {
                $_REQUEST['document']=$row_spam['vTitle'];
            }else if($filtertype=="BODY") {
                $_REQUEST['document']= $row_spam['tQuestion'];
            }else if($filtertype=="BOTH") {
                $_REQUEST['document']=$row_spam['vTitle'] ." ".$row_spam['tQuestion'];
            }
            train();

            $dpostdate = $row_spam['dPostDate'];

            $nticketid = $row_spam['nTicketID'];
            $sql = "Update sptbl_tickets set vDelStatus = 0 where nTicketId IN ($nticketid)";
            $result = executeQuery($sql,$conn);

            /*$_REQUEST['cat']='notspam';
			     $_REQUEST['docid']="notspamticket_".$row_spam['nSpamTicketId'];
			     $_REQUEST['document']=$row_spam['tQuestion'].$row_spam['vTitle'];
			     train();*/
            //$emailmarkedasspam=$row_spam['tcontent'];
            $emailmarkedasspam=$row_spam['nSpamTicketId'];
            if($emailmarkedasspam != "") {
                require("../parser/spamparser.php");
                $message = TEXT_SPAM_REASSIGN;
                $flag_msg = "class='msg_success'";
            }else
                $message = TEXT_SPAM_CANNOT_REASSIGN;
            $flag_msg = "class='msg_error'";
            
            if($emailmarkedasspam != "") {
                $qry="delete  from sptbl_spam_tickets where nSpamTicketId in($var_list)";
                mysql_query($qry);
                
                $sql = "Update sptbl_tickets set vDelStatus = 0 where nTicketId = ".$row_spam['nTicketID'];
                mysql_query($sql);
            }            
            
        }
    }

}

$sql = "Select nSpamTicketId,nDeptId,vTitle,tQuestion,date_format(dPostDate,'%m-%d-%Y') postedDate from sptbl_spam_tickets where 1=1 ";
$qryopt="";
if($var_deptid != "") {
    $qryopt .= " AND nDeptId = '" . mysql_real_escape_string($var_deptid) . "' ";
}

$_SESSION["sess_abackreplyurl"]="";
$var_back="./spamtickets.php?mt=y&stylename=STYLETICKETS&styleminus=minus1&styleplus=plus1&tp=$var_type&begin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&cmbDepartment=" . urlencode($var_deptid) . "&";
$_SESSION["sess_spamticketbackurl"] = $var_back;

$sql .= $qryopt . " Order By dPostDate DESC ";

$var_time=time();
?>
<div class="content_section">
    <form name="frmDetail" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

        <div class="content_search_container">
            <div class="left rightmargin topmargin">
                <select name="cmbDepartment" class="comm_input input_width1" onChange="javascript:changeDepartment();">
                        <option value=""><?php echo TEXT_DEPARTMENT_FILTER ?></option>
                        <?php
                        $sql2 = "Select d.nDeptId,CONCAT(CONCAT(CONCAT('[',d.vDeptCode),']'),CONCAT(d.vDeptDesc,CONCAT(CONCAT('(',c.vCompName),')'))) as 'description' from
                                sptbl_depts d  inner join sptbl_companies c
                                 on d.nCompId = c.nCompId  ";
                        $lst_dept_opt = "";
                        $rs_dept = executeSelect($sql2,$conn);
                        if (mysql_num_rows($rs_dept) > 0) {
                            while($row = mysql_fetch_array($rs_dept)) {
                                $lst_dept_opt .= "<option value=\"" . $row["nDeptId"] . "\" >" . htmlentities($row["description"]) . "</option>";
                            }
                        }
                        echo($lst_dept_opt);
                        ?>
                    </select>
            </div>

            <div class="clear"></div>
        </div>

        <Div class="content_section_title"><h4><?php echo TEXT_SPAM_TICKETS ?></h4></Div>

        <div style="overflow:auto">





            <table width="100%"  border="0" cellpadding="2" cellspacing="0" class="list_tbl">

                <?php
                if($_POST["del"] == "DM" or $_POST["del"] == "NS") {
                    echo("<tr><td align=\"center\"  class=\"errormessage\" colspan=5><div ".$flag_msg.">" . $message . "</div></td></tr>");
                }
                ?>
                <tr align="left"  class="listing">
                    <th width="194" ><input name="checkall" id="checkall" type=checkbox class=checkbox onclick="checkallfn()">
                    </th>
                    <th width="477">
                        <?php echo "<b>".TEXT_SPAM_TICKETS_TITLE."</b>";
                        ;?>
                    </th>
                    <th width="263">
                        <?php echo "<b>".TEXT_SPAM_TICKETS_DATE."</b>";?>
                    </th>
                </tr>
                <?php

//echo $sql;
//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
                $var_maxposts = (int)$_SESSION["sess_maxpostperpage"];
                $var_maxposts = ($var_maxposts < 1)?1:$var_maxposts;

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

//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
                $navigate = pageBrowser($totalrows,10,$var_maxposts,"&mt=y&tp=$var_type&cmbDepartment=$var_deptid&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
                $sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
                $cntr = 1;
                $rs = executeSelect($sql,$conn);
                while($row = mysql_fetch_array($rs)) {

                    ?>

                <tr align="left"  class="whitebasic" >
                    <td width="194" style="text-align:center;">
                        <input type="checkbox" name="chkDeleteTickets[]" id="chkDeleteTickets<?php echo($cntr);?>" value="<?php echo($row["nSpamTicketId"]); ?>" class="checkbox">
                    </td>

                        <?php
                        $spamDate =   $row['postedDate'];
                        echo "<td width='80%'><a href=\"spamdetails.php?mt=y&spamticketid=" . $row["nSpamTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .  htmlentities($row['vTitle']) ."</a></td>";
                        echo "<td width='20%'><a href=\"spamdetails.php?mt=y&spamticketid=" . $row["nSpamTicketId"] . "&us=" . $row["nUserId"] . "&stylename=$var_stylename&styleminus=$var_styleminus&styleplus=$var_styleplus\" class='listing'>" .$spamDate."</a></td>";
                        ?>


                </tr>
                    <?php
                    $cntr++;
                }
                mysql_free_result($rs);
                ?>
                <tr align="left"  class="whitebasic">
                    <td colspan="3">
                        <table  cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr class="whitebasic">
                                <td colspan="2" align="left">
                                    <br>
                                    <?php if($totalrows <> 0) {?>
                                    <a href="javascript:deleteTickets(0);"  class="listing" style="text-decoration:none;"><?php echo TEXT_DELTE_TICKETS; ?></a>
                                    &nbsp;&nbsp;<a href="javascript:notspamTickets(0);"  class="listing" style="text-decoration:none;"><?php echo TEXT_MARKS_AS_NOTSPAM_TICKETS; ?></a>
                                    <br>&nbsp;
                                        <?php }?>
                                </td>


                            </tr>
                            <tr align="left">
                                <td colspan="6" class="subtbl">

                                    <div class="content_section_data">
                                        <div class="pagination_container">
                                            <div class="pagination_info">
                                            <?php
                                            if($totalrows > 0){
                                            echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS );
                                            }
                                            ?>
                                            </div>
                                            <div class="pagination_links">
                                                <?php echo($navigate[2]); ?>
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
                                            <div class="clear">
                                            </div>
                                        </div>

                                </td>
                            </tr>
                        </table>
                    </td>

            </table>
        </div>

    </form>
</div>