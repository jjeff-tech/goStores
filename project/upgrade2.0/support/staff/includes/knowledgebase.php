<?php
$var_staffid = $_SESSION["sess_staffid"];
$txtSearch = "";
if ($_GET["mt"] == "y") {
    $numBegin = $_GET["numBegin"];
    $start = $_GET["start"];
    $begin = $_GET["begin"];
    $num = $_GET["num"];
    $styleminus = $_GET["styleminus"];
    $stylename = $_GET["stylename"];
    $styleplus = $_GET["styleplus"];
} else if ($_POST["mt"] == "y") {
    $numBegin = $_POST["numBegin"];
    $start = $_POST["start"];
    $begin = $_POST["begin"];
    $num = $_POST["num"];
    $styleminus = $_POST["styleminus"];
    $stylename = $_POST["stylename"];
    $styleplus = $_POST["styleplus"];
}
if ($_POST["ddlCategory"] == "") {
    $ddlCategory = $_GET["ddlCategory"];
    $ddlDepartment = $_GET["ddlDepartment"];
} else {
    $ddlCategory = $_POST["ddlCategory"];
    $ddlDepartment = $_POST["ddlDepartment"];
}

//echo "<br>Category: ". $ddlCategory;
//echo "<br>Dept: ". $ddlDepartment;
if ($_POST["postback"] == "Save Changes") {
    $error = false;
    $errormessage = "";
    if (isNotNull($_POST["txtName"])) {
        $name = $_POST["txtName"];
    } else {//user name null
        $error = true;
        $errormessage .= MESSAGE_NAME_REQUIRED . "<br>";
        $flag_msg = "class='msg_error'";
    }
    if (isNotNull($_POST["txtEmail"])) {
        $email = $_POST["txtEmail"];
        if (!isValidEmail($email)) {
            $error = true;
            $errormessage .= MESSAGE_INVALID_EMAIL . "<br>";
            $flag_msg = "class='msg_error'";
        }
    } else {//user Email null
        $error = true;
        $errormessage .= MESSAGE_EMAIL_REQUIRED . "<br>";
        $flag_msg = "class='msg_error'";
    }

    if ($error) {
        $errormessage = MESSAGE_ERRORS_FOUND . "<br>" . $errormessage;
        $flag_msg = "class='msg_error'";
    } else {//no error so validate
        $sql1 = " UPDATE sptbl_users  ";
        $sql1 .= " SET vUserName = '" . addslashes($name) . "', vEmail = '" . addslashes($email) . "' WHERE nUserId = '" . $_SESSION["sess_userid"] . "' ";
        $result1 = executeQuery($sql1, $conn);
        $message = true;
        $messagetext = MESSAGE_PROFILE_UPDATED_SUCCESSFULLY;
    }
} else if ($_POST["postback"] == "CD") {
    $ddlDepartment = $_POST["ddlDepartment"];
    $ddlCategory = 0;
    settype($ddlDepartment, integer);
    settype($ddlCategory, integer);
} else if ($_POST["postback"] == "CC") {
    $ddlCategory = $_POST["ddlCategory"];
    $ddlDepartment = $_POST["ddlDepartment"];
    settype($ddlDepartment, integer);
    settype($ddlCategory, integer);
} else if ($_POST["postback"] == "D") {
    deleteEntry(addslashes($_POST["id"]));
    $messagetext = MESSAGE_RECORD_DELETED;
    $ddlCategory = $_POST["ddlCategory"];
    $ddlDepartment = $_POST["ddlDepartment"];
    settype($ddlDepartment, integer);
    settype($ddlCategory, integer);
} elseif ($_POST["postback"] == "DA") {
    $list = "";
    for ($i = 0; $i < count($_POST["chk"]); $i++) {
        $list .= "'" . addslashes($_POST["chk"][$i]) . "',";
    }
    $list = substr($list, 0, -1);
    $arr = explode(",", $list);

    for ($i = 0; $i < count($arr); $i++) {
        deleteEntry($arr[$i]);
    }
    $messagetext = MESSAGE_RECORD_DELETED;
    $ddlCategory = $_POST["ddlCategory"];
    $ddlDepartment = $_POST["ddlDepartment"];
    settype($ddlDepartment, integer);
    settype($ddlCategory, integer);
} else if ($_POST["postback"] == "S") {
    $ddlCategory = $_POST["ddlCategory"];
    $ddlDepartment = $_POST["ddlDepartment"];
    settype($ddlDepartment, integer);
    settype($ddlCategory, integer);
    if ($_POST["txtSearch"] != "") {
        $txtSearch = $_POST["txtSearch"];
    } elseif ($_GET["txtSearch"] != "") {
        $txtSearch = $_GET["txtSearch"];
    }
    if ($_POST["cmbSearch"] != "") {
        $cmbSearch = $_POST["cmbSearch"];
    } else if ($_GET["cmbSearch"] != "") {
        $cmbSearch = $_GET["cmbSearch"];
    }
}

