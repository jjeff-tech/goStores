<?php
/*if($_GET["mt"] == "y") {
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
$sql .=" WHERE kb.nCatId = '$ddlCategory'  and vStatus = 'A' ";*/
/*if($ddlCategory != "0")
$sql .="AND kb.nCatId = '$ddlCategory'";*/



$qryopt="";
//modified on November 26, 2005
//searching based on a criterea, then making the criteria null does not have any effect
//hence while checking the condition, we do check if that is not posted back when taking get parameter.
$txtSearch="";
$cmbSearch="";
/*if($_POST["txtSearch"] != ""){
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

$sql .= $qryopt . " ORDER BY kb.dDate DESC  ";*/

//$_SESSION['sess_backurl'] = getPageAddress();
//echo $sql;
//echo "<br>Department  ". $ddlDepartment;
//echo "<br>Category  ". $ddlCategory;
?>

<div class="content_section">
<form action="" method="post" name="frmKB">
<div class="content_section_title"><h3><?php echo TEXT_KB?></h3></div>


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

<form name="frmkbSearch" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

<div class="content_section_data">

<table border=0 width="100%">
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr>
        <td><h3><?php echo TEXT_SEARCH;?></h3></td>
        <td>&nbsp;</td>
        <td colspan="2">
            <input name="txtKbTitleSearch" id="txtKbTitleSearch" type="text" class="comm_input input_width2"  value="<?php echo htmlentities($_POST['txtKbTitleSearch']);?>" size="200">
            <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">
&nbsp;&nbsp;
        <input type="submit" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_GO;?>" class="comm_btn"></td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3"><h3><?php echo TEXT_SEARCH_RESULT;?></h3></td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">
            <div id ="kbSearchResult">

                <?php
if($_POST['btnKbSearch']){
$txtKbSearchTitle  =trim($_POST['txtKbTitleSearch']);
     //Search by title
     /* if($txtKbSearchTitle !='')
          {*/
                    $my_data=mysql_real_escape_string($txtKbSearchTitle);

                    $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (vKBTitle = '$my_data') AND   vStatus ='A'";
        $result_kbtitle =  executeSelect($sql,$conn);

                    if(mysql_num_rows($result_kbtitle)>0){
                        $row=mysql_fetch_array($result_kbtitle);

            ?>
                <table cellpadding="0" cellspacing="0" border="0" class="">
                    <tr>
                        <td><?php echo stripslashes($row["tKBDesc"]);?></td>
                    </tr>
                </table>
                <?php

                    }

else{
	 $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (vKBTitle like  '%$my_data%') AND   vStatus ='A'";
        $result_kbtitle =  executeSelect($sql,$conn);

                    if(mysql_num_rows($result_kbtitle)>0)
                {
            ?>
<table cellpadding="0" cellspacing="0" border="0" class="">
<?php
		while($row=mysql_fetch_array($result_kbtitle)){
                $viewkbentry_seo_link = "viewuserkbsearchresult/".stripslashes($row['vKBTitle']). "/".$row["nKBID"]."/KNOWLEDGEBASE/threeminus/threeplus";
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
//}
}
        ?>
</table>
            </div>
        </td></tr>
   
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