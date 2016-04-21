<?php 
//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
$var_numBegin = $_GET["numBegin"];
$var_start = $_GET["start"];
$var_begin = $_GET["begin"];
$var_num = $_GET["num"];

$txtSearchVal = $_REQUEST["txtKbTitleSearch"];

$maxPageLimit = getSettingsValue('MaxPostsPerPage');
    
$qryopt="";
$txtSearch="";
$cmbSearch="";
?>
<script type="text/javascript">
    $(document).ready(function(){
        $(".jqRate").live("click",function(){

            var userId = "<?php echo $_SESSION['sess_userid'];?>";
            if(userId<=0){
                alert("Please login to rate");
                return;
            }
        });
        $(".jqNoRate").live("click",function(){
            alert("You have already rated this entry");
        });

    });
</script>
<div class="content_section" >
    <form action="" method="post" name="frmKB">
        <div class="content_section_title"><h3><?php echo TEXT_KB?></h3></div>
        <?php
        if($error) {?>
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

            <?php }?>

        <form name="frmkbSearch" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

            <div class="content_section_data" style="padding:10px; ">

                <table border="0" width="100%" cellpadding="0" cellspacing="0">

                    <tr>
                        <td>
                            <div class="content_search_container">
                                <h3 style="margin:0; padding:5px 0; "><?php echo TEXT_SEARCH;?></h3>
                            </div>
                        </td>

                        <td colspan="2">
                            <div class="content_search_container">
                                <input name="txtKbTitleSearch" id="txtKbTitleSearch" type="text" class="comm_input input_width2"  value="<?php echo htmlentities($txtSearchVal);?>" size="200">
                                <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">
                                &nbsp;&nbsp;
                                <input type="submit" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_GO;?>" class="comm_btn">
                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="3">&nbsp;</td></tr>
                    <tr><td colspan="3"><h3><?php echo TEXT_SEARCH_RESULT;?></h3></td></tr>

                    <tr><td colspan="3">
                        <div id ="kbSearchResult">
                        <?php
                        if($_POST['btnKbSearch'] || $var_numBegin!='' || $txtSearchVal!='') {
                            $txtKbSearchTitle  = trim($_POST['txtKbTitleSearch']);
                            if(!$txtKbSearchTitle)
                                $txtKbSearchTitle = $txtSearchVal;

                        $my_data=mysql_real_escape_string($txtKbSearchTitle);

                        $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (vKBTitle = '". $my_data . "') AND   vStatus ='A'";
                        $result_kbtitle =  executeSelect($sql,$conn);

                        if(mysql_num_rows($result_kbtitle)>0) { 
                            $row=mysql_fetch_array($result_kbtitle);

                        ?>
                            <table cellpadding="0" cellspacing="0" border="0" class="" width="100%">
                                <tr>
                                    <td align="left"><b><?php echo stripslashes($row['vKBTitle']);?></b><br></td>
                                </tr>
                                <tr>
                                    <td align="left"><?php echo stripslashes($row["tKBDesc"]);?></td>
                                </tr>
                                <tr><td align="left">
                                <?php 
                                include("./includes/releatedresults.php");
                                getReleatedResults($row['vKBTitle'], $row['nKBID']);
                                ?>

                                </td></tr>
                            </table>
                        <?php
                        }else { 
                            $sql = "SELECT kb.nKBID,kb.vKBTitle,kb.tKBDesc,c.vCatDesc from sptbl_kb kb
                                    INNER JOIN sptbl_categories c ON kb.nCatId = c.nCatId where (vKBTitle like  '%$my_data%') AND   vStatus ='A'";
                            $result_kbtitle =  executeSelect($sql,$conn);

                            //Pagination

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

                            $navigate = pageBrowser($totalrows, 10,$maxPageLimit, "&txtKbTitleSearch=" . urlencode($txtSearchVal) . "&", $var_numBegin, $var_start, $var_begin, $var_num);

                            //execute the new query with the appended SQL bit returned by the function
                            $sql = $sql . $navigate[0];
                            $rs = executeSelect($sql, $conn);
                            //Pagination

                            if(mysql_num_rows($rs)>0) {
                            ?>
                                <table cellpadding="5" cellspacing="1" border="0" class="" style="background-color:#cfcfcf;" width="100%">
                                <?php
                                    while($row=mysql_fetch_array($rs)) {
                                        $viewkbentry_seo_link = SITE_URL. "viewuserkbsearchresult/".str_replace(" ","_",str_replace("?", "", stripslashes($row['vKBTitle']))). "/".$row["nKBID"]."/KNOWLEDGEBASE/threeminus/threeplus";
                                        ?>
                                        <tr>
                                            <td align="left" style="background-color:#ffffff;">
                                            <a href="<?php echo $viewkbentry_seo_link?>" class="listing"><?php echo $row["vCatDesc"].' - '.trimString(htmlentities($row["vKBTitle"]),140); ?></a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                ?>
                                    <tr align="left">
                                        <td colspan="6">
                                            <div class="pagination_container">
                                                <div class="pagination_info">
                                                    <?php echo($navigate[1] . "&nbsp;" . TEXT_OF . "&nbsp;" . $totalrows . "&nbsp;" . TEXT_RESULTS ); ?>
                                                </div>
                                                <div class="pagination_links">
                                                    <?php echo($navigate[2]); ?>
                                                </div>

                                            </div>
                                            <input type="hidden" name="numBegin" value="<?php echo $var_numBegin; ?>">
                                            <input type="hidden" name="start" value="<?php echo $var_start; ?>">
                                            <input type="hidden" name="begin" value="<?php echo $var_begin; ?>">
                                            <input type="hidden" name="num" value="<?php echo $var_num; ?>">
                                        </td>
                                    </tr>
                            <?php
                            }
                            else {
                            ?>
                                <tr>
                                    <td align="left" style="background-color:#ffffff;">
                                    <?php
                                        echo MESSAGE_NO_RECORDS;
                                    ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </table>
                        <?php
                            }
                        }
                        ?>

                        </div>
                        </td></tr>
                    <tr>
                        <td align="center" colspan="3" style="padding:10px 0; ">
                            <input type="button" value=" <?php echo BUTTON_TEXT_BACK; ?> " onClick = "window.location.href = '<?php echo SITE_URL; ?>'" class="button" style="margin: 0 0 10px 0">
                        </td>
                    </tr>
                </table>

            </div>


        </form>
        <script type="text/javascript" src="./scripts/jquery.autocomplete_kbsearch.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                var site_url ='<?php echo SITE_URL?>';

                $("#txtKbTitleSearch").autocomplete(site_url+"kb_search_home.php", {
                    selectFirst: true
                });

                // getKbSearchdata();
            });

            function getKbSearchdata(){

                var txtKbSearchid = $("#txtKbSearchid").val();
                var dataString = {"txtKbSearchid":txtKbSearchid};

                $.ajax({

                    url			:"kb_search_home.php",

                    type		:"POST",

                    data		:dataString,

                    dataType            : "html",

                    success		:function(response){

                        if(response!='')
                        {
                            //  alert(response);
                            $("#kbSearchResult").html(response);
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

        <input type="hidden" name="numBegin" value="<?php echo $var_numBegin; ?>">
        <input type="hidden" name="start" value="<?php echo $var_start; ?>">
        <input type="hidden" name="begin" value="<?php echo $var_begin; ?>">
        <input type="hidden" name="num" value="<?php echo $var_num; ?>">
        <input type="hidden" name="mt" value="y">
        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
        <input type="hidden" name="id" value="<?php echo($var_id); ?>">
        <input type="hidden" name="postback" value="">
    </form>
</div>