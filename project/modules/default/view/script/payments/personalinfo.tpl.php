<div class="payment_right_container">
    <ul>
        <li>
            <h3>Please enter your billing info</h3>
            <div class="payment_column1">
                <div class="payment_form">

                    <input type="hidden" name="txtStoreName" id="txtStoreNameuserfm">
                    <table border="0" cellpadding="0" width="100%" align="center">

                        <!-- <tr>
                            <td colspan="2" style="padding-bottom: 15px;">
                                <h3>Please enter your billing info</h3>
                            </td>
                        </tr> -->
                        
                        <tr>

                            <td align="left" width="32%" valign="center">First Name<span class="mandred">*</span> </td>
                            <td align="left"><input type="text" id="fname" name="fname" value="<?php echo PageContext::$response->cardDetails->vFirstName ? PageContext::$response->cardDetails->vFirstName : $this->fname; ?>" maxlength="255" ></td>

                        </tr>

                        <tr>

                            <td align="left" valign="center">Last Name<span class="mandred">*</span></td>
                            <td><input type="text" id="lname" name="lname" value="<?php echo PageContext::$response->cardDetails->vLastName ? PageContext::$response->cardDetails->vLastName : $this->lname; ?>" maxlength="255" ></td>

                        </tr>

                        <tr>

                            <td align="left" valign="center">Email<span class="mandred">*</span></td>
                            <td><input type="text" id="email" name="email" value="<?php echo PageContext::$response->cardDetails->vEmail ? PageContext::$response->cardDetails->vEmail : $this->email; ?>" maxlength="255" ></td>

                        </tr>

                        <tr>

                            <td align="left" valign="center">Address<span class="mandred">*</span></td>
                            <td><input type="text" id="add1" name="add1" value="<?php echo PageContext::$response->cardDetails->vAddress ? PageContext::$response->cardDetails->vAddress : $this->address; ?>" maxlength="255" ></td>

                        </tr>



                        <tr>

                            <td align="left" valign="center">Country<span class="mandred">*</span></td>

                            <td>
                                <select name="country" id="country" >
                                    <option value="">Select Country</option>
                                    <?php
                                    $selectedCountry = stripslashes(PageContext::$response->cardDetails->vCountry ? PageContext::$response->cardDetails->vCountry : $this->country);
                                    if ($selectedCountry == "")
                                        $countryKey = "US";
                                    else
                                        $countryKey = $selectedCountry;
                                    global $countries;
                                    foreach ($countries as $key => $value) {
                                        ?>
                                    <option value="<?php echo $key ?>" <?php if ($key == $countryKey || $value == PageContext::$response->cardDetails->vCountry)
                                                    echo "selected"; ?>><?php echo $value; ?></option>
                                                <?php
                                            }
                                            ?>
                                    <option value="undefined">undefined</option>
                                </select>

                            </td>

                        </tr>
                        <tr>

                            <td align="left" valign="center">State<span class="mandred">*</span></td>
                            <td><input type="text" id="state" name="state" value="<?php echo PageContext::$response->cardDetails->vState ? PageContext::$response->cardDetails->vState : $this->state; ?>" maxlength="255"></td>

                        </tr>
                        <tr>

                            <td align="left" valign="center">City<span class="mandred">*</span></td>
                            <td><input type="text" id="city" name="city" value="<?php echo PageContext::$response->cardDetails->vCity ? PageContext::$response->cardDetails->vCity : $this->city; ?>" maxlength="255" ></td>

                        </tr>

                        <tr>

                            <td align="left" valign="center">ZIP<span class="mandred">*</span></td>
                            <td><input type="text" id="zip" name="zip" value="<?php echo PageContext::$response->cardDetails->vZipcode ? PageContext::$response->cardDetails->vZipcode : $this->zip; ?>" maxlength="20"></td>

                        </tr>


                    </table>

                </div>

            </div>

        </li>
    </ul>
    <div class="clear"></div>
</div>