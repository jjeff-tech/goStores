<?php
$var_staffid = $_SESSION["sess_staffid"];
if ($_POST["postback"] == "R") {
    $var_usercmbid = trim($_POST["cmbUser"]);

    $var_sdate = trim($_POST["txtSdate"]);
    $var_edate = trim($_POST["txtEdate"]);
}

if ($var_sdate == "") {
    //$var_sdate=date("m-d-Y H:i");
    $var_sdate = date("m-d-Y H:i", strtotime("$from_date -30 days"));
}
if ($var_edate == "") {
    $var_edate = date("m-d-Y H:i");
}
if ($_GET["stylename"] != "") {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
} elseif ($_POST["stylename"] != "") {

    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}
?>
<div class="content_section">
<form name="frmrepCompTicket" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<div class="content_section_title">
	<h3><?php echo TEXT_REPORTS_STAFF_SUMMARY ?></h3>
</div>
          
		  
<div class="content_search_container">
	<div class="left rightmargin topmargin">
						<?php echo TXT_SELECT_USER; ?>
	</div>
						
						<div class="left rightmargin">
						<input type="text" name="txtUsername" value="<?php echo stripslashes($_POST['txtUsername']);?>" class="comm_input input_width1" maxlength="100" size="100" id="txtautoComplete" >
                                                                           <input type="hidden" name="cmbUser" value="<?php echo stripslashes($_POST['cmbUser']);?>" class="textbox" id="txtUserid"  >
						</div>
						
						<div class="right">
						<div class="left topmargin">
						<?php echo TXT_SELECT_DATE_RANGE ?>&nbsp;
						</div>
						
						<div class="left">
							<input name="txtSdate" type="text" class="comm_input input_width1" id="txtSdate" size="20" maxlength="100" value="<?php echo htmlentities($var_sdate); ?>"  readonly >
                                                                                        <input type="button" value="V" id="button1" name="button1"  class="comm_btn" style=" width:30px;">
                                                                                        <script type="text/javascript">
                                                                                            Calendar.setup({
                                                                                                inputField    	: "txtSdate",
                                                                                                button        : "button1",
                                                                                                ifFormat      	: "%m-%d-%Y %H:%M",       // format of the input field
                                                                                                showsTime      	: true,
                                                                                                timeFormat     	: "24"
                                                                                            });
                                                                                        </script>
						</div>
						
						<div class="left">
						<input name="txtEdate" type="text" class="comm_input input_width1" id="txtEdate" size="20" maxlength="100" value="<?php echo htmlentities($var_edate); ?>" style="width:170px;" readonly >
                                                                                        <input type="button" value="V" id="button2" name="button2"  class="comm_btn" style=" width:30px;">
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

                                                                           
                                                                        
																
                

    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
        <tr>
            <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                        <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr align="center" valign="middle">
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                <tr>
                                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                            <tr align="center" class="listingbtnbar">
                                                <td valign="middle"> 
                                              <input name="btnDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_RUN_REPORT ?>" onClick="javascript:clickRunreport();"></td>
                                            </tr>
                                            <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                            <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                            <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
                                            <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                            <input type="hidden" name="postback" value="">
                                        </table></td>
                                </tr>
                      </table></td>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                  </tr>


              </table>
                </td>			   
        </tr>
    </table>
	
	<div style="margin:auto 0; width:100%; text-align: center;">
    <table border="0" cellspacing="0"  cellpadding="0" width="100%">
        <tr>
            <td align="center">
             
<?php
if ($_POST["postback"] == "R") {
    require("./includes/repticketusersummarydetail.php");
}
?>
            
          </td>
        </tr>
    </table>
	
	</div>
	
</td>
</tr>
</table>			
</form>

</div>

<script type="text/javascript" src="../scripts/jquery.autocomplete.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var site_url ='<?php echo SITE_URL ?>';
    
        $("#txtautoComplete").autocomplete(site_url+"admin/autocomplete.php", {
            selectFirst: true
        });
    });
    
    function getUserdata()
    {
        //empty function
    }
</script>