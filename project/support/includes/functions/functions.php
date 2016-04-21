<?php


function checkKbRatingExists($userId,$kbId) {
    
    $ip              = '';
if($userId <= 0){
   $ip = getClientIP();
} 
 $sqlRatingExist  =  " SELECT sKBRId FROM  sptbl_kb_rating
                      WHERE nKBID ='".$kbId."'";

if($userId > 0){
    $sqlRatingExist  .= " AND nUserId ='".$userId."'";
}else{
    $sqlRatingExist  .= " AND vIP ='".$ip."'";
}


    global $conn;
    /*$sqlRatingExist  =  "SELECT sKBRId FROM  sptbl_kb_rating
                          WHERE nUserId ='".$userId."'
                          AND nKBID ='".$kbId."'";*/

    $resRatingExist  =   executeSelect($sqlRatingExist,$conn);

    if(mysql_num_rows($resRatingExist)==0){
        $existsFlag = 0;
    }else{
        $existsFlag = 1;
    }

    return $existsFlag;
}

function getKbRatingValue($kbId) {
    global $conn;
    $sqlRatingValue  =  "SELECT round( COALESCE(SUM( nMarks ) / COUNT( sKBRId ),0) ) AS RATE FROM  sptbl_kb_rating
                         WHERE nKBID ='".$kbId."'";
    
    $resRatingValue = mysql_query($sqlRatingValue) or die(mysql_error());
    $valRatingValue = mysql_fetch_assoc($resRatingValue);

    return $valRatingValue['RATE'];
}

function getStarRatingContent($txtKbSearchid){ 

    $user_id     =   $_SESSION['sess_userid'];
    $kb_id       =   $txtKbSearchid;

    $siteUrl    = getSettingsValue('SiteURL');

    $kbRatingExists = checkKbRatingExists($user_id,$kb_id);
    //echo '<pre>'; print_r($kbRatingExists); echo '</pre>';

    if($kbRatingExists ==0) {
        $classToCall = 'jqRate';
        $cursorStyle = 'cursor:pointer';
    } else {
        $classToCall = 'jqNoRate';
        $cursorStyle = 'cursor:default';
    }
    $classToCall = 'jqRate';
    $cursorStyle = 'cursor:pointer';
    $kbRatingValue = getKbRatingValue($kb_id); 

    $str = '<div class="rate_div" >
            <div class="left">'.TEXT_KNOWLEDGEBASE_RATE.' &nbsp;&nbsp;:&nbsp;&nbsp;</div>';
    for ($i = 1; $i <= 5; $i++) { 

        if ($i <= $kbRatingValue) {
            $image = "star-yellow.png";
        } else {
            $image = "star-grey.png";
        }
         
        $str .= '<span class="renderRate '.$classToCall.'" style="'.$cursorStyle.'" id="renderRate_'.$i.'" rateVal="'.$i.'" ><img style="margin-top:4px;" src="'.$siteUrl.'images/' . $image.'"  /></span>';
    }
    $str .='</div>'; 

    return $str;

}


function getMostlyReviewedKbs() {
    global $conn;
    $mostlyReviewedQuery = "SELECT kb.*,kbr.nMarks,kbr.nKBID,c.vCatDesc FROM sptbl_kb kb
                           INNER JOIN sptbl_kb_rating kbr ON kb.nKBID = kbr.nKBID
                           INNER JOIN sptbl_categories c ON c.nCatId = kb.nCatId
                           WHERE kb.vStatus = 'A' ORDER BY kbr.nMarks DESC LIMIT 0,5";
    $mostlyReviewedRes  =  executeSelect($mostlyReviewedQuery, $conn);

    return $mostlyReviewedRes;
}

function getRecentlyAddedKbs() {
    global $conn;
    $recentlyAddedQuery = "SELECT kb.*,c.vCatDesc FROM sptbl_kb kb
                           INNER JOIN sptbl_categories c ON c.nCatId = kb.nCatId
                           WHERE kb.vStatus = 'A' ORDER BY kb.dDate DESC LIMIT 0,6";
    $recentlyAddedRes  =  executeSelect($recentlyAddedQuery, $conn);

    return $recentlyAddedRes;
}

function getCategoriesWithKbCount($limit,$type) {
    global $conn;
    $catWithKbCountQuery = "SELECT count(kb.nKBID) as kbCount,c.vCatDesc,c.nCatId FROM sptbl_kb kb
                            INNER JOIN sptbl_categories c ON c.nCatId = kb.nCatId
                            WHERE kb.vStatus = 'A' GROUP BY kb.nCatId ";
    if($type=='detail'){
        if($limit){
            $catWithKbCountQuery .= $limit;
        }
    }else{
        if($limit > 0){
            $catWithKbCountQuery .= "LIMIT 0,$limit";
        }
    }
    $catWithKbCountRes  =  executeSelect($catWithKbCountQuery, $conn);

    return $catWithKbCountRes;
}

function getIndCategoryData($catId) {
    global $conn;
    $categoryData = "SELECT vCatDesc,nCatId FROM sptbl_categories
                          WHERE nCatId=$catId";
    $categoryDataRes  =  executeSelect($categoryData, $conn);
    $categoryDataVal  = mysql_fetch_assoc($categoryDataRes) or die(mysql_error());

    return $categoryDataVal;
}


function getKbByCategory($catId,$limit) {
    global $conn;
    $kbByCat = "SELECT kb.*,c.vCatDesc,c.nCatId FROM sptbl_kb kb
                INNER JOIN sptbl_categories c ON c.nCatId = kb.nCatId
                WHERE kb.vStatus = 'A' AND kb.nCatId=$catId";
    
    if($limit){
        $kbByCat .= $limit;
    }
    
    $kbByCatRes  =  executeSelect($kbByCat, $conn);

    return $kbByCatRes;
}

//Function to generate the breadcrumb
function getBreadCrumb($links) {
    if(sizeof($links)>0) {
        $breadcrumb = '';
        $arrow = '';
        $count = 0;
        foreach($links as $title=>$link) {
            $count++;
            if($count==count($links)) $activeClass = "class=bc01-active";
            $breadcrumb.=($link!='')?'<li '.$activeClass.' ><a href="'.SITE_URL.$link.'">'.
                            $arrow.stripslashes($title).'</a></li> ':stripslashes($title);
            $arrow = '<span>&nbsp; &raquo; &nbsp;</span>';
        }
        $breadcrumb = '<div class="bc01"><ul>'.$breadcrumb.'</ul><div class="clear"></div></div>';
    }
    return $breadcrumb;
}


function getKbData($kbId) {
    global $conn;
    $kbQuery = "SELECT kb.*,c.vCatDesc FROM sptbl_kb kb
                INNER JOIN sptbl_categories c ON c.nCatId = kb.nCatId
                WHERE kb.vStatus = 'A' AND nKBID='".$kbId."'";
    $kbRes  =  executeSelect($kbQuery, $conn);

    return $kbRes;
}

	
?>
