<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                |
// |                                                                                       // |
// +----------------------------------------------------------------------+

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
        if ($_GET["id"] != "") {
                $var_id = $_GET["id"];
        }
        elseif ($_POST["id"] != "") {
                $var_id = $_POST["id"];
        }
        $var_country = "UnitedStates";
        //$var_userid = $_SESSION["sess_staffid"];
        $var_staffid = $_SESSION["sess_staffid"];

        if ($_POST["postback"] == "" && $var_id != "") {
                $sql = "Select * from sptbl_news ";
        $sql .=" where nNewsId='".mysql_real_escape_string($var_id)."'";
                $var_result = executeSelect($sql,$conn);
                if (mysql_num_rows($var_result) > 0) {
                        $var_row = mysql_fetch_array($var_result);
                        $var_title = $var_row["vTitle"];
                        $var_news= $var_row["tNews"];
                        $var_validdate = datetimefrommysql($var_row["dVaildDate"]);
                        $vtype = $var_row["vType"];

            }
                else {
                        $var_message = "<font color=red>" . MESSAGE_RECORD_ERROR . "</font>";
                }
        }





?>
<form name="frmNews" method="POST" action="<?php echo($_SERVER["REQUEST_URI"]); ?>">
<table width="100%"  border="0">
  <tr>
    <td width="76%" valign="top">
    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
    <tr>
    <td>
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" >
     <tr>
     <td><img src="./images/spacerr.gif" width="1" height="1"></td>
     </tr>

     </table>

     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
     <td class="pagecolor">
     <table width="100%"  border="0" cellspacing="0" cellpadding="5">
     <tr>
     <td width="93%" class="heading"><?php echo TEXT_VIEW_NEWS ?></td>
     </tr>
     </table>


     <table width="100%"  border="0" cellspacing="1" cellpadding="0" class="column1">

     <tr>
     <td>

         <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">

                           <tr><td colspan="3">&nbsp;</td></tr>
                      <tr>
                     <td width="13%" align="left">&nbsp;</td>
                     <td width="100%" align="center" class="toplinks" colspan=2><b>
                     <p align=justify>
                     <?php echo htmlentities($var_title); ?></p></b>
                                          </td>
                      </tr>

                                          <tr>
                     <td width="13%" align="left">&nbsp;</td>
                     <td width="100%" align="center" colspan=2 class="toplinks">
                      <p align=justify>
<?php echo htmlentities($var_news); ?>
                        </p>
                                         </td>
                      </tr>
                      <tr><td colspan="3">&nbsp;</td></tr>



                                          <tr>
                                           <td>

                                           </td>
                                          </tr>
                                          <tr><td colspan="3">&nbsp;</td></tr>
                                                                </table>
                        </td>
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
            <table width="100%"  border="0" cellspacing="10" cellpadding="0">
              <tr>
                <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                      <td><img src="./images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                  </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr >
                        <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                        <td ><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr align="center"  class="listingbtnbar">
<td width="100%" colspan=6>

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
                        <td width="1" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                    </table>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td ><img src="images/spacerr.gif" width="1" height="1"></td>
                      </tr>
                  </table></td>
              </tr>
            </table>
		</td>
  </tr>
</table>

</form>