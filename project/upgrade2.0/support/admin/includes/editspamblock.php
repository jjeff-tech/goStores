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
// |                                                                      |                // |                                                                      |
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
        $var_messag = "";
        if($_POST['action']== 'filterchoice'){
             $filtertype=$_POST['rdFilterChoice'];
             $filtertype=addslashes($filtertype);
             $sqlFilter = " update sptbl_lookup set vLookUpValue='$filtertype' where  vLookUpName ='spamfiltertype'";

			 $resultFilter = executeSelect($sqlFilter,$conn);
                         $var_messag= MESSAGE_RECORD_UPDATED;
                         $flag_msg  = 'class="msg_success"';
		}
		     $sqlFilter = "Select * from sptbl_lookup where vLookUpName ='spamfiltertype'";
	         $resultFilter = executeSelect($sqlFilter,$conn);
	         $rowFilterType = mysql_fetch_array($resultFilter);
	         $filtertype=$rowFilterType['vLookUpValue'];
?>
<script language="javascript">
    
    </script>
<form name="frmConfig" id="frmConfig" method="POST" action="<?php echo SITE_URL?>admin/editspamblock.php?mt=y&stylename=STYLEGENERAL&styleminus=minus&styleplus=plus&">

<div class="content_section">
			<div class="content_section_title">
				<h3> <?php echo TEXT_EDIT_SPAM_CONFIG; ?></h3>
			</div>
			
			<div class="content_section_data">
			
