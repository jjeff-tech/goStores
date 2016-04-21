<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	$addOredit = HEADING_ADD_ESCALATION;
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
		$addOredit = HEADING_EDIT_ESCALATION;
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
		$addOredit = HEADING_EDIT_ESCALATION;
	} 
	if ($_GET["stylename"] != "") {
		$var_styleminus = $_GET["styleminus"];
		$var_stylename = $_GET["stylename"];
		$var_styleplus = $_GET["styleplus"];
	}
	else {
		$var_styleminus = $_POST["styleminus"];
		$var_stylename = $_POST["stylename"];
		$var_styleplus = $_POST["styleplus"];
	}	
	$var_country = "UnitedStates";	
	
	if ($_POST["postback"] == "" && $var_id != "") { // If edit Rule Get Rule Detial
		
		$sql = "Select * from  sptbl_escalationrules  WHERE nERId = '".$var_id."' ";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			
			$ruleName       =  trim($var_row["vRuleName"]);
			$comapny        =  trim($var_row["nCompId"]);
			$dept           =  trim($var_row["nDeptId"]);
			$staff          =  trim($var_row["nStaffId"]);
			$sett_time      =  trim($var_row["eRespTimeSetting"]);
			$sett_count     =  trim($var_row["eRespCountSetting"]);
			$txtTime        =  trim($var_row["nResponseTime"]);
			$txtCount       =  trim($var_row["nResponseCount"]);

                        $settings  = ($sett_time     == 'Y')?"T":"";

                        if($settings == '')
                            $settings  = ($sett_count    == 'Y')?"C":"";

                        if($settings == 'T')
                            $txtResponseSetting = ($txtTime    == '0')?"":$txtTime;
                        else
                            $txtResponseSetting = ($txtCount    == '0')?"":$txtCount;
		}
		else {
			$var_id="";	
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
		mysql_free_result($var_result);
	}
	elseif ($_POST["postback"] == "A") { // Inserting New Rule
			$ruleName           = trim($_POST["txtRuleName"]);
			$comapny            = trim($_POST["txtCompany"]);
			$dept               = trim($_POST["txtDept"]);
			$staff              = trim($_POST["txtStaff"]);
			$settings           = trim($_POST["settings"]);
			$txtResponseSetting = trim($_POST["txtResponseSetting"]);
			$var_message="";

                        $Time_settings      = ($settings      == 'T')?"Y":"N";
                        $Count_settings     = ($settings      == 'C')?"Y":"N";

                        $txtTime            = ($Time_settings == 'Y')?$txtResponseSetting:"";
                        $txtCount           = ($Count_settings == 'Y')?$txtResponseSetting:"";
                        
		if (validateAddition($ruleName,$var_message) == true) {
			//Insert into the company table
			$sql = "Insert into sptbl_escalationrules(nERId,vRuleName,nCompId,nDeptId,eRespTimeSetting,eRespCountSetting,nResponseTime,nResponseCount,nStaffId,";
			$sql .= "nStatus) Values('','" . mysql_real_escape_string($ruleName) . "',
					'" . mysql_real_escape_string($comapny). "','" . mysql_real_escape_string($dept) . "','" . mysql_real_escape_string($Time_settings) . "',
					'" . mysql_real_escape_string($Count_settings) . "','" . mysql_real_escape_string($txtTime) . "','" . mysql_real_escape_string($txtCount) . "',
					'" . mysql_real_escape_string($staff) . "','0')";
			executeQuery($sql,$conn);
			 
			 $var_insert_id     = mysql_insert_id($conn);

			$var_message        = MESSAGE_RECORD_ADDED;
                        $flag_msg           = 'class="msg_success"';
                        $ruleName           = "";
                        $comapny            = "";
                        $dept               = "";
                        $staff              = "";
                        $settings           = "";
                        $txtResponseSetting = "";
                        $var_id             = "";
		}
                else {
			$var_message = MESSAGE_RECORD_DUPILCATION ;
                        $flag_msg           = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "D") { // Deleting Rule
			$ruleName           = trim($_POST["txtRuleName"]);
			$comapny            = trim($_POST["txtCompany"]);
			$dept               = trim($_POST["txtDept"]);
			$staff              = trim($_POST["txtStaff"]);
			$settings           = trim($_POST["settings"]);
			$txtResponseSetting = trim($_POST["txtResponseSetting"]);
                        
		if (validateDeletion($var_id) == true) {
			$sql = "DELETE FROM sptbl_escalationrules where nERId='" . mysql_real_escape_string($var_id) . "'";
			executeQuery($sql,$conn);	
			
                        $ruleName           = "";
                        $comapny            = "";
                        $dept               = "";
                        $staff              = "";
                        $settings           = "";
                        $txtResponseSetting = "";
                        $var_id             = "";
			$var_message        = MESSAGE_RECORD_DELETED;
                        $flag_msg           = 'class="msg_success"';
		}
		else {
			$var_message        = MESSAGE_RECORD_ERROR ;
                        $flag_msg           = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "U") { // Updating Rule
			$ruleName           = trim($_POST["txtRuleName"]);
			$comapny            = trim($_POST["txtCompany"]);
			$dept               = trim($_POST["txtDept"]);
			$staff              = trim($_POST["txtStaff"]);
			$settings           = trim($_POST["settings"]);
			$txtResponseSetting = trim($_POST["txtResponseSetting"]);
			$var_message="";

                        $Time_settings      = ($settings      == 'T')?"Y":"N";
                        $Count_settings     = ($settings      == 'C')?"Y":"N";

                        $txtTime            = ($Time_settings == 'Y')?$txtResponseSetting:"";
                        $txtCount           = ($Count_settings == 'Y')?$txtResponseSetting:"";
                        
			$var_message = "";
			if (validateUpdation($ruleName,$var_message) == true) {
				$sql = "Update sptbl_escalationrules set vRuleName='" . mysql_real_escape_string($ruleName) . "',
					nCompId='" . mysql_real_escape_string($comapny) . "',
					nDeptId='" . mysql_real_escape_string($dept). "',
					eRespTimeSetting='" . mysql_real_escape_string($Time_settings) . "',
					eRespCountSetting='" . mysql_real_escape_string($Count_settings) . "',
					nResponseTime='" . mysql_real_escape_string($txtTime) . "',
					nResponseCount='" . mysql_real_escape_string($txtCount) . "',
					nStaffId='" . mysql_real_escape_string($staff). "'
                                        where nERId='" . mysql_real_escape_string($var_id) . "'";
				executeQuery($sql,$conn);
				$var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
			}
                        else {
                                $var_message =  MESSAGE_RECORD_DUPILCATION ;
                                $flag_msg    = 'class="msg_error"';
                        }
	}
	
	function validateAddition($ruleName,&$var_message)
	{
		global $conn,$flag_msg,$var_message;
		
		if (trim($_POST["txtRuleName"]) == "" || trim($_POST["txtCompany"]) == "" || trim($_POST["txtDept"]) == "" || trim($_POST["txtStaff"]) == "") {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
			return false;
		}
		else {
			$sql = "Select vRuleName from sptbl_escalationrules where vRuleName='" . mysql_real_escape_string(trim($_POST["txtRuleName"])) . "'";
			if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
				$var_message = TEXT_RULE_DUPLICATE ;
                                $flag_msg    = 'class="msg_error"';
				return false;
			}
			else {
				return true;
			}	
		}
	}
	
	function validateDeletion($var_list) 
	{
		//implement logic here
		global $conn;
		$sql = "Select nERId from sptbl_escalationrules where nERId IN($var_list)";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			return true;
		}
		else {
			return false;
		}
	}
	
	function validateUpdation($ruleName,&$var_message)
	{
		global $conn,$var_id,$flag_msg;
		//implement logic here
		$sql = "Select nERId from sptbl_escalationrules where nERId='" . $var_id . "'";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			if (trim($_POST["txtRuleName"]) == "" || trim($_POST["txtCompany"]) == "" || trim($_POST["txtDept"]) == "" || trim($_POST["txtStaff"]) == "") {
				$var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
				return false;
			}
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
			return false;
		}
		$sql = "Select nERId from sptbl_escalationrules Where vRuleName='" . mysql_real_escape_string(trim($_POST["txtRuleName"])) . "' AND nERId !='" . mysql_real_escape_string($var_id) . "' ";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			$var_message = MESSAGE_RECORD_DUPILCATION ;
                        $flag_msg    = 'class="msg_error"';
			return false;
		}
		return true;
	}


