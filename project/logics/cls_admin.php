<?php

/*
 * All User Entity Logics should come here.
 */

class Admin {

    //function to get service features
    public static function getServiceFeatures($userId) {
        $db = new Db();
        $features = array();
        $features = $db->selectResult('ServiceFeatures', "nFeatureId,tFeatureName", "eStatus='Active' ");

        $var = 0;
        foreach ($features as $feature) {
            $result[$var]->value = $feature->nFeatureId;
            $result[$var]->text = $feature->tFeatureName;
            $var++;
        }
        return $result;
    }

    //function to get  domain name
    public static function getDomainName($productLookUpId) {
        User::$dbObj = new Db();
        $domainName = User::$dbObj->selectRow("ProductLookup", "vDomain", "nPLId='$productLookUpId'");
        if ($domainName == "") {
            $subdomainName = User::$dbObj->selectRow("ProductLookup", "vDomain", "nPLId='$productLookUpId'");

            $domainName = User::getSubDomainName($productLookUpId) . "." . DOMAIN_NAME;
        }
        return $domainName;
    }

    public static function getSubDomainName($productLookUpId) {
        User::$dbObj = new Db();
        $subdomainName = User::$dbObj->selectRow("ProductLookup", "vSubDomain", " nPLId='$productLookUpId'");
        return $subdomainName;
    }

    public static function getUsersCount($startDate, $endDate) {
        $db = new Db();
        $count = $db->getDataCount("User", "nUId", " where nStatus='1' AND DATE_FORMAT(dLastUpdated,'%Y-%m-%d')>= '" . $startDate . "' AND DATE_FORMAT(dLastUpdated,'%Y-%m-%d')< '" . $endDate . "'");
        return $count;
    }

    public static function getUpgradeCount($startDate, $endDate) {
        $db = new Db();
        $count = $db->getDataCount("Invoice", "nInvId", " where upgraded=1 AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')>= '" . date("Y-m-d", $startDate) . "' AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')< '" . date("Y-m-d", $endDate) . "'");
        return $count;
    }

    public static function getStoresCount($startDate, $endDate) {
        $db = new Db();
        $count = $db->getDataCount("Invoice", "nInvId", " where upgraded=0 AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')>= '" . $startDate . "' AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')< '" . $endDate . "'");
        return $count;
    }

    public static function getFreeTrialsCount($startDate, $endDate) {
        $db = new Db();
        $count = $db->getDataCount("Invoice", "nInvId", " where vSubscriptionType='FREE' AND upgraded=0 AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')>= '" . $startDate . "' AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')< '" . $endDate . "' ");
        return $count;
    }

    //function to get domain reg count
    public static function getRegistredDomainCount($startDate, $endDate) {
        $dbh = new db();
        //selected fields
        $fields = 'nInvId ';
        //join with file table to get banner image
        $join = ' LEFT JOIN  ' . $dbh->tablePrefix . 'ProductLookup AS PL ON PL.nPLId=I.nPLId';
        //where condition
        $where = " WHERE PL.nStatus=1 AND I.vSubscriptionType='PAID' AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')>= '" . $startDate . "' AND DATE_FORMAT(dGeneratedDate,'%Y-%m-%d')< '" . $endDate . "' ";
        //search condition



        $regCount = $dbh->getPagingCount($fields, 'Invoice AS I', $join, $where, $groupby, $orderType, $oderField);

        return $regCount[0]->cnt;
    }

    public static function updateSettings($data) {
        $db = new Db();
        if (is_array($data) && !empty($data)) {
            foreach ($data as $field => $val) {
                $db->updateFields("Settings", array('value' => $val), "settingfield = '$field'");
            }
        }
        return true;
    }

    public static function updateCompanyNameForSupport($data) {
        $db = new Db();
        $db->customQuery("UPDATE sptbl_companies SET vCompName = '" . addslashes($data) . "' WHERE nCompId = 1");

        return true;
    }

    public static function updateTLD($data) {
        $db = new Db();
        if (is_array($data) && !empty($data)) {
            $db->updateFields("tld", array('register_fee' => $data['priceDomiainRegistration']), "registrar = '".$data['domain_registrar']."'");
        }
        return true;
    }

