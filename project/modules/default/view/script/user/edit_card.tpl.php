  <div class="right_column right_section_template">
    <div class="form_container">
      <div class="form_top">Edit Card Details</div>
      <div class="form_bgr">
			 <div class="dboard_tab_container">
					<div class="dboard_tab" >
							<ul>
									<li><a href="" class="<?php echo $this->changeCreditCardStyle;?>">Change Billing Details</a></li>
							</ul>
					</div>
					<div class="clear"></div>
			</div>
	<div class="clear"></div>
	





<div class="dboard_tab_contents">
    <div class="dashboard_heading">Change Billing Details</div><br/>
<?php PageContext::renderPostAction($this->messageFunction);?>
Fill in the form below to update your credit card details:<br/><br/>
<form id="frmCreditCard" name="frmCreditCard" method="post" action="" onsubmit="">

<div class="div-60">
    
    Edit Billing Address
    
  <div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vFirstName']?$_POST['vFirstName']:PageContext::$response->billing['first_name'];?>" name="vFirstName" id="vFirstName" class="form-control credit_add" placeholder="First Name*"  >
      <label class="error" id="first_name_field_error"></label>
</div>
  <div class="profile-text-filed">
    <input type="text" value="<?php echo $_POST['vLastName']?$_POST['vLastName']:PageContext::$response->billing['last_name'];?>" name="vLastName" id="vLastName" class="form-control credit_add" placeholder="Last Name*" >
      <label class="error" id="last_name_field_error"></label>
  </div>

 <div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vEmail']?$_POST['vEmail']:PageContext::$response->billing['email'];?>" name="vEmail" id="vEmail" placeholder="Email*" class="form-control credit_add">
      <label class="error" id="email_field_error"></label>
 </div>
<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vPhone']?$_POST['vPhone']:PageContext::$response->billing['phone'];?>" name="vPhone" id="vPhone" placeholder="Phone*" class="form-control credit_add">
      <label class="error" id="phone_field_error"></label>
 </div>
<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vFax']?$_POST['vFax']:PageContext::$response->billing['fax'];?>" name="vFax" id="vFax" placeholder="Fax Number" class="form-control credit_add">
      <label class="error" id="fax_field_error"></label>
 </div>
<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vAddress']?$_POST['vAddress']:PageContext::$response->billing['address_line_1'];?>" name="vAddress" id="vAddress" class="form-control credit_add" placeholder="Address*" >
      <label class="error" id="address_field_error"></label>
</div>

<div class="profile-text-filed">
   <?php 
          if(trim(PageContext::$response->billing['country']) == "US"){
              PageContext::$response->billing['country'] = "United States";
          }
          ?>
          <select class="form-control" id="vCountry" name="vCountry">
          <option value="">Select Country*</option>
          <?php global $countries;
          foreach($countries as $countryCode=>$country){
               if(PageContext::$response->billing['country'] == $country)
                   $selected = 'selected';
               else
                   $selected = '';
              ?>
          <option value="<?php echo ($country=='United States')?"US":$country;?>" <?php echo $selected;?>><?php echo $country;?></option>
          <?php }?>
          <!--<option value="undefined">undefined</option>-->

        </select>
          <label class="error" id="country_field_error"></label>
</div>

<div class="profile-text-filed">
    <input type="text"  value="<?php echo $_POST['vState']?$_POST['vState']:PageContext::$response->billing['state'];?>" name="vState" id="vState" class="form-control credit_add" placeholder="State*" >
        <label class="error" id="state_field_error"></label>
</div>

<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vCity']?$_POST['vCity']:PageContext::$response->billing['city'];?>" name="vCity" id="vCity" class="form-control credit_add"  placeholder="City*" >
      <label class="error" id="city_field_error"></label>
</div>

<div class="profile-text-filed">
  <input type="text" value="<?php echo $_POST['vZipcode']?$_POST['vZipcode']:PageContext::$response->billing['postal_code'];?>" name="vZipcode" id="vZipcode" class="form-control credit_add" placeholder="Postal Code*">
      <label class="error" id="zip_field_error"></label>
</div>



<div class="card-detail-outer">






