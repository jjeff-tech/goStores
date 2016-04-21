<?php 
 $HeaderBanner = PageContext::$response->HeaderBanner;
    $HeaderBannerCount = 0;
?>
<script src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/js/multislider.js"></script>
<link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.5/slick.min.css'>
<section class="contentarea">
    <div class="full-width">
        <script>$('#carouselFade').carousel();</script>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
           
           <?php 
           if($HeaderBanner){
           foreach($HeaderBanner as $key => $HeaderBannerRow){   ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $key; ?>" class="<?php if($key==0){echo "active";}?>"></li>
           <?php }} ?>
            
        
         
            
          </ol>
    <?php
    // Banner Display Start
   
    foreach($HeaderBanner as $key => $HeaderBannerRow){
        if(is_file(FILE_UPLOAD_DIR.$HeaderBannerRow->file_path)){
            ++$HeaderBannerCount;
        }
    }
    if($HeaderBannerCount > 0){
    ?>
  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <?php
    $count = 1;
    foreach($HeaderBanner as $key => $HeaderBannerRow){
        if(is_file(FILE_UPLOAD_DIR.$HeaderBannerRow->file_path)){
          if (!preg_match("~^(?:f|ht)tps?://~i", $HeaderBannerRow->vBannerUrl)) {
            $HeaderBanner_Url = "http://" . $HeaderBannerRow->vBannerUrl;
          } else {
            $HeaderBanner_Url = $HeaderBannerRow->vBannerUrl;
          }
          if($count == 1){
            $bannerClass = "item active";
          }else{
            $bannerClass = "item";
          }
     ?>
    <div class="<?php echo $bannerClass; ?>">
        <div class="carousel-caption home-bnr">
            <div class="full-width">
                <a href="<?php echo $HeaderBanner_Url;?>" target="_blank"  onclick="setClickCount(<?php echo $HeaderBannerRow->nBannerId;?>);">
                <h3>Ecommerce Solutions</h3>
                <h2>Create free online store </h2>
                <p>in just 10 Minutes.</p>
            </a>
            </div>
            <div class="full-width  tp-mg-btn">                
                <a class="big-btn" href="<?php if(PageContext::$response->userId){ echo ConfigUrl::base()."user/dashboard"; }else{ echo ConfigUrl::base()."signup"; } ?>">Get Started</a>
            </div>
        </div>

        <a href="<?php echo $HeaderBanner_Url;?>" target="_blank"  onclick="setClickCount(<?php echo $HeaderBannerRow->nBannerId;?>);">
        <img onclick="setClickCount(<?php echo $HeaderBannerRow->nBannerId;?>);" src="<?php echo IMAGE_FILE_URL.$HeaderBannerRow->file_path;?>">
        </a>
    </div>
    <?php
            $count++;
        }
    }
    ?>
    </div>
    <?php } ?>

       <!-- Controls -->
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>






    <?php /* <div class="homepage_banner_wrapper">
        <?php
            // Banner Display Start
            $HeaderBanner = PageContext::$response->HeaderBanner;

            $HeaderBannerCount = 0;
            foreach($HeaderBanner as $key => $HeaderBannerRow){
                if(is_file(FILE_UPLOAD_DIR.$HeaderBannerRow->file_path)){
                    ++$HeaderBannerCount;
                }
            }

            ?>
        <?php $bannerClass = ($HeaderBannerCount >  0)? 'homepage_banner' : 'homepage_bannerimage'; ?>
        <div class="<?php echo $bannerClass; ?>">

            <div class="theme-default">
            <?php if($HeaderBannerCount > 0) { ?>
            <div id="slider" class="nivoSlider">
             <?php
             $count = 1;
                 foreach($HeaderBanner as $key => $HeaderBannerRow)
                {

                    if(is_file(FILE_UPLOAD_DIR.$HeaderBannerRow->file_path)){

                      if (!preg_match("~^(?:f|ht)tps?://~i", $HeaderBannerRow->vBannerUrl)) {
                        $HeaderBanner_Url = "http://" . $HeaderBannerRow->vBannerUrl;
                      } else {
                        $HeaderBanner_Url = $HeaderBannerRow->vBannerUrl;
                      }
                 ?>
                <a href="<?php echo $HeaderBanner_Url;?>" target="_blank"  onclick="setClickCount(<?php echo $HeaderBannerRow->nBannerId;?>);">
                <img src="<?php echo IMAGE_FILE_URL.$HeaderBannerRow->file_path;?>" alt="" width="1250" height="400">
                </a>
                <?php
                 $count++;
                }
                }
                ?>
            </div>
            <?php } ?>
            </div>


        </div>
    </div> */ ?>

    </div>
    <div class="clear"></div>
    <div class="content_area_wrapper for_bg">
        <div class="content_area">
         <div class="container">
          <div class="col-xs-12 col-sm-12 col-md-12">
                <?php PageContext::renderPostAction('freetrial');?>
            </div>
            </div>	
           <!-- <div class="container">
        </div>	            <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-12 main-titile sec-pad">
                <h2>Distributor Integration</h2>
                <h4>Realtime Catalog Synchronization with Point and Click  Order Fulfillment and Dropshipping</h4>

                <div id="mixedSlider">
                    <div class="MS-content">
                <?php                      if(PageContext::$response->distributors) { foreach (PageContext::$response->distributors as $key=>$val)
                 { ?>
                        <div class="item">
                            <div class="imgTitle">

                                
                                <a href="<?php echo $val->vDistributorLink?>" target="_blank">    <img src="<?php echo ConfigUrl::base(); ?>project/files/<?php echo $val->vDistributorImagePath?>" alt="<?php echo $val->vDistributorName?>"/ class="img-responsive"> </a>
                            
                            </div>


                        </div>
                        
<?php } } ?>




                    </div>
                    <div class="MS-controls">
                        <button class="MS-left">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/left.png">
                        </button>
                        <button class="MS-right">
                         <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/right.png">
                        </button>
                    </div>
                </div>
            </div>
