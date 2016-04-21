<div class="form_container">
    <div class="form_top">Plan Purchase Category Details Management</div>
    <div class="form_bgr">
        <?php

        if($this->action=="edit" || $this->action=="add")// add plan details
        {
            ?>
        <form id="frmPlan" action="<?php echo BASE_URL; ?>admin/plan/purchasecategorydetails/" method="post" onsubmit="return validatePlanPurchaseCategoryDetail()">
            <input type="hidden" name="id" value="<?php echo $this->dataArr->nId ?>">
            <input type="hidden" name="action" value="<?php echo $this->action ?>">
            <table border="0" class="formstyle" align="center" cellpadding="0" cellspacing="0"  id="id-form">
                <tr align="right" valign="middle">
                  <th height="37" colspan="2"><div class="back"><a href="javascript:history.go(-1)">Back</a></div></th>
                </tr>
                <tr>
                    <th width="142" height="37" valign="top">Plan Pruchase Category : <span class="mandred">*</span></th>
                    <td width="517"><?php echo $this->select('category', $this->categoryArr, $this->dataArr->nPlanPurchaseCategoryId, NULL,NULL,'category'); ?></td>
                </tr>
                <tr>
                    <th width="142" height="37" valign="top">Detail Description : <span class="mandred">*</span></th>
                    <td width="517"><textarea name="description" id="description" cols="40" rows="5"><?php echo stripslashes($this->dataArr->vDescription)?></textarea></td>
                </tr>
                <tr>
                    <th width="142" height="37" valign="top">Amount : <span class="mandred">*</span></th>
                    <td width="517"><input id="amount" name="amount" type="text" class="inp-form" maxlength="6" value="<?php echo stripslashes($this->dataArr->nAmount)?>" /> </td>
                </tr>
                <tr>
                    <th width="142" height="37" valign="top">Is Mandatory : </th>
                    <td width="517">              
                        <?php
                            $detailMandatory = (isset($this->dataArr->nIsMandatory) && $this->dataArr->nIsMandatory == 1) ? 1 : NULL;
                            echo $this->checkbox('isMandatory', 1, $detailMandatory, '', 'isMandatory');
                        ?>
                    </td>
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
        <form class="cmxform" id="frmRole" action="<?php echo BASE_URL; ?>admin/plan/purchasecategorydetails/" method="post" onsubmit="return validateListSearch()">
            <input type="hidden" name="action" value="search">
            <div class="search_container"><ul><li><input name="search" type="text" class="search_box" value="<?php echo $this->txtSearch; ?>"></li><li><input name="btnSearch" type="submit" class="button_orange" value="Search"></li>
                    <li></li>
                </ul></div>
            <div class="addnew"><a href="<?php echo BASE_URL; ?>admin/plan/purchasecategorydetails/add">Add New</a></div>
        </form>

        <br><br><br>
        <?php if(!empty($this->message)) { ?>
        <div class="green_box"><?php echo $this->message; ?></div>
        <?php } ?>
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="8%" align="center">Sl No.</td>
                <td width="35%">Description</td>
                <td width="35%">Amount</td>
                <td width="23%" align="center">Options</td>
            </tr>
                <?php
                if(!empty($this->pageContents)) {
                    $i=$this->pageInfo['base'];
                    foreach($this->pageContents as $row) {
                        $i++;
                        $className=($i%2) ? 'column1' : 'column2';
                        ?>
            <tr class="<?php echo $className ?>" id="item_<?php echo $row->nId?>">
                <td align="center"><?php echo $i; ?></td>
                <td><?php echo $row->vDescription; ?></td>
                <td><?php $amount =(!empty($row->nAmount)) ? $row->nAmount : 0; echo number_format($amount, 2, '.', ''); ?></td>
                <td align="center"><span class="edit"><a href="<?php echo BASE_URL; ?>admin/plan/purchasecategorydetails/edit/<?php echo $row->nId?>" title="Edit" >Edit</a></span>&nbsp;/&nbsp;
                    <span class="delete"><a href="javascript:void(0);" onclick="return deleteListItem('<?php echo $row->nId?>','<?php echo BASE_URL; ?>admin/plan/droppurchasecategorydetail','Plan Purchase Category Detail')" title="Delete" >Delete</a></span>&nbsp;/&nbsp;
                                <?php
                                $status = ($row->nStatus == 1) ? 'Deactivate' : 'Activate';
                                $statusAction = ($row->nStatus == 1) ? 'deactivate' : 'activate';
                                ?>
                    <span class="delete"> <a href="<?php echo BASE_URL; ?>admin/plan/purchasecategorydetails/<?php echo $statusAction ?>/<?php echo $row->nId?>/<?php echo $this->pageInfo['page']; ?>" onclick="confirmBox('<?php echo $statusAction ?>', 'Detail')" title="<?php echo $status ?>" ><?php echo $status ?></a></span>
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
            <?php if(!empty($this->pageContents)) { echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/plan/purchasecategorydetails/x/x/'); } ?>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="form_bottom"></div>

</div>