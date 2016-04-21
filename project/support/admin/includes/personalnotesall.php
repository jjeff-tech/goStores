<?php

$var_staffid = $_SESSION["sess_staffid"];

if ($_POST["postback"] == "D") {
    if (validateDeletion(mysql_real_escape_string($_POST["id"])) == true) {
        $sql = "delete from  sptbl_personalnotes   where nPNId='" . mysql_real_escape_string($_POST["id"]) . "'";
        executeQuery($sql,$conn);
        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Personal Notes','" . mysql_real_escape_string($_POST["id"]) . "',now())";
            executeQuery($sql,$conn);
        }
        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg    = 'class="msg_success"';
    }
    else {
        $var_message = MESSAGE_RECORD_ERROR ;
        $flag_msg    = 'class="msg_error"';
    }
}
elseif ($_POST["postback"] == "DA") {
    $var_list = "";
    for($i=0;$i<count($_POST["chk"]);$i++) {
        $var_list .= "'" . mysql_real_escape_string($_POST["chk"][$i]) . "',";
    }
    $var_list = substr($var_list,0,-1);

    if (validateDeletion($var_list) == true) {
        $sql = "delete from  sptbl_personalnotes   where nPNId  IN(" . $var_list . ")";
        executeQuery($sql,$conn);


        //Insert the actionlog
        if(logActivity()) {
            for($i=0;$i<count($_POST["chk"]);$i++) {
                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Personal Notes','" . mysql_real_escape_string($_POST["chk"][$i]) . "',now())";
                executeQuery($sql,$conn);
            }
        }
        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg    = 'class="msg_success"';
    }
    else {
        $var_message = MESSAGE_RECORD_ERROR ;
        $flag_msg    = 'class="msg_error"';
    }
}


function validateDeletion($var_list) {

    return true;
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

$sql = "Select p.nPNId,p.nStaffId,p.nTicketId,p.vPNTitle,p.tPNDesc,p.dDate,t.vRefNo,s.vLogin from sptbl_personalnotes as p,";
$sql .=" sptbl_tickets as t ,sptbl_staffs as s";
$sql .=" where p.nTicketId=t.nTicketId and p.nStaffId=s.nStaffId ";

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

if($var_search != "") {
    if($var_cmbSearch == "title") {
        $qryopt .= " AND p.vPNTitle like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "refno") {
        $qryopt .= " AND t.vRefNo like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "staff") {
        $qryopt .= " AND s.vLogin like '" . mysql_real_escape_string($var_search) . "%'";
    }
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By p.vPNTitle desc,p.dDate DESC";

?>
<div class="content_section">
    <form name="frmPersonalNotes" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">



        <Div class="content_section_title"><h3><?php echo TEXT_PERSONAL_NOTES ?></h3></Div>
        <div class="content_search_container">
            <div class="left rightmargin topmargin">


                <?php echo   TEXT_SEARCH?>

            </div>
            <div class="left rightmargin">
                <select name="cmbSearch" class="comm_input input_width1" style="height:29px!important; float:left; ">
                    <option value="title" <?php echo(($var_cmbSearch == "title" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_TITLE ?></option>
                    <option value="refno" <?php echo(($var_cmbSearch == "refno")?"Selected":""); ?>><?php echo TEXT_REFNO ?></option>
                    <option value="staff" <?php echo(($var_cmbSearch == "staff")?"Selected":""); ?>><?php echo TEXT_STAFF ?></option>

                </select>
            </div>
            <div class="left">
                <input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="comm_input input_width1" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px; height:15px!important; margin-right:5px;">
            </div>
            <div class="left">
                <a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
            </div>
            <div class="clear"></div>
        </div>

        <div <?php echo $flag_msg; ?>><?php echo($var_message); ?></div>




        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl"  >
            <tr align="left"  class="listing">
                <th width="5%">&nbsp;</td>
                <th width="33%" ><?php echo "<b>".TEXT_TITLE."</b>"; ?></th>
                <th width="18%"><?php echo "<b>".TEXT_REFNO."</b>"; ?></th>
                <th width="30%"><?php echo "<b>".TEXT_STAFF."</b>"; ?></th>
                <th colspan="2"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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
            $navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLEPERSONALNOTES&styleminus=minus16&styleplus=plus16&",$var_numBegin,$var_start,$var_begin,$var_num);

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

            <tr align="left"  class="whitebasic">
                <td align="center"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nPNId"]); ?>" class="checkbox"></td>
                <td><?PHP echo htmlentities(trim_the_string($row["vPNTitle"])); ?></td>
                <td><?php echo htmlentities(trim_the_string($row["vRefNo"])); ?></td>
                <td><?php echo htmlentities(trim_the_string($row["vLogin"])); ?></td>
                <td width="7%"><a href="editpersonalnotesall.php?id=<?php echo $row["nPNId"]; ?>&stylename=STYLEPERSONALNOTES&styleminus=minus16&styleplus=plus16&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_EDIT ?>"></a></td>
                <td width="7%"><a href="javascript:deleted('<?php echo $row["nPNId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_DELETE ?>"></a></td>
            </tr>
                <?php
                $cnt++;
            }
            mysql_free_result($rs);
            ?>
            <tr align="left">
                <td colspan="6" class="subtbl">

                    <div class="content_section_data">
                        <div class="pagination_container">
                            <div class="pagination_info">
                                <?php
                                if($totalrows > 0) {
                                    echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS );
                                }
                                ?>
                            </div>
                            <div class="pagination_links">
                                <?php echo($navigate[2]); ?>
                            </div>


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



        <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="comm_tbl">
            <tr align="center" >
                <td>
                    <input name="btnDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:clickDelete();">                                    </td></tr>
        </table>

    </form>
</div>