<?php
$var_staffid=$_SESSION["sess_staffid"];
if ($_POST["postback"] == "R") {
    $var_companyid= trim($_POST["cmbCompany"]);
    $var_sdate = trim($_POST["txtSdate"]);
    $var_edate = trim($_POST["txtEdate"]);
}
if($var_sdate=="") {
    //$var_sdate=date("m-d-Y H:i");
    $var_sdate=date("m-d-Y H:i", strtotime("$from_date -30 days"));
}
if($var_edate=="") {
    $var_edate=date("m-d-Y H:i");
}
if($_GET["stylename"] != "") {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
}
elseif($_POST["stylename"] !="") {

    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}
?>
<div class="content_section">
<form name="frmrepCompTicket" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
 
<div class="content_section_title"><h3><?php echo TEXT_REPORTS_COMPANY ?></h3></div>
            
<div class="content_search_container">
						<div class="left rightmargin topmargin">
						<?php echo TXT_SELECT_COMPANY; ?>
						</div>
						
						<div class="left rightmargin">
						 <?php
                                                                                $sql = "SELECT nCompId,vCompName   FROM `sptbl_companies` where vDelStatus=0 order by vCompName";
                                                                                $rs = executeSelect($sql,$conn);
                                                                                $cnt = 1;
                                                                                ?>
                                                                                <select name="cmbCompany" size="1" class="comm_input input_width1" id="cmbCompany" >
                                                                                    <?php
                                                                                    $options ="<option value='0'";
                                                                                    $options .=">".TEXT_SELECT_ALL."</option>\n";
                                                                                    echo $options;
                                                                                    while($row = mysql_fetch_array($rs)) {
                                                                                        $options ="<option value='".$row['nCompId']."'";
                                                                                        if ($var_companyid == $row['nCompId']) {

                                                                                            $options .=" selected=\"selected\"";
                                                                                        }
                                                                                        $options .=">".htmlentities($row['vCompName'])."</option>\n";
                                                                                        echo $options;
                                                                                    }

                                                                                    ?>
                                                                                </select>
						</div>
						
						<div class="right">
						
							<div class="left topmargin">
							<?php echo TXT_SELECT_DATE_RANGE ?>&nbsp;&nbsp;
							</div>
							
							<div class="left">
							 <input name="txtSdate" type="text" class="comm_input input_width3" id="txtSdate" size="20" maxlength="100" value="<?php echo htmlentities($var_sdate); ?>"  readonly >
                                                                                <input type="button" value="v" id="button1" name="button1"  class="comm_btn" >
                                                                                <script type="text/javascript">
                                                                                    Calendar.setup({
                                                                                        inputField    	: "txtSdate",
                                                                                        button        : "button1",
                                                                                        ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
                                                                                        showsTime      	: true,
                                                                                        timeFormat     	: "24"
                                                                                    });
                                                                                </script>&nbsp;&nbsp;
							</div>
							
							
							<div class="left">
							<input name="txtEdate" type="text" class="comm_input input_width3" id="txtEdate" size="20" maxlength="100" value="<?php echo htmlentities($var_edate); ?>"  readonly >
                                                                                <input type="button" value="V" id="button2" name="button2"  class="comm_btn">
                                                                                <script type="text/javascript">
                                                                                    Calendar.setup({
                                                                                        inputField    	: "txtEdate",
                                                                                        button          : "button2",
                                                                                        ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
                                                                                        showsTime      	: true,
                                                                                        timeFormat     	: "24"
                                                                                    });
                                                                                </script>
							</div>
						
						
						<div class="clear"></div>
						</div>
						
					<div class="clear"></div>
					</div>                      

                                                                    
                                                                
     <div class="content_section_data btm_brdr" align="center">                                     
              

   
  
  
               
			  
                                                    <input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_RUN_REPORT ?>" onClick="javascript:clickRunreport();">                                    </td></tr>
                                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                            <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                            <input type="hidden" name="postback" value="">
                                       
							</div>     
                
				
				
	</div>
    
                    <?php
                    if ($_POST["postback"] == "R") {
                        require("./includes/repticketcomp_detail_new.php");
                    }
                    ?>
                


</form>
