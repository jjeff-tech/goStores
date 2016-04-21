<div class="section_list_view">
    <div class="row have-margin">
        <div class="tophding_blk">
            <div class="input-append pull-right srch_pad">
                <form class="cmxform" id="frmSearchPlan" action="<?php echo BASE_URL; ?>cms?section=orders" method="post" >
                    <input type="hidden" name="action" value="search">
                    <select name="cmbSearchType" id="bugType" class="input-medium have-margin10">
                        <option value="0" >Select Type</option>
                        <option value="1" <?php if(PageContext::$response->searchType==1){?>selected="selected" <?php } ?> >User</option>
                        <option value="2" <?php if(PageContext::$response->searchType==2){?>selected="selected" <?php } ?> >Plan</option>
                        <option value="3" <?php if(PageContext::$response->searchType==3){?>selected="selected" <?php } ?> >Domain Name</option>
                    </select>&nbsp;&nbsp;
                    <input name="txtSearch" id="searchText" type="text" class="input-medium have-margin10" placeholder="Search" value="<?php echo PageContext::$response->txtSearch; ?>">
                    <input name="btnSearch" type="submit" id="section_search_button" class="btn btn-info searchBtn" value="">
                </form>
            </div>
            <span class="legend hdname hdblk_inr"><div class='hdblk_inr'>Section : Service History</div></span>
            
            
       </div>
        
        

         
        <!-- End Search Form -->            
    </div> 
          
         <table id="tbl_activities" class="cms_listtable table table-striped table-bordered table-hover "> 
              <tbody>
           <tr class="heading1">
                <td class="table-header"><a href="<?php echo BASE_URL?>cms?section=orders&cmbSearchType=<?php echo PageContext::$response->searchType?>&txtSearch=<?php echo PageContext::$response->txtSearch?>&orderField=vServiceName&orderType=<?php echo PageContext::$response->orderType;?>"><b>Plan</b></a></td>
                <td class="table-header"><a href="<?php echo BASE_URL?>cms?section=orders&cmbSearchType=<?php echo PageContext::$response->searchType?>&txtSearch=<?php echo PageContext::$response->txtSearch?>&orderField=user&orderType=<?php echo PageContext::$response->orderType;?>"><b>User</b></a></td>
                <td class="table-header"><b>Service Period</b></td>
                <td class="table-header"><b>Domain Name</b></td>
                <!--<td class="table-header"><b>Status</b></td>-->
            </tr>
                <?php
                if(!empty(PageContext::$response->pageContents)) {
                    $i=PageContext::$response->pageInfo['base'];
                    foreach(PageContext::$response->pageContents as $row) {                         
                        $i++;
                        $className=($i%2) ? 'column1' : 'column2';
                        ?>
                        <tr class="<?php echo $className ?>" id="item_<?php echo $i?>">
                            <td><a  href="javascript:void(0)" class="serviceDetails" name= "<?php echo $row->nPLId;?>"><?php echo $row->vServiceName; ?></a><br>
                            <?php //echo ($row->vSubDomain<>NULL)?$row->vSubDomain:$row->vDomain ;?>
                            <!--a href="<?php //echo BASE_URL?>cms?section=service_details&parent_id=<?php //echo $row->nPLId;?>" class="cms_list_operation">View Details</a-->
                            </td>
                            <td><a href="javascript:void(0)" class="userDetails" name="<?php echo $row->nUId;  ?>" ><?php echo ucwords($row->user); ?></a></td>
                            <td><?php echo ($row->dDateStart); echo  ' - ';?><?php echo ($row->dDateStop);?></td>
                             <td><?php echo Admincomponents::getStoreHost($row->nPLId);
                        //echo ($row->vSubDomain<>NULL)?$row->vSubDomain.".".$row->vserver_name:$row->vDomain.".".$row->vserver_name ;?></td>
                        </tr>
                        <?php
                    }
                } else {
                ?>
                    <tr class="column1">
                        <td colspan="5" style="text-align: center">No Results Found</td>
                    </tr>
                <?php
                }
                ?>
              </tbody>
        </table>
        <?php if (PageContext::$response->resultPageCount > 1) { ?>
            <div class="pagination pagination-right ull-right">
                <?php
                echo PageContext::$response->pagination;
                ?>
            </div>
        <?php } ?>
 
        
</div>
