
  <div class="right_column">
    <div class="form_container">
      <div class="form_top">My Profile</div>
      <div class="form_bgr">
			 <div class="dboard_tab_container">
					<div class="dboard_tab" >
							<ul>
								<li><a href="<?php echo BASE_URL.'user/profile/accountdetails';?>" class="<?php echo $this->accountDetailsStyle;?>" >Account Details</a></li>
								<li><a href="<?php echo BASE_URL.'user/profile/changepassword';?>" class="<?php echo $this->changePasswordStyle;?>">Change Password</a></li>
								<li><a href="<?php echo BASE_URL.'user/profile/changecreditcard';?>" class="<?php echo $this->changeCreditCardStyle;?>">Change Billing Details</a></li>
							</ul>
					</div>
					<div class="clear"></div>
			</div>
	<div class="clear"></div>
	
<?php if($this->activeTab=='accountdetails'){?>
<div class="dboard_tab_contents">
	
    <form id="frmUserProfile" class="form-horizontal" name="frmUserProfile" method="post" action="<?php echo BASE_URL;?>user/profile/accountdetails">
<div class="dashboard_heading">Account Details </div><br/>
<?php PageContext::renderPostAction($this->messageFunction);?>
Fill in the form below to update your account details:<br/><br/>

<div class="text-left row">
              <div class="main_headings">
                <h2><?php echo $this->pageTitle;?></h2>
              </div>
                <div class="col-sm-12">
                  <span id="emailAddress_err" class="error"><?php echo $this->errMsg; ?></span>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Username <span class="text-danger small">*</span></label>
                  <label class="col-sm-8 control-label text_align_left"><?php echo $this->userDetails->vUsername;?></label>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">First Name <span class="text-danger small">*</span></label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vFirstName']?$_POST['vFirstName']:$this->userDetails->vFirstName;?>" name="vFirstName" id="vFirstName" validate="required:true" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Last Name <span class="text-danger small">*</span></label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vLastName']?$_POST['vLastName']:$this->userDetails->vLastName;?>" name="vLastName" id="vLastName" validate="required:true" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Email <span class="text-danger small">*</span></label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vEmail']?$_POST['vEmail']:$this->userDetails->vEmail;?>" name="vEmail" id="vEmail" validate="required:true" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Invoice Email <span class="text-danger small">*</span></label>
                  <div class="col-sm-8">
                    <input  type="text" value="<?php echo $_POST['vInvoiceEmail']?$_POST['vInvoiceEmail']:$this->userDetails->vInvoiceEmail;?>" name="vInvoiceEmail" id="vInvoiceEmail" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Address</label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vAddress']?$_POST['vAddress']:$this->userDetails->vAddress;?>" name="vAddress" id="vAddress" class="form-control">
                  </div>
                </div>
                 <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Country</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="vCountry" name="vCountry" >
                      <option value="">Select Country</option>
                      <?php global $countries;
                      foreach($countries as $countryCode=>$country){
                           if($this->userDetails->vCountry == $country)
                               $selected = 'selected';
                           else
                               $selected = '';
                          ?>
                      <option value="<?php echo $country;?>" <?php echo $selected;?>><?php echo $country;?></option>
                      <?php }?>
                      <!--<option value="undefined">undefined</option>-->
                    </select>
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">State</label>
                  <div class="col-sm-8">
                    <input type="text"  value="<?php echo $_POST['vState']?$_POST['vState']:$this->userDetails->vState;?>" name="vState" id="vState" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">City</label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vCity']?$_POST['vCity']:$this->userDetails->vCity;?>" name="vCity" id="vCity" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Zip</label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vZipcode']?$_POST['vZipcode']:$this->userDetails->vZipcode;?>" name="vZipcode" id="vZipcode" class="form-control">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Phone</label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vPhoneNumber']?$_POST['vPhoneNumber']:$this->userDetails->vPhoneNumber;?>" name="vPhoneNumber" id="vPhoneNumber" class="form-control" maxlength="15">
                  </div>
                </div>
                <div class="form-group has-feedback">
                  <label class="col-sm-3 control-label">Fax <span class="text-danger small">*</span></label>
                  <div class="col-sm-8">
                    <input type="text" value="<?php echo $_POST['vFax']?$_POST['vFax']:$this->userDetails->vFax;?>" name="vFax" id="vFax" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-3 col-sm-8">
                    <input type="submit" class="button_orange" name="btnProfile" value="Save Changes">
                  </div>
                </div>
            </div>
    </form>
	 
	</div>
        <?php } ?>

