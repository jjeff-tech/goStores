  <div class="right_column right_section_template">
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
	
    <form id="frmUserProfile" name="frmUserProfile" method="post" action="<?php echo BASE_URL;?>user/profile/accountdetails">
<div class="dashboard_heading">Account Details </div><br/>
<?php PageContext::renderPostAction($this->messageFunction);?>
Fill in the form below to update your account details:<br/><br/>
<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="form_tbls">
  <tbody>
    <tr>
      <th width="25%" valign="middle" align="left">Username<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><b><label><?php echo $this->userDetails->vUsername;?></label></b>
      
      </td>
    </tr>
    
    <tr>
      <th  valign="middle" align="left"> First Name<span style="color: red">*</span> </th>
      <td  valign="middle" align="left"><input type="text" value="<?php echo $_POST['vFirstName']?$_POST['vFirstName']:$this->userDetails->vFirstName;?>" name="vFirstName" id="vFirstName" class="txt_area" validate="required:true"></td>
    </tr>
    <tr>
      <th valign="middle" align="left" > Last Name<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $_POST['vLastName']?$_POST['vLastName']:$this->userDetails->vLastName;?>" name="vLastName" id="vLastName" class="txt_area" validate="required:true"></td>
    </tr>
    <tr>
      <th align="left" valign="middle">Email<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $_POST['vEmail']?$_POST['vEmail']:$this->userDetails->vEmail;?>" name="vEmail" id="vEmail" class="txt_area {required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}" validate="required:true"></td>
    </tr>
     <tr>
      <th align="left" valign="middle">Invoice Email </th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $_POST['vInvoiceEmail']?$_POST['vInvoiceEmail']:$this->userDetails->vInvoiceEmail;?>" name="vInvoiceEmail" id="vInvoiceEmail" class="txt_area { email:true, messages:{ email:'Please enter a valid email address'}}"></td>
    </tr>
    <tr>
      <th align="left" valign="middle"> Address</th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $_POST['vAddress']?$_POST['vAddress']:$this->userDetails->vAddress;?>" name="vAddress" id="vAddress" class="txt_area"></td>
    </tr>
   <tr>
      <th align="left" valign="middle">Country  </th>
      <td valign="middle" align="left"><select class="select_box" id="vCountry" name="vCountry" >
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
      </td>
    </tr>
    <tr>
            <tr>
      <th align="left" valign="middle"> State </th>
      <td align="left" valign="middle">
        <input type="text"  value="<?php echo $_POST['vState']?$_POST['vState']:$this->userDetails->vState;?>" name="vState" id="vState" class="txt_area">
      </td>
    </tr>
    <tr>
      <th align="left" valign="middle"> City </th>
      <td align="left" valign="middle"><input type="text" value="<?php echo $_POST['vCity']?$_POST['vCity']:$this->userDetails->vCity;?>" name="vCity" id="vCity" class="txt_area"></td>
    </tr>

    <tr>
      <th align="left" valign="middle"> Zip </th>
      <td align="left" valign="middle"><input type="text" value="<?php echo $_POST['vZipcode']?$_POST['vZipcode']:$this->userDetails->vZipcode;?>" name="vZipcode" id="vZipcode" class="txt_area"></td>
    </tr>
    <tr>
      <th align="left" valign="middle"> Phone </th>
      <td align="left" valign="middle"><input type="text" value="<?php echo $_POST['vPhoneNumber']?$_POST['vPhoneNumber']:$this->userDetails->vPhoneNumber;?>" name="vPhoneNumber" id="vPhoneNumber" class="txt_area" maxlength="15"></td>
    </tr>
    <tr>
      <th align="left" valign="middle"> Fax </th>
      <td align="left" valign="middle"><input type="text" value="<?php echo $_POST['vFax']?$_POST['vFax']:$this->userDetails->vFax;?>" name="vFax" id="vFax" class="txt_area"></td>
    </tr>
 <!--   <tr>
      <th align="left" valign="middle"> Url :</th>
      <td><input type="text" value="" name="txtUrl" id="txtUrl" class="txt_area"></td>
    </tr>
    <tr>
      <th height="30" align="left" valign="middle"> Gender :</th>
      <td align="left" valign="middle"><input type="radio" checked="" value="M" name="radGender">
        Male
        <input type="radio" value="F" name="radGender">
        Female</td>
    </tr>
    <!--
    <tr>
            <th valign="top"> Education:</th>
            <td><input type="text" class="txt_area" id="txtEdu" name="txtEdu" value=""/></td>

    </tr>
    <tr>
            <th valign="top"> Description :</th>
            <td><textarea  class="txt_area" id="txtDesc" name="txtDesc"></textarea></td>

    </tr>
   
    <tr>
      <th height="50" align="left" valign="middle"> AlertStatus :</th>
      <td align="left" valign="middle"><input type="radio" checked="" value="Y" name="radAlert">
        Y
        <input type="radio" value="N" name="radAlert">
        N</td>
    </tr> -->
    <tr>
      <th>&nbsp;</th>
      <td valign="middle" align="left"><input type="submit" class="button_orange" name="btnProfile" value="Save Changes">
      </td>
    </tr>
  </tbody>