if ($_POST["txtSearch"] != "") {
    $txtSearch = $_POST["txtSearch"];
} elseif ($_GET["txtSearch"] != "") {
    $txtSearch = $_GET["txtSearch"];
}
if (!isNotNull($ddlCategory)) {
    $ddlCategory = "0";
}

$sql = " SELECT kb.nKBID, kb.vKBTitle, kb.nStaffId, d.nDeptId,d.vDeptDesc,d.vDeptMail,c.nCompId,c.vCompName, ca.vCatDesc, ca.nCatId ";
$sql .=" FROM  sptbl_kb kb INNER JOIN sptbl_categories  ca ON kb.nCatId = ca.nCatId ";
$sql .=" INNER JOIN sptbl_depts d ON d.nDeptId = ca.nDeptId ";
$sql .=" INNER JOIN sptbl_companies c ON c.nCompId = d.nCompId ";
//WHERE ( kb.nCatId = '101')  and (( kb.nStaffId = 7 ) or ( (kb.nStaffId <> 7) and ( vStatus = 'A')))
$sql .=" WHERE (kb.nCatId = '$ddlCategory') and (( kb.nStaffId = $var_staffid ) or (( kb.nStaffId <> $var_staffid)   and ( vStatus = 'A'))) ";

//echo "<br>".$sql;


$qryopt = "";


if ($txtSearch != "") {
    if ($cmbSearch == "title") {
        $qryopt .= " AND kb.vKBTitle like '" . addslashes($txtSearch) . "%'";
    }
}

//$sback="companies.php?mt=ybegin=" . $sbegin . "&num=" . $snum . "&numBegin=" . $snumBegin . "&start=$sstart&cmbSearch=" . $scmbSearch . "&txtSearch=" . urlencode($ssearch) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " ORDER BY kb.dDate DESC  ";
?>
<script>
    <!--

    function validateProfileForm(){
        var frm = window.document.frmKB;
        var errors="";
        if(frm.txtName.value.length == 0){
            errors += "<?php echo MESSAGE_NAME_REQUIRED; ?>"+ "\n";
        }
        if(frm.txtEmail.value.length == 0){
            errors += "<?php echo MESSAGE_EMAIL_REQUIRED; ?>"+ "\n";
        }else if(!isValidEmail(frm.txtEmail.value)){
            errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
        }
        if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors; 
            alert(errors);
            return false;
        }else{
            frm.postback.value = "Save Changes";
            frm.submit();
        }
    }
    function cancel(){
        ;
    }
    function changeDepartment(){
        document.frmKB.postback.value="CD";
        document.frmKB.method="post";
        document.frmKB.submit();
    }	
    function changeCategory(){
        document.frmKB.postback.value="CC";
        document.frmKB.method="post";
        document.frmKB.submit();
  
    }
    function clickDelete() {
        var i=1;
        var flag = false;
        for(i=1;i<=10;i++) {
            try{
                if(eval("document.getElementById('c" + i + "').checked") == true) {
                    flag = true;
                    break;
                }
            }catch(e) {}
        }
        if(flag == true) {
            if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                document.frmKB.postback.value="DA";
                document.frmKB.method="post";
                document.frmKB.submit();
            }	
        }
        else {
            alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
        }
    }


    function deleted(id) {
        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
            document.frmKB.id.value=id;
            document.frmKB.postback.value="D";
            document.frmKB.method="post";
            document.frmKB.submit();
        }	
    }
    -->
</script>
<div class="content_section">
    <form action="" method="post" name="frmKB">

        <div class="content_section_title">
            <h3><?php echo TEXT_KNOWLEDGEBASE; ?></h3>
        </div>
        <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->
