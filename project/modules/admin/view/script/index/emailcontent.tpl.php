<div class="form_container">
    <div class="form_top"><?php echo $this->pageTitle;?></div>
    <div class="form_bgr">
              
        <?php

        if($this->action=="edit" || $this->action=="add")// add plan details
        {
            ?>
        <form id="frmPlan" action="<?php echo ConfigUrl::base(); ?>index/emailcontent" method="post" onsubmit="return validatePlan()">
            <input type="hidden" name="id" value="<?php echo $this->id?>">
    <input type="hidden" name="action" value="<?php echo $this->action ?>">






    <table border="0" cellpadding="0" cellspacing="0" class="formstyle" id="id-form">
        
		<tr>
		  <th align="left" valign="top" width="25%">Template Name <span class="mandred">*</span></th>
                  <td align="left" valign="top"><input id="cms_name" name="cms_name" type="text" class="inp-form" validate="required:true"  value="<?php echo stripslashes($this->pageContent->cms_name)?>" readonly /> </td>
		 </tr>
       
		<tr>
                    <th height="32" valign="top" align="left" >Subject:</th>
                    <td><input id="cms_title" name="cms_title" type="text" class="inp-form" value="<?php echo stripslashes($this->pageContent->cms_title)?>" />  </td>
		</tr>
                <tr>
                    <th align="left" valign="top">Body:</th>
                    <td align="left" valign="top">  <textarea id="cms_desc" name="cms_desc" rows="5"  cols="40"  ><?php echo stripslashes($this->pageContent->cms_desc)?></textarea>		 </td>
		</tr>

	<tr>
		<th align="left" valign="top"><div class="cancel"><a href="<?php echo BASE_URL;?>admin/index/emailcontent">Cancel</a></div></th>
		<td valign="top">

			<input type="submit" name="btnAdd"  value="<?php echo $this->btnval;?>"  /></td>
		</tr>
	</table>
        </form>


            <?php
        } else {
            ?>
        <!--<form class="cmxform" id="frmRole" action="<?php //echo ConfigUrl::base(); ?>index/cms/" method="post" onsubmit="return validateListSearch()">
            <input type="hidden" name="action" value="search">-->
            <div class="search_container" style="display: none;"><ul><li><!--Search--></li><li><!--<input name="search" type="text" class="search_box" value="<?php //echo $this->txtSearch; ?>">--></li><li><!--<input name="btnSearch" type="submit" class="button_orange" value="Search">--></li>
                    <li><div class="addnew"><a href="<?php echo ConfigUrl::base(); ?>index/cms/add">Add New</a></div></li>
                </ul></div>
        <!--</form>-->
        <?php if(!empty($this->message)) { ?>
        <div class="green_box"><?php echo $this->message; ?></div>
        <?php } ?>
          <div  align="left">
<?php PageContext::renderPostAction('successmessage');
$this->messageFunction ='';?>
</div>
        <table width="95%"   border="0" align="center" cellpadding="0" cellspacing="0">
            <tr class="heading1">
                <td width="6%" align="center">Sl No.</td>
                <td width="34%"  align="left">Template Name</td>
                <td width="40%"  align="left">Subject</td>
                <td width="20%" align="left">Actions</td>
            </tr>
            <?php
                if(!empty($this->pageContents)) {
                    $i=$this->pageInfo['base'];
                    foreach($this->pageContents as $row) {
                        $i++;
                        $className=($i%2) ? 'column2' : 'column1';
                        ?>
            <tr class="<?php echo $className ?>" id="item_<?php echo $row->cms_id;?>">
                <td align="left"><?php echo $i; ?></td>
                <td align="left"><?php echo htmlentities($row->cms_name); ?></td>
                <td align="left"><?php echo htmlentities($row->cms_title); ?></td>
                <td align="left"><span class="edit"><a href="<?php echo ConfigUrl::base(); ?>index/emailcontent/edit/<?php echo $row->cms_id;?>" title="Edit" >Edit</a></span>
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

        <!--<div class="more_entries">
            <div class="wp-pagenavi">
            <?php //if(!empty($this->pageContents)) { echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'admin/plan/index/x/x/'); } ?>
            </div>
        </div> -->


        <?php
        }
        ?>
    </div>
    <div class="form_bottom"></div>

</div>
