<div class="form_container">
    <div class="form_top"><?php echo $this->pageHeadLabel; ?><?php echo $this->pageTitle;?> </div>
    <div class="form_bgr">
       <?php
 if($this->editEnabled|| $this->addEnabled)// add plan details
 {

?>
        <?php PageContext::renderPostAction('errormessage');?>
        <form id="frmModule" action="<?php echo ConfigUrl::base(); ?>index/module/<?php echo $this->action=='edit'?$this->action.'/'.$this->id:$this->action;?>" method="post" >
            <input type="hidden" name="id" value="<?php echo $this->id?>">
            <input type="hidden" name="action" value="<?php echo $this->action ?>">

            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">
                <tr>
                    <th align="left" valign="top" width="25%">Module  <span class="mandred">*</span></th>
                    <td align="left" valign="top"><input id="mod_name" name="mod_name" type="text" class="inp-form" validate="required:true"  value="<?php echo $_POST['mod_name']?$_POST['mod_name']:stripslashes($this->pageContent->vModuleName)?>" /> </td>
                </tr>

                <tr>
                    <th align="left" valign="top">Description<span class="mandred">*</span></th>
                    <td align="left" valign="top"><textarea id="mod_desc" name="mod_desc" rows="5"  cols="40" validate="required:true"   ><?php echo stripslashes($this->pageContent->vDescription)?></textarea>		 </td>
                </tr>


                <tr>
                    <th align="left" valign="top">Status<span class="mandred">*</span></th>
                    <td align="left" valign="top"><select name="mod_status"  id="mod_status">
                            <option  value="1" <?php echo (($this->pageContent->nStatus==1)?'selected="selected"':''); ?> >Active </option>
                            <option  value="2" <?php echo (($this->pageContent->nStatus==2)?'selected="selected"':''); ?> >In Active </option>
                        </select></td>
                </tr>
                <tr>
                    <th align="left" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/index/module">Cancel</a></div></th>
                    <td valign="top">

                        <input type="submit" name="btnAdd"  value="<?php echo $this->buttonValue; ?>"  /></td>
                </tr>
            </table>
        </form>


            <?php
        } else {
            ?>
      
	  
	   <div class="r_float">
               <div class="l_float margin_right" >
	           <form class="cmxform" id="frmRole" action="<?php echo ConfigUrl::base(); ?>index/module/" method="post" onsubmit="return validateListSearch()">

            <input type="hidden" name="action" value="search">
            <input type="hidden" name="page" id="page" value="<?php echo $this->pageCount;?>">
            <div class="admin_search_container">
                <input name="search" id="search" type="text" class="search_box" placeholder="Search by Module" value="<?php echo $this->txtSearch; ?>">
                <input name="btnSearch" type="submit" class="button_orange" value="Search">&nbsp;&nbsp;
            </div>
             </form>
		</div>
		<!-- <div class="l_float">
        <div class="addnew"><!--<a href="<?php echo ConfigUrl::base(); ?>index/module/add">Add Module</a></div>
                </div>-->
       
		<div class="clear"></div>
        </div>
      
	  <br><br><br>
	  
	  
            <?php if(!empty($this->message)) { ?>
        <div class="green_box"><?php echo $this->message; ?></div>
                <?php } ?>
        <div align="left">
<?php PageContext::renderPostAction($this->messageFunction);
$this->messageFunction ='';?>
</div>
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="6%" align="center">Sl No.</td>
                <td width="36%" align="left">Module<a href="javascript:void(0);" id="<?php echo BASE_URL;?>admin/index/module/<?php echo $this->modulenameSortAction;?>/x"  class="<?php echo $this->modulenameSortStyle;?>"></a></td>
                <td width="40%" align="left">Last Modified</td>
                <td width="18%" align="left">Actions</td>
            </tr>
                <?php
                if(!empty($this->pageContents)) {
                    $i=$this->pageInfo['base'];
                    foreach($this->pageContents as $row) {
                        $i++;
                        $className=($i%2) ? 'column2' : 'column1';
                        ?>
            <tr class="<?php echo $className ?>" id="item_<?php echo $row->nMId?>">
                <td align="left"><?php echo $i; ?></td>
                <td align="left"><?php echo $row->vModuleName; ?></td>
                <td align="left"><?php echo Utils::formatDate($row->dLastModifiedDate);?></td>
                <td align="left"><span class="edit"><a href="<?php echo ConfigUrl::base(); ?>index/module/edit/<?php echo $row->nMId?>" title="Edit" >Edit</a></span>
                    <!--<span class="delete"><a href="javascript:void(0);" onclick="return deletePlan('<?php //echo $row->nMId?>')" title="Delete" >Delete</a></span>&nbsp;/&nbsp;-->
                                <?php
                                $status = ($row->nStatus == 1) ? 'Deactivate' : 'Activate';
                                $statusAction = ($row->nStatus == 1) ? 'deactivate' : 'activate';
                                ?>
                    |<span class="delete"> <a href="<?php echo ConfigUrl::base(); ?>index/module/<?php echo $statusAction ?>/<?php echo $row->nMId?>/<?php echo $this->pageInfo['page']; ?>" onclick="return confirmBox('<?php echo $statusAction ?>', 'Module')" title="<?php echo $status ?>" ><?php echo $status ?></a></span>
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
                    <?php if(!empty($this->pageContents) && $this->pageInfo['maxPages']>1) {
        echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/index/module/'.($this->action?$this->action:'x').'/x/'.($this->searchParam?$this->searchParam.'/':'x/'));
    } ?>
            </div>
        </div>


            <?php
}
?>
    </div>
    <div class="form_bottom"></div>

</div>
