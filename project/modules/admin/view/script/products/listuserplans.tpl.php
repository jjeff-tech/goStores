<div class="section_list_view ">

    <div class="row have-margin">

        <legend>Section : Billing &raquo; Manage User Plans &raquo; <?php echo User::getNameById(PageContext::$response->userId);?></legend>

        <?php if (!empty(PageContext::$response->message)) { ?>
        <div class="alert alert-<?php echo PageContext::$response->successError; ?>">
            <button class="close" data-dismiss="alert" type="button">x</button>
                <?php echo PageContext::$response->message; ?>
        </div>
            <?php } ?>

        <div class="input-append pull-right">
            <form class="cmxform" id="frmSearchPlan" action="<?php echo BASE_URL; ?>cms?section=user_plans&parent_id=<?php echo PageContext::$response->userId; ?>" method="post" >
                <input type="hidden" name="action" value="search">
                <input name="search" id="search" type="text" class="input-medium have-margin10" placeholder="Search" value="<?php echo PageContext::$response->txtSearch; ?>">
                <input name="btnSearch" type="submit" id="section_search_button" class="btn btn-info searchBtn" value="Search">
            </form>
        </div>
        <!-- End Search Form -->
    </div>

    <table id="tbl_activities" class="cms_listtable table table-striped table-bordered table-hover ">
        <tbody>
            <tr class="heading1">
                <th class="table-header">Plan</th>
                <th class="table-header">Billing Type</th>
                <th class="table-header">Special Cost</th>
                <th class="table-header">Price</th>
                <th class="table-header">Total</th>
                <th class="table-header">Next Billing Date</th>
                <th class="table-header">Operations</th>
            </tr>
            <?php
            if (!empty(PageContext::$response->postedData)) {
                $edit_params = PageContext::$response->postedData;
            }

            if (!empty(PageContext::$response->pageContents)) {
                foreach (PageContext::$response->pageContents as $row) {
                    if ($row->nBmId == PageContext::$response->itemId && empty(PageContext::$response->postedData)) {
                        $plan = Admincomponents::getStoreHost($row->nPLId);
                        $plan .= '</br>'.$row->vServiceName;
                        $plan .= '</br>'.$row->vServiceDescription;
                        $edit_params['plan'] = stripslashes($plan);
                        $edit_params['billingType'] = stripslashes(Admincomponents::getPlanBillingType(array('vType' => $row->vType, 'vBillingInterval' => $row->vBillingInterval, 'nBillingDuration' => $row->nBillingDuration)));
                        $edit_params['price'] = $row->nAmount;
                        $edit_params['nBmId'] = $row->nBmId;
                        
                    }
                    $specialsArr = (!empty($row->vSpecials)) ? json_decode($row->vSpecials) : array();
                    

                    ?>
            <tr>
                <td><?php echo stripslashes(Admincomponents::getStoreHost($row->nPLId)); ?></br><?php echo stripslashes($row->vServiceName); ?></br>
                            <?php
                            if (strlen($row->vServiceDescription) > 150) {
                                echo stripslashes(substr($row->vServiceDescription, 0, 150)) . '..';
                            } else {
                                echo stripslashes($row->vServiceDescription);
                            }
                            ?>
                </td>
                <td><?php echo stripslashes(Admincomponents::getPlanBillingType(array('vType' => $row->vType, 'vBillingInterval' => $row->vBillingInterval, 'nBillingDuration' => $row->nBillingDuration))); ?></td>
                <td><?php echo Utils::formatPrice($row->specialCost); ?></td>
                <td><?php echo Utils::formatPrice($row->nAmount); ?></td>
                <td><?php echo Utils::formatPrice($row->specialCost+$row->nAmount); ?></td>
                <td><?php echo Utils::formatDate($row->dDateNextBill,FALSE,'date'); ?></td>
                <td>
                    <?php
                    //$spcl_cost_url = BASE_URL.'cms?section=user_plans&parent_id='.PageContext::$response->userId.'&section_action=add_special&node_id='.$row->nBmId;
                    //echo htmlentities($spcl_cost_url);
                    /*
                    ?>
                    <a href="<?php echo htmlentities($spcl_cost_url); ?>" title="Add Special Cost" data-toggle="modal" >Add Special Cost</a>
                    <?php
                    */
                    ?>
                    <a href="<?php echo BASE_URL; ?>cms?section=user_plans&parent_id=<?php echo PageContext::$response->userId; ?>&section_action=add_special&node_id=<?php echo $row->nBmId; ?>#addForm" title="Add Special Cost"  >Add Special Cost</a>
                </td>
            </tr>
                    <?php
                }
            } else {
                ?>
            <tr>
                <td align="center" colspan="7">No Records Found</td>
            </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <!--<form id="frmAddPlanSelect" action="<?php echo BASE_URL; ?>cms?section=plans" method="post" >
        <input type="hidden" name="addPlan" id="addPlan" value="addPlan" />
        <div class="">
            <div class="section_list_operations ull-left pagination">
                <a class="addrecord btn btn-info" href="<?php echo BASE_URL; ?>cms?section=plans&section_action=add_plan">Add Record</a>
                <a class="addrecord btn btn-info" href="<?php echo BASE_URL; ?>cms?section=product_service_features">Manage Plan Features</a>
            </div>
            <!-- <div class="pagination pagination-right ull-right">
            </div> -->
            <!--<div style="clear:both"></div>
        </div>
    </form>-->

    <?php if (PageContext::$response->showAddForm) { ?>
    <div id="addForm" class="listForm">
        <form id="" class="form-horizontal" action="<?php echo BASE_URL; ?>cms?section=user_plans&parent_id=<?php echo PageContext::$response->userId; ?>&node_id=<?php echo $edit_params['nBmId']; ?>" method="POST" name="frmAddPlan">

            <legend>
                    <?php if ($edit_params['nServiceId'] == '') { ?>
                Add Specials
                        <?php } else { ?>
                Edit Specials
                        <?php } ?>
            </legend>

            <div class="control-group">
                <input type="hidden" name="node_id" id="node_id" value="<?php echo $edit_params['nBmId']; ?>" />
            </div>

            <div class="control-group">
                <label class="control-label" for="serviceName">Plan</label>
                <div class="controls">
                    <div class="well span4">
                            <?php echo $edit_params['plan']; ?>
                    </div>

                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="serviceDescription">Billing Type</label>
                <div class="controls">
                    <div class="well span4">
                            <?php echo $edit_params['billingType']; ?>
                    </div>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="servicePrice">Price</label>
                <div class="controls">
                    <div class="well span4">
                            <?php echo CURRENCY_SYMBOL.' '.Utils::formatPrice($edit_params['price']); ?>
                    </div>
                </div>
            </div>
            <?php
            $i=0;
            if(!empty($specialsArr)){
                foreach($specialsArr as $itemSp){                
            ?>
             <div class="control-group" id="specials_<?php echo $i ?>">
                <div class="input-append ">
                    <label class="control-label" for="productCount">Specials</label>
                    <div class="input-prepend " style="padding:0px 0px 0px 18px;">
                        <span class="add-on">Note</span><input class="span10" type="text" name="note[]" id="note_<?php echo $i ?>" value="<?php echo $itemSp->note; ?>" />
                    </div>
                </div>
                <div class="input-append">
                    <div class="input-prepend input-append">
                        <span class="add-on"><?php echo CURRENCY_SYMBOL; ?></span><input class="span4" type="text" name="cost[]" id="cost_<?php echo $i ?>" value="<?php echo $itemSp->cost; ?>" />
                        <span class="add-on">.00</span>
                    </div>
                </div>
                <label class="radio inline">
                <input type="radio" name="capture[<?php echo $i?>]" value="recurring"<?php echo $captute = ($itemSp->capture=='recurring')? ' checked="checked"' : '' ?>>
                Recurring capture
                </label>
                <label class="radio inline">
                    <input type="radio" name="capture[<?php echo $i?>]" value="one-time"<?php echo $captute = ($itemSp->capture=='one-time')? ' checked="checked"' : '' ?>>
                One-time capture
                <span class="mandatory">*</span>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="dropSpecials('<?php echo $i; ?>')" class="cms_list_operation">Remove</a></span>
                </label>
                <span class="text-error error-box" id="err_<?php echo $i?>"></span>
            </div>
            <?php
                $i++;
                }
            }
            ?>
            <div class="control-group" id="specials_<?php echo $i ?>">
                <div class="input-append ">
                    <label class="control-label" for="productCount">Specials</label>
                    <div class="input-prepend " style="padding:0px 0px 0px 18px;">
                        <span class="add-on">Note</span><input class="span10" type="text" name="note[]" id="note_<?php echo $i ?>" value="" />
                    </div>
                </div>
                <div class="input-append">
                    <div class="input-prepend input-append">
                        <span class="add-on"><?php echo CURRENCY_SYMBOL; ?></span><input class="span4" type="text" name="cost[]" id="cost_<?php echo $i ?>" value="" />
                        <span class="add-on">.00</span>
                    </div>
                </div>
                <label class="radio inline">
                <input type="radio" name="capture[<?php echo $i ?>]" value="recurring" checked>
                Recurring capture
                </label>
                <label class="radio inline">
                <input type="radio" name="capture[<?php echo $i?>]" value="one-time">
                One-time capture
                <span class="mandatory">*</span>
                <span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="dropSpecials('<?php echo $i; ?>')" class="cms_list_operation">Remove</a></span>                                
                </label>
                <span class="text-error error-box" id="err_<?php echo $i?>"></span>
            </div>

            <div class="controls">
                <input class="submitButton btn" type="submit" name="addSpecials" value="Save" onclick="return checkValue()">
                <input class="cancelButton btn" type="button" name="cancel" value="Cancel" onclick="$('#addForm').hide()">
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
        <h3> </h3>
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
                    <td class="span3">Products Supported</td>
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
                                        echo 'Lifetime';
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
                        if(!empty(PageContext::$response->serviceFeatures)) {
                            foreach(PageContext::$response->serviceFeatures as $serviceFeatures) {
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