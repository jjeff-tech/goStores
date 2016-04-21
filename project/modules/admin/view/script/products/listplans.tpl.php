<div class="section_list_view ">

<div class="tophding_blk">

    
    <div class="input-append pull-right srch_pad">
            <form class="cmxform" id="frmSearchPlan" action="<?php echo BASE_URL; ?>cms?section=plans" method="post" >
                <input type="hidden" name="action" value="search">
                <input name="search" id="searchText" type="text" class="input-medium have-margin10" placeholder="Search" value="<?php echo PageContext::$response->txtSearch; ?>">
                <input name="btnSearch" type="submit" id="section_search_button" class="btn btn-info searchBtn" value="">
            </form>
        </div>
        
            
            <span class="legend hdname hdblk_inr"><div class="hdblk_inr">Section : Plans</div></span>

            <div id="jqMessageConatainer">
            <?php if (!empty(PageContext::$response->message)) { ?>
                <div class="alert alert-<?php echo PageContext::$response->successError; ?>">
                    <button class="close" data-dismiss="alert" type="button">x</button>
                    <?php echo PageContext::$response->message; ?>
                </div>
            <?php } ?>
            </div>
            
            
       </div>
       
       
    
    <table width="100%" id="tbl_activities" class="cms_listtable table table-striped table-bordered table-hover ">
        <tbody>
            <tr class="heading1">
                <th width="35%" class="table-header">Plan</th>
                <!--th class="table-header">Description</th-->
                <th class="table-header">Number Of Products Supported</th>
                <th class="table-header">Price</th>
                <th class="table-header">Status</th>
                <th class="table-header">Operations</th>
            </tr>
            <?php
            if (!empty(PageContext::$response->postedData)) {
                $edit_params = PageContext::$response->postedData;
            }
                
            if (!empty(PageContext::$response->pageContents)) {
                foreach (PageContext::$response->pageContents as $row) { 
                    if ($row->nServiceId == PageContext::$response->planId && empty(PageContext::$response->postedData)) {
                        $edit_params['vServiceName'] = $row->vServiceName;
                        $edit_params['vServiceDescription'] = trim($row->vServiceDescription);
                        $edit_params['price'] = $row->price;
//                        $edit_params['trasaction_fee'] = $row->trasaction_fee;
//                        $edit_params['savings'] = $row->savings;
//                        $edit_params['third_party_transaction'] = $row->third_party_transaction;
//                        $edit_params['makeready_payments'] = $row->makeready_payments;
//                        $edit_params['permonth_price'] = $row->permonth_price;
//                        $edit_params['nStatus'] = $row->nStatus;
//                        $edit_params['dropship_fee'] = $row->dropship_fee;
                        $edit_params['nServiceId'] = $row->nServiceId;
                        $edit_params['vBillingInterval'] = $row->vBillingInterval;
                        $edit_params['nBillingDuration'] = $row->nBillingDuration;
                        $edit_params['nQty'] = $row->nQty;
                        $edit_params['vType'] = $row->vType;
                    }
                    ?>
                    <tr>
                        <td>
                            <a  href="javascript:void(0)" class="planFeatures" name= "<?php echo $row->nServiceId;?>"><?php echo $row->vServiceName; ?></a>
                        </td>
                        <!--td>
                            <?php
                            /*
                            if (strlen($row->vServiceDescription) > 150) {
                                echo substr($row->vServiceDescription, 0, 150) . '..';
                            } else {
                                echo $row->vServiceDescription;
                            } */
                            ?>
                        </td-->
                        <td><?php echo $row->nQty; ?></td>
                        <td><?php echo number_format($row->price,2); ?></td>
                        <td>
                            <?php
                            $status = ($row->nStatus == 1) ? 'Active' : 'Inactive';
                            $statusClass = ($row->nStatus == 1) ? 'btn-success' : 'btn-danger';
                            ?>
                            <div id="jqStatusContainer_<?php echo $row->nServiceId; ?>">
                            <a class="jqPlanStatusChange btn btn-mini <?php echo $statusClass; ?>" name="<?php echo $row->nStatus?>" planId="<?php echo $row->nServiceId;?>" planType="<?php echo $row->vType;?>" href="#"><?php echo $status;?></a>
                            </div>
                        </td>
                        <td>
                            <a href="#<?php echo $row->nServiceId; ?>" title="View" data-toggle="modal" >View</a>&nbsp;
                            <a href="<?php echo BASE_URL; ?>cms?section=plans&section_action=edit_plan&plan_id=<?php echo $row->nServiceId; ?>#addForm" title="Edit" >Edit</a>&nbsp;
                            <?php if($row->vType!='free'){ ?>
                            <a class="jqDeletePlan" href="<?php echo BASE_URL; ?>cms?section=plans&section_action=delete_plan&plan_id=<?php echo $row->nServiceId; ?>">Delete</a>
                            <?php }
                            //$statusAction = ($row->nStatus == 1) ? 'deactivate' : 'activate';
                            ?>
                        </td>
                    </tr>

                    <?php
                }
            } else {
                ?>
                <tr>
                    <td align="center" colspan="6">No Records Found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <form id="frmAddPlanSelect" action="<?php echo BASE_URL; ?>cms?section=plans" method="post" >
        <input type="hidden" name="addPlan" id="addPlan" value="addPlan" />
        <div class="">
            <div class="section_list_operations ull-left pagination">
                <a class="addrecord btn btn-info" href="<?php echo BASE_URL; ?>cms?section=plans&section_action=add_plan#addForm">Add Record</a>
                <a class="addrecord btn btn-info" href="<?php echo BASE_URL; ?>cms?section=product_service_features">Manage Plan Features</a>
            </div>
            <!-- <div class="pagination pagination-right ull-right">                    
            </div> -->
            <div style="clear:both"></div>
        </div>
    </form>

    <?php if (PageContext::$response->showAddForm) { ?>
        <div id="addForm" class="listForm">
            <form id="frmAddPlan" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=plans" method="POST" name="frmAddPlan">

                <legend>
                    <?php if ($edit_params['nServiceId'] == '') { ?>
                        Add Plan
                    <?php } else { ?>
                        Edit Plan
                    <?php } ?>
                </legend>

                <div class="control-group">
                    <input type="hidden" name="catId" id="catId" value="1" />
                    <input type="hidden" name="planId" id="planId" value="<?php echo $edit_params['nServiceId']; ?>" />
                    <input type="hidden" name="planType" id="planType" value="<?php echo ($edit_params['vType'])?$edit_params['vType']:'paid'; ?>" />
                </div>

                <div class="control-group">
                    <label class="control-label" for="serviceName">Plan Name</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="serviceName" id="serviceName" value="<?php echo $edit_params['vServiceName']; ?>" validate="required:true" <?php echo ($edit_params['vType']=='free')?'readonly':''; ?> /> </div>
                        <div style="float: left;"> <span class="mandatory">*</span></div>                 
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="serviceDescription">Plan Description</label>
                    <div class="controls"><div style="float: left;"><textarea name="serviceDescription" id="serviceDescription"><?php  echo trim($edit_params['vServiceDescription']); ?></textarea></div>
                        <div style="float: left;"><span class="mandatory">*</span></div>
                    </div>
                </div>
                 <div class="control-group">
                    <label class="control-label" for="nQty">No of Products</label>
                    <div class="controls"><div style="float: left;"><input type="text" name="nQty" id="nQty" value="<?php  echo trim($edit_params['nQty']); ?>" ></div>
                        <div style="float: left;"><span class="mandatory">*</span></div>
                    </div>
                </div>
               

<!--                <div class="control-group">
                    <label class="control-label" for="trasaction_fee">Transaction Fees</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="trasaction_fee" id="trasaction_fee" value="<?php echo $edit_params['trasaction_fee']; ?>" placeholder="2.59% + $0.30"/></div>
                        <div style="float: left;"><span class="mandatory"></span></div>
                    </div>
                </div>-->
<!--                <div class="control-group">
                    <label class="control-label" for="savings">Monthly Savings</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="savings" id="savings" value="<?php echo $edit_params['savings']; ?>" placeholder="100.00"/></div>
                        <div style="float: left;"><span class="mandatory"></span></div>
                    </div>
                </div>-->
<!--<div class="control-group">
                    <label class="control-label" for="third_party_transaction">Third Party Transaction Fee</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="third_party_transaction" id="third_party_transaction" value="<?php echo $edit_params['third_party_transaction']; ?>" placeholder="0.29"/></div>
                        <div style="float: left;"><span class="mandatory"></span></div>
                    </div>
                </div>-->
                
<!--                <div class="control-group">
                    <label class="control-label" for="makeready_payments">MakeReady Payments</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="makeready_payments" id="makeready_payments" value="<?php echo $edit_params['makeready_payments']; ?>" placeholder="None"/></div>
                        <div style="float: left;"><span class="mandatory"></span></div>
                    </div>
                </div>-->
                
                <div class="control-group">
                    <label class="control-label" for="servicePrice">Price&nbsp;(&nbsp;<?php echo CURRENCY_SYMBOL; ?>)</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="servicePrice" id="servicePrice" value="<?php echo $edit_params['price']; ?>" /></div>
                        <div style="float: left;"><span class="mandatory">*</span></div>
                    </div>
                </div>
<!--<div class="control-group">
                    <label class="control-label" for="servicePrice">Per Month Price&nbsp;(&nbsp;<?php echo CURRENCY_SYMBOL; ?>)</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="permonth_price" id="servicePrice" value="<?php echo $edit_params['permonth_price']; ?>" /></div>
                        <div style="float: left;"><span class="mandatory">*</span></div>
                    </div>
                </div>-->
                
<!--                <div class="control-group">
                    <label class="control-label" for="servicePrice">DropShip Fee per Month&nbsp;(&nbsp;<?php echo CURRENCY_SYMBOL; ?>)</label>
                    <div class="controls">
                        <div style="float: left;"><input type="text" name="dropship_fee" id="servicePrice" value="<?php echo $edit_params['dropship_fee']; ?>" /></div>
                        <div style="float: left;"><span class="mandatory">*</span></div>
                    </div>
                </div>-->
                
                <div class="control-group">
                    <label class="control-label" for="billingType">Billing Type</label>
                    <div class="controls">   
                        <div style="float: left;">
                        <input type="radio" value="M" id="billingInterval" name="billingInterval" <?php if ($edit_params['vBillingInterval'] == 'M') { ?>checked<?php } ?> <?php if ($edit_params['vBillingInterval'] == '') { ?>checked<?php } ?>  /> Day(s)<br>
                        <input type="radio" value="Y" id="billingInterval" name="billingInterval" <?php if ($edit_params['vBillingInterval'] == 'Y') { ?>checked<?php } ?> /> Year(s)<br>
                        <input type="radio" value="L" id="billingInterval" name="billingInterval" <?php if ($edit_params['vBillingInterval'] == 'L') { ?>checked<?php } ?> /> One-time
                        <p><p/>
                        
                        <input type="text" name="billingDuration" id="billingDuration" value="<?php echo $edit_params['nBillingDuration']; ?>" maxlength="6"<?php echo $durationScaleW; ?>></div>
                        <div style="float: left;"><span class="mandatory">*</span>
                            <br>
                            <br>
                            <br>
                            <br>
                            <a href="#" class="tooltiplink" data-original-title="Please enter a numeric value for billing duration. Eg: The plan period would be 30 days, if you choose billing type as Day(s) and billing duration as 30."><span class="help-icon"><img src="<?php echo BASE_URL?>modules/cms/images/help_icon.png"></span></a>

                        </div>
                        
                    </div>
                </div>

                <!--div class="control-group">
                    <label class="control-label" for="servicePrice">Status</label>
                    <div class="controls">
                        <select name="planStatus" id="planStatus">
                            <option value="1" <?php //if ($edit_params['nStatus'] == "1") { ?>selected<?php //} ?> >Active</option>
                            <option value="0" <?php //if ($edit_params['nStatus'] == "0") { ?>selected<?php //} ?>>Inactive</option>
                        </select>
                    </div>
                </div-->

                <div class="controls">
                    <input class="submitButton btn" type="submit" name="submit" value="Save">
                    <!--<input class="cancelButton btn" type="button" name="cancel" value="Cancel" onclick="$('#addForm').hide()">-->
                    <a href="<?php echo BASE_URL; ?>cms?section=plans"><input class="cancelButton btn" type="button" name="cancel" value="Cancel" onclick="$('#addForm').hide()"></a>
                </div>

            </form>
        </div>
    <?php } ?>

</div>

<?php
if (!empty(PageContext::$response->pageContents)) {
    foreach (PageContext::$response->pageContents as $row) { 
        ?>
        <div id="<?php echo $row->nServiceId; ?>" class="modal hide fade in" style="display: none; ">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">x</a>
                <h3> Plan:<?php echo $row->vServiceName; ?></h3>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-hover table-condensed">
                    <tbody>
                        <tr>
                            <td class="span3">Plan</td>
                            <td class="span6"><?php echo $row->vServiceName; ?></td>
                        </tr>
                        <tr>
                            <td class="span3">Description</td>
                            <td class="span6"><?php echo $row->vServiceDescription; ?></td>
                        </tr>
                        <tr>
                            <td class="span3">Number of Products Supported</td>
                            <td class="span6"><?php echo $row->nQty; ?></td>
                        </tr>
                        <tr>
                            <td class="span3">Price</td>
                            <td class="span6"><?php echo CURRENCY_SYMBOL . ' ' . $row->price; ?></td>
                        </tr>
                        <tr>
                            <td class="span3">Billing Interval</td>
                            <td class="span6">
                                <?php
                                switch ($row->vBillingInterval) {
                                    case 'M':
                                        echo 'Monthly';
                                        $timeFrame = 'Days';
                                        break;
                                    case 'Y':
                                        echo 'Yearly';
                                        $timeFrame = 'Years';
                                        break;
                                    case 'L':
                                        echo 'One-time';
                                        break;
                                    default:
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="span3">Billing Duration</td>
                            <td class="span6">
                                <?php
                                if ($row->vBillingInterval == 'L') {
                                    echo 'N.A';
                                } else {
                                    echo $row->nBillingDuration . ' ' . $timeFrame;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="span3">Status</td>
                            <td class="span6">
                                <?php
                                echo $row->nStatus == 1 ? 'Active' : 'Inactive';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left">
                                <b>Features</b>
                            </td>
                        </tr>
                        <?php
                        if(!empty(PageContext::$response->serviceFeatures)){
                            foreach(PageContext::$response->serviceFeatures as $serviceFeatures){
                                ?>
                                <tr>
                                    <td class="span3">
                                        <?php
                                            echo $serviceFeatures->tFeatureName;
                                        ?>
                                    </td>
                                    <td class="span6">
                                        <?php
                                            echo $serviceFeatures->tValue;
                                        ?>
                                    </td>
                                </tr>
                                <?php                                        
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <a class="btn" data-dismiss="modal" href="#">Close</a>
            </div>
        </div>
        <?php
    }
}
?>