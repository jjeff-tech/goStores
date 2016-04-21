<div class="section_list_view ">

    <div class="row have-margin">
        <legend>Manage Privileges</legend>
         <?php if(PageContext::$response->message!="") { ?><div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">×</button>  <?php echo PageContext::$response->message ?></div> <?php } ?>
    </div>
    <table  id="tbl_activities" class="cms_listtable table  table-striped table-bordered table-hover " >
        <tbody>
            <tr>
                <!-- RENDER LIST HEADER -->

                <th class="table-header"><a class="cms_list_operation" href="" >Type</a></th>
                <th class="table-header"><a class="cms_list_operation" href="" >Name</a></th>
                <th class="table-header"><a class="cms_list_operation" href="" >View</a> </th>
                <th class="table-header"><a class="cms_list_operation" href="" >Add </a></th>
                <th class="table-header"><a class="cms_list_operation" href="" >Edit </a></th>
                <th class="table-header"><a class="cms_list_operation" href="" >Delete </a></th>
              <!--  <th class="table-header"><a class="cms_list_operation" href="" >Publish </a></th>-->
                <th class="span2 listingTableHeadTh">Operations</th>

            </tr>
        </tbody>
        <?php foreach(pageContext::$response->privilegList as $privileg) {
            ?>
        <tr>
            <td><?php echo $privileg->entity_type?></td>
            <td><?php echo $privileg->enity_name?></td>
            <td><?php echo $privileg->view_role_id?></td>
            <td><?php echo $privileg->add_role_id?></td>
            <td><?php echo $privileg->edit_role_id?></td>
            <td><?php echo $privileg->delete_role_id?></td>
           <!--  <td><?php echo $privileg->publish_role_id?></td> -->
            <td> <a data-toggle="modal" href="#<?php echo $privileg->privilege_id;?>">View</a> <a class="cms_list_operation action_unpublish" href="<?php echo pageContext::$response->currentURL."&action=edit&privilege_id=".$privileg->privilege_id;?>" >Edit</a>
            <a class="cms_list_operation action_delete" href="<?php echo pageContext::$response->currentURL."&action=delete&privilege_id=".$privileg->privilege_id;?>" >Delete</a></td>
        </tr><?php }

         if(PageContext::$response->totalResultsNum==0) {    ?>
            <tr>  <td colspan="8">
                    No Data Found !!

                </td></tr>
                    <?php
                }
                ?>
        
    </table>
     <div class="">
     <div class="section_list_operations ull-left span3 pagination">
            
            <a href="<?php echo  PageContext::$response->currentURL;?>&action=add#addForm" class="addrecord btn btn-info">Add Record</a>
                 <div style="clear:both"></div>
        </div>
          <div class="pagination pagination-right ull-right">
<?php echo  PageContext::$response->pagination ;?>
                
        </div>
     </div>
            <div <?php if(!PageContext::$response->showForm) { ?> style="display: none;" <?php } ?>class="listForm" id="addForm">
            <form class="form-horizontal" action="<?php echo pageContext::$response->currentURL; ?>" method="POST" id="" name="form_">
                <legend><?php echo pagecontext::$response->form_title;?></legend>
                <input type="hidden" name="privilege_id" id="privilege_id" value="<?php echo pageContext::$response->privilegeId ;?>">
                <div class="control-group">
                    <label for="ventity_type" class="control-label">Select Group or Section</label>
                    <div class="controls">
                        <select name="entity_type" id="entity_type" class="required" <?php if(pageContext::$response->previlegeDetails->entity_type) { ?> disabled  <?php  }?>>
                            <option value="">Select</option>
                            <option value="group" <?php if(pageContext::$response->previlegeDetails->entity_type=="group") { ?> selected <?php  } ?> >Group</option>
                            <option value="section" <?php if(pageContext::$response->previlegeDetails->entity_type=="section") { ?> selected <?php  }?>>Section</option>
                         
                        </select>
                        <span class="mandatory">*</span></div>
                </div>
                <div class="control-group jqSectionDiv" <?php if(pageContext::$response->previlegeDetails->entity_type!="section") { ?> style="display: none;"  <?php  }?>>
                    <label for="vFirstName" class="control-label">Section</label>
                    <div class="controls">
                        <select name="section_entity_id" id="section_entity_id" class="required" <?php if(pageContext::$response->previlegeDetails->entity_id) { ?> disabled  <?php  }?> >
                            <option value="">Select</option>
                            <?php foreach( pageContext::$response->sections as $section) {  ?>
                             <option value="<?php echo $section->id;?>" <?php if(pageContext::$response->previlegeDetails->entity_id==$section->id) { ?> selected  <?php  }?> ><?php echo $section->section_name;?></option>
                            <?php } ?>
                        </select>
                        <span class="mandatory">*</span></div>
                </div>
                <div class="control-group jqGroupDiv" <?php if(pageContext::$response->previlegeDetails->entity_type!="group") { ?> style="display: none;"  <?php  }?>>
                    <label for="vFirstName" class="control-label">Group</label>
                    <div class="controls">
                        <select name="group_entity_id" id="group_entity_id" class="required" <?php if(pageContext::$response->previlegeDetails->entity_id) { ?> disabled  <?php  }?>>
                            <option value="">Select</option>
                            <?php foreach( pageContext::$response->groups as $group) {  ?>
                             <option value="<?php echo $group->id;?>" <?php if(pageContext::$response->previlegeDetails->entity_id==$group->id) { ?> selected <?php  }?> ><?php echo $group->group_name;?></option>
                            <?php } ?>
                        </select>
                        <span class="mandatory">*</span></div>
                </div>
            <div class="control-group">
                    <label for="vFirstName" class="control-label">View Privilege</label>
                    <div class="controls">
                        <select name="view_role_id" id="view_role_id" class="required">
                            <option value="">sadmin</option>
                            <?php foreach( pageContext::$response->roles as $role) {  ?>
                             <option value="<?php echo $role->value;?>" <?php if(pageContext::$response->previlegeDetails->view_role_id==$role->value) { ?> selected <?php  }?>><?php echo $role->text;?></option>
                            <?php } ?>
                        </select>
                        </div>
                </div>
                <div class="control-group">
                    <label for="vFirstName" class="control-label">Add Privilege</label>
                    <div class="controls">
                        <select name="add_role_id" id="add_role_id" class="required">
                            <option value="">sadmin</option>
                            <?php foreach( pageContext::$response->roles as $role) {  ?>
                             <option value="<?php echo $role->value;?>" <?php if(pageContext::$response->previlegeDetails->add_role_id==$role->value) { ?> selected <?php  }?>><?php echo $role->text;?></option>
                            <?php } ?>
                        </select>
                       </div>
                </div>
                <div class="control-group">
                    <label for="vFirstName" class="control-label">Edit Privilege</label>
                    <div class="controls">
                        <select name="edit_role_id" id="edit_role_id" class="required">
                            <option value="">sadmin</option>
                            <?php foreach( pageContext::$response->roles as $role) {  ?>
                             <option value="<?php echo $role->value;?>" <?php if(pageContext::$response->previlegeDetails->edit_role_id==$role->value) { ?> selected <?php  }?>><?php echo $role->text;?></option>
                            <?php } ?>
                        </select>
                        </div>
                </div>
                <div class="control-group">
                    <label for="vFirstName" class="control-label">Delete Privilege</label>
                    <div class="controls">
                        <select name="delete_role_id" id="delete_role_id" class="required">
                            <option value="">sadmin</option>
                            <?php foreach( pageContext::$response->roles as $role) {  ?>
                             <option value="<?php echo $role->value;?>" <?php if(pageContext::$response->previlegeDetails->delete_role_id==$role->value) { ?> selected <?php  }?>><?php echo $role->text;?></option>
                            <?php } ?>
                        </select>
                        </div>
                </div>
                 <!--
                <div class="control-group">
                    <label for="vFirstName" class="control-label">Publish Privilege</label>
                    <div class="controls">
                        <select name="publish_role_id" id="publish_role_id" class="required">
                            <option value="">sadmin</option>
                            <?php foreach( pageContext::$response->roles as $role) {  ?>
                             <option value="<?php echo $role->value;?>" <?php if(pageContext::$response->previlegeDetails->publish_role_id==$role->value) { ?> selected <?php  }?>><?php echo $role->text;?></option>
                            <?php } ?>
                        </select>
                        </div>
                </div>
                 -->
                <div class="controls">
                    <input type="submit" name="submit" value="Save" class="submitButton btn jqPrivilegeForm">
                    <input type="button" name="cancel" value="Cancel" class="cancelButton btn"></div></form>

        </div>
    <?php foreach(pageContext::$response->privilegList as $privileg) {
        ?>
    <div id="<?php echo $privileg->privilege_id?>" class="modal hide fade in" style="display: none; ">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4> <?php echo $privileg->enity_name; ?> </h4>
        </div>
        <div class="modal-body">
            <table class="table  table-bordered table-hover table-condensed">


                <tbody>


                    <tr><td class="span3">Entity Name&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $privileg->enity_name; ?></small>                    </td>
                    </tr>


                    <tr><td class="span3">Entity Type&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $privileg->entity_type; ?></small>                    </td>
                    </tr>


                    <tr><td class="span3">View privilege&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $privileg->view_role_id; ?></small>                    </td>
                    </tr>


                    <tr><td class="span3">Add privilege&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $privileg->add_role_id; ?></small>                    </td>
                    </tr>


                    <tr><td class="span3">Edit privilege&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $privileg->edit_role_id; ?></small>                    </td>
                    </tr>


                    <tr><td class="span3">Delete privilege&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $privileg->delete_role_id; ?></small>                    </td>
                    </tr>


                    <tr><td class="span3">Publish&nbsp;</td>
                        <td class="span6">

                                <?php echo $privileg->publish_role_id; ?>                     </td>
                    </tr>




                </tbody></table>
        </div>
        </div>
                <?php } ?>
        </div>