<?php if($this->activeTab=='changepassword'){?>
        <div class="dboard_tab_contents">
            <div class="dashboard_heading">Change Password </div><br/>
<?php PageContext::renderPostAction($this->messageFunction);?>
Fill in the form below to update your password:<br/><br/>
<form id="frmChangePassword" name="frmChangePassword" method="post" action="<?php echo BASE_URL.'user/profile/changepassword';?>">

<table width="98%" cellspacing="0" cellpadding="0" border="0" align="center" class="form_tbls">
  <tbody>
   <!-- <tr>
      <th width="15%" valign="middle" align="left">Username<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><b><label><?php echo $this->userDetails->vUsername;?></label></b>
      
      </td>
    </tr>-->
    
    <tr>
      <th width="25%" valign="middle" align="left"> Current Password<span style="color: red">*</span> </th>
      <td  valign="middle" align="left"><input type="password" value="<?php echo $_POST['currentpassword'];?>" name="currentpassword" id="currentpassword" class="txt_area" validate="required:true"></td>
    </tr>
    <tr>
      <th valign="middle" align="left" >  Password<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="password" value="<?php echo $_POST['password'];?>" name="password" id="password" class="txt_area" validate="required:true" minlength="4"></td>
    </tr>
     <tr>
      <th valign="middle" align="left" > Confirm  Password<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="password" value="<?php echo $_POST['confirm_password'];?>" name="confirm_password" id="confirm_password" class="txt_area" validate="required:true" minlength="4"></td>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <td valign="middle" align="left"><input type="submit" class="button_orange" name="btnProfile" value="Save Changes">
      </td>
    </tr>
</table>
</form>
        </div>
	 
<?php } ?>

 <?php if($this->activeTab=='changecreditcard'){?>
<div class="dboard_tab_contents">
    <div class="dashboard_heading">Change Billing Details</div><br/>
<?php PageContext::renderPostAction($this->messageFunction);?>
Fill in the form below to update your credit card details:<br/><br/>
<form id="frmCreditCard" class="form-horizontal" name="frmCreditCard" method="post" action="<?php echo BASE_URL.'user/profile/changecreditcard';?>">
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">First Name <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $_POST['vFirstName']?$_POST['vFirstName']:$this->cardDetails->vFirstName;?>" name="vFirstName" id="vFirstName" class="form-control" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">Last Name <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $_POST['vLastName']?$_POST['vLastName']:$this->cardDetails->vLastName;?>" name="vLastName" id="vLastName" class="form-control" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">Email <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $_POST['vEmail']?$_POST['vEmail']:$this->cardDetails->vEmail;?>" name="vEmail" id="vEmail" class="form-control {required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">Address <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $_POST['vAddress']?$_POST['vAddress']:$this->cardDetails->vAddress;?>" name="vAddress" id="vAddress" class="form-control" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">Country <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <select class="form-control" id="vCountry" name="vCountry" validate="required:true">
          <option value="">Select Country</option>
          <?php global $countries;
          foreach($countries as $countryCode=>$country){
               if($this->cardDetails->vCountry == $country)
                   $selected = 'selected';
               else
                   $selected = '';
              ?>
          <option value="<?php echo $country;?>" <?php echo $selected;?>><?php echo $country;?></option>
          <?php }?>
          <!--<option value="undefined">undefined</option>-->

        </select>
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">State <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text"  value="<?php echo $_POST['vState']?$_POST['vState']:$this->cardDetails->vState;?>" name="vState" id="vState" class="form-control" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">City <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $_POST['vCity']?$_POST['vCity']:$this->cardDetails->vCity;?>" name="vCity" id="vCity" class="form-control" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">Zip <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $_POST['vZipcode']?$_POST['vZipcode']:$this->cardDetails->vZipcode;?>" name="vZipcode" id="vZipcode" class="form-control" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">Card Number <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $this->cardDetails->vNumber;?>" name="vNumber" id="vNumber" class="form-control" validate="required:true" minlength="16" maxlength="16">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">CVV/CVV2 No. <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <input type="text" value="<?php echo $this->cardDetails->vCode;?>" name="vCode" id="vCode" class="txt_area_small form-control" validate="required:true">
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label">Expiry Date(MM/YYYY) <span class="text-danger small">*</span></label>
    <div class="col-sm-8">
      <select name="vMonth" id="vMonth" class="select_small">

          <?php for($i=1; $i<=12; $i++) {
              if($this->cardDetails->vMonth==$i)
                  $selected = 'selected';
              else
                  $selected = '';
              ?>
          

          <option value="<?php echo $i;?>" <?php echo $selected;?>>
              <?php if($i<10){echo '0'.$i; }else {echo $i;}?>
          </option>
          <?php                         } ?>

              </select>
                    <select name="vYear" id="vYear" class="select_small">

          <?php for($i=date('Y'); $i<=(date('Y')+50); $i++) { 
              if($this->cardDetails->vYear==$i)
                  $selected = 'selected';
              else
                  $selected = '';?>
          
          <option value="<?php echo $i;?>" <?php echo $selected;?>>
              <?php echo $i;?>
          </option>
          <?php                                             } ?>

          </select>
    </div>
  </div>
  <div class="form-group has-feedback">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-8">
      <?php  if($this->activeTab=='changecreditcard'){ ?>
        <input type="submit" class="button_orange" name="btnProfile" value="Save Changes">
      <?php } else { ?>
        <input type="submit" class="button_orange" name="btnProfile"  value="Save Changes" >
      <?php } ?>
    </div>
  </div>
</form>
</div>

<?php } ?>
		
		
		
      </div>
     
    </div>
  </div>
 
<script type="text/javascript">
   function checkCvv(){
      
      
            var eyear      =  $("#vYear").val();
             var emonth     =  $("#vMonth").val();
           
             
             var currentTime = new Date()

          
            var currentMonth = currentTime.getMonth() + 1;
            var currentYear  =  currentTime.getFullYear().toString().substr(2,2);
            var  emonthSub   = emonth.toString()[0];;
            
            
             var eyear      =  eyear.substr(2,2);
           
            if(emonthSub==0){
               emonth = emonth.toString()[1];
               
            }
           
           
             
             if(eyear<currentYear){
                 alert('Please Select Valid Year');
                  return false;
             }
             //alert(eyear);
             //alert(currentYear);
              if(eyear<=currentYear){
                  
                  if(emonth<currentMonth){
                    
                    alert('Please Select Valid Month');
                     return false;
                  }
             }
             return true;
          
   }
</script>
			





