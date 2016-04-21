<?php  
if($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
    $var_start = $_GET["start"];
    $var_begin = $_GET["begin"];
    $var_num = $_GET["num"];
    $styleminus = $_GET["styleminus"];
    $stylename = $_GET["stylename"];
    $styleplus = $_GET["styleplus"];
} 

else if($_POST["mt"] == "y") {
    $var_numBegin = $_POST["numBegin"];
    $var_start = $_POST["start"];
    $var_begin = $_POST["begin"];
    $var_num = $_POST["num"];
    $styleminus = $_POST["styleminus"];
    $stylename = $_POST["stylename"];
    $styleplus = $_POST["styleplus"];
}

$txtSearchVal = $_REQUEST["txtKbTitleSearch"];
$txtSearchVal=urldecode($txtSearchVal);
//print_r($txtSearchVal);exit();
$maxPageLimit = getSettingsValue('MaxPostsPerPage');

//echo '<pre>'; print_r($stylename); echo '</pre>';


if($_POST["postback"] == "CC") {
    $ddlCategory = $_POST["ddlCategory"];
    $ddlDepartment = $_POST["ddlDepartment"];
}else if ($_POST["postback"] == "CD") {//change department
    $ddlDepartment = $_POST["ddlDepartment"];
}else if($_POST["postback"] == "S") {
    $ddlCategory = $_POST["ddlCategory"];
    $ddlDepartment = $_POST["ddlDepartment"];
}

if($_POST["ddlCategory"] == "") {
    $ddlCategory = $_GET["ddlCategory"];
}
if($_POST["ddlDepartment"] == "") {
    $ddlDepartment = $_GET["ddlDepartment"];
}
settype($ddlDepartment,integer);
settype($ddlCategory,integer);

$sql = " SELECT kb.nKBID, kb.vKBTitle ";
$sql .=" FROM  sptbl_kb kb LEFT OUTER JOIN sptbl_categories  ca ON kb.nCatId = ca.nCatId ";
$sql .=" INNER JOIN sptbl_depts d ON d.nDeptId = ca.nDeptId ";
$sql .=" WHERE kb.nCatId = '$ddlCategory' and vStatus = 'A' ";

$qryopt="";
//modified on November 26, 2005
//searching based on a criterea, then making the criteria null does not have any effect
//hence while checking the condition, we do check if that is not posted back when taking get parameter.   
$txtSearch="";
$cmbSearch="";
if($_POST["txtSearch"] != "") {
    $txtSearch = $_POST["txtSearch"];
}elseif($_GET["txtSearch"] != "" && $_POST["mt"] != "y") {
    $txtSearch = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != "") {
    $cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != "" && $_POST["mt"] != "y") {
    $cmbSearch = $_GET["cmbSearch"];
}
if($txtSearch != "") {
    if($cmbSearch == "title") {
        $qryopt .= " AND kb.vKBTitle like '%" . mysql_real_escape_string($txtSearch) . "%'";
    }
}

$sql .= $qryopt . " ORDER BY kb.dDate DESC  "; 

$_SESSION['sess_backurl'] = getPageAddress(); 

?>

<script type="text/javascript">
    $(document).ready(function(){
        $(".jqRate").live("click",function(){

            var userId = "<?php echo $_SESSION['sess_userid'];?>";
            if(userId<=0){
                alert("Please login to rate the entry");
                return;
            }else{
                var clickedStarVal = $(this).attr("rateVal");
                submitKBRating(clickedStarVal);
                getKbSearchdata();
            }
            //window.location.reload();

        });
        $(".jqNoRate").live("click",function(){
            alert("You have already rated this entry");
        });

    });
</script>

