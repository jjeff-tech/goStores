
<div class="modal" id="planFeatures" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  id="jqCloseLinkUserDetails">×</button>
        <h4 id="myModalLabel" style="text-align: left;">Plan Features : <?php echo PageContext::$response->dataArr[0]->vServiceName;  ?>  </h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered table-hover table-condensed">
            <tbody>
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
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"  id="jqCloseUserDetails">Close</button>
        </div>

    </div>
</div>