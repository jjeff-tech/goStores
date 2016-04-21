<div class="form_container">
    <div class="form_top"><?php echo $this->pageTitle;?></div>
    <div class="form_bgr">
        <?php

        if($this->action=="edit" || $this->action=="add")// add plan details
        {            
            ?>
        <?php PageContext::renderPostAction('errormessage','index');?>
        <form id="frmPlanPurchaseCategory" action="<?php echo BASE_URL; ?>admin/plan/purchasecategory<?php echo $this->action=='add'?'/'.$this->action:'/'.$this->action.'/'.$this->id;?>" method="post" onsubmit="return validatePlanPurchaseCategory()">
            <input type="hidden" name="id" value="<?php echo $this->dataArr->nSCatId ?>">
            <input type="hidden" name="action" value="<?php echo $this->action ?>">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                <tr>
                    <th align="left" valign="top" width="25%">Service Category  <span class="mandred">*</span></th>
                    <td align="left" valign="top"><input id="category" name="category" validate="required:true" type="text" class="inp-form" value="<?php echo $_POST['category']?$_POST['category']:stripslashes($this->dataArr->vCategory)?>" /> </td>
                </tr>
                <tr>
                    <th align="left" valign="top">Service Category Description  <span class="mandred">*</span></th>
                    <td align="left" valign="top"><textarea name="description" id="description"  validate="required:true" cols="30" rows="5"><?php echo $_POST['description']?$_POST['description']:stripslashes($this->dataArr->vDescription)?></textarea></td>
                </tr>
                <tr>
                    <th align="left" valign="top">Service Category Input Type </th>
                    <td align="left" valign="top">
                        <?php echo $this->radio('inputType', 'C', $this->dataArr->vInputType, NULL, '&nbsp;', NULL); ?> Checkbox&nbsp;&nbsp;
                        <?php echo $this->radio('inputType', 'R', $this->dataArr->vInputType, NULL, '&nbsp;', NULL); ?> Radio
                    </td>
                </tr>
                <tr>
                    <th><div class="cancel"><a href="<?php echo BASE_URL;?>admin/plan/purchasecategory">Cancel</a></div></th>
                    <td valign="top"><input type="submit" name="btnAdd"  value="<?php echo $this->buttonValue;?> " /></td>
                </tr>
          </table>
        </form>
   

            <?php
        } else {
            ?>
        
		
		<div class="r_float">
       	    <form class="cmxform" id="frmRole" action="<?php echo BASE_URL; ?>admin/plan/purchasecategory/" method="post" onsubmit="return validateListSearch()">

	   <div class="l_float">
               <input type="hidden" name="page" id="page" value="<?php echo $this->pageCount;?>">
            <input type="hidden" name="action" value="search">
            <div class="admin_search_container">
                <input name="search" id="search" type="text" class="search_box" value="<?php echo $this->txtSearch; ?>"  placeholder="Search by Service Category">
                <input name="btnSearch" type="submit" class="button_orange" value="Search">&nbsp;&nbsp;
                  </div>
				  </div>
				  <div class="l_float">
            <div class="addnew"><a href="<?php echo BASE_URL; ?>admin/plan/purchasecategory/add">Add Service Category</a></div>
			</div>
			
        </form>
		
		<div class="clear"></div>
		
        </div>
		
		

        <br><br><br>
        <?php if(!empty($this->message)) { ?>
        <div class="green_box"><?php echo $this->message; ?></div>
        <?php } ?>
        <div style="width: 100%;float: left;" align="left">
<?php PageContext::renderPostAction($this->messageFunction,'index');
$this->messageFunction ='';?>
              </div>
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="6%" align="center">Sl No.</td>
                <td width="35%" align="left">Service Category<a href="<?php echo BASE_URL;?>admin/plan/purchasecategory/<?php echo $this->serviceCategorySortAction;?>/x/<?php echo $this->txtSearch?$this->txtSearch:'x';?>/<?php echo $this->pageCount;?>" id="<?php echo BASE_URL;?>admin/plan/purchasecategory/<?php echo $this->serviceCategorySortAction;?>"  class="<?php echo $this->serviceCategorySortStyle;?>"></a></td>
                <td width="23%" align="left">Created On</td>
                <td width="24%" align="left">Actions</td>
            </tr>
                <?php
                if(!empty($this->pageContents)) {
                    $i=$this->pageInfo['base'];
                    foreach($this->pageContents as $row) {
                        $i++;
                        $className=($i%2) ? 'column2' : 'column1';
                        ?>
            <tr class="<?php echo $className ?>" id="item_<?php echo $row->nId?>">
                <td align="left"><?php echo $i; ?></td>
                <td align="left"><?php echo $row->vCategory; ?></td>
                <td align="left"><?php echo Utils::formatDate($row->dCreatedOn." 00:00:00");?></td>
                <td align="left"><span class="edit"><a href="<?php echo BASE_URL; ?>admin/plan/purchasecategory/edit/<?php echo $row->nSCatId?>" title="Edit" >Edit</a></span> |
                    <span class="delete"><a href="<?php echo BASE_URL; ?>admin/plan/purchasecategory/delete/<?php echo $row->nSCatId?>"  title="Delete" onClick="return confirm('Are you sure want to delete the Service Category ?');">Delete</a></span> |
                                <?php
                                //deleteListItem(idItem, actionUrl, itemLabel)
                                $status = ($row->nStatus == 1) ? 'Deactivate' : 'Activate';
                                $statusAction = ($row->nStatus == 1) ? 'deactivate' : 'activate';
                                ?>
                    <span class="delete"> <a href="<?php echo BASE_URL; ?>admin/plan/purchasecategory/<?php echo $statusAction ?>/<?php echo $row->nSCatId?>/<?php echo $this->pageInfo['page']; ?>" onclick="return confirmBox('<?php echo $statusAction ?>', 'Service Category')" title="<?php echo $status ?>" ><?php echo $status ?></a></span>
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
            <?php if(!empty($this->pageContents)&& $this->pageInfo['maxPages']>1) { echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/plan/purchasecategory/'.($this->action?$this->action:'x').'/x/'.($this->txtSearch?$this->txtSearch.'/':'x/')); } ?>
            </div>
        </div>


        <?php
        }
        ?>
  
    <div class="form_bottom"></div>

</div>
</div>