<table width="100%"  border="0" class="whitebasic" cellspacing="1" cellpadding="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td>
     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     


     <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
     <tr>
     <td>
         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">


         <tr>
         <td width="100%" align="center" colspan=3 >
             <?php
             if ($var_messag != ""){?>
                 <div <?php echo $flag_msg; ?>>
		 	 <?php echo $var_messag ?>
		 </div>
             <?php
             }?>
        </td>

         </tr>

                           <tr>
         <td width="100%" align="center" colspan=3 class="listing">
         </td>

         </tr>
         <tr>
		   <td align="center">
         <form name='frmfilterchoice'  action='' method='POST'>
		 
		     <input type='hidden' name='action' value='filterchoice'>
			 
         
		   <div class="msg_common">
		   
		     <table border=0 class="whitebasic" width="100%">
		      <tr>
			    <td colspan=3>
			     <?php echo TEXT_EDIT_SPAM_TRAIN_SPAMFILTER_TYPE_TEXT;?>
				</td>
			  </tr>
			 	<tr>
					<td colspan="3"></td>
				</tr>
			  <tr>
			   
			   <td width="438" colspan="3" >
			    <input name="rdFilterChoice" type="radio" value="OFF" checked><?php echo TEXT_EDIT_SPAM_TRAIN_SPAMFILTER_TYPE_TEXT1;?>
			   </td>
			  </tr>
                <tr>
			   
			    <td colspan="3" ><input name="rdFilterChoice" type="radio" value="SUBJECT"  <?php if($filtertype=="SUBJECT") echo "checked"; ?>><?php echo TEXT_EDIT_SPAM_TRAIN_SPAMFILTER_TYPE_TEXT2;?></td>
			  </tr>
			  <tr>
			   
			    <td colspan="3" ><input name="rdFilterChoice" type="radio" value="BODY"  <?php if($filtertype=="BODY") echo "checked"; ?>><?php echo TEXT_EDIT_SPAM_TRAIN_SPAMFILTER_TYPE_TEXT3;?> </td>
			  </tr>
			  <tr>
			 
			    <td colspan="3" ><input name="rdFilterChoice" type="radio" value="BOTH"  <?php if($filtertype=="BOTH") echo "checked"; ?>><?php echo TEXT_EDIT_SPAM_TRAIN_SPAMFILTER_TYPE_TEXT4;?></td>
			  </tr>
			  <tr>
			  	<td colspan="3" class=" btm_brdr"></td>
			  </tr>
			  <tr>
			  <td align="center" colspan="3">
			   <input type='submit' name='Submit' value='<?php echo TEXT_UPDATE ;?>' class="comm_btn" >
			  </td>
			  </tr>
			 </table>
		  
		   </div>		   
		 </form>
		</td>
	 </tr>
         <tr class="whitebasic">
		  <td align="center">
		  	<table border="0" class="whitebasic" cellpadding="3" cellspacing="3" width="20%">
			 <tr>
				<td align="center">
				  <!-- nave baysin-->
					<?php
						require("../spamfilter/spamfilterclass.php");
						$cats = $nbs->getCategories();
					?>
				</td>
			 </tr>
			</table>
			
			<div class="content_section_subtitle">
			<h3 align="left">&nbsp;&nbsp;&nbsp;<?php echo TEXT_EDIT_SPAM_TRAIN_SPAMFILTER;?></h3>
			</div>
		
		<form name="frmspam" action='' method='POST'>
		<div class="content_section_data">
		<table border=0 align="center" width="100%" cellspacing="1" cellpadding="1" class="comm_tbl">
			<tr>
				<td class="fieldnames" align="left">
					<input type='hidden' name='action' value='train'/>
					<?php echo TEXT_EDIT_SPAM_DOCUMENTIDENTIFIER;?> : <input type='text' name='docid' id='docid' value=''  class="comm_input input_width1"  /> (<?php echo TEXT_EDIT_SPAM_DOCUMENTUNIQUE;?>)<br><br>
					<?php echo TEXT_EDIT_SPAM_DOCUMENTCATEGORY;?>: <select name='cat' class="comm_input input_width1a">
					<?php
					reset($cats);
					while(list($key,$val) = each($cats)) {
						echo "<option value='$key'>$key</option>\n";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="fieldnames" align="left">		
					<?php echo TEXT_EDIT_SPAM_DOCUMENT_PASTETEXT;?>:
				</td>
			</tr>
			<tr>
				<td align="left" class="listing">		
					<textarea name="document" id="document1" cols='137' rows='20' class=textarea></textarea>
				</td>
			</tr>
			<tr>
				<td align="right"><input type='submit' name='Submit' value='<?php echo TEXT_EDIT_SPAM_DOCUMENT_TRAIN;?>' class="comm_btn" /></td>
			</tr>
		</table>
		</div>
		</form>

		<div class="content_section_subtitle">
		<h3 align="left">&nbsp;&nbsp;&nbsp;<?php echo TEXT_EDIT_SPAM_DOCUMENT_DETERMINE_TEXT;?>:</h3>
		</div>
		<div class="content_section_data">
		<form name="frmspamdoc"  method='POST'>
		<table width="100%" class="comm_tbl">
			<tr>
				<td valign="top" align="left">
				<input type='hidden' name='action' value='cat'/>
				<?php echo TEXT_EDIT_SPAM_DOCUMENT_PASTETEXT;?> :<br /><br />
				<textarea name="document" id="document2" cols='137' rows='20' class=textarea></textarea><br />
				
				<tr>
					<td align="right"><input type='submit' name='Submit' value='<?php echo TEXT_EDIT_SPAM_DOCUMENT_DETERMINE;?>' class="comm_btn" /></td>
				</tr>

		</table>
		</form>
		</div>
		<div class="content_section_subtitle">
		<h3 align="left">&nbsp;&nbsp;&nbsp;<?php echo TEXT_EDIT_SPAM_DOCUMENT_REMOVE_TEXT1;?></h3>
		</div>
		<div class="content_section_data">
			<form name="frmspamdocremove"  method='POST'>
			
			<table align="left" class="comm_tbl ">
			
				<tr>
				<td align="left"> 
				<input type='hidden' name='action' value='untrain' />
			<?php echo TEXT_EDIT_SPAM_DOCUMENT_REMOVE_TEXT2;?> :
			<select name='docid' class="comm_input input_width1a">
			<?php
			$con = new Connection($login, $pass, $server, $db);
			$rs = $con->select("SELECT * FROM sptbl_spam_references");
			while (!$rs->EOF()) {
				echo "<option value='".$rs->f('id')."'>".$rs->f('id')." - ".$rs->f('category_id')."</option>\n";
				$rs->moveNext();
			}
			?>
			</select>
			</td><td align="left">
			<input type='submit' name='Submit' value='<?php echo TEXT_EDIT_SPAM_DOCUMENT_REMOVE;?>'  class="comm_btn" />
				
				</td>
				</tr>
			</table>
			
			</form>
		</div>
		  <!-- nave baysin-->
		  </td>

		 </tr>
                           <tr><td colspan="3" class="btm_brdr">&nbsp;</td></tr>
                              </table>
                        </td>
                            </tr>
                        </table></td>
                      <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
 				</td>
              </tr>
            </table>
            <table width="100%"  border="0" cellspacing="10" cellpadding="0" class="whitebasic">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td>

                              <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                  <tr align="center"  class="listingbtnbar">
                                    <td width="20%">&nbsp;</td>
                                    <td width="10%"></td>
                                    <td width="20%" align=right><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
                                    <td width="20%"><input name="btCancel" type="reset" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();"></td>
                                    <td width="10%"></td>
                                    <td width="20%">
                                                                        <input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
                                                                        <input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
                                                                        <input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">

                                                                        <input type="hidden" name="postback" value="">
                                                                        </td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                        <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                   </td>
              </tr>
            </table>
		</td>
  </tr>
</table>
</div>
<div class="clear"></div>
</div>
</form>