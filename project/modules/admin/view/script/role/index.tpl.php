<div class="form_container">
    <div class="form_top"><?php echo $this->pageTitle; ?></div>
    <div class="form_bgr">
        <?php

        if($this->action=="edit" || $this->action=="add")// add plan details
        {
            ?>
        <?php PageContext::renderPostAction('errormessage','index');?>
        <form class="cmxform" id="frmRole" action="<?php echo ConfigUrl::base(); ?>role/index/<?php echo $this->action;?>" method="post" onsubmit="return validateRole()">
            <input type="hidden" name="id" value="<?php echo $this->role['nRid'] ?>">
            <input type="hidden" name="action" value="<?php echo $this->action ?>">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="formstyle">

                <tr>
                    <th align="left" valign="top" width="25%">Role  <span class="mandred">*</span></th>
                    <td align="left" valign="top"><input id="role" name="role" type="text"  value="<?php echo $_POST['role']?$_POST['role']:stripslashes($this->role['vRoleName']);?>" />
                    <div id="role_error" class="error"></div></td>
                </tr>
                <tr>
                    <th align="left" valign="top">Module Access <span class="mandred">*</span> </th>
                    <td align="left" valign="top">
                            <?php
                            if($_POST['moduleAccess'])
                                Logger::info($_POST['moduleAccess']);
                            //echo '<ul>';
                            $selectedArr = array();
                            if(!empty($this->role['vPermission'])) {
                                $selectedArr = explode(",", $this->role['vPermission']);
                            }
                            $i = 1;
                            foreach($this->moduleArr as $module) {
                                $selectedModule = NULL;
                                
                               if(!empty($selectedArr)||!empty($_POST['moduleAccess'])) {
                                    if($_POST['moduleAccess']){
                                        $selectedModule = (in_array($module->nMId,$_POST['moduleAccess'])) ? $module->nMId : NULL;
                                    }else
                                        $selectedModule = (in_array($module->nMId, $selectedArr)) ? $module->nMId : NULL;
                                }
                              ?>
         
                            <?php    echo $this->checkbox('moduleAccess[]', $module->nMId, $selectedModule, $class = '', 'moduleAccess'.$module->nMId).'&nbsp;&nbsp;'.$module->vModuleName.($i==1?' <label id="module_error" class="error"></label>':'');
                          
                            echo '<br>';
                                $i++;
                            } // End Foreach
                            //echo '</ul>';
                            ?>
                        
                    </td>
                </tr>

                <tr>
                    <th align="right" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/role">Cancel</a></div></th>
                    <td align="left" valign="top">
                        <input type="submit" name="btnAdd"  value="<?php echo $this->buttonValue;?>"  />		</td>
                </tr>
            </table>
        </form>
            <?php
        } else {
            ?>
        
		<div class="r_float">
        <form class="cmxform" id="frmSearch" action="<?php echo ConfigUrl::base(); ?>role/index/" method="post" >
		<div class="l_float">
            <input type="hidden" name="page" id="page" value="<?php echo $this->pageCount;?>">
            <input type="hidden" name="action" value="search">
            <div class="admin_search_container">
                <input name="search" id="search" type="text" class="search_box" placeholder="Search by Role" value="<?php echo $this->txtSearch; ?>">
                <input name="btnSearch" type="submit" class="button_orange" value="Search">&nbsp;&nbsp;
                    
            </div>
		</div>
			
			<div class="l_float">
            <div class="addnew"><a href="<?php echo ConfigUrl::base(); ?>role/index/add">Add Role</a></div>
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
                <td width="35%" align="left">Role<a href="javascript:void(0);" id="<?php echo BASE_URL;?>admin/role/index/<?php echo $this->rolenameSortAction;?>/x"  class="<?php echo $this->rolenameSortStyle;?>"></a></td>
                <td width="23%" align="left">Created On</td>
                <td width="24%" align="left">Actions</td>
            </tr>
                <?php
                $i=0;
                if(!empty($this->pageContents)) {
                $i=$this->pageInfo['base'];
                foreach($this->pageContents as $row) {
                    $i++;
                    $className=($i%2) ? 'column2' : 'column1';
                    ?>
            <tr class="<?php echo $className ?>">
                <td align="left"><?php echo $i; ?></td>
                <td align="left"><?php echo $row->vRoleName; ?></td>
                <td align="left"><?php echo Utils::formatDate($row->dCreatedOn);?></td>
                <td align="left"><span class="edit"><a href="<?php echo ConfigUrl::base(); ?>role/index/edit/<?php echo $row->nRid?>" title="Edit" >Edit</a></span>
                            <?php
                            if($row->nRid!=1){
                            $status = ($row->nStatus == 1) ? ' Deactivate' : ' Activate';
                            $statusAction = ($row->nStatus == 1) ? 'deactivate' : 'activate';
                            
                            
                                   // $statusAction ='';
                                   // $status ='';
                            
                            ?>
                    |<span class="delete"> <a href="<?php echo ConfigUrl::base(); ?>role/index/<?php echo $statusAction ?>/<?php echo $row->nRid?>" title="<?php echo $status ?>"  onClick="return confirm('Are you sure want to <?php echo strtolower($status);?> the role ?');"><?php echo $status ?></a></span>
                <?php }?>
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
                        echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/role/index/'.($this->action?$this->action:'x').'/x/'.($this->txtSearch?$this->txtSearch.'/':'x/'));
                    } ?>
            </div>
        </div>


            <?php
        }
        ?>
    </div>
    <div class="form_bottom"></div>

</div>
