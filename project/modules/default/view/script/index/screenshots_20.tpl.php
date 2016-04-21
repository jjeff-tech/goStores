<div class="container">
    <div class="row">
			<div class="content_area_wrapper">
				<div class="content_area_inner">
					<div class="main_headings">
                        <div class="col-xs-12 col-sm-12 col-md-12">
						  <h2>SCREENS</h2>
                        
						<!--<h4>Here's just a few of our clients' stores.</h4>-->                                              
                                                <?php
                                                $userPanelArr = $adminPanelArr = array();
                                                if(!empty(PageContext::$response->screenshots)) {
                                                    foreach(PageContext::$response->screenshots as $item){
                                                        if(is_file(FILE_UPLOAD_DIR.$item->file_path)){
                                                                if($item->eType == 'User'){
                                                                    $userPanelArr[]=$item;
                                                                } else {
                                                                    $adminPanelArr[]=$item;
                                                                }                                                               
                                                            } // End if
                                                    } // End foreach
                                                ?>
						<h6>User Panel </h6>
                                                <?php
                                                } else {
                                                ?>
                                                <div class="flashmsg">
                                                <h2>Oops! No screens available right now. Please be sure to check again later.</h2>
                                                </div>                                               
                                                <?php
                                                }
                                                ?>
					</div>
                    </div>
                                        <?php
                                        if(!empty($userPanelArr)) {
                                          
                                           
                                            foreach($userPanelArr as $item){
                                                
                                               
                                               
                                                
                                                ?>
                                                <div class="col-xs-12 col-sm-4 col-md-4">
                                                <div class="sceenshot">
                                                    <img src="<?php echo IMAGE_FILE_URL.$item->file_path; ?>"alt="User Panel">

						</div>
                        </div><?php
                                                
                                            } // End foreach
                                            
                                        }
                                        if(!empty($adminPanelArr)) {
                                        ?>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="main_headings">
    						<h6>Admin Panel </h6>
    					</div>
                    </div>
                    <?php
                        foreach($adminPanelArr as $item){
                            ?>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                            <div class="sceenshot">
                                <img  class="img-responsive"src="<?php echo IMAGE_FILE_URL.$item->file_path; ?>"  alt="User Panel">
						    </div>
                        </div>
                                                <?php
                                            } // End foreach

                                        }
                                        ?>

				<?php PageContext::renderPostAction('freetrial');?>
				<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
</div>