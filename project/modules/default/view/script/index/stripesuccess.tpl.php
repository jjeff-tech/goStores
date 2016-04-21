
<?php if(PageContext::$response->content !="") {

echo PageContext::$response->content;


}   else { ?>
<!-- installation completed----------------------------------------------------------------------- -->
<div id="jqMessage" style="display: block;"><div class="storecration_instalation_wrapper" style="">
                                                                        <div class="storecration_instalation_wrapper_inner">
                                                                            <div class="instalation_completed_img "></div>

                                                                             <div class="pymnt_sucessmsgs" style="text-align:center;"><div class="store_success">
            <div class="store_success_label"></div>
            <?php if(PageContext::$response->upgrade==0) {  ?>
                    <h2>Congratulations!</h2><h3>Your installation was successful!</h3>
                <?php } else { ?>
                    <h2>Congratulations!</h2><h3>The Upgrade Process was completed successfully!</h3>
                <?php } ?>
                    <p class="head">Site Login Details</p><table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                    <td align="left" valign="top" width="20%">Admin URL </td>
                    <td align="left" valign="top">:&nbsp;<a href="http://www.<?php echo PageContext::$response->subdom ?>.<?php echo PageContext::$response->domainName ?>/admins/"  target="_blank">http://www.<?php echo PageContext::$response->subdom ?>.<?php echo PageContext::$response->domainName ?>/admins/</a></td>
                    </tr>
                    <tr>
                    <td align="left" valign="top">Home URL </td>
                    <td align="left" valign="top">:&nbsp;<a href="http://www.<?php echo PageContext::$response->subdom ?>.<?php echo PageContext::$response->domainName ?>/index.php"  target="_blank">http://www.<?php echo PageContext::$response->subdom ?>.<?php echo PageContext::$response->domainName ?>/</a></td>
                    </tr>
                    </table>
                    <p class="head">Admin Credentials</p>
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                    <td align="left" valign="top" width="20%">Username</td>
                    <td align="left" valign="top">:&nbsp;admin</td>
                    </tr>
                    <tr>
                    <td align="left" valign="top" >Password</td>
                    <td align="left" valign="top">:&nbsp;admin</td>
                    </tr>
                    </table>

            </div></div>
                                                                        </div>
                                                                        <div class="clear"></div>
                                                                   </div></div>
<div class="storecration_instalation_wrapper" style="display:none">
<h3>Your Installation Successfully Completed</h3>
<div class="storecration_instalation_wrapper_inner">
<div class="instalation_completed_img "></div>
<h4>Congratulations!!!</h4>
</div>

</div> </div> </div>            
           
           
           
     <?php } ?>  
     
    
