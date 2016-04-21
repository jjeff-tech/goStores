
<?php $categoriesWithKbCount = getCategoriesWithKbCount(5,'home'); ?>
<div class="knwledgebase_footerrow" style="padding:5px 0 0 2px; width: 107%">
    <div class="categories" style="border-radius:0px;">
        <h4><?php echo KB_CATEGORIES; ?></h4>
        <ul>
            <?php while($categoriesWithKbCountVal = mysql_fetch_assoc($categoriesWithKbCount)) { //echo '<pre>'; print_r($categoriesWithKbCountVal); echo '</pre>'; ?>
            <li>
            <?php 
            $seolinkKb=str_replace(" ","-", stripslashes($categoriesWithKbCountVal['vCatDesc']));
            $seolinkKb=preg_replace('/[^a-zA-Z0-9__.-]/s', '', $seolinkKb);
            $seolinkKb=str_replace("?","", $seolinkKb);
            $seolinkKb=substr($seolinkKb,0,100);
            $seolinkKb=strtolower($seolinkKb);
            ?>
                <a href="<?php echo SITE_URL . 'kb/'.$seolinkKb.'/catid='.$categoriesWithKbCountVal['nCatId'];?>"><?php echo $categoriesWithKbCountVal['vCatDesc'];?><span class="cat_count"> (<?php echo $categoriesWithKbCountVal['kbCount'];?>)</span> </a>
            </li>
                <?php } ?>

            <div style="font-size: 13px;float: right; padding: 5px;">
                <a style="color:#999;" href="<?php echo SITE_URL . 'categories.php';?>"><?php echo KB_VIEW_ALL_CATEGORIES;?> </a>
            </div>
        </ul>

    </div>
    <div class="table_spacer"></div>
    
</div>