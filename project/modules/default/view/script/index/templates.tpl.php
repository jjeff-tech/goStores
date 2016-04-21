
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>  


<script type="text/javascript">
    
    $(document).ready(function(){
        
        <?php foreach(PageContext::$response->pageContents as $key => $item){
            
            ?>
        
        
$(document).on('click', '[data-toggle="lightbox_<?php echo $key;?>"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
            

            
        <?php } ?>
 });
    
    
    
    
    
    
    
    
    function selltemplates(sell_link){

        window.location = sell_link;
    }
</script>
<?php PageContext::renderPostAction('loginpop', 'index'); ?>
<div class="container">
    <div class="row">
				<div class="content_area_inner">
					<div class="main-titile marg20_top">
                        
						  <h2><?php echo $this->pageTitle;?></h2>
                     
						<!--<h4>Caption</h4>-->

						<div class="clear"></div>
					</div>

					<div class="screenshot_div">









                                                <?php
                                                if(!empty(PageContext::$response->pageContents)){
                                                    $i=0;
                                                   foreach(PageContext::$response->pageContents as $key => $item){

                                                    // IMAGE_FILE_URL // FILE_UPLOAD_DIR

                                                    //if(is_file(FILE_UPLOAD_DIR.$item->zipFile) && is_file(FILE_UPLOAD_DIR.$item->homeScreenshot)){

                                                        $image = (is_file(FILE_UPLOAD_DIR.$item->homeScreenshot)) ? IMAGE_FILE_URL.$item->homeScreenshot : IMAGE_URL.'no-image.jpg';
                                                        
                                                        $image2 = (is_file(FILE_UPLOAD_DIR.$item->innerScreenshot1)) ? IMAGE_FILE_URL.$item->innerScreenshot1 : IMAGE_URL.'no-image.jpg';
                                                        
                                                        $image3 = (is_file(FILE_UPLOAD_DIR.$item->innerScreenshot2)) ? IMAGE_FILE_URL.$item->innerScreenshot2 : IMAGE_URL.'no-image.jpg';
                                                        
                                                        
                                                    ++$i;
                                                    $class = NULL;
                                                    $class = 'sceenshot';

                                                    $class .= ' marginbottom50';
                                                    if(PageContext::$response->userID){

                                                    }
                                                    $dialog_class = (PageContext::$response->userID) ? "" : "jqLoginInnerDiv";
                                                    $buy_link = (PageContext::$response->userID) ? BASE_URL."buytemplate/".$item->nTemplateId : "javascript:void(0);";
                                                ?>
                                                <div class="col-xs-12 col-sm-4 col-md-4">
                                                <div class="<?php echo $class ?> row">
                                                    
                                                    
                                                   
                                                    <a href="<?php echo $image; ?>" data-toggle="lightbox_<?php echo $key;?>" data-gallery="example-gallery_<?php echo $key;?>">
                                                    <!--<a href="<?php echo BASE_URL?>templates/<?php echo $item->nTemplateId?>">-->
                                                      <img src="<?php echo $image; ?>" alt="<?php echo stripslashes($item->vTemplateName); ?>">
                                                    <!--</a>-->
                                                    
                                                    </a>
                                                    
                                                   
            <a style="display: none;" href="<?php echo $image2; ?>" data-toggle="lightbox_<?php echo $key;?>" data-gallery="example-gallery_<?php echo $key;?>">
                <img src="<?php echo $image2; ?>" class="img-fluid">
            </a>
            <a style="display: none;" href="<?php echo $image3; ?>" data-toggle="lightbox_<?php echo $key;?>" data-gallery="example-gallery_<?php echo $key;?>">
                <img src="<?php echo $image3; ?>" class="img-fluid">
            </a>
       
       
                                                    
                                                    
                                                    
							<div class="ss_details">
                                                            <!--<a href="<?php echo BASE_URL?>templates/<?php echo $item->nTemplateId?>">-->
                                                              <h2><?php echo stripslashes($item->vTemplateName); ?><?php //echo '&nbsp;&nbsp;'.CURRENCY_SYMBOL.' '.Utils::formatPrice($item->nCost); ?></h2>
                                                            <!--</a>-->
                                                <div class="temp_desc">
							<p><?php echo Utils::subString($item->vDescription, 150,'...'); ?></p>
						</div>
                                                            <?php

  //if(PageContext::$response->userLogged){ ?>
                                                            <a class="plan-btn" href="<?php echo ConfigUrl::base(); ?>index/paynowredirect?plan_id=<?php echo PageContext::$response->plan_id;?>&template_id=<?php echo base64_encode(base64_encode($item->nTemplateId));?>" >Select</a>
  <?php //} ?>
                                                            <div>
                                                                    <!--<a class="<?php echo $dialog_class; ?> orng_btn_new" href="<?php echo $buy_link; ?>" >Buy</a>-->
                                                                    <div class="clear"></div>
                                                                </div>
							</div>
                            <div class="clear"></div>
                            
                            
                                                  
                            
						</div>
                        </div>
                                                <?php
                                                    $i=($i==3) ? 0 : $i;
                                                    //}
                                                   }
                                                } else {
                                                ?>
                                            <div class="flashmsg">

                                                <h2>Oops! No templates available right now. Please be sure to check again later.</h2>

                                            </div>

                                                <?php
                                                }
                                                ?>
						<div class="clear"></div>
					</div>

				<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