<?php if ($error) { ?>
            <div class="msg_error"><?php echo $errormessage; ?></div>
    <?php
}
if ($messagetext) {
    ?>
            <div class="msg_success"><?php echo $messagetext; ?></div>
            <?php
        }
        ?>
        <!-- %%%%%%%%%%%%%%%%%%%%% Errors or Messages %%%%%%%%%%%%%%%%%% -->


        <table width="100%"  border="0" cellpadding="0" cellspacing="3" class="pagecolor">
            <tr align="center" class="pagecolor">
                <td class="maintext">
                    <!-- ##########################################- -->

                    <table width="100%"  border="0" align="center">
                        <tr>
                            <td colspan="3">


                            </td>
                        </tr>


                        <tr>
                            <td colspan="3">
                                <!-- &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& -->
                                <div class="content_search_container" style="height: 70px;">
                                    <div class="content_section_title" align="left">
                                        <h4><?php echo TEXT_SELECT_DEPARTMENT ?></h4>
                                    </div>


                                    <div class="left rightmargin">
                                   
<?php
echo makeDropDownList("ddlDepartment", makeStaffDepartmentList($_SESSION["sess_staffid"]), $ddlDepartment, true, "comm_input input_width1", "\" style=\"width:250px\" \"", "onChange=\"javascript:changeDepartment();\"");
?>																
                                    </div>

                                    
                                </div>
                                <br/>

                        <!-- <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                        <td width="5%" align="right"><img src="./../images/dot6.gif" width="15" height="15"></td>
                                        <td width="2%"  class="listingheadmid">&nbsp;</td>
                                        <td width="93%" class="listingheadright"><?php echo HEADING_KB_ENTRY_DETAILS ?></td>
                                </tr>
                        </table> -->



                                <div class="content_search_container" style="height: 70px;" >
                                    <div class="content_section_title" align="left"> 
                                        <h4><?php echo TEXT_SELECT_CATEGORY ?></h4>

                                    </div>

                                    <div class="left rightmargin">
<?php
echo makeDropDownList("ddlCategory", makeCategoryList(0, 0, $ddlDepartment), $ddlCategory, true, "comm_input input_width1", "\" style=\"width:250px\" \"", "onChange=\"javascript:changeCategory();\"");
?>																
                                    </div>


                                    <div class="left rightmargin topmargin"><?php echo TEXT_SEARCH ?></div>
                                    <div class="left rightmargin"><select name="cmbSearch" class="comm_input input_width1">
                                            <option value="title" <?php echo(($cmbSearch == "title" || $cmbSearch == "") ? "Selected" : ""); ?>><?php echo TEXT_TITLE ?></option>
                                        </select>&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                    &nbsp;
                                    <div class="left">
                                        <input type="text" name="txtSearch" value="<?php echo(htmlentities($txtSearch)); ?>" class="comm_input input_width1" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px">
                                        &nbsp;&nbsp;</div>
                                    <div class="left">
                                        <a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language']; ?>/images/go.gif" border="0"></a>
                                    </div>
                                    
                                    
                                    <div class="clear"></div>
                                    
                                </div>
                                <div class="content_section_title" align="left">
                                        <h4><?php echo TEXT_KB_ENTRIES ?></h4>
                                    </div>

                            </td>
                        </tr>

                        <tr>
                        <td>

                        <table width="100%"   border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
                            <tr align="left"  class="listing">
                                <th>&nbsp;</th>
                                <th><?php echo TEXT_TITLE; ?></th>
                                <th><?php echo TEXT_ACTION; ?></th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
<?php
//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
$totalrows = mysql_num_rows(executeSelect($sql, $conn));
settype($totalrows, integer);
settype($begin, integer);
settype($num, integer);
settype($numBegin, integer);
settype($start, integer);
$calc_begin = ($begin == 0) ? $start : $begin;

