  <div class="right_column right_section_template">
    <div class="form_container">
      <div class="form_top">My Profile</div>
      <div class="form_bgr">
			 <div class="dboard_tab_container">
					<div class="dboard_tab" >
							<ul>
								<li><a href="<?php echo BASE_URL.'user/profile/accountdetails';?>" class="<?php echo $this->accountDetailsStyle;?>" >Account Details</a></li>
								<li><a href="<?php echo BASE_URL.'user/profile/changepassword';?>" class="<?php echo $this->changePasswordStyle;?>">Change Password</a></li>
<!--								<li><a href="<?php echo BASE_URL.'user/profile/changecreditcard';?>" class="<?php echo $this->changeCreditCardStyle;?>">Change Billing Details</a></li>-->
							</ul>
					</div>
					<div class="clear"></div>
			</div>
	<div class="clear"></div>
	
<?php if($this->activeTab=='accountdetails'){?>
<div class="dboard_tab_contents">
	
    <form id="frmUserProfile" name="frmUserProfile" method="post" action="<?php echo BASE_URL;?>user/profile/accountdetails">
<div class="dashboard_heading">Account Details </div><br/>
<?php PageContext::renderPostAction($this->messageFunction);?>
Fill in the form below to update your account details:<br/><br/>

  <div class="full-width">
  <div class="profile-text-filed">
    <div class="div-20">
      Username*
    </div>
    <div class="div-60">
      <label><?php echo $this->userDetails->vUsername;?></label>
    </div>
  </div>

  <div class="profile-text-filed">
    <div class="div-20">
      First Name*
    </div>
    <div class="div-60">
     <input type="text" value="<?php echo $_POST['vFirstName']?$_POST['vFirstName']:$this->userDetails->vFirstName;?>" name="vFirstName" id="vFirstName" class="form-control" validate="required:true">
  </div>
</div>

  <div class="profile-text-filed">
    <div class="div-20">
      Last Name*
    </div>
    <div class="div-60">
    <input type="text" value="<?php echo $_POST['vLastName']?$_POST['vLastName']:$this->userDetails->vLastName;?>" name="vLastName" id="vLastName" class="form-control" validate="required:true">
  </div>
</div>

  <div class="profile-text-filed">
    <div class="div-20">
      Email*
    </div>
    <div class="div-60">
   <input type="text" value="<?php echo $_POST['vEmail']?$_POST['vEmail']:$this->userDetails->vEmail;?>" name="vEmail" id="vEmail" class="form-control {required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}" validate="required:true">
  </div>
</div>

  <div class="profile-text-filed">
    <div class="div-20">
      Invoice Email
    </div>
    <div class="div-60">
   <input type="text" value="<?php echo $_POST['vInvoiceEmail']?$_POST['vInvoiceEmail']:$this->userDetails->vInvoiceEmail;?>" name="vInvoiceEmail" id="vInvoiceEmail" class="form-control { email:true, messages:{ email:'Please enter a valid email address'}}">
  </div>
</div>

  <div class="profile-text-filed">
    <div class="div-20">
      Address
    </div>
    <div class="div-60">
  <input type="text" value="<?php echo $_POST['vAddress']?$_POST['vAddress']:$this->userDetails->vAddress;?>" name="vAddress" id="vAddress" class="form-control">
  </div>
</div>

  <div class="profile-text-filed">
    <div class="div-20">
      Country
    </div>
    <div class="div-60">
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

  <div class="profile-text-filed">
    <div class="div-20">
      State
    </div>
    <div class="div-60">
  <input type="text"  value="<?php echo $_POST['vState']?$_POST['vState']:$this->userDetails->vState;?>" name="vState" id="vState" class="form-control">
  </div>
</div>

  <div class="profile-text-filed">
    <div class="div-20">
      City
    </div>
    <div class="div-60">
 <input type="text" value="<?php echo $_POST['vCity']?$_POST['vCity']:$this->userDetails->vCity;?>" name="vCity" id="vCity" class="form-control">
  </div>
</div>

  <div class="profile-text-filed">
    <div class="div-20">
      Zip
    </div>
    <div class="div-60">
<input type="text" value="<?php echo $_POST['vZipcode']?$_POST['vZipcode']:$this->userDetails->vZipcode;?>" name="vZipcode" id="vZipcode" class="form-control">
  </div>
</div>


  <div class="profile-text-filed">
    <div class="div-20">
      Phone
    </div>
    <div class="div-60">
<input type="text" value="<?php echo $_POST['vPhoneNumber']?$_POST['vPhoneNumber']:$this->userDetails->vPhoneNumber;?>" name="vPhoneNumber" id="vPhoneNumber" class="form-control" maxlength="15">
  </div>
</div>

 <div class="profile-text-filed">
    <div class="div-20">
      Fax
    </div>
    <div class="div-60">
<input type="text" value="<?php echo $_POST['vFax']?$_POST['vFax']:$this->userDetails->vFax;?>" name="vFax" id="vFax" class="form-control">
  </div>
</div>

<div class="profile-text-filed">
  <input type="submit" class="more-btn" name="btnProfile" value="Save Changes">
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
  <div class="div-60">
  <div class="profile-text-filed">
    <input type="password" value="<?php echo $_POST['currentpassword'];?>" name="currentpassword" id="currentpassword" class="form-control" validate="required:true" placeholder="Current Password*">
  </div>

    <div class="profile-text-filed">
      <input type="password" value="<?php echo $_POST['password'];?>" name="password" id="password" class="form-control" validate="required:true" minlength="8" placeholder="Password*">

    </div>
    <div class="profile-text-filed">
      <input type="password" value="<?php echo $_POST['confirm_password'];?>" name="confirm_password" id="confirm_password" class="form-control" validate="required:true" minlength="8" placeholder="Confirm  Password*">

    </div>

     <div class="profile-text-filed">
<input type="submit" class="more-btn" name="btnProfile" value="Save Changes">
     </div>
</div>
</form>
        </div>
	 
<?php } ?>

 <?php if($this->activeTab=='changecreditcard'){?>
<div class="dboard_tab_contents">
    <div class="dashboard_heading">Change Billing Details</div><br/>
<?php PageContext::renderPostAction($this->messageFunction);?>
Fill in the form below to update your credit card details:<br/><br/>
<form id="frmCreditCard" name="frmCreditCard" method="post" action="<?php echo BASE_URL.'user/profile/changecreditcard';?>">

<div class="div-60">
  <div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vFirstName']?$_POST['vFirstName']:$this->cardDetails->vFirstName;?>" name="vFirstName" id="vFirstName" class="form-control" placeholder="First Name*" validate="required:true" >
      <label class="error" id="first_name_field_error"></label>
</div>
  <div class="profile-text-filed">
    <input type="text" value="<?php echo $_POST['vLastName']?$_POST['vLastName']:$this->cardDetails->vLastName;?>" name="vLastName" id="vLastName" class="form-control" placeholder="Last Name" validate="required:true">
      <label class="error" id="last_name_field_error"></label>
  </div>

 <div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vEmail']?$_POST['vEmail']:$this->cardDetails->vEmail;?>" name="vEmail" id="vEmail" placeholder="Email*" class="form-control {required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}" validate="required:true">
      <label class="error" id="email_field_error"></label>
 </div>


<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vAddress']?$_POST['vAddress']:$this->cardDetails->vAddress;?>" name="vAddress" id="vAddress" class="form-control" placeholder="Address*"validate="required:true">
      <label class="error" id="address_field_error"></label>
</div>

<div class="profile-text-filed">
   <?php 
          if(trim($this->cardDetails->vCountry) == "US"){
              $this->cardDetails->vCountry = "United States";
          }
          ?>
          <select class="form-control" id="vCountry" name="vCountry" validate="required:true">
          <option value="">Select Country*</option>
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
          <label class="error" id="country_field_error"></label>
</div>

<div class="profile-text-filed">
    <input type="text"  value="<?php echo $_POST['vState']?$_POST['vState']:$this->cardDetails->vState;?>" name="vState" id="vState" class="form-control" placeholder="State*" validate="required:true">
        <label class="error" id="state_field_error"></label>
</div>

<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vCity']?$_POST['vCity']:$this->cardDetails->vCity;?>" name="vCity" id="vCity" class="form-control" placeholder="City*" validate="required:true">
      <label class="error" id="city_field_error"></label>
</div>

<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vZipcode']?$_POST['vZipcode']:$this->cardDetails->vZipcode;?>" name="vZipcode" id="vZipcode" class="form-control" placeholder="Zip*" validate="required:true">
      <label class="error" id="zip_field_error"></label>
</div>
<div class="card-detail-outer">

<div class="profile-text-filed">

  <input type="text" value="<?php echo $this->cardDetails->vNumber;?>" name="vNumber" id="vNumber" class="form-control" placeholder="Card Number*" validate="required:true" minlength="16" maxlength="16" validate="required:true">
          <label class="error" id="number_field_error"></label>
</div>

<div class="profile-text-filed">
  <input type="password" value="<?php echo $this->cardDetails->vCode;?>" name="vCode" id="vCode" class="form-control" placeholder="CVV/CVV2 No.*" minlength="3" maxlength="4" size="4" validate="required:true">
      <label class="error" id="code_field_error"></label>
  </div>

<div class="profile-text-filed">
  <div class="full-width la-height">
  Expiry Date(MM/YYYY)*
</div>
<div class="div-45">
<select name="vMonth" id="vMonth" class="form-control">

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
</div>


<div class="div-45 pull-right">
<select name="vYear" id="vYear" class="form-control">

<?php for($i=date('Y'); $i<=(date('Y')+50); $i++) { 
if($this->cardDetails->vYear==$i)
$selected = 'selected';
else
$selected = '';?>

<option value="<?php echo $i;?>" <?php echo $selected;?>>
<?php echo $i;?>
</option>
<?php  
} ?>

</select>
</div>
 <label class="error" id="date_field_error"></label>
</div>

</div>

<div class="profile-text-filed">
   <?php  if($this->activeTab=='changecreditcard'){ ?>
          <input type="submit" class="more-btn" name="btnProfile" value="Save Changes">
          <?php } else { ?>
           <input type="submit" class="more-btn" name="btnProfile"  value="Save Changes" >
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
			





