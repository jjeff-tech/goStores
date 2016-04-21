<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                      | 
// +----------------------------------------------------------------------+
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
        //$var_userid = $_SESSION["sess_staffid"];
        $var_staffid = "1";
      
      if ($_POST["postback"] == "U") {
			 $var_companyid = trim($_POST["cmbCompany"]);
             $var_welcomemsg = trim($_POST["txtWelcomeMsg"]);
             $var_chaticon = trim($_POST["rdIcon"]);
			 $var_codesnippet = trim($_POST["txtCodeSnippet"]);
             $var_oprating = trim($_POST["rdOpRating"]);
			
				//$sql = "Update sptbl_companies set vChatWelcomeMessage='" . mysql_real_escape_string($var_welcomemsg) ."', vChatCodeSnippet='".mysql_real_escape_string($var_codesnippet)."', vChatOperatorRating='".mysql_real_escape_string($var_oprating)."', vChatIcon ='".mysql_real_escape_string($var_chaticon)."' where nCompId='".$var_companyid."'";
			   $sql = "Update sptbl_companies set vChatWelcomeMessage='" . mysql_real_escape_string($var_welcomemsg) ."', vChatOperatorRating='".mysql_real_escape_string($var_oprating)."', vChatIcon ='".mysql_real_escape_string($var_chaticon)."' where nCompId='".$var_companyid."'";
			   executeQuery($sql,$conn);
	
				//Insert the actionlog
				if(logActivity()) {
				   $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','ChatSettings','0',now())";
				   executeQuery($sql,$conn);
				}
				  $var_message = MESSAGE_RECORD_UPDATED;
                                  $flag_msg    = 'class="msg_success"';
		}
		if ($_POST["postback"] == "C") {
		   $var_companyid = trim($_POST["cmbCompany"]);
        } 
                //$sql = "Select vChatWelcomeMessage, vChatIcon, vChatCodeSnippet, vChatOperatorRating, vChatIcon from sptbl_companies where nCompId='".$var_companyid."'";
                $sql = "Select vChatWelcomeMessage, vChatIcon, vChatOperatorRating from sptbl_companies where nCompId='".$var_companyid."'";
				$var_result = executeSelect($sql,$conn);
				if (mysql_num_rows($var_result) > 0) {
                    while($var_row = mysql_fetch_array($var_result)){
                       $var_welcomemsg =$var_row["vChatWelcomeMessage"];
                       $var_chaticon =$var_row["vChatIcon"];
                       $var_oprating =$var_row["vChatOperatorRating"];
					  // $var_codesnippet =$var_row["vChatCodeSnippet"];
					   $sql = "Select vLookUpValue from sptbl_lookup where vLookUpName='HelpDeskURL'";
					   $res = executeSelect($sql,$conn);
					   if (mysql_num_rows($res) > 0) {
					      $row = mysql_fetch_array($res);
						  $site = $row['vLookUpValue'];
 					   }
					   $site = preg_replace('/index.php/','',$site);
					   $var_codesnippet="<a href=\"\" onClick=\"window.open('".$site."index_client_chat.php?comp=".$var_companyid."&ref=visitorChat','LiveChat','width=475,height=635,resizable=yes,location=no');\">";
           			   //$var_codesnippet .="<img id=\"lCIcon\" src=\"".$site."getChatIcon.php?comp=".$var_companyid."&page=\" width=\"100\" height=\"35\" border=\"0\"></img>";
           			   $var_codesnippet .="<script type=\"text/javascript\">var pg = window.location.href;document.write('<img id=\"lCIcon\" src=\"".$site."getChatIcon.php?comp=".$var_companyid."&page='+pg+'\" width=\"100\" height=\"35\" border=\"0\"></img>');</script>";
 				       $var_codesnippet .="<script language=\"JavaScript\" type=\"text/javascript\" src=\"".$site."scripts/chatVisiting.js\"></script>";
					   $var_codesnippet .="<script language=\"JavaScript\" type=\"text/javascript\">var tmr_g;	tmr_g =setTimeout(\"updateIcon(".$var_companyid.")\",2000); function updateIcon(cmp) { var pg = window.location.href; document.getElementById('lCIcon'). src=\"".$site."getChatIcon.php?comp=\"+cmp+\"&page=\"+pg; chatInvoke(cmp,'".$site."', pg ); if (tmr_g) clearTimeout(tmr_g); tmr_g =setTimeout(\"updateIcon(\"+cmp+\")\",2000);}</script>";
                    }
                }
                else {
                      $var_welcomemsg="";
                      $var_chaticon="";
                      $var_codesnippte="";
                      $var_oprating="";
               }
                mysql_free_result($var_result);
		
