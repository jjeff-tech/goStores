<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
?>
<div class="inner_page_title">
    <h4>Payment<span>&nbsp;-&nbsp;Select your payment options here.</span></h4>
</div>

<div class="inner_page_content">

    <!-- New HTML for payment options -->
    <div class="payment_option1">
        <div class="payment_option1_colleft">

            <!-- Domain Options starts -->
            <div class="payment_block">
                <div class="payment_block_title"><h5>Domain Name Type</h5></div>
                <div class="payment_block_content">
                    <ul>

                        <li>
                            <div>
                                <div class="formbtn"><input type="radio" class="jqOptionStyle" value="1" name="jqOptionStyle" checked></div>
                                <div class="information">I want to use the same subdomain</div>
                                <div class="clear"></div>
                            </div>
                        </li>


                        <li>
                            <div>
                                <div class="formbtn"><input type="radio" class="jqOptionStyle" value="2" name="jqOptionStyle"></div>
                                <div class="information">I want to use a new domain after upgrade</div>
                                <div class="clear"></div>
                            </div>
                        </li>

                        <li id="jqDomainEntryBox" style="display: none;">
                            <div>
                                <div class="formbtn">&nbsp;</div>
                                <div class="information">
                                    <span><b>Domain :
                                            http://www.</b></span>&nbsp;&nbsp;<input name="" type="text" class="width1" id="idsld1" name="sld1">
                                    <select class="domain_ext" id="tld1" name="tld1">
                                        <option>com</option>
                                        <option>info</option>
                                        <option>org</option>
                                        <option>biz</option>
                                    </select></b></span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Check Availability" id="jqCheckDomainExist">
                                </div>
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li>
                            <div>
                                <div class="formbtn"><input type="radio" class="jqOptionStyle" value="3" name="jqOptionStyle"></div>
                                <div class="information">I want to keep my domain name with my current registrar, but use it for my store</div>
                                <div class="clear"></div>
                            </div>
                        </li>

                        <li id="jqUserDomainEntryBox" style="display: none;">
                            <div>
                                <div class="formbtn">&nbsp;</div>
                                <div class="information">
                                    <span><b>Enter Domain :
                                            http://www.</b></span>&nbsp;&nbsp;<input name="" type="text" class="width1" id="idsld2" name="sld2">
                                    <select class="domain_ext" id="tld2" name="tld2">
                                        <option>com</option>
                                        <option>info</option>
                                        <option>org</option>
                                        <option>biz</option>
                                    </select></b></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li><div id="jqShowMessage"></div></li>



                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <!-- Domain Options ends -->


            <!-- Looing content for each Service -->
            <?php
            $prevVal="";
            $j=1;
            foreach($this->purchaseCategory as $purchaseCategory) {
                if($prevVal=="") {
                    ?>
            <div class="payment_block">
                <div class="payment_block_title"><h5><?php echo $purchaseCategory->vCategory; ?></h5></div>
                <div class="payment_block_content">
                    <ul>
                        <li>
                            <div>
                                        <?php echo $purchaseCategory->SEDESC; ?>
                            </div>
                        </li>
                        <li>
                            <div>
                                <div class="formbtn">
                                    <?php
                                    switch($purchaseCategory->vInputType){
                                        case 'C':
                                    ?>
                                          <input type="checkbox" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatVal" name="jqSerciceCatVal[]" Id="<?php echo $purchaseCategory->nServiceId; ?>" />
                                    <?php
                                            break;
                                        case 'R':
                                    ?>
                                          <input type="radio" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatValRd" name="serviceSubscription[<?php echo $j; ?>]" Id="serSub_<?php echo $purchaseCategory->nServiceId; ?>" />
                                    <?php
                                            break;
                                    } // End Switch
                                    ?>
                                    <!--<input type="checkbox" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatVal" name="jqSerciceCatVal[]" Id="<?php echo $purchaseCategory->nServiceId; ?>">--></div>
                                <div class="information"><?php echo $purchaseCategory->vServiceDescription; ?> - <span><b>$ <?php echo $purchaseCategory->price; ?></b></span></div>
                                <div class="clear"></div>
                            </div>
                        </li>
                                <?php
                            }
                            else if($purchaseCategory->SEDESC==$prevVal) {
                                ?>
                        <li>
                            <div>
                                <div class="formbtn">
                                    <?php
                                    switch($purchaseCategory->vInputType){
                                        case 'C':
                                    ?>
                                            <input type="checkbox" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatVal" name="jqSerciceCatVal[]" id="<?php echo $purchaseCategory->nServiceId; ?>" />
                                    <?php
                                            break;
                                        case 'R':
                                    ?>
                                            <input type="radio" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatValRd" name="serviceSubscription[<?php echo $j; ?>]" Id="serSub_<?php echo $purchaseCategory->nServiceId; ?>" />
                                    <?php
                                            break;
                                    }
                                    ?>
                                    <!--<input type="checkbox" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatVal" name="jqSerciceCatVal[]" id="<?php echo $purchaseCategory->nServiceId; ?>">--></div>
                                <div class="information"><?php echo $purchaseCategory->vServiceDescription; ?> - <span><b>$ <?php echo $purchaseCategory->price; ?></b></span></div>
                                <div class="clear"></div>
                            </div>
                        </li>
                                <?php
                            } else {
                                ++$j;
                                ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            <div class="payment_block">
                <div class="payment_block_title"><h5><?php echo $purchaseCategory->vCategory; ?></h5></div>
                <div class="payment_block_content">
                    <ul>
                        <li>
                            <div>
                                        <?php echo $purchaseCategory->SEDESC; ?>
                            </div>
                        </li>
                        <li>
                            <div>
                                <div class="formbtn">
                                    <?php
                                    switch($purchaseCategory->vInputType){
                                        case 'C':
                                    ?>
                                            <input type="checkbox" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatVal" name="jqSerciceCatVal[]" id="<?php echo $purchaseCategory->nServiceId; ?>" />
                                    <?php
                                            break;
                                        case 'R':
                                    ?>
                                            <input type="radio" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatValRd" name="serviceSubscription[<?php echo $j; ?>]" Id="serSub_<?php echo $purchaseCategory->nServiceId; ?>" />
                                    <?php
                                            break;
                                    }
                                    ?> 
                                    <!--<input type="checkbox" value="<?php echo $purchaseCategory->price; ?>" class="jqSerciceCatVal" name="jqSerciceCatVal[]" id="<?php echo $purchaseCategory->nServiceId; ?>">--></div>
                                <div class="information"><?php echo $purchaseCategory->vServiceDescription; ?> - <span><b>$ <?php echo $purchaseCategory->price; ?></b></span></div>
                                <div class="clear"></div>
                            </div>
                        </li>
                                <?php
                            }
                            $prevVal=$purchaseCategory->SEDESC;

                        }
                        ?>
                        <input type="hidden" id="radioCount" name="radioCount" value="<?php echo $j; ?>" />
                        <?php
                        if(sizeof($this->purchaseCategory)>0) {

                            ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
                <?php } ?>
            <!-- Looping content for each service ends -->

            <div class="r_float ">
                <div class="l_float amount_info">
					You have to pay  $ <span id="jqPriceDisplayarea"><?php echo $this->productPrice; ?></span>  for the services selected
                </div>
                <div class="l_float">
                    <input type="submit" class="button_orange2" name="Submit" value="PROCEED TO CHECKOUT" id="jqProceedToPay">
                </div>
            </div>



        </div>

        <div class="payment_option1_colright">

            <div class="question_area">
                <ul>


                    <!-- One set of question and answer -->
                    <li class="qust">
                        <div>
                            <div class="icon">&nbsp;</div>
                            <div class="txt">
							Can I Switch my store to a different domain name ?
                            </div>
                            <div class="clear"></div>
                        </div>
                    </li>

                    <li class="ans">
                        <div>
                            <div class="icon">&nbsp;</div>
                            <div class="txt">
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus consectetur, sem eu viverra auctor, felis dolor laoreet turpis, in tempor massa mauris at nunc. Maecenas urna arcu, consectetur in vulputate at, dignissim in lacus. Aliquam erat volutpat. Curabitur nec accumsan lacus.
                            </div>
                            <div class="clear"></div>
                        </div>
                    </li>
                    <!-- One set of question and answer ends-->

                    <!-- One set of question and answer -->
                    <li class="qust">
                        <div>
                            <div class="icon">&nbsp;</div>
                            <div class="txt">
							Can I Switch my store to a different domain name ?
                            </div>
                            <div class="clear"></div>
                        </div>
                    </li>

                    <li class="ans">
                        <div>
                            <div class="icon">&nbsp;</div>
                            <div class="txt">
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus consectetur, sem eu viverra auctor, felis dolor laoreet turpis, in tempor massa mauris at nunc. Maecenas urna arcu, consectetur in vulputate at, dignissim in lacus. Aliquam erat volutpat. Curabitur nec accumsan lacus.
                            </div>
                            <div class="clear"></div>
                        </div>
                    </li>
                    <!-- One set of question and answer ends-->

                </ul>
            </div>

        </div>

        <div class="clear"></div>
    </div>
    <!-- New HTML for payment options ends -->





    <div class="payment_option1" style="display:none;">
        <div class="payment_option1_colleft">
            <!--- column start------------------------------------------------------------------------------------ -->
            <div class="procedure_column">
                <div class="procedure_num">
                    <p>1</p>
                </div>
                <div class="procedure_content">
                    <h1>Domain Name Type</h1>
                    <div class="field_wrapper">
                        <input name="" type="radio" value="" checked>
                        <label>I want to use subdomain for cloud product</label>
                    </div>
                    <div class="field_wrapper">
                        <div class="spl_atnsion">
                            <label> http:// &nbsp; &nbsp; </label>
                            <input name="" type="text" class="width1" name="txtStoreName" id="txtStoreName">
                            <label> .<?php echo DOMAIN_NAME ?> </label>
                        </div>
                        <input name="" type="button" value="Check Availability" class="btn_cmngrey" id="jqCheckSubdomainExist">
                        <div class="clear"></div>
                    </div>
                    <div class="field_wrapper">
                        <input name="" type="radio" value="" checked>
                        <label>I want to use subdomain for cloud product</label>
                    </div>
                    <div class="field_wrapper">
                        <div class="spl_atnsion">
                            <label> Domain  </label>
                            <input name="" type="text" class="width1" id="idsld" name="sld">
                            <select class="domain_ext" id="tld" name="tld">
                                <option>com</option>
                                <option>info</option>
                                <option>org</option>
                                <option>biz</option>
                            </select>
                        </div>
                        <input name="" type="button"  value="Check Domain" id="jqCheckDomainExist" class="btn_cmngrey">
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                    <li><div id="jqShowMessage"></div></li>
                </div>
                <div class="clear"></div>
            </div>
            <!--- column start------------------------------------------------------------------------------------ -->
            <div class="procedure_column">
                <div class="procedure_num">
                    <p>2</p>
                </div>
                <div class="procedure_content">
                    <h1>Copyright Removal License</h1>
                    <div class="field_wrapper">
                        <input name="" type="radio" value="" checked>
                        <label>I want to use subdomain for cloud product</label>
                    </div>
                    <div class="field_wrapper">
                        <div class="spl_atnsion">
                            <label>f you do not want to display "powered by iscripts.com" at the bottomof your website you need a copyright removal license.</label>
                        </div>
                        <div class="clear"></div>
                    </div>

                    <div class="field_wrapper">
                        <div class="spl_atnsion">
                            <label> Domain  </label>
                            <input name="" type="text" class="width1">
                            <select name="" class="width2">
                                <option>dasdad</option>
                            </select>
                        </div>
                        <input name="" type="button" value="Check Availability" class="btn_cmngrey">
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="payment_option1_colright"></div>
        <div class="clear"></div>
    </div>






    <div class="clear"></div>
    <form id="jqProductPayNow">
        <!-- Display area for " PROCEED TO PAY "-->
        <div class="proceed_content_box2" style="display:none;">
            <div class="payment_column1">

                <div class="payment_form">

                    <!-- Display Area for Registrar information -->
                    <div class="heading_style2"><b>Registrant Information</b></div>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="2">
                        <tr id="jqRegisterYears">
                            <td width="35%" align="right" class="maintext_new">
                                <b class="red">*</b>Period&nbsp;to&nbsp;Register:&nbsp;:&nbsp;&nbsp;
                            </td>

                            <td valign="middle" align="left">
                                <select class="comm_input" name="NumYears" id="jqNumYears">
                                    <option value="1">1 year</option>
                                    <option value="2">2 years</option>
                                    <option value="3">3 years</option>
                                    <option value="4">4 years</option>
                                    <option value="5">5 years</option>
                                    <option value="6">6 years</option>
                                    <option value="7">7 years</option>
                                    <option value="8">8 years</option>
                                    <option value="9">9 years</option>
                                    <option value="10">10 years</option>                                                  </select>
                            </td>

                        </tr>
                        <tr>
                            <td  align="right" ><b class="red">*</b>First Name&nbsp;:&nbsp;&nbsp;</td>
                            <td  align="left"><input name="RegistrantFirstName" id="RegistrantFirstName" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantFirstName" ]; ?>" style="width:230px; "> </td>
                        </tr>
                        <tr>
                            <td  align="right"><b class="red">*</b> Last Name&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantLastName" id="RegistrantLastName" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantLastName" ]; ?>" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right" ><b class="red">*</b>Job&nbsp;Title&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantJobTitle" id="RegistrantJobTitle" value="<?php echo $_POST[ "RegistrantJobTitle" ]; ?>" type="text" class="comm_input" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right"><b class="red">*</b>Organization&nbsp;Name&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantOrganizationName" id="RegistrantOrganizationName" value="<?php echo $_POST[ "RegistrantOrganizationName" ]; ?>" type="text" class="comm_input" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right" ><b class="red">*</b>Address1&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantAddress1" id="RegistrantAddress1" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantAddress1" ]; ?>" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right" >Address2&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantAddress2" id="RegistrantAddress2" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantAddress2" ]; ?>" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right" ><b class="red">*</b>City&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantCity" id="RegistrantCity" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantCity" ]; ?>" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right"><?php
                                $strStateValue = trim( $_POST[ "RegistrantState" ] );
                                ?>
                                US State&nbsp;&nbsp;
                                <input type="radio" value="S" id="radio" name="RegistrantStateProvinceChoice"
                                <?php if ( $_POST[ "RegistrantStateProvinceChoice" ] == "State" || $_POST[ "RegistrantStateProvinceChoice" ] == "" ) {
                                    echo "checked";
                                }?>
                                       onClick="fnRegistrantStateSelected()">&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"> <select name="RegistrantState" id="RegistrantState" size="1" class="comm_input" style="width:230px; ">
                                    <?php
                                    global $usStates;
                                    foreach( $usStates as $key => $value) {

                                        ?>
                                    <option value="<?php echo $key?>" <?php if($key==$countryKey) echo "selected";?>><?php echo $value;?></option>
                                        <?php
                                    }
                                    ?>
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td align="right" >Province
                                <input type="radio" size="14" value="P" name="RegistrantStateProvinceChoice" id="radio2" <?php if ( $strProvinceValue != "" ) {
                                    echo "checked";
                                       } ?> onClick="fnRegistrantProvinceSelected()" >&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input  type="text" class="comm_input" name="RegistrantProvince" id="RegistrantProvince" maxlength="60"
                                                     value="<?php echo $strProvinceValue; ?>" style="width:230px; "></td>
                        </tr>
                        <tr>

                            <td  align="right">Not&nbsp;Applicable&nbsp;&nbsp;
                                <input type="radio" value="Blank" name="RegistrantStateProvinceChoice" id="radio3" <?php if ($strStateValue=='' && $strProvinceValue=='') {
                                    echo "checked";
                                       }?> onClick="fnRegistrantNoneSelected()">&nbsp;:&nbsp;&nbsp;</td>
                            <td valign="middle" class="maintext_new" align="left">&nbsp;&nbsp;&nbsp;(the state/province field will be left blank) <b class="red"></b></td>

                        </tr>
                        <tr>
                            <td align="right" ><b class="red">*</b>Postal/ZIP&nbsp;Code&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantPostalCode" id="RegistrantPostalCode" value="<?php echo $_POST["RegistrantPostalCode"]; ?>" type="text" class="comm_input" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right" ><b class="red">*</b>Country&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"> <select name="RegistrantCountry" id="idRegistrantCountry" class="comm_input" style="width:230px; ">
                                    <?php global $countries;
                                    foreach( $countries as $key => $value) {

                                        ?>
                                    <option value="<?php echo $key?>" <?php if($key==$countryKey) echo "selected";?>><?php echo $value;?></option>
                                        <?php
                                    }
                                    ?>
                                    <option value="undefined">undefined</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td align="right" ><b class="red"></b><b class="red">*</b>Fax&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantFax" id="RegistrantFax" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantFax" ]; ?>" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right" ><b class="red">*</b>Phone&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left" width="73%"><input name="RegistrantPhone" id="RegistrantPhone" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantPhone" ]; ?>" style="width:230px; "></td>
                        </tr>
                        <tr>
                            <td align="right"><b class="red">*</b>Email&nbsp;Address&nbsp;:&nbsp;&nbsp;</td>
                            <td align="left"><input name="RegistrantEmailAddress" id="RegistrantEmailAddress" type="text" class="comm_input" value="<?php echo $_POST[ "RegistrantEmailAddress" ]; ?>" style="width:230px; "></td>
                        </tr>
                        <?php
