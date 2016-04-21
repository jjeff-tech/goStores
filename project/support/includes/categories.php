<?php 
$fld_prio = $_SESSION["sess_priority"];
$var_userid = $_SESSION["sess_userid"];

if ($_REQUEST["numBegin"]) {
    $var_numBegin = $_REQUEST["numBegin"];
    $var_start = $_REQUEST["start"];
    $var_begin = $_REQUEST["begin"];
    $var_num = $_REQUEST["num"];
}
$maxPageLimit = getSettingsValue('MaxPostsPerPage');

?>
<?php
    $linkArray = array( TEXT_KB=>'knowledgebase.php',
                        KB_CATEGORIES =>'categories.php');
//echo getBreadCrumb($linkArray);
?>

<form name="frmDetail" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">

    <div class="breadcrumb_top"><a href="<?php echo SITE_URL . 'kb/';?>"><?php echo TEXT_KB?></a>&nbsp;&nbsp; >> &nbsp;&nbsp;
    <span><?php echo KB_CATEGORIES;?></span>
    </div>
    <div class="content_section">
        <div style="clear: both; overflow: hidden;" >
            <div class="content_section_title" style="float: left;">
                <h4 style="padding: 10px 0 0 10px;"><?php echo KB_CATEGORIES;?></h4>
            </div>
            <div class="kb_search" style="float: right; margin-top: 14px;" >
                <input name="txtKbTitleSearch" id="txtKbTitleSearch" style="margin-right:10px; width: 230px;" type="text" class="comm_input left"  value="<?php echo htmlentities($_REQUEST['txtKbTitleSearch']);?>">
                <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">
                <input type="button" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_SEARCH;?>" class="comm_btn left" onclick="return searchKb()">
            </div>
        </div>
        <?php
        $categoriesWithKbCount = getCategoriesWithKbCount('','detail');
        $totalrows = mysql_num_rows($categoriesWithKbCount); 
        settype($totalrows, integer);
        settype($var_begin, integer);
        settype($var_num, integer);
        settype($var_numBegin, integer);
        settype($var_start, integer);

        $var_calc_begin = ($var_begin == 0) ? $var_start : $var_begin;
        if (($totalrows <= $var_calc_begin)) {
            $var_nor = 10;
            $var_nol = 10;
            if ($var_num > $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin;
                $var_begin = $var_begin - $var_nor;
            } elseif ($var_num == $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin - $var_nol;
                $var_begin = $var_calc_begin - $var_nor;
                $var_start = "";
            }
        }
        if ($var_begin < 0)
            $var_begin = 0;

        $navigate = pageBrowser($totalrows, 10, $maxPageLimit, "", $var_numBegin, $var_start, $var_begin, $var_num);

        $categoriesWithKbCount = getCategoriesWithKbCount($navigate[0],'detail');
        ?>

        <div class="popular_kbs" >
            <ul>
                <?php while($categoriesWithKbCountVal = mysql_fetch_assoc($categoriesWithKbCount)) { //echo '<pre>'; print_r($categoriesWithKbCountVal); echo '</pre>';
                $seolinkKb=str_replace(" ","-", stripslashes($categoriesWithKbCountVal['vCatDesc']));
                $seolinkKb=preg_replace('/[^a-zA-Z0-9__.-]/s', '', $seolinkKb);
                $seolinkKb=strtolower($seolinkKb);
                $seolinkKb=substr($seolinkKb,0,100);
                $seolinkKb=str_replace("?","", $seolinkKb);
                 
                
        		?>
                <li>
                    <a href="<?php echo SITE_URL . 'kb/'.$seolinkKb.'/catid='.$categoriesWithKbCountVal['nCatId'];?>"><?php echo $categoriesWithKbCountVal['vCatDesc'];?><span class="cat_count"> (<?php echo $categoriesWithKbCountVal['kbCount'];?>)</span> </a>
                </li>
                <?php } ?>
            </ul>

            <div class="pagination_container">
                <div class="pagination_info" style="float: left; padding-top: 10px;">
                    <?php
                    if($totalrows > 0) {
                        echo($navigate[1] . "&nbsp;" . TEXT_OF . "&nbsp;" . $totalrows . "&nbsp;" . TEXT_RESULTS );
                    }
                    ?>
                </div>
                <div class="pagination_links" style="float: right;padding-top: 10px;">
                    <?php echo($navigate[2]); ?>
                    <input type="hidden" name="numBegin" value="<?php echo $var_numBegin ?>">
                    <input type="hidden" name="start" value="<?php echo $var_start ?>">
                    <input type="hidden" name="begin" value="<?php echo $var_begin ?>">
                    <input type="hidden" name="num" value="<?php echo $var_num ?>">
                </div>

            </div>
        </div>
    </div>

</form>