<div class="content_section" >
    <!--form action="" method="post" name="frmKB"-->
    <div class="content_section_title"><h3><?php echo TEXT_KB?></h3></div>

    <div class="content_section_data">
        <?php
        if($error) { ?>
        <div class="content_section_data">
            <div class="msg_error">
                    <?php echo $errormessage;?>
            </div>
        </div>

            <?php }
        if($message) { ?>
        <div class="content_section_data">
            <div class="msg_common">
                    <?php echo $messagetext;?>
            </div>
        </div>
            <?php } ?>

        <form name="frmkbSearch" action="" method="post">

            <!--div class="content_section_title"><h4><?php //echo TEXT_SEARCH;?></h4></div-->

            <div class="kb_search" style="padding: 0px;">
                <table border="0" width="100%" class="" cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top">
                            <input name="txtKbTitleSearch" id="txtKbTitleSearch" style="margin-right:10px;width:882px;" type="text" class="comm_input left input_widthnw9"  value="<?php echo htmlentities($_REQUEST['txtKbTitleSearch']);?>">
                            <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">
                            <input type="submit" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_SEARCH;?>" class="comm_btn left" onclick="return validateKbKey()"></td>
                    </tr>
                </table>
            </div>
            <table border="0" width="100%" class="" cellpadding="0" cellspacing="0">

                <tr><td colspan="3">
                        <div id ="kbSearchResult">

                            <?php
                            if($_POST['btnKbSearch'] || $txtSearchVal!='' || $var_numBegin!='') {

                                $txtKbSearchTitle  = $_POST['txtKbTitleSearch'];
                                if(!$txtKbSearchTitle)
                                    $txtKbSearchTitle = $txtSearchVal;

                                $my_data=mysql_real_escape_string($txtKbSearchTitle);

                                $sql = "SELECT DISTINCT kb.nKBID,kb.vKBTitle,kb.tKBDesc,c.vCatDesc from sptbl_kb kb
                                                INNER JOIN sptbl_categories c ON kb.nCatId = c.nCatId  where (kb.vKBTitle like  '%$my_data%') AND  kb.vStatus ='A'";
                                $result_kbtitle =  executeSelect($sql,$conn);

                                $totalrows = mysql_num_rows($result_kbtitle);
                                settype($totalrows, integer);
                                settype($var_begin, integer);
                                settype($var_num, integer);
                                settype($var_numBegin, integer);
                                settype($var_start, integer);

                                $var_calc_begin = ($var_begin == 0) ? $var_start : $var_begin;
                                if (($totalrows <= $var_calc_begin)) {
                                    $var_nor = 10;
                                    $var_nol = 10;
                                    if ($var_num > $var_numBegin) {
                                        $var_num = $var_num - 1;
                                        $var_numBegin = $var_numBegin;
                                        $var_begin = $var_begin - $var_nor;
                                    } elseif ($var_num == $var_numBegin) {
                                        $var_num = $var_num - 1;
                                        $var_numBegin = $var_numBegin - $var_nol;
                                        $var_begin = $var_calc_begin - $var_nor;
                                        $var_start = "";
                                    }
                                }
                                if ($var_begin < 0)
                                    $var_begin = 0;

                                $navigate = pageBrowser($totalrows, 10, $maxPageLimit, "&mt=y", $var_numBegin, $var_start, $var_begin, $var_num);

                                //execute the new query with the appended SQL bit returned by the function
                                $sql = $sql . $navigate[0];

                                $rs = executeSelect($sql, $conn);


                                ?>
                            <div class="content_section_title"><h4><?php echo TEXT_SEARCH_RESULT;?></h4></div>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" class="list_tbl" >
                                    <?php
                                    if(mysql_num_rows($rs)>0) {
                                        while($row=mysql_fetch_array($rs)) { //echo '<pre>'; print_r($row); echo '</pre>';
                                            $seolink=str_replace(" ","-", stripslashes($row['vKBTitle']));
                                            $seolink=preg_replace('/[^a-zA-Z0-9__.-]/s', '', $seolink);
                                            $seolink=strtolower($seolink);
                                            $seolink=substr($seolink,0,100);
                                            $viewkbentry_seo_link = SITE_URL . "kb/".str_replace("?","", $seolink). "/".$row["nKBID"];
                                            ?>
                                <tr>
                                    <td align="left">
                                        <a href="<?php echo $viewkbentry_seo_link?>" class="listing"><?php echo $row["vCatDesc"].' - '.trimString($row["vKBTitle"],80); ?></a>
                                    </td>
                                </tr>
                                            <?php
                                        }?>
                                <tr align="left">
                                    <td colspan="6">

                                        <div class="pagination_container">
                                            <div class="pagination_info">
                                                        <?php echo($navigate[1] . "&nbsp;" . TEXT_OF . "&nbsp;" . $totalrows . "&nbsp;" . TEXT_RESULTS ); ?>
                                            </div>
                                            <div class="pagination_links">
                                                        <?php $nav=str_replace("<a href='?","<a href='s=".$txtSearchVal."&",$navigate[2]);
                                                        echo $nav;?>
                                            </div>

                                        </div>
                                        <input type="hidden" name="numBegin" value="<?php echo $var_numBegin; ?>">
                                        <input type="hidden" name="start" value="<?php echo $var_start; ?>">
                                        <input type="hidden" name="begin" value="<?php echo $var_begin; ?>">
                                        <input type="hidden" name="num" value="<?php echo $var_num; ?>">
                                        <input type="hidden" name="mt" value="y">
                                        <input type="hidden" name="stylename" value="<?php echo($stylename); ?>" >
                                        <input type="hidden" name="styleminus" value="<?php echo($styleminus); ?>">
                                        <input type="hidden" name="styleplus" value="<?php echo($styleplus); ?>">
                                        <input type="hidden" name="id" value="<?php echo($var_id); ?>">

                                    </td>
                                </tr>

                                        <?php
                                    }else {
                                        ?>
                                <tr>
                                    <td align="left">
                                                <?php echo TEXT_NO_RESULT_FOUND;?>
                                    </td>
                                </tr>
                                        <?php } ?>
                            </table>
                                <?php

                            }else {

                                $mostlyReviewedKbs     = getMostlyReviewedKbs();
                                $recentlyAddedKbs      = getRecentlyAddedKbs();
                                ?>
                            <div class="popular_kbs" style="margin:25px 0 25px 0;">
                                <h4><?php echo MOSTLY_REVIEWED_KB; ?></h4>
                                <ul>
                                        <?php
                                        while($mostlyReviewedVal = mysql_fetch_assoc($mostlyReviewedKbs)) {
                                            $seolink=str_replace(" ","-", stripslashes($mostlyReviewedVal['vKBTitle']));
                                            $seolink=preg_replace('/[^a-zA-Z0-9__.-]/s','', $seolink);
                                            $seolink=strtolower($seolink);
                                            $seolink=substr($seolink,0,100);
                                            $viewkbentry_seo_link = SITE_URL . "kb/".str_replace("?","", $seolink). "/".$mostlyReviewedVal["nKBID"];

                                            ?>
                                    <li>

                                        <a href="<?php echo $viewkbentry_seo_link; ?>"><span><?php echo $mostlyReviewedVal['vCatDesc']; ?> -</span><?php echo $mostlyReviewedVal['vKBTitle']; ?></a>
                                    </li>
                                            <?php } ?>
                                </ul>
                            </div>

                            <div class="popular_kbs" style="margin:25px 0 25px 0;">
                                <h4><?php echo RECENTLY_ADDED_KB; ?></h4>
                                <ul>
                                        <?php
                                        while($recentlyAddedVal = mysql_fetch_assoc($recentlyAddedKbs)) {
                                            $seolink=str_replace(" ","-", stripslashes($recentlyAddedVal['vKBTitle']));
                                            $seolink=preg_replace('/[^a-zA-Z0-9__.-]/s', '', $seolink);
                                            $seolink=strtolower($seolink);
                                            $seolink=substr($seolink,0,100);
                                            $viewkbentry_seo_link = SITE_URL . "kb/".str_replace("?","", $seolink). "/".$recentlyAddedVal["nKBID"];

                                            ?>
                                    <li>
                                        <a href="<?php echo $viewkbentry_seo_link; ?>"><span><?php echo $recentlyAddedVal['vCatDesc']; ?> -</span><?php echo trim_the_string($recentlyAddedVal['vKBTitle'],100); ?></a>
                                    </li>
        <?php } ?>
                                </ul>
                            </div>

    <?php
                            }
                            ?>

                        </div>


                    </td></tr>
            </table>
        </form>
        <script type="text/javascript" src="<?php echo SITE_URL?>scripts/jquery.autocomplete_kbsearch.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                var site_url ='<?php echo SITE_URL?>';

                $("#txtKbTitleSearch").autocomplete(site_url+"searck_kb_result_ajax.php", {
                    selectFirst: true
                });

                // getKbSearchdata();
            });

            function getKbSearchdata(){ //alert('hii');

                var txtKbSearchid = $("#txtKbSearchid").val(); //alert(txtKbSearchid);
                var dataString = {"txtKbSearchid":txtKbSearchid};
                var base_url ='<?php echo SITE_URL?>';
                var site_url =base_url+"searck_kb_result_ajax.php";
                $.ajax({

                    url			:site_url,
                    type		:"POST",
                    data		:dataString,
                    dataType        : "html",

                    success		:function(response){ //alert(response);

                        if(response!='')
                        {
                            //  alert(response);
                            //$("#kbSearchResult").html(response);
                            window.location = base_url+response;
                            //  $("#txt_kbSearchResult").val(response);
                        }
                        else
                        {
                            $("#kbSearchResult").html("No Result Found !");
                        }
                    }

                });
            }
        </script>
        <!--Show KnowlaGE Base ends-->

        <!--/form-->

    </div>
