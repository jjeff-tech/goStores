<div class="form_container">
    <div class="form_top">Edit Product</div>
    <div class="form_bgr">
        <?php PageContext::renderPostAction('errormessage','index');?>        
        <form name="frmAddProduct" id="frmAddProduct" method="post" action="<?php echo BASE_URL; ?>admin/products/editproduct" enctype="multipart/form-data" onsubmit="return validateProductEd()" >
            <input type="hidden" name="nPId" id="nPId" value="<?php echo $this->dataArr['nPId'] ?>" />
            <input type="hidden" name="nPRId" id="nPRId" value="<?php echo $this->dataArr['nPRId'] ?>" />
            <!-- Product Add Area -->
        <div class="comm_section">
            <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                <tr>
                    <td align="left" valign="top" width="20%">Product  <span class="mandred">*</span></td>
                    <td align="left" valign="top">
                        <input name="productName" id="productName" type="text" maxlength="200" value="<?php echo stripslashes($this->dataArr['vPName']) ?>">
                        <label id="product_name_error" class="error"></label>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Release <span class="mandred">*</span></td>
                    <td align="left" valign="top">
                        <input name="productRelease" id="productRelease" type="text" style="width:50px" maxlength="10" size=5 value="<?php echo stripslashes($this->dataArr['vVersion']) ?>">
                        <label id="product_release_error" class="error"></label>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Caption  <span class="mandred">*</span></td>
                    <td align="left" valign="top">
                        <input name="productCaption" id="productCaption" type="text" maxlength="200" value="<?php echo stripslashes($this->dataArr['vProductCaption']) ?>">
                        <label id="product_caption_error" class="error"></label>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Logo  <span class="mandred">*</span><br>
                    </td>
                    <td align="left" valign="bottom">
                        <div class="l_float">
                        <input name="productLogo" id="productLogo" type="file" />
                        <img src="<?php echo IMAGE_URL; ?>icon_help.gif" border="0" alt=""  title="Ideal size is 240 X 320 or greater."  />

                         <label id="product_logo_error" class="error"></label>
                        </div>
                        <div class="l_float">
                        <?php                    
                        $pdLogo = BASE_PATH.'project/styles/images/'.$this->dataArr['vProductlogo'];                      
                        if(is_file($pdLogo)) {
                        ?>
                            &nbsp;&nbsp;<a href="<?php echo IMAGE_URL.$this->dataArr['vProductlogo'];?>" title="<?php echo $this->dataArr['vPName'] ?> Logo" class="thickbox"><img src="<?php echo IMAGE_URL; ?>/preview_file.png" border="0" /></a>
                        <?php
                        } 
                        ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Logo(small) <span class="mandred">*</span><br>
                    </td>
                    <td align="left" valign="top">
                        <div class="l_float">
                            <input name="productLogoSmall" id="productLogoSmall" type="file" />
                            <img src="<?php echo IMAGE_URL; ?>icon_help.gif" border="0" alt=""  title="Ideal size is 122 X 77 or greater."  />
                            <label id="product_logo_small_error" class="error"></label>
                        </div>
                        <div class="l_float">
                        <?php
                        $pdLogoSm = BASE_PATH.'project/styles/images/'.$this->dataArr['vProductlogoSmall'];
                        if(is_file($pdLogoSm)) {
                        ?>
                        &nbsp;&nbsp;<a href="<?php echo IMAGE_URL.$this->dataArr['vProductlogoSmall'];?>" title="<?php echo $this->dataArr['vPName'] ?> Logo Small" class="thickbox"><img src="<?php echo IMAGE_URL; ?>/preview_file.png" border="0" /></a>
                        <?php
                        }
                        ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Screens  <span class="mandred">*</span><br>
                    </td>
                    <td align="left" valign="top">
                        <div class="l_float">
                        <input name="productScreens" id="productScreens" type="file" />
                        <img src="<?php echo IMAGE_URL; ?>icon_help.gif" border="0" alt=""  title="Ideal size is 487 X 305 or greater."  />
                         <label id="product_screen_error" class="error"></label>
                        </div>
                        <div class="l_float">
                        <?php
                        $pdLogoSc = BASE_PATH.'project/styles/images/'.$this->dataArr['vProductScreens'];
                        if(is_file($pdLogoSc)) {
                        ?>
                        &nbsp;&nbsp;<a href="<?php echo IMAGE_URL.$this->dataArr['vProductScreens'];?>" title="<?php echo $this->dataArr['vPName'] ?> Screens" class="thickbox"><img src="<?php echo IMAGE_URL; ?>/preview_file.png" border="0" /></a>
                        <?php
                        }
                        ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Description</td>
                    <td align="left" valign="top">
                        <textarea name="productDescription" id="productDescription" class=textarea rows=12 cols=64 style="width:350px;"><?php echo stripslashes($this->dataArr['vProductDescription']) ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Installation Permissions<br>
                    </td>
                    <td align="left" valign="top">
                        <textarea name="productPermission" id="productPermission" class=textarea rows=12 cols=64 style="width:350px;"><?php echo stripslashes($this->dataArr['vPermissions']) ?></textarea>
                        <img src="<?php echo IMAGE_URL; ?>icon_help.gif" border="0" alt=""  title="Eg : images/, images/album/, images/classifieds/"  />
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top">Product  <span class="mandred">*</span><br>
                    </td>
                    <td align="left" valign="top">
                        <input name="productPack" id="productPack" type="file" />
                        <img src="<?php echo IMAGE_URL; ?>icon_help.gif" border="0" alt=""  title="Allowed Type : 'zip', Max Upload Size : <?php echo $this->maxUpload ;?> MB"  />
                        <label id="product_pack_error" class="error"></label>
                    </td>
                </tr>
                <tr>
                    <td align="left" valign="top"></td>
                    <td align="left" valign="top"></td>
                </tr>
            </table>
        </div>
        <!-- Product Add Area Ends -->
        <!-- Product Service Area -->
        <h3>Services</h3>
        <div class="serv_container" id="productSer">
            <ul>
                <!-- looping section-->
                <li class="header">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tbl">
                        <tr>
                            <th align="left" valign="top" width="2%"><?php echo $this->checkbox('chkAll', '1', NULL, NULL, 'chkAll'); ?></th>
                            <th align="left" valign="top" width="20%">Name</th>
                            <th align="left" valign="top" width="25%">Description</th>
                            <th align="left" valign="top" width="15%">Price</th>
                            <th align="left" valign="top" width="20%">Service Category</th>
                            <th align="left" valign="top" width="18%">Billing Duration</th>                        
                        </tr>
                    </table>
                </li>

                <!-- looping section-->
                <?php
                $i=0;
                $k=0;
                foreach($this->dataArr['productServices'] as $dataSerItem) {
                    ++$i;
                    //echo '<pre>'; print_r($dataSerItem); echo '</pre>';
                    $k=$i-1;
                ?>
                <li class="serviceItem" id="serItem_<?php echo $i ?>">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="tbl">
                        <input type="hidden" name="nServiceId[]" id="nServiceId_<?php echo $i; ?>" value="<?php echo $dataSerItem['nServiceId'] ?>" />
                        <tr>
                            <td align="left" valign="top" width="2%">
                                <?php $serSel = (empty($dataSerItem['nServiceId'])) ? 'x' : NULL; ?>
                                <?php echo $this->checkbox('chkService[]', $dataSerItem['nServiceId'], $serSel, NULL, NULL); ?>
                            </td>
                            <td align="left" valign="top" width="20%">
                                <input type=text name="serviceName[]" id="serviceName_<?php echo $i; ?>" value="<?php echo stripslashes($dataSerItem['vServiceName']); ?>" class="textbox width2">
                                <div class="error" id="service_name_error_<?php echo $i; ?>"></div>
                            </td>
                            <td align="left" valign="top" width="20%">
                                <textarea name="serviceDescription[]" id="serviceDescription_<?php echo $i; ?>" class="textarea width2"><?php echo stripslashes($dataSerItem['vServiceDescription']); ?></textarea>
                                 <div class="error" id="service_description_error_<?php echo $i; ?>"></div>

                            </td>
                            <td align="left" valign="top" width="10%">
                               <?php echo CURRENCY_SYMBOL; ?><input type=text name="servicePrice[]" id="servicePrice_<?php echo $i ?>" value="<?php echo stripslashes($dataSerItem['price']); ?>" class="textbox width3">
                                <div class="error" id="service_price_error_<?php echo $i; ?>"></div>

                            </td>
                            <td align="left" valign="top" width="20%">
                                    <?php echo $this->select("serviceCategory[]",$this->sCatArr,$dataSerItem['nSCatId'],'textbox width2', NULL, 'serviceCategory'.$i); ?>
                            </td>
                            <td align="left" valign="top" width="18%">
                                    <?php echo $this->radio('billingType['.$k.']', 'M', $dataSerItem['vBillingInterval'], NULL, '&nbsp;', NULL); ?> Month(days)<br>
                                    <?php echo $this->radio('billingType['.$k.']', 'Y', $dataSerItem['vBillingInterval'], NULL, '&nbsp;', NULL); ?> Year<br>
                                    <?php echo $this->radio('billingType['.$k.']', 'L', $dataSerItem['vBillingInterval'], NULL, '&nbsp;', NULL); ?> Lifetime<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="billingInterval[]" id="billingInterval_<?php echo $i; ?>" value="<?php echo stripslashes($dataSerItem['nBillingDuration']); ?>"  class="textbox width3" maxlength="6">
                                   <div class="error" id="service_billing_duration_error_<?php echo $i; ?>"></div>

                            </td>                          
                        </tr>
                    </table>
                </li>
                <?php              
                }
                ?>
                <!-- looping section ends-->
                <li class="common">
                    <div class="clear">
                        <div class="l_float">
                            <input name="deleteChkEd" id="deleteChkEd" type="button" class="comm_button_small" value="Delete Selected">
                        </div>
                        <div class="r_float">
                            <input type=text name="addNew" id="addNew" value="" class="textbox width3">	&nbsp;
                            <input type="hidden" name="countL" id="countL" value="<?php echo $i ?>">
                            <input type="button" value="Add More Service" class="comm_button_small" onclick="newServiceItem()" />
                        </div>
                        <div class="clear"></div>
                    </div>
                </li>


            </ul>
        </div>
        <!-- Product Service Area Ends -->
        <!-- Add Button -->
        <table  width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                <tr>
        <th align="left" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/products/index">Cancel</a></div></th>
        <td valign="top">
            <input type="submit" class="comm_button" name="btnSubmit" value="Save Changes" /></td>
        </tr>
         </table>
        
        <!-- Add Button Ends -->
    </form>
    </div>
    <div class="form_bottom"></div>

</div>