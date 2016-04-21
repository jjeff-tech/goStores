<?php
$var_staffid = $_SESSION["sess_staffid"];


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
}

$sql = "Select s.nStaffId, s.vStaffname from  sptbl_staffratings r INNER JOIN sptbl_staffs s ON r.nStaffId = s.nStaffId
        LEFT JOIN sptbl_staffdept sd ON r.nStaffId = sd.nStaffId
        LEFT JOIN sptbl_depts dept ON sd.nDeptId = dept.nDeptId
        where s.vDelStatus='0' AND s.vStaffname != 'Administrator'  ";

$qryopt="";

if($_POST["txtCompany"] != ""){
		$var_companySearch = $_POST["txtCompany"];
}
if($_POST["txtDept"] != ""){
		$var_deptSearch = $_POST["txtDept"];
}

if($var_companySearch != ""){
	
	$qryopt .= " AND dept.nCompId = '" . addslashes($var_companySearch) . "'";
	if($var_deptSearch != ""){
			$qryopt .= " AND sd.nDeptId = '" . addslashes($var_deptSearch) . "'";
	}
}

$sql .= $qryopt . " GROUP BY s.nStaffId  Order By s.vStaffname Asc ";
//echo $sql;

// Listing Company
$sql_company = "Select nCompId, vCompName from  sptbl_companies where vDelStatus='0' ";
$rs_company  = executeSelect($sql_company,$conn);
$company     = array();
while($row_company = mysql_fetch_array($rs_company)){

    $company[$row_company['nCompId']]   =   $row_company['vCompName'];

}

?>
<script type="text/javascript">

    $(document).ready(function(){

       var companyId    =   '<?php echo $var_companySearch; ?>';
       var dept         =   '<?php echo $var_deptSearch; ?>';
       
       if(companyId != ''){

           var dataString = {"companyId":companyId};

           $.ajax({
            url	        :"ajax_response.php",
            type	:"post",
            data	:dataString,
            dataType 	:"json",
            success	:function(data){
                
                $('#txtDept').empty();
                if(data!='')
                    {
                        jQuery.each(data, function(index, value)
                        {
                            //alert(index + ': ' + value);
                            $("#txtDept").append(new Option(value, index));                            

                        });
                        if(dept != '')
                        {                            
                            $("#txtDept").val( dept ).attr('selected','selected');
                        }

                    }                    

            }
            });

       }

       
       $('#txtCompany').change(function() {
          var companyId    =   $(this).val();
          var dataString = {"companyId":companyId};

          if(companyId == '')
              {
                  $('#txtDept').empty();
                  $('form#frmDetail').submit();
              }
          else
              {
                $.ajax({
                url	        :"ajax_response.php",
                type	:"post",
                data	:dataString,
                dataType 	:"json",
                success	:function(data){

                    $('#txtDept').empty();
                    if(data!='')
                        {
                            jQuery.each(data, function(index, value)
                            {
                                //alert(index + ': ' + value);
                                $("#txtDept").append(new Option(value, index));
                            });

                        }

                        $('form#frmDetail').submit();

                }
                });
              }

    });


    $('#txtDept').change(function() {
        
        $('form#frmDetail').submit();

    });

    });
</script>
<style>

    /*-------------------Rating Star ------------------*/

            .rating_5{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px 0px;
                      float:left;

            }
            .rating_4{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -14px;
                      float:left;


            }
            .rating_3{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -28px;
                      float:left;

            }
            .rating_2{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -43px;
                      float:left;

            }
            .rating_1{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -57px;
                      float:left;

            }
            .rating_0{width:71px;
                      height:12px;
                      background-image:url(../images/rating_sprite.png);
                      background-position: 0px -71px;
                      float:left;

            }

