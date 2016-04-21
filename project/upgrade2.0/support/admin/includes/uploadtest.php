<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
    //system("mysqldump -u root --password='status' --databases test >>db.txt");
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
	
	$var_staffid = "1";
	
    if ($_POST["postback"] == "A") {
	   
	}elseif ($_POST["postback"] == "C") {
	   
  	}
	function validateSave() {
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
function getDirList($base)
{
   
  
   if(is_dir($base)){
               $subbase = $base . '/';
			   $per=substr(sprintf('%o', fileperms($subbase)), -3);
			  
			   $uper=substr($per,0,1);
			   $gper=substr($per,1,1);
			   $wper=substr($per,2,1);
			   $wr_per = TEXT_WRITE_PERMISSION_AVAILABLE;
			   $permis=permission($wper);
			   if($permis[1]=="0")
				       $wr_per="<font color=red>".TEXT_ENABLE_WRITE_PERMISSION."</font>";	
               return $wr_per;
               
          
      
   }
}
?>
<div class="content_section">
<form name="frmuploadtest" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">

   <Div class="content_section_title"><h3><?php echo TEXT_UPLOAD_TEST ?></h3></Div>
    

     
     
     
     
     



	       
		 <div style="overflow:auto">
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="list_tbl">
                              <tr >
                                <th  width="22%" class="listing"><?php echo "<b>".TEXT_DIRECTORY_NAME."</b>"; ?></th>
								 <th width="26%" class="listing"><?php echo "<b>".TEXT_PATH_NAME."</b>"; ?></th>
								 <th  width="27%" class="listing" ><?php echo "<b>".TEXT_PERMISSION."</b>"; ?></th>
								 <th width="25%" class="listing"><?php echo "<b>".TEXT_RECOMENDED."</b>"; ?></th>
							   </tr>
							   <tr class="listingmaintext">
							    <?php
								  $wr=getDirList("../styles");
								  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
								    $current=$wr; 
								   $recom="&nbsp;";
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
								  }
								?>
							     <td width="22%" ><?php echo   TEXT_STYLES?></td>
								 <td width="26%" style="page-break-after:always"><?php echo  htmlpath("../styles");?></td>
								 <td width="27%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>								 
							   </tr>	
							   <tr class="listingmaintext">
							    <?php
								  $wr=getDirList("../attachments");
								  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
								    $current=$wr; 
								   $recom="&nbsp;";
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
								  }
								?>
							     <td width="22%" ><?php echo   TEXT_ATTACHMENTS?></td>
								 <td width="26%" ><?php echo  htmlpath("../attachments");?></td>
								 <td width="27%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>								 
							   </tr>	
							   
							   <!--
							   <tr align="left">
                               <td colspan="4"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
							   <tr class="listingmaintext">
							    <?php
								  $wr=getDirList("../config");
								  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE ){
								    $current=$wr; 
								   $recom="&nbsp;";
								   //$recom=$wr;
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
									//$current=$wr; 
								   //$recom="&nbsp;";
								  }
								?>
							     <td width="25%" ><?php echo   TEXT_CONFIG?></td>
								 <td width="25%" ><?php echo  htmlpath("../config");?></td>
								 <td width="25%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>
								 
							   </tr>	-->
							   <tr class="whitebasic">
							    <?php
								  $wr=getDirList("../backup");
								  if($wr== TEXT_WRITE_PERMISSION_AVAILABLE){
								    $current=$wr; 
								   $recom="&nbsp;";
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
								  }
								?>
							     <td width="22%" ><?php echo   TEXT_BACKUP?></td>
								 <td width="26%" ><?php echo  htmlpath("../backup");?></td>
								 <td width="27%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>								 
							   </tr>	
							   <tr class="whitebasic">
							    <?php
								  $wr=getDirList("../custom");
								  if($wr==TEXT_WRITE_PERMISSION_AVAILABLE){
								    $current=$wr; 
								   $recom="&nbsp;";
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
								  }
								?>
							     <td width="22%" ><?php echo   TEXT_CUSTOM?></td>
								 <td width="26%" ><?php echo  htmlpath("../custom");?></td>
								 <td width="27%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>
							   </tr>					   
							   <tr class="whitebasic">
							    <?php
								  $wr=getDirList("../downloads");
								  if($wr==TEXT_WRITE_PERMISSION_AVAILABLE){
								    $current=$wr; 
								   $recom="&nbsp;";
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
								  }
								?>
							     <td width="22%" ><?php echo   TEXT_DOWNLOADS?></td>
								 <td width="26%" ><?php echo  htmlpath("../downloads");?></td>
								 <td width="27%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>							 
							   </tr>	
							   <tr class="listingmaintext">
							    <?php
								  $wr=getDirList("./purgedtickets");
								  if($wr==TEXT_WRITE_PERMISSION_AVAILABLE){
								    $current=$wr; 
								   $recom="&nbsp;";
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
								  }
								?>
							     <td width="22%" ><?php echo   TEXT_PURGED_TICKETS?></td>
								 <td width="26%" ><?php echo  htmlpath("./purgedtickets");?></td>
								 <td width="27%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>							 
							   </tr>	
   							   <tr class="listingmaintext">
							    <?php
								  $wr=getDirList("./purgedtickets/attachments");
								  if($wr==TEXT_WRITE_PERMISSION_AVAILABLE){
								    $current=$wr; 
								   $recom="&nbsp;";
								  }else{
								  $current=TEXT_WRITE_PERMISSION_UNAVAILABLE;
								    $recom=$wr;
								  }
								?>
							     <td width="22%" ><?php echo   TEXT_PURGED_ATTACHMENTS?></td>
								 <td width="26%" ><?php echo  htmlpath("./purgedtickets/attachments");?></td>
								 <td width="27%" ><?php   echo $current;?></td>
								 <td width="25%" ><?php   echo $recom;?></td>								 
							   </tr>	
							</table>
							</div>
	                       
                  
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td>
                    <!--  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr align="center"  class="listingbtnbar">
                                    <td width="22%">&nbsp;</td>
                                    <td width="16%" colspan=4 align=center><input name="btSave" type="button" class="button" value="<?php echo BUTTON_TEXT_SAVE ?>" onClick="javascript:saveMe(this.form);"></td>
                                    
                                    <td width="20%">
									<input type=hidden name="tosave">
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
                        </table></td>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>-->
                    </td>
              </tr>
            </table>
		

</form>
</div>