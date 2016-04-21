<div class="modal" id="viewScreenshotImage" style="width: 600px!important;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  id="jqCloseLinkUserDetails">×</button>
        <h4 id="myModalLabel" style="text-align: left;">Screenshot : <?php echo $screenType = (PageContext::$response->screenDetails->eType=='User') ? 'User Panel Screen' : 'Admin Panel Screen'; ?> </h4>
    </div>
    <div class="modal-body" style="">
        <table class="table table-bordered table-hover table-condensed">
            <tbody>
                <?php
                if(!empty(PageContext::$response->screenDetails)) {
                    $imgName =PageContext::$response->screenDetails->file_path;
                ?>
                    <tr>
                        <td class="span3" style="text-align: center;">
                            <?php if(file_exists(FILE_UPLOAD_DIR.'/'.$imgName)) { ?>
                            <img src="<?php echo IMAGE_FILE_URL.'/'.$imgName;?>" width="400" height="231" />
                            <?php } ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"  id="jqCloseUserDetails">Close</button>
        </div>

    </div>
</div>