//                                                  if($showUK) {
//                                                    include "includes/uk.php";
//                                                  }
                        ?>
                        <?php if($shownexus=="YES") { ?>
                        <input type=hidden name=shonex value="<?php echo $shownexus?>">
                        <tr>
                            <td align="left"  colspan="2">
                                    <?php
                                    if($shownexus=="YES") {
                                        include "includes/nexus.php";
                                    }

                                    ?>


                            </td>
                        </tr>
                        <!-- this ends section TWO -->

                            <?php } ?>
                        <?php if($showca=="YES") { ?>
                        <input type=hidden name=showca value="<?php echo $showca?>">
                        <tr>
                            <td align="left" class="maintext_new" colspan="2">
                                    <?php
//												if($showca=="YES"){
//													include "includes/showca.php";
//												}

                                    ?>

                            </td>
                        </tr>
                        <!-- this ends section TWO -->

                            <?php } ?>

                        <tr>
                            <td colspan="2">
                                <b>Additional Settings<b>

                                        </td>

                                        </tr>
                                        <tr>
                                            <td colspan="2" align="left">
                                                <input type="checkbox" name="UnLockRegistrar" value="ON" checked id="UnLockRegistrar">
                                                Do not allow this name to be transferred to another registrar (recommended)
                                            </td>


                                        </tr>


                                        </table>
                                        <div class="heading_style2" align="left">Additional Settings</div>
                                        &nbsp;<br>