</div>-->


            <div class="createstore4steps" id="whoweare">
                 <div class="container">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <h2 class="step-titile">Create an online store in just 4 easy steps</h2>
                </div>
<div class="full-width tp-mg-nw">
                <div class="col-md-4">
                    <div class="full-width">
                    <div class="srv-bx-inr">
                        <div class="image">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/storefront.png">
                        </div>
                     <h5>Select Domain Name</h5>
                      <p itemprop="description">Start your business by choosing a domain or subdomain and start creating your own store
!</p>
                      </div>
                  </div>


                       <div class="full-width tp-mg-lw dis-none">
                    <div class="srv-bx-inr">
                        <div class="image">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/add-product.png">
                        </div>
                     <h5>Select Plan Type</h5>
                      <p itemprop="description">Choose a plan which best suits your business requirement. We have custom plans that suit small, medium and large businesses.</p>
                      </div>
                  </div>

                  <div class="full-width tp-mg-lw">
                    <div class="srv-bx-inr">
                        <div class="image">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/online-orders.png">
                        </div>
                     <h5>Make Payment</h5>
                      <p itemprop="description">Make the required payment with your credit card seamlessly</p>
                      </div>
                  </div>

                   <div class="full-width tp-mg-lw dis-none">
                    <div class="srv-bx-inr">
                        <div class="image">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/add-product.png">
                        </div>
<!--                     <h5>That's It!</h5>
                      <p itemprop="description">Drop ship accessories directly to your customer, or firearms directly
from the supplier to the transfer FFL for customer pickup and save $$
on double shipping.</p>-->
                      </div>
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="full-width">
                    <h3 class="inner-heading">WHO WE ARE ?</h3>
                    <p class="cont-txt">Want to take your business online?
iScripts GoSotores is a flexible shop builder cart that allows you to build, manage and run your own online business. Add your own logos, categories, site content and products while managing payments, shipping and added bonus features included within the cart.   You can sell products to your customers and use the software simply as an e-commerce shopping cart platform. Buyers will send payment through one of our trusted gateways for the product and the transaction will occur. 
</p>

<!--<div class="half-div border-ryt new-riht-pad">
    <h4 class="number-titile txt-right"> 200K+</h4>
    <h5 class="numb-text txt-right">Drop-shippable Products</h5>
</div>

<div class="half-div new-lft-pad">
    <h4 class="number-titile"> 15</h4>
    <h5 class="numb-text">Fully Automated Suppliers</h5>
</div>-->
<div class="full-width">
<a href="<?php echo ConfigUrl::base(); ?>aboutus" class="more-btn">More Information</a>
</div>
                    </div>
                </div>
                <div class="col-md-4">
               <div class="full-width no-dis">
                    <div class="srv-bx-inr">
                        <div class="image">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/add-product.png">
                        </div>
                     <h5>Add Products to Your Storefront</h5>
                     <p itemprop="description">
                         
                        Add products and start selling within minutes.
                         
                     </p>
                      </div>
                  </div>

                            <div class="full-width tp-mg-lw no-dis">
                  <div class="srv-bx-inr">
                        <div class="image">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/save-money.png">
                        </div>
                     <h5>That's It!
</h5>
                      <p itemprop="description">That's all you need to start your business journey. </p>
                      </div>
                  </div>





                </div>

