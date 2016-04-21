<script type="text/javascript">
function change_status(product_id){
    if(confirm("Are you sure to change the status of the product?")){
        var frm = document.frmChangeStatusProduct;
        frm.product_id.value = product_id;
        frm.submit();
    }
}
function clear_search(){
   var frm = document.frmSearchProduct;
   frm.search.value = "";
   frm.action = "<?php echo BASE_URL; ?>cms?section=products&storeid=<?php echo PageContext::$response->storeId; ?>";
   frm.submit();
}
</script>
<?php
if(trim($_GET['sort_order']) <> ""){
    if(trim($_GET['sort_order']) == "ASC"){
        $sort_order = "DESC";
    }else{
        $sort_order = "ASC";
    }
}else{
    $sort_order = "ASC";
}
if(trim($_GET['page']) <> ""){
    $page = trim($_GET['page']);
}else{
   $page = 1;
}
$extra_parameters = "";
if(trim($_GET['sort_item']) <> "" && trim($_GET['sort_order']) <> ""){
    $extra_parameters = "&sort_item=".trim($_GET['sort_item'])."&sort_order=".trim($_GET['sort_order']);
}
?>
<div class="section_list_view ">
    <div class="tophding_blk">

        <div class="input-append pull-right srch_pad">
            <form class="cmxform" id="frmSearchProduct" name="frmSearchProduct" action="<?php echo BASE_URL; ?>cms?section=products&storeid=<?php echo PageContext::$response->storeId.$extra_parameters; ?>" method="post" >
                <input type="hidden" name="action" value="search">
                <input name="search" autocomplete="off" id="searchText" type="text" class="input-medium have-margin10" placeholder="Search Products" value="<?php echo PageContext::$response->txtSearch; ?>">
                <input name="btnSearch" type="submit" id="section_search_button" class="btn btn-info searchBtn" value="">
                <input name="btnClear" type="button" id="section_search_button" class="btn btn-info searchBtn" style="margin-left: 10px; background-image:none!important;" value="Clear" onclick="clear_search()">
            </form>
            <form class="cmxform" id="frmChangeStatusProduct" name="frmChangeStatusProduct" action="<?php echo BASE_URL; ?>cms?section=products&storeid=<?php echo PageContext::$response->storeId.$extra_parameters; ?>" method="post" >
                <input type="hidden" name="action" value="changestatus">
                <input name="product_id" id="product_id" type="hidden" value="">
                <input name="store_id" id="store_id" type="hidden" value="<?php echo PageContext::$response->storeId; ?>">
            </form>
        </div>


        <span class="legend hdname hdblk_inr">
            <div class="hdblk_inr">Section : <?php echo ucfirst(PageContext::$response->store_name)."&nbsp;"; ?>Store Products</div>
        </span>

        <div id="jqMessageConatainer">
        <?php if (!empty(PageContext::$response->message)) { ?>
            <div class="alert alert-<?php echo PageContext::$response->successError; ?>">
                <button class="close" data-dismiss="alert" type="button">x</button>
                <?php echo PageContext::$response->message; ?>
            </div>
        <?php } ?>
        </div>





    </div>

    <div class="clear">&nbsp;</div>
    <div align="right"><a href="<?php echo BASE_URL; ?>cms?section=domains">Back</a></div><div style="clear:both;">&nbsp;</div>



    <table width="100%" id="tbl_activities" class="cms_listtable table table-striped table-bordered table-hover ">
        <tbody>
            <tr class="heading1">
                <th width="20%" class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=product_name&sort_order=".$sort_order; ?>">Product Name</a></th>
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=category_name&sort_order=".$sort_order; ?>">Category</a></th>
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=inv_sku&sort_order=".$sort_order; ?>">SKU</a></th>
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=retail_price&sort_order=".$sort_order; ?>">Retail Price</a></th>
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=wholesale_price&sort_order=".$sort_order; ?>">Wholesale Price</a></th>
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=stock&sort_order=".$sort_order; ?>">Stock</a></th>
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=weight&sort_order=".$sort_order; ?>">Weight</a></th>
                <!--<th class="table-header">Type</th>-->
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=vendor_name&sort_order=".$sort_order; ?>">Vendor</a></th>
                <th class="table-header"><a href="<?php echo BASE_URL."cms?section=products&storeid=".PageContext::$response->storeId."&page=".$page."&sort_item=status&sort_order=".$sort_order; ?>">Status</a></th>
                <th class="table-header">Operations</th>
            </tr>
            <?php
            if (!empty(PageContext::$response->postedData)){
                $edit_params = PageContext::$response->postedData;
            }
            if(!empty(PageContext::$response->pageContents)){
                foreach (PageContext::$response->pageContents as $row){
                    ?>
                    <tr>
                        <td><?php if(trim($row["product_name"]) <> "") echo $row["product_name"]; else echo "--";  ?></td>
                        <td><?php if(trim($row["category_name"]) <> "") echo $row["category_name"]; else echo "--"; ?></td>
                        <!--<td>
                            <?php
                            /*
                            if (strlen($row["product_long_description"]) > 150) {
                                echo substr($row["product_long_description"], 0, 150) . '..';
                            } else {
                                echo $row["product_long_description"];
                            }
                            */
                            ?>
                        </td>-->
                        <td><?php if(trim($row["inv_sku"]) <> "") echo trim($row["inv_sku"]); else echo "--"; ?></td>
                        <td><?php if(trim($row["retail_price"]) <> "") echo PageContext::$response->currency.number_format($row["retail_price"],2); else echo "--"; ?></td>
                        <td><?php if(trim($row["wholesale_price"]) <> "") echo PageContext::$response->currency.number_format($row["wholesale_price"],2); else echo "--"; ?></td>
                        <td><?php if(trim($row["prodtype"]) == "Non-Digital"){ if(trim($row["stock"]) <> ""){ echo trim($row["stock"]);} else{ echo "--"; } }else echo "--"; ?></td>
                        <td><?php if(trim($row["weight"]) <> "") echo trim($row["weight"])." lbs"; else echo "--"; ?></td>
                        <td><?php if(trim($row["vendor_name"]) <> "") echo trim($row["vendor_name"]); else echo "--"; ?></td>
                        <!--<td><?php if(trim($row["product_type"]) <> "") echo trim($row["product_type"]); else echo "--"; ?></td>-->
                        <td>
                            <?php
                            $status         = ($row["status"] == "A")?'Active':'Inactive';
                            $statusClass    = ($row["status"] == "A")?'btn-success':'btn-danger';
                            ?>
                            <div>
                                <a class="btn btn-mini <?php echo $statusClass; ?>" href="javascript:change_status('<?php echo trim($row["product_id"]); ?>');"><?php echo $status;?></a>
                            </div>
                        </td>
                        <td>
                            <a href="<?php echo "http://".PageContext::$response->storeDomainName; ?>/products/show/<?php echo trim($row["seo_slug"])."/".$row["product_id"]; ?>" title="View Details" target="_blank">View Details</a>&nbsp;
                        </td>
                    </tr>
                    <?php
                    }
                }else{
                ?>
                <tr>
                    <td align="center" colspan="6">No Records Found</td>
                </tr>
                <?php
                }
                ?>
        </tbody>
    </table>
</div>
<div class="more_entries">
    <div class="wp-pagenavi" align="center">
        <?php
        if(!empty(PageContext::$response->pageContents) && PageContext::$response->pageInfo['maxPages']>1) {
            echo Admincomponents::adminApiPaginationContent(PageContext::$response->pageInfo, BASE_URL.'cms?section=products&storeid='.PageContext::$response->storeId,$extra_parameters);
        } ?>
    </div>
</div>
