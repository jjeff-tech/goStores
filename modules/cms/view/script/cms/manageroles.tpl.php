<div class="section_list_view ">

    <div class="row have-margin">
        <legend>Manage Roles</legend>
        <?php if(PageContext::$response->message!="") { ?><div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">×</button>  <?php echo PageContext::$response->message ?></div> <?php } ?>

      <?php if(PageContext::$response->errorMessage!="") { ?><div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">×</button>  <?php echo PageContext::$response->errorMessage ?></div> <?php } ?></div>
    <table  id="tbl_activities" class="cms_listtable table  table-striped table-bordered table-hover " >
        <tbody>
            <tr>
                <!-- RENDER LIST HEADER -->

                <th class="table-header"><a class="cms_list_operation" href="" >Role ID</a></th>
                <th class="table-header"><a class="cms_list_operation" href="" >Role Name</a></th>
                <th class="table-header"><a class="cms_list_operation" href="" >Parent Role</a> </th>

                <th class="span2 listingTableHeadTh">Operations</th>

            </tr>
        </tbody>
        <?php foreach(pageContext::$response->roles as $role) {
            ?>
        <tr>
            <td><?php echo $role->role_id?></td>
            <td><?php echo $role->role_name?></td>
            <td><?php echo $role->parent_role_name?></td>

            <td> <a data-toggle="modal" href="#<?php echo $role->role_id?>">View</a>
                <a class="cms_list_operation action_unpublish" href="<?php echo pageContext::$response->currentURL."&action=edit&role_id=".$role->role_id;?>" >Edit</a>
                <a class="cms_list_operation action_delete" href="<?php echo pageContext::$response->currentURL."&action=delete&role_id=".$role->role_id;?>" >Delete</a></td>
        </tr><?php } if(PageContext::$response->totalResultsNum==0) {    ?>
            <tr>  <td colspan="8">
                    No Data Found !!

                </td></tr>
                    <?php
                } ?>
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
        <form class="form-horizontal" action="<?php echo pageContext::$response->currentURL; ?>" method="POST" id="newrole" name="form_">
            <legend>Add New Role</legend>
            <input type="hidden" name="role_id" id="role_id" value="<?php echo pageContext::$response->rolesDetails->role_id;?>">

            <div class="control-group">
                <label for="vFirstName" class="control-label">Role Name</label>
                <div class="controls">
                    <input type="text" name="role_name" id="role_name" value="<?php echo pageContext::$response->rolesDetails->role_name;?>">

                </div>
            </div>

            <div class="control-group">
                <label for="vFirstName" class="control-label">Parent Role</label>
                <div class="controls">
                    <select name="parent_role_id" id="parent_role_id" class="required">
                        <option value="">Superadmin</option>
                        <?php foreach( pageContext::$response->roles as $role) {
                            if(pageContext::$response->rolesDetails->role_id!=$role->role_id) { ?>
                        <option value="<?php echo $role->role_id;?>" <?php if( pageContext::$response->rolesDetails->parent_role_id==$role->role_id) { ?> selected <?php  }?>><?php echo $role->role_name;?></option>
                            <?php } } ?>
                    </select>
                </div>
            </div>




            <div class="controls">
                <input type="submit" name="submit" value="Save" class="submitButton btn jqRoleForm">
                <input type="button" name="cancel" value="Cancel" class="cancelButton btn"></div></form>

    </div>
    <?php foreach(pageContext::$response->roles as $role) {
        ?>
    <div id="<?php echo $role->role_id?>" class="modal hide fade in" style="display: none; ">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4> <?php echo $role->role_name; ?> </h4>
        </div>
        <div class="modal-body">
            <table class="table  table-bordered table-hover table-condensed">


                <tbody>


                    <tr><td class="span3">Role&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $role->role_name ?></small>                    </td>
                    </tr>


                    <tr>
                        <td class="span3">Parent Role&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $role->parent_role_name; ?></small>                    </td>
                    </tr>











                </tbody></table>
        </div>
    </div>
        <?php } ?>
</div>
