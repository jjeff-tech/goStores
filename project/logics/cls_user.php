<?php
/*
 * All User Entity Logics should come here.
*/
class User {

    public static $dbObj = null;

    /*
         * Function to track Site Traffic
    */
    public static function siteAnalytics() {
        User::$dbObj = new Db();
        if(!isset($_COOKIE['monthly_tracking_cookie'])) {

            $currentMonth     = date ( 'm' ,time()  );
            $entryExists      =  (integer)User::$dbObj->checkExists('SiteAnalytics',"nId"," MONTH(dTrackingDate)= '$currentMonth'");

            $entryExists      =  ($entryExists>0) ? true : false;

            if($entryExists)  // A ENTRY OF THIS MONTH EXISTS IN DB. JUST INCREMENT THE COUNTER.
            {

                $table            = 'SiteAnalytics';
                $field            = 'nCount';
                $order            = "";
                $where            = "MONTH(dTrackingDate)='".$currentMonth."'";

                $existingCount    = User::$dbObj->selectRow($table,$field,$where);

                $arrUpdate       = array('nCount'=>($existingCount+1));
                User::$dbObj->updateFields("SiteAnalytics",$arrUpdate,"MONTH(dTrackingDate)='".$currentMonth."'");
            }
            else  //   IF THERE IS NO ENTRY OF THIS MONTH IN DB.THEN CREATE A ENTRY.
            {
                $table            = 'SiteAnalytics';
                $field            = 'NOW()';
                $order            = "";
                $where            = "";
                $timeStamp        = User::$dbObj->selectRow($table,$field,$where);

                $arr         =   array("nCount"=>1,"dTrackingDate"=>$timeStamp);
                User::$dbObj->addFields("SiteAnalytics",$arr);
            }


            setcookie('monthly_tracking_cookie',time(),(time()+3600*24*30));

        }
        else if(trim($_COOKIE['monthly_tracking_cookie'])!='') // A COOKIE EXISTS. CHECK FOR THE NEW MONTH.
        {

            $currentMonth     = date ( 'm' ,$_COOKIE['monthly_tracking_cookie']  );  // 06 OR 12

            $entryExists      =  (integer)User::$dbObj->checkExists('SiteAnalytics',"nId"," MONTH(dTrackingDate)= '$currentMonth'");

            $entryExists      =  ($entryExists>0) ? true : false;

            if($entryExists==false) {
                $table            = 'SiteAnalytics';
                $field            = 'NOW()';
                $order            = "";
                $where            = "";
                $timeStamp        = User::$dbObj->selectRow($table,$field,$where);

                $arr         =   array("nCount"=>1,"dTrackingDate"=>$timeStamp);
                User::$dbObj->addFields("SiteAnalytics",$arr);

                setcookie('monthly_tracking_cookie',time(),(time()+3600*24*30));  // RESET USER COOCKIE FOR THE NEXT MONTH.

            }
        }
    } // End Function

    /*
     * Method : Admin User Login Check <M>
    */
    public static function adminLoginCheck($username, $password, $id = NULL) {
        //User Data Check
        $status = 0; // Invalid Username / Password
        $checkReturn = array();
        $errMsg = NULL;
        $connection = new Db();

        if(!empty($username) || !empty($password) || !empty($id)) {
            $user = User::getAdminUser($username, $password, $id);
            if(!empty($user)) {
                $modules = Admincomponents::getAdminUserModules($user[0]->nRid);
                if($user[0]->nStatus == 1) {
                    $_SESSION['adminUser']['userID'] = $user[0]->nAId;
                    $_SESSION['adminUser']['userRoleID'] = $user[0]->nRid;
                    $_SESSION['adminUser']['username'] = $user[0]->vUsername;
                    $_SESSION['adminUser']['userFirstname'] = $user[0]->vFirstName;
                    $_SESSION['adminUser']['userLastname'] = $user[0]->vLastName;
                    $_SESSION['adminUser']['userLastLogin'] = $user[0]->dLastLogin;
                    $_SESSION['adminUser']['userEmail']=$user[0]->vEmail;
                    $_SESSION['adminUser']['userStatus']=$user[0]->nStatus;
                    $_SESSION['adminUser']['userRoleName']=$user[0]->vRoleName;
                    $_SESSION['adminUser']['userModules']=$modules;
                    $status = 1; // Active User

                    //---------------------Session for supportdesk admin sync---------------//
                    //$_SESSION["sess_cssurl"]        = $cssurl;
                    $_SESSION["sess_staffname"]     = $user[0]->vUsername;
                    $_SESSION["sess_staffid"]       = $user[0]->nAId;
                    $_SESSION["sess_staffemail"]    = $user[0]->vEmail;
                    $_SESSION["sess_stafffullname"] = $user[0]->vFirstName.' '.$user[0]->vLastName;
                    $_SESSION["sess_isadmin"]       = 1;

                    //----------Set sonicbb session---------------//
                    $sonicbb_user = mysqli_query($connection,"SELECT * FROM `users` WHERE id = 1");
                    if(mysqli_num_rows($sonicbb_user) > 0) {
                        $sonicbb_res = mysqli_fetch_array($sonicbb_user);
                        $_SESSION['loggedin']   = 1;
                        $_SESSION['user']       = $sonicbb_res['username'];
                    }

                } else {
                    $status = 2; // Inactvive User
                } // End If
            }

        } // End If

        if($status == 2) {
            $errMsg = 'Your account is no longer active!';
        } else if($status == 0) {
            $errMsg = 'Invalid login! Please try again.';
        }

        $checkReturn['status'] = $status;
        $checkReturn['errMsg'] = $errMsg;

        return $checkReturn;
    } // End Function

    public static function loggedUserRoleCheck() {

        $role = $_SESSION['adminUser']['userRoleID'];
        User::$dbObj = new Db();
        $result = User::$dbObj->checkExists("Role","nRid"," nStatus=1 AND nRid=".$role);
        return $result?TRUE:FALSE;

    }
    public static function adminAccessCheck() {
        $access = 0;
        if(isset($_SESSION['adminUser']['userID']) && !empty($_SESSION['adminUser']['userID'])) {
            $access = 1;
        }
        return $access;
    } // End Function

    public static function getAdminUser($username, $password, $id = NULL) {
        User::$dbObj = new Db();
        if(empty($id)) {
            $selUser = "SELECT a.nAId, a.nRid, a.vUsername, a.vPassword, a.vFirstName, a.vLastName,
                a.dLastLogin, a.vEmail, a.nStatus, r.vRoleName
                FROM " . User::$dbObj->tablePrefix . "Admin a
                LEFT JOIN " . User::$dbObj->tablePrefix . "Role r ON a.nRid = r.nRid
                WHERE LOWER(a.vUsername)='" . strtolower($username) . "'
                AND a.vPassword ='" . md5($password) . "'";
        } else {
            $selUser = "SELECT a.nAId, a.nRid, a.vUsername, a.vPassword, a.vFirstName, a.vLastName,
                a.dLastLogin, a.vEmail, a.nStatus, r.vRoleName
                FROM " . User::$dbObj->tablePrefix . "Admin a
                LEFT JOIN " . User::$dbObj->tablePrefix . "Role r ON a.nRid = r.nRid
                WHERE a.nAId='" . $id . "'";

        }
        $userData = User::$dbObj->selectQuery($selUser);
        return $userData;
    } // End Function

    public static function checkSubdomain($subdomain) {
        User::$dbObj = new Db();
        $condition      = "nSubDomainStatus='1' AND vSubDomain='$subdomain'";
        $entryExists    =  (integer)User::$dbObj->checkExists('ProductLookup',"nPLId",$condition);
        $statusFlag     =  ($entryExists>0) ? true : false;
        return $statusFlag;
    }
    /*
     * Function to setup new user account
    */
    public static function createUserAccount($userArray, $userUpdateArr=NULL, $userCreditArr=NULL) {
        Admincomponents::$dbObj = new Db();
        $postedArray    	= array("vUsername"=> $userArray['user_name'],
                "vFirstName"	=> $userArray['user_name'],
                "vEmail"	=> $userArray['user_email'],
                "vPhoneNumber"	=> $userArray['user_phone'],
                "vInvoiceEmail" => $userArray['user_email'],
                "nStatus"       => 1,
                "vPassword"	=> md5($userArray['userpassw']),);

        $fName = Utils::splitEmailIntoParts($userArray['user_email'], 'user');
        $fNameArr = preg_split("/[\s._]+/", $fName);
        $postedArray["vFirstName"] = ucfirst($fNameArr[0]); // Set First Name

        if(isset($userArray['user_lname'])){
            $postedArray["vLastName"] = ucfirst($userArray['user_lname']); // Set Last Name
        } else if(isset($fNameArr[1])){
            $postedArray["vLastName"] = ucfirst($fNameArr[1]); // Set Last Name
        }

        $userStatus = Admincomponents::$dbObj->checkExists("User","nUId"," vEmail='".$postedArray["vEmail"]."'");


        if(!$userStatus)
        {
            $status = Admincomponents::$dbObj->addFields("User",$postedArray);

        /* User Update Area *********/
        if(!empty($userUpdateArr)) {
            Admincomponents::$dbObj->updateFields("User",$userUpdateArr,"nUId = '".$status."'");
        }
        /* User Update Area Ends *********/

        /* User Credit card Info Update Area *********/
        if(!empty($userCreditArr)) {
            foreach ($userCreditArr as $key=>$value) {
                $userCreditArr[$key] = self::encrytCreditCardDetails($value);
            }
            $userCreditArr['nUserId']=$status;

            //Admincomponents::$dbObj->addFields("general",$userCreditArr); // removed by Anoop
        }

        }
        /* User Credit card Info Update Area Ends *********/

        else
        {
            $userId = Admincomponents::$dbObj->selectRecord("User","nUId"," vEmail='".$postedArray["vEmail"]."'");
            $status = $userId->nUId;
        }





        return $status;
    }






    /*
     * Send Registration mail to User
    */
    
    
    public static function sendInventorySourceMail($userArray) {
//cms_userregistration
        User::$dbObj = new Db();
//        $entryExists      = (integer)User::checkUserEmail($userArray['user_email']);
//        $entryExists      =  ($entryExists>0) ? true : false;
//        if(!$entryExists)
//        {
        //echo "<pre>"; print_r($userArray); echo "</pre>";
        $emailContent       = array();
        $table          = 'Cms';
        $field          = "cms_title,cms_desc";
        $where          = "cms_name='inventory_source_subscription' AND cms_status='1'";
        $emailContent   = User::$dbObj->selectRecord($table,$field,$where);
        $emailTitle     = $emailContent->cms_title;
        $emailBody      = $emailContent->cms_desc;
        
        foreach ($userArray as $k=>$v)
        {
            
            $emailBody      = str_replace('{'.$k.'}', $v, $emailBody);
            $emailTitle      = str_replace('{'.$k.'}', $v, $emailTitle);
        }
        
        
        
       // echo $emailBody; die();
        $emailBody      = Utils::bindEmailTemplate($emailBody);

        //PageContext::includePath('email');
        //$emailObject    = new Emailsend();
        $emailContents    	= array("from"		=> ADMIN_EMAILS,
                "subject"	=> $emailTitle,
                "message"		=> $emailBody,
                "to"            => $userArray['EMAIL']);
        //$emailObject->email_senderNow($emailContents);
        Mailer::sendSmtpMail($emailContents);
//        }
    }
    public static function sendInventorySourceRenewalMail($userArray,$email) {
//cms_userregistration
        User::$dbObj = new Db();
//        $entryExists      = (integer)User::checkUserEmail($userArray['user_email']);
//        $entryExists      =  ($entryExists>0) ? true : false;
//        if(!$entryExists)
//        {
        //echo "<pre>"; print_r($userArray); echo "</pre>";
        $emailContent       = array();
        $table          = 'Cms';
        $field          = "cms_title,cms_desc";
        $where          = "cms_name='$email' AND cms_status='1'";
        $emailContent   = User::$dbObj->selectRecord($table,$field,$where);
        $emailTitle     = $emailContent->cms_title;
        $emailBody      = $emailContent->cms_desc;
        
        foreach ($userArray as $k=>$v)
        {
            
            $emailBody      = str_replace('{'.$k.'}', $v, $emailBody);
            $emailTitle      = str_replace('{'.$k.'}', $v, $emailTitle);
        }
        
        
        
       // echo $emailBody; die();
        $emailBody      = Utils::bindEmailTemplate($emailBody);

        //PageContext::includePath('email');
        //$emailObject    = new Emailsend();
        $emailContents    	= array("from"		=> ADMIN_EMAILS,
                "subject"	=> $emailTitle,
                "message"		=> $emailBody,
                "to"            => $userArray['EMAIL']);
        //$emailObject->email_senderNow($emailContents);
        Mailer::sendSmtpMail($emailContents);
//        }
    }

    public static function sendMail($userArray) {

        
//cms_userregistration
        User::$dbObj = new Db();
//        $entryExists      = (integer)User::checkUserEmail($userArray['user_email']);
//        $entryExists      =  ($entryExists>0) ? true : false;
//        if(!$entryExists)
//        {
        //echo "<pre>"; print_r($userArray); echo "</pre>";
        $emailContent       = array();
        $table          = 'Cms';
        $field          = "cms_title,cms_desc";
        $where          = "cms_name='USER_REGISTRATION' AND cms_status='1'";
        $emailContent   = User::$dbObj->selectRecord($table,$field,$where);
        $emailTitle     = $emailContent->cms_title;
        $emailBody      = $emailContent->cms_desc;
        $emailBody      = str_replace('{USERNAME}', rtrim(strtolower(strtolower(str_replace(".", " ", $userArray['user_name'])))), $emailBody);
        $emailBody      = str_replace('{USEREMAIL}', $userArray['user_email'], $emailBody);
        $emailBody      = str_replace('{USERPASSWORD}', $userArray['userpassw'], $emailBody);
        $emailBody      = str_replace('{SITE_NAME}', SITE_NAME, $emailBody);
        $emailBody      = str_replace('{STORENAME}', $userArray['store_name'], $emailBody);
        //$emailBody      = str_replace('{USERNAME}', strtolower($userArray['user_name']), $emailBody);
        $emailBody      = str_replace('{STOREBACKENDURL}', $userArray['back_end_url'], $emailBody);
        $emailBody      = str_replace('{STOREFRONTENDURL}', $userArray['front_end_url'], $emailBody);
        //echo $emailBody; die();
        $emailBody      = Utils::bindEmailTemplate($emailBody);

        //PageContext::includePath('email');
        //$emailObject    = new Emailsend();
        $emailContents    	= array("from"		=> ADMIN_EMAILS,
                "subject"	=> $emailTitle,
                "message"		=> $emailBody,
                "to"            => $userArray['user_email']);
        //$emailObject->email_senderNow($emailContents);
        Mailer::sendSmtpMail($emailContents);
//        }
    }

    public static function sendUserMail($userArray) {
        //send email for forgot password
        User::$dbObj = new Db();
        $emailContent       = array();
        $table          = 'Cms';
        $field          = "cms_title,cms_desc";
        $where          = "cms_name='forgotpasswordemail' AND cms_status='1'";
        $emailContent   = User::$dbObj->selectRecord($table,$field,$where);
        $emailTitle     = $emailContent->cms_title;
        $emailBody      = $emailContent->cms_desc;
        $emailBody      = str_replace('{USERNAME}', $userArray['user_name'], $emailBody);
        $emailBody      = str_replace('{USEREMAIL}', $userArray['email'], $emailBody);
        $emailBody      = str_replace('{PASSWORDLINK}', $userArray['passwordLink'], $emailBody);
        $emailBody      = Utils::bindEmailTemplate($emailBody);

        //PageContext::includePath('email');
        //$emailObject    = new Emailsend();
        $emailContents    	= array("from"		=> ADMIN_EMAILS,
                "subject"	=> $emailTitle,
                "message"		=> $emailBody,
                "to"            => $userArray['email']);
            Mailer::sendSmtpMail($emailContents);
        //$emailObject->email_senderNow($emailContents);
    }

    public static function addLookupEntry($userArray,$subdom,$userId,$domainFlag=0) {
        User::$dbObj = new Db();
        $serverSettings = serialize($userArray);
        if($domainFlag==0) {


            $postedArray    	= array("vSubDomain"		=> $subdom,
                    "nSubDomainStatus"	=> 1,
                    "nUId"		=> $userId,
                    "nPPId"         => LibSession::get('planpackage'),
                    "nPId"		=> LibSession::get('productid'),
                    "nPRId"         => LibSession::get('productreleaseid'),
                    "dStatusUpdatedOn"		=> date("Y-m-d H:i:s", time()),
                    "nStatus"            => 1,
                    "dPlanExpiryDate"   =>date("Y-m-d H:i:s", mktime(0,0,0,date('m',time()),date('d',time())+14,date('Y',time()))),
                    "dLastUpdated"		=> date("Y-m-d H:i:s", time()),
                    "vAccountDetails"       => $serverSettings);

        }
        else {
            $postedArray    	= array("vDomain"		=> $subdom,
                    "nDomainStatus"	=> 1,
                    "nUId"		=> $userId,
                    "nPPId"         => LibSession::get('planpackage'),
                    "nPId"		=> LibSession::get('productid'),
                    "nPRId"         => LibSession::get('productreleaseid'),
                    "dStatusUpdatedOn"		=> date("Y-m-d H:i:s", time()),
                    "nStatus"            => 1,
                    "dPlanExpiryDate"   =>date("Y-m-d H:i:s", mktime(0,0,0,date('m',time()),date('d',time())+14,date('Y',time()))),
                    "dLastUpdated"		=> date("Y-m-d H:i:s", time()),
                    "vAccountDetails"       => $serverSettings);
        }

        if($userArray['customerDetails'])
        {
           $postedArray['bluedogdetails'] = serialize($userArray['customerDetails']);
        }

        //echopre($postedArray);exit;


        $status = User::$dbObj->addFields("ProductLookup",$postedArray);


        Admincomponents::logServerMapped($status); // $status = $idProductLookUp

        return $status;
    }
    
    
    public function addInventorySourcePlanEntry($nPId,$inventory_source_plan_duration)
    {
        User::$dbObj = new Db();
        
        $invPlan   = User::$dbObj->selectRecord('inventorysource_plan',"*","nPId='$nPId'");
        //echopre1($invPlan);
        if(!$invPlan){
          $postedArray    	= array("nPId"		=> $nPId,
                    "nUserId"		=> LibSession::get('userID'),
                    "dateStart"         => date('Y-m-d'),
                    "dateEnd"		=> date('Y-m-d', strtotime(date('Y-m-d') . " +$inventory_source_plan_duration day")),
              "createdDate"		=> date('Y-m-d H:i:s'),
              "updatedDate"		=> date('Y-m-d H:i:s'),
                    "nStatus"            => 1,
                  "cronAttempt" =>  0,
              "lastCronDate" => '0000-00-00');
          $nInvsPid = User::$dbObj->addFields("inventorysource_plan",$postedArray);
        }else{
            $nInvsPid  = $invPlan->nInvsPid;
             $postedArray    	= array(  "dateStart"         => date('Y-m-d'),
                    "dateEnd"		=> date('Y-m-d', strtotime(date('Y-m-d') . " +$inventory_source_plan_duration day")),
              "updatedDate"		=> date('Y-m-d H:i:s'),
                    "nStatus"            => 1,
                  "cronAttempt" =>  0,
              "lastCronDate" => '0000-00-00');
         User::$dbObj->updateFields("inventorysource_plan",$postedArray,"nInvsPid = '$nInvsPid'");
        }
          
         User::$dbObj->updateFields("ProductLookup",array('inventory_source_status'=>1),"nPLId = '$nPId'");
          
          
          return $nInvsPid;
        
    }
    
    public function disableInventoryService($nPId)
    {
        
        User::$dbObj->updateFields("ProductLookup",array('inventory_source_status'=>0),"nPLId = '$nPId'");
        User::$dbObj->updateFields("inventorysource_plan",array('nStatus'=>0),"nPId = '$nPId'");
    }
    
    public function updateInventorySourcePlanEntry($nPId,$inventory_source_plan_duration,$nInvsPid,$cronAttempt,$nStatus)
    {
        User::$dbObj = new Db();
          $postedArray    	= array("nPId"		=> $nPId, 
                    "dateStart"         => date('Y-m-d'),
                    "dateEnd"		=> date('Y-m-d', strtotime(date('Y-m-d') . " +$inventory_source_plan_duration day")),
                    "updatedDate"		=> date('Y-m-d H:i:s'),
                    "nStatus"            => $nStatus,
                  "cronAttempt" =>  $cronAttempt,
              "lastCronDate" => date('Y-m-d'));
          $status = User::$dbObj->updateFields("inventorysource_plan",$postedArray,"nInvsPid = '$nInvsPid'");
          
          User::$dbObj->updateFields("ProductLookup",array('inventory_source_status'=>1),"nPLId = '$nPId'");
          
          
          return $nInvsPid;
        
    }
    
    
    
