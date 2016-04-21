  <div class="right_column">
    <div class="form_container">
      <div class="form_top">PRODUCTS</div>
      <div class="form_bgr">
	  
<table width="95%" cellspacing="0" cellpadding="0" border="0" align="center">
  <tbody>
    <tr class="heading1">
        <td width="6%" align="left" style="margin-left: 2px;">Sl No.</td>
      <td width="25%" align="left">Product Name</td>
      <td width="25%" align="left">Product Version</td>
      <td width="20%" align="left">Domain</td>
      <td width="12%" align="left">Last Updated</td>
      <td width="12%" align="left">Plan Expiry</td>
    </tr>
    <?php
        $i=0;
        if(!empty($this->pageContents)) {
            $i=$this->pageInfo['base'];
            foreach($this->pageContents as $row) {
                $i++;
                $className=($i%2) ? 'column2' : 'column1';
                ?>
    <tr class="<?php echo $className;?>">
      <td align="left"><?php echo $i;?></td>
      <td align="left"><?php echo $row->vPName;?></td>
      <td align="left"><?php echo $row->vVersion;?></td>
      <td align="left"><?php echo $row->vSubDomain;?></td>
      <td align="left"><?php echo Utils::formatDate($row->dLastUpdated);?></td>
      <td align="left"><?php echo Utils::formatDate($row->dPlanExpiryDate);?></td>
      
    </tr>
    <?php
                    }
                } else {
                    ?>
            <tr class="column1">
                <td align="center" colspan="6">No Results Found</td>
            </tr>
                    <?php
                }
                ?>
  </tbody>
</table>
	<div class="more_entries">
            <div class="wp-pagenavi">
            <?php if(!empty($this->pageContents)) { echo Admincomponents::adminPaginationContent($this->pageInfo, BASE_URL.'user/products/'); } ?>
            </div>
        </div>
		
		
		
      </div>
      <div class="form_bottom"></div>
    </div>
  </div>
			





