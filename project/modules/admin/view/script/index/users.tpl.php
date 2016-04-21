<div class="form_container">
<div class="form_top"><?php echo $this->pageTitle;?></div>
<div class="form_bgr">
<?php
 if($this->action=="edit" || $this->action=="add")// add plan details
 {
   $this->id=="" ? $btnval = "Save Changes" : $btnval = "Save Changes";
?>
<?php PageContext::renderPostAction('errormessage');
$this->messageFunction ='';
?>
<form id="frmSiteUsers" action="<?php echo ConfigUrl::base(); ?>index/users/<?php echo $this->action; ?><?php if($this->action=='edit'){ echo '/'.$this->id;}?>" method="post">
    <input type="hidden" name="id" value="<?php echo $this->id?>">
    <input type="hidden" name="action" value="<?php echo $this->action ?>">
    <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">

        
                <tr>
		  <th align="left" valign="top" width="25%">User Name <span class="mandred">*</span></th>
		  <td align="left" valign="top"><input id="uname" name="uname" type="text" class="inp-form" validate="required:true"  value="<?php echo $_POST['uname']?$_POST['uname']:$this->userArray[0]->vUsername; ?>" /> </td>
		</tr>
		<tr>
		  <th align="left" valign="top">Password<?php if($this->addEnabled){?><span class="mandred">*</span><?php }?></th>
		  <td align="left" valign="top"><input id="pwd" name="pwd" type="password" class="valid" <?php if($this->addEnabled){?>validate="required:true" minlength="4" <?php }?> value="<?php echo $_POST['pwd']; ?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">Confirm Password <?php if($this->addEnabled){?><span class="mandred">*</span><?php }?></th>
		  <td align="left" valign="top"><input id="cpwd" name="cpwd" type="password" class="valid" <?php if($this->addEnabled){?>validate="required:true" minlength="4"  <?php }?> value="<?php echo $_POST['cpwd']; ?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">First Name <span class="mandred">*</span></th>
		  <td align="left" valign="top"><input id="fname" name="fname" type="text" class="inp-form" validate="required:true"  value="<?php echo $_POST['fname']?$_POST['fname']:$this->userArray[0]->vFirstName; ?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">Last Name</th>
		  <td align="left" valign="top"><input id="lname" name="lname" type="text" class="inp-form"   value="<?php echo $_POST['lname']?$_POST['lname']:$this->userArray[0]->vLastName; ?>" /></td>
		</tr>
		<tr>
		  <th align="left" valign="top">Email<span class="mandred">*</span></th>
		  <td align="left" valign="top"><input id="email" name="email" type="text" class="inp-form{required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}" validate="required:true"  value="<?php echo $_POST['email']?$_POST['email']:$this->userArray[0]->vEmail; ?>" /></td>
		</tr>
                <tr>
		  <th align="left" valign="top">Invoice Email</th>
		  <td align="left" valign="top"><input id="invoice_email" name="invoice_email" type="text" class="inp-form{ email:true, messages:{email:'Please enter a valid email address'}}" value="<?php echo $_POST['invoice_email']?$_POST['invoice_email']:$this->userArray[0]->vInvoiceEmail; ?>" /></td>
		</tr>
                <tr>
		  <th align="left" valign="top">Address</th>
                  <td align="left" valign="top"><textarea cols="30" rows="5" id="address" name="address" ><?php echo $_POST['address']?$_POST['address']:$this->userArray[0]->vAddress; ?></textarea></td>
		</tr>
                <tr>
		  <th align="left" valign="top">Country</th>
                  <td align="left" valign="top"><input id="country" name="country" type="text" class="inp-form"  value="<?php echo $_POST['country']?$_POST['address']:$this->userArray[0]->vCountry; ?>" ></td>
		</tr>
                 <tr>
		  <th align="left" valign="top">State</th>
                  <td align="left" valign="top"><input id="state" name="state" type="text" class="inp-form"   value="<?php echo $_POST['state']?$_POST['address']:$this->userArray[0]->vState; ?>" ></td>
		</tr>
                 <tr>
		  <th align="left" valign="top">Zipcode</th>
                  <td align="left" valign="top"><input id="zip_code" name="zip_code" type="text" class="inp-form"  value="<?php echo $_POST['zip_code']?$_POST['zip_code']:$this->userArray[0]->vZipcode; ?>" ></td>
		</tr>
                <tr>
		  <th align="left" valign="top">Phone number</th>
                  <td align="left" valign="top"><input id="phone_number" name="phone_number" type="text" class="inp-form"  maxlength="10" value="<?php echo $_POST['phone_number']?$_POST['phone_number']:$this->userArray[0]->vPhoneNumber; ?>" ></td>
		</tr>
                <?php if($this->editEnabled){?>
		<tr>
		  <th align="left" valign="top">Status</th>
		  <td align="left" valign="top">
                    <select name="status"  id="status">
                      <option  value="1" <?php echo (($this->userArray[0]->nStatus==1)?'selected="selected"':''); ?> >Active    </option>
                      <option  value="2" <?php echo (($this->userArray[0]->nStatus==2)?'selected="selected"':''); ?> >In Active </option>
                    </select>
                  </td>
		</tr>
                <?php }?>
                <tr>
                    <th align="left" valign="top">&nbsp;</th>
                    <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <th align="left" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/index/users">Cancel</a></div></th>
                    <td align="left" valign="top">
                       <input type="submit"  name="btnAdd"  value="<?php echo $btnval;?>"  />
                    </td>
                </tr>
     </table>
 </form>

<?php } else {   ?>  
 <!-- Search Form -->
  <div class="r_float" >
<form class="cmxform" id="siteusers" action="<?php echo BASE_URL; ?>admin/index/users/" method="post" >
    <input type="hidden" name="action" value="search">
   
   <div class="l_float" > 
	<div class="admin_search_container">
             <input type="hidden" name="page" id="page" value="<?php echo $this->pageCount;?>">
        <input name="search" id="search" type="text" class="search_box" placeholder="Search by Username,Name or Email" value="<?php echo $this->txtSearch; ?>">
        <input name="btnSearch" type="submit" class="button_orange" value="Search">&nbsp;&nbsp;
   </div>
   </div>
   <div class="l_float" >
    <div class="addnew"><?php if($this->action!="add" && $this->action!="edit") { ?><a href="<?php echo BASE_URL; ?>admin/index/users/add">Add User</a><?php } ?></div>
	</div>
</form>
<div class="clear"></div>
 </div>
<!-- End Search Form -->	  
<br><br><br>
<div  align="left">
<?php PageContext::renderPostAction($this->messageFunction);
$this->messageFunction ='';?>
</div>
<!-- Listing Form -->
<table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
    <tr class="heading1">
      <td width="8%" align="left">Sl No.</td>
      <td width="14%" align="left">User Name<a href="javascript:void(0);" id="<?php echo BASE_URL;?>admin/index/users/<?php echo $this->usernameSortAction;?>/x"  class="<?php echo $this->usernameSortStyle;?>"></a></td>
      <td width="17%" align="left">Name</td>
      <td width="18%" align="left">Email</td>
      <td width="22%" align="left">Last Login <a href="javascript:void(0);" id="<?php echo BASE_URL;?>admin/index/users/<?php echo $this->lastLoginSortAction;?>/x" class="<?php echo $this->lastLoginSortStyle;?>"></a></td>
      <td width="12%" align="left"  class="fixed_width">Actions</td>
    </tr>
    <?php
    if(!empty($this->siteusers)) {
         
        $i=0;
        $i=$this->pageInfo['base'];
        foreach($this->siteusers as $users) {
          $i++;
          $className=($i%2) ? 'column2' : 'column1';
    ?>
    <tr class="<?php echo $className ?>">
     <td align="left"><?php echo $i; ?></td>
     <td align="left"><a title="<?php echo $users->vUsername; ?>"><?php echo strlen(trim($users->vUsername))>20?substr(trim($users->vUsername),0,20).'..':$users->vUsername; ?></a></td>
     <td align="left"><a title="<?php echo $users->vFirstName.' '.$users->vLastName; ?>"><?php echo strlen(trim($users->vFirstName).($users->vLastName?' '.trim($users->vLastName):''))>16?substr((trim($users->vFirstName).($users->vLastName?' '.trim($users->vLastName):'')),0,16).'..':(trim($users->vFirstName).($users->vLastName?' '.trim($users->vLastName):'')); ?></a></td>
     <td align="left"><a title="<?php echo $users->vEmail; ?>"><?php echo strlen($users->vEmail)>32?substr($users->vEmail,0,32).'..':$users->vEmail; ?></a></td>
     <td align="left"><?php echo Utils::formatDateUS($users->dLastLogin, TRUE); ?></td>
     <td align="left">
     <?php // {
       //if($users->nStatus == 1) echo '<a href="'.ConfigUrl::base().'index/users/deactivate/'.$users->nUId.'" title="Deactivate user">Deactivate</a>' ;
     //  elseif($users->nStatus == 2) echo '<a href="'.ConfigUrl::base().'index/users/activate/'.$users->nUId.'" title="Activate user">Activate</a>';
      // else
	// echo 'Pending';
    // } ?>
    <span class="edit"> <a href="<?php echo ConfigUrl::base();?>index/addtowallet/<?php echo $users->nUId?>" title="Wallet Management">Wallet</a></span>

        | <span class="edit"><a href="<?php echo BASE_URL;?>admin/index/users/edit/<?php echo $users->nUId;?>" title="Wallet Management">Edit</a></span>
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
                    <?php if(!empty($this->siteusers) && $this->pageInfo['maxPages']>1) {
        echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/index/users/'.($this->action?$this->action:'x').'/x/'.($this->searchParam?$this->searchParam.'/':'x/'));
    } ?>
            </div>
        </div>
<?php }?>
</div>
</div>
<div class="form_bottom"></div> 