    public function addInventorySourcePlanInventory($nInvsPid,$inventory_source_plan_duration,$inventory_source_amount,$transactionID)
    {
        User::$dbObj = new Db();
          $postedArray    	= array("nInvsPid"		=> $nInvsPid,
                    "invDateStart"         => date('Y-m-d'),
                    "invDateEnd"		=> date('Y-m-d', strtotime(date('Y-m-d') . " +$inventory_source_plan_duration day")),
              "InvCreatedDate"		=> date('Y-m-d H:i:s'),
                    "nAmount"            => $inventory_source_amount,
                  "nDuration" =>  $inventory_source_plan_duration,
              "nTransactionId" => $transactionID);
          $status = User::$dbObj->addFields("inventorysource_plan_invoice",$postedArray);
          
        
          
          
          return $status;
        
    }

    
    public static function validateLoginByEmail($userName) {
        User::$dbObj = new Db();
        $condition      = " vEmail='$userName'";
        $entryExists    =  (integer)User::$dbObj->checkExists('User',"nUId",$condition);
        if($entryExists>0) {
            $condition      = "nStatus!='1' AND vEmail='$userName'";
            $statusentryExists    =  (integer)User::$dbObj->checkExists('User',"nUId",$condition);

            if($statusentryExists>0)
                $statusFlag = -2;
            else {
                $statusFlag = true;
                $condition      = "nStatus='1' AND vEmail='$userName'";
                $userData   = User::$dbObj->selectRecord('User',"nUId,vFirstName",$condition);

                $query = "UPDATE  ".User::$dbObj->tablePrefix."User SET dLastLogin=NOW() WHERE nUId =".$userData->nUId;
                User::$dbObj ->customQuery($query);
                $sesObj = new LibSession();
                $sesObj->set('userID',$userData->nUId);
               $sesObj->set('userName',$userName);
                $sesObj->set('userPass',$password);
                $sesObj->set('firstName',$userData->vFirstName);

                //-----------Set supportdesk session----------//
                $sptbl_user = mysqli_query("SELECT * FROM sptbl_users WHERE vEmail = '$userName'");
                if(mysqli_num_rows($sptbl_user) > 0) {
                    $sptbl_res = mysqli_fetch_array($sptbl_user);

                    $_SESSION["sess_username"]      = $sptbl_res['vUserName'];
                    $_SESSION["sess_userid"]        = $sptbl_res['nUserId'];
                    $_SESSION["sess_useremail"]     = $sptbl_res['vEmail'];
                    $_SESSION["sess_userfullname"]  = $sptbl_res['vUserName'];
                    $_SESSION["sess_usercompid"]    = 1;
                }

                //----------Set sonicbb session---------------//
//                $sonicbb_user = mysql_query("SELECT * FROM `users` WHERE email = '$userName'");
//                if(mysql_num_rows($sonicbb_user) > 0) {
//                    $sonicbb_res = mysql_fetch_array($sonicbb_user);
//                    $_SESSION['loggedin']   = 1;
//                    $_SESSION['user']       = $sonicbb_res['username'];
//                }

            }

        } else {
            $statusFlag = false;
        }

        return $statusFlag;

    }
    
    
    
    
    public static function validateLogin($userName,$password) {
        User::$dbObj = new Db();
        $condition      = " vEmail='$userName' AND vPassword='$password'";
        $entryExists    =  (integer)User::$dbObj->checkExists('User',"nUId",$condition);
        if($entryExists>0) {
            $condition      = "nStatus!='1' AND vEmail='$userName' AND vPassword='$password'";
            $statusentryExists    =  (integer)User::$dbObj->checkExists('User',"nUId",$condition);

            if($statusentryExists>0)
                $statusFlag = -2;
            else {
                $statusFlag = true;
                $condition      = "nStatus='1' AND vEmail='$userName' AND vPassword='$password'";
                $userData   = User::$dbObj->selectRecord('User',"nUId,vFirstName",$condition);

                $query = "UPDATE  ".User::$dbObj->tablePrefix."User SET dLastLogin=NOW() WHERE nUId =".$userData->nUId;
                User::$dbObj ->customQuery($query);
                $sesObj = new LibSession();
                $sesObj->set('userID',$userData->nUId);
               $sesObj->set('userName',$userName);
                $sesObj->set('userPass',$password);
                $sesObj->set('firstName',$userData->vFirstName);

                //-----------Set supportdesk session----------//
                $sptbl_user = mysqli_query("SELECT * FROM sptbl_users WHERE vEmail = '$userName'");
                if(mysqli_num_rows($sptbl_user) > 0) {
                    $sptbl_res = mysqli_fetch_array($sptbl_user);

                    $_SESSION["sess_username"]      = $sptbl_res['vUserName'];
                    $_SESSION["sess_userid"]        = $sptbl_res['nUserId'];
                    $_SESSION["sess_useremail"]     = $sptbl_res['vEmail'];
                    $_SESSION["sess_userfullname"]  = $sptbl_res['vUserName'];
                    $_SESSION["sess_usercompid"]    = 1;
                }

                //----------Set sonicbb session---------------//
//                $sonicbb_user = mysql_query("SELECT * FROM `users` WHERE email = '$userName'");
//                if(mysql_num_rows($sonicbb_user) > 0) {
//                    $sonicbb_res = mysql_fetch_array($sonicbb_user);
//                    $_SESSION['loggedin']   = 1;
//                    $_SESSION['user']       = $sonicbb_res['username'];
//                }

            }

        } else {
            $statusFlag = false;
        }

        return $statusFlag;

    }

    public static function createNewsLetter($userName,$userEmail) {
        User::$dbObj = new Db();
        $postedArray = array("vName"                  => $userName,
                "vEmail"                 => $userEmail,
                "nSubscriptionStatus"    => 1,
        );
        $condition   = "vEmail='$userEmail'";
        $entryExists =  (integer)User::$dbObj->checkExists('NewsLetters',"vEmail",$condition);
        $entryExists =  ($entryExists>0) ? true : false;
//        echo 'Entrystatus->'. $entryExists;
        if($entryExists) {
            $status = 0;
        }
        else {
            if(User::$dbObj->addFields("NewsLetters",$postedArray))
                $status = 1;
            else
                $status = 2;
        }
        return $status;

    }
    /*
     * Function to fetch User product Details
    */
    public static function getUserProducts($txtSearch="", $limit="") {
        User::$dbObj = new Db();
        if($txtSearch=="" && $limit=="") {
            $selUserProduct =      "SELECT PLK.vSubDomain, PLK.nSubDomainStatus, PLK.vDomain, PLK.nDomainStatus, PLK.dLastUpdated, PLK.dPlanExpiryDate,
                            PRD.vPName, PRL.vVersion
                                  FROM " . User::$dbObj->tablePrefix . "User USR
                            INNER JOIN " . User::$dbObj->tablePrefix . "ProductLookup PLK ON USR.nUId = PLK.nUId
                            INNER JOIN " . User::$dbObj->tablePrefix . "Products PRD ON PRD.nPId  = PLK.nPId
                            INNER JOIN " . User::$dbObj->tablePrefix . "ProductReleases PRL ON PRL.nPRId = PLK.nPRId
                                 WHERE PLK.nUId='" . LibSession::get('userID') . "'
                                   AND PLK.nStatus ='1'";
            $selUserProduct = User::$dbObj->selectQuery($selUserProduct);

        }
        else {
            $selFields = 'PLK.nPLId, PLK.vSubDomain, PLK.nSubDomainStatus, PLK.vDomain, PLK.nDomainStatus, PLK.dLastUpdated, PLK.dPlanExpiryDate,
                                PRD.vPName, PRL.vVersion';
            $table='User USR';
            $join ="INNER JOIN " . User::$dbObj->tablePrefix . "ProductLookup PLK ON USR.nUId = PLK.nUId
                                INNER JOIN " . User::$dbObj->tablePrefix . "Products PRD ON PRD.nPId  = PLK.nPId
                                INNER JOIN " . User::$dbObj->tablePrefix . "ProductReleases PRL ON PRL.nPRId = PLK.nPRId";
            $where="WHERE PLK.nUId='" . LibSession::get('userID') . "'
                                       AND PLK.nStatus ='1'";
            $groupby = '';
            $sort_order='DESC';
            $sort_filed='';
            $limit=$limit;
            $selUserProduct = User::$dbObj->getPagingData($selFields,$table,$join ,$where, $groupby,$sort_order,$sort_filed,$limit);
        }
        Logger::info($selUserProduct);
        return $selUserProduct;
    }

    public static function userPayments($txtSearch='') {
        User::$dbObj = new Db();

        $queryopt = $queryopt1 = '';

        if($txtSearch!='')
            {
                $queryopt = " AND  iv.vInvNo ='".Utils::formatPostData($txtSearch)."' ";
                $queryopt1 = " AND  TP.id ='".Utils::formatPostData($txtSearch)."' ";
            }

            $selUserPayments = "SELECT iv.nInvId, iv.vInvNo, iv.nPLId, iv.dGeneratedDate, iv.dDueDate, iv.nAmount,
                iv.nDiscount, iv.nTotal,ps.vServiceName, ps.vServiceDescription, iv.dPayment, 'invoice' as billgenType
                                    FROM " . User::$dbObj->tablePrefix . "Invoice iv LEFT JOIN " . User::$dbObj->tablePrefix . "InvoicePlan ip
                                      ON ip.nInvId = iv.nInvId LEFT JOIN ". User::$dbObj->tablePrefix . "ProductServices ps
                                          ON ps.nServiceId = ip.nServiceId
                                          WHERE iv.nUId='".LibSession::get('userID') . "' AND iv.vSubscriptionType!='FREE'".$queryopt."
                                              UNION
                                              SELECT TP.id as nInvId, TP.id as vInvNo, TP.nPLId, TP.paidOn as dGeneratedDate, TP.paidOn as dDueDate, TP.amount as nAmount, '0.00' as nDiscount, TP.amount as nTotal, CONCAT_WS(' - ','Template Purchase',T.vTemplateName) as vServiceName,  TP.comments as vServiceDescription,  TP.paidOn as dPayment, 'template' as billgenType  FROM ". User::$dbObj->tablePrefix . "paidtemplatepurchase TP LEFT JOIN ". User::$dbObj->tablePrefix . "PaidTemplates T ON TP.nTemplateId=T.nTemplateId WHERE TP.nUId='".LibSession::get('userID') . "'".$queryopt1;

            /*
             $selUserPayments = "SELECT iv.nInvId, iv.vInvNo, iv.nPLId, iv.dGeneratedDate, iv.dDueDate, iv.nAmount,
                iv.nDiscount, iv.nTotal,ps.vServiceName, ps.vServiceDescription, iv.dPayment
                                  FROM " . User::$dbObj->tablePrefix . "Invoice iv LEFT JOIN " . User::$dbObj->tablePrefix . "InvoicePlan ip
                                      ON ip.nInvId = iv.nInvId LEFT JOIN ". User::$dbObj->tablePrefix . "ProductServices ps
                                          ON ps.nServiceId = ip.nServiceId
                                          WHERE iv.nUId='".LibSession::get('userID') . "' AND iv.vSubscriptionType!='FREE'".$queryopt." ";
             */
            $selUserPayments .= " ORDER BY dGeneratedDate DESC";
              //echo $selUserPayments.'<br>';
            $selUserPayments = User::$dbObj->selectQuery($selUserPayments);

        Logger::info($selUserPayments);
        return $selUserPayments;
    }

    public static function getSettlements($id) {
        User::$dbObj = new Db();

        $selUserSettlements = "SELECT * FROM " . User::$dbObj->tablePrefix . "billingsettlement WHERE nId = " .$id;
        $selUserSettlements = User::$dbObj->selectQuery($selUserSettlements);

        return $selUserSettlements[0];
    }

    public static function userSettlements() {
        User::$dbObj = new Db();

        $selUserSettlements = "SELECT * FROM " . User::$dbObj->tablePrefix . "billingsettlement WHERE nUId = " . LibSession::get('userID');
        $selUserSettlements = User::$dbObj->selectQuery($selUserSettlements);

        return $selUserSettlements;
    }

   /*
     * Function to save user settlement Request
    */
     public static function saveSettlements($data) {
        User::$dbObj = new Db();
        $itemDet = array();

                if(empty($data['nId'])) {
                    //Insert new settlement request
                    $itemQry = "INSERT INTO ".User::$dbObj->tablePrefix . "billingsettlement SET nUId = '".$data['nUId']."', nRequestedAmount = '".addslashes($data['nRequestedAmount'])."', tUserComments  = '".addslashes($data['tUserComments'])."', eStatus = 'Pending', dCreatedOn= NOW()";
                    User::$dbObj->customQuery($itemQry);

                } else {
                    //Update settlement request
                    $itemQry = "UPDATE ".User::$dbObj->tablePrefix . "billingsettlement SET nRequestedAmount  = '".addslashes($data['nRequestedAmount'])."', tUserComments = '".addslashes($data['tUserComments'])."' WHERE nId ='".$data['nId']."'";
                    User::$dbObj->customQuery($itemQry);
                    $requestID = $data['nId'];

                }

            $itemDet['nid']= $requestID;

        return $itemDet;

    } // End Function

    public static function addToWallet($userId,$userBalance,$amtToAdd) {
        User::$dbObj = new Db();

        //Check if user is already having an entry in wallet table
        $condition   = "nUId='$userId'";
        $entryExists =  (integer)User::$dbObj->checkExists('Wallet',"nUId",$condition);
        $entryExists =  ($entryExists>0) ? true : false;

        //User has a wallet and hence updating the amount to existing balance
        if($entryExists) {
            $arrUpdate    = array('nBalanceAmount'=>($userBalance+$amtToAdd));
            $status = User::$dbObj->updateFields("Wallet",$arrUpdate,"nUId='".$userId."'");
        }
        // User has no wallet and hence adding a new entry
        else {
            $postedArray  = array("nUId"	=> $userId,
                    "nBalanceAmount"	=> $amtToAdd,
                    "vType"		=> '1',
                    "dCreatedOn"      => date("Y-m-d H:i:s", time()),
                    "dLastUpdated"	=> date("Y-m-d H:i:s", time()));
            if(User::$dbObj->addFields("Wallet",$postedArray))
                $status = 1;
            else
                $status = 2;
        }
        return $status;
    }


    /*
     * Complete the Creaditcard Payment
    */
    public static function creditPayment($authorizeInfo) {
        // User Information

        $listId = LibSession::get('temp_listing_id');

        PageContext::includePath('authorize');

        $Libauthorize_class   = new  Authorize_class();

        User::$dbObj          = new Db();

        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_enable'");
        $authorizeLoginId     =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_loginid'");
        $authorizeTransKey    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_transkey'");
        $authorizeEmail       =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_email'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_test_mode'");
        $listName            = 'Test';//User::$dbObj->selectRow("product","Title","nBusId='".$listId."'");
        $adminCurrency    =   User::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");

        $authorizeInfo['desc'] = $listName;
        $authorizeInfo['x_login'] = $authorizeLoginId;
        $authorizeInfo['x_tran_key'] = $authorizeTransKey;
        $authorizeInfo['email'] = $authorizeEmail;
        $authorizeInfo['testMode'] = $authorizeTestMode;
        $authorizeInfo['currency_code'] =$adminCurrency;
//commenting amount as is in controller 09/24/2012 - Jose
        //$authorizeInfo['amount'] = $amount;

        $return =  $Libauthorize_class->submit_authorize_post($authorizeInfo);

        //echopre1($return);

        $details = $return[0];

        $transaction_id = $return[1];
        switch ($details) {
            case "1": // Credit Card Successfully Charged
                $paymentsuccessful = true;
                $transactionid = $return[6];
                break;
            case "2":
                $paymentsuccessful = false;
                $paymenterror = "The card has been declined";
                $paymenterror .= "" . $return[3];
                $transactionid = NULL;
                break;
            case "4":
                $paymentsuccessful = false;
                $paymenterror = "The card has been held for review";
                $paymenterror .= "" . $return[3];
                $transactionid = NULL;
                break;
            default: // Credit Card Not Successfully Charged
                $paymentsuccessful = false;
                $paymenterror = "Error";
                $paymenterror .= "" . $return[3];
                $transactionid = NULL;
                break;
        }

        if($paymentsuccessful) {
            if($transaction_id!='' && $paymentsuccessful) {


            }
            $msg = 'Payment was Successfull';
            $data = array('success' => 1, 'list' => $msg);
        }
        else {
            $errorMsg = 'Payment was failure - '.$paymenterror;
            $data = array('failed' => 1, 'list' => $errorMsg);
        }

        $payArr = array('transactionId' => $transactionid, 'paymentMethod' => 'CC');
        $dataArr = array_merge($data, $payArr);

        if($transaction_id!='' && $paymentsuccessful) {

            $arrProfile =   array("dPaymentDate"=>date("Y-m-d H:i:s", time()),
                    "nUId"=>LibSession::get('reg_usr_id'),
                    "nAmount"=>$authorizeInfo['amount'],
                    "nPId"=>LibSession::get('planid'),
                    "vPaymentMethod"=>"CC",
                    "vTransactionId"=>$transaction_id
            );


            $paymentId =   User::$dbObj->addFields("Payments",$arrProfile);
            $arrProfile         =   array("nFeatured"=>1);
//            User::$dbObj->updateFields("product",$arrProfile,"nBusId='".$listId."'");

        }
        return $dataArr;
    }

    
    public function updateBlueDogDetails($res,$productLookUpId)
    {
        
           $customerData = serialize($res);
           
           User::$dbObj->updateFields("ProductLookup",array("bluedogdetails"=>$customerData),"nPLId='".$productLookUpId."'");
           
    }

    
    public static function GetProductLookupID($Cid)
{



        User::$dbObj = new Db();

        $getPrID = "SELECT * FROM " . User::$dbObj->tablePrefix . "ProductLookup WHERE nUId = " . $Cid ." ORDER BY nPLId DESC LIMIT 1";
        $getPrLookUPID = User::$dbObj->selectQuery($getPrID);

        

        
        return $getPrLookUPID;
}

public function UpdateProductLookUp($customerData,$productLookUpId)
{

$customerData = serialize($customerData);
User::$dbObj = new Db();

User::$dbObj->updateFields("ProductLookup",array("bluedogdetails"=>$customerData),"nPLId='".$productLookUpId."'");

//echopre($customerData);exit;

return true;

}

   
    public function ccMasking($number, $maskingCharacter = 'X') {
    return substr($number, 0, 4) . str_repeat($maskingCharacter, strlen($number) - 8) . substr($number, -4);
}

    
    
    public function authorizeCreditCardPayment($authorizeInfo)
    {
        
        $listId = LibSession::get('temp_listing_id');

//        PageContext::includePath('authorize');
//
//        $Libauthorize_class   = new  Authorize_class();

        User::$dbObj          = new Db();
        
        $siteName      =   User::$dbObj->selectRow("Settings","value","settingfield='siteName'");
        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_enable'");
        $authorizeLoginId     =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_loginid'");
        $authorizeTransKey    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_transkey'");
        $authorizeEmail       =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_email'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_test_mode'");
        $listName            = 'Test';//User::$dbObj->selectRow("product","Title","nBusId='".$listId."'");
        $adminCurrency    =   User::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");
        
        if($authorizeTestMode=='Y')
        {
            $apiUrl = 'https://apitest.authorize.net/xml/v1/request.api';
        }else{
             $apiUrl = 'https://api.authorize.net/xml/v1/request.api';
        }
        
        

//        $authorizeInfo['desc'] = $listName;
//        $authorizeInfo['x_login'] = $authorizeLoginId;
//        $authorizeInfo['x_tran_key'] = $authorizeTransKey;
//        $authorizeInfo['email'] = $authorizeEmail;
//        $authorizeInfo['testMode'] = $authorizeTestMode;
//        $authorizeInfo['currency_code'] =$adminCurrency;
        
        //echopre1($authorizeInfo);
        
        
        $cutomerarray = array();
        $billto = array();
        $payment = array();
        
        $cutomerarray['createCustomerProfileRequest']['merchantAuthentication']['name'] = $authorizeLoginId;
        $cutomerarray['createCustomerProfileRequest']['merchantAuthentication']['transactionKey'] = $authorizeTransKey;
        $cutomerarray['createCustomerProfileRequest']['profile']['merchantCustomerId'] = $authorizeInfo['fName'].'_'.rand(1,100).rand(1,100);
        $cutomerarray['createCustomerProfileRequest']['profile']['description'] = 'Authorize Profile Creation from '.$siteName;
        $cutomerarray['createCustomerProfileRequest']['profile']['email'] = $authorizeInfo['email'];
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['customerType'] = 'individual';
       
        
        $billto['firstName'] = $authorizeInfo['fName'];
        $billto['lastName'] = $authorizeInfo['lName'];
        $billto['address'] = $authorizeInfo['add1'];
        $billto['city'] = $authorizeInfo['city'];
        $billto['state'] = $authorizeInfo['state'];
        $billto['zip'] = $authorizeInfo['zip'];
        $billto['country'] = ($authorizeInfo['country']=='US')?'USA':$authorizeInfo['country'];
        $billto['phoneNumber'] = $authorizeInfo['phone'];
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['billTo'] = $billto;
        //$cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['payment'] = 'individual';
        $payment['creditCard']['cardNumber'] = $authorizeInfo['ccno'];
        $expiryear = "20".$authorizeInfo['expYear']; 
        $payment['creditCard']['expirationDate'] = $expiryear.'-'.$authorizeInfo['expMonth'];
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['payment'] = $payment;
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['defaultPaymentProfile'] = false;
        $cutomerarray['createCustomerProfileRequest']['validationMode'] = 'liveMode';
      
        
        $payload = json_encode($cutomerarray);
       // echo $payload;
       // exit;
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

            $headr = array();
            $headr[] = 'Content-Type: application/json';
           

            curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            # Send request.
            $result = curl_exec($ch);
            // echopre($result);
            curl_close($ch);
           // $jsonData = str_replace('\ufeff', '', $result);
            $jsonData = preg_replace('/\x{FEFF}/u', '', $result);
       //$jsonData = preg_replace('/\x{feff}$/u', '', $result);
      // echo($jsonData);
            $res = json_decode($jsonData,true);
            
            if($res['messages']['resultCode']=='Ok' && $res['customerProfileId'])
            {
                $customerProfileId = $res['customerProfileId'];
                $customerPaymentProfileIdList = $res['customerPaymentProfileIdList'][0];
                $authorizeInfo['customerDetails']['address'] = $billto;
                $authorizeInfo['customerDetails']['cardNumber'] = User::ccMasking($authorizeInfo['ccno']);
                $authorizeInfo['customerDetails']['expirationDate'] = $payment['creditCard']['expirationDate'];
                $authorizeInfo['customerDetails']['payment_gateway'] = 'authorize';
                $authorizeInfo['customerDetails']['customerProfileId'] = $customerProfileId;
                $authorizeInfo['customerDetails']['customerPaymentProfileIdList'] = $customerPaymentProfileIdList;
                return User::proceessAuthorizeTokenPayment($customerProfileId,$customerPaymentProfileIdList,$authorizeInfo);
                
            }else{
                $return = array();
                $return['failed'] = 1;
                $return['list'] = $res['messages']['message'][0]['text'];
                $return['Message'] = $res['messages']['message'][0]['text'];
                //echopre($return);
                return $return;
               
            }
            
            
            
        
        
        
        
        
    }
    
