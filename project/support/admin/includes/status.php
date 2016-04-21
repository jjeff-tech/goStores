<?php
$var_userid = $_SESSION["sess_staffid"];
$flag_msg = "";

if ($_POST["postback"] == "D") {
    if (validateDeletion(mysql_real_escape_string($_POST["id"]),0)) {
        $sql = "delete from  sptbl_lookup  where vLookUpValue='" . mysql_real_escape_string($_POST["id"]) . "' and vLookUpName='ExtraStatus'";
        executeQuery($sql,$conn);
        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Lookup/ExtraStatus','" . mysql_real_escape_string($_POST["id"]) . "',now())";
            executeQuery($sql,$conn);
        }
        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg = "class='msg_success'";
    }
    else {
        $var_message = MESSAGE_RECORD_ERROR;
        $flag_msg = "class='msg_error'";
    }
}
elseif ($_POST["postback"] == "DA") {
    $var_list = "";
    for($i=0;$i<count($_POST["chk"]);$i++) {
        if($_POST["chk"][$i]!="en") {
            $var_list .= "'" . mysql_real_escape_string($_POST["chk"][$i]) . "',";
        }
    }
    $var_list = substr($var_list,0,-1);

    if (validateDeletion($var_list,1) == true and $var_list!="") {


        $sql = "delete from  sptbl_lookup where vLookUpName='ExtraStatus' and vLookUpValue  IN(" . $var_list . ")";
        executeQuery($sql,$conn);
        //Insert the actionlog
        if(logActivity()) {
            for($i=0;$i<count($_POST["chk"]);$i++) {
                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Lookup/ExtraStatus','" . mysql_real_escape_string($_POST["chk"][$i]) . "',now())";
                executeQuery($sql,$conn);
            }
        }

        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg = "class='msg_success'";


    }else {
        $var_message = MESSAGE_RECORD_ERROR;
        $flag_msg = "class='msg_error'";
    }

}elseif ($_POST["postback"] == "A") {

    if (validateAddition(mysql_real_escape_string($_POST["txtExtraStatus"]))) {

        $sql = "Insert into sptbl_lookup(nLookUpId,vLookUpName,vLookUpValue) values('','ExtraStatus','".mysql_real_escape_string($_POST["txtExtraStatus"])."')";
        executeQuery($sql,$conn);
        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Lookup/ExtraStatus','" . mysql_real_escape_string($_POST["txtExtraStatus"]) . "',now())";
            executeQuery($sql,$conn);
        }

        $var_message = MESSAGE_RECORD_ADDED;
        $flag_msg = "class='msg_success'";

    }else {

        $var_message = MESSAGE_STATUS_ABORTED;
        $flag_msg = "class='msg_error'";
    }

}







function validateDeletion($var_list,$fl) {
    global $conn;
    if($fl=="1")
        $sql_del_st="select nTicketId from sptbl_tickets where vStatus in($var_list)";
    else
        $sql_del_st="select nTicketId from sptbl_tickets where vStatus in('$var_list')";

    $var_result = executeSelect($sql_del_st,$conn);
    if (mysql_num_rows($var_result) > 0) {
        return false;
    }else {
        return true;
    }
}

function validateAddition($var_list) {
    global $conn;
    $arr_check = array("open","closed","escalated");
    if(in_array(strtolower(trim($var_list)),$arr_check) == true) {
        return false;
    }
    $sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='ExtraStatus' and vLookUpValue='$var_list'";
    $var_result = executeSelect($sql,$conn);
    if (mysql_num_rows($var_result) > 0) {
        return false;
    }elseif(isValidStatus($var_list)) {
        return true;
    }
}

if($_GET["mt"] == "y") {

    $var_numBegin = $_GET["numBegin"];
    $var_start = $_GET["start"];
    $var_begin = $_GET["begin"];
    $var_num = $_GET["num"];
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
    $var_numBegin = $_POST["numBegin"];
    $var_start = $_POST["start"];
    $var_begin = $_POST["begin"];
    $var_num = $_POST["num"];
    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}else {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
}

$sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='ExtraStatus' ";


$qryopt="";
if($_POST["txtSearch"] != "") {
    $var_search = $_POST["txtSearch"];
}else if($_GET["txtSearch"] != "") {
    $var_search = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != "") {
    $var_cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != "") {
    $var_cmbSearch = $_GET["cmbSearch"];
}

if($var_search != "" and $var_cmbSearch == "status" ) {

    $qryopt .= " and vLookUpValue like '" . mysql_real_escape_string($var_search) . "%'";

}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By vLookUpValue";