</style>
<form name="frmDetail" id="frmDetail" action="rating.php?mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&" method="post">
<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo HEADING_STAFF_DETAILS ?></h3>
			</div>
			
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="100%"> 
                                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                           
                                                            <tr>
                                                                <td width="61%" align="right" class="whitebasic">
																<div class="content_search_container" style="background-color:#ffffff; ">
																	<div class="left rightmargin topmargin">
																		<?php echo TEXT_CHOOSE ?> 
																	</div>
																	<div class="left rightmargin">
																		 <select name="txtCompany" id="txtCompany" class="selectstyle">
                                                                    <option value=""><?php echo TEXT_COMPANY ?></option>
                                                                    <?php
                                                                    if(!empty ($company)){

                                                                        foreach ($company as $key => $value) {
                                                                    ?>

                                                                        <option value="<?php echo $key; ?>" <?php echo(($key == $var_companySearch)?"Selected":""); ?>><?php echo $value; ?></option>

                                                                    <?php
                                                                        }//end for each
                                                                    }//end if
                                                                    ?>                                                                    
                                                                  </select>
																	</div>
																	<div class="left">
																	<select name="txtDept" id="txtDept" class="selectstyle">
                                                                    <option value=""><?php echo TEXT_DEPARTMENT ?></option>
                                                                    
                                                                  </select>
																	</div>
																	<div class="left">
																	</div>
																	<div class="clear"></div>
																</div>
                                                            </td>
                                                            </tr>
                                                               
                                                                <tr><td colspan="2" align="center" class="errormessage">
																
																<?php
																
																if ($var_message != ""){
																?>
																	<div class="msg_error">
																<b><?php echo($var_message); ?></b>
																</div>
																<?php
																}
																?>			
			

																</td></tr>
                                                        </table>
                                                </td>
                                        </tr>
                                        <tr>
                                          <td class="whitebasic" ><table width="100%"  border="0" cellpadding="2" cellspacing="0" class="list_tbl" >
                                              <tr align="left"  class="listing">
											    <th width="4%">&nbsp;</th>
                                                <th width="50%" ><?php echo "<b>".TEXT_STAFF."</b>"; ?></th>
                                                <th width="50%"><?php echo "<b>".TEXT_RATING."</b>"; ?></th>
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

//echo $totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLESTAFF&styleminus=minus5&styleplus=plus5&",$var_numBegin,$var_start,$var_begin,$var_num;
$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLESTAFF&styleminus=minus5&styleplus=plus5&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
$rs = executeSelect($sql,$conn);
$cnt = 1;

if(mysql_num_rows($rs) > 0){
    
    while($row = mysql_fetch_array($rs)) {

        $sql_rating = "Select SUM(nMarks) as Rating, count(*) as TotalRatings from  sptbl_staffratings where nStaffId = '".$row["nStaffId"]."' GROUP BY nStaffId ";
        $rs_rating  = executeSelect($sql_rating,$conn);
        $row_rating = mysql_fetch_array($rs_rating);
        $avgrating  = ceil($row_rating['Rating']/$row_rating['TotalRatings']);


?>

                                              <tr align="left"  class="whitebasic">
                                                <td width="4%" align="center"><?PHP echo $cnt; ?></td>
                                                <td><a href="ratingdetails.php?id=<?php echo $row["nStaffId"]; ?>&mt=y&stylename=STYLESTAFF&styleminus=minus7&styleplus=plus7&">
                                                <?PHP echo htmlentities($row["vStaffname"]); ?></a></td>
                                                <td><span class="rating_<?php echo $avgrating; ?>"></span></td>
                                              </tr>
                                                <?php
                                                    $cnt++;
                                                    }//end while
                                                    mysql_free_result($rs);
                                                }//end if                                               
                                                ?>
                                              <tr align="left"  class="listingmaintext">
                                                <td colspan="7">
												
												<div class="pagination_info">
												<?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?>
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
                                                        
                                                </td>
                                             </tr>
                                          </table></td>
                                        </tr>
                                    </table>

                  </td>
            </tr>
          </table>

                  
			</div>
</form>