<div class="contentarea_wrapper">
    <div class="content_area_wrapper">
        <div class="content_area_inner">
            <div class="main_headings">
                <h2><?php echo $this->pageTitle;?></h2>

            </div>
            <!-- Message Container -->
            <div class="flashmsg" <?php if(PageContext::$response->processStatus == "0") {?> style="border-color:#d82b2b !important;" <?php }else{ ?>style="border-color:#72b55f !important;" <?php } ?>>
                <h2 <?php if(PageContext::$response->processStatus == "0") {?> style="color:#d82b2b !important;" <?php }else{ ?>style="color:#72b55f !important;" <?php } ?>><?php echo PageContext::$response->processMsg; ?></h2>
                <div class="clear"></div>
            </div>
            <!-- Message Container -->
            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>