</div>


               </div>
                <div class="clear"></div>
            </div>
<!--              <div class="container" id="partners">
                  <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-12 main-titile sec-pad">
                <h2>Our Preferred Partners</h2>
                <h4>We Only Work With the Best</h4>

                <div id="mixedSlider_1">
                    <div class="MS-content">

                     <?php      if(PageContext::$response->partners){                foreach (PageContext::$response->partners as $key=>$val)
{ ?>
                        <div class="item">
                            <div class="imgTitle">

                                
<a href="<?php echo $val->vPartnerLink?>" target="_blank">    <img src="<?php echo ConfigUrl::base(); ?>project/files/<?php echo $val->vPartnerImage?>" alt="<?php echo $val->vPartnerName?>"/ class="img-responsive"> </a>
                            
                            </div>


                        </div>
                        
                     <?php } } ?>
                       



                    </div>
                    <div class="MS-controls">
                        <button class="MS-left">
                            <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/left.png">
                        </button>
                        <button class="MS-right">
                         <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/theme2/images/right.png">
                        </button>
                    </div>
                </div>
            </div>
</div>
            <div class="clear"></div>
        </div>-->

<!-- <div class="ecommerce-features-outer" id="ourfeatures">
    <div class="container">
         <h2 class="step-titile">Ecommerce Features</h2>
        <div class="col-md-6 btm-mrg">
<div class="full-width text-center">
    <div class="e-f-icons">
    <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/e-com.png">
</div>

    <div class="eco-fe-inner">
        <h3>All In One E-Commerce Platform</h3>
        <ul>
           
           
          
            <li>Stunning User Friendly Interface</li>
            <li>Many Professional Customizable Templates</li>
            <li>Desktop, Tablet or Smartphone Compatible</li>
            <li>Sell Even When Retail Store Front Is Closed</li>

        </ul>
    </div>

</div>

        </div>


        <div class="col-md-6 btm-mrg">
<div class="full-width text-center">
    <div class="e-f-icons">
    <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/inventory.png">
</div>

    <div class="eco-fe-inner">
        <h3>Inventory Management & Full Automation (optional)
</h3>
        <ul>
           
            
            <li>Supplier Catalog Synchronization</li>
             <li>Custom Banners &amp; Content Pages</li>
            <li>Realtime Price and Qty Updates</li>
            <li>Customize Products Your Way</li>
           
           

        </ul>
    </div>

</div>

        </div>

            <div class="col-md-6">
<div class="full-width text-center">
    <div class="e-f-icons">
    <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/powerful-tool.png">
</div>

    <div class="eco-fe-inner  top-line">
        <h3> Powerful Tools To Help You Succeed</h3>
        <ul>
            <li>Extensive Reports
</li>
            <li>Integrated Newsletter’s
</li>
            <li>Gift Card Transactions
</li>
           
            
            

        </ul>

</div>

        </div>

    </div>

            <div class="col-md-6">
<div class="full-width text-center">
    <div class="e-f-icons">
    <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/art-structure.png">
</div>

    <div class="eco-fe-inner  top-line">
        <h3>State Of The Art Structure</h3>
        <ul>
            <li>Runs on Amazon’s Cloud
</li>
            <li>Architected for 99.9% Monthly Uptime

</li>
            <li>Autoscaling Adds Servers as Needed

</li>
           
            
            <li>No Traffic or Bandwidth Limits

</li>

        </ul>

</div>

        </div>

    </div>


</div>

    </div> -->

    

<!--<div class="container">
	<div class="col-xs-12 col-sm-12 col-md-12 main-titile sec-pad">
		<div class="payment-method-outer">

	<img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/authorizenet_logo.png">
  <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/bluedog-logo.png">
  <img src="<?php echo ConfigUrl::base(); ?>project/styles/themes/<?php echo THEME;?>/images/stripe-logo.png">

</div>
</div>
</div>-->


    </div>

    <div class="clear"></div>
</section>
</div>
<script type="text/javascript">
    /*
$(function(){
  $('.slider').glide({
    autoplay: 3500,
    hoverpause: true, // set to false for nonstop rotate
    arrowRightText: '&rarr;',
    arrowLeftText: '&larr;'
  });
});
*/
</script>



<script>
$('#basicSlider').multislider({
            continuous: true,
            duration: 2000
        });
        $('#mixedSlider').multislider({
            duration: 750,
            interval: 3000
        });

$('#basicSlider_1').multislider({
            continuous: true,
            duration: 2000
        });
        $('#mixedSlider_1').multislider({
            duration: 750,
            interval: 3000
        });

</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
