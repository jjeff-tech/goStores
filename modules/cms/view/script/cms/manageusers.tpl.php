
<div class="section_list_view ">
 
    <div class="row have-margin">
        <legend>Manage Users</legend>
        <?php if(PageContext::$response->message!="") { ?><div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">×</button>  <?php echo PageContext::$response->message ?></div> <?php } ?>
     <?php if(PageContext::$response->errorMessage!="") { ?><div class="alert alert-error"> <button type="button" class="close" data-dismiss="alert">×</button>  <?php echo PageContext::$response->errorMessage ?></div> <?php } ?>
    </div>
    <table  id="tbl_activities" class="cms_listtable table  table-striped table-bordered table-hover " >
        <tbody>
            <tr>
                <!-- RENDER LIST HEADER -->

                <th class="table-header"><a class="cms_list_operation" href="" >User ID</a></th>
                <th class="table-header"><a class="cms_list_operation" href="" >User Name</a></th>
                  <th class="table-header"><a class="cms_list_operation" href="" >Email</a> </th>
                <th class="table-header"><a class="cms_list_operation" href="" >Role</a> </th>

                <th class=" listingTableHeadTh">Operations</th>

            </tr>
        </tbody>
        <?php foreach(pageContext::$response->users as $user) {
            ?>
        <tr>
            <td><?php echo $user->id;?></td>
            <td><?php echo $user->username;?></td>
              <td><?php echo $user->email;?></td>
            <td><?php echo $user->role_name;?></td>

            <td> <a data-toggle="modal" href="#<?php echo $user->id?>">View</a>
                <?php if($user->username!="sadmin") { ?>
                <a class="cms_list_operation action_unpublish" href="<?php echo pageContext::$response->currentURL."&action=edit&id=".$user->id;?>" >Edit</a>
                <a class="cms_list_operation action_delete" href="<?php echo pageContext::$response->currentURL."&action=delete&id=".$user->id;?>" >Delete</a>
    <a class="cms_list_operation action_unpublish" href="<?php echo pageContext::$response->currentURL."&action=changepw&id=".$user->id;?>" >Change Password</a></td>
        <?php } ?></tr><?php } ?>
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
    <div  <?php if(!PageContext::$response->showForm) { ?> style="display: none;" <?php } ?>class="listForm" id="addForm">
        <form class="form-horizontal" action="<?php echo pageContext::$response->currentURL; ?>" method="POST" id="newuser" name="form" >
            <legend>Add New User</legend>
            <input type="hidden" name="id" id="id" value="<?php echo pageContext::$response->userDetails->id;?>">

            <div class="control-group">
                <label for="vFirstName" class="control-label">User Name</label>
                <div class="controls">
                    <input type="text" name="username" id="username" value="<?php echo pageContext::$response->userDetails->username;?>">

                </div>
            </div>
            <?php if(!pageContext::$response->userDetails->id) { ?>
 <div class="control-group">
                <label for="vFirstName" class="control-label">Password</label>
                <div class="controls">
                    <input type="password" name="password" id="password" value="">

                </div>
            </div>
            <?php } ?>
             <div class="control-group">
                <label for="vFirstName" class="control-label">Email</label>
                <div class="controls">
                    <input type="text" name="email" id="email" value="<?php echo pageContext::$response->userDetails->email;?>">

                </div>
            </div>
            <div class="control-group">
                <label for="vFirstName" class="control-label">Role</label>
                <div class="controls">
                    <select name="role_id" id="role_id" class="required">
                        
                        <?php foreach( PageContext::$response->roles as $role) {
                          ?>
                        <option value="<?php echo $role->value;?>" <?php if( pageContext::$response->userDetails->role_id==$role->value) { ?> selected <?php  }?>><?php echo $role->text;?></option>
                           <?php } ?>
                    </select>
                </div>
            </div>




            <div class="controls">
                <input type="submit" name="submit" value="Save" class="submitButton btn jqUserForm">
                <input type="button" name="cancel" value="Cancel" class="cancelButton btn"></div></form>

    </div>
        <div  <?php if(!PageContext::$response->showPasswordForm) { ?> style="display: none;" <?php } ?>class="cpForm" id="cpForm">
        <form class="form-horizontal" action="<?php echo pageContext::$response->currentURL; ?>" method="POST"  name="cpform" id="cpform" >
            <legend>Change Password</legend>
            <input type="hidden" name="id" id="id" value="<?php echo pageContext::$response->userDetails->id;?>">

            <div class="control-group">
                <label for="vFirstName" class="control-label">Current Password</label>
                <div class="controls">
                    <input type="password" name="cpassword" id="cpassword" >

                </div>
            </div>
              <div class="control-group">
                <label for="vFirstName" class="control-label">New Password</label>
                <div class="controls">
                    <input type="password" name="newpassword" id="newpassword" >

                </div>
            </div>
              <div class="control-group">
                <label for="vFirstName" class="control-label">Confirm Password</label>
                <div class="controls">
                    <input type="password" name="cnewpassword" id="cnewpassword" >

                </div>
            </div>
          
         




            <div class="controls">
                <input type="submit" name="submit" value="Update" class="submitButton btn jqCPForm">
                <input type="button" name="cancel" value="Cancel" class="cancelButton btn"></div></form>

    </div>
    <?php foreach(pageContext::$response->users as $user) {
        ?>
    <div id="<?php echo $user->id?>" class="modal hide fade in" style="display: none; ">
        <div class="modal-header">
            <a class="close" data-dismiss="modal">×</a>
            <h4> <?php echo $user->username; ?> </h4>
        </div>
        <div class="modal-body">
            <table class="table  table-bordered table-hover table-condensed">


                <tbody>

 <tr><td class="span3">User Id&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $user->id ?></small>                    </td>
                    </tr>
                    <tr><td class="span3">User&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $user->username ?></small>                    </td>
                    </tr>
  <tr><td class="span3">Email&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $user->email; ?></small>                    </td>
                    </tr>

                    <tr>
                        <td class="span3">Role&nbsp;</td>
                        <td class="span6">

                            <small class=""><?php echo $user->role_name; ?></small>                    </td>
                    </tr>











                </tbody></table>
        </div>
    </div>
        <?php } ?>
</div>
