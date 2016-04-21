<form action="<?php echo htmlentities($_SESSION["sess_backreplyurl"]) ?>" method=post>

<table width="100%"  border="0" cellspacing="10" cellpadding="0">
            <tr>
              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="dotedhoriznline">
                  <tr>
                    <td><img src="../images/spacerr.gif" width="1" height="1"></td>
                  </tr>
                </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1" class="vline" ><img src="../images/spacerr.gif" width="1" height="1"></td>
                      <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="1">
                          <tr>
                            <td width="93%" class="heading" align="left"><?php echo TEXT_REPLIES?></td>
                          </tr>
                        </table>
                          <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="listingmaintext">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" >
                                  <tr>
                                    <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">

									    <tr>
                                          <td class="whitebasic" ><table width="100%"  border="0" cellpadding="0" cellspacing="3"  >

                                              <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
											  <tr><td>&nbsp;</td></tr>
                                              <tr>
											    <td align=center><b><?php echo $var_message; ?></b></td>
											  </tr>
											  <tr>
											    <td align=center><b><?php echo $var_message1; ?></b></td>
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
                      <td width="1" class="vline"><img src="../images/spacerr.gif" width="1" height="1"></td>
                    </tr>

                  </table>

                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="dotedhoriznline"><img src="../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table></td>
            </tr>
          </table>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr align="center"  class="listingbtnbar">

            <td width="100%" align=center><input name="btAdd" type="submit" class="button" value="<?php echo BUTTON_TEXT_BACK; ?>" ></td>

         </td></tr></table>
</form>
<?php
 //session_unregister("sess_backreplyurl");
// $_SESSION["sess_backreplyurl"]="";
?>