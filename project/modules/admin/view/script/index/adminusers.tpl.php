<div class="form_container">
<div class="form_top"><?php echo $this->pageTitle;?></div>
<div class="form_bgr">
 <!-- Add/Edit form -->
<?php
 if($this->editEnabled|| $this->addEnabled)// add plan details
 {
   
?>
 <?php PageContext::renderPostAction('errormessage');?>
<form id="frmAdminUsers" name="frmAdminUsers" action="<?php echo ConfigUrl::base(); ?>index/adminusers/<?php echo $this->action=='edit'?$this->action.'/'.$this->id:$this->action;?>" method="post" >
    <input type="hidden" name="id" value="<?php echo $this->id?>">
    <input type="hidden" name="action" value="<?php echo $this->action ?>"> 
    <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                <tr>
		  <th align="left" valign="top" width="20%">User Name <span class="mandred">*</span></th>
		  <td align="left" valign="top">
                      <?php if(!$this->displayUsername){?><input id="ad_uname" name="ad_uname" type="text" class="inp-form" validate="required:true"  value="<?php echo stripslashes($this->pageContent->vUsername)?>" />
                              <?php }else{?> <label><b><?php echo stripslashes($this->pageContent->vUsername)?> </b></label>
                                      <input id="ad_uname" name="ad_uname" type="hidden" class="inp-form"  value="<?php echo stripslashes($this->pageContent->vUsername)?>" /><?php }?>
                  </td>
		</tr>
		<tr>
		  <th align="left" valign="top">Password<?php if($this->addEnabled){?><span class="mandred">*</span><?php } ?></th>
		  <td align="left" valign="top"><input id="ad_pwd" name="ad_pwd" type="password" class="valid"  <?php if($this->addEnabled){?>validate="required:true" <?php }?>  minlength="4" value="<?php echo $_POST['ad_pwd'];?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">Confirm Password <?php if($this->addEnabled){?><span class="mandred">*</span><?php } ?></th>
		  <td align="left" valign="top"><input id="ad_cpwd" name="ad_cpwd" type="password" class="valid" <?php if($this->addEnabled){?>validate="required:true" <?php }?>minlength="4" value="<?php echo $_POST['ad_cpwd'];?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">First Name <span class="mandred">*</span></th>
		  <td align="left" valign="top"><input id="ad_fname" name="ad_fname" type="text" class="inp-form" validate="required:true"  value="<?php echo stripslashes($this->pageContent->vFirstName)?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">Last Name</th>
		  <td align="left" valign="top"><input id="ad_lname" name="ad_lname" type="text" class="inp-form"   value="<?php echo stripslashes($this->pageContent->vLastName)?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">Email<span class="mandred">*</span></th>
		  <td align="left" valign="top"><input id="ad_email" name="ad_email" type="text"  validate="required:true" class="inp-form{required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}" value="<?php echo $_POST['ad_email']?$_POST['ad_email']:stripslashes($this->pageContent->vEmail);?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">Roles<span class="mandred">*</span></th>
                    <td align="left" valign="top">
                        <?php if($this->displayRole){?>
                        <input type="hidden" id="ad_role" name="ad_role" value="1"><label><b><?php echo $this->role; ?></b></label>
                        <?php }else{?>
                        <select name="ad_role" validate="required:true" >
                        <option value="">Choose A Role</option>
                            <?php
                            if(sizeof($this->roles) > 0 )
                            { 
                               foreach($this->roles as $role)
                                    if(($this->addEnabled && $role->nStatus ==1)||$this->editEnabled)
                                        echo ' <option value="'.$role->nRid.'" '.(($role->nRid==$this->pageContent->nRid)?' selected="selected"':'').'>'.$role->vRoleName.'</option>';
                            }
                            ?>	
                        </select>
                        <?php }?>
                    </td>
		</tr>
		<tr>
		  <th align="left" valign="top">Status<span class="mandred">*</span></th>
		  <td align="left" valign="top">
                      <?php if($this->displayRole){?>
                      <input type="hidden" id="ad_status" name="ad_status" value="1"><label><b>Active</b></label>
                      <?php }else{?>
                    <select name="ad_status"   validate="required:true"  id="ad_status" >
                      <option  value="1" <?php echo (($this->pageContent->nStatus==1)?'selected="selected"':''); ?> >Active    </option>
                      <option  value="2" <?php echo (($this->pageContent->nStatus==2)?'selected="selected"':''); ?> >In Active </option>
                    </select>
                      <?php }?>
                  </td>
		</tr>		
                <tr>
                    <th align="left" valign="top">&nbsp;</th>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>		
                <tr>
                    <th align="right" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/index/adminusers">Cancel</a></div></th>
                    <td valign="top">
                      
                       <input type="submit" name="btnAdd"  value="<?php echo $this->buttonValue; ?>"  />
                    </td>
                </tr>
     </table>    
 
    </form> 
<?php } else {   ?>  
 
		 <div class="r_float">
		 <!-- Search Form -->
		<div class="l_float"><form class="cmxform" id="adminusers" action="<?php echo ConfigUrl::base(); ?>index/adminusers/" method="post" onsubmit="return validateAdminUserSearch()">
			
                        <input type="hidden" name="action" value="search">
                        <input type="hidden" name="page" id="page" value="<?php echo $this->pageCount;?>">
			<div class="admin_search_container" ><input name="search" id="search" type="text" class="search_box" placeholder="Search by Username,Name or Email" value="<?php echo $this->searchParam ; ?>">
			<input name="btnSearch" type="submit" class="button_orange" value="Search">&nbsp;&nbsp;</div>
		</form>
		<!-- End Search Form -->
		</div>
		<div class="l_float">
		<div class="addnew" ><?php if($this->action!="add" && $this->action!="edit") { ?><a href="<?php echo ConfigUrl::base(); ?>index/adminusers/add">Add Admin</a><?php } ?></div>
		</div>
		<div class="clear"></div>
		</div>

<br><br><br>
<div align="left">
<?php PageContext::renderPostAction($this->messageFunction);
$this->messageFunction ='';?>
</div>
<!-- Listing Form -->
<table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
        <tr class="heading1">
          <td width="8%" align="center">Sl No.</td>
          <td width="15%" align="left">User Name<a href="javascript:void(0);" id="<?php echo BASE_URL;?>admin/index/adminusers/<?php echo $this->usernameSortAction;?>/x"  class="<?php echo $this->usernameSortStyle;?>"></a></td>
          <td width="17%" align="left">Name</td>
          <td width="14%" align="left">Role</td>
          <td width="8%" align="left">Email</td>
          <td width="24%" align="left">Last Login<a href="javascript:void(0);" id="<?php echo BASE_URL;?>admin/index/adminusers/<?php echo $this->lastLoginSortAction;?>/x" class="<?php echo $this->lastLoginSortStyle;?>"></a></td>
          <td width="14%" align="left">Actions</td>
        </tr>
        <?php
           if(!empty($this->adusers)) {
               $i=0;
                $i=$this->pageInfo['base'];
               foreach($this->adusers as $row) {
                   $i++;
                   $className=($i%2) ? 'column2' : 'column1';
        ?>
        <tr class="<?php echo $className ?>">
            <td align="left"><?php echo $i; ?></td>
            <td align="left"><a title="<?php echo $row->vUsername; ?>"><?php echo strlen($row->vUsername)>12?substr($row->vUsername,0,12).'..':$row->vUsername; ?></a></td>
            <td align="left"><a title="<?php echo $row->vFirstName.' '.$row->vLastName; ?>"><?php echo strlen($row->vFirstName.' '.$row->vLastName)>10?substr($row->vFirstName.' '.$row->vLastName,0,10).'..':($row->vFirstName.' '.$row->vLastName); ?></a></td>
            <td align="left"><a title="<?php echo $row->vRoleName; ?>"><?php echo strlen($row->vRoleName)>8?substr($row->vRoleName, 0,8).'..':$row->vRoleName; ?></a></td>
            <td align="left"><a title="<?php echo $row->vEmail; ?>"><?php echo strlen($row->vEmail)>20?substr($row->vEmail,0,20).'..':$row->vEmail; ?></a></td>
            <td align="left"><?php echo Utils::formatDate($row->dLastLogin, TRUE); ?></td>
            <td align="left">
               <span class="edit"><a href="<?php echo ConfigUrl::base(); ?>index/adminusers/edit/<?php echo $row->nAId;?>" title="Edit" >Edit</a></span>
               <?php
                if($_SESSION['adminUser']['userID'] != $row->nAId)
                { ?>	
                |<span class="edit"><a href="<?php echo ConfigUrl::base(); ?>index/adminusers/delete/<?php echo $row->nAId;?>" title="Delete" onClick="return confirm('Are you sure want to delete the Admin user ?');" > Delete</a></span>
                <?php }  ?>
               
            </td>
        </tr> 
         <?php } } else {
                    ?>
            <tr class="column1">
                <td align="center" colspan="6">No Results Found</td>
            </tr>
                    <?php
                }
                ?>
</table>
 <div class="more_entries">
            <div class="wp-pagenavi">
                    <?php if(!empty($this->adusers) && $this->pageInfo['maxPages']>1) {
                       
                   
        echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/index/adminusers/'.($this->action?$this->action:'x').'/x/'.($this->searchParam?$this->searchParam.'/':'x/'));
    } ?>
            </div>
        </div>
<?php } ?>

</div>
</div>
    
<!-- End Listing Form --> 