</div>


<!--
<div id="jqRatingPop" class="jqRatingPop" style="display:none;">
    <div style="width:480px;"><h1 style="font-size:16px; "><?php //echo RATE_TITLE; ?></h1></div>
    <div id="jqLoader" style="display:none;"><img src="images/loading.gif" border="0" class="loader" alt="" /></div>
    <div id="ratingArea">
        <form name="frmRate" method="post" action="#" >
            <table cellpadding="1" cellspacing="1" width="100%" border="0" class="ratingBox">
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td class="emailStoryStyle"><h1 style="font-size:16px; "><?php //echo RATE_TEXT; ?>:</h1></td>
                    <td>
                        <input name="star"  type="radio" class="star"  value="1" />
                        <input name="star"  type="radio" class="star"  value="2" />
                        <input name="star"  type="radio" class="star"  value="3" />
                        <input name="star"  type="radio" class="star"  value="4" />
                        <input name="star"  type="radio" class="star"  value="5" />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>

                <tr><td colspan="2">&nbsp; </td></tr>
                <tr>
                    <td align="center" colspan="2">
                        <input type="hidden" name="hid_user_id" id="hid_user_id" value="<?php //echo $_SESSION[sess_userid]; ?>" />
                        <input type="hidden" name="site_url" id="site_url" value="<?php //echo SITE_URL; ?>" />
                        <input type="button" value="<?php //echo RATE_TEXT; ?>" style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqPostProductRating" onclick="submitKBRating()" id="comn_button_blue1"/>
                        <input type="button" value="<?php //echo BUTTON_TEXT_CANCEL; ?>"  style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqProductRatingCancel" id="comn_button_blue2" onclick="closeKBRating()"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
-->





