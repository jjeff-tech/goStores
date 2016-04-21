<?php
if(PageContext::$response->illegal) {
    PageContext::renderPostAction('permission',"cms","cms",1);
}
else {
    if(PageContext::$response->settingsTab) {
        PageContext::renderPostAction('settings',"cms","cms",1);
    }
    if(PageContext::$response->dashboardPanel) {
        PageContext::renderPostAction('dashboard',"cms","cms",1);
    }
    if(PageContext::$response->customCmsAction){
         PageContext::renderPostAction(PageContext::$response->customActionMethod,PageContext::$response->customActionController,PageContext::$response->customActionModule,1);
    }
    if(PageContext::$request['section'] && PageContext::$response->logged_in && PageContext::$response->isCustomAction==0 && !PageContext::$response->settingsTab && !PageContext::$response->dashboardPanel && PageContext::$response->customCmsAction==0) {
        ?>

        <?php PageContext::renderPostAction(PageContext::$response->postAction,"cms","cms",1); ?>

<div <?php if(!PageContext::$response->showForm) { ?> style="display: none;" <?php } ?>class="listForm" id="addForm">
            <?php PageContext::$response->addform->renderForm();?>
</div>
        <?php } if

    (PageContext::$response->isCustomAction==1) {
        PageContext::renderPostAction(PageContext::$response->customActionMethod,PageContext::$response->customActionController,PageContext::$response->customActionModule);

    }

    if(PageContext::$request['section'] && PageContext::$response->logged_in && PageContext::$response->isCustomAction==2) {


        PageContext::renderPostAction(PageContext::$response->postAction,"cms","cms",1);
    }

    ?>


    <?php
}
if(!PageContext::$response->logged_in) {
    PageContext::renderPostAction('login',"cms","cms",1);
}
if(PageContext::$response->invalidLicense) {
    PageContext::renderPostAction('invalidlicense',"cms","cms",1);
}


?>