?>
<div class="content_section">
    <form name="frmExtraStatus" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">
        <Div class="content_section_title"><h3><?php echo TEXT_STATUS_DETAILS ?></h3></Div>

        <div class="content_search_container">
            <div class="left rightmargin topmargin">
                <?php echo TEXT_SEARCH ?>
            </div>
            <div class="left rightmargin">
                <select name="cmbSearch" class="comm_input1b input_width1">
                    <option value="status" <?php echo(($var_cmbSearch == "status" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_STATUS_TYPE?></option>
                </select>
            </div>
             <div class="left rightmargin">
                <input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="comm_input1btxt input_width1" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px">
                </div>
            <div class="left">
                <a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
            </div><div class="clear"></div></div>

        <div <?php echo $flag_msg; ?>><?php echo($var_message); ?></div>




        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
            <tr align="left"  class="listing">
                <th width="4%">&nbsp;</th>
                <th><?php echo "<b>".TEXT_TICKET_STATUS."</b>"; ?></th>
                <th width="15%" align="center"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
            </tr>
            <?php


//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
            $totalrows = mysql_num_rows(executeSelect($sql,$conn));
            settype($totalrows,integer);
            settype($var_begin,integer);
            settype($var_num,integer);
            settype($var_numBegin,integer);
            settype($var_start,integer);

            $var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
            if(($totalrows <= $var_calc_begin)) {
                $var_nor = 10;
                $var_nol = 10;
                if ($totalrows <= 0) {
                    $var_num = 0;
                    $var_numBegin = 0;
                    $var_begin = 0;
                    $var_start="";
                }
                elseif ($var_num > $var_numBegin) {
                    $var_num = $var_num - 1;
                    $var_numBegin = $var_numBegin;
                    $var_begin = $var_begin - $var_nor;
                }
                elseif ($var_num == $var_numBegin) {
                    $var_num = $var_num - 1;
                    $var_numBegin = $var_numBegin - $var_nol;
                    $var_begin = $var_calc_begin - $var_nor;
                    $var_start="";
                }
            }

//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
            $navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
            $sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
            $rs = executeSelect($sql,$conn);
            $cnt = 1;
            while($row = mysql_fetch_array($rs)) {
                ?>

            <tr align="left"  class="listingmaintext"><td align="center"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["vLookUpValue"]); ?>" class="checkbox" >
                <td width="81%"><?php echo htmlentities(trim_the_string($row["vLookUpValue"])); ?></td>
                <td width="15%"  align="center"><a href="javascript:deleted('<?php echo mysql_real_escape_string($row["vLookUpValue"]); ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_DELETE_STATUS?>"></a></td>
            </tr>
                <?php
                $cnt++;
            }
            mysql_free_result($rs);
            ?>
            <tr align="left"  class="listingmaintext">
                <td colspan="5">

                    <div class="content_section_data">
                        <div class="pagination_container">
                            <div class="pagination_info">
                            <?php
                            if($totalrows > 0){
                                echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS );
                            }
                            ?>
                            </div>
                            <div class="pagination_links"><?php echo($navigate[2]); ?>
                                <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
                                <input type="hidden" name="start" value="<?php echo   $var_start?>">
                                <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
                                <input type="hidden" name="num" value="<?php echo   $var_num?>">
                                <input type="hidden" name="mt" value="y">
                                <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                <input type="hidden" name="postback" value="">
                                <input type="hidden" name="id" value="">
                            </div>
                            <div class="clear">
                            </div>
                        </div>

                </td>
            </tr>
        </table>





        <table width="100%"  border="0" cellspacing="0" cellpadding="5">
            <tr>
                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                        <tr align="center" class="listingbtnbar">
                            <td>
                                <input name="btnDelete" type="button" class="button" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:clickDelete();">                                    </td></tr>
                    </table></td>
            </tr>
        </table>




        <br><br>



        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>

                    <Div class="content_section_title"><h3><?php echo TEXT_ADD_STATUS ?></h3></Div>


                    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
                        <tr align="center" class="whitebasic">
                            <td><br>
                                <input name="txtExtraStatus" id="txtExtraStatus" type="textbox" class="comm_input input_width1"  maxlength=100 size=25>
                                <br>&nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr align="center" class="listingbtnbar">
                <td>
                    <input name="btnAdd" type="button" class="button" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick=javascript:clickAdd()  >
                </td>
            </tr>
        </table>



    </form>
</div>