    public function proceessAuthorizeTokenPayment($customerProfileId,$customerPaymentProfileIdList,$authorizeInfo)
    {
        
         $listId = LibSession::get('temp_listing_id');

//        PageContext::includePath('authorize');
//
//        $Libauthorize_class   = new  Authorize_class();

        User::$dbObj          = new Db();
        
        $siteName      =   User::$dbObj->selectRow("Settings","value","settingfield='siteName'");
        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_enable'");
        $authorizeLoginId     =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_loginid'");
        $authorizeTransKey    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_transkey'");
        $authorizeEmail       =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_email'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_test_mode'");
        $listName            = 'Test';//User::$dbObj->selectRow("product","Title","nBusId='".$listId."'");
        $adminCurrency    =   User::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");
        
        if($authorizeTestMode=='Y')
        {
            $apiUrl = 'https://apitest.authorize.net/xml/v1/request.api';
        }else{
             $apiUrl = 'https://api.authorize.net/xml/v1/request.api';
        }
        
        
        $creditArray = array();
        $creditArray['createTransactionRequest']['merchantAuthentication']['name'] = $authorizeLoginId;
        $creditArray['createTransactionRequest']['merchantAuthentication']['transactionKey'] = $authorizeTransKey;
        $creditArray['createTransactionRequest']['refId'] = time();
        $creditArray['createTransactionRequest']['transactionRequest']['transactionType'] = "authCaptureTransaction";
        $creditArray['createTransactionRequest']['transactionRequest']['amount'] = $authorizeInfo['amount'];
        $creditArray['createTransactionRequest']['transactionRequest']['profile']['customerProfileId'] = $customerProfileId;
        $creditArray['createTransactionRequest']['transactionRequest']['profile']['paymentProfile']['paymentProfileId'] = $customerPaymentProfileIdList;
        
        $lineItems = array();
        $lineItems['lineItem']['itemId'] = 1;
        $lineItems['lineItem']['name'] = 'Plan purchase';
        $lineItems['lineItems']['lineItem']['name'] = $authorizeInfo['fName'].' '.$authorizeInfo['lName'].' '.$siteName.' Customer';
        $lineItems['lineItem']['quantity'] = 1;
        $lineItems['lineItem']['unitPrice'] = $authorizeInfo['amount'];
        
        
        $creditArray['createTransactionRequest']['transactionRequest']['transactionType']['lineItems'] = $lineItems;
        
        
         $payload = json_encode($creditArray);
      // echo $payload;
      // exit;
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

            $headr = array();
            $headr[] = 'Content-Type: application/json';
           

            curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            # Send request.
            $result = curl_exec($ch);
            // echopre($result);
            curl_close($ch);
        
             $jsonData = preg_replace('/\x{FEFF}/u', '', $result);
       //$jsonData = preg_replace('/\x{feff}$/u', '', $result);
      // echo($jsonData);
            $res = json_decode($jsonData,true);
          // echopre($res);
            if($res['messages']['resultCode']=='Ok' && $res['transactionResponse']['transId']) // Checking payment is Approved
            {

             $paymentsuccessful = true;
             $transactionid = $res['transactionResponse']['transId'];

            }else{
                $paymentsuccessful = false;
                $paymenterror = "The card has been declined";
                $paymenterror .= "" . $res['messages']['message'][0]['code'].'--'.$res['messages']['message'][0]['text'];
                $transactionid = NULL;

            }

       


       if($paymentsuccessful) { 
            $msg = 'Payment was Successfull';
            $data = array('success' => 1, 'list' => $msg);
        }
        else {
            $errorMsg = 'Payment was failure - '.$paymenterror;
            $data = array('failed' => 1, 'list' => $errorMsg);
            
        }

        $payArr = array('transactionId' => $transactionid, 'paymentMethod' => 'authorize');
        $dataArr = array_merge($data, $payArr);
        if($authorizeInfo['customerDetails']){
        $dataArr['customerDetails'] = $authorizeInfo['customerDetails'];
        }
        $dataArr['amount'] = $authorizeInfo['amount'];
        $dataArr['email'] = $authorizeInfo['email'];
        $dataArr['paymentSuccessful'] = $paymentsuccessful;
        $dataArr['paymentError'] = $errorMsg;
        $dataArr['amount'] = $authorizeInfo['amount'];
        $dataArr['payment_method'] = 'authorize';
        
        
        if($transaction_id!='' && $paymentsuccessful) {
  
            $arrProfile =   array("dPaymentDate"=>date("Y-m-d H:i:s", time()),
                    "nUId"=>LibSession::get('reg_usr_id'),
                    "nAmount"=>$authorizeInfo['amount'],
                    "nPId"=>LibSession::get('planid'),
                    "vPaymentMethod"=>"CC",
                    "vTransactionId"=>$transaction_id
            );


            //$paymentId =   User::$dbObj->addFields("Payments",$arrProfile);
            $arrProfile         =   array("nFeatured"=>1);
//            User::$dbObj->updateFields("product",$arrProfile,"nBusId='".$listId."'");

        }

        //echopre($dataArr);

        return $dataArr;



            
            
            
            
            
            
            
            
            
        
    }


    public function proceessStripePayment($customer_id,$payment_id,$secretKey,$dataArray,$intentid)
    {


$paymentsuccessful = false;
require_once('project/lib/stripe/init.php');

\Stripe\Stripe::setApiKey($secretKey);



if($intentid=='')
{
  $intent = \Stripe\PaymentIntent::retrieve($payment_id);  
  $paymentmethod=$intent['charges']['data'][0]->payment_method;
}
else
{
    $paymentmethod=$payment_id;
}





$payment=\Stripe\PaymentIntent::create([
  'amount' => $dataArray['amount']*100,
  'currency' => 'usd',
  'payment_method_types' => ['card'],
  'confirm' =>true,
  'customer'=>$customer_id,
  'receipt_email'=>$dataArray['email'],
  'payment_method'=>$paymentmethod,
]);

if($payment['status']=='succeeded')
{
$paymentsuccessful = true;

}

 if($paymentsuccessful) { 
            $msg = 'Payment was Successfull';
            $data = array('success' => 1, 'list' => $msg);
        }
        else {
            $errorMsg = 'Payment was failure';
            $data = array('failed' => 1, 'list' => $errorMsg);
            
        }


        

        $payArr = array('transactionId' => $payment['id'], 'paymentMethod' => 'stripe');
        $dataArr = array_merge($data, $payArr);
        if($authorizeInfo['customerDetails']){
        $dataArr['customerDetails'] = $dataArray['customerDetails'];
        }
        $dataArr['amount'] = $dataArray['amount'];
        $dataArr['email'] = $dataArray['email'];
        $dataArr['paymentSuccessful'] = $paymentsuccessful;
        //$dataArr['paymentError'] = $errorMsg;
        $dataArr['amount'] = $dataArray['amount'];
        $dataArr['payment_method'] = 'stripe';

        
        
        
        if($payment['id']!='' && $paymentsuccessful) {
  
            $arrProfile =   array("dPaymentDate"=>date("Y-m-d H:i:s", time()),
                    "nUId"=>LibSession::get('reg_usr_id'),
                    "nAmount"=>$authorizeInfo['amount'],
                    "nPId"=>LibSession::get('planid'),
                    "vPaymentMethod"=>"CC",
                    "vTransactionId"=>$payment['id']
            );


            //$paymentId =   User::$dbObj->addFields("Payments",$arrProfile);
            $arrProfile         =   array("nFeatured"=>1);
        }

return $dataArr;



    }
    
    

    public function updateProductLookupCardDetails($customer_id,$productLookUpId)
    {
        User::$dbObj          = new Db();
        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_enable'");
        $authorizeLiveApikey    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_live_apikey'");
        $authorizeSandboxApikey       =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_sandbox_apikey'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_test_mode'");
        
        //echopre($cardArray);
        
            if($authorizeTestMode=='Y')
            {
                $apiurl = 'https://sandbox.bluedogpayments.com/api/';
                $apikey = $authorizeSandboxApikey;
            }else{
                $apiurl = 'https://app.bluedogpayments.com/api/';
                $apikey = $authorizeLiveApikey;

            }
        $url = $apiurl."customer/$customer_id";
        
        $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);

   

    $headr = array();
    $headr[] = 'Content-Type: application/json';
    $headr[] = 'Authorization: '.$apikey;

    curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    # Send request.
    $customer = curl_exec($ch);
    curl_close($ch);
        
    
     
     $res = json_decode($customer,TRUE);
     if($res['status']=='success') 
     {
           $customer_id = $res['data']['id'];
           $payment_method_id = $res['data']['payment_method']['card']['id'];
           $billing_address_id = $res['data']['billing_address']['id'];
           $shipping_address_id = $res['data']['shipping_address']['id'];
           $payment_method_type = $res['data']['payment_method_type'];

           $customerData = array();
           $customerData['customer_id'] = $customer_id;
           $customerData['payment_method_id'] = $payment_method_id;
           $customerData['billing_address_id'] = $billing_address_id;
           $customerData['shipping_address_id'] = $shipping_address_id;
           $customerData['payment_method_type'] = $payment_method_type;
           $customerData['customer_data'] = $res['data'];
            
           $customerData = serialize($customerData);
           
           User::$dbObj->updateFields("ProductLookup",array("bluedogdetails"=>$customerData),"nPLId='".$productLookUpId."'");
           
     }
     
     
        
    }
    
    public function SaveBluedogCardDetails($cardArray,$customer_id,$payement_id)
    {
        User::$dbObj          = new Db();
        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_enable'");
        $authorizeLiveApikey    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_live_apikey'");
        $authorizeSandboxApikey       =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_sandbox_apikey'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_test_mode'");
        
        //echopre($cardArray);
        
            if($authorizeTestMode=='Y')
            {
                $apiurl = 'https://sandbox.bluedogpayments.com/api/';
                $apikey = $authorizeSandboxApikey;
            }else{
                $apiurl = 'https://app.bluedogpayments.com/api/';
                $apikey = $authorizeLiveApikey;

            }
        
        $payload = json_encode($cardArray);

    //echo $cutomerArray;


    $url = $apiurl."customer/$customer_id/paymentmethod/card/$payement_id";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
# Setup request to send json via POST.
//$payload = $cutomerArray;

//echo $payload;

    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //    'Content-Type: application/json',
    //    'Authorization: Bearer '.$token
    //    ));
    //# Return response instead of printing.

    $headr = array();
    $headr[] = 'Content-Type: application/json';
    $headr[] = 'Authorization: '.$apikey;

    curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    # Send request.
    $customer = curl_exec($ch);
    curl_close($ch);

  // echopre1($customer);

/************************* Creating Customer Id in Bluedog End *******************************/

/************************* Processing a Sale transaction using Customer Id in Bluedog *******************************/


   $res = json_decode($customer,TRUE);
    // echopre1($res);       
   return $res;
       
            
    }
    
    
     public function SaveBluedogCardAddressDetails($cardArray,$customer_id,$billing_id,$shipping_id)
    {
        User::$dbObj          = new Db();
        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_enable'");
        $authorizeLiveApikey    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_live_apikey'");
        $authorizeSandboxApikey       =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_sandbox_apikey'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_test_mode'");
        
        //echopre($cardArray);
        
            if($authorizeTestMode=='Y')
            {
                $apiurl = 'https://sandbox.bluedogpayments.com/api/';
                $apikey = $authorizeSandboxApikey;
            }else{
                $apiurl = 'https://app.bluedogpayments.com/api/';
                $apikey = $authorizeLiveApikey;

            }
        
        $payload = json_encode($cardArray);

    //echo $cutomerArray;


    $url = $apiurl."customer/$customer_id/address/$billing_id";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
# Setup request to send json via POST.
//$payload = $cutomerArray;

//echo $payload;

    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //    'Content-Type: application/json',
    //    'Authorization: Bearer '.$token
    //    ));
    //# Return response instead of printing.

    $headr = array();
    $headr[] = 'Content-Type: application/json';
    $headr[] = 'Authorization: '.$apikey;

    curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    # Send request.
    $customer = curl_exec($ch);
    curl_close($ch);

  // echopre1($customer);

/************************* Creating Customer Id in Bluedog End *******************************/

/************************* Processing a Sale transaction using Customer Id in Bluedog *******************************/


   $res = json_decode($customer,TRUE);
    // echopre1($res);       
   return $res;
       
            
    }
    
    
    
    public static function blueDogcreditPayment($authorizeInfo) {
        // User Information

        $listId = LibSession::get('temp_listing_id');



        User::$dbObj          = new Db();

        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_enable'");
        $authorizeLiveApikey    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_live_apikey'");
        $authorizeSandboxApikey       =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_sandbox_apikey'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_test_mode'");
        $listName            = 'Test';//User::$dbObj->selectRow("product","Title","nBusId='".$listId."'");
        $adminCurrency    =   User::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");


//commenting amount as is in controller 09/24/2012 - Jose
        //$authorizeInfo['amount'] = $amount;

        $return =  self::processBlueDogPayment($authorizeInfo,$authorizeLiveApikey,$authorizeSandboxApikey,$authorizeTestMode,$adminCurrency);


        return $return;
    }

 


    function addNewAuthorizeCard()
    {
        
        $listId = LibSession::get('temp_listing_id');

//        PageContext::includePath('authorize');
//
//        $Libauthorize_class   = new  Authorize_class();

        User::$dbObj          = new Db();
        
        $siteName      =   User::$dbObj->selectRow("Settings","value","settingfield='siteName'");
        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_enable'");
        $authorizeLoginId     =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_loginid'");
        $authorizeTransKey    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_transkey'");
        $authorizeEmail       =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_email'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='authorize_test_mode'");
        $listName            = 'Test';//User::$dbObj->selectRow("product","Title","nBusId='".$listId."'");
        $adminCurrency    =   User::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");
        
        if($authorizeTestMode=='Y')
        {
            $apiUrl = 'https://apitest.authorize.net/xml/v1/request.api';
        }else{
             $apiUrl = 'https://api.authorize.net/xml/v1/request.api';
        }
        
        

//        $authorizeInfo['desc'] = $listName;
//        $authorizeInfo['x_login'] = $authorizeLoginId;
//        $authorizeInfo['x_tran_key'] = $authorizeTransKey;
//        $authorizeInfo['email'] = $authorizeEmail;
//        $authorizeInfo['testMode'] = $authorizeTestMode;
//        $authorizeInfo['currency_code'] =$adminCurrency;
        
        //echopre1($authorizeInfo);
        
        
        $cutomerarray = array();
        $billto = array();
        $payment = array();
        
        $cutomerarray['createCustomerProfileRequest']['merchantAuthentication']['name'] = $authorizeLoginId;
        $cutomerarray['createCustomerProfileRequest']['merchantAuthentication']['transactionKey'] = $authorizeTransKey;
        $cutomerarray['createCustomerProfileRequest']['profile']['merchantCustomerId'] = $_POST['vFirstName'].'_'.rand(1,100).rand(1,100);
        $cutomerarray['createCustomerProfileRequest']['profile']['description'] = 'Authorize Profile Creation from '.$siteName;
        $cutomerarray['createCustomerProfileRequest']['profile']['email'] = $_POST['vEmail'];
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['customerType'] = 'individual';
       
        
        $billto['firstName'] = $_POST['vFirstName'];
        $billto['lastName'] = $_POST['vLastName'];
        $billto['address'] = $_POST['vAddress'];
        $billto['city'] = $_POST['vCity'];
        $billto['state'] = $_POST['vState'];
        $billto['zip'] = $_POST['vZipcode'];
        $billto['country'] = ($_POST['vCountry']=='US')?'USA':$_POST['vCountry'];
        $billto['phoneNumber'] = $_POST['vPhone'];
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['billTo'] = $billto;
        //$cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['payment'] = 'individual';
        $payment['creditCard']['cardNumber'] = $_POST['vNumber'];
        $expiryear = "20".$_POST['vYear']; 
        $payment['creditCard']['expirationDate'] = $expiryear.'-'.$_POST['vMonth'];
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['payment'] = $payment;
        $cutomerarray['createCustomerProfileRequest']['profile']['paymentProfiles']['defaultPaymentProfile'] = false;
        $cutomerarray['createCustomerProfileRequest']['validationMode'] = 'liveMode';
      
        
        $payload = json_encode($cutomerarray);
       // echo $payload;
       // exit;
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

            $headr = array();
            $headr[] = 'Content-Type: application/json';
           

            curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            # Send request.
            $result = curl_exec($ch);
            // echopre($result);
            curl_close($ch);
           // $jsonData = str_replace('\ufeff', '', $result);
            $jsonData = preg_replace('/\x{FEFF}/u', '', $result);
       //$jsonData = preg_replace('/\x{feff}$/u', '', $result);
      // echo($jsonData);
            $res = json_decode($jsonData,true);
            
            if($res['messages']['resultCode']=='Ok' && $res['customerProfileId'])
            {
                
                $authorizeData = array();
                
                $customerProfileId = $res['customerProfileId'];
                $customerPaymentProfileIdList = $res['customerPaymentProfileIdList'][0];
                $authorizeData['customerDetails']['address'] = $billto;
                $authorizeData['customerDetails']['cardNumber'] = User::ccMasking($payment['creditCard']['cardNumber']);
                $authorizeData['customerDetails']['expirationDate'] = $payment['creditCard']['expirationDate'];
                $authorizeData['customerDetails']['payment_gateway'] = 'authorize';
                $authorizeData['customerDetails']['customerProfileId'] = $customerProfileId;
                $authorizeData['customerDetails']['customerPaymentProfileIdList'] = $customerPaymentProfileIdList;
                return $authorizeData['customerDetails'];
                
            }else{
                $return = array();
                $return['status'] = 'failed';
                $return['list'] = $res['messages']['message'][0]['text'];
                $return['msg'] = $res['messages']['message'][0]['text'];
                //echopre($return);
                return $return;
               
            }
            
            
            
        
        
        
    }

