
<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>    		                      |
// |          									                          |
// +----------------------------------------------------------------------+
	$addOredit = 'Add Company';
	if ($_GET["id"] != "") {
		$var_id = $_GET["id"];
		$addOredit = 'Edit Company';
	}
	elseif ($_POST["id"] != "") {
		$var_id = $_POST["id"];
		$addOredit = 'Edit Company';
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
	//$var_country = "United States";
	//$var_userid = $_SESSION["sess_staffid"];
	$var_staffid = $_SESSION["sess_staffid"];
	
	if ($_POST["postback"] == "" && $var_id != "") {
		
		$sql = "Select nCompId,vCompName,vCompAddress1,vCompAddress2,vCompCity,vCompState,nCompZip,vCompCountry,vCompPhone,";
		$sql .= "vCompFax,vCompMail,vCompContact from sptbl_companies where nCompId = '" . addslashes($var_id) . "' AND vDelStatus='0' ";
		$var_result = executeSelect($sql,$conn); 
		if (mysql_num_rows($var_result) > 0) {
			$var_row = mysql_fetch_array($var_result);
			
			$var_companyName =  trim($var_row["vCompName"]);
			$var_address1 =  trim($var_row["vCompAddress1"]);
			$var_address2 =  trim($var_row["vCompAddress2"]);
			$var_city =  trim($var_row["vCompCity"]);
			$var_state =  trim($var_row["vCompState"]);
			$var_phone =  trim($var_row["vCompPhone"]);
			$var_fax =  trim($var_row["vCompFax"]);
			$var_email =  trim($var_row["vCompMail"]);
			$var_zip =  trim($var_row["nCompZip"]);
			$var_contact =  trim($var_row["vCompContact"]);
			$var_country =  trim($var_row["vCompCountry"]);
		}
		else {
			$var_id="";	
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
		mysql_free_result($var_result);
	}
	elseif ($_POST["postback"] == "A") {
			$var_companyName = trim($_POST["txtCompanyName"]);
			$var_address1 = trim($_POST["txtAddress1"]);
			$var_address2 = trim($_POST["txtAddress2"]);
			$var_city = trim($_POST["txtCity"]);
			$var_state = trim($_POST["txtState"]);
			$var_phone = trim($_POST["txtPhone"]);
			$var_fax = trim($_POST["txtFax"]);
			$var_email = trim($_POST["txtEmail"]);
			$var_zip = trim($_POST["txtZip"]);
			$var_contact = trim($_POST["txtContact"]);
			$var_country = trim($_POST["cmbCountry"]);
			$var_message="";
		if (validateAddition($var_email,$var_message) == true) {
			//Insert into the company table
			$sql = "Insert into sptbl_companies(nCompId,vCompName,vCompAddress1,vCompAddress2,vCompCity,vCompState,nCompZip,vCompCountry,vCompPhone,";
			$sql .= "vCompFax,vCompMail,vCompContact) Values('','" . addslashes($var_companyName) . "',
					'" . addslashes($var_address1). "','" . addslashes($var_address2) . "','" . addslashes($var_city) . "',
					'" . addslashes($var_state) . "','" . addslashes($var_zip) . "','" . addslashes($var_country) . "',
					'" . addslashes($var_phone) . "','" . addslashes($var_fax) . "','" . addslashes($var_email) . "',
					'" . addslashes($var_contact) . "')";
			executeQuery($sql,$conn);
			 
			 $var_insert_id = mysql_insert_id($conn);
			
			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_ADDITION . "','Company','$var_insert_id',now())";			
				executeQuery($sql,$conn);
			}
			$var_message        = MESSAGE_RECORD_ADDED;
                        $flag_msg           = 'class="msg_success"';
                        $var_companyName    = "";
                        $var_address1       = "";
                        $var_address2       = "";
                        $var_city           = "";
                        $var_state          = "";
                        $var_phone          = "";
                        $var_fax            = "";
                        $var_email          = "";
                        $var_zip            = "";
                        $var_contact        = "";
                        $var_country        = "";
                        $var_id             = "";
		}
/*		else {
			$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
		}*/
	}
	elseif ($_POST["postback"] == "D") {
			$var_companyName = trim($_POST["txtCompanyName"]);
			$var_address1 = trim($_POST["txtAddress1"]);
			$var_address2 = trim($_POST["txtAddress2"]);
			$var_city = trim($_POST["txtCity"]);
			$var_state =  trim($_POST["txtState"]);
			$var_phone =  trim($_POST["txtPhone"]);
			$var_fax =  trim($_POST["txtFax"]);
			$var_email =  trim($_POST["txtEmail"]);
			$var_zip =  trim($_POST["txtZip"]);
			$var_contact =  trim($_POST["txtContact"]);
			$var_country =  trim($_POST["cmbCountry"]);
		if (validateDeletion($var_id) == true) {
			$sql = "Update sptbl_companies set vDelStatus = '1' where nCompId='" . addslashes($var_id) . "'";
			executeQuery($sql,$conn);
			
			//Insert the actionlog
			if(logActivity()) {
				$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Company','" . addslashes($var_id) . "',now())";			
				executeQuery($sql,$conn);
			}
				$var_companyName = "";
				$var_address1 = "";
				$var_address2 = "";
				$var_city = "";
				$var_state = "";
				$var_phone = "";
				$var_fax = "";
				$var_email = "";
				$var_zip = "";
				$var_contact = "";
				$var_country = "";
				$var_id="";
			$var_message = MESSAGE_RECORD_DELETED;
                        $flag_msg    = 'class="msg_success"';
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
		}
	}
	elseif ($_POST["postback"] == "U") {
			$var_companyName = trim($_POST["txtCompanyName"]);
			$var_address1 = trim($_POST["txtAddress1"]);
			$var_address2 = trim($_POST["txtAddress2"]);
			$var_city = trim($_POST["txtCity"]);
			$var_state = trim($_POST["txtState"]);
			$var_phone = trim($_POST["txtPhone"]);
			$var_fax = trim($_POST["txtFax"]);
			$var_email = trim($_POST["txtEmail"]);
			$var_zip = trim($_POST["txtZip"]);
			$var_contact = trim($_POST["txtContact"]);
			$var_country = trim($_POST["cmbCountry"]);
			$var_message = "";
			if (validateUpdation($var_email,$var_message) == true) {
				$sql = "Update sptbl_companies set vCompName='" . addslashes($var_companyName) . "',
					vCompAddress1='" . addslashes($var_address1) . "',
					vCompAddress2='" . addslashes($var_address2). "',
					vCompCity='" . addslashes($var_city) . "',
					vCompState='" . addslashes($var_state) . "',
					nCompZip='" . addslashes($var_zip) . "',
					vCompCountry='" . addslashes($var_country) . "',
					vCompPhone='" . addslashes($var_phone). "',
				   vCompFax='" . addslashes($var_fax) . "',
				   vCompMail='" . addslashes($var_email) . "',
				   vCompContact='" . addslashes($var_contact) . "' where nCompId='" . addslashes($var_id) . "'";
				executeQuery($sql,$conn);
				
			//Insert the actionlog
			if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_UPDATION . "','Company','" . addslashes($var_id) . "',now())";			
			executeQuery($sql,$conn);
			}	
				$var_message = MESSAGE_RECORD_UPDATED;
                                $flag_msg    = 'class="msg_success"';
			}