<!-- <input type="submit"  name="" class= "button_orange_big" value="Register Domain" id="jqRegisterDomain"> -->
                                        </div>

                                        </div>

                                        <div class="payment_column2">


                                            <!-- Cart Info -->
                                            <div class="payment_right_container">
                                                <ul>
                                                    <li>
                                                        <div class="payment_right_item">
                                                            <div class="large l_float">
                                                                <h5>Item</h5>
                                                            </div>
                                                            <div class="small  right_text r_float"><h5>Amount</h5></div>
                                                            <div class="clear"></div>
                                                        </div>

                                                        <div class="payment_right_item">
                                                            <div class="large left_text l_float" style="float:left;">
                                                                <?php //echo $this->productname; ?>
                                                            </div>
                                                            <div class="small r_float right_text r_float">
                                                                <b><?php echo $this->productPrice; ?></b>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>

                                                        <div class="payment_right_item">
                                                            <div class="large right_text" style="float:left;">Sub Total&nbsp;:</div>
                                                            <div class="small r_float right_text" style="float:right;">
                                                                <span class="jqSubTotal"><?php echo Utils::formatPrice(0); ?></span>&nbsp;
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>

                                                         <div class="payment_right_item">
                                                                    <div class="large right_text" style="float:left;">Discounts&nbsp;:</div>
                                                                    <div class="small r_float right_text" style="float:right;">
                                                                        <span class="jqDiscount"><?php echo Utils::formatPrice(0); ?></span>&nbsp;
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>
                                                        <div class="payment_right_item">
                                                            <div class="large right_text" style="float:left;">Total&nbsp;:</div>
                                                            <div class="small r_float right_text" style="float:right;">

                                                                <span class="jqTotalPurchaseVal"><?php echo $this->productPrice; ?></span>&nbsp;USD
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>


                                                    </li>

                                                </ul>
                                                <div class="clear"></div>
                                            </div>

                                            <!-- Cart Info ends -->

                                            <!-- Credit card info -->
                                            <div class="payment_right_container">
                                                <ul>
                                                    <li>

                                                        <div class="payment_right_item">
                                                            <div class="large l_float">
                                                                <h5>Enter your credit card details</h5>
                                                            </div>

                                                            <div class="clear"></div>
                                                        </div>



                                                        <div class="payment_right_item">
                                                            <div class="small right_text l_float">Card Number&nbsp;&nbsp;&nbsp;</div>
                                                            <div class="large l_float left_text">
                                                                <input type="text" id="ccno2" name="ccno2" maxlength="16" class="width1">
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>

                                                        <div class="payment_right_item">
                                                            <div class="small right_text l_float">Expiry Date(MM/YYYY)&nbsp;&nbsp;&nbsp;</div>
                                                            <div class="large l_float left_text">
                                                                <select name="expM" id="expM2" class="width2">

                                                                    <?php for($i=1; $i<=12; $i++) { ?>
                                                                    <option>
                                                                            <?php if($i<10) {
                                                                                echo '0'.$i;
                                                                            }else {
                                                                                echo $i;
                                                                            }?>
                                                                    </option>
                                                                        <?php                         } ?>

                                                                </select>
                                                                <select name="expY" id="expY2" class="width2">

                                                                    <?php for($i=date('Y'); $i<=(date('Y')+50); $i++) { ?>
                                                                    <option>
                                                                            <?php echo $i;?>
                                                                    </option>
                                                                        <?php                                             } ?>

                                                                </select>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                        <input type="hidden" name="productId" value="<?php echo $this->productid;?>" id="productId">
                                                        <div class="payment_right_item">
                                                            <div class="small right_text l_float">
			CVV/CVV2 No.&nbsp;&nbsp;&nbsp;
                                                            </div>
                                                            <div class="large l_float left_text">
                                                                <input type="text" id="cvv2" name="cvv2" maxlength="4" class="width2">&nbsp;&nbsp;<a href="#">Where do I find this?</a>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>








                                                    </li>

                                                </ul>
                                                <div class="clear"></div>
                                            </div>
                                            <!-- Credit card info ends -->
                                            <!-- Coupon info -->
                                            <div class="payment_right_container">
                                                <ul>
                                                    <li>

                                                        <div class="payment_right_item">
                                                            <div class="large l_float">
                                                                <h5>Enter Coupon Code</h5>
                                                            </div>

                                                            <div class="clear"></div>
                                                        </div>
                                                        <div class="payment_right_item">
                                                            <div class="small right_text l_float">Coupon Code&nbsp;&nbsp;&nbsp;</div>
                                                            <div class="large l_float left_text">
                                                                <input type="text" id="couponNumber1" name="couponNumber" onchange="couponCodeValidation('couponNumber1')" maxlength="16" class="width1">
                                                                <div id="couponNumber1_err"><label class="error">&nbsp;</label></div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <div class="clear"></div>
                                            </div>
                                            <!-- Coupon info ends -->

                                            <div class="comm_div" align="right"><input type="submit" class= "button_orange_big"name="Submit" value="PAY NOW" id="jqRegisterDomain"></div>


                                        </div>


                                        <div class="clear"></div>

                                        <!-- Display area for " PROCEED TO PAY "-->

                                        </div>
                                        </form>
                                        <!-- Payment details area start -->

                                        <input type="hidden" name="totPrice" value="<?php echo $this->productPrice; ?>" id="jqTotalPrice">
                                        <input type="hidden" name="finalPrice" value="<?php echo $this->productPrice; ?>" id="jqFinalPrice">
                                        <input type="hidden" name="totPriceDomain" value="0" id="jqTotalDomainPrice">
                                        <input type="hidden" name="tldPrice" value="0" id="jqTldPrice">
                                        <input type="hidden" name="domainFlag" value="0" id="jqdomainFlag">
                                        <input type="hidden" name="productLookUpid" value="<?php echo $this->productLookUpid;?>" id="productLookUpid">

                                        <input type="hidden" name="domainFlag" value="" id="jqProductService">

                                        <!-- Payment details area end -->

                                        <!-- Display area for " PROCEED TO PAY "-->
                                        <div class="proceed_content_box3" style="display:none;">
                                            <form id="frmUsers" name="frmUsers" method="POST">
                                                <div class="payment_column1">
                                                    <div class="payment_form">


                                                        <table border="0" cellpadding="0" width="80%" align="center">

                                                            <tr>

                                                                <td colspan="2" style="padding-bottom: 15px;"><b>Please enter your info</b></td>


                                                            </tr>
                                                            <tr>

                                                                <td align="left" width="20%" valign="center">First Name&nbsp;&nbsp;&nbsp; </td>
                                                                <td align="left"><input type="text" id="fname" name="fname" value="<?php echo $this->fname;?>" maxlength="255" class="width1"></td>

                                                            </tr>

                                                            <tr>

                                                                <td align="left" valign="center">Last Name&nbsp;&nbsp;&nbsp;</td>
                                                                <td><input type="text" id="lname" name="lname" value="<?php echo $this->lname;?>" maxlength="255" class="width1"></td>

                                                            </tr>

                                                            <tr>

                                                                <td align="left" valign="center">Email&nbsp;&nbsp;&nbsp;</td>
                                                                <td><input type="text" id="email" name="email" value="<?php echo $this->email;?>" maxlength="255" class="width1"></td>

                                                            </tr>

                                                            <tr>

                                                                <td align="left" valign="center">Address&nbsp;&nbsp;&nbsp;</td>
                                                                <td><input type="text" id="add1" name="add1" value="<?php echo $this->address;?>" maxlength="255" class="width1"></td>

                                                            </tr>



                                                            <tr>

                                                                <td align="left" valign="center">Country&nbsp;&nbsp;&nbsp;</td>

                                                                <td>
                                                                    <select name="country" id="country" style="width:160px;" >
                                                                        <option value="">Select Country</option>
                                                                        <?php
                                                                        $selectedCountry    = stripslashes($this->country);
                                                                        if($selectedCountry=="") $countryKey   ="US";
                                                                        else
                                                                            $countryKey = $selectedCountry;
                                                                        global $countries;
                                                                        foreach( $countries as $key => $value) {

                                                                            ?>
                                                                        <option value="<?php echo $key?>" <?php if($key==$countryKey) echo "selected";?>><?php echo $value;?></option>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <option value="undefined">undefined</option>
                                                                    </select>

                                                                </td>

                                                            </tr>
                                                            <tr>

                                                                <td align="left" valign="center">State&nbsp;&nbsp;&nbsp;</td>
                                                                <td><input type="text" id="state" name="state" value="<?php echo $this->state;?>" maxlength="255" class="width1" ></td>

                                                            </tr>
                                                            <tr>

                                                                <td align="left" valign="center">City&nbsp;&nbsp;&nbsp;</td>
                                                                <td><input type="text" id="city" name="city" value="<?php echo $this->city;?>" maxlength="255" class="width1"></td>

                                                            </tr>

                                                            <tr>

                                                                <td align="left" valign="center">ZIP&nbsp;&nbsp;&nbsp;</td>
                                                                <td><input type="text" id="zip" name="zip" value="<?php echo $this->zip;?>" maxlength="20"></td>

                                                            </tr>

                                                            <!--

                                                                      <tr>

                                                                          <td align="right" >Card Number&nbsp;:&nbsp;&nbsp;</td>
                                                                          <td><input type="text" id="ccno" name="ccno" maxlength="16"></td>

                                                                      </tr>


                                                                      <tr>

                                                                          <td align="right" >CVV&nbsp;:&nbsp;&nbsp;</td>
                                                                          <td><input type="text" id="cvv" name="cvv" maxlength="4"></td>

                                                                      </tr>


                                                                      <tr>

                                                                          <td align="right" >Expiration Date(MM/YYYY)&nbsp;:&nbsp;&nbsp;</td>
                                                                          <td><select name="expM" id="expM">

                                                            <?php for($i=1; $i<=12; $i++) { ?>
                                                                          <option>
                                                                <?php if($i<10) {
                                                                    echo '0'.$i;
                                                                }else {
                                                                    echo $i;
                                                                }?>
                                                                          </option>
                                                                <?php                         } ?>

                                                                              </select> &nbsp;

                                                                           <select name="expY" id="expY">

                                                            <?php for($i=date('Y'); $i<=(date('Y')+50); $i++) { ?>
                                                                          <option>
                                                                <?php echo $i;?>
                                                                          </option>
                                                                <?php                                             } ?>

                                                                          </select>


                                                                          </td>


                                                                      </tr>
                                                            -->
                                                            <tr>
                                                                <td align="left">&nbsp;</td>
                                              <td  align="left"><!--<input type="submit" class= "button_orange_big"name="Submit" value="PAY NOW">--></td>


                                                            </tr>
                                                        </table>

                                                    </div>

                                                </div>
                                                <div class="payment_column2">


                                                    <!-- Cart Info -->
                                                    <div class="payment_right_container">
                                                        <ul>
                                                            <li>

                                                                <div class="payment_right_item">
                                                                    <div class="large l_float">
                                                                        <h5>Item</h5>
                                                                    </div>
                                                                    <div class="small  right_text r_float"><h5>Amount</h5></div>
                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="payment_right_item">
                                                                    <div class="large left_text l_float" style="float:left;">
                                                                        <?php echo $this->productname; ?>
                                                                    </div>
                                                                    <div class="small r_float right_text r_float">
                                                                        <b><?php echo $this->productPrice; ?></b>
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="payment_right_item">
                                                                    <div class="large right_text" style="float:left;">Sub total&nbsp;:</div>
                                                                    <div class="small r_float right_text" style="float:right;">
                                                                        <span class="jqSubTotal"><?php echo Utils::formatPrice(0); ?></span>&nbsp;
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="payment_right_item">
                                                                    <div class="large right_text" style="float:left;">Discounts&nbsp;:</div>
                                                                    <div class="small r_float right_text" style="float:right;">
                                                                        <span class="jqDiscount"><?php echo Utils::formatPrice(0); ?></span>&nbsp;
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="payment_right_item">
                                                                    <div class="large right_text" style="float:left;">Total&nbsp;:</div>
                                                                    <div class="small r_float right_text" style="float:right;">
                                                                        <span class="jqTotalPurchaseVal"><?php echo $this->productPrice; ?></span>&nbsp;
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>




                                                            </li>

                                                        </ul>
                                                        <div class="clear"></div>
                                                    </div>

                                                    <!-- Cart Info ends -->

                                                    <!-- Credit card info -->
                                                    <div class="payment_right_container">
                                                        <ul>
                                                            <li>

                                                                <div class="payment_right_item">
                                                                    <div class="large l_float">
                                                                        <h5>Enter your credit card details</h5>
                                                                    </div>

                                                                    <div class="clear"></div>
                                                                </div>



                                                                <div class="payment_right_item">
                                                                    <div class="small right_text l_float">
			Card Number&nbsp;&nbsp;&nbsp;
                                                                    </div>
                                                                    <div class="large l_float left_text">
                                                                        <input type="text" id="ccno1" name="ccno1" maxlength="16" class="width1">
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="payment_right_item">
                                                                    <div class="small right_text l_float">
			Expiry Date(MM/YYYY)&nbsp;&nbsp;&nbsp;
                                                                    </div>
                                                                    <div class="large l_float left_text">
                                                                        <select name="expM" id="expM1" class="width2">

                                                                            <?php for($i=1; $i<=12; $i++) { ?>
                                                                            <option>
                                                                                    <?php if($i<10) {
                                                                                        echo '0'.$i;
                                                                                    }else {
                                                                                        echo $i;
                                                                                    }?>
                                                                            </option>
                                                                                <?php                         } ?>

                                                                        </select>
                                                                        <select name="expY" id="expY1" class="width2">

                                                                            <?php for($i=date('Y'); $i<=(date('Y')+50); $i++) { ?>
                                                                            <option>
                                                                                    <?php echo $i;?>
                                                                            </option>
                                                                                <?php                                             } ?>

                                                                        </select>
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>
                                                                <input type="hidden" name="productId" value="<?php echo $this->productid;?>" id="productId">
                                                                <div class="payment_right_item">
                                                                    <div class="small right_text l_float">
			CVV/CVV2 No.&nbsp;&nbsp;&nbsp;
                                                                    </div>
                                                                    <div class="large l_float left_text">
                                                                        <input type="text" id="cvv1" name="cvv1" maxlength="4" class="width2">&nbsp;&nbsp;<a href="#">Where do I find this?</a>
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                </div>








                                                            </li>

                                                        </ul>
                                                        <div class="clear"></div>
                                                    </div>
                                                    <!-- Credit card info ends -->
                                                    <!-- Coupon info -->
                                            <div class="payment_right_container">
                                                <ul>
                                                    <li>

                                                        <div class="payment_right_item">
                                                            <div class="large l_float">
                                                                <h5>Enter Coupon Code</h5>
                                                            </div>

                                                            <div class="clear"></div>
                                                        </div>



                                                        <div class="payment_right_item">
                                                            <div class="small right_text l_float">Coupon Code&nbsp;&nbsp;&nbsp;</div>
                                                            <div class="large l_float left_text">
                                                                <input type="text" id="couponNumber2" name="couponNumber" onchange="couponCodeValidation('couponNumber2')" maxlength="16" class="width1">
                                                                <div id="couponNumber2_err"><label class="error">&nbsp;</label></div>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>

                                                    </li>

                                                </ul>
                                                <div class="clear"></div>
                                            </div>
                                            <!-- Coupon info ends -->


                                                    <div class="comm_div" align="right"><input type="submit" class= "button_orange_big"name="Submit" value="PAY NOW"></div>
                                            </form>

                                        </div>
                                        <div class="clear"></div>



                                        <!-- Display area for " PROCEED TO PAY "-->

                                        </div>
                                        <div id="jqProgress" >
                                            <div class="progress_outer">
                                                <div class="progress_bar">
                                                    <div class="pointer" style="left:-120px;">
                                                        <b>Phase 1: Originate</b><br /> Analyzing input, preparing installation files and scripts
                                                    </div>
                                                    <div class="bar" style="width:0px;"></div>
                                                </div>
                                                <div class="progress_count">0&nbsp;%</div>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div id="jqMessage"></div>
                                        <div class="overlay" id="overlay" style="display:none;">

                                            <div class="clear"></div>
                                        </div>
                                        </div>