?>
<script language="javascript">
function clickyes()
{
	document . getElementById("auth1") . style . display = "";
}

function clickno()
{
	document . getElementById("auth1") . style . display = "none";
}

function clicksmtpyes()
{
	document . getElementById("smtp1") . style . display = "";
	document . getElementById("smtp2") . style . display = "";
}

function clicksmtpno()
{
	document . getElementById("smtp1") . style . display = "none";
	document . getElementById("smtp2") . style . display = "none";
}
</script>
<div class="content_section">
<form name="frmChatConfig" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" enctype="multipart/form-data">

<div class="content_section_title">
<h3><?php echo TEXT_EDIT_CHATSETTINGS ?></h3>
</div>
   
<div class="content_section_data">
   

     
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" >
		
         <tr><?php if($var_message != ''){ ?>
    	     <td align="center" colspan=3 <?php echo $flag_msg; ?>>
	    	 <?php echo $var_message ?></td>
             <?php } ?>
         </tr>
         <tr>
	         <td align="center" colspan=3 class="fieldnames">
    	     <?php echo TEXT_FIELDS_MANDATORY ?></td>
         </tr>
		 
		 <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
                     <td width="7%" align="left">&nbsp;</td>
                     <td width="38%" align="left" class="fieldnames"><?php echo TEXT_COMPANY_NAME?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
					 <td width="55%" align="left">
					       <?php
						     $sql = "SELECT nCompId,vCompName   FROM `sptbl_companies` where vDelStatus=0 order by vCompName";			
 							 $rs = executeSelect($sql,$conn);
						  	 $cnt = 1;
							?>
						   <input type=hidden name="cmbCompanyhidden" value="<?php echo   $var_companyid?>">
		                   <select name="cmbCompany" size="1" class="comm_input input_width1a" id="cmbCompany" onchange="changecompany();" >
						     <?php
							               $options ="<option value='0'";
                                           $options .=">Select</option>\n"; 
										    echo $options;
											while($row = mysql_fetch_array($rs)) {
											  $options ="<option value='".$row['nCompId']."'";
											  if ($var_companyid == $row['nCompId']){
                                                 $options .=" selected=\"selected\"";
                                              }
                                              $options .=">".htmlentities($row['vCompName'])."</option>\n";
											  echo $options;
											}
                                            
                             ?>
						     
		                    </select>
		                   </td>
          </tr>
		  
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="6%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="fieldnames"><?php echo TEXT_WELCOME_MSG?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
         <td width="55%" align="left">
         <input name="txtWelcomeMsg" type="text" class="comm_input input_width1" id="txtWelcomeMsg" size="30" maxlength="100" value="<?php echo htmlentities($var_welcomemsg); ?>">
		 </td>
         </tr>
		 <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
		  <td width="6%" align="left">&nbsp;</td>
          <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CHAT_ICON?></td>
		  <td>
		   <table class="column1" border="1">
		    <tr><td><input name="rdIcon" type="radio" value="1"<?php if ($var_chaticon == '1') echo "checked"; ?>></td><td><img width="140" height="60" src="../images/chat/chat-icon-1-online.gif"></img>&nbsp;<img width="140" height="60"  src="../images/chat/chat-icon-1-offline.gif"></img></td></tr>
		    <tr><td><input name="rdIcon" type="radio" value="2"<?php if ($var_chaticon == '2') echo "checked"; ?>></td><td><img width="140" height="60" src="../images/chat/chat-icon-2-online.gif"></img>&nbsp;<img width="140" height="60" src="../images/chat/chat-icon-2-offline.gif"></img></td></tr>
   		    <tr><td><input name="rdIcon" type="radio" value="3"<?php if ($var_chaticon == '3') echo "checked"; ?>></td><td><img width="140" height="60" src="../images/chat/chat-icon-3-online.gif"></img>&nbsp;<img width="140" height="60" src="../images/chat/chat-icon-3-offline.gif"></img></td></tr>
   		    <tr><td><input name="rdIcon" type="radio" value="4"<?php if ($var_chaticon == '4') echo "checked"; ?>></td><td><img width="140" height="60" src="../images/chat/chat-icon-4-online.gif"></img>&nbsp;<img width="140" height="60" src="../images/chat/chat-icon-4-offline.gif"></img></td></tr>
		   </table>		  
		  </td>
		 </tr>
