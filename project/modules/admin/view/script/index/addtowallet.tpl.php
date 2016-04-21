<div class="form_container">
    <div class="form_top">Wallet</div>
    <div class="form_bgr">
        
    <?php if(!empty($this->message)) { ?>
    <div class="green_box"><?php //echo $this->message; ?></div>
    <?php } ?>
           <?php PageContext::renderPostAction('errormessage');?>
    <form id="frmWallet" action="<?php echo ConfigUrl::base(); ?>index/addtowallet" method="post" onsubmit="return validateWallet()">
            <input type="hidden" name="id" value="<?php echo $this->id ?>">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                <tr>
                    <th align="left" valign="top" width="20%">User Email: </th>
                    <td align="left" valign="top"><input id="mod_useremail" readonly="true" name="mod_useremail" type="text" class="inp-form" value="<?php echo $_POST['mod_useremail']?$_POST['mod_useremail']:$this->userEmail; ?>" /> </td>
                </tr>

                <tr>
                    <th align="left" valign="top">Outstanding Balance: </th>
                    <td align="left" valign="top"> <input id="mod_userbalance" name="mod_userbalance" type="text" class="inp-form" value="<?php echo $_POST['mod_userbalance']?$_POST['mod_userbalance']:stripslashes($this->userBalanceAmt); ?>" /> </td>
                </tr>
                <tr>
                    <th align="left" valign="top">Amount to Add: </th>
                    <td align="left" valign="top">
                        <input id="mod_amttoadd" name="mod_amttoadd" type="text" class="inp-form" validate="required:true" />
                    </td>                    
                </tr>
                <tr>
                    <th align="left" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/index/users">Cancel</a></div></th>
                    <td align="left" valign="top">
                     <input type="submit" name="btnAdd"  value="Save Changes"  /></td>
                </tr>
            </table>
    </form>
    </div>
    <div class="form_bottom"></div>
</div>
