<form action="<?php echo htmlentities($_SESSION["sess_ubackreplyurl"]) ?>" method=post>
<?php
 //session_unregister("sess_ubackreplyurl");
 $_SESSION["sess_ubackreplyurl"]="";
?>
<table width="100%"  border="0" cellspacing="10" cellpadding="0">
            <tr>
              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td><img src="./images/spacerr.gif" width="1" height="1"></td>
                  </tr>
                </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1"  ><img src="./images/spacerr.gif" width="1" height="1"></td>
                      <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="93%" class="heading"><?php echo TEXT_REPLIES?></td>
                          </tr>
                        </table>
                          <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="column1">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                  <tr>
                                    <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        
									    <tr>
                                          <td class="bodycolor" ><table width="100%" border="0" cellpadding="0" cellspacing="3"  >
                                              
                                              <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
                                              <tr>
											    <td align=center class="listing"><b><?php echo $var_message; ?></b></td>
											  </tr>
											  <tr>
											    <td align=center class="listing"><b><?php echo $var_message1; ?></b></td>
											  </tr>
                                              <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>

                                          </table></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>

                  </table>

                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table></td>
            </tr>
          </table>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr align="center"  class="listingbtnbar">
            
            <td width="100%" align=center><input name="btAdd" type="submit" class="button" value="<?php echo BUTTON_TEXT_BACK; ?>" ></td>

         </td></tr></table>
</form>				  

               
