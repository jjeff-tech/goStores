<div style="margin:85px 0 50px 0; border:1px solid #c6c6c6; padding:20px; background:#fefefe; ">
<table width="70%"  border="0" cellspacing="10" cellpadding="0" class="whitebasic" align="center">
	
            <tr>
              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td><img src="../images/spacerr.gif" width="1" height="1"></td>
                  </tr>
                </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" ><img src="../images/spacerr.gif" width="1" height="1"></td>
                      <td class="pagecolor">
                         <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="greentext">
                          <tr>
                            <td align="center" valign="middle">

                              <table width="100%"  border="0" cellspacing="0" cellpadding="4">
							<tr><td align="center" colspan="3" class="maintext"><font color="#FF0000"><?php echo $message;?></font></td></tr>
							<tr>
								<td colspan=3 class="maintext license_hd">
									<p align=center  ><b>Invalid License (<?php echo getLicense();?>).<br> Please contact support@iscripts.com</b><br></p> 
								</td>
							</tr>
							<tr>
								<td align="center" colspan="3" class="maintext license_p" bgcolor="#FFFFFF">Click <a href="javascript: enterNewKey();">here</a> to enter new key
								</td>
							</tr>						
							<tr id="adminpass" style="display:none;" bgcolor="#FFFFFF">
								<td width="43%" align="right" class="maintext license_p">Admin password</td>
								<td width="38%" align="left">
									<input name="txtAdminPass"  id="txtAdminPass" type="text" class="textbox" size="35" maxlength="40" value="<?php echo htmlentities($txtAdminPass);?>">
								</td>
								<td width="19%" align="left">&nbsp;</td>
							</tr>
							<tr id="licensekey" style="display:none;" bgcolor="#FFFFFF">
								<td width="43%" align="right" class="maintext license_p license_p">Enter new license key </td>
								<td width="38%" align="left">
									<input name="txtLicenseKey"  id="txtLicenseKey" type="text" class="textbox" size="35" maxlength="40" value="<?php echo htmlentities($txtLicenseKey);?>">
								</td>
								<td width="19%" align="left">
								</td>
							</tr>
							<tr id="licensekeysubmit" bgcolor="#FFFFFF" style="display:none;">
								<td width="43%" align="right" class="maintext license_p license_p"> </td>
								<td width="38%" align="left">
									<input type="submit" name="btnGo" value="Submit" class="button submit">
								</td>
								<td width="19%" align="left">
								</td>
							</tr>
                              <tr>
                                <td colspan="3" align="right" valign="top" class="blacktext">&nbsp;</td>
                              </tr>
                            </table>
                            </td>
                          </tr>
                      </table></td>
                      <td width="1" ><img src="../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table></td>
            </tr>
			
          </table>
</div>