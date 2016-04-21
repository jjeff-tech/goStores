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
// |                                                                                          |
// +----------------------------------------------------------------------+

$totalsize=0;
function isValidlangcode($str)
{
    if (trim($str) !="" ) {
          if ( preg_match ( "[^0-9a-zA-Z+_]", $str ) ) {
                         return false;
             }else{
                    return true;
         }
    }else{
                return false;
    }

}
function getsize($source)
{    
			static $totalsize;						
					   $folder = opendir($source);
					   while($file = readdir($folder))
					   {
					       if ($file == '.' || $file == '..') {
					           continue;
					       }
					   
					       if(is_dir($source.'/'.$file))
						    
					       {
						       //echo "size of (".$source.'/'.$file.")".filesize($source.'/'.$file)."($totalsize)<br>";
					           $totalsize=$totalsize+filesize($source.'/'.$file);
					           getsize($source.'/'.$file);
							    
					       }
					       else 
					       {
					            //echo "size of (".$source.'/'.$file.")".filesize($source.'/'.$file)."($totalsize)<br>";
							   $totalsize=$totalsize+filesize($source.'/'.$file);
					       }
					       
					   }
					   closedir($folder);
					   return $totalsize;
}
$totalfile=0;
function getnumfiles($source)
{    
			  
            global $totalfile;						
					   $folder = opendir($source);
					   while($file = readdir($folder))
					   {
					       if ($file == '.' || $file == '..') {
					           continue;
					       }
					   
					       if(is_dir($source.'/'.$file))
						    
					       {
						       //echo "size of (".$source.'/'.$file.")".$totalfile."<br>";
					           $totalfile=$totalfile+1;
					           getnumfiles($source.'/'.$file);
							    
					       }
					       else 
					       {
					           //echo "size of (".$source.'/'.$file.")".$totalfile."<br>";
							   $totalfile=$totalfile+1;
					       }
					       
					   }
					   closedir($folder);
					   return $totalfile;
}


       function permission($per){
			  $retarray=array();
			  if($per=="1"){
			       $retarray[0]=1;
			       $retarray[1]=0;
				   $retarray[2]=0;
			  }else if($per=="2"){
			       $retarray[0]=0;
			       $retarray[1]=1;
				   $retarray[2]=0;
			  }else if($per=="3"){
			       $retarray[0]=1;
			       $retarray[1]=1;
				   $retarray[2]=0;
			  }else if($per=="4"){
			       $retarray[0]=0;
			       $retarray[1]=0;
				   $retarray[2]=1;
			  }else if($per=="5"){
			       $retarray[0]=1;
			       $retarray[1]=0;
				   $retarray[2]=1;
			  }else if($per=="6"){
			       $retarray[0]=0;
			       $retarray[1]=1;
				   $retarray[2]=1;
			  }else if($per=="7"){
			       $retarray[0]=1;
			       $retarray[1]=1;
				   $retarray[2]=1;
			  }else{
			       $retarray[0]=0;
			       $retarray[1]=0;
				   $retarray[2]=0;
			  }
			   return  $retarray; 
}
function CopyFiles($source,$dest)
{    
									
					   $folder = opendir($source);
					   while($file = readdir($folder))
					   {
					       if ($file == '.' || $file == '..') {
					           continue;
					       }
					   
					       if(is_dir($source.'/'.$file))
					       {
					           mkdir($dest.'/'.$file,0777);
							   chmod($dest.'/'.$file,0777);
					           CopyFiles($source.'/'.$file,$dest.'/'.$file);
					       }
					       else 
					       {
					           copy($source.'/'.$file,$dest.'/'.$file);
							   chmod($dest.'/'.$file,0777);
					       }
					       
					   }
					   closedir($folder);
					   return 1;
}
function getDirList($base)
{
                    $subbase = $base . '/';
 			
		$per=substr(sprintf('%o', fileperms($subbase)), -3);
		
		$uper=substr($per,0,1);
		$gper=substr($per,1,1);
		$oper=substr($per,2,1);
		$wr_per = TEXT_WRITE_PERMISSION_AVAILABLE;
		$permis=permission($oper);
		if($permis[1]=="0")
    			$wr_per="<font color=red>".TEXT_ENABLE_WRITE_PERMISSION."</font>";	
       				return $wr_per;
}	
  
              $admin_flag=0; 
  $wr=getDirList("./languages");
  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
    $current=$wr; 
    $recom="&nbsp;";
  }else{
    $admin_flag=1;
    $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
    $recom=$wr;
  }
  $staff_flag=0; 
  $wr=getDirList("../staff/languages/");
  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
    $current=$wr; 
    $recom="&nbsp;";
  }else{
    $staff_flag=1;
    $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
    $recom=$wr;
  }

  /*
   * parser Language
   */
  $parser_flag=0;
  $wr=getDirList("../parser/languages/");
  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
    $current=$wr;
    $recom="&nbsp;";
  }else{
    $parser_flag=1;
    $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
    $recom=$wr;
  }
  //-------------------
   $user_flag=0; 
  $wr=getDirList("../languages/");
  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
    $current=$wr; 
    $recom="&nbsp;";
  }else{
    $user_flag=1;
    $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
    $recom=$wr;
  }

        $addOredit = 'Add Language';
		if ($_GET["id"] != "") {
                $var_id = $_GET["id"];
				$addOredit = 'Edit Language';
        }
        elseif ($_POST["id"] != "") {
                $var_id = $_POST["id"];
				$addOredit = 'Edit Language';
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
        $var_staffid = $_SESSION["sess_staffid"];


        if ($_POST["postback"] == "" && $var_id != "") {

                $sql = "Select vLangCode,vLangDesc from sptbl_lang where vlangCode = '" . mysql_real_escape_string($var_id) . "'";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {
                        $var_row = mysql_fetch_array($var_result);

                        $var_langCode = $var_row["vLangCode"];
                        $var_langDesc = $var_row["vLangDesc"];
                }
                else {
                        $var_message = MESSAGE_RECORD_ERROR;
                        $flag_msg    = 'class="msg_error"';
                }
                mysql_free_result($var_result);
        }
        elseif ($_POST["postback"] == "A") {
		       if($user_flag==1 or $staff_flag==1 or $admin_flag==1 or $parser_flag==1){
			       $var_message = TEXT_ENABLE_WRITE_PERMISSION ;
                               $flag_msg    = 'class="msg_error"';
			   }else{
            

              	$var_langCode = trim($_POST["txtLangCode"]);
             	$var_langDesc = trim($_POST["txtLangDesc"]);
				if(isValidlangcode($var_langCode) and isValidlangcode($var_langDesc)){
						     
						
		                if (validateAddition($var_langCode,$var_langDesc) == true) {
		                        /* copy language file to folder*/
								$totalfile=0;
								$numberoffileinen_admin=getnumfiles("./languages/en/");
								$totalfile=0;
                                                                $numberoffileinen_staff=getnumfiles("../staff/languages/en/");
								$totalfile=0;
								$numberoffileinen_user=getnumfiles("../languages/en/");
								$totalfile=0;

                                                                $totalfile=0;
                                                                $numberoffileinen_parser=getnumfiles("../parser/languages/en/");

								$dfen =number_format(getsize("./languages/en/"),0,',','');

                                                                $stafffilemissing=0;
								$adminfilemissing=0;
								$userfilemissing=0;

                                                                $parserfilemissing=0;
								
								 
                                if(is_dir("./languages/".$var_langCode."/") ){
								   $languageusageforadmin =number_format(getsize("./languages/".$var_langCode."/"),0,',','');
								   $totalfile=0;
								   $numberoffileinen_admin_lang=getnumfiles("./languages/".$var_langCode."/");
								   if($numberoffileinen_admin_lang <$numberoffileinen_admin){
								     $adminfilemissing=1;
								   }
								}else{
								    mkdir("./languages/".$var_langCode,0777);
									chmod("./languages/".$var_langCode,0777);
									$source="./languages/en";
									$dest="./languages/".$var_langCode;
									CopyFiles($source,$dest);
									
								
								}
								
								if(is_dir("../staff/languages/".$var_langCode."/") ){
								   $totalfile=0;
								   $numberoffileinen_staff_lang=getnumfiles("../staff/languages/".$var_langCode."/");
								   
								   if($numberoffileinen_staff_lang <$numberoffileinen_staff){
								     $stafffilemissing=1;
								   }
								}else{
								    mkdir("../staff/languages/".$var_langCode,0777);
									chmod("../staff/languages/".$var_langCode,0777);
									$source="../staff/languages/en";
									$dest="../staff/languages/".$var_langCode;
									CopyFiles($source,$dest);
									
									
								}

                                                   /*
                                                                 * Parser file
                                                                 */
                                                                if(is_dir("../parser/languages/".$var_langCode."/") ){
								   $totalfile=0;
								   $numberoffileinen_parser_lang=getnumfiles("../parser/languages/".$var_langCode."/");

								   if($numberoffileinen_parser_lang <$numberoffileinen_parser){
								     $parserfilemissing=1;
								   }
								}else{
								    mkdir("../parser/languages/".$var_langCode,0777);
									chmod("../parser/languages/".$var_langCode,0777);
									$source="../parser/languages/en";
									$dest="../parser/languages/".$var_langCode;
									CopyFiles($source,$dest);


								}
                                                                // parser file

								if(is_dir("../languages/".$var_langCode."/") ){
								    $totalfile=0;
								   $numberoffileinen_user_lang=getnumfiles("../languages/".$var_langCode."/");
								   if($numberoffileinen_user_lang <$numberoffileinen_user){
								     $userfilemissing=1;
								   }
								}else{
								    mkdir("../languages/".$var_langCode,0777);
									chmod("../languages/".$var_langCode,0777);
									$source="../languages/en";
									$dest="../languages/".$var_langCode;
									CopyFiles($source,$dest);
									
									
								}		
								 $errorinfiles=0; 
								 
								 if($stafffilemissing==1){
								   $var_message1 =  htmlpath("../staff/languages") ;
								   $errorinfiles=1; 
								 }
								 if($adminfilemissing==1){
								   $var_message2 =  htmlpath("./languages") ;
								   $errorinfiles=1; 
								 }
								 if($userfilemissing==1){
								   $var_message3 =  htmlpath("../languages") ;
								   $errorinfiles=1; 
								 }	
									
								if($parserfilemissing==1){
								   $var_message1 =  htmlpath("../parser/languages") ;
								   $errorinfiles=1;
								 }
									
								
								if($errorinfiles==0){
				                        $sql = "Insert into sptbl_lang(vLangCode,vlangdesc) Values('" . mysql_real_escape_string($var_langCode) . "','" . mysql_real_escape_string($var_langDesc)."')";
				                        executeQuery($sql,$conn);
				                        $var_insert_id = mysql_insert_id($conn);
				
				                        //Insert the actionlog
										if(logActivity()) {
				                        $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Language','$var_insert_id',now())";
				                        executeQuery($sql,$conn);
										}
				                        $var_message =MESSAGE_RECORD_ADDED."<br>".TEXT_PLEASE_DO."<br>";
				                        $var_message .="1.".TEXT_CHANGE_PERMISSION."<br>&nbsp;&nbsp;*".htmlpath("./languages")."<br>&nbsp;&nbsp;*".htmlpath("../staff/languages")."<br>&nbsp;&nbsp;*".htmlpath("../languages") ;
							$var_message .= "<br>2.".TEXT_REPLACE_TXT."&nbsp;'".$var_langDesc."'&nbsp;".TEXT_EQUIVALENT."<br>&nbsp;&nbsp;*".htmlpath("./languages/".$var_langCode."/")."<br>&nbsp;&nbsp;*".htmlpath("../staff/languages/".$var_langCode."/")."<br>&nbsp;&nbsp;*".htmlpath("../languages/".$var_langCode."/") ;
                                                        $flag_msg    = 'class="msg_success"';
				                                $var_langCode = "";
				                                $var_langDesc = "";
				                                $var_id = "";
								}else{
								   
								  $var_message="<font color=red>".TEXT_LANGUAGE_FILE_MISSING."<br>".$var_message1."<br>".$var_message2."<br>".$var_message3."</font>";
                                                                  $flag_msg    = 'class="msg_error"';
								}				
		                }
		                else {
		                        $var_message = MESSAGE_RECORD_DUPLICATE ;
                                        $flag_msg    = 'class="msg_error"';
		                }
						
				}else{
				  $var_message = TEXT_INVALID_CODE;
                                  $flag_msg    = 'class="msg_error"';
				}		
						
			 }			
        }
        elseif ($_POST["postback"] == "D") {

                if (validateDeletion($var_id) == true and $var_id!="en") {
                        $sql = "Delete from  sptbl_lang  where vLangCode='" . mysql_real_escape_string($var_id) . "'";
                        executeQuery($sql,$conn);

                        //Insert the actionlog
						if(logActivity()) {
                        $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Language','" . mysql_real_escape_string($var_id) . "',now())";
                        executeQuery($sql,$conn);
						}

                                $var_langCode = "";
                                $var_langDesc = "";

                                $var_id = "";

                        $var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
                }
                else {
                        $var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
                }
        }
        elseif ($_POST["postback"] == "U") {

                        $var_langCode = trim($_POST["txtLangCode"]);
                        $var_langDesc = trim($_POST["txtLangDesc"]);

                        if (validateUpdation($var_langCode, $var_langDesc) == true  and $var_langCode !="en") {
                           $sql = "Update sptbl_lang set    vLangDesc='" . mysql_real_escape_string($var_langDesc) . "'  where vLangCode='" . mysql_real_escape_string($var_id) . "'";
                                executeQuery($sql,$conn);

                        //Insert the actionlog
						if(logActivity()) {
                        $sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Language','" . mysql_real_escape_string($var_id) . "',now())";
                        executeQuery($sql,$conn);
						}

                                $var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
                        }
                        else {
                                $var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
                        }
        }

        function validateAddition($var_langCode,$var_langDesc)
        {


                global $conn;
                $returnFlag ="";
                If (trim($_POST["txtLangCode"]) == "" || trim($_POST["txtLangDesc"]) == "" )               {
                        $returnFlag=false;
                }else {

                  $sql = "Select vLangCode,vLangDesc from sptbl_lang where
                  vLangCode='" . mysql_real_escape_string($var_langCode) ."' or
                  vLangDesc='". mysql_real_escape_string($var_langDesc) ."'";

                  if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {

                          $returnFlag=false;

                  }else{

                          $returnFlag=true;

                  }
                }
                          return $returnFlag;

        }

        function validateDeletion()
        {

                        return true;

        }

        function validateUpdation()
        {

                global $conn;
                $returnFlag ="";
                If (trim($_POST["txtLangCode"]) == "" || trim($_POST["txtLangDesc"]) == "" )               {
                        $returnFlag=false;
                }else {

                  $sql = "Select vLangCode,vLangDesc from sptbl_lang where
                  vLangCode='" . mysql_real_escape_string($var_langCode) ."' or
                  vLangDesc='". mysql_real_escape_string($var_langDesc) ."'";

                  if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {

                          $returnFlag=false;

                  }else{

                          $returnFlag=true;

                  }
                }
                          return $returnFlag;
        }
?>
<form name="frmLang" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
	<div class="content_section">
			<div class="content_section_title">
				<h3> <?php echo $addOredit; ?></h3>
			</div>
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                 <tr>
         <td align="center" colspan=3 >&nbsp;</td>

         </tr>

                <tr>
				<td>&nbsp;</td>
         <td align="left" colspan=2 class="toplinks">
         <?php echo TEXT_FIELDS_MANDATORY ?></td>

         </tr>		

         <tr>
         <td align="center" colspan=3 >
             <?php
             if ($var_message != ""){?>
		 	<div <?php echo $flag_msg; ?>><p><?php echo $var_message ?></p></div>
             <?php
             }?>
          </td>

         </tr>
         <tr><td colspan="3">&nbsp;</td></tr>
         <tr>
         <td width="2%" align="left">&nbsp;</td>
         <td width="43%" align="left" class="toplinks"><?php echo TEXT_LANGUAGE_CODE?> <font style="color:#FF0000; font-size:9px">*</font> </td>
         <td width="55%" align="left">
         <input name="txtLangCode" type="text" class="comm_input input_width1a" id="txtLangCode" size="30" maxlength="100" value="<?php echo htmlentities($var_langCode); ?>">
         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                      <td align="left">&nbsp;</td>
                      <td align="left" class="toplinks"><?php echo TEXT_LANGUAGE_DESC ?> <font style="color:#FF0000; font-size:9px">*</font></td>
                      <td width="55%" align="left">
                        <input name="txtLangDesc" type="text" class="comm_input input_width1a" id="txtLangDesc" size="30" maxlength="100" value="<?php echo htmlentities($var_langDesc); ?>">
</td>
                      </tr>
                      <tr><td colspan="3"  align=center>&nbsp;</td></tr>
                      
					  <?php
						  $addbuttonenabled="";
					  if($user_flag==1 or $staff_flag==1 or $admin_flag==1){
					  		  $addbuttonenabled="disabled";
					  ?>
							  <tr class="listingmaintext">
							    <td colspan="3" class=subsidelink align=center > <?php echo TEXT_ENABLE_WRITE_PERMISSION_MESSAGE ;?></td>
							  </tr>
					 <?php 
					   }
					   if($admin_flag==1){
					  ?>
					    		<tr class="listingmaintext"><td colspan="3"  class=subsidelink  align=center ><?php echo  htmlpath("./languages");?>&nbsp;</td></tr>
					  <?php } 
					   if($staff_flag==1){
					  ?>
					    		<tr class="listingmaintext"><td colspan="3"  class=subsidelink align=center ><?php echo  htmlpath("../staff/languages/");?>&nbsp;</td></tr>
					  <?php }
					   if($user_flag==1){
					  ?>
					     		<tr class="listingmaintext"><td colspan="3"  class=subsidelink align=center ><?php echo  htmlpath("../languages/");?>&nbsp;</td></tr>
					  <?php } ?>
                       
					   
					  
                     </table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td class="btm_brdr">&nbsp;</td>
				</tr>
              <tr>
                <td>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="14%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD; ?>" onClick="javascript:add();" <?php echo   $addbuttonenabled?>></td>
                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT; ?>"  onClick="javascript:edit();"></td>
                                    <td width="14%"><input name="btDelete" type="button" class="comm_btn_black" value="<?php echo BUTTON_TEXT_DELETE; ?>" onClick="javascript:deleted();"></td>
                                    <td width="14%"><input name="btCancel" type="reset" class="comm_btn_black" value="<?php echo BUTTON_TEXT_CLEAR; ?>" ></td><!-- onClick="javascript:cancel();" -->
                                    <td width="20%">
                                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
                                                                        <input type="hidden" name="id" value="<?php echo($var_id); ?>">
                                                                        <input type="hidden" name="postback" value="">
                                                                        </td>
                                  </tr>
                              </table>
                    </td>
              </tr>
            </table>
            <p class="ashbody">&nbsp;</p></td>

  </tr>
</table>
</div>
<script>

        <?php
  			    if ($var_id == "") {
                        echo("document.frmLang.btAdd.disabled=false;\n");
                        echo("document.frmLang.btUpdate.disabled=true;\n");
                        echo("document.frmLang.btDelete.disabled=true;\n");
                }
                else {
                        echo("document.frmLang.btAdd.disabled=true;\n");
                        echo("document.frmLang.btUpdate.disabled=false;\n");
                        echo("document.frmLang.btDelete.disabled=false;\n");
                }
        ?>
		document.frmLang.txtLangCode.focus();
</script>
</form>