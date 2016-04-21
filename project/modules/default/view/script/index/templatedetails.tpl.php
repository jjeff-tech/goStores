<?php PageContext::renderPostAction('loginpop', 'index'); ?>
<div class="contentarea_wrapper">
    <div class="content_area_wrapper">
        <div class="content_area_inner">
		<?php
                if(!empty(PageContext::$response->pageContents)) {
                    $i=0;
                    foreach(PageContext::$response->pageContents as $item) {

                        // IMAGE_FILE_URL // FILE_UPLOAD_DIR
                        //   echopre($item);
                        if(is_file(FILE_UPLOAD_DIR.$item->zipFile)) {

                            $image = (is_file(FILE_UPLOAD_DIR.$item->homeScreenshot)) ? IMAGE_FILE_URL.$item->homeScreenshot : IMAGE_URL.'no-image.jpg';
                            ++$i;
                            $class = NULL;
                            $class = 'sceenshot';
                            $class .= ($i==3) ? ' nomargin' : '';
                            $class .= ' marginbottom50';
                            if(PageContext::$response->userID) {

                            }
                            $dialog_class = (PageContext::$response->userID) ? "" : "jqLoginInnerDiv";
                            $buy_link = (PageContext::$response->userID) ? BASE_URL."buytemplate/".$item->nTemplateId : "javascript:void(0);";
                            ?>
            <div class="main-titile marg20_top">
                <h2><?php echo stripslashes($this->pageTitle); ?></h2>
               <h6><?php echo stripslashes($item->vTemplateName); ?></h6>
            </div>

            <div class="screenshot_div">
				<div class="screenshot_leftdiv">
				  <!--slider starts-->
                    <div class="sceenshot_detail">
						<div id="slidebox_template">
                        <div class="next"></div>
                        <div class="previous"></div>
                        <div class="thumbs">
                            <a href="#" onClick="Slidebox(1);return false" class="thumb">1</a>
                            <a href="#" onClick="Slidebox(2);return false" class="thumb">2</a>
                            <a href="#" onClick="Slidebox(3);return false" class="thumb">3</a>
                        </div>
                        <div class="container">
                                        <?php
                                        $templateImage = "";
                                        ?>
                            <div class="content">
                                <img src="<?php echo IMAGE_FILE_URL.$item->homeScreenshot;?>" alt="<?php echo ($item->vTemplateName); ?>" height="430" width="400"/>
                            </div>
                            <div class="content">
                                <img src="<?php echo IMAGE_FILE_URL.$item->innerScreenshot1;?>" alt="<?php echo ($item->vTemplateName); ?>" height="430" width="400"/>
                            </div>
                            <div class="content">
                                <img src="<?php echo IMAGE_FILE_URL.$item->innerScreenshot2;?>" alt="<?php echo ($item->vTemplateName); ?>" height="430" width="400"/>
                            </div>

                        </div>
                    </div>

					</div>
                    <!--slider ends-->
					<div class="clear"></div>
				</div>
				<div class="screenshot_rightdiv">

				<div class="temp_desc_detail">

                            <p><?php echo stripslashes($item->vDescription);?></p>
                        </div>
				 <div class="btn_container_screenshot_rightdiv">
				 <h4><span>Price:</span><?php echo CURRENCY_SYMBOL.' '.Utils::formatPrice($item->nCost); ?></h4>
				 <a class="<?php echo $dialog_class; ?> orng_btn_new" href="<?php echo $buy_link; ?>" >Buy</a></div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>


                            <?php
                            $i=($i==3) ? 0 : $i;
                        }
                    }
                }
                ?>
                <div class="clear"></div>
            </div>



            <div class="clear"></div>
        </div>
    </div>
    <div class="clear"></div>
</div>