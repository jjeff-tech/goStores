<link rel="stylesheet" href="<?php echo BASE_URL;?>modules/cms/style/cms.css" type="text/css" >
<!--<link rel="stylesheet" href="<?php echo BASE_URL;?>modules/cms/style/theme1.css" type="text/css" >-->
<script type="text/javascript" src="<?php echo BASE_URL;?>modules/cms/js/cms.js"></script>

<?php if(PageContext::$response->logged_in){?>

<div class="container-fluid">
<div class="header row-fluid" >
	<div class="framework_logo lfloat span4" >
            <a href="<?php echo BASE_URL."cms";?>"><?php if(PageContext::$response->cmsSettings['site_logo']) { echo  PageContext::$response->siteLogo;} else { ?><img src="<?php echo BASE_URL.PageContext::$response->cmsSettings['admin_logo']?>" ><?php } ?></a>
	</div>
	<div class="pull-right  sitehome">
		<br />
		<ul class="nav nav-pills pull-right" >
                   <?php if(PageContext::$response->cmsUsername) { ?> <li><a href="" onclick="return false;">Welcome <?php echo ucfirst(PageContext::$response->cmsUsername); ?></a></li><?php } ?>
                    <?php foreach(PageContext::$response->headerLinks as $key=>$links) { ?>
                    <li><a href="<?php echo $links->link; ?>" <?php if($links->target=="popup") { ?>class="jqHeaderPopupLink"<?php } ?>><?php echo $links->title; ?></a></li>
                   <?php } ?>
		  <li class="active">
		    <a href="<?php  echo ConfigUrl::root();?>">Site Home</a>
		  </li>
		  <?php if(PageContext::$response->logged_in){?>
		  <li><a href="<?php echo ConfigUrl::root();?>cms/cms/logout">Logout</a></li>
		  <?php }?>	 
		</ul>
	</div>
</div>
<div class="row-fluid" style="border:1px solid #e8e8e8;padding-right:10px;" >
	<div class="left-panel span2" style="background:#f1f1f1;border:1px solid #e3e3e3;min-height:500px">
		<ul class="nav nav-list">
		<?php 
			foreach(PageContext::$response->menu as $menu){
				echo '<li class="nav-header">'.$menu->name.'</li>';			 
				foreach($menu->sections as $section){
					
					if(PageContext::$request['section'] == $section->section_alias || PageContext::$request['parent_section'] == $section->section_alias )$listatus=' class="active" ';else $listatus= ' ';
					echo '<li'.$listatus.'><a href="'.ConfigUrl::root().'cms?section='.$section->section_alias.'">'.$section->section_name.'</a></li>';
				}
				
			}
		?>
			<!--li class="nav-header">CMS</li>
			<li><a href="#">Manage Groups</a></li>
			<li><a href="#">Manage Sections</a></li-->
		</ul>
	</div>
	<div class="right-panel  span10" >		
		<?php echo $this->_content; ?>
             <div class="modal" id="popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none; ">
        <div class="is-padded">
            <button type="button" class="close jqCloseButton" data-dismiss="modal" aria-hidden="true">×</button>

        </div>
        <div class="modal-body" id="popupBody">
            </div>
        <div class="modal-footer">
            <button class="btn jqCloseButton" data-dismiss="modal" aria-hidden="true">Close</button>

        </div>

    </div>
	</div>
	</div>
	<div class="footer row-fluid">
		<p class="muted"><small><?php if(PageContext::$response->cmsSettings['admin_copyright']) { echo PageContext::$response->cmsSettings['admin_copyright']; } else { ?>&copy;Armia Systems <?php  } ?></small></p>
	</div>
</div>
<?php }else{
	 echo $this->_content;
}?>