// Listing Company
$sql_company = "Select nCompId, vCompName from  sptbl_companies where vDelStatus='0' ";
$rs_company  = executeSelect($sql_company,$conn);
$company     = array();
while($row_company = mysql_fetch_array($rs_company)){

    $company[$row_company['nCompId']]   =   $row_company['vCompName'];

}
?>

<script type="text/javascript">

       var ER_Id           =   '<?php echo $var_id; ?>';
       var companyId       =   '<?php echo $comapny; ?>';
       var dept            =   '<?php echo $dept; ?>';
       var staff           =   '<?php echo $staff; ?>';
       var response        =   '<?php echo $settings; ?>';
       
    $(document).ready(function(){

       if(ER_Id == ''){

           document.frmEscalation.btAdd.disabled=false;
           document.frmEscalation.btUpdate.disabled=true;
           document.frmEscalation.btDelete.disabled=true;
           
       }else{

           document.frmEscalation.btAdd.disabled=true;
           document.frmEscalation.btUpdate.disabled=false;
           document.frmEscalation.btDelete.disabled=false;

       }

       if(response == 'T' || response == ''){

            $('#span_response').text('<?php echo TEXT_MIN ?>');
            $('#ticketlabel').text('<?php echo TEXT_RESPONSE_TIME ?>');

       }else{

            $('#span_response').text('<?php echo TEXT_COUNT ?>');
            $('#ticketlabel').text('<?php echo TEXT_RESPONSE_COUNT ?>');

       }

       if(companyId != ''){

           var dataString = {"companyId":companyId};

           $.ajax({
            url	        :"ajax_response.php",
            type	:"post",
            async       :true,
            data	:dataString,
            dataType 	:"json",
            success	:function(data){

                $('#txtDept').empty();
                $('#txtStaff').empty();
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
                        if(staff != '')
                        {
                            $('#txtDept').change();                            
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
                  $('#txtStaff').empty();
              }
          else
              {
                $.ajax({
                url	:"ajax_response.php",
                type	:"post",
                async   :true,
                data	:dataString,
                dataType:"json",
                success	:function(data){

                    $('#txtDept').empty();
                    $('#txtStaff').empty();
                    if(data!='')
                        {
                            jQuery.each(data, function(index, value)
                            {
                                //alert(index + ': ' + value);
                                $("#txtDept").append(new Option(value, index));
                                $('#txtDept').change();
                            });

                        }                       

                }
                });
              }

    });


    $('#txtDept').change(function() {
        
          if(dept != ''){
              var deptId    =   dept;
          }
          if($(this).val() != ''){
            var deptId    =   $(this).val();
          }
       
          var dataString = {"deptId":deptId};

          if(deptId == '')
              {
                  $('#txtStaff').empty();
              }
          else
              {
                $.ajax({
                url	:"ajax_response.php",
                type	:"post",
                data	:dataString,
                dataType:"json",
                success	:function(data){

                    $('#txtStaff').empty();
                    if(data!='')
                        {
                            jQuery.each(data, function(index, value)
                            {
                                //alert(index + ': ' + value);
                                $("#txtStaff").append(new Option(value, index));
                            });
                            if(staff != ''){
                                $("#txtStaff").val( staff );
                            }
                        }                       

                }
                });
              }

    });

    $('#sett_time').click(function(){

        $('#span_response').text('<?php echo TEXT_MIN ?>');
        $('#ticketlabel').text('<?php echo TEXT_RESPONSE_TIME ?>');

    });

    $('#sett_count').click(function(){

        $('#span_response').text('<?php echo TEXT_COUNT ?>');
        $('#ticketlabel').text('<?php echo TEXT_RESPONSE_COUNT ?>');

    });

    });
</script>
<div class="content_section">
<form name="frmEscalation" method="POST" action="editescalation.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&">
<div class="content_section_title">
<h3><?php echo $addOredit ?></h3>
</div>

<div class="content_section_data">
   
     
    


         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="comm_tbl">


		<tr>
         <td align="center" colspan=3 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>

         <tr>
             <?php if($var_message != ''){ ?>
         <td align="center" colspan=3 <?php echo $flag_msg; ?>>
         <?php echo $var_message ?></td>
         <?php } ?>

         </tr>

                     
                      <tr>
                         <td width="8%" align="left">&nbsp;</td>
                         <td width="38%" align="left" class="toplinks"><?php echo TEXT_RULE_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
                         <td width="54%" align="left">
                         <input name="txtRuleName" type="text" class="comm_input  input_width1" id="txtRuleName" size="30" maxlength="100" value="<?php echo htmlentities($ruleName); ?>">
                         </td>
                        </tr>
                       
                        <tr>
                          <td align="left">&nbsp;</td>
                          <td align="left" class="toplinks"><?php echo TEXT_COMPANY ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                          <td width="54%" align="left">
                              <select name="txtCompany" id="txtCompany" class="comm_input  input_width1">
                                <option value=""><?php echo TEXT_COMPANY ?></option>
                                <?php
                                if(!empty ($company)){

                                    foreach ($company as $key => $value) {
                                ?>

                                    <option value="<?php echo $key; ?>" <?php echo(($key == $comapny)?"Selected":""); ?>><?php echo $value; ?></option>

                                <?php
                                    }//end for each
                                }//end if
                                ?>
                              </select>
                          </td>
                        </tr>
                       
                        <tr>
                          <td align="left">&nbsp;</td>
                          <td align="left" class="toplinks"><?php echo TEXT_DEPARTMENT ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                          <td width="54%" align="left">
                               <select name="txtDept" id="txtDept" class="comm_input  input_width1">
                                <option value=""><?php echo TEXT_DEPARTMENT ?></option>

                              </select>
                          </td>
                        </tr>
                      
                        <tr>
                          <td align="left">&nbsp;</td>
                          <td align="left" class="toplinks"><?php echo TEXT_ESCALATE_TO ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                          <td width="54%" align="left">
                               <select name="txtStaff" size="1" class="comm_input  input_width1" id="txtStaff" >
                                <option value=""><?php echo TEXT_STAFF ?></option>
                            </select>
                          </td>
                        </tr>
                       
                        <tr>
                          <td align="left">&nbsp;</td>
                          <td align="left" class="toplinks"><?php echo TEXT_SETTINGS ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                          <td width="54%" align="left">
                              <input type="radio" name="settings" id="sett_time" value="T"  <?php echo(($settings == 'T')?"checked":""); ?> <?php echo(($settings == '')?"checked":""); ?>>&nbsp;<?php echo TEXT_SETTINGS_NAME1 ?>
                              <input type="radio" name="settings" id="sett_count" value="C" <?php echo(($settings == 'C')?"checked":""); ?>><?php echo TEXT_SETTINGS_NAME2 ?>
                          </td>
                        </tr>
                        
                        <tr>
                          <td align="left">&nbsp;</td>
                          <td align="left" class="toplinks"><span id="ticketlabel"><?php echo TEXT_RESPONSE_TIME ?></span> <font style="color:#FF0000; font-size:9px">*</font></td>
                          <td width="54%" align="left">
                              <input name="txtResponseSetting" type="text" class="comm_input  input_width1" id="txtResponseSetting" size="30" maxlength="20" value="<?php echo htmlentities($txtResponseSetting); ?>">&nbsp;
                              <span id="span_response"><?php echo TEXT_MIN ?></span>
                              <!--<br><br>
                              <input name="txtCount" type="text" class="comm_input  input_width1" id="txtCount" size="30" maxlength="20" value="<?php echo htmlentities($txtCount); ?>">&nbsp;<?php echo TEXT_COUNT ?>-->
                          </td>
                        </tr>
                    
                      </table>
                
			<div class="comm_spacediv">&nbsp;</div>
			
           
                 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                  
                                    <td align="center">
 <input type="button" class="comm_btn" name="btBack" id="btBack" value="<?php echo(TEXT_MAIN_BACK); ?>" onClick="javascript:window.location.href='<?php echo SITE_URL?>admin/escalations.php?mt=y&stylename=STYLETICKETS&styleminus=minus9&styleplus=plus9&';">

									&nbsp;&nbsp;<input name="btAdd" id="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();">
                                    &nbsp;&nbsp;<input name="btUpdate" id="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();">
                                   &nbsp;&nbsp;<input name="btDelete" id="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();">
                                  &nbsp;&nbsp; <input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL; ?>" onClick="javascript:cancel();">
                                   
									&nbsp;&nbsp;<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
									&nbsp;&nbsp;<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
									&nbsp;&nbsp;<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
									&nbsp;&nbsp;<input type="hidden" name="id" value="<?php echo($var_id); ?>">
									&nbsp;&nbsp;<input type="hidden" name="postback" value="">
									</td>
                                  </tr>
                              </table>
		
</form>
</div>
</div>
