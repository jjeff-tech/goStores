<?php
if($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
	$var_start = $_GET["start"];
	$var_begin = $_GET["begin"];
	$var_num = $_GET["num"];
	$styleminus = $_GET["styleminus"];
	$stylename = $_GET["stylename"];
	$styleplus = $_GET["styleplus"];
}else if($_POST["mt"] == "y") {
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	$styleminus = $_POST["styleminus"];
	$stylename = $_POST["stylename"];
	$styleplus = $_POST["styleplus"];
	
}


if($_POST["postback"] == "CC"){
	$ddlCategory = $_POST["ddlCategory"];
	$ddlDepartment = $_POST["ddlDepartment"];
}else if ($_POST["postback"] == "CD") {//change department
	$ddlDepartment = $_POST["ddlDepartment"];
}else if($_POST["postback"] == "S"){
	$ddlCategory = $_POST["ddlCategory"];
	$ddlDepartment = $_POST["ddlDepartment"];
}


if($_POST["ddlCategory"] == ""){
	$ddlCategory = $_GET["ddlCategory"];
}
if($_POST["ddlDepartment"] == ""){
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
if($_POST["txtSearch"] != ""){
		$txtSearch = $_POST["txtSearch"];
}elseif($_GET["txtSearch"] != "" && $_POST["mt"] != "y"){
		$txtSearch = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != ""){
		$cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != "" && $_POST["mt"] != "y"){
		$cmbSearch = $_GET["cmbSearch"];
}
if($txtSearch != ""){
	if($cmbSearch == "title"){
	        $qryopt .= " AND kb.vKBTitle like '%" . addslashes($txtSearch) . "%'";
	}
}

$sql .= $qryopt . " ORDER BY kb.dDate DESC  ";

$_SESSION['sess_backurl'] = getPageAddress(); 

//echo "<br>Department  ". $ddlDepartment;
//echo "<br>Category  ". $ddlCategory;
?>

<div class="content_section">
<form action="" method="post" name="frmKB">
<div class="content_section_title"><h3><?php echo TEXT_KB?></h3></div>
                    
<div class="content_section_data">		  
         												 <?php
														  if($error){?>
														 <div class="content_section_data">  
														  <div class="msg_error">
														  <?php echo $errormessage;?>
														  </div>				
														</div>
															
														  <?php }
														  if($message){ ?>
														  <div class="content_section_data">  
														  <div class="msg_common">
														  <?php echo $messagetext;?>
														  </div>
														  </div>
														  
														 <?php }?>
								
<form name="frmkbSearch" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<div class="content_section_title"><h4><?php echo TEXT_SEARCH;?></h4></div>

<table border="0" width="100%" class="comm_tbl" cellpadding="0" cellspacing="0">    
    <tr>       
        <td>
            <input name="txtKbTitleSearch" id="txtKbTitleSearch" type="text" class="comm_input input_width2"  value="<?php echo htmlentities($var_cc);?>" size="200">
            <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">            
            <input type="submit" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_GO;?>" class="comm_btn" onclick="return validateKbKey()"></td>
    </tr>
	</table>
	
	<table border="0" width="100%" class="comm_tbl" cellpadding="0" cellspacing="0">    
    
    <tr><td colspan="3">
            <div id ="kbSearchResult">

                <?php
if($_POST['btnKbSearch']){
   // print_r($_POST);exit;
$txtKbSearchTitle  = $_POST['txtKbTitleSearch'];
     //Search by title
      if($txtKbSearchTitle !='')
          {
                    $my_data=mysql_real_escape_string($txtKbSearchTitle);

	 $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (vKBTitle like  '%$my_data%') AND   vStatus ='A'";
        $result_kbtitle =  executeSelect($sql,$conn);

                    if(mysql_num_rows($result_kbtitle)>0)
                {
            ?>
                <div class="content_section_title"><h4><?php echo TEXT_SEARCH_RESULT;?></h4></div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="list_tbl" >
<?php
		while($row=mysql_fetch_array($result_kbtitle)){
                $viewkbentry_seo_link = SITE_URL . "viewkbentry/".stripslashes($row['vKBTitle']). "/".$row["nKBID"]."/KNOWLEDGEBASE/threeminus/threeplus";
                ?>
    <tr>
        <td align="left">
            <a href="<?php echo $viewkbentry_seo_link?>" class="listing"><?php echo trimString(htmlentities($row["vKBTitle"]),80); ?></a>
        </td>
    </tr>

                <?php
                }
               

	}

      }
}
        ?>
</table>
            </div>
            <div id="jqRatingPop" class="jqRatingPop" style="display:none;">
                <div style="width:480px;"><h1 style="font-size:16px; "><?php echo RATE_TITLE; ?></h1></div>
                <div id="jqLoader" style="display:none;"><img src="images/loading.gif" border="0" class="loader" alt="" /></div>
                <div id="ratingArea">
                    <form name="frmRate" method="post" action="#" >
                        <table cellpadding="1" cellspacing="1" width="100%" border="0" class="ratingBox">
                            <tr><td colspan="2">&nbsp;</td></tr>
                            <tr>
                                <td class="emailStoryStyle"><h1 style="font-size:16px; "><?php echo RATE_TEXT; ?>:</h1></td>
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
                                    <input type="hidden" name="hid_user_id" id="hid_user_id" value="<?php echo $_SESSION[sess_userid]; ?>" />
                                    <!--<input type="hidden" name="hid_kb_id" id="hid_kb_id" value="<?php echo $kbid; ?>" />-->
                                    <input type="hidden" name="site_url" id="site_url" value="<?php echo SITE_URL; ?>" />
                                    <input type="button" value="<?php echo RATE_TEXT; ?>" style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqPostProductRating" onclick="submitKBRating()" id="comn_button_blue1"/>
                                    <input type="button" value="<?php echo BUTTON_TEXT_CANCEL; ?>"  style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqProductRatingCancel" id="comn_button_blue2" onclick="closeKBRating()"/>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
          
        </td></tr>
</table>
</form>
 <script type="text/javascript" src="./scripts/jquery.autocomplete_kbsearch.js"></script>
                                                        <script type="text/javascript">
                                                        $(document).ready(function(){
                                                            var site_url ='<?php echo SITE_URL?>';

                                                          $("#txtKbTitleSearch").autocomplete(site_url+"searck_kb_result_ajax.php", {
                                                                        selectFirst: true
                                                                       

                                                                });

                                                                // getKbSearchdata();
                                                        });

                                                            function getKbSearchdata(){

                                                                var txtKbSearchid = $("#txtKbSearchid").val();
                                                                var dataString = {"txtKbSearchid":txtKbSearchid};

                                                                $.ajax({

                                                                    url			:"searck_kb_result_ajax.php",

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
</div>