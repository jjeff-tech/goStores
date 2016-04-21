<div class="form_container">
    <div class="form_top">Plan Package Management</div>
    <div class="form_bgr">
        <?php

        if($this->action=="edit" || $this->action=="add")// add plan details
        {
            ?>
        <form id="frmPlan" action="<?php echo BASE_URL; ?>admin/plan/packages/" method="post" onsubmit="return validatePlanPackage()">
            <input type="hidden" name="id" value="<?php echo $this->dataArr->nPPId ?>">
            <input type="hidden" name="action" value="<?php echo $this->action ?>">
            <table border="0" class="formstyle" align="center" cellpadding="0" cellspacing="0"  id="id-form">
                <tr align="right" valign="middle">
                  <th height="37" colspan="2"><div class="back"><a href="javascript:history.go(-1)">Back</a></div></th>
                </tr>
                <tr>
                    <th width="142" height="37" valign="top">Plan : <span class="mandred">*</span></th>
                    <td width="517"><?php echo $this->select('plan', $this->categoryArr, $this->dataArr->nPlanPlanId, NULL,NULL,'plan'); ?></td>
                </tr>
                <tr>
                    <th width="142" height="37" valign="top">Detail Description : <span class="mandred">*</span></th>
                    <td width="517"><textarea name="description" id="description" cols="40" rows="5"><?php echo stripslashes($this->dataArr->vDescription)?></textarea></td>
                </tr>
                <tr>
                    <th width="142" height="37" valign="top">Amount : <span class="mandred">*</span></th>
                    <td width="517"><input id="amount" name="amount" type="text" class="inp-form" maxlength="6" value="<?php echo stripslashes($this->dataArr->nPlanAmount)?>" /> </td>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <td valign="top"><input type="submit" name="btnAdd"  value="<?php echo $this->buttonLabel;?> Plan Purchase Category" /></td>
                </tr>
          </table>
        </form>


            <?php
        } else {
            ?>
        <form class="cmxform" id="frmRole" action="<?php echo BASE_URL; ?>admin/plan/packages/" method="post" onsubmit="return validateListSearch()">
            <input type="hidden" name="action" value="search">
            <div class="search_container"><ul><li><input name="search" type="text" class="search_box" value="<?php echo $this->txtSearch; ?>"></li><li><input name="btnSearch" type="submit" class="button_orange" value="Search"></li>
                    <li></li>
                </ul></div>
            <div class="addnew"><a href="<?php echo BASE_URL; ?>admin/plan/packages/add">Add New</a></div>
        </form>

        <br><br><br>
        <?php if(!empty($this->message)) { ?>
        <div class="green_box"><?php echo $this->message; ?></div>
        <?php } ?>
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="7%" align="center">Sl No.</td>
                <td width="33%">Description</td>
                <td width="35%">Amount</td>
                <td width="25%" align="center">Options</td>
            </tr>
                <?php
                if(!empty($this->pageContents)) {
                    $i=$this->pageInfo['base'];
                    foreach($this->pageContents as $row) {
                        $i++;
                        $className=($i%2) ? 'column1' : 'column2';
                        ?>
            <tr class="<?php echo $className ?>" id="item_<?php echo $row->nPPId?>">
                <td align="center"><?php echo $i; ?></td>
                <td><?php echo $row->vDescription; ?></td>
                <td><?php $amount =(!empty($row->nPlanAmount)) ? $row->nPlanAmount : 0; echo number_format($amount, 2, '.', ''); ?></td>
                <td align="center"><span class="edit"><a href="<?php echo BASE_URL; ?>admin/plan/packages/edit/<?php echo $row->nPPId?>" title="Edit" >Edit</a></span>&nbsp;/&nbsp;
                    <span class="delete"><a href="javascript:void(0);" onclick="return deleteListItem('<?php echo $row->nPPId?>','<?php echo BASE_URL; ?>admin/plan/droppackages','Plan Packages')" title="Delete" >Delete</a></span>&nbsp;/&nbsp;
                                <?php
                                $status = ($row->nStatus == 1) ? 'Deactivate' : 'Activate';
                                $statusAction = ($row->nStatus == 1) ? 'deactivate' : 'activate';
                                ?>
                    <span class="delete"> <a href="<?php echo BASE_URL; ?>admin/plan/packages/<?php echo $statusAction ?>/<?php echo $row->nPPId?>/<?php echo $this->pageInfo['page']; ?>" onclick="confirmBox('<?php echo $statusAction ?>', 'Plan Package')" title="<?php echo $status ?>" ><?php echo $status ?></a></span>
                </td>
            </tr>
                        <?php
                    }
                } else {
                    ?>
            <tr class="column1">
                <td align="center" colspan="4">No Results Found</td>
            </tr>
                    <?php
                }
                ?>

        </table>

        <div class="more_entries">
            <div class="wp-pagenavi">
            <?php if(!empty($this->pageContents)) { echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/plan/packages/x/x/'); } ?>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="form_bottom"></div>

</div>