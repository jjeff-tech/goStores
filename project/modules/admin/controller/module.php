<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.                                      |
// | File name : plan.php                                                  |
// | PHP version >= 5.2                                                   |
// | Created On	: 23 Aug 2012
// | Author : Meena Susan Joseph <meena.s@armiasystems.com>                                               |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+


class ControllerModule extends BaseController {
    /*
      construction function. we can initialize the models here
     */

    public function init() {
        parent::init();
        $this->_common = new ModelCommon();
        /*         * *********** Admin Access Check *************** */
        $adminAccess = User::adminAccessCheck();

        if ($adminAccess == 0) {
            $this->redirect('login/index');
        }
        /*         * *********** Admin Access Check End *********** */
        /*         * *********** Left Menu Area *********** */
        $leftMenuArr = NULL;
        if (isset($_SESSION['adminUser']['userModules']) && !empty($_SESSION['adminUser']['userModules'])) {
            $leftMenuArr = $_SESSION['adminUser']['userModules'];
        }
        $this->view->leftMenu = 'left_main';
        $this->view->leftMenuArr = $leftMenuArr;
        /*         * *********** Left Menu Area Ends ****** */
        PageContext::addScript("admin.js");
    }

    /*
      function to load the index template
     */

    public function index() { 
        $this->view->setLayout("home");
    }

    /*
      Function to logout the user
     */

    public function logout() {
        session_destroy();
        session_unset($_SESSION['user']);

        header("location:" . ConfigUrl::base());
        $this->view->disableView();
        exit();
    }
    
    public static function godaddycertify() {
        $utils   = new Utils();
        $common = new ModelCommon();
     
       
        PageContext::includePath('parsexmls');
         
        $parse  = new parsexmls();
        PageContext::includePath('godaddy');
        
        $isDomAvailable = new goDaddy;
        $retVal = array();
        global $retVal;
        global $privacyUserID;
        $currstep       = 1;
        // INIT : reset the certification process back to STEP 1
        $isDomAvailable->resetcertification();

        // STEP 1 : check availability of the following 2 domains [ just check, not dependent on results ]
        if($currstep == 1)
        {
            $certifyDomains = array('example.us', 'example.biz');
            $isDomAvailable->checkdomainavailability($certifyDomains);

            $currstep = 2;
        }

        // STEP 2 : register the 2 domains
        if($currstep == 2)
        {
            $registerDomains[0]['domain']   = 'example.us';
            $registerDomains[0]['duration'] = '2';
            $registerDomains[0]['prd_id']   = '350127';

            $registerDomains[1]['domain']   = 'example.biz';
            $registerDomains[1]['duration'] = '2';
            $registerDomains[1]['prd_id']   = '350077';

            $nsarray =  array('ns1.example.com', 'ns2.example.com');

            $op = $isDomAvailable->registerdomain('agordon@wildwestdomains.com', 'Artemus', 'Gordon', '8885551212', '2 N. Main St.', 'Valdosta', 'Georgia', '17123', 'United States', $registerDomains, $nsarray, 'abcde', true);

            $currstep = 3;
        }
        // Poll the server & get the resourceids
        $pollRes = $isDomAvailable->pollserver();
        $parse->parseXML($pollRes);
        //print_r($retVal);exit;

        // STEP 3 : order privacy for domain example.us
        if($currstep == 3)
        {
            $info = $isDomAvailable->domainprivacy($retVal[1], '377001', $op['user'], 'example.biz', 2, 'info@example.biz', 'Artemus', 'Gordon', '8885551212', 'defgh');
            $parse->parseUserXML($info);
            $currstep = 4;
        }

        // STEP 4 : check the availability of both domains again
        if($currstep == 4)
        {
            $isDomAvailable->checkdomainavailability($certifyDomains);
            $currstep = 5;
        }

        // STEP 5 : get info of example.biz
        if($currstep == 5)
        {
            $isDomAvailable->getinfo('example.biz', false, 1, $retVal[1]);
            $currstep = 6;
        }

        // Poll the server again & get the resourceid of privacy purchased
        $pollRes = $isDomAvailable->pollserver();
        $parse->parseXML($pollRes);

        //print_r($retVal);exit;

        // STEP 6 : renew both the domains
        if($currstep == 6)
        {
            $renewDomains[0]['domain']   = 'example.us';
            $renewDomains[0]['duration'] = '1';
            $renewDomains[0]['prodID']   = '350137';
            $renewDomains[0]['resID']    = $retVal[0];

            $renewDomains[1]['domain']   = 'example.biz';
            $renewDomains[1]['duration'] = '1';
            $renewDomains[1]['prodID']   = '350087';
            $renewDomains[1]['resID']    = $retVal[1];

            $privacy[0]['duration'] = '1';
            $privacy[0]['prodID']   = '387001';
            $privacy[0]['resID']    = $retVal[2];

            $isDomAvailable->privatedomainrenewal($op['user'], $privacyUserID, $renewDomains, $privacy, 'defgh');
            $currstep = 7;
        }

        // STEP 7 : transfer a domain example.com
        if($currstep == 7)
        {
            $paramArray['pass']     = 'ghijk';
            $paramArray['email']    = 'joe@smith.us';
            $paramArray['fname']    = 'Joe';
            $paramArray['lname']    = 'Smith';
            $paramArray['phone']    = '+1.7775551212';
            $paramArray['prodID']   = '350011';
            $paramArray['sld']      = 'example';
            $paramArray['tld']      = 'com';

            $isDomAvailable->certifytransferdomain($paramArray);

        } 
      
    //$this->view->setLayout("home");
        
    }