/*			else {
				$var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
			}*/
	}
	
	function validateAddition($var_email,&$var_message) 
	{
		global $conn,$flag_msg,$var_message;
		
		if (trim($_POST["txtCompanyName"]) == "" || trim($_POST["txtAddress1"]) == "" || trim($_POST["txtCity"]) == "" || trim($_POST["txtEmail"]) == "" || preg_match('/[><]/',trim($_POST["txtCompanyName"])) > 0) {
			$var_message = MESSAGE_RECORD_ERROR;
                        $flag_msg    = 'class="msg_error"';
			return false;
		}
		else {
			$sql = "Select nCompId from sptbl_companies where vCompName='" . addslashes(trim($_POST["txtCompanyName"])) . "'";
			if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
				$var_message = TEXT_COMPANY_DUPLICATE;
                                $flag_msg    = 'class="msg_error"';
				return false;
			}
			elseif(!isUniqueEmail($var_email)) {
				$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                                $flag_msg    = 'class="msg_error"';
				return false;
			}
			else {
				return true;
			}	
		}
	}
	
	function validateDeletion($var_list) 
	{
		//implement logic here
		global $conn;
		$sql = "Select nCompId from sptbl_depts where nCompId IN($var_list)";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			return false;
		}
		else {
			return true;
		}
	}
	
	function validateUpdation($var_email,&$var_message) 
	{
		global $conn,$var_id,$flag_msg;
		//implement logic here
		$sql = "Select nCompId from sptbl_companies where nCompId='" . addslashes($var_id) .  "' AND vDelStatus='0'";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			if (trim($_POST["txtCompanyName"]) == "" || trim($_POST["txtAddress1"]) == "" || trim($_POST["txtCity"]) == "" || trim($_POST["txtEmail"]) == "" || preg_match('/[><]/',trim($_POST["txtCompanyName"])) > 0) {
				$var_message = MESSAGE_RECORD_ERROR ;
                                $flag_msg    = 'class="msg_error"';
				return false;
			}
		}
		else {
			$var_message = MESSAGE_RECORD_ERROR ;
                        $flag_msg    = 'class="msg_error"';
			return false;
		}
		$sql = "Select nCompId from sptbl_companies Where vCompName='" . addslashes(trim($_POST["txtCompanyName"])) . "' AND nCompId !='" . addslashes($var_id) . "' ";
		if (mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			$var_message =  TEXT_COMPANY_DUPLICATE ;
                        $flag_msg    = 'class="msg_error"';
			return false;
		}
		if(!isUniqueEmail($var_email,$var_id,"c")) {
			$var_message = MESSAGE_NONUNIQUE_EMAIL ;
                        $flag_msg    = 'class="msg_error"';
			return false;
		}
		return true;
	}

   