if (($totalrows <= $calc_begin)) {
    $nor1 = 10;
    $nol = 10;
    if ($num > $numBegin) {
        $num = $num - 1;
        $numBegin = $numBegin;
        $begin = $begin - $nor1;
    } elseif ($num == $numBegin) {
        $num = $num - 1;
        $numBegin = $numBegin - $nol;
        $begin = $calc_begin - $nor1;
        $start = 0;
    }
}
//$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$cmbSearch&txtSearch=" . urlencode($txtSearch) . "&stylename=STYLEKNOWLEDGEBASE&styleminus=minus10&styleplus=plus10&",$var_numBegin,$var_start,$var_begin,$var_num);
$navigate = pageBrowser($totalrows, 10, 10, "&mt=y&cmbSearch=$cmbSearch&txtSearch=" . urlencode($txtSearch) . "&ddlCategory=" . $ddlCategory . "&ddlDepartment=" . $ddlDepartment . "&stylename=STYLEKNOWLEDGEBASE&styleminus=minus6&styleplus=plus6&", $numBegin, $start, $begin, $num);

$sql = $sql . $navigate[0];

$rs = executeSelect($sql, $conn);
$cnt = 1;
while ($row = mysql_fetch_array($rs)) {
    ?>

                                <tr align="left"  class="whitebasic">
                                    <td width="4%">
                                <?php
                                if ($row["nStaffId"] == $_SESSION["sess_staffid"]) {
                                    echo "<input type=\"checkbox\" name=\"chk[]\" id=\"c" . $cnt . "\" value = \"" . $row["nKBID"] . "\"  >";
                                }
                                ?>
                                    </td>
                                    <td colspan="2">                                                                                                
                                        <?PHP echo trimString(htmlentities($row["vKBTitle"]), 60); ?>
                                    </td>

                                    <td width="13%">
    <?php
    if ($row["nStaffId"] == $_SESSION["sess_staffid"]) {
        ?>
                                            <a href="editkbentry.php?id=<?php echo $row["nKBID"]; ?>&stylename=STYLEKNOWLEDGEBASE&styleminus=minus6&styleplus=plus6&mt=y&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo TEXT_EDIT_ENTRY; ?>"></a>	
    <?php } else { ?>

                                            <a href="viewkbentry.php?id=<?php echo $row["nKBID"]; ?>&ddlCategory=<?php echo $ddlCategory ?>&ddlDepartment=<?php echo $ddlDepartment; ?>&stylename=STYLEKNOWLEDGEBASE&styleminus=minus6&styleplus=plus6&mt=y&numBegin=<?php echo $numBegin; ?>&start=<?php echo $start; ?>&begin=<?php echo $begin; ?>&num=<?php echo $num; ?>&";><img src="././../images/view.gif" width="13" height="13" border="0" title="<?php echo TEXT_VIEW_ENTRY; ?>"></a>
                                        <?php }
                                        ?>


                                    </td>
                                    <td width="9%">
                                        <?php
                                        if ($row["nStaffId"] == $_SESSION["sess_staffid"]) {
                                            ?>
                                            <a href="javascript:deleted('<?php echo $row["nKBID"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_DELETE_ENTRY; ?>"></a>
    <?php } ?>
                                    </td>
                                </tr>

                                        <?php
                                        $cnt++;
                                    }
                                    mysql_free_result($rs);
                                    ?>
                            <tr align="left"  class="whitebasic">
                                <td colspan="2"><?php echo($navigate[1] . "&nbsp;" . TEXT_OF . "&nbsp;" . $totalrows . "&nbsp;" . TEXT_RESULTS ); ?></td>
                                <td colspan="3"><?php echo($navigate[2]); ?>
                                    <input type="hidden" name="numBegin" value="<?php echo $numBegin; ?>">
                                    <input type="hidden" name="start" value="">
                                    <input type="hidden" name="begin" value="">
                                    <input type="hidden" name="num" value="">   
                                    <input type="hidden" name="mt" value="y">
                                    <input type="hidden" name="stylename" value="<?php echo($stylename); ?>" >
                                    <input type="hidden" name="styleminus" value="<?php echo($styleminus); ?>">
                                    <input type="hidden" name="styleplus" value="<?php echo($styleplus); ?>">																
                                    <input type="hidden" name="id" value="<?php echo($id); ?>">
                                    <input type="hidden" name="postback" value="">
                                </td>
                            </tr>



                        </table>
                       <!-- ============================================= -->													
                </td></tr>
        </table>

        <!-- Buttons were placed here ---- -->

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr align="center" class="whitebasic">
                <td> 
                    <input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:clickDelete();">   <br> <br>                                 
                </td>
            </tr>
        </table>

        <!-- Buttons were placed here ---- -->
        </table>
    </form>
</div>