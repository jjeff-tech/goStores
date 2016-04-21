<style>
.nav-tabs > li > a {
    
    border-radius: 0px;
}
li.active{
background: #f61d26;
}
.plan_nav ul li {
   
}
.tab-content{
    
    background-color: #ffffff;
}
</style>  
<?php //print_r(PageContext::$response->planDetails); ?>


<div class="container_plan" >
	


<div class="plan_nav">
<!-- Nav tabs -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
    
     <?php foreach (PageContext::$response->planDetails as $k=>$v){ 
         if($v['vType']!='free'){?>
    
  <li class="nav-item <?php if($k==3){ echo "active";}?>">
    <a class="nav-link" id="<?php echo $v['plan_id']?>-tab" data-toggle="tab" href="#<?php echo $v['plan_id']?>" role="tab" aria-controls="<?php echo $v['plan_id']?>" aria-selected="true"><?php echo $v['plan_name']?></a>
  </li>

     <?php }} ?>
</ul>
</div>
<div class="clearfix"></div>
<!-- Tab panes -->
<div class="tab-content">
     <?php foreach (PageContext::$response->planDetails as $k=>$v){ 
         if($v['vType']!='free'){
         ?>
  <div class="tab-pane <?php if($k==3){ echo "active";}?>" id="<?php echo $v['plan_id']?>" role="tabpanel" aria-labelledby="<?php echo $v['plan_id']?>-tab">
      
      
     <div class="plan_cont_main_outer">
		<div class="plan_cont_outer">
			<div class="top">
				<h2>E-Commerce Suite</h2>
				<div class="top_cont">
					<div class="top_cont_L">
						<h3><span>$</span><?php echo $v['plan_cost']?></h3>
						<h4>Total</h4>
						<!-- <h5>$<?php echo $v['permonth_price']?> / Month ($<?php echo $v['savings']?> savings)</h5> -->
					</div>
					<!-- <div class="top_cont_R">
						<div class="top_cont_R_inner">
							<h2>Transaction Fees</h2>
							<h3><?php echo $v['trasaction_fee']?></h3>
						</div>
						<div class="top_cont_R_inner">
							<h2>Make Ready Payments</h2>
							<h3><?php echo $v['makeready_payments']?></h3>
						</div>
						<div class="top_cont_R_inner">
							<h2>3rd Party Gateway</h2>
							<h3>$<?php echo $v['third_party_transaction']?></h3>
						</div>
					</div> -->
				</div>
				<!-- <div class="middle_cont_main_outer">
					<h2>Distributor Integration (optional)</h2>
					<div class="clearfix"></div>
					<div class="middle_cont">
						<div class="middle_cont_L">
							<ul>
								<li><?php echo nl2br($v['vServiceDescription'])?></li>
							</ul>
						</div>
						<div class="middle_cont_R">
                                                        <?php if($v['dropship_fee']){ ?><span>$</span><?php echo $v['dropship_fee']?> <?php } ?>
							<div class="clearfix"></div>
							<dd>Per month</dd>
						</div>
					</div>
					<div class="clearfix"></div>
				</div> -->
				<!-- <div class="bottom_cont">
					 <?php if($v['dropship_fee']){ ?>
					No discount on Drop ship add on, Always <?php echo '$'.$v['dropship_fee']?>/Month
                                    <?php } ?>
				</div> -->
				<div class="clearfix"></div>
			</div>
			<div class="content">
				<h2>Standard Features</h2>
				<ul>
                                 <?php
                                                if(!empty($v['plan_features'])) {  
                                                                                                                       
                                                   
                                                    foreach($v['plan_features'] as $feature_id => $plan_features) {
                                                        ?>
                                <li class="plan-feature"><h5><?php echo $plan_features; ?></h5> <h4><?php echo $v['feature_value'][$feature_id]; ?></h4></li>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php } ?>
                              </ul>
				<div class="btt_outer">
					<a href="javascript:void(0)" style=""id="<?php echo $v['plan_id']?>" class="jqPlans">Select</a>
				<div class="clearfix"></div>
				</div>
			</div>
		<div class="clearfix"></div>
		</div>
	<div class="clearfix"></div>
	</div>
  
  
  </div>
     <?php } }?>
</div>



</div>