?>


<?php

if($var_country!='')
    {
        $txtState = $var_country;
    }
    else
        {
        $txtState = 'United States';
    }
?>
<script>
    $(document).ready(function() {
  // Handler for .ready() called.
    $("#cmbCountry").val('<?php echo $txtState;?>');
});
 </script>
<form name="frmCompany" method="POST" action="<?php echo($_SERVER['PHP_SELF']); ?>">
	<div class="content_section">
			<div class="content_section_title">
				<h3><?php echo $addOredit ?></h3>
			</div>
			<table width="100%"  border="0">
			  <tr>
				<td width="76%" valign="top">
						<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="column1">
				 <tr>
				 <td>
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
					 <td align="center" colspan=3>
                                              <?php 
                                               if ($var_message != ""){?>
					 	<div <?php echo $flag_msg; ?>>
						 <?php echo $var_message ?>
						</div>
                                             <?php
                                               }?>
						 </td>
			
					 </tr>
			
					<tr><td colspan="3">&nbsp;</td></tr>
					 <tr>
					 <td width="2%" align="left">&nbsp;</td>
					 <td width="38%" align="left" class="toplinks"><?php echo TEXT_COMPANY_NAME?> <font style="color:#FF0000; font-size:9px">*</font> </td>
					 <td width="60%" align="left">
					 <input name="txtCompanyName" type="text" class="comm_input input_width1" id="txtCompanyName" size="30" maxlength="100" value="<?php echo htmlentities($var_companyName); ?>">
					 </td>
								  </tr>
								  <tr><td colspan="3">&nbsp;</td></tr>
								  <tr>
								  <td align="left">&nbsp;</td>
								  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_ADDRESS_1 ?> <font style="color:#FF0000; font-size:9px">*</font></td>
								  <td width="60%" align="left">
									<input name="txtAddress1" type="text" class="comm_input input_width1" id="txtAddress1" size="30" maxlength="100" value="<?php echo htmlentities($var_address1); ?>">
			</td>
								  </tr>
								  <tr><td colspan="3">&nbsp;</td></tr>
								  <tr>
								  <td align="left">&nbsp;</td>
								  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_ADDRESS_2 ?></td>
								  <td width="60%" align="left">
								  <input name="txtAddress2" type="text" class="comm_input input_width1" id="txtAddress2" size="30" maxlength="100" value="<?php echo htmlentities($var_address2); ?>">
								  </td>
								  </tr>
			
								  <tr><td colspan="3">&nbsp;</td></tr>
								  <tr>
								  <td align="left">&nbsp;</td>
								  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_CITY?> <font style="color:#FF0000; font-size:9px">*</font></td>
								  <td width="60%" align="left">
								  <input name="txtCity" type="text" class="comm_input input_width1" id="txtCity" size="30" maxlength="100" value="<?php echo htmlentities($var_city); ?>">
								  </td>
								  </tr>
			
								  <tr>
								  <td colspan="3">&nbsp;</td></tr>
								  <tr>
								  <td align="left">&nbsp;</td>
								  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_STATE?></td>
								  <td width="60%" align="left">
								  <input name="txtState" type="text" class="comm_input input_width1" id="txtState" size="30" maxlength="100" value="<?php echo htmlentities($var_state); ?>">
								  </td>
								  </tr>
								  <tr><td colspan="3">&nbsp;</td></tr>
								  
											<tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_COUNTRY?></td>
											  <td width="54%" align="left">
												  <select name="cmbCountry" size="1" class="comm_input input_width1a" id="cmbCountry">
													  <option>Afghanistan</option>
															<option>Albania</option>
															<option>Algeria</option>
															<option>Andorra</option>
															<option>Angola</option>
															<option>Antigua&nbsp; and&nbsp; Barbuda</option>
															  <option>Argentina</option>
															  <option>Armenia</option>
															  <option>Australia</option>
															  <option>Austria</option>
															  <option>Azerbaijan</option>
															  <option>Bahamas</option>
															  <option>Bahrain</option>
															  <option>Bangladesh</option>
															  <option>Barbados</option>
															  <option>Belarus</option>
															  <option>Belgium</option>
															  <option>Belize</option>
															  <option>Benin</option>
															  <option>Bhutan</option>
															  <option>Bolivia</option>
															  <option>Bosnia &amp; Herzegovina </option>
															  <option>Botswana</option>
															  <option>Brazil</option>
															  <option>Brunei</option>
															  <option>Bulgaria</option>
															  <option>Burkina Faso</option>
															  <option>Burundi</option>
															  <option>Cambodia</option>
															  <option>Cameroon</option>
															  <option>Canada</option>
															  <option>Cape Verde</option>
															  <option>Cent African Rep</option>
															  <option>Chad</option>
															  <option>Chile</option>
															  <option>China</option>
															  <option>Colombia</option>
															  <option>Comoros</option>
															  <option>Congo</option>
															  <option>Costa Rica</option>
															  <option>Croatia</option>
															  <option>Cuba</option>
															  <option>Cyprus</option>
															  <option>Czech Republic</option>
															  <option>C&ocirc;te d'Ivoire</option>
															  <option>Denmark</option>
															  <option>Djibouti</option>
															  <option>Dominica</option>
															  <option>Dominican Republic</option>
															  <option>East Timor</option>
															  <option>Ecuador</option>
															  <option>Egypt</option>
															  <option>El Salvador</option>
															  <option>Equatorial Guinea</option>
															  <option>Eritrea</option>
															  <option>Estonia</option>
															  <option>Ethiopia</option>
															  <option>Fiji</option>
															  <option>Finland</option>
															  <option>France</option>
															  <option>Gabon</option>
															  <option>Gambia</option>
															  <option>Georgia</option>
															  <option>Germany</option>
															  <option>Ghana</option>
															  <option>Greece</option>
															  <option>Grenada</option>
															  <option>Guatemala</option>
															  <option>Guinea</option>
															  <option>Guinea-Bissau</option>
															  <option>Guyana</option>
															  <option>Haiti</option>
															  <option>Honduras</option>
															  <option>Hungary</option>
															  <option>Iceland</option>
															  <option>India</option>
															  <option>Indonesia</option>
															  <option>Iran</option>
															  <option>Iraq</option>
															  <option>Ireland</option>
															  <option>Israel</option>
															  <option>Italy</option>
															  <option>Jamaica</option>
															  <option>Japan</option>
															  <option>Jordan</option>
															  <option>Kazakhstan</option>
															  <option>Kenya</option>
															  <option>Kiribati</option>
															  <option>Korea, North</option>
															  <option>Korea, South</option>
															  <option>Kuwait</option>
															  <option>Kyrgyzstan</option>
															  <option>Laos</option>
															  <option>Latvia</option>
															  <option>Lebanon</option>
															  <option>Lesotho</option>
															  <option>Liberia</option>
															  <option>Libya</option>
															  <option>Liechtenstein</option>
															  <option>Lithuania</option>
															  <option>Luxembourg</option>
															  <option>Macedonia</option>
															  <option>Madagascar</option>
															  <option>Malawi</option>
															  <option>Malaysia</option>
															  <option>Maldives</option>
															  <option>Mali</option>
															  <option>Malta</option>
															  <option>Marshall Islands</option>
															  <option>Mauritania</option>
															  <option>Mauritius</option>
															  <option>Mexico</option>
															  <option>Micronesia</option>
															  <option>Moldova</option>
															  <option>Monaco</option>
															  <option>Mongolia</option>
															  <option>Morocco</option>
															  <option>Mozambique</option>
															  <option>Myanmar</option>
															  <option>Namibia</option>
															  <option>Nauru</option>
															  <option>Nepal</option>
															  <option>Netherlands</option>
															  <option>New Zealand</option>
															  <option>Nicaragua</option>
															  <option>Niger</option>
															  <option>Nigeria</option>
															  <option>Norway</option>
															  <option>Oman</option>
															  <option>Pakistan</option>
															  <option>Palau</option>
															  <option>Panama</option>
															  <option>Papua New Guinea</option>
															  <option>Paraguay</option>
															  <option>Peru</option>
															  <option>Philippines</option>
															  <option>Poland</option>
															  <option>Portugal</option>
															  <option>Qatar</option>
															  <option>Romania</option>
															  <option>Russia</option>
															  <option>Rwanda</option>
															  <option>Saint Kitts</option>
															  <option>Saint Lucia</option>
															  <option>Saint Vincent</option>
															  <option>Samoa</option>
															  <option>San Marino</option>
															  <option>Sao Tome</option>
															  <option>Saudi Arabia</option>
															  <option>Senegal</option>
															  <option>Seychelles</option>
															  <option>Sierra Leone</option>
															  <option>Singapore</option>
															  <option>Slovakia</option>
															  <option>Slovenia</option>
															  <option>Solomon Islands</option>
															  <option>Somalia</option>
															  <option>South Africa</option>
															  <option>Spain</option>
															  <option>Sri Lanka</option>
															  <option>Sudan</option>
															  <option>Suriname</option>
															  <option>Swaziland</option>
															  <option>Sweden</option>
															  <option>Switzerland</option>
															  <option>Syria</option>
															  <option>Taiwan</option>
															  <option>Tajikistan</option>
															  <option>Tanzania</option>
															  <option>Thailand</option>
															  <option>Togo</option>
															  <option>Tonga</option>
															  <option>Trinidad and Tobago</option>
															  <option>Tunisia</option>
															  <option>Turkey</option>
															  <option>Turkmenistan</option>
															  <option>Tuvalu</option>
															  <option>Uganda</option>
															  <option>Ukraine</option>
															  <option>United Arab Emirates</option>
															  <option>United Kingdom</option>
                                                                                                                          <option selected>United States</option>
															  <option>Uruguay</option>
															  <option>Uzbekistan</option>
															  <option>Vanuatu</option>
															  <option>Vatican City</option>
															  <option>Venezuela</option>
															  <option>Vietnam</option>
															  <option>Western Sahara</option>
															  <option>Yemen</option>
															  <option>Yugoslavia</option>
															  <option>Zambia</option>
															  <option>Zimbabwe</option>
												</select>
											  </td>
											</tr>
																			<tr><td colspan="3">&nbsp;</td></tr>
											<tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_ZIP?></td>
											  <td width="54%" align="left">
												  <input name="txtZip" type="text" class="comm_input input_width1" id="txtZip" size="30" maxlength="7" value="<?php echo($var_zip); ?>">
											  </td>
											</tr>
																			<tr><td colspan="3">&nbsp;</td></tr>
											<tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_PHONE?></td>
											  <td width="54%" align="left">
												  <input name="txtPhone" type="text" class="comm_input input_width1" id="txtPhone" size="30" maxlength="20" value="<?php echo htmlentities($var_phone); ?>">
											  </td>
											</tr>
																			<tr><td colspan="3">&nbsp;</td></tr>
											<tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_FAX?></td>
											  <td width="54%" align="left">
												  <input name="txtFax" type="text" class="comm_input input_width1" id="txtFax" size="30" maxlength="20" value="<?php echo htmlentities($var_fax); ?>">
											  </td>
											</tr>
																			<tr><td colspan="3">&nbsp;</td></tr>
											<tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_EMAIL?> <font style="color:#FF0000; font-size:9px">*</font></td>
											  <td width="54%" align="left">
												  <input name="txtEmail" type="text" class="comm_input input_width1" id="txtEmail" size="30" maxlength="100" value="<?php echo htmlentities($var_email); ?>">
											  </td>
											</tr>
																			<tr><td colspan="3">&nbsp;</td></tr>
											<tr>
											  <td align="left">&nbsp;</td>
											  <td align="left" class="toplinks"><?php echo TEXT_COMPANY_CONTACT_PERSON?></td>
											  <td width="54%" align="left">
												  <input name="txtContact" type="text" class="comm_input input_width1" id="txtContact" size="30" maxlength="100" value="<?php echo htmlentities($var_contact); ?>">
											  </td>
											</tr>
																																	
										  </table>
									</td>
										</tr>
									</table>
						<table width="100%"  border="0" cellspacing="10" cellpadding="0">
						  <tr>
							<td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
								<tr>
								  <td class="btm_brdr">&nbsp;</td>
								</tr>
							  </table>
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td >&nbsp;</td>
								</tr>
								  <tr >
									<td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
									<td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
										<tr>
										  <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
											  <tr align="center"  class="listingbtnbar">
												<td width="22%">&nbsp;</td>
                                                                                                <?php if($var_id==''){?>
                                                                                                    <td width="16%"><input name="btAdd" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_ADD ?>" onClick="javascript:add();"></td>
                                                                                                   <?php }
                                                                                                   if($var_id>0){?>
                                                                                                    <td width="14%"><input name="btUpdate" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_EDIT ?>"  onClick="javascript:edit();"></td>
                                                                                                    <?php
                                                                                                   }?>
												<td width="16%"><input name="btDelete" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_DELETE ?>" onClick="javascript:deleted();"></td>
												<td width="12%"><input name="btCancel" type="button" class="comm_btn" value="<?php echo BUTTON_TEXT_CANCEL ?>" onClick="javascript:cancel();"></td>
												<td width="20%">
												<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
												<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
												<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
												<input type="hidden" name="id" value="<?php echo($var_id); ?>">
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
<script>
	var setValue = "<?php echo trim($var_country); ?>";
	
//	document.frmCompany.cmbCountry.text=setValue;
	try{
 	for(i=0;i<document.frmCompany.cmbCountry.options.length;i++){
            if(document.frmCompany.cmbCountry.options[i].text == setValue){
                        //document.getElementById("cmbCountry").value = setValue;
						//document.frmCompany.cmbCountry.options[i].selected=true;
						break;
            }
   }
	}catch(e){}

	document.getElementById("cmbCountry").value = setValue;
	
	<?php
		if ($var_id == "") {
			echo("document.frmCompany.btAdd.disabled=false;");
			echo("document.frmCompany.btUpdate.disabled=true;");
			echo("document.frmCompany.btDelete.disabled=true;");
		}
		else {
			echo("document.frmCompany.btAdd.disabled=true;");
			echo("document.frmCompany.btUpdate.disabled=false;");
			echo("document.frmCompany.btDelete.disabled=false;");
		}
	?>
	document.frmCompany.txtCompanyName.focus();
</script>
</form>