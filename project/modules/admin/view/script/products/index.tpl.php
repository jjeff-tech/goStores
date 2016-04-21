<div class="form_container">
    <div class="form_top">Products</div>
    <div class="form_bgr">

<div class="r_float">
    <!-- Search Form -->
    <div class="l_float"><form class="cmxform" id="frmSearchProduct" action="<?php echo BASE_URL; ?>admin/products/index/" method="post" >
            <input type="hidden" name="action" value="search">
            <!-- <input type="hidden" name="page" id="page" value="<?php //echo $this->pageCount;?>"> -->
            <div class="admin_search_container" ><input name="search" id="search" type="text" class="search_box" placeholder="Search by Product" value="<?php echo $this->txtSearch ; ?>">
                <input name="btnSearch" type="submit" class="button_orange" value="Search">&nbsp;&nbsp;</div>
        </form>
    </div>
    <!-- End Search Form -->
    <div class="l_float">
        <div class="addnew" ><a href="<?php echo BASE_URL; ?>admin/products/addproduct">Add Product</a></div>
    </div>
    <div class="clear"></div>
</div>
<br><br><br>
        <?php if(!empty($this->message)) { ?>
        <div class="green_box"><?php echo $this->message; ?></div>
        <?php } ?>
        <?php PageContext::renderPostAction('successmessage','index');?>
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="6%" align="center">Sl No.</td>
                <td width="35%" align="left">Product</td>
                <td width="40%" align="left">Last Modified On</td>
                <td width="18%" align="left">Actions</td>
            </tr>
                <?php
                if(!empty($this->pageContents)) {
                    $i=$this->pageInfo['base'];
                    foreach($this->pageContents as $row) {
                        $i++;
                        $className=($i%2) ? 'column2' : 'column1';
                        ?>
            <tr class="<?php echo $className ?>" id="item_<?php echo $row->nPId?>">
                <td align="left"><?php echo $i; ?></td>
                <td align="left"><?php echo $row->vPName; ?></td>
                <td align="left"><?php echo Utils::formatDate($row->dLastUpdated);?></td>
                <td align="left"><span class="edit"><a href="<?php echo BASE_URL; ?>admin/products/editproduct/<?php echo $row->nPId?>" title="Edit" >Edit</a></span>&nbsp;|&nbsp;
                    <span class="delete"><a href="javascript:void(0);" onclick="return deleteProduct('<?php echo $row->nPId?>','<?php echo BASE_URL; ?>admin/products/dropproduct','Product')" title="Delete" >Delete</a></span>&nbsp;<!--/&nbsp;-->
                                <?php
                                $status = ($row->nStatus == 1) ? 'Deactivate' : 'Activate';
                                $statusAction = ($row->nStatus == 1) ? 'deactivate' : 'activate';
                                ?>
                    <!--<span class="delete"> <a href="<?php echo ConfigUrl::base(); ?>products/index/<?php //echo $statusAction ?>/<?php //echo $row->nPId?>/<?php //echo $this->pageInfo['page']; ?>" onclick="confirmBox('<?php //echo $statusAction ?>', 'Plan')" title="<?php //echo $status ?>" ><?php //echo $status ?></a></span>-->
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
            <?php if(!empty($this->pageContents) && $this->pageInfo['maxPages']>1) { echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/products/index/x/'); } ?>
            </div>
        </div>


        
    </div>
    <div class="form_bottom"></div>

</div>