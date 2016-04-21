<div class="section_list_view " >

    <div class="row have-margin"  >
        <span class="legend">
            <?php  echo "Section : ".PageContext::$response->sectionName; ?>
        </span>
<?php if(PageContext::$response->message!="") { ?><div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert">x</button>  <?php echo PageContext::$response->message ?></div> <?php } ?>

<?php if(PageContext::$response->settingStyle=="tab") { ?>


<ul class="nav nav-tabs" id="settingtab">
        <?php  $loop=0;
    foreach(PageContext::$response->settingsTabs as $tab) { ?>


    <li class="<?php  if($loop==0) { ?>active<?php } ?>"><a href="#<?php echo $tab->id ?>" data-toggle="tab" ><?php echo $tab->label ?></a></li>


        <?php  $loop++;
    } ?>
</ul>
<div style="border-bottom:1px solid #DDDDDD;border-left:1px solid #DDDDDD;border-right:1px solid #DDDDDD;margin-top: -20px;">
    <div class="control-group">&nbsp;</div>
<form action="" name="settingsForm" id="settingsForm" method="post" class="form-horizontal">
    <div class="tab-content">

    <?php $loop=0;
                foreach(PageContext::$response->settingsTabs as $tab) { ?>
        <div class="tab-pane <?php if($loop==0) { ?>active<?php } ?>" id="<?php echo $tab->id ?>">

        <?php foreach($tab->tabContent as $tabContent) { ?>
           
            <?php if($tabContent->type=="checkbox") {?>
             <div class="control-group">
                <label class="control-label" for="<?php echo $tabContent->settingfield;?>"><?php echo $tabContent->settinglabel;?></label>
                <div class="controls">
                    <input type="checkbox"  name="<?php echo $tabContent->settingfield;?>" <?php if($tabContent->customColumn->checked==$tabContent->value) { ?>checked <?php } ?>><span class="help-inline"><?php echo $tabContent->customColumn->hint; ?></span>
                    </div>
                  
            </div>
             <?php } else if($tabContent->type=="textarea") { ?> 
             <div class="control-group">
                <label class="control-label" for="<?php echo $tabContent->settingfield;?>"><?php echo $tabContent->settinglabel;?></label>
                <div class="controls">
                    <textarea  name="<?php echo $tabContent->settingfield;?>" ><?php echo $tabContent->value;?></textarea></div>
            </div>
            <?php } else if($tabContent->type=="link") { ?>
             <div class="control-group">
                <label class="control-label" for="<?php echo $tabContent->settingfield;?>"><?php echo $tabContent->settinglabel;?></label>
                <div class="controls">
                    <?php echo $tabContent->value;?>
                    <input type="hidden"  name="<?php echo $tabContent->settingfield;?>" value="<?php echo $tabContent->value;?>"></div>
            </div>
            <?php } else if($tabContent->type=="") { ?>
            <div class="control-group">
                <label class="control-label" for="<?php echo $tabContent->settingfield;?>"><?php echo $tabContent->settinglabel;?></label>
                <div class="controls">
                    <input type="text"  name="<?php echo $tabContent->settingfield;?>" value="<?php echo $tabContent->value;?>" ></div>
            </div>

                <?php } ?>
            <?php } ?>
            <div class="controls"><input class="submitButton btn" type="submit" value="Save" name="submit">
                <input class="cancelButton btn" type="button" value="Cancel" name="cancel"></div>
        </div>

        <?php $loop++;
    } ?>

    </div>
</form>
    <?php  } ?>
    </div>
    </div>
    <br>
    <br>
</div>
