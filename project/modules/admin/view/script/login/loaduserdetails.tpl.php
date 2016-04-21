<div class="modal" id="userFullDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  id="jqCloseLinkUserDetails">×</button>
        <h4 id="myModalLabel" style="text-align: left;">User Details : <?php echo ucwords($this->userDetails->vFirstName.' '.$this->userDetails->vLastName);  ?>  </h4>
    </div>
    <div class="modal-body">

        <table class="table  table-bordered table-hover table-condensed">
            <tbody>

                <?php if($this->userDetails->vFirstName) {?>
                <tr><td class="span3">First Name&nbsp;</td>
                    <td class="span6">

                            <?php echo ucwords($this->userDetails->vFirstName); ?> </td>
                </tr>
                    <?php } ?>
                <?php if($this->userDetails->vLastName) {?>

                <tr><td class="span3">Last Name&nbsp;</td>
                    <td class="span6">

                            <?php echo ucwords($this->userDetails->vLastName); ?>               </td>
                </tr>
                    <?php } ?>
                <?php if($this->userDetails->vEmail) {?>

                <tr><td class="span3">Email&nbsp;</td>
                    <td class="span6">

                            <?php echo $this->userDetails->vEmail  ?>                    </td>
                </tr>
                    <?php } ?>
                <!--    <?php if($this->userDetails->vInvoiceEmail) {?>
                    <tr><td class="span3">Invoice Email&nbsp;</td>
                        <td class="span6">

                    <?php echo $this->userDetails->vInvoiceEmail  ?>                    </td>
                    </tr>
                    <?php } ?>
                <?php if($this->userDetails->vAddress) {?>
                    <tr><td class="span3">Address&nbsp;</td>
                        <td class="span6">

                    <?php echo $this->userDetails->vAddress  ?>                    </td>
                    </tr>
                    <?php } ?>
                <?php if($this->userDetails->vCountry) {?>
                    <tr><td class="span3">Country&nbsp;</td>
                        <td class="span6">

                    <?php echo $this->userDetails->vCountry 	  ?>                    </td>
                    </tr>
                    <?php } ?>
                <?php if($this->userDetails->vState) {?>
                    <tr><td class="span3">State&nbsp;</td>
                        <td class="span6">

                    <?php echo $this->userDetails->vState  ?>                    </td>
                    </tr>
                    <?php } ?>
                <?php if($this->userDetails->vCity) {?>
                    <tr><td class="span3">City&nbsp;</td>
                        <td class="span6">

                    <?php echo $this->userDetails->vCity  ?>                    </td>
                    </tr>
                    <?php } ?>
                <?php if($this->userDetails->vZipcode) {?>
                    <tr><td class="span3">Zip&nbsp;</td>
                        <td class="span6">

                    <?php echo $this->userDetails->vZipcode 	  ?>                    </td>
                    </tr>
                    <?php } ?>
                <?php if($this->userDetails->vPhoneNumber) {?>
                     <tr><td class="span3">Phone&nbsp;</td>
                        <td class="span6">

                    <?php echo $this->userDetails->vPhoneNumber 	  ?>                    </td>
                    </tr>
                    <?php } ?>-->

            </tbody>
        </table>

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true"  id="jqCloseUserDetails">Close</button>

    </div>

</div>

<!--<table class="table  table-bordered table-hover table-condensed">


                <tbody><tr><td class="span3">&nbsp;</td>
                    <td class="span6">

                                    1                    </td>
                </tr>


                <tr><td class="span3">Product&nbsp;</td>
                    <td class="span6">

                                    tt.cloud.iscripts.com                    </td>
                </tr>


                <tr><td class="span3">User&nbsp;</td>
                    <td class="span6">

                                    <a id="userDetails" href="javascript:void(0)">Krishna</a>                    </td>
                </tr>


                <tr><td class="span3">Plan Expiry Date&nbsp;</td>
                    <td class="span6">

                                    11/30/1999                    </td>
                </tr>

                                    </tbody></table>-->