    public static function updateAdminPassword($uid, $postValues) { 
        $db = new Db();        
                
        $current_pass = $db->selectQuery("SELECT password FROM cms_users WHERE type ='$uid' ");        
        $enc_current_password = md5(trim($postValues['current_password']));
        $enc_new_password = md5(trim($postValues['retype_password']));
        if(trim($postValues['current_password'])=='' && trim($postValues['new_password'])==''&trim($postValues['retype_password'])==''){
        	$message = "Please fill the fields!";
        }
        else if (trim($current_pass[0]->password) == $enc_current_password) { 
            if ($postValues['new_password'] == $postValues['retype_password']) {  
                $db->customQuery("UPDATE cms_users SET password = '" . addslashes($enc_new_password) . "' WHERE type = '" . $uid . "'");
                $message = "success";
            } else {
                $message = "New Password and Re-Type Password do not match!";
            }
        } else {
            $message = "Incorrect Current Password!";
        }
        return $message;
    }

    public static function getServerDefaultUrl($values){
        $id  =   $values['id'];
        $value  =   $values['value'];
        $serverDefaultUrl = BASE_URL.'admin/settings/ajaxMakeServerDefault?id='.$id.'&value='.$value;
        return $serverDefaultUrl;
    }

    public static function updateDefaultServer($id,$value){
        
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 0)?1:0; 
       if($defaultVal==1){
        Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."ServerInfo SET vmakethisserver_default = " . addslashes($defaultVal) . " WHERE nserver_id = '" . $id . "'");
        Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."ServerInfo SET vmakethisserver_default = " . addslashes($value) . " WHERE nserver_id != '" . $id . "'");
       }
    }

    // Banner status change ajax url
    public static function changeBannerStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $bannerStatusUrl = BASE_URL.'admin/service/ajaxBannerStatusChange?id='.$id.'&value='.$value;
        return $bannerStatusUrl;
    }

     // Function to update Banner status 
     public static function updateBannerStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 0)?1:0;
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."Banners SET vActive = '" . $defaultVal . "' WHERE nBannerId = '" . $id . "'");
    }

    // Service Feature status change ajax url
    public static function changeServiceFeatureStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $serviceFeatureStatusUrl = BASE_URL.'admin/service/ajaxServiceFeatureStatusChange?id='.$id.'&value='.$value;
        return $serviceFeatureStatusUrl;
    }

     // Function to update Banner status
     public static function updateServiceFeatureStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 'Active')?'Disabled':'Active';
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."ServiceFeatures SET eStatus = '" . $defaultVal . "' WHERE nFeatureId = '" . $id . "'");
    }

    // User status change ajax url
    public static function changeUserStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $userStatusUrl = BASE_URL.'admin/index/ajaxUserStatusChange?id='.$id.'&value='.$value;
        return $userStatusUrl;
    }

     // Function to update User status
     public static function updateUserStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 1)?2:1;
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."User SET nStatus = '" . $defaultVal . "' WHERE nUId = '" . $id . "'");
    }


    // Theme status change ajax url
    public static function changeThemeStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $themeStatusUrl = BASE_URL.'admin/service/ajaxThemeStatusChange?id='.$id.'&value='.$value;
        return $themeStatusUrl;
    }

     // Function to update Theme status
     public static function updateThemeStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 0)?1:0;
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."themes SET theme_status  = '" . $defaultVal . "' WHERE theme_id = '" . $id . "'");
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."themes SET theme_status = " . addslashes($value) . " WHERE theme_id != '" . $id . "'");
    }

    // Content status change ajax url
    public static function changeContentStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $contentStatusUrl = BASE_URL.'admin/service/ajaxContentStatusChange?id='.$id.'&value='.$value;
        return $contentStatusUrl;
    }

     // Function to update content status
     public static function updateContentStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 0)?1:0;
       Admincomponents::$dbObj->customQuery("UPDATE  ".Admincomponents::$dbObj->tablePrefix."Cms SET cms_status  = '" . $defaultVal . "' WHERE cms_id = '" . $id . "'");
    }

    // Newsletter status change ajax url
    public static function changeNewsletterScheduleStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $newsletterStatusUrl = BASE_URL.'admin/service/ajaxNewsletterStatusChange?id='.$id.'&value='.$value;
        return $newsletterStatusUrl;
    }

     // Function to update Newsletter status
     public static function updateNewsletterStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 'Active')?'Inactive':'Active';
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."EmailTemplates SET estatus  = '" . $defaultVal . "' WHERE nETId = '" . $id . "'");
    }

    // Email Scheduler status change ajax url
    public static function changeEmailSchedulerStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $emailSchedulerStatusUrl = BASE_URL.'admin/service/ajaxEmailSchedulerStatusChange?id='.$id.'&value='.$value;
        return $emailSchedulerStatusUrl;
    }

     // Function to update Newsletter status
     public static function updateEmailSchedulerStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 'Active')?'Deactive':'Active';
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."EmailTemplatesMails SET eStatus  = '" . $defaultVal . "' WHERE nETMId = '" . $id . "'");
    }


    // function to display pagination
    public static function pagination($total, $perPage  =   5, $url  =   '',$page) {

        $adjacents          =   "2";
        $page               =   ($page == 0 ? 1 : $page);
        $start              =   ($page - 1) * $perPage;
        $prev               =   $page - 1;
        $next               =   $page + 1;
        $lastPage           =   ceil($total/$perPage);
        $lpm1               =   $lastPage - 1;
        $pagination         =   "";
        if($lastPage > 1) {
            $pagination     .=  "<ul class='pagination'>";
            if($page>1)
                $pagination .=  "<li><a href='{$url}page=$prev'>&laquo;</a></li>";
            if ($lastPage < 5 + ($adjacents * 2)) {
                for ($counter = 1;
                $counter <= $lastPage;
                $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li><a class='current'>$counter</a></li>";
                    else
                        $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                }
            }
            elseif($lastPage > 5 + ($adjacents * 2)) {
                if($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1;
                    $counter < 4 + ($adjacents * 2);
                    $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li><a class='current'>..</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lastPage'>$lastPage</a></li>";
                }
                elseif($lastPage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li><a href='{$url}page=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}page=2'>2</a></li>";
                    $pagination .= "<li><a href='#'>..</a></li>";
                    for ($counter = $page - $adjacents;
                    $counter <= $page + $adjacents;
                    $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li><a class='current'>..</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li><a href='{$url}page=$lastPage'>$lastPage</a></li>";
                }
                else {
                    $pagination .= "<li><a href='{$url}page=1'>1</a></li>";
                    $pagination .= "<li><a href='{$url}page=2'>2</a></li>";
                    $pagination .= "<li><a href='#'>..</a></li>";
                    for ($counter = $lastPage - (2 + ($adjacents * 2));
                    $counter <= $lastPage;
                    $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li><a class='current'>$counter</a></li>";
                        else
                            $pagination .= "<li><a href='{$url}page=$counter'>$counter</a></li>";
                    }
                }
            }
            if ($page < $counter - 1) {
                $pagination .= "<li><a href='{$url}page=$next'>&raquo;</a></li>";

            }

            $pagination .='<li class="is-padded">Page<input type="text" name="goto" class="input goto is-padded" value="'.$page.'"> of  '.$lastPage.'</li>';
            $pagination .= "</ul>\n";
        }
        //echo $pagination;exit;
        return $pagination;
    } //End Function

    // Screen status change ajax url
    public static function changeScreenStatus($values){
        $id     =  $values['id'];
        $value  =  $values['value'];
        $screenStatusUrl = BASE_URL.'admin/service/ajaxScreenStatusChange?id='.$id.'&value='.$value;
        return $screenStatusUrl;
    }

     // Function to update Screen status
     public static function updateScreenStatus($id,$value){
       Admincomponents::$dbObj = new Db();
       $defaultVal = ($value == 0)?1:0;
       Admincomponents::$dbObj->customQuery("UPDATE ".Admincomponents::$dbObj->tablePrefix."DemoScreenshots SET vActive = '" . $defaultVal . "' WHERE nScreenId = '" . $id . "'");
    }

    public static function getuserLogInUrl($userid) {
        $userUrl = BASE_URL.'user/doUserLogInForCMS/'.$userid;
        return $userUrl ;
    } // End Function

    public static function setSupportDeskSessions($userName) {
        Admincomponents::$dbObj = new Db();
    	$sel = "SELECT * FROM sptbl_users WHERE vEmail = '{$userName}'";

        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        
        if(!empty($dataArr)){
            $_SESSION["sess_username"] = $dataArr[0]->vUserName;
            $_SESSION["sess_userid"] = $dataArr[0]->nUserId;
            $_SESSION["sess_useremail"] = $dataArr[0]->vEmail;
            $_SESSION["sess_userfullname"] = $dataArr[0]->vUserName;
            $_SESSION["sess_usercompid"] = 1;
        }                
        return true ;
    } // End Function



}

//End Class
?>