    public function settingsdisplay() {
    	$currentURL ="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    	pageContext::$response->currentURL          =   $currentURL;
    	PageContext::addJsVar("currentURL", $currentURL);
    	$searchURL = BASE_URL."cms?section=".$request['section'];
    	pageContext::$response->searchURL          =   $searchURL;
    	PageContext::addJsVar("searchURL", $searchURL);
        $utils   = new Utils();
        $common = new ModelCommon();
        PageContext::includePath('resize');
        PageContext::addScript("settingsdisplay.js");
        PageContext::addScript("godaddycertify.js");
        //  PageContext::addScript("main.js");
        // PageContext::addScript("jquery.js");
        //  PageContext::addStyle("admin_style.css");
                
        $message = "";
        $success = "success";
        $enableChecks = array('enableGoogleAdsense', 'enablesiteBanner', 'streamsend_enable','recaptcha_enable');
        $enablePaymentChecks = array('enablepaypal','enablepaypalsandbox','authorize_enable','authorize_test_mode','twoco_enable','twoco_testmode','paypalpro_enable','paypalpro_testmode','paypalexpress_enable','paypalexpress_testmode','paypaladvanced_enable','paypaladvanced_testmode','paypalflowlink_enable','paypalflowlink_testmode','ogone_enable','ogone_testmode','moneybookers_enable','moneybookers_testmode','braintree_enable','braintree_testmode','enable_googlecheckout','yourpay_enable','yourpay_demo','quickbook_enable','quickbook_testmode','paypalflow_enable','paypalflow_testmode','stripe_enable','stripe_test_mode');
        $enableDomainChecks = array('godaddy_testmode', 'enom_testmode', 'enableDomiainRegistration');
        $enableSocialSettingChecks = array('enable_fb', 'enable_twitter','enable_ln');

        // General
        if(isset($_POST['submitBtn'])){
            /*
            if(is_uploaded_file($_FILES['siteBanner']['tmp_name'])) {  
                $bannerParts = pathinfo($_FILES['siteBanner']['name']);               
                $bannerOriginal = BASE_PATH.'project/styles/images/'.'mainbanner.'.$bannerParts['extension'];

                if(move_uploaded_file($_FILES['siteBanner']['tmp_name'], $bannerOriginal)) {
                    $resizeObj = new resize($bannerOriginal);
                    $resizeObj->resizeImage(775, 100, 'exact');
                    $rz=preg_replace("/\.([a-zA-Z]{3,4})$/","_disp.gif",$bannerOriginal);
                    $resizeObj->saveImage($rz, 100);
                    $_POST['siteBanner'] = 'mainbanner_disp.gif';      
                    @unlink($bannerOriginal);
                }  
            } */

            // photo upload
            if($_FILES['siteLogo']['size']>0) {
                Logger::info("Site Logo Image upload...");
                $photoFileDetails	= $utils->uploadFile($_FILES['siteLogo']);
                $photo_id               = $photoFileDetails->file_id;
                $common->updateFields('files',array("file_type"  => 'photo'),'file_id ='.$photo_id); // update the user table
                $utils->createThumbnail($photo_id,'siteLogo',true);
                $_POST['siteLogo']=$photo_id;
            }
            
            foreach($enableChecks as $checkBoxes){
                if($_POST[$checkBoxes] == ''){
                    $_POST[$checkBoxes] = 'N';
                }
            }

            // Update Company Name for Support desk
            if(isset($_POST['siteName'])){
                Admin::updateCompanyNameForSupport($_POST['siteName']);
            }

            // Update Company Name for Support desk, in case if Company Name is specified
            if(isset($_POST['company_name']) && !empty($_POST['company_name'])){
                Admin::updateCompanyNameForSupport($_POST['company_name']);
            }

            Admin::updateSettings($_POST);
            if(isset($photo_id)){
             $newLogo=Admincomponents::getSiteLogoName();
            }
            $message = "Settings updated successfully.";
            $success = "success";
        }

        // Payment
        if(isset($_POST['paymentSubmitBtn'])){ 

            //echopre($_POST);exit;

            foreach($enablePaymentChecks as $checkBoxes){
                if($_POST[$checkBoxes] == ''){
                    $_POST[$checkBoxes] = 'N';
                }
            } //echopre1($_POST);
            if($_POST['enablepaypal']=='N' && $_POST['authorize_enable']=='N' && $_POST['twoco_enable']=='N' && $_POST['paypalpro_enable']=='N' && $_POST['paypalexpress_enable']=='N' && $_POST['paypaladvanced_enable']=='N' && $_POST['paypalflowlink_enable']=='N' && $_POST['ogone_enable']=='N' && $_POST['moneybookers_enable']=='N' && $_POST['braintree_enable']=='N' && $_POST['enable_googlecheckout']=='N' && $_POST['yourpay_enable']=='N' && $_POST['quickbook_enable']=='N' && $_POST['paypalflow_enable']=='N' &&  $_POST['bluedog_enable']=='N'){
                $message = "Please enable atleast one payment method.";
                $success = "error";
            }else{
                Admin::updateSettings($_POST);
                $message = "Payment Settings updated successfully.";
                $success = "success";
            }
        }

        // Domain Registrar
        if(isset($_POST['domainSubmitBtn'])){  
            foreach($enableDomainChecks as $checkBoxes){
                if($_POST[$checkBoxes] == ''){
                    $_POST[$checkBoxes] = 'N';
                }
            }  //echopre1($_POST);

            if(!empty($_POST["enom_password"])){
                $_POST["enom_password"] = User::encrytCreditCardDetails($_POST["enom_password"]);
            }
            if(!empty($_POST["godaddy_password"])){
                $_POST["godaddy_password"] = User::encrytCreditCardDetails($_POST["godaddy_password"]);
            }

            Admin::updateSettings($_POST);
            Admin::updateTLD($_POST);
            $message = "Domain Registrar Settings updated successfully.";
            $success = "success";
        }

        // Social Settings
        if(isset($_POST['socialSubmitBtn'])){
            foreach($enableSocialSettingChecks as $checkBoxes){
                if($_POST[$checkBoxes] == ''){
                    $_POST[$checkBoxes] = 'N';
                }
            }
            Admin::updateSettings($_POST);
            $message = "Social Settings updated successfully.";
            $success = "success";
        }

        // Name Servers
        if(isset($_POST['nameServerSubmitBtn'])){ //echopre($_POST);
            $messageX = NULL;
            $valCount = $valCountX = 0;
            for($i=1;$i<=4;$i++){ 
                if($_POST['name_server_'.$i]!=''){
                    $valCount = $valCount+1;
                    if (preg_match("/\\s/", $_POST['name_server_'.$i])) {
                       // there are spaces
                        $valCountX += 1;
                        $messageX .= (!empty($messageX)) ? ", " : "";
                        $messageX .= "'".$_POST['name_server_'.$i]."' ";
                    }
                }
            }
            if($valCount<2){
                $message = "Please enter atleast two name servers.";
                $success = "error";
            } else if($valCountX>0){
                $message = "Whitespaces are not allowed for name servers details ".$messageX.".";
                $success = "error";
            }else{
                Admin::updateSettings($_POST);
                $message = "Name Servers updated successfully.";
                $success = "success";
            }
        }
        
        if(isset($_POST['SMTPSubmitBtn'])){ //echopre1($_POST);
               $messageX = NULL;
            unset($_POST['SMTPSubmitBtn']);
                Admin::updateSettings($_POST);
                $message = "SMTP Details updated successfully.";
                $success = "success";
            
        }
        
        
        
        if(isset($_POST['passwordSubmitBtn'])){
            
            //$admin_uid = $_SESSION['admin_type'];  // cms doesnt support uid as of now
            $admin_uid = $_SESSION['cms_admin_type'];
            $updatePassword = Admin::updateAdminPassword($admin_uid, $_POST);

            if($updatePassword=='success'){
               $message = "Password updated successfully.";
                $success = "success";
            }else{
                $message = $updatePassword;
                $success = "error";
            }
        }
        
        
        if(isset($_POST['serverSettingsSubmitBtn'])){ // Server Settings Area
            echo "hi";exit;

            if($_POST[""])

                $_POST["site_operation_mode"];
                $_POST["site_operation_park_domain"];

            if(!empty($_POST["site_operation_mode"]) && !empty($_POST["site_operation_park_domain"])){

                echopre($_POST);exit;
                $response = Admin::updateSettings($_POST);
                if($response){
                    $message = "Server settings updated successfully.";
                    $success = "success";
                }
            } else {

                $message = "";              
                $message .= (empty($_POST["site_operation_mode"])) ? "site operation mode" : "";
                $message .= (!empty($message)) ? ", " : "";
                $message .= (empty($_POST["site_operation_mode"])) ? "temporary URLs" : "";
                $message = (!empty($message)) ? "Please choose ".$message : "";
                $success = "error";
            }
        }

        
        $pageContentsData  = Admincomponents::getListItem("Settings", array('*'), array(array('field' => 'groupLabel' , 'value' => 'General')));
        $pageContents = array();
        foreach($pageContentsData as $pageItems ){
            $pageItems->value = stripslashes($pageItems->value);
            $pageContents[$pageItems->settingfield] = $pageItems;
        }
        PageContext::$response->pageContents  = $pageContents;
        PageContext::$response->siteLogoName = Admincomponents::getSiteLogoName(); 
        
        $pagePaymentContentsData  = Admincomponents::getListItem("Settings", array('*'), array(array('field' => 'groupLabel' , 'value' => 'Payment')));
        $pagePaymentContents = array();
        foreach($pagePaymentContentsData as $pageItems ){
            $pagePaymentContents[$pageItems->settingfield] = $pageItems;
        }


        PageContext::$response->pagePaymentContents = $pagePaymentContents;

        $pageDomainContentsData  = Admincomponents::getListItem("Settings", array('*'), array(array('field' => 'groupLabel' , 'value' => 'Domain Registrar')));
        $pageDomainContents = array();
        foreach($pageDomainContentsData as $pageItems ){
            
             if($pageItems->settingfield == "enom_password"){
                $pageItems->value = User::decrytCreditCardDetails($pageItems->value);
                
            }
            if($pageItems->settingfield == "godaddy_password"){

                $pageItems->value = User::decrytCreditCardDetails($pageItems->value);
            }
            $pageDomainContents[$pageItems->settingfield] = $pageItems;
            
        }
        
        PageContext::$response->pageDomainContents  = $pageDomainContents;

        $pageSocialSettingContentsData   = Admincomponents::getListItem("Settings", array('*'), array(array('field' => 'groupLabel' , 'value' => 'Social Settings')));
        $pageSocialSettingContents = array();
        foreach($pageSocialSettingContentsData as $pageItems ){
            $pageSocialSettingContents[$pageItems->settingfield] = $pageItems;
        }
        PageContext::$response->pageSocialSettingContents = $pageSocialSettingContents;
        
        $pageNameServerContentsData  = Admincomponents::getListItem("Settings", array('*'), array(array('field' => 'groupLabel' , 'value' => 'Name Servers')));
        $pageNameServerContents = array();
        foreach($pageNameServerContentsData as $pageItems ){
            $pageNameServerContents[$pageItems->settingfield] = $pageItems;
        }
        PageContext::$response->pageNameServerContents = $pageNameServerContents;

        $pageServerSettingContentsData = Admincomponents::getListItem("Settings", array('*'), array(array('field' => 'groupLabel' , 'value' => 'Server Settings')));
        $pageServerSettingContents = array();
        foreach($pageServerSettingContentsData as $pageItems ){
            $pageServerSettingContents[$pageItems->settingfield] = $pageItems;
        }
        PageContext::$response->pageServerSettingContents = $pageServerSettingContents;
        
        $pageSMTPData = Admincomponents::getListItem("Settings", array('*'), array(array('field' => 'groupLabel' , 'value' => 'SMTP')));
        $pageSMTPDataContents = array();
        foreach($pageSMTPData as $pageItems ){
            $pageSMTPDataContents[$pageItems->settingfield] = $pageItems;
        }
        PageContext::$response->pageSMTPDataContents = $pageSMTPDataContents;
        
        
        
        //echopre(PageContext::$response->pageSMTPDataContents);
        PageContext::$response->message = $message;
        PageContext::$response->successError = $success;
    }

}

?>