public function UpdateStripemethod($secretKey,$customerid,$payment_id,$data)
    {


$vNumber=$data['vNumber'];
$vCode=$data['vCode'];
$vMonth=$data['vMonth'];
$vYear=$data['vYear'];



require_once('project/lib/stripe/init.php');

\Stripe\Stripe::setApiKey($secretKey);




try {

$createpaymentmethod=   \Stripe\PaymentMethod::create([
  'type' => 'card',
  'card' => [
    'number' => $vNumber,
    'exp_month' => $vMonth,
    'exp_year' => $vYear,
    'cvc' => $vCode
  ],
]);

$payment_method = \Stripe\PaymentMethod::retrieve($createpaymentmethod['id']);
$payment_method->attach(['customer' => $customerid]);

$data=array('success'=>1,'data'=>$payment_method['id']);





}catch(Exception $e) {
  //echo 'Message: ' .$e->getMessage();

    $data=array('failed'=>1,'data'=>$e->getMessage());

  
}

$data=json_encode($data);
return $data;

    }
    
    
    function addNewBluedogCard($cardArray)
    {


        
        User::$dbObj          = new Db();
        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_enable'");
        $authorizeLiveApikey    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_live_apikey'");
        $authorizeSandboxApikey       =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_sandbox_apikey'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_test_mode'");
        
        //echopre($cardArray);
        
            if($authorizeTestMode=='Y')
            {
                $apiurl = 'https://sandbox.bluedogpayments.com/api/';
                $apikey = $authorizeSandboxApikey;
            }else{
                $apiurl = 'https://app.bluedogpayments.com/api/';
                $apikey = $authorizeLiveApikey;

            }
        
        $payload = json_encode($cardArray);
        
        $url = $apiurl.'customer';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
# Setup request to send json via POST.
//$payload = $cutomerArray;

//echo $payload;

    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //    'Content-Type: application/json',
    //    'Authorization: Bearer '.$token
    //    ));
    //# Return response instead of printing.

    $headr = array();
    $headr[] = 'Content-Type: application/json';
    $headr[] = 'Authorization: '.$apikey;

    curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    # Send request.
    $customer = curl_exec($ch);
    curl_close($ch);

  // echopre1($customer);

/************************* Creating Customer Id in Bluedog End *******************************/

/************************* Processing a Sale transaction using Customer Id in Bluedog *******************************/


   $res = json_decode($customer,TRUE);

   if($res['status']=='success')
       {
           $customer_id = $res['data']['id'];
           $payment_method_id = $res['data']['payment_method']['card']['id'];
           $billing_address_id = $res['data']['billing_address']['id'];
           $shipping_address_id = $res['data']['shipping_address']['id'];
           $payment_method_type = $res['data']['payment_method_type'];

           $customer = array();
           $customer['customer_id'] = $customer_id;
           $customer['payment_gateway'] = 'bluedog';
           $customer['payment_method_id'] = $payment_method_id;
           $customer['billing_address_id'] = $billing_address_id;
           $customer['shipping_address_id'] = $shipping_address_id;
           $customer['payment_method_type'] = $payment_method_type;
           $customer['customer_data'] = $res['data'];
           
           return $customer;
           
       }else{
           
           return $res;
       }
        
        
        
        
        
        
    }
    
    
    