<div class="profile-text-filed">
    Edit Card Details
    <input type="text" value="<?php echo PageContext::$response->card['masked_card'];?>" name="vNumber" id="vNumber" class="form-control credit_cc" placeholder="Card Number*"  minlength="16" maxlength="16"  >
          <label class="error" id="number_field_error"></label>
</div>

<div class="profile-text-filed">
  <input type="password" value="" name="vCode" id="vCode" class="form-control credit_cc" placeholder="CVV/CVV2 No.*" minlength="3" maxlength="4" size="4"  >
      <label class="error" id="code_field_error"></label>
  </div>

<div class="profile-text-filed">
  <div class="full-width la-height">
  Expiry Date(MM/YYYY)*
</div>
<div class="div-45">
    <?php 
    //echo PageContext::$response->card['expiration_date'];
$expm = explode('/',PageContext::$response->card['expiration_date']);
 // print_r($expm); 
    
    ?>
<select name="vMonth" id="vMonth" class="form-control">

<?php 
    
        
for($i=1; $i<=12; $i++) {
if($expm[0]==$i)
$selected = 'selected';
else
$selected = '';
?>


<option value="<?php if($i<10){echo '0'.$i; }else {echo $i;};?>" <?php echo $selected;?>>
<?php if($i<10){echo '0'.$i; }else {echo $i;}?>
</option>
<?php                         } ?>

</select>
</div>


<div class="div-45 pull-right">
<select name="vYear" id="vYear" class="form-control">

<?php for($i=date('Y'); $i<=(date('Y')+50); $i++) { 
if($expm[1]==substr($i, -2))
$selected = 'selected';
else
$selected = '';?>

<option value="<?php echo substr($i, -2);?>" <?php echo $selected;?>>
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
  
          <input type="submit" class="more-btn" name="btnProfile" value="Save Changes">
         
</div>

</div>

</form>
</div>


		
		
		
      </div>
     
    </div>
  </div>
 
<script type="text/javascript">
    
    
    $(document).ready(function(){
        
       
    });
    
    
   function checkCvv(){
      
      var err = 0;
      
       var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
      
      if($("#edit_add").is(":checked")) {
          
          if($("#vFirstName").val() == '')
          {
              $("#first_name_field_error").show();
              $("#first_name_field_error").html("First Name is required!");
              err = 1;  
          }
          if($("#vLastName").val() == '')
          {
              $("#last_name_field_error").show();
              $("#last_name_field_error").html("Last Name is required!");
              err = 1;  
          }
          if(reg.test($("#vEmail").val()) == false)
          {
              $("#email_field_error").show();
              $("#email_field_error").html("Please Enter a proper email address!");
              err = 1;  
          }
          if($("#vAddress").val() == '')
          {
              $("#address_field_error").show();
              $("#address_field_error").html("Address is  required!");
              err = 1;  
          }
          if($("#vCountry").val() == '')
          {
              $("#country_field_error").show();
              $("#country_field_error").html("Country is  required!");
              err = 1;  
          }
          if($("#vCity").val() == '')
          {
              $("#city_field_error").show();
              $("#city_field_error").html("City is  required!");
              err = 1;  
          }
          if($("#vState").val() == '')
          {
              $("#state_field_error").show();
              $("#state_field_error").html("State is  required!");
              err = 1;  
          }
          if($("#vPhone").val() == '')
          {
              $("#phone_field_error").show();
              $("#phone_field_error").html("Phone number is  required!");
              err = 1;  
          }
          if($("#vZipcode").val() == '')
          {
              $("#zip_field_error").show();
              $("#zip_field_error").html("Zipcode is  required!");
              err = 1;  
          }
      }
      
      if($("#edit_cc").is(":checked")) {
          var ccnumber = $("#vNumber").val();
          if(ccnumber.length != 16 || isNaN(ccnumber))
          {
              //alert("Here");
              $("#number_field_error").show();
              $("#number_field_error").html("Please enter a valid credit card number!");
              err = 1;  
          }
          
          var ccvnumber = $("#vCode").val();
          if(isNaN(ccvnumber))
          {
             //  alert("Here2");
              $("#code_field_error").show();
              $("#code_field_error").html("Please enter a valid CVV/CVV2!");
              err = 1;  
          }
          
      }
      //alert(err);
      if(err==1)
      {
          return false;
      }else{
          return true;
      }
      
      
      
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
			





