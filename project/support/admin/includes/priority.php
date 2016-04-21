<?php
$flag_msg = "";
$var_staffid = $_SESSION["sess_staffid"];
if ($_GET["id"] != "") {
    $var_id = $_GET["id"];
}
elseif ($_POST["id"] != "") {
    $var_id = $_POST["id"];
} 
$var_staffid = $_SESSION["sess_staffid"];

if ($_POST["postback"] == "" && $var_id != "") {
    $sql = "Select * from sptbl_priorities where nPriorityId='".mysql_real_escape_string($var_id)."'";
    $rs=executeSelect($sql,$conn);
    $row=mysql_fetch_array($rs);
    $var_prtyvalue=$row['nPriorityValue'];
    $var_prtycolor=$row['vTicketColor'];
    $var_prtydesc=$row['vPriorityDesc'];
    //prior icon
    $txticon_Url = "../ticketPriorLogo/" . $row["vPrioritie_icon"];
    if($row["vPrioritie_icon"] == "")
        $txticon_Url = "../ticketPriorLogo/noicon.jpg";
}
else if ($_POST["postback"] == "D") {
    if (validateDeletion(mysql_real_escape_string($_POST["id"])) and mysql_real_escape_string($_POST["id"]) !="1") {
        $sql = "delete from  sptbl_priorities where nPriorityValue !='0' and  nPriorityId='" . mysql_real_escape_string($_POST["id"]) . "'";
        executeQuery($sql,$conn);

        $hidioniconename = $_POST['hidioniconename'];
        if($hidioniconename != "") {
            $filepath = "".$hidioniconename;
            if (strpos($filepath, "noicon.j") === false) {
                if(file_exists($filepath)) {
                    unlink($filepath);
                }
            }
        }
        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Priority','" . mysql_real_escape_string($_POST["id"]) . "',now())";
            executeQuery($sql,$conn);
        }
        // it is to update tickets table
        $sql = "update sptbl_tickets set vPriority='0' where vPriority='" . mysql_real_escape_string($_POST["id"]) . "'";
        executeQuery($sql,$conn);

        $var_message = MESSAGE_RECORD_DELETED;
        $flag_msg = "class='msg_success'";
    }
    else {
        $var_message =  MESSAGE_RECORD_ERROR;
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

    if (validateDeletion($var_list) == true and $var_list!="") {


        $sql = "delete from  sptbl_priorities where nPriorityId  IN(" . $var_list . ")";
        executeQuery($sql,$conn);
        //Insert the actionlog
        if(logActivity()) {
            for($i=0;$i<count($_POST["chk"]);$i++) {
                $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Priority','" . mysql_real_escape_string($_POST["chk"][$i]) . "',now())";
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
    $var_prtyvalue=$_POST['cmbValue'];
    $var_prtycolor=$_POST['cmbColor'];
    $var_prtydesc=$_POST['txtExtraPrty'];
    // Upload icon
    if($_FILES['txticon_Url']['name']) {
        $uploadstatus=upload_ticket_prior_logo("txticon_Url","../ticketPriorLogo/","","image/jpeg,image/pjpeg,image/gif,image/png,image/xpng","10000000");
        
        $errorcode="";
        $dup_flag=0;
        $file_name="";
        switch ($uploadstatus) {
            case "FNA":
                $errorcode="";
                break;
            case "IS":
                $errorcode=MESSAGE_UPLOAD_ERROR_3;
                break;
            case "IT":
                $errorcode=MESSAGE_UPLOAD_ERROR_2;
                break;
            case "NW":
                $errorcode=MESSAGE_UPLOAD_ERROR_4;
                break;
            case "IF":
                $errorcode=MESSAGE_UPLOAD_ERROR_6;
                break;
//			   case "FE":
//			            $errorcode=MESSAGE_UPLOAD_ERROR_5;
//				         break;
            default:
                $txticon_Url=$uploadstatus;
                break;
        }


    }
    if($txticon_Url =="") {
        $txticon_Url = 'noicon.jpg';
    }
    // Upload icon  ends

    if (validateAddition()==true) {
        $sql = "Insert into sptbl_priorities(nPriorityId,nPriorityValue,vPriorityDesc,vTicketColor, vPrioritie_icon)";
        $sql .= " Values('','" . mysql_real_escape_string($var_prtyvalue). "','" . mysql_real_escape_string($var_prtydesc) . "','" . mysql_real_escape_string($var_prtycolor) . "','". mysql_real_escape_string($txticon_Url)  . "')";
        executeQuery($sql,$conn);
        $var_insert_id = mysql_insert_id($conn);
        //Insert the actionlog
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Priority','" . mysql_real_escape_string($var_insert_id) . "',now())";
            executeQuery($sql,$conn);
        }
        $var_message = MESSAGE_RECORD_ADDED;
        $flag_msg = "class='msg_success'";

    }else {
        $filepath = "../ticketPriorLogo/".  $txticon_Url;
        if (strpos($filepath, "noicon.j") === false) {
            if(file_exists($filepath)) {
                unlink($filepath);
            }
        }
        $var_message = MESSAGE_STATUS_ABORTED;
        $flag_msg = "class='msg_error'";
    }
    //-----reset the parameter---------//
    $var_prtyvalue=1;

}elseif ($_POST["postback"] == "U") {
    $var_prtyid=$_POST['prtyid'];
    $var_prtyvalue=$_POST['cmbValue'];
    $var_prtycolor=$_POST['cmbColor'];
    $var_prtydesc=$_POST['txtExtraPrty'];
    $var_oldpriority=$_POST['oldpriority'];

    // Upload icon
    if($_FILES['txticon_Url']['name']) {
        $uploadstatus=upload_ticket_prior_logo("txticon_Url","../ticketPriorLogo/","","image/jpeg,image/pjpeg,image/gif,image/png,image/xpng","10000000");

        $errorcode="";
        $dup_flag=0;
        $file_name="";
        switch ($uploadstatus) {
            case "FNA":
                $errorcode="";
                break;
            case "IS":
                $errorcode=MESSAGE_UPLOAD_ERROR_3;
                break;
            case "IT":
                $errorcode=MESSAGE_UPLOAD_ERROR_2;
                break;
            case "NW":
                $errorcode=MESSAGE_UPLOAD_ERROR_4;
                break;
            case "IF":
                $errorcode=MESSAGE_UPLOAD_ERROR_6;
                break;
//			   case "FE":
//			            $errorcode=MESSAGE_UPLOAD_ERROR_5;
//				         break;
            default:
                $txticon_Url=$uploadstatus;
                break;
        }


    }

    // Upload icon  ends



    if (validateUpdation($var_prtyid)==true) {
        if($var_oldpriority == 0)
            $var_prtyvalue = 0;

        $sql = "update sptbl_priorities set nPriorityValue='".mysql_real_escape_string($var_prtyvalue). "',";
        $sql .="vPriorityDesc='". mysql_real_escape_string($var_prtydesc) . "',vTicketColor='".mysql_real_escape_string($var_prtycolor) . "' ";
        if($txticon_Url != "") {
            $sql .=" , vPrioritie_icon='". mysql_real_escape_string($txticon_Url) ."' ";

            $hidioniconename = $_POST['hidioniconename'];
            if($hidioniconename != "") {
                $filepath = "".$hidioniconename;
                if (strpos($filepath, "noicon.j") === false) {
                    if(file_exists($filepath)) {
                        unlink($filepath);
                    }
                }
            }
        }

        $sql .= " where nPriorityId='" . mysql_real_escape_string($var_prtyid) . "'";
        // echo "sql==$sql";exit;
        executeQuery($sql,$conn);
        if(logActivity()) {
            $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Priority','" . mysql_real_escape_string($var_prtyid) . "',now())";
            executeQuery($sql,$conn);
        }
// it is to update tickets table 
        $sql = "update sptbl_tickets set vPriority='".mysql_real_escape_string($var_prtyvalue). "' where vPriority='" . mysql_real_escape_string($var_oldpriority) . "'";
        executeQuery($sql,$conn);

        $var_prtyvalue = 1;
        $var_prtycolor="";
        $var_prtydesc="";
        $txticon_Url = "";
        $var_message = "One Record updated.";
        $flag_msg = "class='msg_success'";
    }else {
        $_POST["postback"] = "";
        $var_id = $var_prtyid;

        $var_message = MESSAGE_STATUS_ABORTED;
        $flag_msg = "class='msg_error'";
    }

}else {
    $var_prtyvalue=1;
}
function validateDeletion($var_list) {

//select p.nPriorityValue,p.nPriorityId from sptbl_priorities p,sptbl_tickets t where p.nPriorityId in('10') and t.vPriority=p.nPriorityValue;

    global $conn;

    $sql="select p.nPriorityValue,p.nPriorityId from sptbl_priorities p,sptbl_tickets t where p.nPriorityId in($var_list) and t.vPriority=p.nPriorityValue";

    if( mysql_num_rows( executeSelect($sql,$conn) ) >0 ) {

        return false;
    }

    return true;
}

function validateUpdation($varpid) {
    global $conn;
    //check priroty value exist
    if(!isValidStatus($_POST['txtExtraPrty'])) {
        return false;
    }
    $sql="Select * from sptbl_priorities where (nPriorityValue='".mysql_real_escape_string($_POST['cmbValue'])."'";
    $sql .=" or vPriorityDesc='".mysql_real_escape_string($_POST['txtExtraPrty'])."') and nPriorityId !='".mysql_real_escape_string($varpid)."'";

    if( mysql_num_rows( executeSelect($sql,$conn) ) >0 ) {
        return false;
    }
    return true;
}
function validateAddition() {
    global $conn;
    //check priroty value exist
    if(!isValidStatus($_POST['txtExtraPrty'])) {
        return false;
    }

    $sql="Select * from sptbl_priorities where nPriorityValue='".mysql_real_escape_string($_POST['cmbValue'])."'";
    $sql .=" or vPriorityDesc='".mysql_real_escape_string($_POST['txtExtraPrty'])."'";

    if( mysql_num_rows( executeSelect($sql,$conn) ) >0 ) {
        return false;
    }
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

$sql = "Select * from sptbl_priorities where 1=1 ";


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

if($var_search != "" and $var_cmbSearch == "name" ) {

    $qryopt .= " and vPriorityDesc like '" . mysql_real_escape_string($var_search) . "%'";

}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By nPriorityValue";

?>
<div class="content_section">
    <form name="frmExtraPriority" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post" enctype="multipart/form-data">
        <Div class="content_section_title"><h3><?php echo TEXT_PRIORITY_DETAILS ?></h3></Div>


        <div class="content_search_container">
            <div class="left rightmargin topmargin">

                <?php echo TEXT_SEARCH?>
            </div>
            <div class="left rightmargin">
                <select name="cmbSearch" class="comm_input input_width1" style="height:29px;">
                    <option value="name" <?php echo(($var_cmbSearch == "name" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_PRIORITY_NAME?></option>
                </select>
            </div>
            &nbsp;<div class="left">
                <input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="comm_input input_width1" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px; height:20px!important; margin-right:5px;">
                </div>
            <div class="left">
                <a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
            </div>
            <div class="clear"></div>
        </div>

        <div <?php echo $flag_msg; ?>>	<?php echo($var_message); ?> </div>




        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
            <tr align="left"  class="listing">
                <th>&nbsp;</th>
                <th><?php echo "<b>".TEXT_PRIORITY_NAME."</b>"; ?></th>
                <th><?php echo "<b>".TEXT_PRIORITY_ICON."</b>"; ?></th>
                <th  align="center"><?php echo "<b>".TEXT_PRIORITY_COLOR."</b>"; ?></th>
                <th  align="center"><?php echo "<b>".TEXT_PRIORITY_VALUE."</b>"; ?></th>
                <th  align="center" colspan=2><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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

            <tr align="left"  class="whitebasic">
                <td width="5%" align="center">
                        <?php if($row["nPriorityValue"]!="0") { ?>
                    <input type="checkbox" name="chk[]" id="c<?php echo($cnt); ?>" value="<?php echo($row["nPriorityId"]); ?>" class="checkbox" >&nbsp;
                            <?php } else { ?>
                    &nbsp;&nbsp;&nbsp;
                            <?php } ?>
                </td>
                <td width="26%"><?php  echo  htmlentities(stripslashes(trim_the_string($row["vPriorityDesc"]))); ?></td>
                <td width="20%">
                        <?php
                        $prior_icon = "../ticketPriorLogo/" . $row["vPrioritie_icon"];
                        if($row["vPrioritie_icon"] == "")
                            $prior_icon = "../ticketPriorLogo/noicon.jpg";


                        ?>
                    <img src="<?php echo $prior_icon;?>" alt="icon" height="50" width="50" >
                </td>
                <td  align="center" width="15%"  >
                    <a class="miniColors-trigger" style="cursor: default; background-color: <?php echo htmlentities(trim_the_string($row["vTicketColor"])); ?>"></a>
                </td>
                <td  align="center" width="15%"  ><?php echo htmlentities(trim_the_string($row["nPriorityValue"])); ?></td>
                <td  width="9%" align="center"><a href="priority.php?id=<?php echo $row["nPriorityId"]; ?>&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&"><img src="././../images/edit.gif" width="13" height="13" border="0" title="<?php echo TEXT_EDIT_PRIORITY ?>"></a></td>
                <td width="10%"  align="center">
                        <?php if($row["nPriorityValue"]!="0") { ?>
                    <a href="javascript:deleted('<?php echo $row["nPriorityId"]; ?>');"><img src="././../images/delete.gif" width="13" height="13" border="0" title="<?php echo TEXT_DELETE_PRIORITY?>"></a></td>
                        <?php } ?>
            </tr>
                <?php
                $cnt++;
            }
            mysql_free_result($rs);
            ?>
            <tr align="left"  class="listingmaintext">
                <td colspan="7">

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
                                <input name="btnDelete" type="button" class="comm_btn_greyad" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:clickDelete();">                                    </td></tr>
                    </table></td>
            </tr>
        </table>


















        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>






                    <Div class="content_section_title"><h3><?php echo TEXT_ADD_OR_EDIT_PRIORITY ?></h3></Div>



                    <div  class="content_section_data">



                        <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="">
                            <tr align="center" class="whitebasic">
                                <td  class="whitebasic"><?php echo TEXT_PRIORITY_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                                <td align="left">
                                    <input name="txtExtraPrty" id="txtExtraPrty" type="text" class="comm_input input_width1"  maxlength=100 size=25 value="<?php echo htmlentities($var_prtydesc); ?>">
                                </td>
                            </tr>
                            <tr class="whitebasic"><td colspan="2">&nbsp;</td></tr>
                            <tr align="center" class="whitebasic" >
                                <td  class="whitebasic"><?php echo TEXT_PRIORITY_COLOR?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                                <td  align="left">
                                                     <!--   <select name="cmbColor" size="1"  id="cmbColor"  >
										 <!--<option value="#FF0000" <?php //if($var_prtycolor=="#FF0000") echo "selected";?> >Red</option>
										 <option value="#0000FF" <?php// if($var_prtycolor=="#0000FF") echo "selected";?>>Blue</option>
										 <option value="#00FF00" <?php// if($var_prtycolor=="#00FF00") echo "selected";?>>GREEN</option>
										 <option value="#FFFFFF" <?php// if($var_prtycolor=="#FFFFFF") echo "selected";?>>White</option>
										 <option value="#000000" <?php// if($var_prtycolor=="#000000") echo "selected";?>>Black</option> -->
                                    <?php
                                    // if($var_prtycolor=="")
                                    //  $var_prtycolor="#FFFFFF";
                                    ?>
                                                                            <!-- <option style="color:<?php //echo   $var_prtycolor?>;" value="<?php //echo   $var_prtycolor?>" selected>#############</option>
                                    <?php
                                    /* for($i=0;$i<25;$i++){
										   	   $clrcode=colorCodelight();
										 ?>
										   <option style="color:<?php// echo   $clrcode?>;" value="<?php// echo   $clrcode?>" >#############</option>
										 <?php			   
											
										   }*/
                                    ?>

						                </select>-->

                                    <!-- Color picker-->

                                    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

                                    <script type="text/javascript" src="../scripts/colorpicker/jquery.miniColors.js"></script>
                                    <!--	<link type="text/css" rel="stylesheet" href="../styles/ColorPicker/jquery.miniColors.css" />-->

                                    <script type="text/javascript">

                                        $(document).ready( function() {

                                            function init() {

                                                // Enabling miniColors
                                                $('.color-picker').miniColors({
                                                    open: function(hex, rgb) {
                                                        $('#console').prepend('open: ' + hex + ', rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')<br>');
                                                    },
                                                    close: function(hex, rgb) {
                                                        $('#console').prepend('close: ' + hex + ', rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')<br>');
                                                    }
                                                });

                                                // With opacity
                                                $('.color-picker-opacity').miniColors({
                                                    opacity: true,
                                                    change: function(hex, rgba) {
                                                        $('#console').prepend('change: ' + hex + ', rgba(' + rgba.r + ', ' + rgba.g + ', ' + rgba.b + ', ' + rgba.a + ')<br>');
                                                    },
                                                    open: function(hex, rgba) {
                                                        $('#console').prepend('open: ' + hex + ', rgba(' + rgba.r + ', ' + rgba.g + ', ' + rgba.b + ', ' + rgba.a + ')<br>');
                                                    },
                                                    close: function(hex, rgba) {
                                                        $('#console').prepend('close: ' + hex + ', rgba(' + rgba.r + ', ' + rgba.g + ', ' + rgba.b + ', ' + rgba.a + ')<br>');
                                                    }
                                                });

                                            }

                                            init();

                                            $('#disable').click( function() {

                                                $('.color-picker, .color-picker-opacity').miniColors('disabled', true);
                                                $("#disable").prop('disabled', true);
                                                $("#enable").prop('disabled', false);
                                            });



                                        });

                                    </script>
                                    <input type="text" name="cmbColor" id="cmbColor" class="color-picker" size="7" autocomplete="on" value="<?php echo $var_prtycolor;?>"  />
                                    <!-- Color picker ends-->


                                </td>
                            </tr>
                            <tr class="whitebasic"><td colspan="2">&nbsp;</td></tr>
                            <tr align="center" class="whitebasic">
                                <td  class="whitebasic"><?php echo TEXT_PRIORITY_VALUE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                                <td  align="left">
                                    <input type="hidden" name="oldpriority" value="<?php echo $var_prtyvalue;?>">
                                    <select name="cmbValue" size="1" class="comm_input input_width1" id="cmbValue" style="width:50px" <?php if($var_prtyvalue ==0) echo "disabled";?>>
                                        <?php
                                        for($i=0;$i<100;$i++) {
                                            $options ="<option value='".$i."'";
                                            if ($var_prtyvalue == $i) {

                                                $options .=" selected=\"selected\"";
                                            }
                                            $options .=">".$i."</option>\n";
                                            echo $options;
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <!-- icon upload-->
                            <tr class="whitebasic"><td colspan="2">&nbsp;</td></tr>
                            <tr align="center" class="whitebasic" >
                                <td  class="whitebasic"><?php echo TEXT_PRIORITY_ICON?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                                <td  align="left">

                                    <input name="txticon_Url" type="file" class="comm_input input_width1" id="txticon_Url" size="30" maxlength="100">
                                    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                    <?php
                                    if($txticon_Url != "") {
                                        ?>
                                    <img alt="icon" src="../ticketPriorLogo/<?php echo $txticon_Url;?>" height="50" width="50">
                                        <?php
                                    }
                                    ?>
                                    <input type="hidden" name ="hidioniconename" value="<?php echo $txticon_Url;?>">
                                </td>
                            </tr>



                        </table>
                </td>
            </tr>





            <tr align="center" class="whitebasic">
                <td colspan=2 class="whitebasic">
                    <?php
                    if ($var_id != "") {
                        ?>
                    <input name="btnAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>" onClick=javascript:clickEdit()  >
                        <?php } else { ?>
                    <input name="btnAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick=javascript:clickAdd()  >
                        <?php } ?>
                </td>
            <input type=hidden name="prtyid" value="<?php echo $var_id ?>">
            </tr>

        </table>


</div>                           


</form>
</div>