function processBlueDogPayment($authorizeInfo,$authorizeLiveApikey,$authorizeSandboxApikey,$authorizeTestMode,$adminCurrency)
{
    //echopre($_POST);
    //echopre($authorizeInfo);
    //echo $authorizeTestMode; exit;
    if($authorizeTestMode=='Y')
    {
        $apiurl = 'https://sandbox.bluedogpayments.com/api/';
        $apikey = $authorizeSandboxApikey;
    }else{
        $apiurl = 'https://app.bluedogpayments.com/api/';
        $apikey = $authorizeLiveApikey;

    }

    //echo $apiurl;

   /************************* Creating Customer Id in Bluedog *******************************/

    $cutomerArray = array();
    $cutomerArray['description'] =  $authorizeInfo['fName'].' '.$authorizeInfo['lName'].' MakereadyArms Customer';
    $cutomerArray['payment_method']['card']['card_number'] = $authorizeInfo['ccno'];
    $cutomerArray['payment_method']['card']['expiration_date'] = $authorizeInfo['expMonth'].'/'.$authorizeInfo['expYear'];
    $cutomerArray['billing_address']['first_name'] = $authorizeInfo['fName'];
    $cutomerArray['billing_address']['last_name']  = $authorizeInfo['lName'];
    $cutomerArray['billing_address']['company']  = $authorizeInfo['fName'].' '.$authorizeInfo['lName'];
    $cutomerArray['billing_address']['address_line_1']  = $authorizeInfo['add1'];
    //$cutomerArray['billing_address']['address_line_2']  = $authorizeInfo['address_line_2'];
    $cutomerArray['billing_address']['city']  = $authorizeInfo['city'];
    $cutomerArray['billing_address']['state']  = $authorizeInfo['state'];
    $cutomerArray['billing_address']['postal_code']  = $authorizeInfo['zip'];
    $cutomerArray['billing_address']['country']  = $authorizeInfo['country'];
    $cutomerArray['billing_address']['email']  = $authorizeInfo['email'];
    $cutomerArray['billing_address']['phone']  = $authorizeInfo['phone'];
    //$cutomerArray['billing_address']['fax']  = $authorizeInfo['fax'];

    $cutomerArray['shipping_address']['first_name'] = $authorizeInfo['fName'];
    $cutomerArray['shipping_address']['last_name']  = $authorizeInfo['lName'];
    $cutomerArray['shipping_address']['company']  = $authorizeInfo['fName'].' '.$authorizeInfo['lName'];
    $cutomerArray['shipping_address']['address_line_1']  = $authorizeInfo['add1'];
    //$cutomerArray['billing_address']['address_line_2']  = $authorizeInfo['address_line_2'];
    $cutomerArray['shipping_address']['city']  = $authorizeInfo['city'];
    $cutomerArray['shipping_address']['state']  = $authorizeInfo['state'];
    $cutomerArray['shipping_address']['postal_code']  = $authorizeInfo['zip'];
    $cutomerArray['shipping_address']['country']  = $authorizeInfo['country'];
    $cutomerArray['shipping_address']['email']  = $authorizeInfo['email'];
    $cutomerArray['shipping_address']['phone']  = $authorizeInfo['phone'];


    $payload = json_encode($cutomerArray);
//echo $payload;
 //  echopre($cutomerArray);


    $url = $apiurl.'customer';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
# Setup request to send json via POST.
//$payload = $cutomerArray;

//echo $payload;

    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //    'Content-Type: application/json',
    //    'Authorization: Bearer '.$token
    //    ));
    //# Return response instead of printing.

    $headr = array();
    $headr[] = 'Content-Type: application/json';
    $headr[] = 'Authorization: '.$apikey;

    curl_setopt($ch, CURLOPT_HTTPHEADER,$headr);


    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    # Send request.
    $customer = curl_exec($ch);
    curl_close($ch);
     //     echo "Account Creation";
   //echopre($customer);

/************************* Creating Customer Id in Bluedog End *******************************/

/************************* Processing a Sale transaction using Customer Id in Bluedog *******************************/


   $res = json_decode($customer,TRUE);
   //echopre($res);
   if($res['status']=='success')
       {
           $customer_id = $res['data']['id'];
           $payment_method_id = $res['data']['payment_method']['card']['id'];
           $billing_address_id = $res['data']['billing_address']['id'];
           $shipping_address_id = $res['data']['shipping_address']['id'];
           $payment_method_type = $res['data']['payment_method_type'];

           $customer = array();
           $customer['customer_id'] = $customer_id;
           $customer['payment_gateway'] = 'bluedog';
           $customer['payment_method_id'] = $payment_method_id;
           $customer['billing_address_id'] = $billing_address_id;
           $customer['shipping_address_id'] = $shipping_address_id;
           $customer['payment_method_type'] = $payment_method_type;
           $customer['customer_data'] = $res['data'];



            $order_id = time();
            $po_number = "po_".$order_id;
            $transactionArray = array();
            $transactionArray['type'] = 'sale';
            //$transactionArray['amount'] = $authorizeInfo['amount'] * 100;
            if($authorizeTestMode=='Y')
            {
            $transactionArray['amount'] = $authorizeInfo['amount']*1; // for sandbox mode only need to keep the amount below $100
            }else{
            $transactionArray['amount'] = $authorizeInfo['amount'] * 100; // converting amount  to cents
            }
            $transactionArray['tax_amount'] = 0;
            $transactionArray['shipping_amount'] = 0;
            $transactionArray['currency'] = 'USD';
            $transactionArray['description'] = $authorizeInfo['fName'].' '.$authorizeInfo['lName'].' transaction from MakereadyArms';
            $transactionArray['order_id'] = "$order_id";
            $transactionArray['po_number'] = "$po_number";
            $transactionArray['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $transactionArray['email_receipt'] = true;
            $transactionArray['email_address'] = $authorizeInfo['email'];
            $transactionArray['create_vault_record'] = true;
            $transactionArray['payment_method']['customer']['id'] = $customer_id;
            $transactionArray['payment_method']['customer']['payment_method_type'] = $payment_method_type;
            $transactionArray['payment_method']['customer']['payment_method_id'] = $payment_method_id;
            $transactionArray['payment_method']['customer']['billing_address_id'] = $billing_address_id;
            $transactionArray['payment_method']['customer']['shipping_address_id'] = $shipping_address_id;
            $payload = json_encode($transactionArray);

    //echo $cutomerArray;


            $url = $apiurl.'transaction';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            # Setup request to send json via POST.
            //$payload = $cutomerArray;
            //echo $payload;

            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            //    'Content-Type: application/json',
            //    'Authorization: Bearer '.$token
            //    ));
            //# Return response instead of printing.

            $headr = array();
            $headr[] = 'Content-Type: application/json';
            $headr[] = 'Authorization: ' . $apikey;

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            # Send request.
            $result = curl_exec($ch);
            curl_close($ch);

            $paymentres = json_decode($result,TRUE);
          // echo "Pyament";
//echopre($paymentres);


            /************************* Processing a Sale transaction using Customer Id in Bluedog End *******************************/


            //echopre($paymentres);
            //echopre($paymentres['data']['response_body']['card']['response_code']);
            if($paymentres['status'] == 'success' && $paymentres['data']['response_body']['card']['response_code']=='100') // Checking payment is Approved
            {

             $paymentsuccessful = true;
             $transactionid = $paymentres['data']['id'];

            }else{
                $paymentsuccessful = false;
                $paymenterror = "The card has been declined";
                $paymenterror .= "" . $paymentres['data']['response_body']['card']['response'].'--'.$paymentres['data']['response_body']['card']['response_code'];
                $transactionid = NULL;

            }

        }else{
                $paymentsuccessful = false;
                $paymenterror = "Unable to Add Customer to Bluedog";
                //$paymenterror .= "" . $paymentres['data']['response_body']['card']['response'].'--'.$paymentres['data']['response_body']['card']['response_code'];
                $transactionid = NULL;

       }


       if($paymentsuccessful) {
            if($transaction_id!='' && $paymentsuccessful) {


            }
            $msg = 'Payment was Successfull';
            $data = array('success' => 1, 'list' => $msg);
        }
        else {
            $errorMsg = 'Payment was failure - '.$paymenterror;
            $data = array('failed' => 1, 'list' => $errorMsg);
        }

        $payArr = array('transactionId' => $transactionid, 'paymentMethod' => 'bluedog');
        $dataArr = array_merge($data, $payArr);
        $dataArr['customerDetails'] = $customer;
        $dataArr['amount'] = $authorizeInfo['amount'];
        $dataArr['email'] = $authorizeInfo['email'];

        if($transaction_id!='' && $paymentsuccessful) {

            $arrProfile =   array("dPaymentDate"=>date("Y-m-d H:i:s", time()),
                    "nUId"=>LibSession::get('reg_usr_id'),
                    "nAmount"=>$authorizeInfo['amount'],
                    "nPId"=>LibSession::get('planid'),
                    "vPaymentMethod"=>"CC",
                    "vTransactionId"=>$transaction_id
            );


            //$paymentId =   User::$dbObj->addFields("Payments",$arrProfile);
            $arrProfile         =   array("nFeatured"=>1);
//            User::$dbObj->updateFields("product",$arrProfile,"nBusId='".$listId."'");

        }

        //echopre($dataArr);

        return $dataArr;








   // echo $transactionid; exit;

}







    public static function checkdomainstatus($sld,$tld) {
        User::$dbObj            = new Db();
        $domain_registrar       = User::$dbObj->selectRow("Settings","value","settingfield='domain_registrar'");

        if($domain_registrar == "ENOM") {
            // Create an instance of the url interface class
            PageContext::includePath('enom');
            $Enom = new Enominterface();
            // Set account username and password
            $username       = User::$dbObj->selectRow("Settings","value","settingfield='enom_user'");
            $password       = User::$dbObj->selectRow("Settings","value","settingfield='enom_password'");
            $password       = User::decrytCreditCardDetails($password);
            $enommode       = User::$dbObj->selectRow("Settings","value","settingfield='enom_testmode'");

            $Enom->AddParam( "uid", $username );
            $Enom->AddParam( "pw", $password );
            /****************************************************************************************************/
            $sql	=	"SELECT tld FROM ".MYSQL_TABLE_PREFIX."tld WHERE registrar = 'ENOM'";
            $res	=	User::$dbObj->selectQuery($sql);
            $c	=	0;
            foreach($res	as	$tlarr) {
                $avarr[$c]	=	$tlarr->tld;
                $c++;
            }
            if(isset($avarr)) {
                $avilabletlds	=	implode(",",$avarr);
            }
            /****************************************************************************************************/
            //$avilabletlds="com,net,org,info,cc,us";
            //$avilabletlds=urlencode($avilabletlds);
            // Set the domain name to check
            //$Enom->AddParam( "tld", $tld );
            $Enom->AddParam( "tldlist", $avilabletlds );
            // Set the domain name to check
            // $Enom->AddParam( "tld", $tld );
            $Enom->AddParam( "sld", $sld );

            // Check the name
            $Enom->AddParam( "command", "check" );
            $Enom->DoTransaction($enommode);
            //echopre($Enom);
            //echo $sld.$tld;
            // Were there errors?
            if ( $Enom->Values[ "ErrCount" ] != "0" ) {
                // Yes, get the first one
                $cErrorMsg  = $Enom->Values[ "Err1" ];
                // Flag an error
                $bError 	= 1;
            } else {
                $nodomainschecked	= $Enom->Values[ "DomainCount" ];
                $bAvailable			= array();
                $domainarray		= array();
                $bError				= array();
                $cErrorMsg			= array();
                $originaldomain 	= $sld.".".$tld;
                $domainFlag         = 0;
                for($i=1;$i<=$nodomainschecked;$i++) {
                    $rppcode		 = "RRPCode".$i;
                    $rptext			 = "RRPText".$i;
                    $domaincode 	 = "Domain".$i;
                    $domainarray[$i] = $Enom->Values[$domaincode ];
                    // No interface errors
                    $bError[$domaincode] = 0;
//                    print_r($Enom->Values[$rppcode ]);
                    // Check code from NSI (210 = name available)
                    switch ( $Enom->Values[$rppcode ] ) {
                        case "210":
                            if($Enom->Values[$domaincode ]==$originaldomain) {
                                // The name is available
                                $domainFlag =1;

                            }
                            else {

                            }
                            $bAvailable[$i] = 1;
                            break;

                        case "211":
                        // The name is not available
                            $bAvailable[$i] = 0;
                            break;

                        default:
                        // There was an error from NSI
                            $bError[$i]    = 1;
                            $cErrorMsg[$i] = $Enom->Values[ $rptext ];
                            break;
                    }

                    if(strcmp($originaldomain,$domainarray[$i]) == 0) {
                        $originaldomainavailable	= $bAvailable[$i];
                        $originaldomaierror			= 0;
                        if($bError[$i] == 1) {
                            $originaldomaierror			= 1;
                            $originaldomaierrormessage	= $cErrorMsg[$i];
                        }
                    }
                }
                return $domainFlag;
                die;
                /* find the suggested name*/
                $Enom1 = new Enominterface();

                // Set account username and password
                $Enom1->AddParam( "uid", $username );
                $Enom1->AddParam( "pw", $password );
                $Enom1->AddParam( "sld", $sld );
                $Enom1->AddParam( "tld", $tld );
                $Enom1->AddParam( "DomainSpinner", "1");
                $Enom1->AddParam( "Word1", $sld);
                $Enom1->AddParam( "Command", "Check" );
                //echo $Enom1->PostString;echo "<br>";
                $Enom1->DoTransaction($enommode);

                //print_r($Enom1->Values); echo "--";echo $Enom1->Values[ "count" ];exit;
                $suggestedcount	= $Enom1->Values[ "count" ];
                $orginaltld		= $Enom1->Values["originalTLD"];
                $suggestednames = array();
                for($j=1;$j<=$suggestedcount;$j++) {
                    $suggname		= "SuggestedName".$j;
                    $suggestednames[$j] = $Enom1->Values[ $suggname ];
                }
                /*--------------------------*/

            }
        }
        elseif($domain_registrar == "GODADDY"){
            // Create an instance of the url interface class
           /* PageContext::includePath('godaddy');
           $isDomAvailable = new goDaddy;
           $domainArray = array(strtolower($sld).".".strtolower($tld));
           $op = $isDomAvailable->checkdomainavailability($domainArray);
           $bError = 0;

           $originaldomain = strtolower($sld).".".strtolower($tld);
           if($op[0]['avail'] == 0) {
               $originaldomainavailable = 0;
           } else {
               $originaldomainavailable = 1;
           } */

            $godaddy_testmode       = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_testmode'");
            $godaddy_id             = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_id'");
            $godaddy_pwd            = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_password'");
            $godaddy_password       = User::decrytCreditCardDetails($godaddy_pwd);
            $apiUrl                 = $godaddy_testmode == 'Y' ?  "https://api.ote-godaddy.com" : "https://api.godaddy.com";

            $jsdata                 = array(strtolower($sld).".".strtolower($tld));
            $data_json              = json_encode($jsdata);

            $url                    = $apiUrl."/v1/domains/available?checkType=FULL";
            if(trim($godaddy_testmode) == "Y"){
                $myKey              = "VVJ3xaQ5_LugqHW7UZbcEfH1WdsFw7A";
                $mySecret           = "LugtmuJE8gb4SW3Kx4fnHS";
            }else{
                $myKey              = "VVJ3xaQ5_LugqHW7UZbcEfH1WdsFw7A";
                $mySecret           = "LugtmuJE8gb4SW3Kx4fnHS";
            }

            $ch                     = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: sso-key '.$myKey.':'.$mySecret,'Accept: application/json','Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $resp                   = curl_exec($ch);
            curl_close($ch);

            $arrJsonResponse        = array();
            $arrJsonResponse        = json_decode($resp,1);

            if(is_array($arrJsonResponse) && count($arrJsonResponse)>0){
                if($arrJsonResponse["domains"][0]['available'] == 0){
                   $originaldomainavailable = 0;
                }else{
                   $originaldomainavailable = 1;
                }
            }

            return $originaldomainavailable;
            die;
        }
    }

    public static function checkdomainispurchased($domainName) {
        User::$dbObj            = new Db();
        $domain_registrar       = User::$dbObj->selectRow("Settings","value","settingfield='domain_registrar'");

        if($domain_registrar == "GODADDY"){

            $godaddy_testmode       = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_testmode'");
            $godaddy_id             = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_id'");
            $godaddy_pwd            = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_password'");
            $godaddy_password       = User::decrytCreditCardDetails($godaddy_pwd);
            $apiUrl                 = $godaddy_testmode == 'Y' ?  "https://api.ote-godaddy.com" : "https://api.godaddy.com";

            $jsdata                 = array($domainName);
            $data_json              = json_encode($jsdata);

            $url                    = $apiUrl."/v1/domains/available?checkType=FULL";
            if(trim($godaddy_testmode) == "Y"){
                $myKey              = "VVJ3xaQ5_LugqHW7UZbcEfH1WdsFw7A";
                $mySecret           = "LugtmuJE8gb4SW3Kx4fnHS";
            }else{
                $myKey              = "VVJ3xaQ5_LugqHW7UZbcEfH1WdsFw7A";
                $mySecret           = "LugtmuJE8gb4SW3Kx4fnHS";
            }

            $ch                     = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: sso-key $myKey:$mySecret','Accept: application/json'));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $resp                   = curl_exec($ch);
            curl_close($ch);

            $arrJsonResponse        = array();
            $arrJsonResponse        = json_decode($resp,1);


            if(is_array($arrJsonResponse) && count($arrJsonResponse)>0){
                if($arrJsonResponse["domains"][0]['available'] == 0){
                   $originaldomainavailable = 0;
                }else{
                   $originaldomainavailable = 1;
                }
            }

            return $originaldomainavailable;
            die;
        }
    }

    public function get_country_phonecode($code){
        $countries = array();
        $countries[] = array("code"=>"AF","name"=>"Afghanistan","d_code"=>"+93");
        $countries[] = array("code"=>"AL","name"=>"Albania","d_code"=>"+355");
        $countries[] = array("code"=>"DZ","name"=>"Algeria","d_code"=>"+213");
        $countries[] = array("code"=>"AS","name"=>"American Samoa","d_code"=>"+1");
        $countries[] = array("code"=>"AD","name"=>"Andorra","d_code"=>"+376");
        $countries[] = array("code"=>"AO","name"=>"Angola","d_code"=>"+244");
        $countries[] = array("code"=>"AI","name"=>"Anguilla","d_code"=>"+1");
        $countries[] = array("code"=>"AG","name"=>"Antigua","d_code"=>"+1");
        $countries[] = array("code"=>"AR","name"=>"Argentina","d_code"=>"+54");
        $countries[] = array("code"=>"AM","name"=>"Armenia","d_code"=>"+374");
        $countries[] = array("code"=>"AW","name"=>"Aruba","d_code"=>"+297");
        $countries[] = array("code"=>"AU","name"=>"Australia","d_code"=>"+61");
        $countries[] = array("code"=>"AT","name"=>"Austria","d_code"=>"+43");
        $countries[] = array("code"=>"AZ","name"=>"Azerbaijan","d_code"=>"+994");
        $countries[] = array("code"=>"BH","name"=>"Bahrain","d_code"=>"+973");
        $countries[] = array("code"=>"BD","name"=>"Bangladesh","d_code"=>"+880");
        $countries[] = array("code"=>"BB","name"=>"Barbados","d_code"=>"+1");
        $countries[] = array("code"=>"BY","name"=>"Belarus","d_code"=>"+375");
        $countries[] = array("code"=>"BE","name"=>"Belgium","d_code"=>"+32");
        $countries[] = array("code"=>"BZ","name"=>"Belize","d_code"=>"+501");
        $countries[] = array("code"=>"BJ","name"=>"Benin","d_code"=>"+229");
        $countries[] = array("code"=>"BM","name"=>"Bermuda","d_code"=>"+1");
        $countries[] = array("code"=>"BT","name"=>"Bhutan","d_code"=>"+975");
        $countries[] = array("code"=>"BO","name"=>"Bolivia","d_code"=>"+591");
        $countries[] = array("code"=>"BA","name"=>"Bosnia and Herzegovina","d_code"=>"+387");
        $countries[] = array("code"=>"BW","name"=>"Botswana","d_code"=>"+267");
        $countries[] = array("code"=>"BR","name"=>"Brazil","d_code"=>"+55");
        $countries[] = array("code"=>"IO","name"=>"British Indian Ocean Territory","d_code"=>"+246");
        $countries[] = array("code"=>"VG","name"=>"British Virgin Islands","d_code"=>"+1");
        $countries[] = array("code"=>"BN","name"=>"Brunei","d_code"=>"+673");
        $countries[] = array("code"=>"BG","name"=>"Bulgaria","d_code"=>"+359");
        $countries[] = array("code"=>"BF","name"=>"Burkina Faso","d_code"=>"+226");
        $countries[] = array("code"=>"MM","name"=>"Burma Myanmar" ,"d_code"=>"+95");
        $countries[] = array("code"=>"BI","name"=>"Burundi","d_code"=>"+257");
        $countries[] = array("code"=>"KH","name"=>"Cambodia","d_code"=>"+855");
        $countries[] = array("code"=>"CM","name"=>"Cameroon","d_code"=>"+237");
        $countries[] = array("code"=>"CA","name"=>"Canada","d_code"=>"+1");
        $countries[] = array("code"=>"CV","name"=>"Cape Verde","d_code"=>"+238");
        $countries[] = array("code"=>"KY","name"=>"Cayman Islands","d_code"=>"+1");
        $countries[] = array("code"=>"CF","name"=>"Central African Republic","d_code"=>"+236");
        $countries[] = array("code"=>"TD","name"=>"Chad","d_code"=>"+235");
        $countries[] = array("code"=>"CL","name"=>"Chile","d_code"=>"+56");
        $countries[] = array("code"=>"CN","name"=>"China","d_code"=>"+86");
        $countries[] = array("code"=>"CO","name"=>"Colombia","d_code"=>"+57");
        $countries[] = array("code"=>"KM","name"=>"Comoros","d_code"=>"+269");
        $countries[] = array("code"=>"CK","name"=>"Cook Islands","d_code"=>"+682");
        $countries[] = array("code"=>"CR","name"=>"Costa Rica","d_code"=>"+506");
        $countries[] = array("code"=>"CI","name"=>"Côte d'Ivoire" ,"d_code"=>"+225");
        $countries[] = array("code"=>"HR","name"=>"Croatia","d_code"=>"+385");
        $countries[] = array("code"=>"CU","name"=>"Cuba","d_code"=>"+53");
        $countries[] = array("code"=>"CY","name"=>"Cyprus","d_code"=>"+357");
        $countries[] = array("code"=>"CZ","name"=>"Czech Republic","d_code"=>"+420");
        $countries[] = array("code"=>"CD","name"=>"Democratic Republic of Congo","d_code"=>"+243");
        $countries[] = array("code"=>"DK","name"=>"Denmark","d_code"=>"+45");
        $countries[] = array("code"=>"DJ","name"=>"Djibouti","d_code"=>"+253");
        $countries[] = array("code"=>"DM","name"=>"Dominica","d_code"=>"+1");
        $countries[] = array("code"=>"DO","name"=>"Dominican Republic","d_code"=>"+1");
        $countries[] = array("code"=>"EC","name"=>"Ecuador","d_code"=>"+593");
        $countries[] = array("code"=>"EG","name"=>"Egypt","d_code"=>"+20");
        $countries[] = array("code"=>"SV","name"=>"El Salvador","d_code"=>"+503");
        $countries[] = array("code"=>"GQ","name"=>"Equatorial Guinea","d_code"=>"+240");
        $countries[] = array("code"=>"ER","name"=>"Eritrea","d_code"=>"+291");
        $countries[] = array("code"=>"EE","name"=>"Estonia","d_code"=>"+372");
        $countries[] = array("code"=>"ET","name"=>"Ethiopia","d_code"=>"+251");
        $countries[] = array("code"=>"FK","name"=>"Falkland Islands","d_code"=>"+500");
        $countries[] = array("code"=>"FO","name"=>"Faroe Islands","d_code"=>"+298");
        $countries[] = array("code"=>"FM","name"=>"Federated States of Micronesia","d_code"=>"+691");
        $countries[] = array("code"=>"FJ","name"=>"Fiji","d_code"=>"+679");
        $countries[] = array("code"=>"FI","name"=>"Finland","d_code"=>"+358");
        $countries[] = array("code"=>"FR","name"=>"France","d_code"=>"+33");
        $countries[] = array("code"=>"GF","name"=>"French Guiana","d_code"=>"+594");
        $countries[] = array("code"=>"PF","name"=>"French Polynesia","d_code"=>"+689");
        $countries[] = array("code"=>"GA","name"=>"Gabon","d_code"=>"+241");
        $countries[] = array("code"=>"GE","name"=>"Georgia","d_code"=>"+995");
        $countries[] = array("code"=>"DE","name"=>"Germany","d_code"=>"+49");
        $countries[] = array("code"=>"GH","name"=>"Ghana","d_code"=>"+233");
        $countries[] = array("code"=>"GI","name"=>"Gibraltar","d_code"=>"+350");
        $countries[] = array("code"=>"GR","name"=>"Greece","d_code"=>"+30");
        $countries[] = array("code"=>"GL","name"=>"Greenland","d_code"=>"+299");
        $countries[] = array("code"=>"GD","name"=>"Grenada","d_code"=>"+1");
        $countries[] = array("code"=>"GP","name"=>"Guadeloupe","d_code"=>"+590");
        $countries[] = array("code"=>"GU","name"=>"Guam","d_code"=>"+1");
        $countries[] = array("code"=>"GT","name"=>"Guatemala","d_code"=>"+502");
        $countries[] = array("code"=>"GN","name"=>"Guinea","d_code"=>"+224");
        $countries[] = array("code"=>"GW","name"=>"Guinea-Bissau","d_code"=>"+245");
        $countries[] = array("code"=>"GY","name"=>"Guyana","d_code"=>"+592");
        $countries[] = array("code"=>"HT","name"=>"Haiti","d_code"=>"+509");
        $countries[] = array("code"=>"HN","name"=>"Honduras","d_code"=>"+504");
        $countries[] = array("code"=>"HK","name"=>"Hong Kong","d_code"=>"+852");
        $countries[] = array("code"=>"HU","name"=>"Hungary","d_code"=>"+36");
        $countries[] = array("code"=>"IS","name"=>"Iceland","d_code"=>"+354");
        $countries[] = array("code"=>"IN","name"=>"India","d_code"=>"+91");
        $countries[] = array("code"=>"ID","name"=>"Indonesia","d_code"=>"+62");
        $countries[] = array("code"=>"IR","name"=>"Iran","d_code"=>"+98");
        $countries[] = array("code"=>"IQ","name"=>"Iraq","d_code"=>"+964");
        $countries[] = array("code"=>"IE","name"=>"Ireland","d_code"=>"+353");
        $countries[] = array("code"=>"IL","name"=>"Israel","d_code"=>"+972");
        $countries[] = array("code"=>"IT","name"=>"Italy","d_code"=>"+39");
        $countries[] = array("code"=>"JM","name"=>"Jamaica","d_code"=>"+1");
        $countries[] = array("code"=>"JP","name"=>"Japan","d_code"=>"+81");
        $countries[] = array("code"=>"JO","name"=>"Jordan","d_code"=>"+962");
        $countries[] = array("code"=>"KZ","name"=>"Kazakhstan","d_code"=>"+7");
        $countries[] = array("code"=>"KE","name"=>"Kenya","d_code"=>"+254");
        $countries[] = array("code"=>"KI","name"=>"Kiribati","d_code"=>"+686");
        $countries[] = array("code"=>"XK","name"=>"Kosovo","d_code"=>"+381");
        $countries[] = array("code"=>"KW","name"=>"Kuwait","d_code"=>"+965");
        $countries[] = array("code"=>"KG","name"=>"Kyrgyzstan","d_code"=>"+996");
        $countries[] = array("code"=>"LA","name"=>"Laos","d_code"=>"+856");
        $countries[] = array("code"=>"LV","name"=>"Latvia","d_code"=>"+371");
        $countries[] = array("code"=>"LB","name"=>"Lebanon","d_code"=>"+961");
        $countries[] = array("code"=>"LS","name"=>"Lesotho","d_code"=>"+266");
        $countries[] = array("code"=>"LR","name"=>"Liberia","d_code"=>"+231");
        $countries[] = array("code"=>"LY","name"=>"Libya","d_code"=>"+218");
        $countries[] = array("code"=>"LI","name"=>"Liechtenstein","d_code"=>"+423");
        $countries[] = array("code"=>"LT","name"=>"Lithuania","d_code"=>"+370");
        $countries[] = array("code"=>"LU","name"=>"Luxembourg","d_code"=>"+352");
        $countries[] = array("code"=>"MO","name"=>"Macau","d_code"=>"+853");
        $countries[] = array("code"=>"MK","name"=>"Macedonia","d_code"=>"+389");
        $countries[] = array("code"=>"MG","name"=>"Madagascar","d_code"=>"+261");
        $countries[] = array("code"=>"MW","name"=>"Malawi","d_code"=>"+265");
        $countries[] = array("code"=>"MY","name"=>"Malaysia","d_code"=>"+60");
        $countries[] = array("code"=>"MV","name"=>"Maldives","d_code"=>"+960");
        $countries[] = array("code"=>"ML","name"=>"Mali","d_code"=>"+223");
        $countries[] = array("code"=>"MT","name"=>"Malta","d_code"=>"+356");
        $countries[] = array("code"=>"MH","name"=>"Marshall Islands","d_code"=>"+692");
        $countries[] = array("code"=>"MQ","name"=>"Martinique","d_code"=>"+596");
        $countries[] = array("code"=>"MR","name"=>"Mauritania","d_code"=>"+222");
        $countries[] = array("code"=>"MU","name"=>"Mauritius","d_code"=>"+230");
        $countries[] = array("code"=>"YT","name"=>"Mayotte","d_code"=>"+262");
        $countries[] = array("code"=>"MX","name"=>"Mexico","d_code"=>"+52");
        $countries[] = array("code"=>"MD","name"=>"Moldova","d_code"=>"+373");
        $countries[] = array("code"=>"MC","name"=>"Monaco","d_code"=>"+377");
        $countries[] = array("code"=>"MN","name"=>"Mongolia","d_code"=>"+976");
        $countries[] = array("code"=>"ME","name"=>"Montenegro","d_code"=>"+382");
        $countries[] = array("code"=>"MS","name"=>"Montserrat","d_code"=>"+1");
        $countries[] = array("code"=>"MA","name"=>"Morocco","d_code"=>"+212");
        $countries[] = array("code"=>"MZ","name"=>"Mozambique","d_code"=>"+258");
        $countries[] = array("code"=>"NA","name"=>"Namibia","d_code"=>"+264");
        $countries[] = array("code"=>"NR","name"=>"Nauru","d_code"=>"+674");
        $countries[] = array("code"=>"NP","name"=>"Nepal","d_code"=>"+977");
        $countries[] = array("code"=>"NL","name"=>"Netherlands","d_code"=>"+31");
        $countries[] = array("code"=>"AN","name"=>"Netherlands Antilles","d_code"=>"+599");
        $countries[] = array("code"=>"NC","name"=>"New Caledonia","d_code"=>"+687");
        $countries[] = array("code"=>"NZ","name"=>"New Zealand","d_code"=>"+64");
        $countries[] = array("code"=>"NI","name"=>"Nicaragua","d_code"=>"+505");
        $countries[] = array("code"=>"NE","name"=>"Niger","d_code"=>"+227");
        $countries[] = array("code"=>"NG","name"=>"Nigeria","d_code"=>"+234");
        $countries[] = array("code"=>"NU","name"=>"Niue","d_code"=>"+683");
        $countries[] = array("code"=>"NF","name"=>"Norfolk Island","d_code"=>"+672");
        $countries[] = array("code"=>"KP","name"=>"North Korea","d_code"=>"+850");
        $countries[] = array("code"=>"MP","name"=>"Northern Mariana Islands","d_code"=>"+1");
        $countries[] = array("code"=>"NO","name"=>"Norway","d_code"=>"+47");
        $countries[] = array("code"=>"OM","name"=>"Oman","d_code"=>"+968");
        $countries[] = array("code"=>"PK","name"=>"Pakistan","d_code"=>"+92");
        $countries[] = array("code"=>"PW","name"=>"Palau","d_code"=>"+680");
        $countries[] = array("code"=>"PS","name"=>"Palestine","d_code"=>"+970");
        $countries[] = array("code"=>"PA","name"=>"Panama","d_code"=>"+507");
        $countries[] = array("code"=>"PG","name"=>"Papua New Guinea","d_code"=>"+675");
        $countries[] = array("code"=>"PY","name"=>"Paraguay","d_code"=>"+595");
        $countries[] = array("code"=>"PE","name"=>"Peru","d_code"=>"+51");
        $countries[] = array("code"=>"PH","name"=>"Philippines","d_code"=>"+63");
        $countries[] = array("code"=>"PL","name"=>"Poland","d_code"=>"+48");
        $countries[] = array("code"=>"PT","name"=>"Portugal","d_code"=>"+351");
        $countries[] = array("code"=>"PR","name"=>"Puerto Rico","d_code"=>"+1");
        $countries[] = array("code"=>"QA","name"=>"Qatar","d_code"=>"+974");
        $countries[] = array("code"=>"CG","name"=>"Republic of the Congo","d_code"=>"+242");
        $countries[] = array("code"=>"RE","name"=>"Réunion" ,"d_code"=>"+262");
        $countries[] = array("code"=>"RO","name"=>"Romania","d_code"=>"+40");
        $countries[] = array("code"=>"RU","name"=>"Russia","d_code"=>"+7");
        $countries[] = array("code"=>"RW","name"=>"Rwanda","d_code"=>"+250");
        $countries[] = array("code"=>"BL","name"=>"Saint Barthélemy" ,"d_code"=>"+590");
        $countries[] = array("code"=>"SH","name"=>"Saint Helena","d_code"=>"+290");
        $countries[] = array("code"=>"KN","name"=>"Saint Kitts and Nevis","d_code"=>"+1");
        $countries[] = array("code"=>"MF","name"=>"Saint Martin","d_code"=>"+590");
        $countries[] = array("code"=>"PM","name"=>"Saint Pierre and Miquelon","d_code"=>"+508");
        $countries[] = array("code"=>"VC","name"=>"Saint Vincent and the Grenadines","d_code"=>"+1");
        $countries[] = array("code"=>"WS","name"=>"Samoa","d_code"=>"+685");
        $countries[] = array("code"=>"SM","name"=>"San Marino","d_code"=>"+378");
        $countries[] = array("code"=>"ST","name"=>"São Tomé and Príncipe" ,"d_code"=>"+239");
        $countries[] = array("code"=>"SA","name"=>"Saudi Arabia","d_code"=>"+966");
        $countries[] = array("code"=>"SN","name"=>"Senegal","d_code"=>"+221");
        $countries[] = array("code"=>"RS","name"=>"Serbia","d_code"=>"+381");
        $countries[] = array("code"=>"SC","name"=>"Seychelles","d_code"=>"+248");
        $countries[] = array("code"=>"SL","name"=>"Sierra Leone","d_code"=>"+232");
        $countries[] = array("code"=>"SG","name"=>"Singapore","d_code"=>"+65");
        $countries[] = array("code"=>"SK","name"=>"Slovakia","d_code"=>"+421");
        $countries[] = array("code"=>"SI","name"=>"Slovenia","d_code"=>"+386");
        $countries[] = array("code"=>"SB","name"=>"Solomon Islands","d_code"=>"+677");
        $countries[] = array("code"=>"SO","name"=>"Somalia","d_code"=>"+252");
        $countries[] = array("code"=>"ZA","name"=>"South Africa","d_code"=>"+27");
        $countries[] = array("code"=>"KR","name"=>"South Korea","d_code"=>"+82");
        $countries[] = array("code"=>"ES","name"=>"Spain","d_code"=>"+34");
        $countries[] = array("code"=>"LK","name"=>"Sri Lanka","d_code"=>"+94");
        $countries[] = array("code"=>"LC","name"=>"St. Lucia","d_code"=>"+1");
        $countries[] = array("code"=>"SD","name"=>"Sudan","d_code"=>"+249");
        $countries[] = array("code"=>"SR","name"=>"Suriname","d_code"=>"+597");
        $countries[] = array("code"=>"SZ","name"=>"Swaziland","d_code"=>"+268");
        $countries[] = array("code"=>"SE","name"=>"Sweden","d_code"=>"+46");
        $countries[] = array("code"=>"CH","name"=>"Switzerland","d_code"=>"+41");
        $countries[] = array("code"=>"SY","name"=>"Syria","d_code"=>"+963");
        $countries[] = array("code"=>"TW","name"=>"Taiwan","d_code"=>"+886");
        $countries[] = array("code"=>"TJ","name"=>"Tajikistan","d_code"=>"+992");
        $countries[] = array("code"=>"TZ","name"=>"Tanzania","d_code"=>"+255");
        $countries[] = array("code"=>"TH","name"=>"Thailand","d_code"=>"+66");
        $countries[] = array("code"=>"BS","name"=>"The Bahamas","d_code"=>"+1");
        $countries[] = array("code"=>"GM","name"=>"The Gambia","d_code"=>"+220");
        $countries[] = array("code"=>"TL","name"=>"Timor-Leste","d_code"=>"+670");
        $countries[] = array("code"=>"TG","name"=>"Togo","d_code"=>"+228");
        $countries[] = array("code"=>"TK","name"=>"Tokelau","d_code"=>"+690");
        $countries[] = array("code"=>"TO","name"=>"Tonga","d_code"=>"+676");
        $countries[] = array("code"=>"TT","name"=>"Trinidad and Tobago","d_code"=>"+1");
        $countries[] = array("code"=>"TN","name"=>"Tunisia","d_code"=>"+216");
        $countries[] = array("code"=>"TR","name"=>"Turkey","d_code"=>"+90");
        $countries[] = array("code"=>"TM","name"=>"Turkmenistan","d_code"=>"+993");
        $countries[] = array("code"=>"TC","name"=>"Turks and Caicos Islands","d_code"=>"+1");
        $countries[] = array("code"=>"TV","name"=>"Tuvalu","d_code"=>"+688");
        $countries[] = array("code"=>"UG","name"=>"Uganda","d_code"=>"+256");
        $countries[] = array("code"=>"UA","name"=>"Ukraine","d_code"=>"+380");
        $countries[] = array("code"=>"AE","name"=>"United Arab Emirates","d_code"=>"+971");
        $countries[] = array("code"=>"GB","name"=>"United Kingdom","d_code"=>"+44");
        $countries[] = array("code"=>"US","name"=>"United States","d_code"=>"+1");
        $countries[] = array("code"=>"UY","name"=>"Uruguay","d_code"=>"+598");
        $countries[] = array("code"=>"VI","name"=>"US Virgin Islands","d_code"=>"+1");
        $countries[] = array("code"=>"UZ","name"=>"Uzbekistan","d_code"=>"+998");
        $countries[] = array("code"=>"VU","name"=>"Vanuatu","d_code"=>"+678");
        $countries[] = array("code"=>"VA","name"=>"Vatican City","d_code"=>"+39");
        $countries[] = array("code"=>"VE","name"=>"Venezuela","d_code"=>"+58");
        $countries[] = array("code"=>"VN","name"=>"Vietnam","d_code"=>"+84");
        $countries[] = array("code"=>"WF","name"=>"Wallis and Futuna","d_code"=>"+681");
        $countries[] = array("code"=>"YE","name"=>"Yemen","d_code"=>"+967");
        $countries[] = array("code"=>"ZM","name"=>"Zambia","d_code"=>"+260");
        $countries[] = array("code"=>"ZW","name"=>"Zimbabwe","d_code"=>"+263");

        $phone_code = "";
        foreach($countries as $cntr){
            if(trim($cntr["code"]) <> ""){
                if(trim($cntr["code"]) == trim($code)){
                    $phone_code = trim($cntr["d_code"]);
                }
            }
        }
        if(trim($phone_code) <> ""){
            return $phone_code;
        }else{
            return "+1";
        }
    }

    public static function registerdomain($RegistrantFirstName,$RegistrantLastName,$RegistrantJobTitle,$RegistrantOrganizationName,$RegistrantAddress2,$RegistrantCity,$RegistrantState,$RegistrantProvince,$RegistrantPostalCode,$idRegistrantCountry,$RegistrantFax,$RegistrantPhone,$RegistrantEmailAddress,$idsld,$tld,$NumYears,$RegistrantAddress1,$UnLockRegistrar) {
       set_time_limit(0);
        User::$dbObj            = new Db();
        $messageArray           = array();
        $domain_registrar       = User::$dbObj->selectRow("Settings","value","settingfield='domain_registrar'");
        $ns1                    = User::$dbObj->selectRow("Settings","value","settingfield='name_server_1'");
        $ns2                    = User::$dbObj->selectRow("Settings","value","settingfield='name_server_2'");
        $ns3                    = User::$dbObj->selectRow("Settings","value","settingfield='name_server_3'");
        $ns4                    = User::$dbObj->selectRow("Settings","value","settingfield='name_server_4'");

        if($domain_registrar == "ENOM") {
            PageContext::includePath('enom');
            // Create URL Interface class
            $username    =   User::$dbObj->selectRow("Settings","value","settingfield='enom_user'");
            $password    =   User::$dbObj->selectRow("Settings","value","settingfield='enom_password'");
            $password = User::decrytCreditCardDetails($password);
            $enduserip   =   User::$dbObj->selectRow("Settings","value","settingfield='enom_uiseripd'");
            $enable_hosting =   User::$dbObj->selectRow("Settings","value","settingfield='enable_hosting'");

            $enommode       =  User::$dbObj->selectRow("Settings","value","settingfield='enom_testmode'");

            $Enom 			= new Enominterface();
            // Set account username and password
            $Enom->AddParam( "uid", $username );
            $Enom->AddParam( "pw", $password );
            // Set domain name
            //$Enom->AddParam( "tld", $tld );
            //$Enom->AddParam( "sld", $rowtmp['vsld'] );
            $Enom->AddParam( "enduserip", $enduserip );
            $Enom->AddParam( "site", "Enomitron-php" );

            // Set number of years to register
            if ( $NumYears != "" ) {
                $Enom->AddParam( "RegisterYears", $NumYears );
            }
            // Do they want to use default (eNom) nameservers?

            if ($enable_hosting == "Y"){
                $Enom->AddParam( "UseDNS", "custom");
                $Enom->AddParam( "NS1",$ns1);
                $Enom->AddParam( "NS2",$ns2);
            }else{
                // Yes, use default eNom nameservers
                $Enom->AddParam( "UseDNS", "default");
            }

            $Enom->AddParam("RegistrantEmailAddress", $RegistrantEmailAddress );
            $Enom->AddParam("RegistrantFax", $RegistrantFax );
            $Enom->AddParam("RegistrantPhone", $RegistrantPhone );
            $Enom->AddParam("RegistrantCountry", $idRegistrantCountry );
            $Enom->AddParam("RegistrantPostalCode", $RegistrantPostalCode );

            if ( $rowtmp[ "vreg_stateprovinceChoice" ] == "S" ) {
                $Enom->AddParam( "RegistrantStateProvinceChoice", "S" );
                $Enom->AddParam( "RegistrantStateProvince", $rowtmp["vreg_state"] );
                $state		= $RegistrantState;
                $province	= "NA";
            } else if ( $rowtmp[ "vreg_stateprovinceChoice" ] == "P" ) {
                $Enom->AddParam( "RegistrantStateProvinceChoice", "Province" );
                $Enom->AddParam( "RegistrantStateProvince", $rowtmp["vreg_province"] );
            } else {
                $Enom->AddParam( "RegistrantStateProvinceChoice", "Blank" );
                $Enom->AddParam( "RegistrantStateProvince", "" );
            }

            $Enom->AddParam( "RegistrantCity", $RegistrantCity );
            $Enom->AddParam( "RegistrantAddress2", $RegistrantAddress2 );
            $Enom->AddParam( "RegistrantAddress1", $RegistrantAddress1 );
            $Enom->AddParam( "RegistrantLastName", $RegistrantLastName );
            $Enom->AddParam( "RegistrantFirstName", $RegistrantFirstName );
            $Enom->AddParam( "RegistrantJobTitle", $RegistrantJobTitle );
            $Enom->AddParam( "RegistrantOrganizationName", $RegistrantOrganizationName );



            //dot name info if sent
            if ($tld== "name") {
                $idforwardOptionYes = $rowtmp[ "vidforwardOptionyes" ];
                $forwardmailto 		= $rowtmp[ "vforwardmailto" ];
                $everything 		= "everything";
                if ($idforwardOptionYes == "yes") {
                    $Enom->AddParam( "ForwardName", $everything );
                    $Enom->AddParam( "ForwardMailTo", $forwardmailto );
                }
            }

            //Now set the password
            //$Enom->AddParam( "DomainPassword", $rowtmp[ "vdomain_password" ] );
            //Set Lock and Renewal

            $Registarlockflag	= "FAL";
            if ($UnLockRegistrar == "ON") {
                $Enom->AddParam( "UnLockRegistrar", "0" );
                $Registarlockflag	= "TR";
            } else {
                $Enom->AddParam( "UnLockRegistrar", "1" );
            }

            $ctype				= "R";
            $currenttransaction	= "Register";
            $invoicedescription	= "Domain registration request at ".$idsld.".";

            $Enom->AddParam("command", "purchase" );
            $cuurent_poststring_Without_sldandtld	= $Enom->Current_poststring();
            $registerd_success_domains	= array();
            $registerd_failure_domains	= array();

            $sld		= $idsld;
            $tld		= "$tld";
            $Enom->AddParam1($cuurent_poststring_Without_sldandtld, "tld", $tld, "sld", $idsld);


//		echo $Enom->Current_poststring(); echo "<br>";
            $Enom->DoTransaction($enommode);
             //echopre($Enom);
            //print_r($Enom->Values); exit();
            $cPW1	= "";
            $_SESSION['sess_currentdomainid']	= $rowdomains['ndomainid'];
            $_SESSION['current_useraccountid']	= $rowdomains['naccount_id'];
            if ( $Enom->Values[ "ErrCount" ] != "0" ) {
                // Yes, get the first one
                $cErrorMsg  = $Enom->Values[ "Err1" ];
                $cErrorMsg .= ",".$Enom->Values[ "Err2" ];

                // Flag an error
                $bError = 1;
                $messageArray['status']    = 0;
                $messageArray['message']   = "Account Registration Failed";
//					include_once "registerfail.php";

            }else{
                // No interface errors
                $bError = 0;
                // Check code from NSI (200 = name registered)
                switch ($Enom->Values[ "RRPCode"]){
                    case "200":
                    // The name was registered
                        $bRegistered = 1;

                        $messageArray['status']    = 1;
                        $messageArray['message']   = "Account Registred Successfully";
                        return $messageArray;
                        break;
                    case "554":
                    // The name is not available (already registered by eNom)
                        $bRegistered = 0;
                        $messageArray['status']    = 0;
                        $messageArray['message']   = "The name is not available (already registered by eNom)";
//							include_once "registerfail.php";
                        break;

                    case "540":
                    // The name is not available (already registered by another registarar)
                        $bRegistered = 0;
//							include_once "registerfail.php";
                        $messageArray['status']    = 0;
                        $messageArray['message']   = "The name is not available (already registered by another registarar)";
                        break;

                    case "1300":
                    // The UK Domain name was successfully submitted to the registry
                        $bRegistered = 1;
                        $messageArray['status']    = 0;
                        $messageArray['message']   = "The UK Domain name was successfully submitted to the registry";
//							include_once "success.php";
                        break;

                    default:
                    // There was an error from NSI
                        $bError 	= 1;
                        $cErrorMsg 	= $Enom->Values[ "RRPText" ];
                        $messageArray['status']    = 0;
                        $messageArray['message']   = "There was an error from NSI";
//							include_once "registerfail.php";
                        break;

                }

                //echopre($messageArray);exit;
                return $messageArray;
            }
        }
        elseif($domain_registrar == "GODADDY"){
            // Create an instance of the url interface class
            //PageContext::includePath('godaddy');
            //$registerDomain = new goDaddy();

            //Domain Array
            $domainArray        = $domainDataArr = array();
            $domainDataArr[0]   = array("sld" => $idsld, "tld" => $tld, "years" => $NumYears);
            $domainArray        = User::getDomainForGodaddy($domainDataArr);

            //Name Server Array
            $NSArr = array($ns1, $ns2);
            /* $op = $registerDomain->registerdomain($RegistrantEmailAddress, $RegistrantFirstName, $RegistrantLastName, $RegistrantPhone, $RegistrantAddress1, $RegistrantCity, $RegistrantState, $RegistrantPostalCode, $idRegistrantCountry, $domainArray, $NSArr, $overridePass = '', $isCertification = false); */

            /*************************** Domain Registration CURL starts Here *********************/
            $tld = trim($tld);            
            $arrDomRegnDetails  = array();
            $arrDomRegnDetails["domain"]   = $domainArray[0]["domain"];
            $arrDomRegnDetails["consent"]  = array(
                    "agreementKeys" => array("DNRA"),
                    "agreedBy"      => "",
                    "agreedAt"      => ""
                );
            if(strtolower($tld) == "us"){ //If .US selected
                $arrDomRegnDetails["period"]       = (int)$NumYears;
                $arrDomRegnDetails["nameServers"]  = $NSArr;   //array("ns1.iscriptscloud.com");
                $arrDomRegnDetails["renewAuto"]    = true;
                $arrDomRegnDetails["citizenship"]  = "US";
                $arrDomRegnDetails["entityType"]   = "INCORPORATED";
                $arrDomRegnDetails["intent"]       = "BUSINESS_NON_PROFIT";               
            }else{
                $arrDomRegnDetails["period"]       = (int)$NumYears;
                $arrDomRegnDetails["nameServers"]  = $NSArr;   //array("ns1.iscriptscloud.com");
                $arrDomRegnDetails["renewAuto"]    = true;
                $arrDomRegnDetails["privacy"]      = false;
            }  

            $RegistrantCountryCode = "";
            $RegistrantCountryCode = User::get_country_phonecode(trim($idRegistrantCountry));
            if(trim($RegistrantCountryCode) == ""){
                $RegistrantCountryCode = '+1';
            }
            if(trim($idRegistrantCountry) <> "US"){
                $RegistrantState = $RegistrantCity;
            }
            if(trim($RegistrantFax) <> ""){
                $RegistrantFax = $RegistrantCountryCode.".".$RegistrantFax;
            }

            $arrRegistrantDetails = array();
            $arrRegistrantDetails = array(
                "nameFirst"     => $RegistrantFirstName,
                "nameMiddle"    => "",
                "nameLast"      => $RegistrantLastName,
                "organization"  => $RegistrantOrganizationName,
                "jobTitle"      => $RegistrantJobTitle,
                "email"         => $RegistrantEmailAddress,
                "phone"         => $RegistrantCountryCode.".".$RegistrantPhone,
                "fax"           => $RegistrantFax,
                "addressMailing"    => array(
                  "address1"    => $RegistrantAddress1,
                  "address2"    => $RegistrantAddress2,
                  "city"        => $RegistrantCity,
                  "state"       => $RegistrantState,
                  "postalCode"  => $RegistrantPostalCode,
                  "country"     => $idRegistrantCountry
                )
            );
            $arrDomRegnDetails["contactRegistrant"]     = $arrRegistrantDetails;
            $arrDomRegnDetails["contactAdmin"]          = $arrRegistrantDetails;
            $arrDomRegnDetails["contactTech"]           = $arrRegistrantDetails;
            $arrDomRegnDetails["contactBilling"]        = $arrRegistrantDetails;

            $userId                 = LibSession::get('userID');
            $userDetails            = User::$dbObj->selectRecord("User", "*", "nUId=".intval($userId));
            //echopre($userDetails);

            $godaddy_testmode       = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_testmode'");
            $apiUrl                 = $godaddy_testmode == 'Y' ?  "https://api.ote-godaddy.com" : "https://api.godaddy.com";

            $myKey                  = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_id'");
            $mySecret               = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_password'");
            $myShopperId            = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_shopper_id'");
            $nShopperId             = $userDetails->nShopperId;
            
            $arrShopperHeaders      = array('Authorization: sso-key '.$myKey.':'.$mySecret,'Content-Type: application/json','Accept: application/json');
            /*********************** Validate Shopper ID ********************/
            $RegistrantPwd          = ucfirst(Utils::randomPassword());
            if(trim($nShopperId) == ""){ 
                $arrShopperDetails = array(
                    "email"         => $RegistrantEmailAddress,
                    "externalId"    => 0,
                    "marketId"      => "en-US",
                    "nameFirst"     => $RegistrantFirstName,
                    "nameLast"      => $RegistrantLastName,
                    "password"      => $RegistrantPwd
                );
                $data_json              = json_encode($arrShopperDetails);
                $url                    = $apiUrl."/v1/shoppers/subaccount";
                $ch                     = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);            
                curl_setopt($ch, CURLOPT_HTTPHEADER, $arrShopperHeaders);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $resp                   = curl_exec($ch);
                curl_close($ch);
                $arrJsonResponse        = array();            
                $arrJsonResponse        = json_decode($resp,1);
                //echo "<br/>Shopper Create API = <pre>"; print_r($arrJsonResponse); echo "</pre>";

                if(is_array($arrJsonResponse) && count($arrJsonResponse)>0){
                    if(!empty($arrJsonResponse["shopperId"])){
                        $nShopperId     = trim($arrJsonResponse["shopperId"]);
                        $updateArray["nShopperId"] = trim($arrJsonResponse["shopperId"]);
                        $updateStatus   = User::$dbObj->updateFields("User",$updateArray," nUId='".trim($userId)."'");
                    }
                }
            }
            if(trim($nShopperId) == ""){ 
                $nShopperId = trim($myShopperId);
            }   
            //echo "Shopper ID = ".$nShopperId;
            /*********************** Validate Shopper ID ********************/
            
            //$godaddy_password     = User::decrytCreditCardDetails($godaddy_pwd);            
            $data_json              = json_encode($arrDomRegnDetails);
            //echo "<pre>"; print_r($arrDomRegnDetails); echo "</pre>";
            //echo "JSON Data = ".$data_json;  
            
            $arrHeaders = array('Authorization: sso-key '.$myKey.':'.$mySecret,'Content-Type: application/json','X-Shopper-Id: '.$nShopperId);
            //echo "<br/>Headers array";
            //echopre($arrHeaders);

            $url                    = $apiUrl."/v1/domains/purchase";
            $ch                     = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);            
            curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $resp                   = curl_exec($ch);
            curl_close($ch);
            /*$resp = '{
                      "currency": "USD",
                      "itemCount": 1,
                      "orderId": 1721969,
                      "total": 23010000
                    }'; */
            $arrJsonKeys            = array();
            $arrJsonResponse        = array();  
            $arrJsonResponse        = json_decode($resp,1);
            //echo "Domain Purchase API = <pre>"; print_r($arrJsonResponse); echo "</pre>"; die();

            if(is_array($arrJsonResponse) && count($arrJsonResponse)> 0){
                foreach($arrJsonResponse as $jSonKey => $jSonValue){
                    $arrJsonKeys[] = $jSonKey;
                }
            }
            //echo "<pre>"; print_r($arrJsonKeys); echo "</pre>";
            if(is_array($arrJsonKeys) && count($arrJsonKeys)> 0){
                if(in_array("orderId", $arrJsonKeys)){
                   $success = 1;
                }else{
                   if(in_array("code", $arrJsonKeys)){
                       if(trim($arrJsonResponse["code"]) == "INVALID_BODY"){
                          $arrMsgFields = array();
                          $arrMsgFields = $arrJsonResponse["fields"];
                          $error_msg = "";

                          if(is_array($arrMsgFields) && count($arrMsgFields)>0){
                              foreach($arrMsgFields as $errorMsg){
                                  if(trim($errorMsg["code"]) <> ""){
                                      switch(trim($errorMsg["code"])){
                                            case "MISMATCH_FORMAT":
                                                if(trim($errorMsg["path"]) == "body.contactAdmin.phone"){
                                                    $error_msg .= "Invalid phone number<br/>";
                                                }
                                                else if(trim($errorMsg["path"]) == "body.contactAdmin.fax"){
                                                    $error_msg .= "Invalid fax number<br/>";
                                                }
                                                break;
                                            case "INVALID_STATE":
                                                if(trim($errorMsg["path"]) == "contactAdmin.addressMailing.state"){
                                                    $error_msg .= "You must include a valid state for the selected country<br/>";
                                                }
                                                break;
                                            case "LENGTH_OVER":
                                                if(trim($errorMsg["path"]) == "body.contactAdmin.addressMailing.address1"){
                                                    $error_msg .= "Address1 field characters should not exceed 40 characters<br/>";
                                                }
                                                else if(trim($errorMsg["path"]) == "body.contactAdmin.addressMailing.address2"){
                                                    $error_msg .= "Address2 field characters should not exceed 40 characters<br/>";
                                                }
                                                break;
                                            case "LENGTH_UNDER":
                                                if(trim($errorMsg["path"]) == "body.contactAdmin.addressMailing.state"){
                                                    $error_msg .= "The state field does not meet minimum length of 2<br/>";
                                                }
                                                break;

                                      }
                                  }
                              }
                          }
                       }
                   }
                   $success = 0;
                }
                if($success == 0){
                    $message = $error_msg;
                }else{
                    $message ="Account Registered Successfully";
                }
            }
            //echo "success=".$success;
            /*************************** Domain Registration CURL Ends Here *********************/

            $messageArray['status']  = ($success) ? 1 : 0;
            $messageArray['message'] = $message;
            //echo "<pre>"; print_r($messageArray); echo "</pre>"; die();

            return $messageArray;
        }
    }

     public static function getDomainForGodaddy($dataArr){
        /***
         * Sample Input Array
         * $dataArr[0] = array("sld" => "example", "tld" => "com", "years" => "1");
         */
        $domainArr = array();
        if(!empty($dataArr)){
            foreach($dataArr as $item){
                // Domain Name
                $domain = $productID = NULL;
                $domain = $item["sld"].".".$item["tld"];
                // Product ID
                $productID = User::getDomainProductIdForGodaddy(array("tld" => $item["tld"],"years" => $item["years"]));

                $domainArr[] = array("domain" => $domain,
                                    "duration" => $item["years"],
                                    "prd_id" => $productID);
            } // End Foreach
        }
        return $domainArr;
    } // End Function

    public static function getDomainProductIdForGodaddy($dataArr, $type = 'Registration'){
        $listData = NULL;
        if(!empty($dataArr)){
            User::$dbObj     = new Db();
            $resData = User::$dbObj->execute("SELECT vProductid FROM " . User::$dbObj->tablePrefix . "tld_godaddy WHERE nDuration='".$dataArr["years"]."' AND LOWER(vTld)='".strtolower($dataArr["tld"])."' AND eType = '".$type."'");
            $listData = User::$dbObj->fetchOne($resData);

        }
        return $listData;
    }


    //Code to create analytics.js from google analytics code stored in db
    public static function googleAnalytics() {
        $db = new Db();
        $analytics = stripslashes($db->selectRow("Settings", "value","settingfield='googleAnalytics'"));
        if($analytics) {
            $file = BASE_PATH. "/project/js/analytics.js";
            file_put_contents($file,"$analytics");
            PageContext::addScript('analytics.js');
        }
    }

    public static function loadStaticContent($cmsName) {
        User::$dbObj          = new Db();
        $content = User::$dbObj->selectRecord("Cms","cms_title,cms_desc,cms_status","cms_name = '".$cmsName."' AND cms_status = '1'");
        return $content;
    }



    //Forogot Password Functionality
    public static function handleForgotPassword($view,$current) {

        $view->email 	= addslashes(trim($current->post('txtEmail')));

        if ($view->email == "") {
            PageContext::$response->error_message = FORGOT_PASSWORD_EMAIL_ID_MISSING;
            $view->messagefunction = 'errormessage';
            Logger::info('Email id is null');
        }else {
            if(Utils::is_valid_email($view->email)) {
                User::$dbObj          = new Db();
                $userId = User::$dbObj->checkExists("User", "*", "vEmail='".$view->email."'");

                if(!empty($userId)) {
                    $passwordStatus = User::$dbObj->checkExists("User", "*", "vEmail='".$view->email."' AND vPassword!=''");
                }

                if(!empty($userId) && !empty($passwordStatus)) {
                    $activationkey = self::generateResetPasswordActivationKey();
                    $updateArray["vActivationKey"]= $activationkey;
                    $updateStatus = User::$dbObj->updateFields("User",$updateArray," vEmail='".$view->email."'");

                    $table          = "User";
                    $field          = "vUsername";
                    $where          = "vEmail='".$view->email."'";
                    $userDetail = User::$dbObj->selectRecord($table,$field,$where);

                    $resetPasswordLink = "Click <a href='".ConfigUrl::base()."index/resetpassword/".$activationkey."'> Here</a> to Reset your password";
                    $userArray =   array(
                            "user_name" => trim($userDetail->vUsername),
                            "email" => trim($view->email),
                            "passwordLink" => $resetPasswordLink
                    );


                    User::sendUserMail($userArray);
                    //TODO :Email reset password link to the user
                    Logger::info($resetPasswordLink);

                    if($updateStatus) {
                        PageContext::$response->success_message = FORGOT_PASSWORD_SUCCESS_MESSAGE;
                        $view->messagefunction = 'successmessage';
                        unset($_POST);

                    }else {
                        PageContext::$response->error_message = FOROGOT_PASSWORD_ERROR_MESSAGE;
                        $view->messagefunction = 'errormessage';
                    }
                }else if(empty($userId)) {
                    PageContext::$response->error_message = FORGOT_PASSWORD_EMAIL_NOT_REGISTERED;
                    $view->messagefunction = 'errormessage';

                }
            }else {
                Logger::info('Invalid email id');
                PageContext::$response->error_message = FORGOT_PASSWORD_INVALID_EMAIL_ID;
                $view->messagefunction = 'errormessage';
            }
        }

    }

    //Generates reset password activation key
    private static function generateResetPasswordActivationKey() {
        Logger::info("Generating reset password activation key :");
        $activationkey  =   're'.uniqid();
        return $activationkey;
    }

    //Reset Password Functionality
    public static function handleResetPassword($view, $current, $activationKey) {

        $view->activationKey = $activationKey?$activationKey:$current->post('key');

        if(!empty($view->activationKey)) {
            User::$dbObj          = new Db();
            $status = User::$dbObj->checkExists("User", "nUId", "vActivationKey='".$view->activationKey."'");
        }

        if(!empty($view->activationKey) && $status) {

            $view->displayResetForm = TRUE;

            if($current->isPost()) {
                $view->password = $current->post('password');
                $view->confpassword = $current->post('confirm_password');

                if($view->password == $view->confpassword) {

                    $updateArray['vPassword'] = md5($view->password);
                    $updateArray["vActivationKey"] = '';
                    $updationStatus = User::$dbObj->updateFields("User",$updateArray,"vActivationKey='".$view->activationKey."'");

                    if($updationStatus) {
                        PageContext::$response->success_message = RESET_PASSWORD_SUCCESS_MESSAGE;
                        $view->messagefunction = 'successmessage';
                        unset($_POST);
                    }else {
                        PageContext::$response->error_message = RESET_PASSWORD_ERROR_MESSAGE;
                        $view->messagefunction = 'errormessage';
                    }

                    Logger::info($view->message);
                }else {
                    PageContext::$response->error_message = RESET_PASSWORDS_NOT_MATCHING_MESSAGE;
                    $view->messagefunction = 'errormessage';
                }

            }

        }else {
            PageContext::$response->error_message = INVALID_RESET_PASSWORD_REQUEST;
            $view->messagefunction = 'errormessage';
            $view->displayResetForm = FALSE;
            Logger::info('Could not find matching details');
        }
    }


    public static function getproductName($productId) {
        User::$dbObj     = new Db();
        $productName     = User::$dbObj->selectRow("Products","vPName","nPId='$productId'");
        return $productName;
    }

    public static function getproductPackName($productId) {
        User::$dbObj     = new Db();
        $productPackName     = User::$dbObj->selectRow("Products","vProductPack","nPId='$productId'");
        return $productPackName;
    }

    public static function getproductPermission($productId) {
        User::$dbObj            = new Db();
        $productPermissions     = User::$dbObj->selectRow("productpermission","vPermissions","nPId='$productId'");
        return $productPermissions;
    }

    public static function getproductReleaseID($productId) {
        User::$dbObj            = new Db();
        $productreleaseId       = User::$dbObj->selectRow("Products","nPRId","nPId='$productId'");
        return $productreleaseId;
    }

        public static function getPlanproductRestriction($planId) {
        User::$dbObj            = new Db();
        $productrestrictionId       = User::$dbObj->selectRow("ProductServices","nQty","nServiceId='$planId'");
        return $productrestrictionId;
    }


     public static function setXmlData($productRestriction=0,$keyVal="") {
         if($productRestriction==0)
         {
        $xmldata    = '<?xml version="1.0" encoding="UTF-8"?>
<Configuration>
<pno>5000</pno>
<secretkey>'.$keyVal.'</secretkey>
</Configuration>';
         }
         else
         {
             $xmldata    = '<?xml version="1.0" encoding="UTF-8"?>
<Configuration>
<pno>'.$productRestriction.'</pno>
<secretkey>'.$keyVal.'</secretkey>
</Configuration>';
         }
//         $configFileName    = IMAGE_FILE_URL.CONFIG_FILE_NAME;
//         if(is_file($configFileName))
//         {
//             $fp=fopen($configFileName,"w");
//             fwrite($fp, $xmldata, strlen($xmldata));
//             fclose($fp);
//         }
//         else
//         {
//             $fp=fopen($configFileName,"w");
//             fwrite($fp, $xmldata, strlen($xmldata));
//             fclose($fp);
//         }
        return $xmldata;
    }


    //Logged in user profile fetching functionality
    public static function fetchUserProfile() {
        $db = new Db();
        $userId = LibSession::get('userID');
        $userDetails = $db->selectRecord("User", "*", "nUId=".intval($userId));
        Logger::info($userDetails);
        return $userDetails;

    }

    //Logged in user credit card fetching functionality
    public  static function fetchUserCreditCardDetails() {
        $db = new Db();
        $sessionObj = new LibSession();
        $userId = $sessionObj->get('userID');
        $cardDetails = $db->selectRecord("general", "*", " nUserId=".intval($userId));

        if(!empty($cardDetails)) {
            foreach ($cardDetails as $key=>$value) {
                $cardDetails->$key = self::decrytCreditCardDetails($value);
            }
        }
        Logger::info($cardDetails);
        return $cardDetails;
    }

    //Logged in user profile updating functionality
    public static function updateUserProfile($postedArray) {
        $db = new Db();

        foreach ($postedArray as $key=>$value) {
            $userArray[$key] = $value;
        }
        unset($userArray['btnProfile']);
        $status = TRUE;
        Logger::info($userArray);
        $userId = LibSession::get('userID');
        $checkEmail = $db->checkExists("User", "vEmail", " vEmail='".$userArray["vEmail"]."' AND nUId!=".$userId);

        if($checkEmail) {
            PageContext::$response->error_message = "Email already exists for another account!";
            PageContext::addPostAction('errormessage');
            $status = FALSE;
            Logger::info("Email exists");
        }

        if($status) {
            $db->updateFields("User", $userArray, "nUId=".$userId);
            PageContext::$response->success_message = "Successfully updated details!";
            PageContext::addPostAction('successmessage');
            $status = TRUE;
        }

        return $status;

    }

    //Logged in user password updating functionality
    public static function updateUserPassword($postedArray) {
        $db = new Db();
        $currentPassword = addslashes($postedArray["currentpassword"]);
        $status = TRUE;
        $userId = LibSession::get('userID');
        $checkCurrentPassword  = $db->checkExists("User", "vEmail", " vPassword='".md5($currentPassword)."' AND nUId=".$userId);

        if(!$checkCurrentPassword) {
            PageContext::$response->error_message = "Invalid Current Password!";
            PageContext::addPostAction('errormessage');
            $status = FALSE;
            Logger::info("Email exists");
        }

        if($status) {
            $newPassword = $postedArray["password"];
            $confNewPassword = $postedArray["confirm_password"];
            if($newPassword!=$confNewPassword) {
                PageContext::$response->error_message = "Passwords do not match!";
                PageContext::addPostAction('errormessage');
                $status = FALSE;
            }
        }

        if($status) {
            $userArray["vPassword"] = md5(addslashes($newPassword));
            $user_arr = User::$dbObj->selectRecord("User","*","nUId='$userId'");

            //update password in supportdesk
            mysqli_query($db,"UPDATE sptbl_users SET vPassword = '".md5($userArray["vPassword"])."' WHERE vEmail = '".$user_arr->vEmail."'");

            $db->updateFields("User", $userArray, "nUId=".$userId);
            PageContext::$response->success_message = "Successfully updated details!";
            PageContext::addPostAction('successmessage');
            $status = TRUE;
        }

        return $status;

    }

    //Logged in user credit card updating functionality
    public static function updateUserCreditCardDetails($postedArray) {

        $db = new Db();
        foreach ($postedArray as $key=>$value) {
            $cardArray[$key] = self::encrytCreditCardDetails($value);
        }


        $string = $cardArray["vNumber"];
        //$cardArray["vNumber"] = self::encrytCreditCardDetails($cardArray["vNumber"]);
        // $cardArray["vCode"] = self::encrytCreditCardDetails($cardArray["vCode"]);

        unset($cardArray['btnProfile']);
        Logger::info($cardArray);
        $userId = LibSession::get('userID');

        $status = TRUE;
        $cardArray['vUserIp'] = $_SERVER['REMOTE_ADDR'];
        if($postedArray['vMonth']>12) {
            PageContext::$response->error_message = "Invalid Month.Please Change!";
            PageContext::addPostAction('errormessage');
            $status = FALSE;
        }

        if($status) {
            $checkCurrentData  = $db->checkExists("general", "nGId", " nUserId=".$userId);

            if($checkCurrentData) {
                $db->updateFields("general", $cardArray, " nUserId=".$userId);
            }else {
                $cardArray['nUserId'] = $userId;
                $db->addFields("general", $cardArray);
            }

            PageContext::$response->success_message = "Successfully updated details!";
            PageContext::addPostAction('successmessage');
            $status = TRUE;

        }

        return $status;
    }

    public static function encrytCreditCardDetails($string) {
        //return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(USER_CREDIT_CARD_ENCRYPT_KEY), $string, MCRYPT_MODE_CBC, md5(md5(USER_CREDIT_CARD_ENCRYPT_KEY))));
        return base64_encode(SECRET_SALT."#".$string);
    }

    public static function decrytCreditCardDetails($string) {
        //return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(USER_CREDIT_CARD_ENCRYPT_KEY), base64_decode($string), MCRYPT_MODE_CBC, md5(md5(USER_CREDIT_CARD_ENCRYPT_KEY))), "\0");
        return str_replace(SECRET_SALT."#", "", base64_decode($string));
    }

    public static function getproductPrice($productId,$purchaseCategoryId) {
        User::$dbObj     = new Db();
        $productPrice     = User::$dbObj->selectRow("ProductServices","price","nPId='$productId' AND nSCatId='$purchaseCategoryId'");
        return $productPrice;
    }

    public static function getPurchaseCategory($productId,$purchaseCategoryId,$freePlanId) {
        $productCategory =      "SELECT PS.vServiceName, PS.vServiceDescription, SC.vCategory, SC.vInputType, PS.price,SC.vDescription as SEDESC,PS.nServiceId, PS.vBillingInterval
                                  FROM " . User::$dbObj->tablePrefix . "Products PRD
                            INNER JOIN " . User::$dbObj->tablePrefix . "ProductServices PS ON PS.nPId = PRD.nPId
                            INNER JOIN " . User::$dbObj->tablePrefix . "ServiceCategories SC ON SC.nSCatId = PS.nSCatId
                                 WHERE PRD.nPId='" . $productId . "'
                                   AND PS.nSCatId <>'$purchaseCategoryId'
                                   AND PS.nSCatId <>'$freePlanId'
                              ORDER By PS.nSCatId";
        $productCategory = User::$dbObj->selectQuery($productCategory);
        return $productCategory;
    }

    public static function getProductServices($productId,$filterArr = NULL) {
        $filter = NULL;
        $dataArr = array();
        User::$dbObj = new Db();
        $sel = "SELECT PS.vServiceName, PS.vServiceDescription, PS.nSCatId, SC.vCategory, PS.price,SC.vDescription as SEDESC,PS.nServiceId
                                  FROM " . User::$dbObj->tablePrefix . "Products PRD
                            INNER JOIN " . User::$dbObj->tablePrefix . "ProductServices PS ON PS.nPId = PRD.nPId
                            INNER JOIN " . User::$dbObj->tablePrefix . "ServiceCategories SC ON SC.nSCatId = PS.nSCatId
                                 WHERE PRD.nPId='" . $productId . "'";

        // FILTER fields for where condition generation like a='xx' AND b='xx' AND c='xx'
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filterCondition = (isset($filterItem['condition'])) ? $filterItem['condition'] : '=';
                $filter .= (!empty($filter)) ? ' AND ' : '';
                $filter .= (!empty($filterItem)) ? $filterItem['field']." ".$filterCondition." '".$filterItem['value']."'" : NULL;
            } // End Foreach
        } // End If

        $sel .= (!empty($filter)) ? ' AND ' : '';
        $sel .=$filter;
        $sel .= " ORDER By PS.nSCatId ASC";

        $dataArr = User::$dbObj->selectQuery($sel);
        return $dataArr;
    }

    public static function getProductServicesId($productId, $filterArr=NULL) {
        // Product Service Id
        $productServiceId = NULL;
        $dataArr = User::getProductServices($productId, $filterArr);
        if(!empty($dataArr)) {
            $productServiceId = $dataArr[0]->nServiceId;
        } // End If
        return $productServiceId;
    } // End Function

    public static function mergeProductServicesId($serArr1, $serArr2) {
        $dataArr = array_merge($serArr1, $serArr2);
        return $dataArr;
    } // End Function

    public static function gettldprice($tld) {
        User::$dbObj = new Db();
        $tldprice    = User::$dbObj->selectRow("Settings","value","settingfield='priceDomiainRegistration'"); //modified
        return $tldprice;
    }
    
    
    public static function getallSettings($tld) {
        User::$dbObj = new Db();
        $tldprice    = User::$dbObj->selectRow("Settings","value","settingfield='$tld'"); //modified
        return $tldprice;
    }

    public static function productsetUpId($scId,$productId) {
        User::$dbObj              = new Db();
        $productserviceId         = User::$dbObj->selectRow("ProductServices","nServiceId","nSCatId='$scId' AND nPId='$productId'");
        return $productserviceId;
    }

    //Functionality to fetch free trials
    public static function fetchFreeTrialsOfLoggedInUser() {
        $db= new Db();
        $userId = LibSession::get('userID');

        $result = $db->selectResult("Invoice I INNER JOIN ".$db->tablePrefix."InvoicePlan  IP ON(I.nInvId= IP.nInvId)
            INNER JOIN  ".$db->tablePrefix."ProductServices PS ON (IP.nServiceId= PS.nServiceId)
            INNER JOIN  ".$db->tablePrefix."ProductLookup  PL ON (PL.nPLId=I.nPLId)",
                "PS.vBillingInterval,PS.nBillingDuration,PL.vSubDomain,I.dGeneratedDate,PL.nPLId,PL.nStatus",
                "I.vSubscriptionType = 'FREE' AND I.nUId=".$userId." AND I.upgraded<>1 GROUP BY PL.nPLId ORDER BY I.nInvId DESC");

        Logger::info($result);
        return $result;

    }
    //Functionality to fetch subscriptions
    public static function fetchSubscriptionsOfLoggedInUser() {
        $db= new Db();
        $userId = LibSession::get('userID');

        $result = $db->selectResult("Invoice I INNER JOIN ".$db->tablePrefix."InvoicePlan  IP ON(I.nInvId= IP.nInvId)
            INNER JOIN  ".$db->tablePrefix."ProductServices PS ON (IP.nServiceId= PS.nServiceId)
            INNER JOIN  ".$db->tablePrefix."BillingMain BM ON (BM.vInvNo= I.vInvNo)
            INNER JOIN  ".$db->tablePrefix."ProductLookup  PL ON (PL.nPLId=I.nPLId)",
                "PS.vBillingInterval,PS.nBillingDuration,PL.vSubDomain,I.dGeneratedDate,PL.nPLId,BM.dDateStop,PL.inventory_source_status",
                "I.vSubscriptionType = 'PAID' AND I.nUId=".$userId." AND PL.nStatus='1' GROUP BY PL.nPLId ORDER BY I.nInvId DESC");
//echopre($result);
        Logger::info($result);
        return $result;

    }

    public static function checkAccount($email) {
        User::$dbObj = new Db();
        $condition      = "vEmail='$email' ";
        $entryExists    =  (integer)User::$dbObj->checkExists('User',"nUId",$condition);
        $statusFlag     =  ($entryExists>0) ? true : false;
        return $statusFlag;
    }

    public static function getPurchaseCategoryIdForInvoice($productId,$purchaseCategoryId,$freePlanId) {
        $productCategory =      "SELECT PS.vServiceName, PS.vServiceDescription, SC.vCategory, PS.price,SC.vDescription as SEDESC,PS.nServiceId
                                  FROM " . User::$dbObj->tablePrefix . "Products PRD
                            INNER JOIN " . User::$dbObj->tablePrefix . "ProductServices PS ON PS.nPId = PRD.nPId
                            INNER JOIN " . User::$dbObj->tablePrefix . "ServiceCategories SC ON SC.nSCatId = PS.nSCatId
                                 WHERE PRD.nPId='" . $productId . "'
                                   AND PS.nSCatId ='$purchaseCategoryId'
                              ORDER By PS.nSCatId";
        $productCategory = User::$dbObj->selectQuery($productCategory);
        return $productCategory;
    }

    public static function updateLastLogin() {
        User::$dbObj = new Db();
        $userId = $_SESSION['adminUser']['userID'];
        $query = "UPDATE ".User::$dbObj->tablePrefix."Admin SET dLastLogin=NOW() WHERE nAId =".$userId;
        User::$dbObj ->customQuery($query);
    }

    public static function getProductId($plId) {
        User::$dbObj     = new Db();
        $productId        = User::$dbObj->selectRow("ProductLookup","nPId","nPLId='$plId'");
        return $productId;
    }
     public static function getProductDetails($productLookUpId) {
        User::$dbObj     = new Db();
        $productId        = User::$dbObj->selectRecord("ProductLookup","*","nPLId='$productLookUpId'");
       
        return $productId;
    }
    public static function getInventoryPlanDetails($productLookUpId) {
        User::$dbObj     = new Db();
        $productId        = User::$dbObj->selectRecord("inventorysource_plan","*","nPId='$productLookUpId'");
       
        return $productId;
    }
    
    
    public static function getSubDomainName($productLookUpId) {
        User::$dbObj     = new Db();
        $subdomainName        = User::$dbObj->selectRow("ProductLookup","vSubDomain","nPLId='$productLookUpId'");
        return $subdomainName;
    }
    public static function getserverDetails($productLookUpId) {
        User::$dbObj     = new Db();
        $accountDetails        = User::$dbObj->selectRow("ProductLookup","vAccountDetails","nPLId='$productLookUpId'");
        return $accountDetails;
    }

    public static function suspendinvoice($productLookUpId,$userID = NULL) {
        User::$dbObj = new Db();
        $lastBillArray = "SELECT max( BMN.nBmId ) as nBmId
                                  FROM " . User::$dbObj->tablePrefix . "BillingMain BMN
                            INNER JOIN " . User::$dbObj->tablePrefix . "Invoice INV  ON INV.nInvId = BMN.vInvNo
                                 WHERE INV.nPLId='" . $productLookUpId . "' GROUP BY INV.nPLId
                              ORDER By INV.nInvId DESC";
        $lastBillArray = User::$dbObj->selectQuery($lastBillArray);
        $arrUpdate       = array();
        $arrUpdate["vDelStatus"]= 1;
        User::$dbObj->updateFields("BillingMain",$arrUpdate,"nBmId='".$lastBillArray[0]->nBmId."'");

    }

    public static function clearlookupentry($productLookUpId,$userID = NULL) {
        User::$dbObj = new Db();
        $arrUpdate       = array();
        $arrUpdate["nStatus"]= 2;
        User::$dbObj->updateFields("ProductLookup",$arrUpdate,"nPLId='".$productLookUpId."'");
    }

    public static function getUserDetails($userId) {
        User::$dbObj     = new Db();
        $userData        = array();
        $userData        = User::$dbObj->selectRecord('User',"vUsername,vEmail","nUId='".$userId."' AND nStatus='".ACTIVE_STATUS."'");
        return $userData;
    }
    public static function getUserAllDetails($userId) {
        User::$dbObj     = new Db();
        $userData        = array();
        $userData        = User::$dbObj->selectRecord('User',"*","nUId='".$userId."' AND nStatus='".ACTIVE_STATUS."'");
        return $userData;
    }

    public static function checkUserEmail($email){
        User::$dbObj     = new Db();
        $data = User:: $dbObj->checkExists("User","vEmail","vEmail='".addslashes($email)."'");
        return $data;
    }

    private function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds'){
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
                $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
                $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
                $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
                $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set){
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
                $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if(!$add_dashes)
                return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    public static function getUserEmailDetails($emailAddress,$customerId = ''){
        $tableName                      =  'User';
        $db                             =   new db();
        if($customerId)
        $andCase               = ' AND nUId != "'.Utils::escapeString($customerId).'"';

        return $db->selectRecord($tableName,'*','vEmail = "'.Utils::escapeString($emailAddress).'" '.$andCase );
    }

    public static function setUserPasswordOnRegn($userId,$postedArray){
        $db                             = new Db();
        $table                          = 'User';
        $dataArray                      = array();
         //$userId                     = Utils::getAuth('user_id');
        $data1 = $db->selectRecord($table, 'vEmail,vFirstName,vLastName', 'nUId = '.$userId);
        //echopre($data1);
        //exit;

        $email = "";
        $vUsername  = "";
        if(trim($data1->vEmail) <> ""){
            $email      = explode('@', $data1->vEmail); //To get only first part of name
            if(trim($email[0]) <> ""){
                $vUsername = trim($email[0]);
            }
        }

        $genPassword    = User::generateStrongPassword(10);
        $vPassword      = md5($genPassword);

        $dataArray['vPassword']     = $vPassword;
        $dataArray['vUsername']     = $vUsername;
        $db->update(MYSQL_TABLE_PREFIX.$table, $dataArray,'nUId = "'.addslashes($userId).'"');

        $mailIds             = array();
        $replaceparams       = array();

        $mailIds[$postedArray['vEmail']] = '';
        $replaceparams['FIRST_NAME']                  = $postedArray['vFirstName'];
        $replaceparams['LAST_NAME']                   = $postedArray['vLastName'];
        $replaceparams['EMAIL']                       = $postedArray['vEmail'];
        $replaceparams['PASSWORD']                    = $postedArray['vPassword'];

        $userArray  = array();
        $userArray  = array(
                        "user_name"     => $postedArray['vFirstName']." ".$postedArray['vLastName'],
                        "user_email"    => $postedArray['vEmail'],
                        "userpassw"     => $genPassword
                    );
        User::sendMail($userArray);

        //$objMailer                                    = new Mailer();
        //$objMailer->sendMail($mailIds, 'admin_user_registration', $replaceparams);

        /* $user = User::getUserEmailDetails($postedArray['vEmail']);
        $message_detail = "Hi ".$postedArray['vFirstName']." ". $postedArray['vLastName'].",<br>
                            You have been registering with  ".SITE_NAME.". <br>
                            Email Id : ".$postedArray['vEmail']." <br>
                            Password : ".$vPassword." <br>
                            You Can Login at ".BASE_URL." <br> <br>
                            Regards, <br>
                            ".SITE_NAME." Team.";

        $message_to_id      = $user->user_id;
        $message_from_id    =  $userId;
        $message_date       = date('Y-m-d', time());
        $message_subject    = 'Welcome to '.SITE_NAME;
        Connections::sendMessages($message_detail,$message_to_id,$message_from_id,$message_date,$message_subject); */
    }

    public static function createUser($userArr){
        $postedArray    	= array("vUsername"=> $userArr['firstName'],
                "vFirstName"	=> $userArr['firstName'],
                "vLastName"     => $userArr['lastName'],
                "vEmail"	=> $userArr['emailAddress'],
                "vInvoiceEmail" => $userArr['emailAddress'],
                "nStatus"       => 1,
                "vPassword"	=> md5($userArr['password']));


            $status = User::$dbObj->addFields("User",$postedArray);

            // Send Mail Notification
            $userArray = array("user_name" => $userArr['firstName'],
                "user_email" => $userArr['emailAddress'],
                "userpassw" => $userArr['password']);

            User::sendMail($userArray);


    } // End Function

     public static function createSupportuser($loginnameapi,$emailapi,$passwordapi){
        User::$dbObj = new Db();
       // print_r($_SESSION);exit;
           /*$sqlapi = " INSERT INTO sptbl_users(`nUserId`,`vUserName`,`nCompId`,`vEmail`,`vLogin`,`vPassword`,`dDate`, `vBanned`, `vDelStatus`) ";
    $sqlapi .= " VALUES('','" . addslashes($loginnameapi) . "','1', '" . addslashes($emailapi) . "','" . addslashes($loginnameapi) . "','" . addslashes(md5($passwordapi)) . "',now(),'0','0')";
    $resultapi = mysqli_query($conapi,$sqlapi); */

        $feedbackQry = "INSERT INTO sptbl_users SET nUserId = '', vUserName = '".addslashes($loginnameapi)."', nCompId = '1', vEmail = '" . addslashes($emailapi) . "',vLogin = '" . addslashes($loginnameapi) . "',vPassword = '" . addslashes(md5($passwordapi)) . "',dDate = now(),vBanned = '0',vDelStatus = '0' ";
      // echo $feedbackQry;exit;

        User::$dbObj->customQuery($feedbackQry);

    } // End Function

    public static function getNameById($userId) {

        User::$dbObj     = new Db();
        $sel =      "SELECT u.vFirstName, u.vLastName FROM " . User::$dbObj->tablePrefix . "User u WHERE u.nUId='" . $userId . "'";
        $res = User::$dbObj->selectQuery($sel);

        $data = NULL;
        if(!empty($res)) {
            $data .= $res[0]->vFirstName;
            $data .=(!empty($res[0]->vLastName)) ? '&nbsp;'.$res[0]->vLastName : '';

        }
        return $data;
    } // End Function

        public static function getUserStatusById($userId) {

        User::$dbObj     = new Db();
        $sel =      "SELECT u.nStatus FROM " . User::$dbObj->tablePrefix . "User u WHERE u.nUId='" . $userId . "'";
        $res = User::$dbObj->selectQuery($sel);

        $data = NULL;
        if(!empty($res)) {
            switch($res[0]->nStatus){
                case 0:
                    $data = 'Pending';
                    break;
                case 1:
                    $data = 'Active';
                    break;
                case 2:
                    $data = 'In Active';
                    break;
            }

        }
        return $data;
    } // End Function

    //Banner Image Fetching
     public static function loadBanners($type = "Footer")
    {
    	User::$dbObj = new Db();
      /*  $query = "SELECT tb.*,tf.file_path
    	          FROM ".User::$dbObj->tablePrefix."Banners tb
                  INNER JOIN ".User::$dbObj->tablePrefix."files tf ON tf.file_id=tb.vBannerImageId WHERE tb.vActive='1' " ;
        */
        if($type == "Footer")
           $limitText = "";
        else
            $limitText = "  LIMIT 5";
         $limitText;
     $query = "SELECT tb.*,tf.file_path
    	          FROM ".User::$dbObj->tablePrefix."Banners tb
                  INNER JOIN ".User::$dbObj->tablePrefix."files tf ON tf.file_id=tb.vBannerImageId WHERE tb.vActive='1' AND tb.eType = '". addslashes($type) ."' ORDER BY tb.displayOrder ASC " .$limitText ;
     //echo  $query;
     $res = User::$dbObj->selectQuery($query);

        if(count($res) > 0){
            $showCount = $res[0]->showcount;
            $arrUpdate = array("showcount" => $showCount + 1);
            $condition = "nBannerId = " . addslashes($res[0]->nBannerId);
            $status = User::$dbObj->updateFields("Banners",$arrUpdate,$condition);
        }

    	return $res;
    }

     public static function setClickCount($Id = 0)
     {
         User::$dbObj = new Db();
         $clickCount =  User::$dbObj->selectRow("Banners","clickcount","nBannerId='$Id'" );
         $arrUpdate = array("clickcount" => $clickCount + 1);
         $status = User::$dbObj->updateFields("Banners",$arrUpdate,"nBannerId = '".addslashes($Id)."'");
         return $status;
     }

    /*
     * get plan details
     */
    public static function getPlanDetails($planId) {
        User::$dbObj     = new Db();
        $planDetails     = User::$dbObj->selectQuery("SELECT * FROM ".User::$dbObj->tablePrefix."ProductServices WHERE nServiceId='$planId'");
        return $planDetails;
    }
    public static function getDomainRegDetails($planId) {
        User::$dbObj     = new Db();
        $planDetails     = User::$dbObj->selectQuery("SELECT * FROM ".User::$dbObj->tablePrefix."Settings WHERE settingfield='priceDomiainRegistration'");
        return $planDetails;
    }

    public static function getSubdomain($uid)
    {

        User::$dbObj     = new Db();
        $planDetails     = User::$dbObj->selectQuery("SELECT * FROM ".User::$dbObj->tablePrefix."ProductLookup WHERE nUId=".$uid." ORDER BY nPLId DESC LIMIT 1");
        return $planDetails;

    }
    public static function storePaymentsEntry($amount,$paymentMode,$transactionId,$description='')
    {
        User::$dbObj     = new Db();
        $arrProfile =   array("nPPId"=>'',
                        "nPId"=>PRODUCT_ID,
                        "nUId"=>LibSession::get('userID'),
                        "nAmount"=>$amount,
                        "vPlanDescription"=>$description,
                        "vPaymentMethod"=>$paymentMode,
                        "dPaymentDate"=>date("Y-m-d H:i:s", time()),
                        "vTransactionId"=>$transactionId
                );


                $paymentId =   User::$dbObj->addFields("payments",$arrProfile);
                return $paymentId;
    }

    public static function storePaymentsEntryStripe($amount,$paymentMode,$userid,$transactionId,$description='',$webhookdata)
    {
        User::$dbObj     = new Db();
        $arrProfile =   array("nPPId"=>'',
                        "nPId"=>PRODUCT_ID,
                        "nUId"=>$userid,
                        "nAmount"=>$amount,
                        "vPlanDescription"=>$description,
                        "vPaymentMethod"=>$paymentMode,
                        "dPaymentDate"=>date("Y-m-d H:i:s", time()),
                        "vTransactionId"=>$transactionId,
                        "webhookdata" =>$webhookdata
                );


                $paymentId =   User::$dbObj->addFields("payments",$arrProfile);
                return $paymentId;
    }

    public static function UpdatePaymentDescription($description,$nPaymentId)
    {
       User::$dbObj = new Db();
        $arrUpdate       = array();
        $arrUpdate["vPlanDescription"]= $description;
       User::$dbObj->updateFields("payments",$arrUpdate,"nPaymentId='".$nPaymentId."'");
        return true;
    }




    public static function updateLookupEntryBluedog($productLookUpId,$bluedog) {
        User::$dbObj = new Db();
        $arrUpdate       = array();
        $arrUpdate["bluedogdetails"]= serialize($bluedog);
        $arrUpdate["nStatus"]= 1;        

        User::$dbObj->updateFields("ProductLookup",$arrUpdate,"nPLId='".$productLookUpId."'");
        return true;
    }


        public static function updateLookupEntry($productLookUpId,$dName,$userId,$userArray = array()) {
        User::$dbObj = new Db();
        $arrUpdate       = array();
        $arrUpdate["nSubDomainStatus"]= 0;
        $arrUpdate["vSubDomain"]= '';
        $arrUpdate["vDomain"]= $dName;
        $arrUpdate["nStatus"]= 1;
        $arrUpdate["nDomainStatus"]= 1;
        if(isset($userArray) && !empty($userArray)) {
            $arrUpdate["vAccountDetails"] = serialize($userArray);
        }
        if(isset($userArray['customerDetails']) && !empty($userArray['customerDetails'])) {
            $arrUpdate["bluedogdetails"] = serialize($userArray['customerDetails']);
        }        
        User::$dbObj->updateFields("ProductLookup",$arrUpdate,"nPLId='".$productLookUpId."' AND nUId='".$userId."'");
        return true;
    }

    public static function saveFeedback($data) {
        User::$dbObj = new Db();

        $feedbackQry = "INSERT INTO ".User::$dbObj->tablePrefix . "contacts SET cname = '".addslashes($data['name'])."', cemail = '".addslashes($data['email'])."', cdescr = '".addslashes($data['feedback'])."', cdate = NOW()";
        User::$dbObj->customQuery($feedbackQry);
    }

    public static function getInvoiceDetails($idInvoice=NULL, $filterArr = NULL, $groupBy= NULL) {
        User::$dbObj     = new Db();
        $sel = "SELECT i.nInvId, i.nUId, i.vInvNo,i.nPLId, i.dGeneratedDate, i.dDueDate, i.nAmount, i.nDiscount,
             i.nTotal, i.vCouponNumber, i.vTerms, i.vNotes, i.vMethod, i.vSubscriptionType,
             i.vTxnId, i.dPayment, NOW() as currentDate,i.nPLId, ip.nSpecialCost, ip.vSpecials, ip.nAmount as ipAmount, ip.nAmtNext, ip.nDiscount as ipDiscount,ip.vType,ip.vBillingInterval as ipBillingInterval, ip.nBillingDuration as ipBillingDuration,ip.dDateStart, ip.dDateStop, ps.vServiceName, ps.vServiceDescription,
             ps.price as servicePrice, ps.vBillingInterval as serviceBillingInterval,
             ps.nBillingDuration as serviceBillingDuration, ps.nSCatId, pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus, pl.nCustomized, pl.nStatus, u.vUsername, u.vFirstName, u.vLastName, CONCAT(u.vFirstName,' ', u.vLastName) as vFullName,
             u.vEmail, u.vInvoiceEmail, u.vAddress, u.vCountry, u.vState, u.vZipcode, u.vCity FROM ".User::$dbObj->tablePrefix."Invoice i
             LEFT JOIN ".User::$dbObj->tablePrefix."InvoicePlan ip ON i.nInvId = ip.nInvId
             LEFT JOIN ".User::$dbObj->tablePrefix."ProductServices ps ON ip.nServiceId = ps.nServiceId
             LEFT JOIN ".User::$dbObj->tablePrefix."ProductLookup pl ON i.nPLId = pl.nPLId
             LEFT JOIN ".User::$dbObj->tablePrefix."User u ON i.nUId = u.nUId";

        $filter = NULL;

        if(!empty($idInvoice)){
            $filter .="i.nInvId='".$idInvoice."'";
        }

        // FILTER fields for where condition generation like a='xx' AND b='xx' AND c='xx'
        if(!empty($filterArr)) {
            foreach($filterArr as $filterItem) {
                $filterCondition = (isset($filterItem['condition'])) ? $filterItem['condition'] : '=';
                $filter .= (!empty($filter)) ? ' AND ' : '';
                $filter .= (!empty($filterItem)) ? $filterItem['field']." ".$filterCondition." '".$filterItem['value']."'" : NULL;
            } // End Foreach
        } // End If


        //$sel .= (!empty($filter)) ? ' AND ' : '';
        $sel .=" WHERE ".$filter;
        $sel .=(!empty($groupBy))?" GROUP BY $groupBy":"";
        $sel .= " ORDER BY i.dGeneratedDate DESC,i.dDueDate DESC";
        //echo '<br/>'.$sel;
//        echo '<br/>';
        $dataArr = User::$dbObj->selectQuery($sel);
        return $dataArr;

    } // End Function

    public static function getInvoiceDomainDetails($idInvoice) {

        User::$dbObj     = new Db();

        $sel = "SELECT id.nIDId, id.nSCatId, id.nPLId, id.vDescription, id.nRate, id.nAmount, id.nAmtNext,
             id.vType, id.vBillingInterval, id.nDiscount, id.dDateStart, id.dDateStop, id.dDateNextBill,
             id.dCreatedOn, id.nPlanStatus, NOW() as currentDate, pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus, pl.nCustomized, pl.nStatus FROM ".User::$dbObj->tablePrefix."InvoiceDomain id
             LEFT JOIN ".User::$dbObj->tablePrefix."ProductLookup pl ON id.nPLId = pl.nPLId
             WHERE id.nInvId='".$idInvoice."' ORDER BY id.dCreatedOn DESC";

        $dataArr = User::$dbObj->selectQuery($sel);
        return $dataArr;

    } // End Function

    public static function getInvoiceTemplateDetails($idInvoice) {

         User::$dbObj     = new Db();

          $sel =    "SELECT TP.id as nInvId, TP.id as vInvNo, TP.nPLId, TP.paidOn as dGeneratedDate, TP.paidOn as dDueDate, TP.amount as nAmount, '0.00' as nDiscount, TP.amount as nTotal, CONCAT_WS(' - ','Template Purchase',T.vTemplateName) as vServiceName,
                    TP.comments as vServiceDescription,  TP.paidOn as dPayment, 'template' as billgenType,u.vUsername, u.vFirstName, u.vLastName, CONCAT(u.vFirstName,' ', u.vLastName) as vFullName,
                    u.vEmail, u.vInvoiceEmail, u.vAddress, u.vCountry, u.vState, u.vZipcode, u.vCity
                    FROM ". User::$dbObj->tablePrefix . "paidtemplatepurchase TP
                    LEFT JOIN ". User::$dbObj->tablePrefix . "PaidTemplates T ON TP.nTemplateId=T.nTemplateId
                    LEFT JOIN ".User::$dbObj->tablePrefix."User u ON TP.nUId = u.nUId
                    WHERE TP.nUId='".LibSession::get('userID') . "' AND TP.id='".$idInvoice."'";

         // echo $sel;exit;
         $dataArr = User::$dbObj->selectQuery($sel);
        return $dataArr;



    }
    public static function getStoreHost($productLookUpID){
        User::$dbObj = new Db();
        $data = NULL;

        // vSubDomain, nSubDomainStatus, vDomain, nDomainStatus
        $sel = "SELECT pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,s.vserver_name FROM ".User::$dbObj->tablePrefix."ProductLookup pl
                LEFT JOIN ".User::$dbObj->tablePrefix."serverHistory h ON pl.nPLId = h.nPLId
                    LEFT JOIN ".User::$dbObj->tablePrefix."ServerInfo s ON h.nserver_id = s.nserver_id
                        WHERE pl.nPLId ='".$productLookUpID."'";


        $dataArr = User::$dbObj->selectQuery($sel);

        if(!empty($dataArr)){
            if($dataArr[0]->nSubDomainStatus == 1){
                $data = $dataArr[0]->vSubDomain.'.'.$dataArr[0]->vserver_name;
            } else if($dataArr[0]->nDomainStatus == 1){
                $data = $dataArr[0]->vDomain;
            }
        }

        return $data;
     } // End Function

      public static function billingInterval($type) {
        $bType ='--';
        if(!empty($type)) {
            switch($type) {
                case 'M':
                    $bType = 'Monthly';
                    break;
                case 'Y':
                    $bType = 'Yearly';
                    break;
                case 'L':
                    $bType = 'One-time';
                    break;
            }
        }
        return $bType;
    } // End Function

     public static function planInterval($type) {
        $bType ='--';
        if(!empty($type)) {
            switch($type) {
                case 'M':
                    $bType = 'Month';
                    break;
                case 'Y':
                    $bType = 'Year';
                    break;
                case 'L':
                    $bType = 'One-time';
                    break;
            }
        }
        return $bType;
    } // End Function

    public static function getInvoicePaymentStatus($currentDate, $dueDate, $paymentDate) {
        $status = '--';
        if(Utils::checkDateTime($paymentDate)){

            $status = (strtotime($currentDate) >= strtotime($paymentDate) || date("Y-m-d", strtotime($currentDate)) == date("Y-m-d", strtotime($paymentDate))) ? 'Paid' : '--';

        } else {
            $status = (strtotime($currentDate) > strtotime($dueDate)) ? 'Due' : '--';
        }

        return $status;
    } //End Function

    //Function to fetch active menus
    public static function getActiveMenus() {
	User::$dbObj = new Db();
        $sel = "SELECT cms_name,cms_title FROM ".User::$dbObj->tablePrefix."Cms WHERE cms_status=1 AND cms_type='cms'";
        $data = User::$dbObj->selectQuery($sel);
	return $data;
    }
    //End Function

     public static function getUserFromSupport($email) {
        User::$dbObj     = new Db();
        $userDetails     = User::$dbObj->selectQuery("SELECT * FROM sptbl_users WHERE vEmail = '$email'");
        return $userDetails;
    }



    public static function getFwMetaData($method='')
    {

        User::$dbObj     = new Db();

        $url = '/'.$method;

        $metaDetails     = User::$dbObj->selectQuery("SELECT * FROM fw_metadata WHERE url = '$url'");
        if(!$metaDetails)
        {
            $url = '/';
            $metaDetails     = User::$dbObj->selectQuery("SELECT * FROM fw_metadata WHERE url = '$url'");
        }


        //echopre($metaDetails);
        if($metaDetails)
        {
           //define('META_TITLE', stripslashes($metaDetails[0]->title));

           define('META_DES', stripslashes($metaDetails[0]->description));

           define('META_KEYWORDS', stripslashes($metaDetails[0]->keyword));
        }
        //return $metaDetails;

    }


                



} //End Class
?>