<!--
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="6%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="fieldnames"><?php //echo TEXT_STAFF_ONLINE_IMG?></td>
         <td width="55%" align="left" colspan="1">
		 <input name="txtStaffOnlineImg" type="file" class="textbox" id="txtStaffOnlineImg" size="30" maxlength="100" value="<?php //echo htmlentities($var_staffonlineimg); ?>">&nbsp;<img src="../images/chat/<?php //echo htmlentities($var_staffonlineimg)?>"></img>
		 </td>
         </tr>

		<tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="6%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="fieldnames"><?php //echo TEXT_STAFF_OFFLINE_IMG?></td>
         <td width="55%" align="left" colspan="1">
		 <input name="txtStaffOfflineImg" type="file" class="textbox" id="txtStaffOfflineImg" size="30" maxlength="100" value="<?php //echo htmlentities($var_staffofflineimg); ?>">&nbsp;<img src="../images/chat/<?php //echo htmlentities($var_staffofflineimg)?>"></img>
		 </td>
         </tr>
   -->    
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="6%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="fieldnames"><?php echo TEXT_CODESNIPPET?></td>
         <td width="55%" align="left">
         <textarea name="txtCodeSnippet" id="txtCodeSnippet" class="comm_input" style="width:400px;height:150px;"><?php echo htmlentities($var_codesnippet); ?></textarea>
		 </td>
         </tr>
		 
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="6%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="fieldnames"><?php echo TEXT_OPERATOR_RATING?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
         <td width="55%" align="left"  class="fieldnames">
         <input name="rdOpRating" type="radio" value="1"
         <?php echo(($var_oprating == 1)?"checked":""); ?>><?php echo(TEXT_YES); ?>
         <input name="rdOpRating" type="radio" value="0"
         <?php echo(($var_oprating == 0)?"checked":""); ?>>
         <?php echo(TEXT_NO); ?></td>
         </tr>

		 <!-- Knowledgebase display : Currently not using
		 <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="6%" align="left">&nbsp;</td>
         <td width="39%" align="left" class="fieldnames"><?php // echo TEXT_DISPLAY_KB?>&nbsp;<font style="color:#FF0000; font-size:9px">*</font></td>
         <td width="55%" align="left"  class="fieldnames">
         <input name="rdKB" type="radio" value="1"
         <?php //echo(($var_displaykb == 1)?"checked":""); ?>><?php //echo(TEXT_YES); ?>
         <input name="rdKB" type="radio" value="0"
         <?php //echo(($var_displaykb == 0)?"checked":""); ?>>
         <?php //echo(TEXT_NO); ?></td>
         </tr> -->
		 
		 <tr><td colspan="3">&nbsp;</td></tr>
         </table>
			
			
        <div class="content_section_data" align="center">
               
                                    <input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                   
											<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
											<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
											<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">

											<input type="hidden" name="postback" value="">
		</div>
                
          
</div>
</div>

</form>