</table> 
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
<form id="frmCreditCard" name="frmCreditCard" method="post" action="<?php echo BASE_URL.'user/profile/changecreditcard';?>">
<table width="98%" cellspacing="0" cellpadding="0" border="0" align="center" class="form_tbls">
  <tbody>
  <!--  <tr>
      <th width="15%" valign="middle" align="left">Username<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><b><label><?php echo $this->userDetails->vUsername;?></label></b>
      
      </td>
    </tr>-->
     <tr>
      <th  width="25%" valign="middle" align="left"> First Name<span style="color: red">*</span> </th>
      <td  valign="middle" align="left"><input type="text" value="<?php echo $_POST['vFirstName']?$_POST['vFirstName']:$this->cardDetails->vFirstName;?>" name="vFirstName" id="vFirstName" class="txt_area" validate="required:true">
      <label class="error" id="first_name_field_error"></label></td>
    </tr>
    <tr>
      <th valign="middle" align="left" > Last Name<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $_POST['vLastName']?$_POST['vLastName']:$this->cardDetails->vLastName;?>" name="vLastName" id="vLastName" class="txt_area" validate="required:true">
      <label class="error" id="last_name_field_error"></label></td>
    </tr>
    <tr>
      <th align="left" valign="middle">Email<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $_POST['vEmail']?$_POST['vEmail']:$this->cardDetails->vEmail;?>" name="vEmail" id="vEmail" class="txt_area {required:true, email:true, messages:{required:'Please enter your email address', email:'Please enter a valid email address'}}" validate="required:true">
      <label class="error" id="email_field_error"></label></td>
    </tr>
    <tr>
      <th align="left" valign="middle"> Address<span style="color: red">*</span></th>
      <td valign="middle" align="left"><input type="text" value="<?php echo $_POST['vAddress']?$_POST['vAddress']:$this->cardDetails->vAddress;?>" name="vAddress" id="vAddress" class="txt_area" validate="required:true">
      <label class="error" id="address_field_error"></label></td>
    </tr>
   <tr>
      <th align="left" valign="middle">Country <span style="color: red">*</span> </th>
      <td valign="middle" align="left">
          <?php 
          if(trim($this->cardDetails->vCountry) == "US"){
              $this->cardDetails->vCountry = "United States";
          }
          ?>
          <select class="select_box" id="vCountry" name="vCountry" validate="required:true">
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
          <label class="error" id="country_field_error"></label>
      </td>
    </tr>
    <tr>
            <tr>
      <th align="left" valign="middle"> State <span style="color: red">*</span></th>
      <td align="left" valign="middle">
        <input type="text"  value="<?php echo $_POST['vState']?$_POST['vState']:$this->cardDetails->vState;?>" name="vState" id="vState" class="txt_area" validate="required:true">
        <label class="error" id="state_field_error"></label>
      </td>
    </tr>
    <tr>
      <th align="left" valign="middle"> City <span style="color: red">*</span></th>
      <td align="left" valign="middle"><input type="text" value="<?php echo $_POST['vCity']?$_POST['vCity']:$this->cardDetails->vCity;?>" name="vCity" id="vCity" class="txt_area" validate="required:true">
      <label class="error" id="city_field_error"></label></td>
    </tr>

    <tr>
      <th align="left" valign="middle"> Zip <span style="color: red">*</span></th>
      <td align="left" valign="middle"><input type="text" value="<?php echo $_POST['vZipcode']?$_POST['vZipcode']:$this->cardDetails->vZipcode;?>" name="vZipcode" id="vZipcode" class="txt_area" validate="required:true">
      <label class="error" id="zip_field_error"></label></td>
    </tr>
    <tr>
      <th  valign="middle" align="left"> Card Number<span style="color: red">*</span> </th>
      <td  valign="middle" align="left"><input type="text" value="<?php echo $this->cardDetails->vNumber;?>" name="vNumber" id="vNumber" class="txt_area" validate="required:true" minlength="16" maxlength="16" validate="required:true">
          <label class="error" id="number_field_error"></label></td>
    </tr>
    <tr>
      <th valign="middle" align="left" >  CVV/CVV2 No.<span style="color: red">*</span> </th>
      <td valign="middle" align="left"><input type="password" value="<?php echo $this->cardDetails->vCode;?>" name="vCode" id="vCode" class="txt_area_small"  minlength="3" maxlength="4" size="4" validate="required:true">
      <label class="error" id="code_field_error"></label></td>
    </tr>
     <tr>
      <th valign="middle" align="left" > Expiry Date(MM/YYYY)<span style="color: red">*</span> </th>
      <td valign="middle" align="left">
      
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
         
          <!--<input type="text" value="<?php echo $this->cardDetails->vMonth;?>" name="vMonth" id="vMonth" class="txt_area_small" validate="required:true" minlength="1"  maxlength="2" size="2">
      <input type="text" value="<?php echo $this->cardDetails->vYear;?>" name="vYear" id="vYear" class="txt_area_small" validate="required:true"  minlength="1"  maxlength="2" size="2">-->
      <label class="error" id="date_field_error"></label>
      </td>
    </tr>
    <tr>
      <th>&nbsp;</th>
      <td valign="middle" align="left">
          <?php  if($this->activeTab=='changecreditcard'){ ?>
          <input type="submit" class="button_orange" name="btnProfile" value="Save Changes">
          <?php } else { ?>
           <input type="submit" class="button_orange" name="btnProfile"  value="Save Changes" >
          <?php } ?>
      </td>
    </tr>
</table>
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
			





