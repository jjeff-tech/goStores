<?php

$var_staffid=$_SESSION["sess_staffid"];
if ($_POST["postback"] == "D") {
    if (validateDeletion(mysql_real_escape_string($_POST["id"])) == true) {
        $sqlUpdate = "UPDATE sptbl_tickets SET nLabelId=0 WHERE nLabelId IN(" . $_POST["id"] . ")";
        executeQuery($sqlUpdate, $conn);

        $sql = "delete from  sptbl_labels  where nLabelId  ='" . mysql_real_escape_string($_POST["id"]) . "'";
        executeQuery($sql,$conn);

        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Labels','" . mysql_real_escape_string($_POST["id"]) . "',now())";
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
        $sql = "delete from  sptbl_labels  where nLabelId IN(" . $var_list . ")";
        executeQuery($sql,$conn);

        //Insert the actionlog
        if(logActivity()) {
            for($i=0;$i<count($_POST["chk"]);$i++) {
                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Labels','" . mysql_real_escape_string($_POST["chk"][$i]) . "',now())";
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
$sql="select sl.nLabelId,sl.vLabelname,sl.nStaffId,sf.vLogin from sptbl_labels sl ";
$sql .= "LEFT OUTER JOIN sptbl_staffs sf ON sl.nStaffId = sf.nStaffId";

$qryopt=" WHERE 1 ";
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
        $qryopt .= " AND sl.vLabelname like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "user") {
        $qryopt .= " AND sf.vLogin  like '" . mysql_real_escape_string($var_search) . "%'";
    }
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By sl.vLabelname Asc ";
?>

<form name="frmLabel" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">
    <div class="content_section">
        <div class="content_section_title">
            <h3><?php echo HEADING_LABEL_DETAILS ?></h3>
        </div>

        <div class="content_search_container">

            <div class="left rightmargin topmargin">
                <?php echo   TEXT_SEARCH?>
            </div>
            <div class="left rightmargin">

                <select name="cmbSearch" class="comm_input input_width1" style="height:29px!important; float:left; ">
                    <option value="title" <?php echo(($var_cmbSearch == "title" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_TITLE ?></option>
                    <option value="user" <?php echo(($var_cmbSearch == "user" )?"Selected":""); ?>><?php echo TEXT_USER ?></option>
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
        <?php

        if ($var_message != "") {
            ?>
        <div <?php echo $flag_msg; ?>>
                <?php echo($var_message); ?>
        </div>
            <?php
        }
        ?>

        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
            <tr align="left"  class="listing">
                <th width="6%">&nbsp;</th>
                <th width="46%"><?php echo "<b>".TEXT_TITLE."</b>"; ?></th>
                <th width="37%"><?php echo "<b>".TEXT_USER."</b>"; ?></th>
                <th colspan="2" align="center"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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
            $navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLETEMPLATES&styleminus=minus19&styleplus=plus19&",$var_numBegin,$var_start,$var_begin,$var_num);

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

            <tr align="left" class="whitebasic">
                <td align="center"><input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nLabelId"]); ?>" class="checkbox"></td>
                <td><a href="labeltickets.php?id=<?php echo $row["nLabelId"]; ?>&label=<?PHP echo htmlentities(trim_the_string($row["vLabelname"])); ?>&stylename=STYLELABELS&styleminus=minus11&styleplus=plus11&" class="listing"><?PHP echo htmlentities(trim_the_string($row["vLabelname"])); ?></a></td>
                <td><?php echo($row["vLogin"] == "")?"Administrator":$row['vLogin']; ?></td>
                <td width="6%" align="center"><a href="editlabel.php?id=<?php echo $row["nLabelId"]; ?>&stylename=STYLELABELS&styleminus=minus22&styleplus=plus22&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_EDIT; ?>"></a></td>
                <td width="5%" align="center"><a href="javascript:deleted('<?php echo $row["nLabelId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_TITLE_DELETE; ?>"></a></td>
            </tr>
                <?php
                $cnt++;
            }
            mysql_free_result($rs);
            ?>
            <tr align="left"  class="whitebasic">
                <td colspan="6" class="subtbl">
                    <div class="content_section_data">
                        <div class="pagination_info">
                            <?php
                            if($totalrows > 0) {
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
            </tr>
        </table>




        <table width="100%"  border="0" cellspacing="0" cellpadding="0"  class="comm_tbl">
            <tr align="center">
                <td>
                    <input name="btnDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:clickDelete();">                                    </td></tr>
        </table>

    </div>
</form>