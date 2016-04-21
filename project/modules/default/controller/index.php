<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +----------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.                                      |
// | File name : index.php                                                  |
// | PHP version >= 5.2                                                   |
// | Created On :   Nov 17 2011                                               |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +-----------------------------------------------------------------------


class ControllerIndex extends BaseController {
    /*
      construction function. we can initialize the models here
     */

    public function init() { 
        parent::init();
        PageContext::$body_class = "home";

        PageContext::addJsVar('payNow', BASE_URL . "index/creditcardbuy/");
        PageContext::addJsVar('otherpaymanturl', BASE_URL . "index/otherpaymantbuy/");
        PageContext::addJsVar('otherpaymanturldomain', BASE_URL . "index/registerdomainotherpay/");
        PageContext::addScript('paynow.js');

        PageContext::addJsVar("createnewsletter", BASE_URL . "index/createnewsletter/");
        PageContext::addScript("jquery.metadata.js");
        //PageContext::addScript("jquery.validate.js"); // Avoid including it twice. It has been included globally in framework
        PageContext::addScript("general.js");
        PageContext::addJsVar('userLogin', BASE_URL . "index/userlogin/");
        PageContext::addJsVar('loginSuccess', BASE_URL . "dashboard/");
        PageContext::addScript("dropmenu.js");
        PageContext::addScript("dropmenu_ready.js");
        PageContext::addStyle("dropdown_style.css");
        //PageContext::addScript("jquery.min.js");
        PageContext::addScript('jquery.timer.js');

        PageContext::addScript("login.js");
        PageContext::addScript("hoverIntent.js");
        //PageContext::addScript("jquery-1.2.6.min.js");
        PageContext::addScript("superfish.js");
        // Tool Tip
        PageContext::addScript("jquery.tooltipster.min.js");
        User::googleAnalytics();
        PageContext::addStyle("custom_progress/progress.css");
        PageContext::addScript('banner.js');
        PageContext::addJsVar("checkUserAccount", BASE_URL . "index/checkUserAccount/");
        PageContext::addJsVar("SetUpgradeStripeToken", BASE_URL . "index/SetUpgradeStripeToken/");
        PageContext::addJsVar('userLoginInner', BASE_URL . "index/userlogin/");

        User::getFwMetaData(METHOD);

    }

    /*
      function to load the index template
     */

    public function index() {

        PageContext::addJsVar('loginSuccess', BASE_URL . "dashboard/");
        PageContext::addScript("usercontact.js");
        //****************home banner *********
        PageContext::addScript("homebanner.js"); // PageContext::addScript("homebanner.js");
        PageContext::addScript("nivoslider/jquery.nivo.slider.pack.js"); // PageContext::addScript("jquery.easing.1.3.js");
        PageContext::addStyle("nivoslider/default/default.css"); // PageContext::addStyle("homebanner.css");
        PageContext::addStyle("nivoslider/nivo-slider.css");

        //$HeaderBanner = User::loadBanners('Header');
        $HeaderBanner = User::loadBanners('Home Page Sliding Banner');

        PageContext::$response->HeaderBanner = $HeaderBanner;
        //****************home banner ends*********
        //**********Payment gateways Footer*******
        PageContext::$response->paymentsEnabled = Payments::getEnabledPaymnets();

        //**************

        PageContext::addScript("userlogin.js");

        PageContext::addJsVar("createAccount", BASE_URL . "index/createaccount/");
        PageContext::addPostAction('freetrial');
        Utils::loadActiveTheme();
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;

        PageContext::addPostAction('cloudfooterpage');

        $this->view->setLayout("productpage");

        //TODO: add option to assign the theme dynamicaly
        PageContext::$metaTitle = "Welcome to ".META_TITLE;
        PageContext::$response->themeUrl = Utils::getThemeUrl();

        if(LibSession::get('userID') != "") {
            PageContext::$response->userId = LibSession::get('userID');
        }else{
            PageContext::$response->userId = "";
        }


         PageContext::$response->distributors = Admincomponents::getDistributors();

        PageContext::$response->partners = Admincomponents::getPartners();


         $sessionObj         = new LibSession();
         $sessionObj->set("plan_id", '');
         $sessionObj->set("template_id", '');


    }

    /*
      Function to logout the user
     */

    public function logout() {
        session_destroy();
        LibSession::destroy();
        $this->redirect('');
        $this->view->disableView();
        exit();
    }

    public function createaccount(){
        set_time_limit(0);
//        error_reporting(E_ALL);

        PageContext::includePath('cpanel');
        $cpanelObj = new cpanel();
        $dbArray = array();
        $productInstallPath = BASE_PATH . '' . $this->post('txtStoreName') . '/';



        $subdom = $this->post('txtStoreName');
        $this->view->disableView();
        $productRestriction = 0;

        /*         * ******* Validate Free trila post and re-captcha **************** */
        $sessionObj         = new LibSession();
        $accessFlag         = $recaptchaValid = $freeTrialFlag = 0;
        $recaptchaValid     = $sessionObj->get("recaptchaValid");
        $freeTrialFlag      = $sessionObj->get("freeTrialFlag");



        User::$dbObj        = new Db();
        $recaptchaStatus    = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_enable'");
        if ($freeTrialFlag == 1){
            $accessFlag = 1;
        }
        if ($recaptchaStatus == 'Y'){
            $accessFlag = ($recaptchaValid == 1) ? 1 : 0;
        }

        /* $data = array('success' => 1, 'list' => $freeTrialFlag."_".$accessFlag."_".$this->post('txtStoreName'));
        echo json_encode($data);
        exit; */

        /*         * ******* Validate Free trila post and re-captcha **************** */

        if ($this->post('txtStoreName') != '' && $accessFlag == 1){
            $userArray      = array();
            $productArray   = array();
            $storeName      = $this->post('txtStoreName');
            $userEmail      = $this->post('txtEmail');
            $userName       = $this->post('txtUserName');
            $userPassword   = $this->post('txtPassword');
            $productId      = PRODUCT_ID;

            // delete session for re-captcha
            $sessionObj->delete("recaptchaValid");
            $sessionObj->delete("freeTrialFlag");

            if (LibSession::get('userID') != "") {
                $userDetails = User::getUserDetails(LibSession::get('userID'));
                $userPassword = substr(md5($userDetails->vUsername), 0, 6);
                $userArray = array(
                    'user_name'     => $userDetails->vUsername,
                    'user_email'    => $userDetails->vEmail,
                    'store_name'    => $storeName,
                    'userpassw'     => $userPassword,
                );
                $userEmail  = $userDetails->vEmail;
                $userName   = $userDetails->vUsername;
            } else {
                $userArray = array(
                    'user_name'     => $userName,
                    'user_email'    => $userEmail,
                    'store_name'    => $storeName,
                    'userpassw'     => $userPassword,
                );
            }
            $subdom = strtolower($subdom);
            $subdom = str_replace(" ", '', $subdom);
            $productArray['id'] = PRODUCT_ID;
            $productArray['packname'] = User::getproductPackName($productId);
            $productArray['permissionlist'] = User::getproductPermission($productId);
            $productArray['productreleaseid'] = User::getproductReleaseID($productId);
            /*             * **************************** Product Service ****************** */
            // Purchase Service Id For Free Trial Plan
            $purchaseServiceId = Admincomponents::getFreePlanId();
            $productRestriction = User::getPlanproductRestriction($purchaseServiceId);
            $productArray['planProductRestriction'] = $productRestriction;
            $productArray['productAccessKey'] = md5(SECRET_SALT.$subdom . '.' . DOMAIN_NAME);
            $productArray['xmlproductdata'] = User::setXmlData($productArray['planProductRestriction'], md5($subdom));
            $productArray['productServices'] = array($purchaseServiceId);

            $username = substr(strtolower($userName), 0, 3);
            $username = $username . substr(md5($userName . time()), 0, 3);
            $password = substr(md5($RegistrantFirstName), 0, 8);
            $domainName = $subdom . '.' . DOMAIN_NAME;

            $statusArray = $cpanelObj->createcpanelaccountforsubdomain($username, $password, $domainName, $userEmail, $productArray);
//echopre($statusArray);
            $userArray['c_user'] = $username;
            $userArray['c_pass'] = $statusArray['c_pass'];
            $userArray['c_host'] = $domainName;

            /************* Store Databse credentials ********/
            $userArray['db_name'] = $statusArray['db_name'];
            $userArray['db_user'] = $statusArray['db_user'];
            $userArray['db_password'] = $statusArray['db_password'];
            
            
            
            //echo "<pre>"; print_r($statusArray); echo "</pre>"; die();

            if ($statusArray['status'] == 0) {
                //Failed
                if (isset($statusArray['tech_statusmsg']) && trim($statusArray['tech_statusmsg']) <> '') {
                    $contents = $statusArray['tech_statusmsg'];
                } else {
                    $contents = "Account setup failed.. Please try again later.";
                }

                $data = array('failed' => 1, 'list' => $contents);
                echo json_encode($data);
                die;
            }
            /* New code */
        }else{
            //Failed
            $contents = "Account setup failed due to invalid cPanel credentials.";
            $data = array('failed' => 1, 'list' => $contents);
            echo json_encode($data);
            die;
        }

        $userArray['front_end_url'] = "http://www.".$subdom.".".DOMAIN_NAME."/index.php";
        $userArray['back_end_url']  = "http://www.".$subdom.".".DOMAIN_NAME."/admins/";

        $contents = "

Site Login Details<br>

Admin URL : <a href='http://www.$subdom" . "." . DOMAIN_NAME . "/admins/' target='_blank'>http://www.$subdom" . "." . DOMAIN_NAME . "/admins/</a><br>

Admin Credentials : Username : admin<br>
Password : admin<br>

Home URL :  <a href='http://www.$subdom" . "." . DOMAIN_NAME . "/index.php' target='_blank'>http://www.$subdom" . "." . DOMAIN_NAME . "/</a><br>
                ";
        $contents = '<div class="storecration_instalation_wrapper" style="">
                                                                        <div class="storecration_instalation_wrapper_inner">
                                                                            <div class="instalation_completed_img "></div>

                                                                             <div class="pymnt_sucessmsgs" style="text-align:center;"><div class="store_success">
        <div class="store_success_label"></div>
            <h2>Congratulations!</h2>
            <h3>Your installation was successful!</h3>
            <p class="head">Site Login Details</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Admin URL </td>
            <td align="left" valign="top">:&nbsp;<a href="http://www.' . $subdom . '.' . DOMAIN_NAME . '/admins/"  target="_blank">http://www.' . $subdom . '.' . DOMAIN_NAME . '/admins/</a></td>
            </tr>
            <tr>
            <td align="left" valign="top">Home URL </td>
            <td align="left" valign="top">:&nbsp;<a href="http://www.' . $subdom . '.' . DOMAIN_NAME . '/index.php"  target="_blank">http://www.' . $subdom . '.' . DOMAIN_NAME . '/</a></td>
            </tr>
            </table>
            <p class="head">Admin Credentials</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Username</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            <tr>
            <td align="left" valign="top" >Password</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            </table>

        </div><div class="clear"></div>
                                                                              </div>
                                                                        </div>
                                                                        <div class="clear"></div>
                                                                  </div>';

        Utils::reconnect();
        $pdLookupId = $this->accountcreateintrial($userArray, $subdom, $productArray);

        /*         * ************ generate invoice for free trial */

        Utils::reconnect();

        $dataArr = array('nUId' => LibSession::get('userID'),
            'nPLId' => $pdLookupId,
            'services' => $productArray['productServices'],
            'domainService' => array(),
            'couponNo' => '',
            'terms' => '',
            'notes' => '',
            'paymentstatus' => '',
            'vMethod' => '',
            'vTxnId' => '',
            'upgrade' => '',
            'subscriptionType' => 'FREE');

        Admincomponents::generateInvoice($dataArr);
        /*         * ************ generate invoice for free trial */

        $data = array('success' => 1, 'list' => $contents);
        echo json_encode($data);
        exit;
    }

    public function accountcreateintrial($userArray, $subdom, $productArray) {
        set_time_limit(0);

        $connection = new Db();

        /*
         * Auto User account setup process
         */
        $productLookupId = NULL;
        if (LibSession::get('userID') == "") {

            if ($userId = User::createUserAccount($userArray)) {
                User::sendMail($userArray);
                $userFullInfoArr = Admincomponents::getUserdetails($userId);

                /*
                 * Session value setup
                 */
                //***********************NOTE*********************//
                /*
                 * Planid,plan package and package description  are not using now. It can be added later.
                 */

                //***********************NOTE*********************//
                LibSession::set('reg_usr_id', $userId);
                LibSession::set('userID', $userId);
                LibSession::set('firstName', $userFullInfoArr->vFirstName);
                LibSession::set('planid', 1);
                LibSession::set('planpackage', 1);
                LibSession::set('purchase_amt', 0);
                LibSession::set('package_desc', 'Discount Pack');
                LibSession::set('productid', $productArray['id']);
                LibSession::set('productreleaseid', $productArray['productreleaseid']);
                $productLookupId = User::addLookupEntry($userArray, $subdom, $userId);

                // Add to streamsend starts
                $userDetailsstreamsend = array("Email" => $userArray['user_email'],
                    "FirstName" => $userArray['user_name'],
                    "LasttName" => $userArray['user_name']
                );
                Streamsendlogic::addUserToStreamsend($userDetailsstreamsend);
                // Add to streamsend ends
                //------------Add user to supportdesk-----------------//
               /* include_once(BASE_PATH . "project/support/api/useradd.php");
                userAdd($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);*/

                User::$dbObj = new Db();

                 User::createSupportuser($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);

                //-----------Set supportdesk session----------//
                $sptbl_user = mysqli_query($connection,"SELECT * FROM sptbl_users WHERE vEmail = '{$userArray['user_email']}'");
                if (mysqli_num_rows($sptbl_user) > 0) {
                    $sptbl_res = mysqli_fetch_array($sptbl_user);

                    $_SESSION["sess_username"] = $sptbl_res['vUserName'];
                    $_SESSION["sess_userid"] = $sptbl_res['nUserId'];
                    $_SESSION["sess_useremail"] = $sptbl_res['vEmail'];
                    $_SESSION["sess_userfullname"] = $sptbl_res['vUserName'];
                    $_SESSION["sess_usercompid"] = 1;
                }
            }
        } else {
            $userId = LibSession::get('userID');
            if ($userId != "") {
//                User::sendMail($userArray);
                /*
                 * Session value setup
                 */
                //***********************NOTE*********************//
                /*
                 * Planid,plan package and package description  are not using now. It can be added later.
                 */
                //***********************NOTE*********************//
                LibSession::set('reg_usr_id', $userId);
                LibSession::set('planid', 1);
                LibSession::set('planpackage', 1);
                LibSession::set('purchase_amt', 0);
                LibSession::set('package_desc', 'Discount Pack');
                LibSession::set('productid', $productArray['id']);
                LibSession::set('productreleaseid', $productArray['productreleaseid']);
                $productLookupId = User::addLookupEntry($userArray, $subdom, $userId);
            }
        }

        return $productLookupId;
        die;
    }

    public function installscript() {
        set_time_limit(0);
        PageContext::includePath('cpanel');
        $cpanelObj = new cpanel();
        $dbArray = array();
        $productInstallPath = '/home/cloudisc/public_html/test123/';
        $ftpUser = "cloudisc";
        $ftpPassword = "zklxna8aa5ON";
        $argsFTP = array(
            'ftp_user' => $ftpUser,
            'ftp_pass' => $ftpPassword,
            'subdomain' => 'test123',
        );


        $dbArray = array(
            'db_name' => 'cloudisc_test123',
            'db_user' => 'cloudisc_test123',
            'db_password' => 'test123',
        );

        $dbArray = array_merge($dbArray, $argsFTP);
        $cpanelObj->test($dbArray, $productInstallPath);
        exit;
    }

    public function product($productName) {
        PageContext::addJsVar('loginSuccess', BASE_URL . "dashboard/");
        PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addPostAction('cloudfooter');
        $this->view->setLayout("product");
    }

    public function trynow($productId='') {
        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        PageContext::addJsVar('loginSuccess', BASE_URL . "index/buy/");
        PageContext::addStyle("global.css");
        PageContext::addJsVar("createAccount", BASE_URL . "index/createaccount/");
        PageContext::addScript("jquery-asProgress.js");
        PageContext::addScript("userlogin.js");
        PageContext::addJsVar("checkeAccount", BASE_URL . "index/checkaccount/");
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");
        $sessionObj = new LibSession();
        $userID = $sessionObj->get('userID');


        /* Initial settings */
        $setFlag = 0;
        $validateFlag = false;
        $errMsg = NULL;
        /* Initial settings End */

        if (!empty($userID)) {
            $this->view->userLogged = true;

            if ($this->post('txtStoreName') != '') {
                $validateFlag = true;
            }
        } else {
            if ($this->post('txtStoreName') != "" && $this->post('txtEmail') != "" && $this->post('txtPassword') != "") {
                $validateFlag = true;
            }
        }

        /* Re - Captcha validation credentials */
        PageContext::includePath('recaptcha');
        User::$dbObj = new Db();
        PageContext::$response->recaptcha_enable = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_enable'");
        $recaptcha_public_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_public_key'");
        $recaptcha_private_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_private_key'");

        $recaptchaValid = 0;

        /* Re - Captcha validation credentials end */


        if ($validateFlag == true) {
            $this->view->txtStoreName = $this->post('txtStoreName');
            $this->view->txtEmail = $this->post('txtEmail');
            $this->view->txtPassword = $this->post('txtPassword');
            $this->view->txtName = substr($this->view->txtEmail, 0, strpos($this->post('txtEmail'), "@") - 1);

            if (PageContext::$response->recaptcha_enable == 'Y') {
                $recaptcha_challenge_field = $this->post('recaptcha_challenge_field');
                $recaptcha_response_field = $this->post('recaptcha_response_field');

                $resp = recaptcha_check_answer($recaptcha_private_key, $_SERVER["REMOTE_ADDR"], $recaptcha_challenge_field, $recaptcha_response_field);
                $captchaError = $resp->error;

                if (!empty($captchaError)) {
                    $errMsg = '<div class="flashmsg">
                            <h2>Oops! Invalid security code. Please try again!</h2>
                          </div>';
                } else {
                    $recaptchaValid = 1;
                }

            }

        } else {

            $errMsg = '<div class="flashmsg">
                    <h2>Oops! Something went wrong.</h2>
                  </div>';
        }

        if (empty($errMsg)) {
            $setFlag = 1;
        }


//        echo $this->view->txtStoreName."-".$this->view->txtEmail."-".$this->view->txtPassword."-".$this->view->txtName;
        $sessionObj->set("freeTrialFlag", $setFlag);
        $sessionObj->set("recaptchaValid", $recaptchaValid);
        $this->view->setFlag = $setFlag;
        $this->view->errMsg = $errMsg;
        $this->view->productid = $productId;
        $this->view->productname = User::getproductName($productId);
        PageContext::$response->freePlanPeriod = Admincomponents::getFreePlanPeriod();
    }

    /*
     * Function to check the availability of subdomain
     */

    public function checkaccount() {
        if ($this->post('storeName') != '') {
            $storeName = $this->post('storeName');
            if (User::checkSubdomain($storeName) > 0) {
                $message = "Site Name Not Available";
                $data = array('faild' => 1, 'list' => $message);
                echo json_encode($data);
                exit;
            } else {
                $message = "Site Name Available";
                $data = array('success' => 1, 'list' => $message);
                echo json_encode($data);
                exit;
            }
        } else {
            $message = "Please enter a site name";
            $message = "";
            $data = array('faild' => 1, 'list' => $message);
            echo json_encode($data);
            exit;
        }
    }

    /*
     * Function to check login validation
     */





    public function userlogin() {
$connection = new Db();
//print_r($connection);exit;
        if ($this->post('username')) {

            $userName = $this->post('username');
            $password = $this->post('password');
            if ($userName != "" && $password != "") {

                $status = User::validateLogin($userName, md5($password));

                if ($status < 0) {
                    $message = "Your account is no longer active!!!";
                    $data = array('faild' => 1, 'message' => $message);
                    echo json_encode($data);
                    exit;
                } else if ($status == true) {
                    $message = "Login Success";
                    $data = array('success' => 1, 'message' => $message);

                    //-----------Set supportdesk session----------//
                   // echo "SELECT * FROM sptbl_users WHERE vEmail = '{$userName}'";exit;

                    $sptbl_user = User::getUserFromSupport($userName);
                   // $sptbl_user = mysqli_query($connection,"SELECT * FROM sptbl_users WHERE vEmail = '{$userName}'");
                    if ($sptbl_user[0]->nUserId > 0) {
                       // $sptbl_res = mysqli_fetch_array($sptbl_user);

                        $_SESSION["sess_username"] = $sptbl_user[0]->vUserName;
                        $_SESSION["sess_userid"] = $sptbl_user[0]->nUserId;
                        $_SESSION["sess_useremail"] = $sptbl_user[0]->vEmail;
                        $_SESSION["sess_userfullname"] = $sptbl_user[0]->vUserName;
                        $_SESSION["sess_usercompid"] = 1;
                    }
                    echo json_encode($data);
                    exit;
                } else {
                    $message = "Invalid login details!!!";
                    $data = array('faild' => 1, 'message' => $message);
                    echo json_encode($data);
                    exit;
                }
            }
        } else {
            $message = "Invalid login details!!!";
            $data = array('faild' => 1, 'message' => $message);
            echo json_encode($data);
            exit;
        }
    }

    //Function to store name and email of
    public function createnewsletter() {
        if ($this->post('Name') != '') {
            $userName = $this->post('Name');
            $userEmail = $this->post('Email');
            $status = User::createNewsLetter($userName, $userEmail);
            if ($status == 1) {
                //success
                $message = "You have succesfully subscribed to newsletters";
                $data = array('success' => 1, 'list' => $message);
            } elseif ($status == 0) {
                //success
                $message = "You have already subscribed!!!";
                $data = array('failed' => 1, 'list' => $message);
            } else {
                $message = "Please try again!!!";
                $data = array('failed' => 1, 'list' => $message);
            }
        }

        echo json_encode($data);
    }

    /*
     * Function to load pay option
     */

    public function paynowredirect()
    {
         $sessionObj = new LibSession();

         $userDetails = $sessionObj->get('userDetails');

         $userID = $sessionObj->get('userID');


         $planId = ($_GET['plan_id'])?$_GET['plan_id']:base64_encode(base64_encode($sessionObj->get("plan_id")));
         $templateId = ($_GET['template_id'])?$_GET['template_id']:base64_encode(base64_encode($sessionObj->get("template_id")));


         if($planId)
         $sessionObj->set("plan_id", base64_decode(base64_decode($planId)));
         if($templateId)
         $sessionObj->set("template_id", base64_decode(base64_decode($templateId)));







        if($planId && $templateId){
            if($userID || $userDetails)
            {
                 
            $this->redirect("paynow?plan_id=$planId&template_id=$templateId");
            }else{
               
                $this->redirect("signin");
            }
        }
        if($planId && !$templateId){
            $this->redirect("templates?plan_id=$planId");
        }
        if(!$planId && $templateId){
            $this->redirect("plan?template_id=$templateId");
        }

         //$this->redirect("paynow?plan_id=$planId&template_id=$templateId");

    }


    public function paynow(){

//Configure::write('debug', 2);


        $sessionObj = new LibSession();
        $userDetails = $sessionObj->get('userDetails');
        //echopre($userDetails);
        PageContext::$response->firstName = $userDetails['firstName'];
        PageContext::$response->lastName = $userDetails['lastName'];
        PageContext::$response->emailAddress = $userDetails['emailAddress'];
        PageContext::$response->password = $userDetails['password'];
        PageContext::$response->phone = ($userDetails['phone'])?$userDetails['phone']:'';
         PageContext::$response->userLogged = false;


            Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");
        //$this->view->footerType = 'limited';



        $userID = $sessionObj->get('userID');

       // echopre($userDetails);exit;

        if(!$userDetails)
        {
            if(!$userID){
             $this->redirect("signin");
            }
        }


        if (!empty($userID)) {
            PageContext::$response->userLogged = true;
        }




        //echopre($userDetails);

        $planId = base64_decode(base64_decode($_GET['plan_id']));
        $templateId = base64_decode(base64_decode($_GET['template_id']));

        if(empty($planId)){
            $this->redirect('plan');
        }

        if (Admincomponents::getPlanStatus($planId) == 0){
            $this->redirect('plan');
        }
$planDetails = User::getPlanDetails($planId);
if($planDetails[0]->vType=='free')
{
    PageContext::addScript("userlogin.js");
}



        //TODO: add option to assign the theme dynamicaly

        PageContext::addStyle("https://fonts.googleapis.com/css?family=Lato");
        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");
        //$this->view->setLayout("product");
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addStyle('userproduct.css');
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addJsVar("checkeAccount", BASE_URL . "index/checkaccount/");
        PageContext::addJsVar("loadServicesUrl", BASE_URL . "index/services/");
        PageContext::addJsVar("checkeDomainAvailability", BASE_URL . "index/checkdomainstatus/");
        PageContext::addJsVar("ajaxGetPlanDetails", BASE_URL . "index/ajaxGetPlanDetails/");
        PageContext::addJsVar("registerDomain", BASE_URL . "index/registerdomain/");
        PageContext::addStyle("payment_newstyle.css");
        // Tab style and Js
        PageContext::addScript('tabbed.js');
        PageContext::addStyle('tabbed-content.css');

        PageContext::addPostAction('cloudlimitedfooter');

        Admincomponents::$dbObj = new Db();
        PageContext::$response->domainregistration_enable = Admincomponents::$dbObj->selectRow("Settings", "value", "settingfield='enableDomiainRegistration'");

        if (LibSession::get('userID')) {
            $this->view->userDetails = User::fetchUserProfile();
            //echopre($this->view->userDetails);
        }


        $this->view->planId = $planId;
        PageContext::$response->type = $planDetails[0]->vType;
        //echo PageContext::$response->type;
        $this->view->planName = $planDetails[0]->vServiceName;
        $this->view->planPrice = $planDetails[0]->price;
        $this->view->vBillingInterval = $planDetails[0]->vBillingInterval;
        $domainRegDetails = User::getDomainRegDetails($planId);
        $this->view->domainRegPrice = $domainRegDetails[0]->value;
        PageContext::addPostAction('plansnippet');

        // Akhil payment integration
        PageContext::addScript('validatepayment.js');
        $paymentsEnabled = Payments::getEnabledPaymnets();
         //echopre($paymentsEnabled);exit;
        if (isset($paymentsEnabled['authorize_enable']) && $paymentsEnabled['authorize_enable'] == 'Y') {
            PageContext::addPostAction('authorize', 'payments');
            PageContext::addPostAction('authorizedomain', 'payments');
        }

        if (isset($paymentsEnabled['bluedog_enable']) && $paymentsEnabled['bluedog_enable'] == 'Y') {
            PageContext::addPostAction('bluedog', 'payments');
            PageContext::addPostAction('bluedogdomain', 'payments');
        }

        if (isset($paymentsEnabled['paypalpro_enable']) && $paymentsEnabled['paypalpro_enable'] == 'Y') {
            $currentCountry = 'US';

            PageContext::$response->creditcard = Payments::getCreditCardPaypalpro($currentCountry);
            PageContext::addPostAction('paypalpro', 'payments');
            PageContext::addPostAction('paypalprodomain', 'payments');
        }

        if (isset($paymentsEnabled['paypalflow_enable']) && $paymentsEnabled['paypalflow_enable'] == 'Y') {

            PageContext::addPostAction('paypalflow', 'payments');
            PageContext::addPostAction('paypalflowdomain', 'payments');
        }

        if (isset($paymentsEnabled['yourpay_enable']) && $paymentsEnabled['yourpay_enable'] == 'Y') {

            PageContext::addPostAction('yourpay', 'payments');
        }

        if (isset($paymentsEnabled['quickbook_enable']) && $paymentsEnabled['quickbook_enable'] == 'Y') {

            PageContext::addPostAction('quickbook', 'payments');
            PageContext::addPostAction('quickbookdomain', 'payments');
        }

        if (isset($paymentsEnabled['stripe_enable']) && $paymentsEnabled['stripe_enable'] == 'Y') {

          $testmode = Admincomponents::$dbObj->selectRow("Settings", "value", "settingfield='stripe_test_mode'");
            $stripesettings = Payments::getStripeSettings($testmode);
            PageContext::$response->stripesettings = $stripesettings;

            //echopre($stripesettings);exit; 

            //token creation



            $stripetoken = self::SetStripeToken($stripesettings['SecretKey'],$this->view->userDetails->vEmail,$this->view->planName,$this->view->planPrice,$stripesettings['WebhookURL']);

            
            PageContext::$response->stripetoken = $stripetoken;

            
        }

        //echopre($stripesettings);exit; 

        //getStripeSettings



        PageContext::$response->paymnetsEnabled = $paymentsEnabled;

        // Akhil Payment ends
    }


    public function SetStripeToken($secretKey,$email,$planname,$planprice,$webhookurl)
    {


//echo $$planprice;exit;
        require_once('project/lib/stripe/init.php');
        $amount=$planprice*100;

try {
        \Stripe\Stripe::setApiKey($secretKey);
$session =\Stripe\Checkout\Session::create([
  "success_url" => BASE_URL."index/stripepayment/",
  "cancel_url" => BASE_URL."index/cancel/",
  "payment_method_types" => ["card"],
  "customer_email"=> $email,
  "client_reference_id"=>LibSession::get('userID'),
  "line_items" => [[
    "name" =>  "$planname",
    "description" => $planname,
    "amount" => round($amount),
    "currency" => "usd",
    "quantity" => 1
  ]]
]);
}catch(Exception $e) {
  //echo 'Message: ' .$e->getMessage();
}
//exit;
//echopre($session);exit;
return $session->id;

    }

     public function SetUpgradeStripeToken()
    {


//echopre($_POST);exit;
        $secretKey=$_POST['key'];
        $email=$_POST['email'];
        $planname=$_POST['plan'];
        $planprice=$_POST['price'];

//echo $$planprice;exit;
        require_once('project/lib/stripe/init.php');
        $amount=$planprice*100;

try {
        \Stripe\Stripe::setApiKey($secretKey);
$session =\Stripe\Checkout\Session::create([
  "success_url" => BASE_URL."index/stripepayment/",
  "cancel_url" => BASE_URL."index/cancel/",
  "payment_method_types" => ["card"],
  "customer_email"=> $email,
  "client_reference_id"=>LibSession::get('userID'),
  "line_items" => [[
    "name" =>  "$planname",
    "description" => $planname,
    "amount" => round($amount),
    "currency" => "usd",
    "quantity" => 1
  ]]
]);
}catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
//exit;

echo $session->id;exit;


    }   

    /*
     * Function to upgrade the plan
     */

    public function upgrade($lookupid) {

        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        //TODO: add option to assign the theme dynamicaly

        $sessionObj = new LibSession();

//echopre($userDetails);
$userID = $sessionObj->get('userID');

if(!$userID)
{
     $this->redirect('index');
}else{

    PageContext::$response->userLogged = true;
}



        PageContext::addJsVar('payNow', BASE_URL . "index/creditcardbuy/");

        PageContext::addJsVar('otherpaymanturl', BASE_URL . "index/otherpaymantbuy/");
        PageContext::addStyle("https://fonts.googleapis.com/css?family=Lato");
        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");
        //$this->view->setLayout("product");
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addScript('upgrade.js');
        PageContext::addStyle('userproduct.css');
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addJsVar("checkeAccount", BASE_URL . "index/checkaccount/");
        PageContext::addJsVar("loadServicesUrl", BASE_URL . "index/services/");
        PageContext::addJsVar("checkeDomainAvailability", BASE_URL . "index/checkdomainstatus/");
        PageContext::addJsVar("ajaxGetPlanDetails", BASE_URL . "index/ajaxGetPlanDetails/");
        PageContext::addJsVar("registerDomain", BASE_URL . "index/registerdomain/");
        PageContext::addStyle("payment_newstyle.css");
        // Tab style and Js
        PageContext::addScript('tabbed.js');
        PageContext::addStyle('tabbed-content.css');
        $this->view->lookupid = $lookupid;
        PageContext::addPostAction('cloudlimitedfooter');

        Admincomponents::$dbObj = new Db();
        PageContext::$response->domainregistration_enable = Admincomponents::$dbObj->selectRow("Settings", "value", "settingfield='enableDomiainRegistration'");

        if (LibSession::get('userID')) {
            $this->view->cardDetails = User::fetchUserCreditCardDetails();
        }
        
        $planDetails = User::getPlanDetails($planId);

        //echo $planId;exit;
        $this->view->planId = $planId;
        $this->view->planName = $planDetails[0]->vServiceName;
        $this->view->planPrice = $planDetails[0]->price;
        $this->view->vBillingInterval = $planDetails[0]->vBillingInterval;

        PageContext::addPostAction('plansnippet');

        // Akhil payment integration
        PageContext::addScript('validatepayment.js');
        $paymentsEnabled = Payments::getEnabledPaymnets();
        // echopre($paymentsEnabled);
        if (isset($paymentsEnabled['authorize_enable']) && $paymentsEnabled['authorize_enable'] == 'Y') {
            PageContext::addPostAction('authorize', 'payments');
            PageContext::addPostAction('authorizedomain', 'payments');
        }

        if (isset($paymentsEnabled['bluedog_enable']) && $paymentsEnabled['bluedog_enable'] == 'Y') {
            PageContext::addPostAction('bluedog', 'payments');
            PageContext::addPostAction('bluedogdomain', 'payments');
        }


        if (isset($paymentsEnabled['paypalpro_enable']) && $paymentsEnabled['paypalpro_enable'] == 'Y') {
            $currentCountry = 'US';

            PageContext::$response->creditcard = Payments::getCreditCardPaypalpro($currentCountry);
            PageContext::addPostAction('paypalpro', 'payments');
            PageContext::addPostAction('paypalprodomain', 'payments');
        }

        if (isset($paymentsEnabled['paypalflow_enable']) && $paymentsEnabled['paypalflow_enable'] == 'Y') {

            PageContext::addPostAction('paypalflow', 'payments');
            PageContext::addPostAction('paypalflowdomain', 'payments');
        }

        if (isset($paymentsEnabled['yourpay_enable']) && $paymentsEnabled['yourpay_enable'] == 'Y') {

            PageContext::addPostAction('yourpay', 'payments');
        }

        if (isset($paymentsEnabled['quickbook_enable']) && $paymentsEnabled['quickbook_enable'] == 'Y') {

            PageContext::addPostAction('quickbook', 'payments');
            PageContext::addPostAction('quickbookdomain', 'payments');
        }

        if (isset($paymentsEnabled['stripe_enable']) && $paymentsEnabled['stripe_enable'] == 'Y') {

            

            //echopre($payment_details);exit;

           $testmode = Admincomponents::$dbObj->selectRow("Settings", "value", "settingfield='stripe_test_mode'");
             $stripesettings = Payments::getStripeSettings($testmode);
             PageContext::$response->stripesettings = $stripesettings;

             $userDetails=User::fetchUserProfile();

             PageContext::$response->SEmail = $userDetails->vEmail;

            //echopre($stripesettings);exit; 

            //get user email

             LibSession::set('Upgrade',1);

            



            //token creation



            // $stripetoken = self::SetStripeToken($stripesettings['SecretKey'],$userDetails->vEmail,'Gostores',$payment_details[0]->nAmount,$stripesettings['WebhookURL']);

            
            // PageContext::$response->stripetoken = $stripetoken;

            
        }



        PageContext::$response->paymnetsEnabled = $paymentsEnabled;

        PageContext::$response->product_lookupid = $lookupid;

        // Akhil Payment ends
    }

    public function ajaxGetPlanDetails() {
        $planDetails = User::getPlanDetails($_POST['planId']);
        $billing = $planDetails[0]->vBillingInterval == 'L' ? 'Lifetime' : ($planDetails[0]->vBillingInterval == 'Y' ? 'Yearly' : 'Monthly');

        $data = array('planName' => $planDetails[0]->vServiceName, 'planPrice' => $planDetails[0]->price, 'vBillingInterval' => $billing);
        echo json_encode($data);
        exit;
    }

public function stripesuccess()
{

        $sessionObj = new LibSession();
        $userDetails = $sessionObj->get('userDetails');

         $subdom=$sessionObj->get('subdomain');
        //echopre($userDetails);
        
       $upgrade=$sessionObj->get('Upgrade');

       if($upgrade==1)
       {
        PageContext::$response->upgrade = 1;
       }
       else
       {
        PageContext::$response->upgrade = 0;
       }


       $content=LibSession::get('content');
       if($content)
       {
        PageContext::$response->content = $content;
       }
        
        PageContext::$response->subdom = $subdom;
        PageContext::$response->domainName = DOMAIN_NAME;
         PageContext::$response->userLogged = true;

         LibSession::set('payment_method','');


            Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        //echo DOMAIN_NAME;exit;

}
    

public function stripepayment()
{


       
    $stripesettings = Payments::getStripeSettings('Y');

    

  

    $session    =   new LibSession();

    $subdom=$session->get('subdomain');
    $mode=LibSession::get('mode');

     
   

    $authorizeInfo=LibSession::get('authorizeInfo');
    $arrtwoPaySettings=LibSession::get('arrtwoPaySettings');


    $dataArray = LibSession::get('customerDetails');
    $userDataArray = LibSession::get('userDataArray');
    LibSession::set('payment_method','stripe');
    $status['amount']=$authorizeInfo['amount'];
    $status['transactionId']="";
    $status['email']=$authorizeInfo['email'];
    $status['customerDetails']=$dataArray;
    $productLookUpId=LibSession::get('productLookUpId');
    $productId=LibSession::get('productid');
    $domainFlag=LibSession::get('domainFlag');
    $userId=LibSession::get('userID');
    $userArray = LibSession::get('userArray');
    $upgradeFlag=LibSession::get('upgradeFlag');
    $couponNo=LibSession::get('couponNo');
    $serCat=LibSession::get('serCat');
    



    
    

    

    if($mode=='subdomain')
    {

           $status['customerDetails']=$authorizeInfo;
           $status['amount']=$authorizeInfo['amount'];
           $status['transactionId']="";
           $status['email']=$authorizeInfo['email'];
           $status['customerDetails']=$dataArray;
           $customerarray = array();
            if(isset($status['customerDetails'])){
            $customerarray = $status['customerDetails'];
            }
    
    $description = 'Subdomain Purchase'.'###'.$status['email'];
    if ($upgradeFlag == 1) {
        $description = 'Subdomain Upgrade'.'###'.$status['email'];
    }  

        if ($upgradeFlag == 1) {
                $subdom = User::getSubDomainName($productLookUpId);
                $session->set('subdomain',$subdom);

                //echopre($subdom);exit;
            }

            
 $productArray=LibSession::get('productArray');

$response=$this->createaccountafterpayment($userArray, $subdom, $userId, $productArray, $status, $upgradeFlag, $productLookUpId,$customerarray);       

LibSession::set('content',"");

    }

else if($mode=='registerdomain')
            {

                

                

        $siteOperationParkDomain    = OPERATION_MODE_PARK_DOMAIN;
        $RegistrantFirstName        = $userDataArray['vFirstName'];
        $RegistrantLastName         = $userDataArray['vLastName'];
        $RegistrantJobTitle         = $userDataArray['VjobTitle'];
        $RegistrantOrganizationName = $userDataArray['VOrgan'];
        $RegistrantAddress1         = $userDataArray['vAddress'];
        $RegistrantAddress2         = $userDataArray['Vaddress2'];
        $RegistrantCity             = $userDataArray['vCity'];
        $RegistrantState            = $userDataArray['vState'];
        $RegistrantPhone            = $userDataArray['VPhone'];

        $RegistrantProvince         = $userDataArray['VProvince'];
        $RegistrantPostalCode       = $userDataArray['vZipcode'];
        $idRegistrantCountry        = $userDataArray['vCountry'];

        $RegistrantEmailAddress     = $userDataArray['vEmail'];
        $RegistrantFax     = $userDataArray['VFax'];

        $idsld              = $userDataArray['idsld'];
        $tld                = $userDataArray['tld'];
        $NumYears           = $userDataArray['NumYears'];
        $UnLockRegistrar    = $userDataArray['UnLockRegistrar'];
        $domainName         = $userDataArray['domainName'];

        
 //echo '$domainName='.$domainName;
        // $productId                          = $productId;
        // $productArray['id']                 = $productId;
        // $productArray['packname']           = User::getproductPackName($productId);
        // $productArray['permissionlist']     = User::getproductPermission($productId);
        // $productArray['productreleaseid']   = User::getproductReleaseID($productId);

        // /**************************** Product Services ***************************/
        // $productServices        = $productId;
        // $productSerArr          = array();
        // if (!empty($productServices)) {
        //     $productSerArr = explode(",", $productServices);
        // }
        // $purchaseServiceId = $productId;
        // $productArray['productServices'] = $productSerArr;
        // $productArray['planProductRestriction'] = User::getPlanproductRestriction($productId);
        // $productArray['productAccessKey'] = md5(SECRET_SALT.$domainName);
        
        // $productArray['xmlproductdata']         = User::setXmlData($productArray['planProductRestriction'], md5($domainName));
        // $productArray['couponNo'] = $couponNo;
        // //$productArray['couponNo']           = $this->post('couponNo');

        $productArray=LibSession::get('productArray');

        

        

        
       //echopre($productArray);

if($productLookUpId){
$storeArray = array();
$storeArray['subscribed_planid'] = $productId;
$this->updatePlanStore($productLookUpId,$storeArray); 
}

if ($domainFlag == 1){


   


    $messageArray = User::registerdomain($RegistrantFirstName, $RegistrantLastName, $RegistrantJobTitle, $RegistrantOrganizationName, $RegistrantAddress2, $RegistrantCity, $RegistrantState, $RegistrantProvince, $RegistrantPostalCode, $idRegistrantCountry, $RegistrantFax, $RegistrantPhone, $RegistrantEmailAddress, $idsld, $tld, $NumYears, $RegistrantAddress1, $UnLockRegistrar);
                    //echo "Message array = <pre>"; print_r($messageArray); echo "</pre>";exit;

                    if ($messageArray['status'] == 1){
                        $domainRegisterFlag = 1;
                    }else{
                        $contents   = "Domain registration failed";
                        $data       = array('failed' => 1, 'list' => $contents);
                        $response = json_encode($data);
                        //echo json_encode($data);
                        //die;
                    }
                }else{
                    $domainRegisterFlag = 1;
                }
    if($domainRegisterFlag == 1){
        
        
       // echo '$domainName='.$domainName;
        
        
                    PageContext::includePath('cpanel');
                    $cpanelObj  = new cpanel();
                    $username   = substr(strtolower($RegistrantFirstName), 0, 3);
                    $username   = $username . substr(md5($RegistrantFirstName . time()), 0, 3);
                    $password   = substr(md5($RegistrantFirstName), 0, 12);
                    $dataArr    = array();
                    if ($upgradeFlag == 0){
                        $statusArray = $cpanelObj->createcpanelaccount($username, $password, $domainName, $RegistrantEmailAddress, $productArray);


                        //echopre($statusArray);
                        //echo "check0";



                        Utils::reconnect();
                        if ($statusArray['status'] == 1){
                            //Additional account details values for User Array
                            $userArray["c_user"]        = $username;
                            $userArray["c_pass"]        = $statusArray['c_pass'];
                            $userArray["c_host"]        = $domainName;
                            $userArray["sld"]           = $idsld;
                            $userArray["tld"]           = $tld;
                            $userArray["tempdispurl"]   = "";
                            $userArray["customerDetails"]           = $userDataArray;
                            
                            /*********** Store Database Credentials ***********/
                            $userArray['db_name'] = $statusArray['db_name'];
                            $userArray['db_user'] = $statusArray['db_user'];
                            $userArray['db_password'] = $statusArray['db_password'];
                            
                            if ($siteOperationParkDomain == 'Y'){
                                if (isset($statusArray['tempdispurl']) && !empty($statusArray['tempdispurl'])) {
                                    $userArray["tempdispurl"] = $statusArray['tempdispurl'];
                                }
                            }
                            $plId = User::addLookupEntry($userArray, $domainName, $userId,1);
                            //echo $plId;
                            if($plId)
                            {
                                $pLookupdata = User::getProductDetails($plId);
                                $cpanelObj->createCpanelCronjob($pLookupdata);
                            }
                            if (LibSession::get('mailSendFlag') == 1){
                                User::sendMail($userArray);
                                LibSession::set('mailSendFlag', 0);
                            }else{
                                LibSession::set('userID', $userId);
                            }
                        }else{
                            //Failed
                            if (isset($statusArray['tech_statusmsg']) && trim($statusArray['tech_statusmsg']) <> '') {
                                $contents = $statusArray['tech_statusmsg'];
                            }else{
                                $contents = "Account setup failed. Please be patient our customer care agent will fix the issue and inform you.";
                            }
                            $data = array('failed' => 1, 'list' => $contents);
                            
                            $response = json_encode($data);
                            //echo json_encode($data);
                            //die;
                        }                        
                        
                        }

                        else
                        {
                            //If Upgrading the account
                        $productArray['domain'] = $domainName;
                        $plId = $productLookUpId;
                        $accountDetails = unserialize(User::getserverDetails($plId));

                        //Additional account details values for User Array
                        $userArray["c_user"]    = $accountDetails['c_user'];
                        $userArray["c_pass"]    = $accountDetails['c_pass'];
                        $userArray["c_host"]    = $domainName;
                        $userArray["sld"]       = $idsld;
                        $userArray["tld"]       = $tld;
                        $userArray["tempdispurl"] = "";
                        $userArray['customerDetails']=$userDataArray;
                        //$userArray["customerDetails"]           = $customerarray;


                        $statusArray    = $cpanelObj->upgradeaccount($accountDetails['c_user'], $accountDetails['c_pass'], $accountDetails['c_host'], $RegistrantEmailAddress, $productArray);
                        $upgrade        = 1;
                        if(trim($statusArray["status"]) == 1){


                            User::updateLookupEntry($plId, $domainName, $userId, $userArray);
                            
                            
                        }

                        Utils::reconnect();
                        //Get Temporrary URL for UserArray
                        if ($siteOperationParkDomain == 'Y'){
                            if (isset($statusArray['tempdispurl']) && !empty($statusArray['tempdispurl'])) {
                                $userArray["tempdispurl"] = $statusArray['tempdispurl'];
                            }
                        } // End if
                        
                        if($plId)
                            {
                                $pLookupdata = User::getProductDetails($plId);
                                $cpanelObj->createCpanelCronjob($pLookupdata);
                            }
                        
                        }


                        //echopre($userArray);

                        $productSetUpServiceId  = User::productsetUpId(PRODUCT_PURCHASE_CATEGORY, $productId);
                    $productRestriction     = User::getPlanproductRestriction($productSetUpServiceId);

                    //Tld Unit Price
                    $tldUnitPrice   = Utils::formatPrice($tldPrice / $NumYears);
                    $dataArr        = array(
                                            'nUId'              => $userId,
                                            'nPLId'             => $plId,
                                            'services'          => $productArray['productServices'],
                                            'domainService'     => array('nSCatId' => DOMAIN_REGISTRATION_ID, 'appendDescription' => '', 'rate' => $tldUnitPrice, 'year' => $NumYears),
                                            'couponNo'          => $productArray['couponNo'],
                                            'terms'             => '',
                                            'notes'             => '',
                                            'paymentstatus'     => 'paid',
                                            'vMethod'           => $status['paymentMethod'],
                                            'vTxnId'            => $status['transactionId'],
                                            'upgrade'           => $upgradeFlag,
                                            'subscriptionType'  => 'PAID'
                                        );
                    Utils::reconnect();
                    Admincomponents::generateInvoice($dataArr);

                    User::$dbObj = new Db();
                    //Name Server Details
                    $ns1 = User::$dbObj->selectRow("Settings", "value", "settingfield='name_server_1'");
                    $ns2 = User::$dbObj->selectRow("Settings", "value", "settingfield='name_server_2'");



                    //success
                    $contents = "Congratulations! Your installation was successful!<br>
                                Site Login Details<br>
                                Admin URL : <a href='" . $statusArray['returnurl'] . "/admins/' target='_blank'>" . $statusArray['returnurl'] . "admins/</a><br>
                                Admin Credentials : Username : admin<br>
                                Password : admin<br>
                                Home URL :  <a href='" . $statusArray['returnurl'] . "index.php' target='_blank'>" . $statusArray['returnurl'] . "</a><br>";

    if ($domainFlag == 0) {
                        $contents = '<div class="col-md-8 col-md-offset-2"><div class="storecration_instalation_wrapper" style="">
                                        <div class="storecration_instalation_wrapper_inner">
                                            <div class="instalation_completed_img "></div>
                                             <div class="pymnt_sucessmsgs" style="text-align:center;">
                                             <div class="store_success">
                                                <div class="store_success_label"></div>
                                                <h2>Congratulations!</h2>';
                        if ($upgradeFlag == 1) {
                            $dName = $idsld . '.' . $tld;

                            
                            User::updateLookupEntry($plId, $dName, $userId, $userArray);
                            if($plId)
                            {
                                $pLookupdata = User::getProductDetails($plId);
                                $cpanelObj->createCpanelCronjob($pLookupdata);
                                
                                
                                
                                
                            }
                            
                            
                            
                            
                            
                            $contents.='<h3>The Upgrade Process was completed successfully!</h3>';
                        } else {
                            $contents.='<h3>Your installation was successful!</h3>';
                        }

$contents.='<p class="head">Site Login Details</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Admin URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'admins/"  target="_blank">' . $statusArray['returnurl'] . 'admin/</a></td>
            </tr>
            <tr>
            <td align="left" valign="top">Home URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'index.php"  target="_blank">' . $statusArray['returnurl'] . '</a></td>
            </tr>
            </table>';

 if ($siteOperationParkDomain == 'Y') {
$contents.='<p class="head">Temporary Login Details</p>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                        <td align="left" valign="top" width="20%">Admin URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'admins/"  target="_blank">' . $statusArray['tempdispurl'] . 'admin/</a></td>
                                        </tr>
                                        <tr>
                                        <td align="left" valign="top">Home URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'index.php"  target="_blank">' . $statusArray['tempdispurl'] . '</a></td>
                                        </tr>
                                        </table>';
                        }

$contents .='<p class="head">Admin Credentials</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Username</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            <tr>
            <td align="left" valign="top" >Password</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>';

$contentsTemp = "";
                        if($siteOperationParkDomain == 'Y'){
                            $contentsTemp = "In the mean time use the above temporary url to manage the site from the admin panel.";
                        }
                        $contents.='<tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: It may take 24 - 48 hrs for the domain to propagate to the site.
                                            ' . $contentsTemp . '
                                        </td>
                                    </tr>';

                        $contents .='</table>
                            <p class="head">Nameserver Details</p>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr>
                                        <td align="left" valign="top" width="20%">NameServer1</td>
                                        <td align="left" valign="top">:&nbsp;' .$ns1.'</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" >NameServer2</td>
                                        <td align="left" valign="top">:&nbsp;' .$ns2.'</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: Please update your domain nameserver details.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div></div></div>';







                    }

else 

{
    $productSetUpServiceId = User::productsetUpId(PRODUCT_PURCHASE_CATEGORY, $productId);
                        $dataArr = array();

                        if ($upgradeFlag == 1) {
                            $plId = $productLookUpId;
                            
                           
                            
                        }

                        $productRestriction = User::getPlanproductRestriction($productSetUpServiceId);

                        Utils::reconnect();

$contents = '<div class="storecration_instalation_wrapper" style="">
                                                                        <div class="storecration_instalation_wrapper_inner">
                                                                            <div class="instalation_completed_img "></div>

                                                                             <div class="pymnt_sucessmsgs" style="text-align:center;"><div class="store_success">
        <div class="store_success_label"></div>
            <h2>Congratulations!</h2>';

             if ($upgradeFlag == 1) {
                            $dName = $idsld . '.' . $tld;
                            User::updateLookupEntry($plId, $dName, $userId, $userArray);
                            $contents.='project/modules/default/controller/index.php
            <h3>The Upgrade Process was completed successfully!</h3>';
                        } else {
                            $contents.='
            <h3>Your installation was successful!</h3>';
                        }

$contents.='<p class="head">Site Login Details</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Admin URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . '/admins/"  target="_blank">' . $statusArray['returnurl'] . 'admin/</a></td>
            </tr>
            <tr>
            <td align="left" valign="top">Home URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'index.php"  target="_blank">' . $statusArray['returnurl'] . '</a></td>
            </tr>
            </table>';

if ($siteOperationParkDomain == 'Y') {
                            $contents.='<p class="head">Temporary Login Details</p>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                        <td align="left" valign="top" width="20%">Admin URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'admins/"  target="_blank">' . $statusArray['tempdispurl'] . 'admin/</a></td>
                                        </tr>
                                        <tr>
                                        <td align="left" valign="top">Home URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'index.php"  target="_blank">' . $statusArray['tempdispurl'] . '</a></td>
                                        </tr>
                                        </table>';
                        }

 $contents .='<p class="head">Admin Credentials</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Username</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            <tr>
            <td align="left" valign="top" >Password</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>';
            
            $contentsTemp = "";
                        if ($siteOperationParkDomain == 'Y') {
                            $contentsTemp = "In the mean time use the above temporary url to manage the site from the admin panel.";
                        }
                        $contents.='<tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: It may take 24 - 48 hrs for the domain to propagate to the site.
                                            ' . $contentsTemp . '
                                        </td>
                                    </tr>';

                        $contents .='</table>
                            <p class="head">Nameserver Details</p>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="left" valign="top" width="20%">NameServer1</td>
                                    <td align="left" valign="top">:&nbsp;' . $ns1 . '</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" >NameServer2</td>
                                    <td align="left" valign="top">:&nbsp;' . $ns2 . '</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" colspan="2">Note: Please update your domain nameserver details.</td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <div class="clear"></div>
                        </div>';
                       


}



//echo json_encode($data);exit;

$response = array('success' => 1, 'list' => $contents);
$response = json_encode($response);
LibSession::set('content',$contents);

//$this->redirect('index/stripesuccess');
    //exit; 

                        }            



            } 




    
    
//echopre($response);

$res=json_decode($response);

//echopre($res);exit;

// echopre($res);exit;



     
 


  
   

      require_once('project/lib/stripe/init.php');

      \Stripe\Stripe::setApiKey($stripesettings['SecretKey']);
        
        $endpoint_secret = $stripesettings['WebhookSecretKey'];
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

       //echopre($endpoint_secret);exit;

        try {
        $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
         );
          } catch(\UnexpectedValueException $e) {
        // Invalid payload
        http_response_code(400);
        //exit();
         } catch(\Stripe\Error\SignatureVerification $e) {
         // Invalid signature
         http_response_code(400);
         //exit();
        }

        //echo "hiii";exit;

         // Handle the checkout.session.completed event
        if ($event->type == 'checkout.session.completed') {
          $session = $event->data->object;

           $paymentintent=$session->payment_intent;
           $customerID=$session->customer;
           $amount=$session->display_items[0]->amount;
           $userid=$session->client_reference_id;
           $payment_method_type=$session->payment_method_types;
           $email = $session->customer_email;
           $description = 'Subdomain Purchase'.'###'.$email;

           

          //update payment table with transaction and amount



           



           // $customer_id = $res['data']['id'];
           // $payment_method_id = $res['data']['payment_method']['card']['id'];
           // $billing_address_id = $res['data']['billing_address']['id'];
           // $shipping_address_id = $res['data']['shipping_address']['id'];
           // $payment_method_type = $res['data']['payment_method_type'];

           $customerData = array();
           $customerData['customer_id'] = $customerID;
           $customerData['payment_method_id'] = $paymentintent;
           $customerData['payment_method_type'] = $payment_method_type[0];
           $customerData['payment_gateway'] = 'stripe';
           //$customerData['customer_data'] = $event->data->object;


           $customerData=serialize($customerData);
           
           $amount=$amount/100;
           User::storePaymentsEntryStripe($amount,'stripe',$userid,$paymentintent,$description,$customerData);

           //User::UpdateProductLookUp($customerData,$productLookUpId[0]->nPLId);

           echo "success";
           

http_response_code(200);
    exit();
           //$this->redirect('index/viewlisting/' . $listId . '/1/2/');
           //exit;
           
           //User::UpdateProductLookUp($customerData,$productLookUpId); 

        // Fulfill the purchase...

        //handle_checkout_session($session);
        }

       
        //LibSession::set('Upgrade',1);



  if($res->success==1)
{




           $productLookUpId=User::GetProductLookupID($userId);

           

           $addressdetails = unserialize($productLookUpId[0]->bluedogdetails);

           

           $customerData['customer_data']['billing_address']['first_name'] = $addressdetails['vFirstName'];
           $customerData['customer_data']['billing_address']['last_name'] = $addressdetails['vLastName'];
           $customerData['customer_data']['billing_address']['address_line_1'] = $addressdetails['vAddress'];
           $customerData['customer_data']['billing_address']['city'] = $addressdetails['vCity'];
           $customerData['customer_data']['billing_address']['state'] = $addressdetails['vState'];
           $customerData['customer_data']['billing_address']['postal_code'] = $addressdetails['vZipcode'];
           $customerData['customer_data']['billing_address']['country'] = $addressdetails['vCountry'];
           $customerData['customer_data']['billing_address']['email'] = $addressdetails['vEmail'];
           $customerData['customer_data']['billing_address']['phone'] = $addressdetails['vPhone'];
           $customerData['customer_data']['billing_address']['fax'] = $addressdetails['VFax'];
           

           $customerData['customer_data']['shipping_address']['first_name'] = $addressdetails['vFirstName'];
           $customerData['customer_data']['shipping_address']['last_name'] = $addressdetails['vLastName'];
           $customerData['customer_data']['shipping_address']['address_line_1'] = $addressdetails['vAddress'];
           $customerData['customer_data']['shipping_address']['city'] = $addressdetails['vCity'];
           $customerData['customer_data']['shipping_address']['state'] = $addressdetails['vState'];
           $customerData['customer_data']['shipping_address']['postal_code'] = $addressdetails['vZipcode'];
           $customerData['customer_data']['shipping_address']['country'] = $addressdetails['vCountry'];
           $customerData['customer_data']['shipping_address']['email'] = $addressdetails['vEmail'];
           $customerData['customer_data']['shipping_address']['phone'] = $addressdetails['vPhone'];
           $customerData['customer_data']['shipping_address']['fax'] = $addressdetails['VFax'];
           

           



    //get payment data

    $webhookdata=Payments::getwebhookData($userId);
    $customerdata=$webhookdata[0]->webhookdata;

    $customerDatas=unserialize($customerdata);
   

    $customerData['customer_id'] = $customerDatas['customer_id'];
    $customerData['payment_method_id'] = $customerDatas['payment_method_id'];
    $customerData['payment_method_type'] = $customerDatas['payment_method_type'];
    $customerData['payment_gateway'] = $customerDatas['payment_gateway'];



    
    
    User::UpdateProductLookUp($customerData,$productLookUpId[0]->nPLId);

    if($mode=='registerdomain')
    {

        $description = 'Domain Purchase'.'###'.$RegistrantEmailAddress;

    if ($upgradeFlag == 1) {
        $description = 'Domain Upgrade'.'###'.$RegistrantEmailAddress;
    }  
       
       User::UpdatePaymentDescription($description,$webhookdata[0]->nPaymentId);
       
    }

    else
    {
           $description = 'Subdomain Purchase'.'###'.$status['email'];

    if ($upgradeFlag == 1) {
        $description = 'Subdomain Upgrade'.'###'.$status['email'];
    }  
       
       User::UpdatePaymentDescription($description,$webhookdata[0]->nPaymentId);

    }

    $this->redirect('index/stripesuccess');
    exit;
    
       

 }
 else
 {
$this->redirect('index/errorpage');
exit;
 }


}    

public function creditcardbuy() {






        $connection = new Db();


        
        
        


        set_time_limit(0);
        $dataArray = array();
        $this->disableLayout();
        $authorizeInfo = array();
        $productArray = array();
        $authorizeInfo['expMonth'] = $this->post('expM');
        $authorizeInfo['expYear'] = $this->post('expY');
        $authorizeInfo['cvv'] = $this->post('cvv');
        $authorizeInfo['ccno'] = $this->post('ccno');
        $authorizeInfo['fName'] = $this->post('fname');
        $authorizeInfo['lName'] = $this->post('lname');
        $authorizeInfo['add1'] = $this->post('add1');
        $authorizeInfo['city'] = $this->post('city');
        $authorizeInfo['state'] = $this->post('state');
        $authorizeInfo['country'] = $this->post('country');
        $authorizeInfo['phone'] = $this->post('phone');



//echopre($authorizeInfo);exit;
        

        // \Stripe\Stripe::setApiKey('sk_test_hN6fUY6SKejBmdVCuVuHSpn300lCPjx1Lu');
        
        // $endpoint_secret = 'whsec_T7yEpwYAMPPjuMkejjBabpJKrZieuGWH';
        // $payload = @file_get_contents('php://input');
        // $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        // $event = null;

        // echopre($sig_header);exit;

        // try {
        // $event = \Stripe\Webhook::constructEvent(
        // $payload, $sig_header, $endpoint_secret
        //  );
        //   } catch(\UnexpectedValueException $e) {
        // // Invalid payload
        // http_response_code(400);
        // exit();
        //  } catch(\Stripe\Error\SignatureVerification $e) {
        //  // Invalid signature
        //  http_response_code(400);
        //  exit();
        // }

        //  // Handle the checkout.session.completed event
        // if ($event->type == 'checkout.session.completed') {
        //   $session = $event->data->object;


        //   echopre($session);exit;
        // // Fulfill the purchase...
        // handle_checkout_session($session);
        // }


        // RegistrantCountry
        $idRegistrantCountry = $authorizeInfo['country'];
        global $usStates;
        $registrantCountry = $usStates[$idRegistrantCountry] == '' ? $idRegistrantCountry : $usStates[$idRegistrantCountry];
        $authorizeInfo['zip'] = $this->post('zip');
        $storeName = $this->post('storeName');
        $authorizeInfo['email'] = $this->post('email');
        $productId = PRODUCT_ID;
        $authorizeInfo['amount'] = $this->post('ServiceAmount');

        $productArray['id'] = $productId;
        $productArray['packname'] = User::getproductPackName($productId);

        $productArray['permissionlist'] = User::getproductPermission($productId);

        $productArray['couponNo'] = $this->post('couponNo');
        $productArray['productreleaseid'] = User::getproductReleaseID($productId);

        $upgradeFlag = $this->post('upgradeFlag');
        $productLookUpId = $_POST['productLookUpId'];


        




        if ($productLookUpId > 0)
            $upgradeFlag = 1;


        //*****************************Akhil Code Paymant start********
        if ($this->post('paymentmethod') != "") // for paypalrpo only
            $paymantArray['paymentmethod'] = $this->post('paymentmethod'); // credit card
        if ($this->post('currentpaymant') != "")
            $paymantArray['currentpaymant'] = $this->post('currentpaymant'); // current paymant method
//echopre($paymantArray);exit;

//$paymantArray['currentpaymant']      = "paypalpro";
        //*****************************Akhil Code Paymant ends********
        //******************** Product Services
        $productServices = $this->post('serCat');
        $productSerArr = array();
        $productSerArr[0] = $this->post('productId');

        // Purchase Service Id
        $purchaseServiceId = User::getProductServicesId($productId, array(array('field' => 'PS.nSCatId', 'value' => PRODUCT_PURCHASE_CATEGORY)));
        //Append Purchase Service Id

        $productArray['productServices'] = $productSerArr;


        //******************** Product Services End
        $sessionObj = new LibSession();
        $userDetails = $sessionObj->get('userDetails');
        $storeName = $this->post('txtStoreName');
        $userEmail = $this->post('email');
        $userName = $this->post('fname');
        $userLname = $this->post('lname');
        $userPhone = $this->post('phone');
        //$userPassword = $storeName . '' . rand(1, 1000);
        $userPassword = $userDetails['password'];
        $userArray = array(
            'user_name' => $userName,
            'user_email' => $userEmail,
            'store_name' => $storeName,
            'userpassw' =>  $userPassword,
            'user_lname' => $userLname,
            'user_phone' => $userPhone,
        );





        if (LibSession::get('userID') == "") {
            /*             * ****** User Details Updation  ********** */
            $userUpdateArr = array();
            $userUpdateArr['vAddress'] = $authorizeInfo['add1'];
            $userUpdateArr['vCountry'] = $registrantCountry;
            $userUpdateArr['vState'] = $authorizeInfo['state'];
            $userUpdateArr['vCity'] = $authorizeInfo['city'];
            $userUpdateArr['vZipcode'] = $authorizeInfo['zip'];

            $userCreditArr = array();
            $userCreditArr['vFirstName'] = $authorizeInfo['fName'];
            $userCreditArr['vLastName'] = $authorizeInfo['lName'];
            $userCreditArr['vNumber'] = $authorizeInfo['ccno'];
            $userCreditArr['vCode'] = $authorizeInfo['cvv'];
            $userCreditArr['vMonth'] = $authorizeInfo['expMonth'];
            $userCreditArr['vYear'] = $authorizeInfo['expYear'];
            $userCreditArr['vAddress'] = $authorizeInfo['add1'];
            $userCreditArr['vCity'] = $authorizeInfo['city'];
            $userCreditArr['vState'] = $authorizeInfo['state'];
            $userCreditArr['vZipcode'] = $authorizeInfo['zip'];
            $userCreditArr['vCountry'] = $registrantCountry;
            $userCreditArr['vEmail'] = $authorizeInfo['email'];
            $userCreditArr['vUserIp'] = $_SERVER['REMOTE_ADDR'];

            /*             * ****** User Details Updation  ********** */
            $userId = User::createUserAccount($userArray, $userUpdateArr, $userCreditArr);

            

            //------------Add user to supportdesk-----------------//
           /* include_once(BASE_PATH . "project/support/api/useradd.php");
            userAdd($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);*/

            User::$dbObj = new Db();
User::createSupportuser($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);

            //-----------Set supportdesk session----------//
            $sptbl_user = @mysqli_query($connection,"SELECT * FROM sptbl_users WHERE vEmail = '{$userArray['user_email']}'");
            if (mysqli_num_rows($sptbl_user) > 0) {
                $sptbl_res = mysqli_fetch_array($sptbl_user);

                $_SESSION["sess_username"] = $sptbl_res['vUserName'];
                $_SESSION["sess_userid"] = $sptbl_res['nUserId'];
                $_SESSION["sess_useremail"] = $sptbl_res['vEmail'];
                $_SESSION["sess_userfullname"] = $sptbl_res['vUserName'];
                $_SESSION["sess_usercompid"] = 1;
            }
            LibSession::set('mailSendFlag', 1);
        } else {
            $userId = LibSession::get('userID');

            /*         UpDating Billing Details        */
            $dataArray['nUserId'] = LibSession::get('userID');
            $dataArray['vFirstName'] = $this->post('fname');
            $dataArray['vLastName'] = $this->post('lname');
            $dataArray['vNumber'] = $this->post('ccno');
            $dataArray['vCode'] = $this->post('cvv');
            $dataArray['vMonth'] = $this->post('expM');
            $dataArray['vYear'] = $this->post('expY');
            $dataArray['vAddress'] = $this->post('add1');
            $dataArray['vCity'] = $this->post('city');
            $dataArray['vState'] = $this->post('state');
            $dataArray['vZipcode'] = $this->post('zip');
            $dataArray['vCountry'] = $this->post('country');
            $dataArray['vEmail'] = $this->post('email');
            $dataArray['vPhone'] = $this->post('phone');

             
            //$tbs = User::updateUserCreditCardDetails($dataArray);
            /*        Ending of  UpDating Billing Details        */
        }


        /*
         * Session value setup
         */
        $userFullInfoArr = Admincomponents::getUserdetails($userId);
        LibSession::set('userID', $userId);
        LibSession::set('firstName', $userFullInfoArr->vFirstName);
        LibSession::set('reg_usr_id', $userId);
        LibSession::set('planid', 1);
        LibSession::set('planpackage', 1);
        LibSession::set('purchase_amt', $this->post('ServiceAmount'));
        LibSession::set('package_desc', 'Full Pack');
        LibSession::set('productid', $productId);
        LibSession::set('productreleaseid', $productArray['productreleaseid']);

        LibSession::set('fname', $authorizeInfo['fName']);
        LibSession::set('lname', $authorizeInfo['lName']);
        /*         * m**
          // Wallet Balance Check
          $walletBalance = $walletDiscount = $walletNewBalance = $discount = 0;
          $totalAmount = $authorizeInfo['amount'];


          if(!empty($userId)) {
          $walletBalance = Admincomponents::getUserWalletBalance($userId);
          $walletDiscount +=($totalAmount < $walletBalance) ? $totalAmount : $walletBalance;
          $walletNewBalance = $walletBalance-$walletDiscount;
          $discount +=$walletDiscount;
          $updateWalletArr = array();
          $updateWalletArr['nUId'] = $userId;
          $updateWalletArr['newBalance'] = $walletNewBalance;

          } // End If

         * *m* */
        // End Wallet Balance Check
//        $status  =   User::creditPayment($authorizeInfo);
        /*
         * Comment the line for testing. Need to enable it.
         */

        //*****Akhil Paymant code Paypal pro //****
        $arrtwoPaySettings = array();
        //$authorizeInfo['amount'] = 0.05;

        $arrtwoPaySettings['Grandtotal'] = urlencode($authorizeInfo['amount']);

        $arrtwoPaySettings['Firstname'] = urlencode($authorizeInfo['fName']);
        $arrtwoPaySettings['Lastname'] = urlencode($authorizeInfo['lName']);
        $arrtwoPaySettings['Street'] = urlencode($authorizeInfo['add1']);
        $arrtwoPaySettings['City'] = urlencode($authorizeInfo['city']);
        $arrtwoPaySettings['Zip'] = urlencode($authorizeInfo['zip']);
        $arrtwoPaySettings['Countrycode'] = urlencode($authorizeInfo['country']);
        $arrtwoPaySettings['Currency'] = urlencode('USD');


        /*  $authorizeInfo['expMonth']   = "01";
          $authorizeInfo['expYear']    = "2014";
          $authorizeInfo['cvv']        = 123;
          $authorizeInfo['ccno']       = "4055825683869610";
          $authorizeInfo['fName']      = 'fname';
          $authorizeInfo['lName']      = 'lname';
          $authorizeInfo['add1']       = 'add1';
          $authorizeInfo['city']       = 'city';
          $authorizeInfo['state']      = 'state';
          $authorizeInfo['country']    = 'US';
          $authorizeInfo['zip']        = "35001"; */


        /* $paymantArray['paymentmethod'] = "Visa";
          $paymantArray['currentpaymant'] ="paypalpro"; */

        if ($paymantArray['currentpaymant'] == 'paypalpro') { // if paymant method is paypalpro
            $paypayproSettings = Payments::getPaypalproSettings();

            $arrtwoPaySettings['Paypalprousername'] = $paypayproSettings['Paypalprousername']; //"mahiat_1351864475_biz_api1.yahoo.com";
            $arrtwoPaySettings['Paypalpropassword'] = $paypayproSettings['Paypalpropassword']; //"1351864518";
            $arrtwoPaySettings['Paypalprosignature'] = $paypayproSettings['Paypalprosignature']; //'A0yYhIEfABicc8vcPNDIgocAdlatAiI9tB-cYE4rdnod1VbCLzTG6fu6';
            $arrtwoPaySettings['Paymenttype'] = urlencode('Sale'); // Constant
            $arrtwoPaySettings['Creditcardtype'] = urlencode($paymantArray['paymentmethod']); //urlencode('Visa');
            $arrtwoPaySettings['Creditcardnumber'] = urlencode($authorizeInfo['ccno']); //urlencode('4055825683869610');
            $arrtwoPaySettings['Expdate'] = urlencode($authorizeInfo['expMonth'] . $authorizeInfo['expYear']); //urlencode('112017');//mmyyyy
            $arrtwoPaySettings['Cvv2'] = urlencode($authorizeInfo['cvv']); //urlencode('123');

            if ($paypayproSettings['Paypalprotestmode'] == 'Y')
                $arrtwoPaySettings['Testmode'] = 'Y';
            else
                $arrtwoPaySettings['Testmode'] = 'N';
        }else if ($paymantArray['currentpaymant'] == 'paypalflow') { // paymant gateway paypal flow
            $paypalflowSettings = Payments::getPaypalflowSettings();
            $arrtwoPaySettings['Paypalflowvendorid'] = $paypalflowSettings['Paypalflowvendorid']; //"armiapaypal";
            $arrtwoPaySettings['Paypalflowpassword'] = $paypalflowSettings['Paypalflowpassword']; //"armia247";
            $arrtwoPaySettings['Paypalflowpartnerid'] = $paypalflowSettings['Paypalflowpartnerid']; //'PayPal';
            $arrtwoPaySettings['Paymenttype'] = urlencode('S'); // Constant
            $arrtwoPaySettings['Tender'] = urlencode('C'); // Constant
            $arrtwoPaySettings['Creditcardnumber'] = urlencode($authorizeInfo['ccno']); //urlencode('5105105105105100');
            $arrtwoPaySettings['Expdate'] = urlencode($authorizeInfo['expMonth'] . $authorizeInfo['expYear']); //urlencode('1117');//mmyy
            $arrtwoPaySettings['Cvv2'] = urlencode($authorizeInfo['cvv']); //urlencode('123');

            if ($paypalflowSettings['Paypalflowtestmode'] == 'Y')
                $arrtwoPaySettings['Testmode'] = 'Y';
            else
                $arrtwoPaySettings['Testmode'] = 'N';
        }else if ($paymantArray['currentpaymant'] == 'authorize') { // paymant gateway authorize
            //$status  =   User::creditPayment($authorizeInfo);

            $arrtwoPaySettings = $authorizeInfo;
            //  $status = Payments::doAllPaymants($paymantArray['currentpaymant'], $arrtwoPaySettings);
        }

        
       



        $status = Payments::doAllPaymants($paymantArray['currentpaymant'], $arrtwoPaySettings,$authorizeInfo);

//echopre($status);

 if($paymantArray['currentpaymant']=='stripe' )
        {

            $productArray['planProductRestriction'] = User::getPlanproductRestriction($this->post('productId'));
            $productArray['productAccessKey'] = md5(SECRET_SALT.$subdom . '.' . DOMAIN_NAME);
            $productArray['xmlproductdata'] = User::setXmlData($productArray['planProductRestriction'], md5(SECRET_SALT . $productLookUpId));

            LibSession::set('arrtwoPaySettings', $arrtwoPaySettings);
            LibSession::set('authorizeInfo', $authorizeInfo);
            LibSession::set('customerDetails', $dataArray);
            $subdom = $storeName;
            $subdom = strtolower($subdom);
            $subdom = str_replace(" ", '', $subdom);
            LibSession::set('subdomain', $subdom);
            LibSession::set('mode', 'subdomain');
            LibSession::set('userArray', $userArray);
            LibSession::set('productLookUpId', $productLookUpId);
            LibSession::set('upgradeFlag', $upgradeFlag);
            LibSession::set('couponNo', $productArray['couponNo']);
            LibSession::set('serCat', $productServices);
            LibSession::set('productArray', $productArray);
 
            echo 1;exit;
        }

        else
        {




        //************Akhil Paymant code ends**************
        if ($status['success'] == 1) {
            //  if(1){
            $subdom = $storeName;
            $subdom = strtolower($subdom);
            $subdom = str_replace(" ", '', $subdom);


            // For bluedog customer id hash store
            $customerarray = array();
            if(isset($status['customerDetails'])){
            $customerarray = $status['customerDetails'];
            }
            /*
             * // Function to store paymnt details
             *  Parameters 1. amount
             *             2. Payment mode
             *             3. transaction ID
             *
             */

                $description = 'Subdomain Purchase'.'###'.$status['email'];;
            if ($upgradeFlag == 1) {
                $description = 'Subdomain Upgrade'.'###'.$status['email'];;
            }        
                    
            User::storePaymentsEntry($status['amount'], $paymantArray['currentpaymant'], $status['transactionId'],$description);


            if ($upgradeFlag == 1) {
                $subdom = User::getSubDomainName($productLookUpId);
            }

            $productArray['planProductRestriction'] = User::getPlanproductRestriction($this->post('productId'));
            $productArray['productAccessKey'] = md5(SECRET_SALT.$subdom . '.' . DOMAIN_NAME);
            $productArray['xmlproductdata'] = User::setXmlData($productArray['planProductRestriction'], md5(SECRET_SALT . $productLookUpId));


            $this->createaccountafterpayment($userArray, $subdom, $userId, $productArray, $status, $upgradeFlag, $productLookUpId,$customerarray);
        } else {
            
            
            $status['Message'] = 'Payment Failed.' . $status['Message'];
            $data = array('failed' => 1, 'list' => $status['Message']);
            echo json_encode($data);
            exit;
        }
        die;

        $this->redirect('index/viewlisting/' . $listId . '/1/2/');
        exit;
    }
    
    }


    public function updatePlanStore($pdLookupId,$storeArray)
    {
//        $url = 'http://'.$subdomain.'.'.DOMAIN_NAME.'/Settings/updatesystemsettings';
//
//$storeArray['super_admin_email'] = ADMIN_EMAILS;
//        $post = [
//    'settings_data' => json_encode($storeArray)
//    ];
//
//
//    $ch = curl_init($url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
//
//    // execute!
//    $response = curl_exec($ch);
//
//    // close the connection, release resources used
//    curl_close($ch);
            PageContext::includePath('cpanel');
                    $cpanelObj  = new cpanel();    
            $pLookupdata = User::getProductDetails($pdLookupId);
            $storeHost = User::getStoreHost($pdLookupId);
            $planCount = User::getPlanproductRestriction($storeArray['subscribed_planid']);
            $storeArray['store_access_key'] = md5(SECRET_SALT.$storeHost);
            $storeArray['product_count'] = $planCount;
            
            $cpanelObj->updateStoreDatabse($pLookupdata,$storeArray);

    }


    public function createaccountafterpayment($userArray, $subdom, $userId, $productArray, $payInfoArr = NULL, $upgradeFlag = 0, $productLookUpId = 0,$customerarray=array()) {

//echo "createaccountafterpayment";
        $session    =   new LibSession(); 
        set_time_limit(0);
        PageContext::includePath('cpanel');
        $cpanelObj = new cpanel();
        $dbArray = array();
        $this->view->disableView();
        $productInstallPath = BASE_PATH . '' . $subdom . '/';



        $dataArr = array();
        if ($upgradeFlag == 1) {
            $pdLookupId = $productLookUpId;



            $dataArr['upgrade'] = 1;

            $session->set('subdomain',$subdom); 
            $plId = $productLookUpId;
            $accountDetails = unserialize(User::getserverDetails($plId));
            $statusArray = $cpanelObj->upgradesubdomainaccount($accountDetails['c_user'], $accountDetails['c_pass'], $accountDetails['c_host'], $RegistrantEmailAddress, $productArray);
//echopre($statusArray);
            /*if($userId==298)
            {
                echopre($userArray);
                echopre($productArray);
                echopre($productLookUpId);
                echopre($subdom);
                echopre($customerarray);

            }*/


            $storeArray = array();
            $storeArray['subscribed_planid'] = $productArray['productServices'][0];

            $this->updatePlanStore($pdLookupId,$storeArray);



        } else {
            /*
             * Commen the line and chnaged the procedure to individual account
             */



            $domainName = $subdom . '.' . DOMAIN_NAME;
            $username = substr(strtolower($userArray['user_name']), 0, 3);
            $username = $username . substr(md5($userArray['user_name'] . time()), 0, 3);

            //sleep(10);
            $statusArray = $cpanelObj->createcpanelaccountforsubdomain($username, $userArray['userpassw'], $domainName, $userArray['user_email'], $productArray);


            if(LibSession::get('payment_method')=='stripe')
                {
            sleep(5);
                }

            $userArray['c_user'] = $username;
            $userArray['c_pass'] = $statusArray['c_pass'];
            $userArray['c_host'] = $domainName;
            $userArray['db_name'] = $statusArray['db_name'];
            $userArray['db_user'] = $statusArray['db_user'];
            $userArray['db_password'] = $statusArray['db_password'];


            
            Utils::reconnect();
            $pdLookupId = $this->updateuser($userArray, $subdom, $userId);

            if ($statusArray['status'] == 0) {
                //Failed
                if (isset($statusArray['tech_statusmsg']) && trim($statusArray['tech_statusmsg']) <> '') {
                    $contents = $statusArray['tech_statusmsg'];
                } else {
                    $contents = "Account setup failed. Please be patient our customer care agent will fix the issue and inform you.";
                }

                $data = array('failed' => 1, 'list' => $contents);

                if(LibSession::get('payment_method')=='stripe')
                {
                    return  json_encode($data);exit;
                }
                else
                {

                
                echo json_encode($data);
                die;
                }
            }
        }

        if($customerarray)
        {
            User::updateLookupEntryBluedog($pdLookupId,$customerarray);
        }


        Utils::reconnect();

        $dataArr = array('nUId' => $userId,
            'nPLId' => $pdLookupId,
            'services' => $productArray['productServices'],
            'domainService' => array(),
            'couponNo' => $productArray['couponNo'],
            'terms' => '',
            'notes' => '',
            'paymentstatus' => 'paid',
            'vMethod' => $payInfoArr['paymentMethod'],
            'vTxnId' => $payInfoArr['transactionId'],
            'upgrade' => $upgradeFlag,
            'subscriptionType' => 'PAID');
        
        
        if($pdLookupId)
        {
            $pLookupdata = User::getProductDetails($pdLookupId);
            $cpanelObj->createCpanelCronjob($pLookupdata);
            
        }

        
        
        
        

        Admincomponents::generateInvoice($dataArr,$customerarray);

        $contents = '<div class="storecration_instalation_wrapper" style="">
                                                                        <div class="storecration_instalation_wrapper_inner">
                                                                            <div class="instalation_completed_img "></div>

                                                                             <div class="pymnt_sucessmsgs" style="text-align:center;"><div class="store_success">
            <div class="store_success_label"></div>
                    <h2>Congratulations!</h2>';
        if ($upgradeFlag == 1) {
            $contents.= '<h3>The Upgrade Process was completed successfully!</h3>
                    <p class="head">Site Login Details</p>';
        } else {
            $contents.= '<h3>Your installation was successful!</h3>
                    <p class="head">Site Login Details</p>';
        }
        $contents.= '<table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                    <td align="left" valign="top" width="20%">Admin URL </td>
                    <td align="left" valign="top">:&nbsp;<a href="http://www.' . $subdom . '.' . DOMAIN_NAME . '/admins/"  target="_blank">http://www.' . $subdom . '.' . DOMAIN_NAME . '/admins/</a></td>
                    </tr>
                    <tr>
                    <td align="left" valign="top">Home URL </td>
                    <td align="left" valign="top">:&nbsp;<a href="http://www.' . $subdom . '.' . DOMAIN_NAME . '/index.php"  target="_blank">http://www.' . $subdom . '.' . DOMAIN_NAME . '/</a></td>
                    </tr>
                    </table>
                    <p class="head">Admin Credentials</p>
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                    <td align="left" valign="top" width="20%">Username</td>
                    <td align="left" valign="top">:&nbsp;admin</td>
                    </tr>
                    <tr>
                    <td align="left" valign="top" >Password</td>
                    <td align="left" valign="top">:&nbsp;admin</td>
                    </tr>
                    </table>

            </div></div>
                                                                        </div>
                                                                        <div class="clear"></div>
                                                                   </div>';

        $data = array('success' => 1, 'list' => $contents);
        if(LibSession::get('payment_method')=='stripe')
                {
                    return  json_encode($data);exit;
                }
                else
                {
                  echo json_encode($data);
        exit;  
                 }
    }

    /*
     * Update user after account setup
     */

    public function updateuser($userArray, $subdom, $userId) {
        /*
         * User status update
         */

        if (LibSession::get('mailSendFlag') == 1) {
            User::sendMail($userArray);
            LibSession::set('mailSendFlag', 0);
        }
        $prdLookupId = User::addLookupEntry($userArray, $subdom, $userId);
        return $prdLookupId;
    }

    /*
     * Check domain availabilty
     */

    public function checkdomainstatus() {
        $this->disableLayout();
        $cAction = $_POST["action"];
        $tld     = $_POST["tld"];
        $sld     = $_POST["idsld"];
        if ($_GET['from'] == "whois"){
            $sld        = $_GET['idsld'];
            $tld        = $_GET['tld'];
            $cAction    = "check";
        }

        $_SESSION["sld"] = $sld;
        $_SESSION["tld"] = $tld;
        $bError     = 0;
        $bAvailable = 0;
        // Do we need to check a name?
        if ($cAction == "check"){
            if (!Utils::isValidDomainname($sld)){
                $originaldomainavailable = 0;
                $originaldomaierror = 1;
                $originaldomaierrormessage = "Invalid domain name. Only letters, numbers or hyphen are allowed";
                $data = array('faild' => 1, 'list' => $originaldomaierrormessage);
                die;
            }else{
                $domainFlag = User::checkdomainstatus($sld, $tld);
                $_SESSION["domainFlag"] = $domainFlag;

                $originaldomain = $sld . "." . $tld;
                if ($domainFlag == 1){
                    $gettldPrice = User::gettldprice($tld);
                    $message = "$originaldomain is available";
                    $data = array('success' => 1, 'list' => $message, 'tldprice' => $gettldPrice);
                    echo json_encode($data);
                }else{
                    $message = "$originaldomain domain not available";
                    $data = array('faild' => 1, 'list' => $message);
                    echo json_encode($data);
                }
            }
        }
        die;
    }

    public function registerdomain() {
        /*****************************  Starting of Billing Details *************************/
set_time_limit(0);
        $connection = new Db();

        $this->view->disableView();
        $siteOperationParkDomain    = OPERATION_MODE_PARK_DOMAIN;
        $RegistrantFirstName        = $this->post("RegistrantFirstName");
        $RegistrantLastName         = $this->post("RegistrantLastName");
        $RegistrantJobTitle         = $this->post("RegistrantJobTitle");
        $RegistrantOrganizationName = $this->post("RegistrantOrganizationName");
        $RegistrantAddress1         = $this->post("RegistrantAddress1");
        $RegistrantAddress2         = $this->post("RegistrantAddress2");
        $RegistrantCity             = $this->post("RegistrantCity");
        $RegistrantState            = $this->post("RegistrantState");
        $RegistrantPhone            = $this->post("RegistrantPhone");

        $RegistrantProvince         = $this->post("RegistrantProvince");
        $RegistrantPostalCode       = $this->post("RegistrantPostalCode");
        $idRegistrantCountry        = $this->post("idRegistrantCountry");

        // Registrant Country
        global $usStates;
        $registrantCountry          = $usStates[$idRegistrantCountry];
        $RegistrantFax              = $this->post("RegistrantFax");
        $RegistrantPhone            = $this->post("RegistrantPhone");
        $RegistrantEmailAddress     = $this->post("RegistrantEmailAddress");
        $domainFlag                 = $this->post('domainFlag');
        $tldPrice                   = $this->post('tldPrice');

        $dataArray['vFirstName']    = $this->post('RegistrantFirstName');
        $dataArray['vLastName']     = $this->post('RegistrantLastName');
        $dataArray['vAddress']      = $this->post('RegistrantAddress1');
        $dataArray['vCity']         = $this->post('RegistrantCity');
        $dataArray['vState']        = $this->post('RegistrantState');
        $dataArray['vZipcode']      = $this->post('RegistrantPostalCode');
        $dataArray['vCountry']      = $this->post('idRegistrantCountry');
        $dataArray['vEmail']        = $this->post('RegistrantEmailAddress');
        $dataArray['vNumber']       = $this->post('ccno');
        $dataArray['vCode']         = $this->post('cvv');
        $dataArray['vMonth']        = $this->post('expM');
        $dataArray['vYear']         = $this->post('expY');

        $dataArray['VjobTitle']     = $this->post("RegistrantJobTitle");
        $dataArray['VOrgan']     = $this->post("RegistrantOrganizationName");
        $dataArray['Vaddress2']     = $this->post("RegistrantAddress2");
        $dataArray['VPhone']     = $this->post("RegistrantPhone");
        $dataArray['VProvince']     = $this->post("RegistrantProvince");
        $dataArray['VFax']     = $this->post("RegistrantFax");
        /*****************************  Ending of Updating Billing Details *************************/

        /****************************   Payment details area *************************************/
        $authorizeInfo              = array();
        $productArray               = array();
        $authorizeInfo['fName']     = $this->post('RegistrantFirstName');
        $authorizeInfo['lName']     = $this->post('RegistrantLastName');
        $authorizeInfo['add1']      = $this->post('RegistrantAddress1');
        $authorizeInfo['city']      = $this->post('RegistrantCity');
        $authorizeInfo['state']     = $this->post('RegistrantState');
        $authorizeInfo['country']   = $this->post('idRegistrantCountry');
        $authorizeInfo['zip']       = $this->post('RegistrantPostalCode');
        $authorizeInfo['email']     = $this->post('RegistrantEmailAddress');
        $authorizeInfo['amount']    = $this->post('ServiceAmount');
        $authorizeInfo['expMonth']  = $this->post('expM');
        $authorizeInfo['expYear']   = $this->post('expY');
        $authorizeInfo['cvv']       = $this->post('cvv');
        $authorizeInfo['ccno']      = $this->post('ccno');
        $authorizeInfo['phone']      = $this->post('RegistrantPhone');
        $authorizeInfo['fax']      = $this->post('RegistrantFax');


        

        $paymantArray                       = array();
        $paymantArray['currentpaymant']     = $this->post('currentpaymant');
        if ($this->post('paymentmethod') != "")
            $paymantArray['paymentmethod']  = $this->post('paymentmethod');


        $productId                          = PRODUCT_ID;
        $productArray['id']                 = $productId;
        $productArray['packname']           = User::getproductPackName($productId);
        $productArray['permissionlist']     = User::getproductPermission($productId);
        $productArray['productreleaseid']   = User::getproductReleaseID($productId);
        $productArray['couponNo']           = $this->post('couponNo');

        /**************************** Product Services ***************************/
        $productServices        = $this->post('productId');
        $productSerArr          = array();
        if (!empty($productServices)) {
            $productSerArr = explode(",", $productServices);
        }
        // Purchase Service Id
        $purchaseServiceId = $this->post('productId');

        //Append Purchase Service Id
        $productArray['productServices'] = $productSerArr;
        /**************************** Product Services ***************************/

        $idsld              = $this->post("idsld");
        $tld                = $this->post("tld");
        $NumYears           = $this->post('NumYears');
        $UnLockRegistrar    = $this->post('UnLockRegistrar');
        $domainName         = $idsld . '.' . $tld;
        $domainName         = str_replace("www.", '', $domainName);
        $domainName         = str_replace("http://", '', $domainName);
        $domainRegisterFlag = 0;

        //session storing

        $dataArray['idsld']     = $idsld;
        $dataArray['tld']     = $tld;
        $dataArray['NumYears']     = $NumYears;
        $dataArray['UnLockRegistrar']     = $UnLockRegistrar;
        $dataArray['domainName']     = $domainName;

        $productArray['planProductRestriction'] = User::getPlanproductRestriction($this->post('productId'));
        $productArray['productAccessKey'] = md5(SECRET_SALT.$domainName);
        
        $productArray['xmlproductdata']         = User::setXmlData($productArray['planProductRestriction'], md5($domainName));
        $upgradeFlag        = $this->post('upgradeFlag');
        $productLookUpId    = $this->post('productLookUpId');
        $upgrade            = NULL;
        if ($productLookUpId > 0)
            $upgradeFlag    = 1;

        if (Utils::isValidDomainname($idsld)) {
            /* User registration area */

            $user_name      = $RegistrantFirstName;
            $user_email     = $RegistrantEmailAddress;
            $store_name     = $idsld . '.' . $tld;
            $userpassw      = $RegistrantFirstName . '' . rand(1, 1000);
            $user_lname     = $RegistrantLastName;
            $userArray      = array(
                                "user_name"     => $user_name,
                                "user_email"    => $user_email,
                                "user_phone"    => $RegistrantPhone,
                                "store_name"    => $store_name,
                                "userpassw"     => $userpassw,
                                "user_lname"    => $user_lname,
                            );
            if (LibSession::get('userID') == ""){
                $storeName      = "My Account";
                $userEmail      = $RegistrantEmailAddress;
                $userName       = $RegistrantFirstName;
                $userLname      = $RegistrantLastName;
                $userPassword   = $RegistrantFirstName . '' . rand(1, 1000);

                /******************** User Details Updation  ********************/
                $userUpdateArr = array();
                $userUpdateArr['vAddress']      = $RegistrantAddress1;
                $userUpdateArr['vCountry']      = $registrantCountry;
                $userUpdateArr['vState']        = $RegistrantState;
                $userUpdateArr['vCity']         = $RegistrantCity;
                $userUpdateArr['vZipcode']      = $RegistrantPostalCode;

                $userCreditArr = array();
                $userCreditArr['vFirstName']    = $RegistrantFirstName;
                $userCreditArr['vLastName']     = $RegistrantLastName;
                $userCreditArr['vNumber']       = $authorizeInfo['ccno'];
                $userCreditArr['vCode']         = $authorizeInfo['cvv'];
                $userCreditArr['vMonth']        = $authorizeInfo['expMonth'];
                $userCreditArr['vYear']         = $authorizeInfo['expYear'];
                $userCreditArr['vAddress']      = $RegistrantAddress1;
                $userCreditArr['vCity']         = $RegistrantCity;
                $userCreditArr['vState']        = $RegistrantState;
                $userCreditArr['vZipcode']      = $RegistrantPostalCode;
                $userCreditArr['vCountry']      = $registrantCountry;
                $userCreditArr['vEmail']        = $RegistrantEmailAddress;
                $userCreditArr['vUserIp']       = $_SERVER['REMOTE_ADDR'];
                /******************** User Details Updation  ********************/

                $userId = User::createUserAccount($userArray, $userUpdateArr, $userCreditArr);

                /********************  Add user to supportdesk ********************/
                /*include_once(BASE_PATH . "project/support/api/useradd.php");
                userAdd($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);*/

                User::$dbObj = new Db();
User::createSupportuser($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);
                /********************  Add user to supportdesk ********************/

                /********************  Set supportdesk session ********************/
                $sptbl_user = mysqli_query($connection,"SELECT * FROM sptbl_users WHERE vEmail = '{$userArray['user_email']}'");
                if (mysqli_num_rows($sptbl_user) > 0) {
                    $sptbl_res = mysqli_fetch_array($sptbl_user);

                    $_SESSION["sess_username"]      = $sptbl_res['vUserName'];
                    $_SESSION["sess_userid"]        = $sptbl_res['nUserId'];
                    $_SESSION["sess_useremail"]     = $sptbl_res['vEmail'];
                    $_SESSION["sess_userfullname"]  = $sptbl_res['vUserName'];
                    $_SESSION["sess_usercompid"]    = 1;
                }

                LibSession::set('mailSendFlag', 1);
                LibSession::set('userID', $userId);
                $userFullInfoArr = Admincomponents::getUserdetails($userId);
                LibSession::set('firstName', $userFullInfoArr->vFirstName);
            } else {
                $userId = LibSession::get('userID');

                /****************************  Updating Billing Details ****************************/
                $userDataArray                  = array();
                $userDataArray['nUserId']       = LibSession::get('userID');
                $userDataArray['vFirstName']    = $RegistrantFirstName;
                $userDataArray['vLastName']     = $RegistrantLastName;
                $userDataArray['vNumber']       = $authorizeInfo['ccno'];
                $userDataArray['vCode']         = $authorizeInfo['cvv'];
                $userDataArray['vMonth']        = $authorizeInfo['expMonth'];
                $userDataArray['vYear']         = $authorizeInfo['expYear'];
                $userDataArray['vAddress']      = $RegistrantAddress1;
                $userDataArray['vCity']         = $RegistrantCity;
                $userDataArray['vState']        = $RegistrantState;
                $userDataArray['vZipcode']      = $RegistrantPostalCode;
                $userDataArray['vCountry']      = $registrantCountry;
                $userDataArray['vEmail']        = $RegistrantEmailAddress;
                $tbs                            = User::updateUserCreditCardDetails($userDataArray);
                /****************************  Updating Billing Details ****************************/

                $DataArr=$userDataArray;
            }

            /*********************************** Session value setup *******************************/
            LibSession::set('reg_usr_id', $userId);
            LibSession::set('planid', 1);
            LibSession::set('planpackage', 1);
            LibSession::set('purchase_amt', $this->post('ServiceAmount'));
            LibSession::set('package_desc', 'Full Pack');
            LibSession::set('productid', $productId);
            LibSession::set('productreleaseid', $productArray['productreleaseid']);

            /***************************  To update payment details **********************/
            $tbs = User::updateUserCreditCardDetails($dataArray);

            /******************************** Wallet Balance Check ************************/
            $walletBalance  = $walletDiscount = $walletNewBalance = $discount = 0;
            $totalAmount    = $authorizeInfo['amount'];

            if (!empty($userId)){
                $walletBalance      =  Admincomponents::getUserWalletBalance($userId);
                $walletDiscount     += ($totalAmount < $walletBalance) ? $totalAmount : $walletBalance;
                $walletNewBalance   =  $walletBalance - $walletDiscount;
                $discount           += $walletDiscount;

                /*************************** Update Wallet ***********************/
                $updateWalletArr                = array();
                $updateWalletArr['nUId']        = $userId;
                $updateWalletArr['newBalance']  = $walletNewBalance;
                Admincomponents::updateWallet($updateWalletArr);
                /*************************** Update Wallet ***********************/

                $authorizeInfo['amount']        = $totalAmount - $discount;
            }
            /******************************** Wallet Balance Check ************************/

            //$status  =   User::creditPayment($authorizeInfo);

            /************************** Payment gateway integration starts *************************/
            $arrtwoPaySettings                  = array();
            //$authorizeInfo['amount']          = 0.05;
            $arrtwoPaySettings['Grandtotal']    = urlencode($authorizeInfo['amount']);
            $arrtwoPaySettings['Firstname']     = urlencode($authorizeInfo['fName']);
            $arrtwoPaySettings['Lastname']      = urlencode($authorizeInfo['lName']);
            $arrtwoPaySettings['Street']        = urlencode($authorizeInfo['add1']);
            $arrtwoPaySettings['City']          = urlencode($authorizeInfo['city']);
            $arrtwoPaySettings['Zip']           = urlencode($authorizeInfo['zip']);
            $arrtwoPaySettings['Countrycode']   = urlencode($authorizeInfo['country']);
            $arrtwoPaySettings['Currency']      = urlencode('USD');

            switch ($paymantArray['currentpaymant']) {
                case 'paypalprodomain': $paymantArray['currentpaymant'] = 'paypalpro';
                    break;
                case 'paypalflowdomain': $paymantArray['currentpaymant'] = 'paypalflow';
                    break;
                case 'authorizedomain': $paymantArray['currentpaymant'] = 'authorize';
                    break;
                 case 'bluedogdomain': $paymantArray['currentpaymant'] = 'bluedog';
                    break;
                    case 'stripe': $paymantArray['currentpaymant'] = 'stripe';
                    break;
                default : case 'authorizedomain': $paymantArray['currentpaymant'] = 'authorize';
                    break;
            }

            if ($paymantArray['currentpaymant'] == 'paypalpro') { // if paymant method is paypalpro
                $paypayproSettings                          = Payments::getPaypalproSettings();
                $arrtwoPaySettings['Paypalprousername']     = $paypayproSettings['Paypalprousername']; //"mahiat_1351864475_biz_api1.yahoo.com";
                $arrtwoPaySettings['Paypalpropassword']     = $paypayproSettings['Paypalpropassword']; //"1351864518";
                $arrtwoPaySettings['Paypalprosignature']    = $paypayproSettings['Paypalprosignature']; //'A0yYhIEfABicc8vcPNDIgocAdlatAiI9tB-cYE4rdnod1VbCLzTG6fu6';
                $arrtwoPaySettings['Paymenttype']           = urlencode('Sale'); // Constant
                $arrtwoPaySettings['Creditcardtype']        = urlencode($paymantArray['paymentmethod']); //urlencode('Visa');
                $arrtwoPaySettings['Creditcardnumber']      = urlencode($authorizeInfo['ccno']); //urlencode('4055825683869610');
                $arrtwoPaySettings['Expdate']               = urlencode($authorizeInfo['expMonth'] . $authorizeInfo['expYear']); //urlencode('112017');//mmyyyy
                $arrtwoPaySettings['Cvv2']                  = urlencode($authorizeInfo['cvv']); //urlencode('123');

                if ($paypayproSettings['Paypalprotestmode'] == 'Y')
                    $arrtwoPaySettings['Testmode'] = 'Y';
                else
                    $arrtwoPaySettings['Testmode'] = 'N';
            } else if ($paymantArray['currentpaymant'] == 'paypalflow') { // paymant gateway paypal flow
                $paypalflowSettings                         = Payments::getPaypalflowSettings();
                $arrtwoPaySettings['Paypalflowvendorid']    = $paypalflowSettings['Paypalflowvendorid']; //"armiapaypal";
                $arrtwoPaySettings['Paypalflowpassword']    = $paypalflowSettings['Paypalflowpassword']; //"armia247";
                $arrtwoPaySettings['Paypalflowpartnerid']   = $paypalflowSettings['Paypalflowpartnerid']; //'PayPal';
                $arrtwoPaySettings['Paymenttype']           = urlencode('S'); // Constant
                $arrtwoPaySettings['Tender']                = urlencode('C'); // Constant
                $arrtwoPaySettings['Creditcardnumber']      = urlencode($authorizeInfo['ccno']); //urlencode('5105105105105100');
                $arrtwoPaySettings['Expdate']               = urlencode($authorizeInfo['expMonth'] . $authorizeInfo['expYear']); //urlencode('1117');//mmyy
                $arrtwoPaySettings['Cvv2']                  = urlencode($authorizeInfo['cvv']); //urlencode('123');
                if ($paypalflowSettings['Paypalflowtestmode'] == 'Y')
                    $arrtwoPaySettings['Testmode'] = 'Y';
                else
                    $arrtwoPaySettings['Testmode'] = 'N';
            }else if($paymantArray['currentpaymant'] == 'authorize'){ // paymant gateway authorize
                //$status  =   User::creditPayment($authorizeInfo);
                $arrtwoPaySettings = $authorizeInfo;
                //$status = Payments::doAllPaymants($paymantArray['currentpaymant'], $arrtwoPaySettings);
            }

            $status = Payments::doAllPaymants($paymantArray['currentpaymant'], $arrtwoPaySettings,$authorizeInfo);


            $customerarray = array();
            if(isset($status['customerDetails'])){
            $customerarray = $status['customerDetails'];
            }


            if($paymantArray['currentpaymant']=='stripe' )
        {

            LibSession::set('arrtwoPaySettings', $arrtwoPaySettings);
            LibSession::set('authorizeInfo', $authorizeInfo);
            LibSession::set('customerDetails', $dataArray);
//            $subdom = $storeName;
//            $subdom = strtolower($subdom);
//            $subdom = str_replace(" ", '', $subdom);
            LibSession::set('domainName', $domainName);

            LibSession::set('mode', 'registerdomain');
            LibSession::set('userDataArray', $dataArray);
            LibSession::set('productLookUpId',$productLookUpId);
            LibSession::set('domainFlag',$domainFlag);
            LibSession::set('productArray',$productArray);
            LibSession::set('couponNo',$productArray['couponNo']);
            



            echo 1;exit;
        }





            //$status['success'] = 1;
            /************************** Payment gateway integration starts *************************/

            if ($status['success'] == 1) {
                
                
                $description = 'Domain Purchase'.'###'.$status['email'];
            if ($upgradeFlag == 1) {
                $description = 'Domain Upgrade'.'###'.$status['email'];
            }    
                
                //Payments entry
                User::storePaymentsEntry($status['amount'], $paymantArray['currentpaymant'], $status['transactionId'],$description);

                if($_POST['productLookUpId']){
                            $storeArray = array();
                            $storeArray['subscribed_planid'] = $_POST['productId'];
                            $this->updatePlanStore($_POST['productLookUpId'],$storeArray); 
                }
                
                /************************** Domain Registration Starts *************************/
                //$domainFlag = 1;
                if ($domainFlag == 1){
                     $messageArray = User::registerdomain($RegistrantFirstName, $RegistrantLastName, $RegistrantJobTitle, $RegistrantOrganizationName, $RegistrantAddress2, $RegistrantCity, $RegistrantState, $RegistrantProvince, $RegistrantPostalCode, $idRegistrantCountry, $RegistrantFax, $RegistrantPhone, $RegistrantEmailAddress, $idsld, $tld, $NumYears, $RegistrantAddress1, $UnLockRegistrar);
                    //echo "Message array = <pre>"; print_r($messageArray); echo "</pre>";

                    if ($messageArray['status'] == 1){
                        $domainRegisterFlag = 1;
                    }else{
                        $contents   = "Domain registration failed";
                        $data       = array('failed' => 1, 'list' => $contents);
                        echo json_encode($data);
                        die;
                    }
                }else{
                    $domainRegisterFlag = 1;
                }

                /************************** Domain Registration Ends *************************/

                /************************** cPanel instance creation Starts *************************/
                if($domainRegisterFlag == 1){
                    PageContext::includePath('cpanel');
                    $cpanelObj  = new cpanel();
                    $username   = substr(strtolower($RegistrantFirstName), 0, 3);
                    $username   = $username . substr(md5($RegistrantFirstName . time()), 0, 3);
                    $password   = substr(md5($RegistrantFirstName), 0, 12);
                    $dataArr    = array();
                    if ($upgradeFlag == 0){
                        $statusArray = $cpanelObj->createcpanelaccount($username, $password, $domainName, $RegistrantEmailAddress, $productArray);
                        //echo "<pre>"; print_r($statusArray); echo "</pre>"; die();

                        Utils::reconnect();
                        if ($statusArray['status'] == 1){
                            //Additional account details values for User Array
                            $userArray["c_user"]        = $username;
                            $userArray["c_pass"]        = $statusArray['c_pass'];
                            $userArray["c_host"]        = $domainName;
                            $userArray["sld"]           = $idsld;
                            $userArray["tld"]           = $tld;
                            $userArray["tempdispurl"]   = "";
                            $userArray["customerDetails"]           = $customerarray;
                            
                            /*********** Store Database Credentials ***********/
                            $userArray['db_name'] = $statusArray['db_name'];
                            $userArray['db_user'] = $statusArray['db_user'];
                            $userArray['db_password'] = $statusArray['db_password'];
                            
                            if ($siteOperationParkDomain == 'Y'){
                                if (isset($statusArray['tempdispurl']) && !empty($statusArray['tempdispurl'])) {
                                    $userArray["tempdispurl"] = $statusArray['tempdispurl'];
                                }
                            }
                            $plId = User::addLookupEntry($userArray, $domainName, $userId,1);
                            //echo $plId;
                            if($plId)
                            {
                                $pLookupdata = User::getProductDetails($plId);
                                $cpanelObj->createCpanelCronjob($pLookupdata);
                            }
                            if (LibSession::get('mailSendFlag') == 1){
                                User::sendMail($userArray);
                                LibSession::set('mailSendFlag', 0);
                            }else{
                                LibSession::set('userID', $userId);
                            }
                        }else{
                            //Failed
                            if (isset($statusArray['tech_statusmsg']) && trim($statusArray['tech_statusmsg']) <> '') {
                                $contents = $statusArray['tech_statusmsg'];
                            }else{
                                $contents = "Account setup failed. Please be patient our customer care agent will fix the issue and inform you.";
                            }
                            $data = array('failed' => 1, 'list' => $contents);
                            echo json_encode($data);
                            die;
                        }
                    } else { //If Upgrading the account
                        $productArray['domain'] = $domainName;
                        $plId = $productLookUpId;
                        $accountDetails = unserialize(User::getserverDetails($plId));

                        //Additional account details values for User Array
                        $userArray["c_user"]    = $accountDetails['c_user'];
                        $userArray["c_pass"]    = $accountDetails['c_pass'];
                        $userArray["c_host"]    = $domainName;
                        $userArray["sld"]       = $idsld;
                        $userArray["tld"]       = $tld;
                        $userArray["tempdispurl"] = "";
                        $userArray["customerDetails"]           = $customerarray;


                        $statusArray    = $cpanelObj->upgradeaccount($accountDetails['c_user'], $accountDetails['c_pass'], $accountDetails['c_host'], $RegistrantEmailAddress, $productArray);
                        $upgrade        = 1;
                        if(trim($statusArray["status"]) == 1){
                            User::updateLookupEntry($plId, $domainName, $userId, $userArray);
                            
                            
                        }

                        Utils::reconnect();
                        //Get Temporrary URL for UserArray
                        if ($siteOperationParkDomain == 'Y'){
                            if (isset($statusArray['tempdispurl']) && !empty($statusArray['tempdispurl'])) {
                                $userArray["tempdispurl"] = $statusArray['tempdispurl'];
                            }
                        } // End if
                        
                        if($plId)
                            {
                                $pLookupdata = User::getProductDetails($plId);
                                $cpanelObj->createCpanelCronjob($pLookupdata);
                            }
                        
                        
                            
                           
                            
                        
                    }
                    $productSetUpServiceId  = User::productsetUpId(PRODUCT_PURCHASE_CATEGORY, $productId);
                    $productRestriction     = User::getPlanproductRestriction($productSetUpServiceId);

                    //Tld Unit Price
                    $tldUnitPrice   = Utils::formatPrice($tldPrice / $NumYears);
                    $dataArr        = array(
                                            'nUId'              => $userId,
                                            'nPLId'             => $plId,
                                            'services'          => $productArray['productServices'],
                                            'domainService'     => array('nSCatId' => DOMAIN_REGISTRATION_ID, 'appendDescription' => '', 'rate' => $tldUnitPrice, 'year' => $NumYears),
                                            'couponNo'          => $productArray['couponNo'],
                                            'terms'             => '',
                                            'notes'             => '',
                                            'paymentstatus'     => 'paid',
                                            'vMethod'           => $status['paymentMethod'],
                                            'vTxnId'            => $status['transactionId'],
                                            'upgrade'           => $upgradeFlag,
                                            'subscriptionType'  => 'PAID'
                                        );
                    Utils::reconnect();
                    Admincomponents::generateInvoice($dataArr);

                    User::$dbObj = new Db();
                    //Name Server Details
                    $ns1 = User::$dbObj->selectRow("Settings", "value", "settingfield='name_server_1'");
                    $ns2 = User::$dbObj->selectRow("Settings", "value", "settingfield='name_server_2'");

                    //success
                    $contents = "Congratulations! Your installation was successful!<br>
                                Site Login Details<br>
                                Admin URL : <a href='" . $statusArray['returnurl'] . "/admins/' target='_blank'>" . $statusArray['returnurl'] . "admins/</a><br>
                                Admin Credentials : Username : admin<br>
                                Password : admin<br>
                                Home URL :  <a href='" . $statusArray['returnurl'] . "index.php' target='_blank'>" . $statusArray['returnurl'] . "</a><br>";

                    if ($domainFlag == 0) {
                        $contents = '<div class="col-md-8 col-md-offset-2"><div class="storecration_instalation_wrapper" style="">
                                        <div class="storecration_instalation_wrapper_inner">
                                            <div class="instalation_completed_img "></div>
                                             <div class="pymnt_sucessmsgs" style="text-align:center;">
                                             <div class="store_success">
                                                <div class="store_success_label"></div>
                                                <h2>Congratulations!</h2>';
                        if ($upgradeFlag == 1) {
                            $dName = $idsld . '.' . $tld;
                            User::updateLookupEntry($plId, $dName, $userId, $userArray);
                            if($plId)
                            {
                                $pLookupdata = User::getProductDetails($plId);
                                $cpanelObj->createCpanelCronjob($pLookupdata);
                                
                                
                                
                                
                            }
                            
                            
                            
                            
                            
                            $contents.='<h3>The Upgrade Process was completed successfully!</h3>';
                        } else {
                            $contents.='<h3>Your installation was successful!</h3>';
                        }

                        $contents.='<p class="head">Site Login Details</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Admin URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'admins/"  target="_blank">' . $statusArray['returnurl'] . 'admin/</a></td>
            </tr>
            <tr>
            <td align="left" valign="top">Home URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'index.php"  target="_blank">' . $statusArray['returnurl'] . '</a></td>
            </tr>
            </table>';

                        if ($siteOperationParkDomain == 'Y') {
                            $contents.='<p class="head">Temporary Login Details</p>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                        <td align="left" valign="top" width="20%">Admin URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'admins/"  target="_blank">' . $statusArray['tempdispurl'] . 'admin/</a></td>
                                        </tr>
                                        <tr>
                                        <td align="left" valign="top">Home URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'index.php"  target="_blank">' . $statusArray['tempdispurl'] . '</a></td>
                                        </tr>
                                        </table>';
                        }

                        $contents .='<p class="head">Admin Credentials</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Username</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            <tr>
            <td align="left" valign="top" >Password</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>';

                        $contentsTemp = "";
                        if($siteOperationParkDomain == 'Y'){
                            $contentsTemp = "In the mean time use the above temporary url to manage the site from the admin panel.";
                        }
                        $contents.='<tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: It may take 24 - 48 hrs for the domain to propagate to the site.
                                            ' . $contentsTemp . '
                                        </td>
                                    </tr>';

                        $contents .='</table>
                            <p class="head">Nameserver Details</p>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr>
                                        <td align="left" valign="top" width="20%">NameServer1</td>
                                        <td align="left" valign="top">:&nbsp;' .$ns1.'</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" >NameServer2</td>
                                        <td align="left" valign="top">:&nbsp;' .$ns2.'</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: Please update your domain nameserver details.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div></div></div>';
                    } else {
                        $productSetUpServiceId = User::productsetUpId(PRODUCT_PURCHASE_CATEGORY, $productId);
                        $dataArr = array();

                        if ($upgradeFlag == 1) {
                            $plId = $productLookUpId;
                            
                           
                            
                        }

                        $productRestriction = User::getPlanproductRestriction($productSetUpServiceId);

                        Utils::reconnect();

                        $contents = '<div class="storecration_instalation_wrapper" style="">
                                                                        <div class="storecration_instalation_wrapper_inner">
                                                                            <div class="instalation_completed_img "></div>

                                                                             <div class="pymnt_sucessmsgs" style="text-align:center;"><div class="store_success">
        <div class="store_success_label"></div>
            <h2>Congratulations!</h2>';
                        if ($upgradeFlag == 1) {
                            $dName = $idsld . '.' . $tld;
                            User::updateLookupEntry($plId, $dName, $userId, $userArray);
                            $contents.='
            <h3>The Upgrade Process was completed successfully!</h3>';
                        } else {
                            $contents.='
            <h3>Your installation was successful!</h3>';
                        }
                        $contents.='<p class="head">Site Login Details</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Admin URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . '/admins/"  target="_blank">' . $statusArray['returnurl'] . 'admin/</a></td>
            </tr>
            <tr>
            <td align="left" valign="top">Home URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'index.php"  target="_blank">' . $statusArray['returnurl'] . '</a></td>
            </tr>
            </table>';
                        if ($siteOperationParkDomain == 'Y') {
                            $contents.='<p class="head">Temporary Login Details</p>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                        <td align="left" valign="top" width="20%">Admin URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'admins/"  target="_blank">' . $statusArray['tempdispurl'] . 'admin/</a></td>
                                        </tr>
                                        <tr>
                                        <td align="left" valign="top">Home URL </td>
                                        <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'index.php"  target="_blank">' . $statusArray['tempdispurl'] . '</a></td>
                                        </tr>
                                        </table>';
                        }
                        $contents .='<p class="head">Admin Credentials</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Username</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            <tr>
            <td align="left" valign="top" >Password</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>';

                        $contentsTemp = "";
                        if ($siteOperationParkDomain == 'Y') {
                            $contentsTemp = "In the mean time use the above temporary url to manage the site from the admin panel.";
                        }
                        $contents.='<tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: It may take 24 - 48 hrs for the domain to propagate to the site.
                                            ' . $contentsTemp . '
                                        </td>
                                    </tr>';

                        $contents .='</table>
                            <p class="head">Nameserver Details</p>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="left" valign="top" width="20%">NameServer1</td>
                                    <td align="left" valign="top">:&nbsp;' . $ns1 . '</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" >NameServer2</td>
                                    <td align="left" valign="top">:&nbsp;' . $ns2 . '</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top" colspan="2">Note: Please update your domain nameserver details.</td>
                                </tr>
                            </table>
                        </div>
                        </div>
                        <div class="clear"></div>
                        </div>';
                    }

                    $data = array('success' => 1, 'list' => $contents);
                    echo json_encode($data);
                    /************************** cPanel instance creation Ends *************************/
                } else {
                    /**************************** cPanel Creation Failed *****************************/
                    $contents = "Account setup/Domain registration failed. Please be patient our customer care agent will fix the issue and inform you.";
                    $data = array('failed' => 1, 'list' => $contents);
                    echo json_encode($data);
                    die;
                }
            } else {
                
                if($status['list'])
                {
                    $contents = $status['list'];
                }else{
                    $contents = "Payment failed";
                }
                
                
                $data = array('failed' => 1, 'list' => $contents);
                echo json_encode($data);
            }
        } else {
            $contents = "$domainName is an invalid domain";
            $data = array('failed' => 1, 'list' => $contents);
            echo json_encode($data);
        }

        die;
    }

// End Function

    public function registerdomainotherpay(){

        $connection = new Db();

        if (isset(PageContext::$request['http_status'])){
            $objSession = new LibSession(); //echo $objSession->get('paymantflage');echopre($objSession->get('arrtwoPaySettings'));exit;
            if ($objSession->get('paymantflage') == 1) {
                $objSession->set('requestBrain', PageContext::$request);
                $this->redirect('index/registerdomainotherpaysucess/braintree/sucess');
                exit;
            }
        }


        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addScript('paynow.js');
        //set layout starts
        Utils::loadActiveTheme();
        //PageContext::$response->themeurl = BASE_URL.'themes/theme1/';
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');

        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");

        $this->view->setLayout("productpage");

        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //$this->view->disableView();
        $RegistrantFirstName        = $this->post("RegistrantFirstName");
        $RegistrantLastName         = $this->post("RegistrantLastName");
        $RegistrantJobTitle         = $this->post("RegistrantJobTitle");
        $RegistrantOrganizationName = $this->post("RegistrantOrganizationName");
        $RegistrantAddress1         = $this->post("RegistrantAddress1");
        $RegistrantAddress2         = $this->post("RegistrantAddress2");
        $idRegistrantCountry        = $this->post("idRegistrantCountry");
        $RegistrantCity             = $this->post("RegistrantCity");
        if(trim($idRegistrantCountry) == "US"){
            $RegistrantState        = $this->post("RegistrantState");
        }else{
            $RegistrantState        = $this->post("RegistrantStateOther");
        }
        if(trim($RegistrantState) == ""){
            $RegistrantState = "CA";
        }
        $RegistrantProvince         = $this->post("RegistrantProvince");
        $RegistrantPostalCode       = $this->post("RegistrantPostalCode");

        global $usStates;
        $registrantCountry          = $usStates[$idRegistrantCountry];
        $RegistrantFax              = $this->post("RegistrantFax");
        $RegistrantPhone            = $this->post("RegistrantPhone");
        $RegistrantEmailAddress     = $this->post("RegistrantEmailAddress");
        $domainFlag                 = $this->post('domainFlag');
        $tldPrice                   = $this->post('tldPrice');
        /*
         * Payment details area
         */
        $authorizeInfo              = array();
        $productArray               = array();
        $authorizeInfo['expMonth']  = $this->post('expM');
        $authorizeInfo['expYear']   = $this->post('expY');
        $authorizeInfo['cvv']       = $this->post('cvv');
        $authorizeInfo['ccno']      = $this->post('ccno');
        $authorizeInfo['fName']     = $this->post('RegistrantFirstName');
        $authorizeInfo['lName']     = $this->post('RegistrantLastName');
        $authorizeInfo['add1']      = $this->post('RegistrantAddress1');
        $authorizeInfo['city']      = $this->post('RegistrantCity');
        $authorizeInfo['country']   = $this->post('RegistrantCountry');
        if(trim($this->post('RegistrantCountry')) == "US"){
            $authorizeInfo['state'] = $this->post('RegistrantState');
        }else{
            $authorizeInfo['state'] = $this->post("RegistrantStateOther");
        }
        if(trim($authorizeInfo['state']) == ""){
            $authorizeInfo['state'] = "CA";
        }
        $authorizeInfo['zip']       = $this->post('RegistrantPostalCode');
        $authorizeInfo['email']     = $this->post('RegistrantEmailAddress');
        $authorizeInfo['amount']    = $this->post('ServiceAmount');
        $paymantArray   = array();
        $paymantArray['currentpaymant'] = $this->post('currentpaymantdomain');
        /* if($this->post('paymentmethod') != "")
          $paymantArray['paymentmethod'] = $this->post('paymentmethod'); */


        $productId = PRODUCT_ID;
        $productArray['id']                 = $productId;
        $productArray['packname']           = User::getproductPackName($productId);
        $productArray['permissionlist']     = User::getproductPermission($productId);
        $productArray['productreleaseid']   = User::getproductReleaseID($productId);
        $productArray['couponNo']           = $this->post('couponNumber');

        //******************** Product Services
        $productServices = $this->post('productId');
        $productSerArr = array();
        $productSerArr[0] = $productServices;
        $productArray['productServices'] = $productSerArr;
        //******************** Product Services End

        $idsld = $this->post("idsld");
        $tld = $this->post("tld");
        $NumYears = $this->post('NumYears');
        $UnLockRegistrar = $this->post('UnLockRegistrar');

        $domainName = $idsld . '.' . $tld;
        $domainName = str_replace("www.", '', $domainName);
        $domainName = str_replace("http://", '', $domainName);
        $domainRegisterFlag = 0;

        $productArray['planProductRestriction'] = User::getPlanproductRestriction($this->post('productId'));
        $productArray['xmlproductdata'] = User::setXmlData($productArray['planProductRestriction'], md5($domainName));

        $upgradeFlag = $this->post('upgradeFlag');
        $productLookUpId = $this->post('productLookUpId');

        if (Utils::isValidDomainname($idsld)) {
            /*
             * User registration area
             */
            if (LibSession::get('userID') == "") {
                $storeName = "My Account";
                $userEmail = $RegistrantEmailAddress;
                $userName = $RegistrantFirstName;
                $userLname = $RegistrantLastName;
                $userPassword = $RegistrantFirstName . '' . rand(1, 1000);
                $userArray = array(
                    'user_name' => $userName,
                    'user_email' => $userEmail,
                    'store_name' => $storeName,
                    'userpassw' => $userPassword,
                    'user_lname' => $userLname,
                );
                /*                 * ****** User Details Updation  ********** */
                $userUpdateArr = array();
                $userUpdateArr['vAddress'] = $RegistrantAddress1;
                $userUpdateArr['vCountry'] = $registrantCountry;
                $userUpdateArr['vState'] = $RegistrantState;
                $userUpdateArr['vCity'] = $RegistrantCity;
                $userUpdateArr['vZipcode'] = $RegistrantPostalCode;

                $userCreditArr = array();
                $userCreditArr['vFirstName'] = $RegistrantFirstName;
                $userCreditArr['vLastName'] = $RegistrantLastName;
                $userCreditArr['vNumber'] = $authorizeInfo['ccno'];
                $userCreditArr['vCode'] = $authorizeInfo['cvv'];
                $userCreditArr['vMonth'] = $authorizeInfo['expMonth'];
                $userCreditArr['vYear'] = $authorizeInfo['expYear'];
                $userCreditArr['vAddress'] = $RegistrantAddress1;
                $userCreditArr['vCity'] = $RegistrantCity;
                $userCreditArr['vState'] = $RegistrantState;
                $userCreditArr['vZipcode'] = $RegistrantPostalCode;
                $userCreditArr['vCountry'] = $registrantCountry;
                $userCreditArr['vEmail'] = $RegistrantEmailAddress;
                $userCreditArr['vUserIp'] = $_SERVER['REMOTE_ADDR'];

                /*                 * ****** User Details Updation  ********** */

                $userId = User::createUserAccount($userArray, $userUpdateArr, $userCreditArr);

                //------------Add user to supportdesk-----------------//
                /*include_once(BASE_PATH . "project/support/api/useradd.php");
                userAdd($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);*/

                User::$dbObj = new Db();
User::createSupportuser($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);



                //-----------Set supportdesk session----------//
                $sptbl_user = mysqli_query($connection,"SELECT * FROM sptbl_users WHERE vEmail = '{$userArray['user_email']}'");
                if (mysqli_num_rows($sptbl_user) > 0) {
                    $sptbl_res = mysqli_fetch_array($sptbl_user);

                    $_SESSION["sess_username"] = $sptbl_res['vUserName'];
                    $_SESSION["sess_userid"] = $sptbl_res['nUserId'];
                    $_SESSION["sess_useremail"] = $sptbl_res['vEmail'];
                    $_SESSION["sess_userfullname"] = $sptbl_res['vUserName'];
                    $_SESSION["sess_usercompid"] = 1;
                }

                LibSession::set('reg_usr_id', $userId);
            } else {
                $userId = LibSession::get('userID');

                /*         UpDating Billing Details        */
                $userDataArray = array();
                $userDataArray['nUserId'] = LibSession::get('userID');
                $userDataArray['vFirstName'] = $RegistrantFirstName;
                $userDataArray['vLastName'] = $RegistrantLastName;
                $userDataArray['vNumber'] = $authorizeInfo['ccno'];
                $userDataArray['vCode'] = $authorizeInfo['cvv'];
                $userDataArray['vMonth'] = $authorizeInfo['expMonth'];
                $userDataArray['vYear'] = $authorizeInfo['expYear'];
                $userDataArray['vAddress'] = $RegistrantAddress1;
                $userDataArray['vCity'] = $RegistrantCity;
                $userDataArray['vState'] = $RegistrantState;
                $userDataArray['vZipcode'] = $RegistrantPostalCode;
                $userDataArray['vCountry'] = $registrantCountry;
                $userDataArray['vEmail'] = $RegistrantEmailAddress;

                $tbs = User::updateUserCreditCardDetails($userDataArray);

                /*        Ending of  UpDating Billing Details        */
            }


            /*
             * Session value setup
             */

            $userFullInfoArr = Admincomponents::getUserdetails($userId);

            LibSession::set('reg_usr_id', $userId);
            LibSession::set('userID', $userId);
            LibSession::set('firstName', $userFullInfoArr->vFirstName);
            LibSession::set('planid', 1);
            LibSession::set('planpackage', 1);
            LibSession::set('purchase_amt', $this->post('ServiceAmount'));
            LibSession::set('package_desc', 'Full Pack');
            LibSession::set('productid', $productId);
            LibSession::set('productreleaseid', $productArray['productreleaseid']);

            // Wallet Balance Check
            $walletBalance = $walletDiscount = $walletNewBalance = $discount = 0;
            $totalAmount = $authorizeInfo['amount'];
            if (!empty($userId)) {
                $walletBalance = Admincomponents::getUserWalletBalance($userId);
                $walletDiscount +=($totalAmount < $walletBalance) ? $totalAmount : $walletBalance;
                $walletNewBalance = $walletBalance - $walletDiscount;
                $discount +=$walletDiscount;
                /*                 * ******************** Update Wallet ********************** */
                $updateWalletArr = array();
                $updateWalletArr['nUId'] = $userId;
                $updateWalletArr['newBalance'] = $walletNewBalance;
                Admincomponents::updateWallet($updateWalletArr);
                /*                 * ******************** Update Wallet ********************** */
                $authorizeInfo['amount'] = $totalAmount - $discount;
            } // End If
            // End Wallet Balance Check
//*****Akhil Paymant code other paymant //****



            $arrtwoPaySettings = array();
            //   $authorizeInfo['amount'] = 0.05;
            $arrtwoPaySettings['Grandtotal'] = urlencode($authorizeInfo['amount']);
            $arrtwoPaySettings['Currency'] = urlencode(CURRENCY); // urlencode('USD');
            $arrtwoPaySettings['ItemNumber'] = NULL;

            $objSession = new LibSession();
            if ($objSession->get('paymantflage') == "") {
                $objSession->set('authorizeInfo', $authorizeInfo);
                $objSession->set('domainName', $domainName);
                $objSession->set('userArray', $userArray);
                $objSession->set('userId', $userId);
                $objSession->set('productArray', $productArray);
                $objSession->set('status', $status);
                $objSession->set('upgradeFlag', $upgradeFlag);
                $objSession->set('productLookUpId', $productLookUpId);
                $objSession->set('idsld', $idsld);
                $objSession->set('tld', $tld);
                $objSession->set('postdata', $_POST);
            }

// Paymant check ********************
            //echo $paymantArray['currentpaymant'];exit;
            if ($paymantArray['currentpaymant'] == 'twocheckout') { // if paymant method is paypalpro
                $twocheckoutSettings = Payments::getTwoCheckoutSettings();
                // $arrtwoPaySettings = array();
                $arrtwoPaySettings['Vendorid'] = $twocheckoutSettings['TwoCheckoutvendorid']; //'1877160'; // vendor id from payment settings
                $arrtwoPaySettings['Company'] = "-NA-";
                $arrtwoPaySettings['Email'] = $authorizeInfo['email']; // User Email
                $arrtwoPaySettings['Currency'] = 'USD';
                if ($twocheckoutSettings['TwoCheckouttestmode'] == 'Y')
                    $arrtwoPaySettings['Testmode'] = "Y";

                $arrtwoPaySettings['Cartid'] = rand(1, 1000);
                $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/sucess";
                // $arrtwoPaySettings['ReturnURL'] = BASE_URL . "payments/twocheckout/sucess";

                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                    $objSession->set('paymantflage', 1);
                }


                PageContext::$response->renderPaymant = Payments::payTwoCheckout($arrtwoPaySettings);
            } else if ($paymantArray['currentpaymant'] == 'paypalflowlink') {
                $paypalflowlinkSettings = Payments::getPaypallinkSettings();
                $arrtwoPaySettings['Paypallinkvendorid'] = $paypalflowlinkSettings['Paypalflowlinkvendorid']; // "armiapayflow";
                $arrtwoPaySettings['Paypallinkpartnerid'] = $paypalflowlinkSettings['Paypalflowlinkpartnerid']; //  'PayPal';
                $arrtwoPaySettings['Paymenttype'] = 'S'; // Constant
                $arrtwoPaySettings['Method'] = 'CC'; // Constant
                // $arrtwoPaySettings['Grandtotal'] = '0.05';
                $arrtwoPaySettings['Customerid'] = rand(1, 10000);
                $arrtwoPaySettings['Orderform'] = true;
                $arrtwoPaySettings['Showconfirm'] = true;

                if ($paypalflowlinkSettings['Paypalflowlinktestmode'] == 'Y')
                    $arrtwoPaySettings['Testmode'] = 'Y';

                $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/sucess";

                $arrtwoPaySettings['Firstname'] = urlencode($authorizeInfo['fName']);
                $arrtwoPaySettings['Address'] = urlencode($authorizeInfo['add1']);
                $arrtwoPaySettings['City'] = urlencode($authorizeInfo['city']);
                $arrtwoPaySettings['Zip'] = urlencode($authorizeInfo['zip']);
                $arrtwoPaySettings['Country'] = urlencode($authorizeInfo['country']);
                $arrtwoPaySettings['Currency'] = urlencode('USD');
                $arrtwoPaySettings['Phone'] = '';
                $arrtwoPaySettings['Fax'] = '';
//echopre($arrtwoPaySettings);exit;
                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                    $objSession->set('paymantflage', 1);
                }


                PageContext::$response->renderPaymant = Payments::payPaypalflowlink($arrtwoPaySettings);
            } else if ($paymantArray['currentpaymant'] == 'paypalxpress') {
                $paypalXpresSettings = Payments::getPaypalXpresSettings();
                $arrtwoPaySettings['Paypalexpressusername'] = $paypalXpresSettings['PaypalXpresUsername']; //"seller_1297271002_biz_api1.yahoo.com";
                $arrtwoPaySettings['Paypalexpresspassword'] = $paypalXpresSettings['PaypalXpresPassword']; //'1297271011';
                $arrtwoPaySettings['Paypalexpresssignature'] = $paypalXpresSettings['PaypalXpresSignature']; //'AFcWxV21C7fd0v3bYYYRCpSSRl31A-Vd1YRxIrhGWvUd2XnlrhGdk6rY';


                if ($paypalXpresSettings['PaypalXprestestmode'] == 'Y')
                    $arrtwoPaySettings['Testmode'] = 'Y';

                $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/sucess";
                $arrtwoPaySettings['CancelURL'] = BASE_URL . BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/cancel";

                //set session pf payment
                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                    $objSession->set('paymantflage', 1);
                }


                $redirectURL = Payments::payPaypalexpress($arrtwoPaySettings);
                if ($redirectURL != "") {
                    Headerredirect::httpRedirect($redirectURL);
                }
            } else if ($paymantArray['currentpaymant'] == 'paypaladvanced') {
                $paypaladvancedSettings = Payments::getPaypaladvancedSettings();
                $arrtwoPaySettings['Paypaladvancedvendorid'] = $paypaladvancedSettings['Paypaladvancedvendorid']; //"palexanderpayflowtest";
                $arrtwoPaySettings['Paypaladvancedpassword'] = $paypaladvancedSettings['Paypaladvancedpassword']; //'demopass123';
                $arrtwoPaySettings['Paypaladvancedpartner'] = $paypaladvancedSettings['Paypaladvancedpartner']; //'PayPal';
                $arrtwoPaySettings['Paypaladvanceduser'] = $paypaladvancedSettings['Paypaladvancedusername']; //'palexanderpayflowtestapionly';


                $arrtwoPaySettings['Paymenttype'] = urlencode('A');
                $arrtwoPaySettings['Createsecuretocken'] = 'Y';
                // $arrtwoPaySettings['Currency'] = "USD";
                $arrtwoPaySettings['Securetockenid'] = uniqid('MySecTokenID-');


                if ($paypaladvancedSettings['Paypaladvancedtestmode'] == "Y")
                    $arrtwoPaySettings['Testmode'] = 'Y';

                $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/sucess";
                $arrtwoPaySettings['CancelURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/cancel";
                $arrtwoPaySettings['ErrorURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/error";

                $arrtwoPaySettings['Billtofirstname'] = $authorizeInfo['fName'];
                $arrtwoPaySettings['Billtolastname'] = $authorizeInfo['lName'];
                $arrtwoPaySettings['Billtostreet'] = $authorizeInfo['add1'];
                $arrtwoPaySettings['Billtocity'] = $authorizeInfo['city'];
                $arrtwoPaySettings['Country'] = $authorizeInfo['country'];

                //set session pf payment
                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                    $objSession->set('paymantflage', 1);
                }

                $redirectURL = Payments::setPaypaladvancedUrl(Payments::payPaypaladvanced($arrtwoPaySettings), $arrtwoPaySettings);
                if ($redirectURL != false) {
                    Headerredirect::httpRedirect($redirectURL);
                }
            } else if ($paymantArray['currentpaymant'] == 'braintree') {

                $braintreeSettings = Payments::getBraintreeSettings();
                $arrtwoPaySettings['Braintreemerchantid'] = $braintreeSettings['BraintreemerchantId']; //"f7mgykzp5b7txjf7";
                $arrtwoPaySettings['Braintreepublickey'] = $braintreeSettings['Braintreepublickey']; //'qfhh854tm6g6md9x';
                $arrtwoPaySettings['Braintreeprivatekey'] = $braintreeSettings['Braintreeprivatekey']; //'863323bad983dc6eca5dea1a7913a90f';
                $arrtwoPaySettings['Paymenttype'] = 'sale'; // Constant
                if ($braintreeSettings['Braintreetestmode'] == "Y")
                    $arrtwoPaySettings['Testmode'] = 'Y';


                $arrtwoPaySettings['Firstname'] = $authorizeInfo['fName'];
                $arrtwoPaySettings['Lastname'] = $authorizeInfo['lName'];
                $arrtwoPaySettings['Email'] = $authorizeInfo['email'];

                //set session pf payment
                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                    $objSession->set('paymantflage', 1);
                }

                $configValues = Payments::payBreantree($arrtwoPaySettings);
                if (isset($configValues) && count($configValues) > 0) {

                    $renderFrom = '<form action="' . $configValues['form_url'] . '" method="post" name="frmPayment" >';
                    $renderFrom .='<table width="40%"  border="0" cellspacing="4" cellpadding="0" align="center">
  <tr>
    <td align="left">Card Number</td>
    <td align="left"><input type="text" size="27" class="box2_admin" value="" maxlength="16" id="txtCCNumber" name="transaction[credit_card][number]"></td>
  </tr>
  <tr>
    <td align="left">Expiry Date(MM/YYYY)</td>
    <td align="left"><input type="text" size="27" class="box2_admin" maxlength="10" id="expiration_date" value="" name="transaction[credit_card][expiration_date]"></td>
  </tr>
  <tr>
    <td align="left">CVV/CVV2 No</td>
    <td align="left"><input type="text" size="27" class="box2_admin" maxlength="10" value="" id="txtCVV2" name="transaction[credit_card][cvv]"></td>
  </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td align="left" valign="top" height="35"><input type="hidden" name="tr_data" value="' . $configValues['tr_data'] . '" />
                                        <input type="hidden" name="transaction[customer][first_name]" value="' . $configValues['firstName'] . '" />
                                        <input type="hidden" name="transaction[customer][last_name]" value="' . $configValues['lastName'] . '" />
                                        <input type="hidden" name="transaction[customer][email]" value="' . $configValues['email'] . '" />
                    <br><input type="submit"  name="btnCompleteOrderbraintree" value="Pay Now" onclick="return validateForm(document.frmPayment);" class="btn-usr01"></td>
  </tr>
</table>';




                    $renderFrom .= '</form>';

                    PageContext::$response->renderPaymant = $renderFrom;
                }
            } else if ($paymantArray['currentpaymant'] == 'ogone') {

                $ogoneSettings = Payments::getOgoneSettings();
                $arrtwoPaySettings['Ogonepspid'] = $ogoneSettings['Ogonepartnerid']; // "rajath";
                $arrtwoPaySettings['Ogonepassphrase'] = $ogoneSettings['Ogonevendorid']; //'shainarmia247~!@';

                if ($ogoneSettings['Ogonetestmode'] == "Y")
                    $arrtwoPaySettings['Testmode'] = 'Y';

                $arrtwoPaySettings['DeclineURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/decline";
                $arrtwoPaySettings['CancelURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/cancel";
                $arrtwoPaySettings['ExceptionURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/exception";
                $arrtwoPaySettings['AcceptURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/sucess";
                ; //sucess return url

                $arrtwoPaySettings['Orderid'] = RAND(10000, 895689596);

                $arrtwoPaySettings['Language'] = "en_us";
                $arrtwoPaySettings['Logo'] = "Logo.jpg";
                $arrtwoPaySettings['Operation'] = 'SAL'; //Constant

                $arrtwoPaySettings['Firstname'] = $authorizeInfo['fName'];
                $arrtwoPaySettings['Lastname'] = $authorizeInfo['lName'];
                $arrtwoPaySettings['Email'] = $authorizeInfo['email'];
                //set session pf payment
                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                    $objSession->set('paymantflage', 1);
                }
                PageContext::$response->renderPaymant = Payments::payOgone($arrtwoPaySettings);
            } else if ($paymantArray['currentpaymant'] == 'paypal') {

                $paypalSettings = Payments::getPaypalSettings();

                $arrtwoPaySettings['Paypalemail'] = $paypalSettings['Paypalemail']; //"mahi_1_1321000734_biz@yahoo.com";
                $arrtwoPaySettings['resultURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/sucess";
                $arrtwoPaySettings['cancelURL'] = BASE_URL . "index/registerdomainotherpaysucess/" . $paymantArray['currentpaymant'] . "/cancel";
                $arrtwoPaySettings['notifyURL'] = BASE_URL . "index/otherpaymentipn/" . $paymantArray['currentpaymant'] . "/ipn";
                $planDetails = User::getPlanDetails($productServices);
                $arrtwoPaySettings['Itemname'] = $planDetails[0]->vServiceName;
                $arrtwoPaySettings['ItemNumber'] = Admincomponents::getTransactionSessionID();
                $arrtwoPaySettings['Transactid'] = RAND(10000, 895689596);

                $planPrice = $planDetails[0]->price;
                $planDuration = $planDetails[0]->vBillingInterval;
                $planDuration = ($planDuration == 'M') ? 'D' : $planDuration;
                $planDurationLength = $planDetails[0]->nBillingDuration;

                // subscription related variables
                $arrtwoPaySettings['a1'] = $authorizeInfo['amount']; // price_of_first_trial_period
                $arrtwoPaySettings['p1'] = $planDurationLength; // duration_length_of_first_trial_period
                $arrtwoPaySettings['t1'] = $planDuration; // duration_of_first_trial_period
                $arrtwoPaySettings['a3'] = $planPrice; // price_of_subscription
                $arrtwoPaySettings['p3'] = $planDurationLength; // length_of_the_regular_billing_cycle
                $arrtwoPaySettings['t3'] = $planDuration; // regular_billing_cycle_units

                $arrtwoPaySettings['src'] = '1';
                $arrtwoPaySettings['sra'] = '1';
                $arrtwoPaySettings['no_note'] = '1';
                $arrtwoPaySettings['modify'] = '0';
                $arrtwoPaySettings['subscr_date'] = date('Y-m-d');

                if ($paypalSettings['Paypaltestmode'] == "Y")
                    $arrtwoPaySettings['Testmode'] = 'Y';
                //set session pf payment
                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                    $objSession->set('paymantflage', 1);
                }
                //   echopre($arrtwoPaySettings);exit;
                PageContext::$response->renderPaymant = Payments::paypalsubscription($arrtwoPaySettings);
            } else if ($paymantArray['currentpaymant'] == 'googlecheckout') {


                // assign the product informations
                $arrGCheckDetails['items']['item_name'] = 'GoStores';
                $arrGCheckDetails['items']['item_desc'] = 'Gostores multicart';
                $arrGCheckDetails['items']['count'] = 1;
                $arrGCheckDetails['items']['amount'] = $authorizeInfo['amount'];


                $arrGCheckDetails['url_edit_cart'] = BASE_URL . "index/otherpaymantbuy";


                $arrGCheckDetails['url_continue_shopping'] = BASE_URL . "index/registerdomainotherpaysucess/googlecheckout/success";
                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrGooglecheckout', $arrGCheckDetails);
                    $objSession->set('paymantflage', 1);
                }


                PageContext::$response->renderPaymant = Payments::doGoogleCheckOut($arrGCheckDetails);
            } else if ($paymantArray['currentpaymant'] == 'yourpay') {

                //echopre(PageContext::$request);
                $YourPaySettings = Payments::getYoursPaySettings();
                $arrYourPay['yourpay_storeid'] = $YourPaySettings['yourpay_storeid'];
                $arrYourPay['yourpay_demo'] = $YourPaySettings['yourpay_demo'];
                $arrYourPay['ordertype'] = "SALE";
                $arrYourPay['userinfo'] = $authorizeInfo;
                $arrYourPay['yp_cardno'] = PageContext::$request['yp_cardno'];
                $arrYourPay['yp_expm'] = PageContext::$request['yp_expm'];
                $arrYourPay['yp_expy'] = PageContext::$request['yp_expy'];
                $arrYourPay['yp_cvno'] = PageContext::$request['yp_cvno'];
                $resPayment = Payments::doYourPay($arrYourPay);

                //TODO : need to add the transaction checking
                $result = Payments::chkYourPay($resPayment, $arrtwoPaySettings);
                if ($result) {  // lets hope the payment success
                    $this->redirect('index/otherpaymantsucess/yourpay/success/transactid/');
                    exit;
                } else {  // the payment fails
                    $this->redirect('index/paynow/2/error');
                    exit;
                }
            } else if ($paymantArray['currentpaymant'] == 'moneybookers') {

                $MoneyBookerSettings = Payments::getMoneyBookersSettings();

                $moneyBookersInfo = array();
                $moneyBookersInfo['pay_to_email'] = $MoneyBookerSettings['moneybookers_emailid'];
                //  $moneyBookersInfo['status_url']             = BASE_URL . "index/otherpaymantsucess/moneybookers/sucess";
                $moneyBookersInfo['status_url'] = 'http://clients.iscripts.com/testspace/googlecheckout.php';
                $moneyBookersInfo['language'] = 'EN';
                $moneyBookersInfo['amount'] = 1;
                $moneyBookersInfo['currency'] = 'USD';
                $moneyBookersInfo['detail1_description'] = 'Description';
                $moneyBookersInfo['detail1_text'] = 'Order Purchase';
                $moneyBookersInfo['return_url'] = BASE_URL . "index/registerdomainotherpaysucess/moneybookers/success";
                $moneyBookersInfo['confirmation_note'] = "Payment Sucess";



                if ($objSession->get('paymantflage') == "") {
                    $objSession->set('arrMoneyBookers', $moneyBookersInfo);
                    $objSession->set('paymantflage', 1);
                }
                PageContext::$response->renderPaymant = Payments::doMoneyBookers($moneyBookersInfo);
            } else if ($paymantArray['currentpaymant'] == 'quickbookdomain') {

                $quickbookSettings = Payments::getQuickBookSettings();
                $quickbookSettings['qb_cardno'] = PageContext::$request['yp_cardno'];
                $quickbookSettings['qb_expm'] = PageContext::$request['yp_expm'];
                $quickbookSettings['qb_expy'] = PageContext::$request['yp_expy'];
                $quickbookSettings['qb_cvno'] = PageContext::$request['yp_cvno'];
                $quickbookSettings['amount'] = $authorizeInfo['amount'];

                $quickbookSettings['transid'] = rand(1, 1000);

                $quickbookSettings['datetime'] = date("Y-m-d H:i:s");
                if ($quickbookSettings['quickbook_testmode'] == 'Y')
                    $quickbookSettings['host'] = 'https://webmerchantaccount.ptc.quickbooks.com/j/AppGateway';
                else
                    $quickbookSettings['host'] = 'https://merchantaccount.ptc.quickbooks.com/j/AppGateway';



                $result = Payments::doQuickbookPayment($quickbookSettings);
                $_SESSION['quickbookpay'] = $result;
                if ($result['success'] == 1) {
                    $this->redirect('index/registerdomainotherpaysucess/quickbook/success/');
                    exit;
                } else {  // the payment fails
                    $this->redirect('index/registerdomainotherpaysucess/quickbook/paymentfailed');
                    exit;
                }
            }
            //************Akhil Paymant code ends**************
        //

        } else {
            $contents = "$domainName is an invalid domain";
            //$data = array('failed' => 1, 'list' => $contents);
            //echo json_encode($data);
        }
    }

    public function registerdomainotherpaysucess($paystatus = "", $msg = ""){
        $siteOperationParkDomain = OPERATION_MODE_PARK_DOMAIN;
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addScript('paynow.js');
        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");

        //set layout starts
        Utils::loadActiveTheme();
        //PageContext::$response->themeurl = BASE_URL.'themes/theme1/';
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //set layout ends
        $objSession = new LibSession();

        //$objSession->set('paymantflage',  1);

        if ($objSession->get('paymantflage') == 1){
            $authorizeInfo      = $objSession->get('authorizeInfo');
            $domainName         = $objSession->get('domainName');
            $userArray          = $objSession->get('userArray');
            $userId             = $objSession->get('userId');
            $productArray       = $objSession->get('productArray');
            $status             = $objSession->get('status');
            $upgradeFlag        = $objSession->get('upgradeFlag');
            $productLookUpId    = $objSession->get('productLookUpId');
            $arrtwoPaySettings  = $objSession->get('arrtwoPaySettings');
            $idsld              = $objSession->get('idsld');
            $tld                = $objSession->get('tld');
            $postdata           = $objSession->get('postdata');
            $arrGcheckSettings  = $objSession->get('arrGooglecheckout');
            $arrMoneyBookers    = $objSession->get('arrMoneyBookers');
            $productArray["transactionSession"] = $arrtwoPaySettings["ItemNumber"];

            $RegistrantFirstName        = $authorizeInfo['fName'];
            $RegistrantLastName         = $authorizeInfo['lName'];
            $RegistrantAddress2         = $authorizeInfo['add1'];
            $RegistrantCity             = $authorizeInfo['city'];
            $RegistrantState            = $authorizeInfo['state'];
            $idRegistrantCountry        = $authorizeInfo['country'];
            $RegistrantEmailAddress     = $authorizeInfo['email'];
            $RegistrantJobTitle         = $postdata['RegistrantJobTitle'];
            $RegistrantOrganizationName = $postdata['RegistrantOrganizationName'];
            $RegistrantProvince         = $postdata['RegistrantProvince'];
            $RegistrantPostalCode       = $postdata['RegistrantPostalCode'];
            $RegistrantFax              = $postdata['RegistrantFax'];
            $RegistrantPhone            = $postdata['RegistrantPhone'];
            $NumYears                   = $postdata['NumYears'];
            $RegistrantAddress1         = $postdata['RegistrantAddress1'];
            $UnLockRegistrar            = $postdata['UnLockRegistrar'];
            $tldPrice                   = $postdata['tldPrice'];

            $objSession->set('paymantflage', "");

            if (isset($paystatus) && $paystatus == 'twocheckout') {
                $status = Payments::chkTwoCheckoutPayment(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypalflowlink') {
                $status = Payments::chkPaypalflowlink(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypalxpress') {
                $status = Payments::chkpayPaypalexpress(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypaladvanced') {
                $status = Payments::chkPaypaladvanced(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'braintree' && isset($msg) && $msg == 'sucess') {
                $braintreeResponce = $objSession->get('requestBrain');
                $status = Payments::chkBreantree($braintreeResponce, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'ogone' && isset($msg) && $msg == 'sucess') {
                $status = Payments::chkOgone(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypal' && isset($msg) && $msg == 'sucess') {
                $status = Payments::chkPaypal(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'googlecheckout') {
                $status = Payments::chkGoogleCheckOut(PageContext::$request, $arrGcheckSettings);
            } else if (isset($paystatus) && $paystatus == 'moneybookers') {
                $status = Payments::chkMoneyBookers(PageContext::$request, $arrMoneyBookers);
            } else if (isset($paystatus) && $paystatus == 'quickbook') {
                $status = $_SESSION['quickbookpay'];
            }

            $domainFlag = User::checkdomainispurchased($domainName);
            //$domainFlag = 1; //$_SESSION["domainFlag"];

            // Append Payment Status
            $status['paymentMethod'] = $paystatus;

            if($status['success'] == 1){
                //if(1){
                User::storePaymentsEntry($status['Amount'], $paystatus, $status['TransactionId']);
                $upgrade = NULL;
                if ($domainFlag == 1){ //If new domain then purchase it
                    $messageArray = User::registerdomain($RegistrantFirstName, $RegistrantLastName, $RegistrantJobTitle, $RegistrantOrganizationName, $RegistrantAddress2, $RegistrantCity, $RegistrantState, $RegistrantProvince, $RegistrantPostalCode, $idRegistrantCountry, $RegistrantFax, $RegistrantPhone, $RegistrantEmailAddress, $idsld, $tld, $NumYears, $RegistrantAddress1, $UnLockRegistrar);

                    if ($messageArray['status'] == 1) {
                        $domainRegisterFlag = 1;
                    }else{
                        $error_contents = $messageArray['message'];
//                        $data = array('failed' => 1, 'list' => $contents);
//                        echo json_encode($data);
//                        die;
                    }
                }else{
                    $domainRegisterFlag = 1;
                }

                if ($domainRegisterFlag == 1){
                    PageContext::includePath('cpanel');
                    $cpanelObj  = new cpanel();
                    $username   = substr(strtolower($RegistrantFirstName), 0, 3);
                    $username   = $username . substr(md5($RegistrantFirstName . time()), 0, 3);
                    $password   = substr(md5($RegistrantFirstName), 0, 8);
                    $dataArr    = array();

                    $userArray = array(
                        "user_name"     => $RegistrantFirstName,
                        "user_email"    => $RegistrantEmailAddress,
                        "store_name"    => $domainName,
                        "userpassw"     => $password,
                        "user_lname"    => $RegistrantLastName,
                    );

                    if ($upgradeFlag == 0){
                        $statusArray = $cpanelObj->createcpanelaccount($username, $password, $domainName, $RegistrantEmailAddress, $productArray);

                        Utils::reconnect();
                        if ($statusArray['status'] == 1) {

                            //Additional account details values for User Array
                            $userArray["c_user"] = $username;
                            $userArray["c_pass"] = $password;
                            $userArray["c_host"] = $domainName;
                            $userArray["sld"] = $idsld;
                            $userArray["tld"] = $tld;
                            $userArray["tempdispurl"] = "";

                            if ($siteOperationParkDomain == 'Y') {

                                if (isset($statusArray['tempdispurl']) && !empty($statusArray['tempdispurl'])) {
                                    $userArray["tempdispurl"] = $statusArray['tempdispurl'];
                                }
                            }


                            $plId = User::addLookupEntry($userArray, $domainName, $userId, 1);
                            if (LibSession::get('mailSendFlag') == 1) {
                                User::sendMail($userArray);
                                LibSession::set('mailSendFlag', 0);
                            } else {
                                LibSession::set('userID', $userId);
                            }
                        } else {
                            //Failed
                            if (isset($statusArray['tech_statusmsg']) && trim($statusArray['tech_statusmsg']) <> '') {
                                $contents = $statusArray['tech_statusmsg'];
                            } else {
                                $contents = "Account setup failed. Please be patient our customer care agent will fix the issue and inform you.";
                            }

//                                $data = array('failed' => 1, 'list' => $contents);
//                                echo json_encode($data);
//                                die;
                        }
                    } else {
                        $productArray['domain'] = $domainName;
                        $plId = $productLookUpId;
                        $accountDetails = unserialize(User::getserverDetails($plId));

                        //Additional account details values for User Array
                        $userArray["c_user"] = $accountDetails['c_user'];
                        $userArray["c_pass"] = $accountDetails['c_pass'];
                        $userArray["c_host"] = $domainName;
                        $userArray["sld"] = $idsld;
                        $userArray["tld"] = $tld;
                        $userArray["tempdispurl"] = "";


                        $statusArray = $cpanelObj->upgradeaccount($accountDetails['c_user'], $accountDetails['c_pass'], $accountDetails['c_host'], $RegistrantEmailAddress, $productArray);
                        $upgrade = 1;
                        Utils::reconnect();

                        // Get Temporrary URL for UserArray
                        if ($siteOperationParkDomain == 'Y') {

                            if (isset($statusArray['tempdispurl']) && !empty($statusArray['tempdispurl'])) {
                                $userArray["tempdispurl"] = $statusArray['tempdispurl'];
                            }
                        }
                    }

                    $productSetUpServiceId = User::productsetUpId(PRODUCT_PURCHASE_CATEGORY, $productId);
                    $productRestriction = User::getPlanproductRestriction($productSetUpServiceId);

                    // Tld Unit Price
                    $tldUnitPrice = Utils::formatPrice($tldPrice / $NumYears);

                    // Update Transaction Session
                    $transactionSession = $productArray["transactionSession"];
                    Admincomponents::saveTransactionSessionID($transactionSession, $plId);

                    $dataArr = array(
                        'nUId' => $userId,
                        'nPLId' => $plId,
                        'services' => $productArray['productServices'],
                        'domainService' => array('nSCatId' => DOMAIN_REGISTRATION_ID, 'appendDescription' => '', 'rate' => $tldUnitPrice, 'year' => $NumYears),
                        'couponNo' => $productArray['couponNo'],
                        'terms' => '',
                        'notes' => '',
                        'paymentstatus' => 'paid',
                        'vMethod' => $status['paymentMethod'],
                        'vTxnId' => $status['transactionId'],
                        'upgrade' => $upgradeFlag,
                        'subscriptionType' => 'PAID'
                         );


                    Utils::reconnect();
                    Admincomponents::generateInvoice($dataArr);

                    // Name Server details
                    User::$dbObj = new Db();
                    $ns1 = User::$dbObj->selectRow("Settings", "value", "settingfield='name_server_1'");
                    $ns2 = User::$dbObj->selectRow("Settings", "value", "settingfield='name_server_2'");

                    //success
                    $contents = "Congratulations! Your installation was successful!<br>
Site Login Details<br>
Admin URL : <a href='" . $statusArray['returnurl'] . "/admins/' target='_blank'>" . $statusArray['returnurl'] . "admins/</a><br>
Admin Credentials : Username : admin<br>
Password : admin<br>
Home URL :  <a href='" . $statusArray['returnurl'] . "index.php' target='_blank'>" . $statusArray['returnurl'] . "</a><br>
";
                    
                    
                    
                    
                    if ($domainFlag == 0) {
                        $contents = '<div class="store_success">
        <div class="store_success_label"></div>
            <h2>Congratulations!</h2>';
                        if ($upgradeFlag == 1) {
                            $dName = $idsld . '.' . $tld;
                            User::updateLookupEntry($plId, $dName, $userId, $userArray);
                            $contents.='
            <h3>The Upgrade Process was completed successfully!</h3>';
                        } else {
                            $contents.='
            <h3>Your installation was successful!</h3>';
                        }

                        $contents.='<p class="head">Site Login Details</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Admin URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'admins/"  target="_blank">' . $statusArray['returnurl'] . 'admin/</a></td>
            </tr>
            <tr>
            <td align="left" valign="top">Home URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'index.php"  target="_blank">' . $statusArray['returnurl'] . '</a></td>
            </tr>
            </table>';

                        if ($siteOperationParkDomain == 'Y') {

                            $contents.='<p class="head">Temporary Login Details</p>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="left" valign="top" width="20%">Admin URL </td>
                                    <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'admins/"  target="_blank">' . $statusArray['tempdispurl'] . 'admin/</a></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">Home URL </td>
                                    <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'index.php"  target="_blank">' . $statusArray['tempdispurl'] . '</a></td>
                                </tr>
                            </table>';
                        }

                        $contents .='<p class="head">Admin Credentials</p>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
            <td align="left" valign="top" width="20%">Username</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            <tr>
            <td align="left" valign="top" >Password</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>';

                        if ($siteOperationParkDomain == 'Y') {
                            // If parked domain is enabled notifications differ
                            $contentsTemp = "In the mean time use the above temporary url to manage the site from the admin panel.";
                        }

                        $contents.='<tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: It may take 24 - 48 hrs for the domain to propagate to the site.
                                            ' . $contentsTemp . '
                                        </td>
                                    </tr>';
                        
                        
                        
                        
                        

                        $contents .='</table>
                                <p class="head">Nameserver Details</p>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td align="left" valign="top" width="20%">NameServer1</td>
                                            <td align="left" valign="top">:&nbsp;' . $ns1 . '</td>
                                        </tr>
                                        <tr>
                                        <td align="left" valign="top" >NameServer2</td>
                                        <td align="left" valign="top">:&nbsp;' . $ns2 . '</td>
                                        </tr>
                                    <tr>
                                    <td align="left" valign="top" colspan="2">Note: Please update your domain nameserver details.</td>
                                    </tr>
                                    </table>';

                        $contents .='</div>';
                    } else {

                        $productSetUpServiceId = User::productsetUpId(PRODUCT_PURCHASE_CATEGORY, $productId);
                        $productRestriction = User::getPlanproductRestriction($productSetUpServiceId);

                        $dataArr = array();
                        if ($upgradeFlag == 1) {
                            $plId = $productLookUpId;
                        }

                        Utils::reconnect();


                        $contents = ' <div class="store_success">
        <div class="store_success_label"></div>
            <h2>Congratulations!</h2>';
                        if ($upgradeFlag == 1) {
                            $contents.='<h3>The Upgrade Process was completed successfully!</h3>';
                        } else {
                            $contents.='<h3>Your installation was successful!</h3>';
                        }
                        $contents.='<p class="head">Site Login Details</p>
            <table cellpadding="0" cellspacing="0" border="0" width="400px" align="center" >
            <tr>
            <td align="left" valign="top" width="20%">Admin URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . '/admins/"  target="_blank">' . $statusArray['returnurl'] . 'admin/</a></td>
            </tr>
            <tr>
            <td align="left" valign="top">Home URL </td>
            <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['returnurl'] . 'index.php"  target="_blank">' . $statusArray['returnurl'] . '</a></td>
            </tr>
            </table>';
                        if ($siteOperationParkDomain == 'Y') {
                            $contents.='<p class="head">Temporary Login Details</p>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                <td align="left" valign="top" width="20%">Admin URL </td>
                                <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'admins/"  target="_blank">' . $statusArray['tempdispurl'] . 'admin/</a></td>
                                </tr>
                                <tr>
                                <td align="left" valign="top">Home URL </td>
                                <td align="left" valign="top">:&nbsp;<a href="' . $statusArray['tempdispurl'] . 'index.php"  target="_blank">' . $statusArray['tempdispurl'] . '</a></td>
                                </tr>
                                </table>';
                        }

                        $contents.='<p class="head">Admin Credentials</p>
            <table cellpadding="0" cellspacing="0" border="0" width="400px" align="center">
            <tr>
            <td align="left" valign="top" width="20%">Username</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>
            <tr>
            <td align="left" valign="top" >Password</td>
            <td align="left" valign="top">:&nbsp;admin</td>
            </tr>';

                        $contentsTemp = "";

                        if ($siteOperationParkDomain == 'Y') {
                            $contentsTemp = "In the mean time use the above temporary url to manage the site from the admin panel.";
                        }

                        $contents.='<tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: It may take 24 - 48 hrs for the domain to propagate to the site.
                                            ' . $contentsTemp . '
                                        </td>
                                    </tr>';
                        
                        
                        
                        

                        $contents.='</table>
                            <p class="head">Nameserver Details</p>
                                <table cellpadding="0" cellspacing="0" border="0" width="400px" align="center">
                                    <tr>
                                        <td align="left" valign="top" width="20%">NameServer1</td>
                                        <td align="left" valign="top">:&nbsp;' . $ns1 . '</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" >NameServer2</td>
                                        <td align="left" valign="top">:&nbsp;' . $ns2 . '</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" colspan="2">
                                            Note: Please update your domain nameserver details.
                                        </td>
                                    </tr>
                                </table>
                        </div>
                <div class="clear"></div>';
                    }

                    $data = array('success' => 1, 'list' => $contents);
                } else {
                    //Failed
                    $contents = "Account setup failed. Please be patient our customer care agent will fix the issue and inform you.";
                    if(trim($error_contents) <> ""){
                        $contents .= "<br/><br/>The following issues were occurred on the registration page - <br/>".trim($error_contents);
                    }
                    error_log('['.$domainName.'] - Installation errors - ' . $message);
                    $data = array('failed' => 1, 'list' => $contents);
                }
            } else {
                $contents = "Payment failed";
                $data = array('failed' => 1, 'list' => $contents);
                // echo json_encode($data);
            }
        }
        PageContext::$response->registerDomain = $data;
    }

    public function staticcontent($cmsName) {

        PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");
        PageContext::addStyle("userproduct.css");
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addPostAction('cloudfooter');
        $content = User::loadStaticContent($cmsName);
        $this->view->staticContentTitle = $content->cms_title;
        $this->view->staticContent = $content->cms_desc;
        $this->view->setLayout("home");
    }

    public function plan() {
//echo Utils::bindEmailTemplate();
        $sessionObj = new LibSession();
        $userDetails = $sessionObj->get('userDetails');
PageContext::$response->userLogged = false;
//echopre($userDetails);
$userID = $sessionObj->get('userID');

        if (!empty($userID) || !empty($userDetails)) {

            PageContext::$response->userLogged = true;
        }



         if (Admincomponents::getFreePlanId() != "")
            PageContext::$response->freetrialStatus = 1;
        else {
            PageContext::$response->freetrialStatus = 0;
        }


        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();

        PageContext::addScript("userlogin.js");

        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");


        //PageContext::addPostAction('freetrial');

        //get plan details
        PageContext::$response->planDetails = Defaults::getPlanFeatures();
       // echopre(PageContext::$response->planDetails);
        $this->view->features = Defaults::getFeatures();
//echopre(PageContext::$response->planDetails);


        PageContext::$response->selectedLink = 'plan';
    }

    public function plansnippet() {

        Utils::loadActiveTheme();
        PageContext::$response->productId = 1;

        //get plan details
        PageContext::$response->planDetails = Defaults::getPlanFeatures();
        PageContext::$response->features = Defaults::getFeatures();
        PageContext::$response->selectedLink = 'plan';
    }

    /*
     * public function to load the screen shots
     */

    public function screenshots() {
        Utils::loadActiveTheme();

        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");


        PageContext::$response->screenshotsUrl = BASE_URL . 'project/styles/screenshots/';
        PageContext::$response->screenshots = Admincomponents::getScreenDetails();

        PageContext::$response->selectedLink = 'screenshots';
        PageContext::$response->themeUrl = Utils::getThemeUrl();
    }

    /*
     * public function to load the help
     */

    public function help() {
        Utils::loadActiveTheme();

        PageContext::addPostAction('cloudtopmenupage');

        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");


        PageContext::addPostAction('freetrial');
        PageContext::$response->selectedLink = 'help';
        PageContext::$response->themeUrl = Utils::getThemeUrl();

        //get user help details
        PageContext::$response->userHelpDetails = Help::getUserHelpDetails();
    }

  public function disclaimernotice() {
        Utils::loadActiveTheme();

        PageContext::addPostAction('cloudtopmenupage');

        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");


        PageContext::addPostAction('freetrial');
        PageContext::$response->selectedLink = 'help';
        PageContext::$response->themeUrl = Utils::getThemeUrl();

        //get user help details
        //PageContext::$response->userHelpDetails = Help::getUserHelpDetails();
    }




    // for new theme
    public function staticpages($cmsName) {

        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //TODO: add theme folder existing validation

        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');



        $content = User::loadStaticContent($cmsName);
        //echopre($content);
        if(trim($content->cms_status) == 0){
           header("location:".ConfigUrl::base());
           exit(0);
        }
        $this->view->staticContentTitle = $content->cms_title;
        $this->view->staticContent = $content->cms_desc;
        $this->view->setLayout("productpage");


        PageContext::$response->selectedLink = $cmsName;
    }

    public function forgotpwd($productId) {


        Utils::loadActiveTheme();
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        PageContext::$response->screenshotsUrl = BASE_URL . 'project/styles/screenshots/';
        PageContext::$response->selectedLink = '';
        PageContext::$response->themeUrl = Utils::getThemeUrl();

        PageContext::addStyle("global.css");

        PageContext::addScript("userpassword.js");


        if ($this->isPost()) {

            User::handleForgotPassword($this->view, $this);
            PageContext::addPostAction($this->view->messagefunction);
        }
    }

    //Reset password functionality
    public function resetpassword($activationKey) {
        PageContext::addStyle("global.css");

        Utils::loadActiveTheme();
        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');

        PageContext::addStyle("userproduct.css");
        PageContext::addScript("userpassword.js");
        Logger::info('Reset Password Procedure :');
        User::handleResetPassword($this->view, $this, $activationKey);
        PageContext::addPostAction($this->view->messagefunction);
        $this->view->setLayout('productpage');
    }

    //functionality to load success message
    public function successmessage() {

    }

    //functionality to load success message
    public function errormessage() {

    }

    //functionality to load cloud top menu
    public function cloudtopmenu() {

    }

    //functionality to load cloud footer with all contents
    public function cloudfooter() {

    }

    //functionality to load cloud footer with limited contents
    public function cloudlimitedfooter() {

    }

    public function freetrial() {

        if (Admincomponents::getFreePlanId() != "")
            PageContext::$response->freetrialStatus = 1;
        else {
            PageContext::$response->freetrialStatus = 0;
        }
        PageContext::addScript("jquery.addplaceholder.min.js");
        PageContext::addJsVar("checkeAccount", BASE_URL . "index/checkaccount/");


        $userLogged = false;
        $sessionObj = new LibSession();
        $userID = $sessionObj->get('userID');

        if (!empty($userID)) {
            $userLogged = true;
        }

        /*         * ****** Re Captcha ************** */
        PageContext::includePath('recaptcha');

        User::$dbObj = new Db();
        PageContext::$response->recaptcha_enable = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_enable'");
        $recaptcha_public_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_public_key'");
        $recaptcha_private_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_private_key'");

        if (PageContext::$response->recaptcha_enable == 'Y') {
            // RECAPTCHA CUSTOM STYLE
            PageContext::$headerCodeSnippet = '<script type="text/javascript">
                                             var RecaptchaOptions = {
                                                theme : \'clean\'
                                             };
                                             </script>';
            // RECAPTCHA ELEMENT
            PageContext::$response->publickey = (!empty($recaptcha_public_key)) ? $recaptcha_public_key : RECAPTCHA_PUBLICKEY;
            PageContext::$response->privatekey = (!empty($recaptcha_private_key)) ? $recaptcha_private_key : RECAPTCHA_PRIVATEKEY;

            $recaptchaHTML = null;
            $recaptchaError = null;

            if (!empty(PageContext::$response->publickey) && !empty(PageContext::$response->privatekey)) {
                PageContext::$response->recaptchaHTML = recaptcha_get_html(PageContext::$response->publickey, $recaptchaError);
            }
        }

        /*         * ****** Re Captcha Ends ********* */


        PageContext::$response->userLogged = $userLogged;
    }

    //functionality to load cloud top menu
    public function cloudtopmenupage() {

    }

    //functionality to load cloud footer menu
    public function cloudfooterpage() {

        User::$dbObj = new Db();

        PageContext::$response->cmsData = User::getActiveMenus();


        PageContext::addJsVar('bannerUrl', BASE_URL . "index/ajaxBannerCount/");

        $enableFB = User::$dbObj->selectRow("Settings", "value", "settingfield='enable_fb'");
        $enableTW = User::$dbObj->selectRow("Settings", "value", "settingfield='enable_twitter'");
        $enableLN = User::$dbObj->selectRow("Settings", "value", "settingfield='enable_ln'");

        $FBLink = User::$dbObj->selectRow("Settings", "value", "settingfield='facebookUrl'");
        $TWLink = User::$dbObj->selectRow("Settings", "value", "settingfield='twitterUrl'");
        $LNLink = User::$dbObj->selectRow("Settings", "value", "settingfield='linkedInUrl'");





        PageContext::$response->enableFB = $enableFB;
        PageContext::$response->enableTW = $enableTW;
        PageContext::$response->enableLN = $enableLN;

        PageContext::$response->FBLink = $FBLink;
        PageContext::$response->TWLink = $TWLink;
        PageContext::$response->LNLink = $LNLink;

        // Banner Display in footer
        $FooterBanner = User::loadBanners();
        PageContext::$response->BannerImage = $FooterBanner;

        // End
        //Google Analytics Code

        $googleSettings = User::$dbObj->selectRow("Settings", "value", "settingfield='enableGoogleAdsense'");

        $googleCode = User::$dbObj->selectRow("Settings", "value", "settingfield='googleAdsense'");


        PageContext::$response->googleSettings = $googleSettings;

        PageContext::$response->googleCode = $googleCode;

        // End
    }

    public function searchresults() {
        //    PageContext::addScript("googleSearchResults.js");
        PageContext::addStyle("global.css");
        PageContext::addStyle("home.css");
        PageContext::$headerCodeSnippet = "
            <script>
  (function() {
    var cx = '001817314419920192210:y32ewf88ube';
    var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);
  })();
</script>";
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addPostAction('cloudfooter');
        //$this->view->footerType      = 'limited';
        $this->view->setLayout('home');
    }

    public function checkcoupon() {
        $this->view->disableLayout();
        $this->view->disableView();
        $dataArr = array();
        if ($this->isPost()) {
            $couponCode = addslashes(($this->post('coupon') != '') ? $this->post('coupon') : $this->get('coupon'));

            $dataArr = Admincomponents::couponValidate($couponCode);
        }
        echo json_encode($dataArr);
    }

// End Function

    /*
     * Product upgrade
     */
    /*
     * Function to load pay option
     */

    public function upgrade_bck($productLookUpId) {
        PageContext::addJsVar('payNow', BASE_URL . "index/creditcardbuy/");
        PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");
        $this->view->setLayout("product");
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addScript('upgrade.js');
        PageContext::addStyle('userproduct.css');
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addJsVar("checkeAccount", BASE_URL . "index/checkaccount/");
        PageContext::addJsVar("checkeDomainAvailability", BASE_URL . "index/checkdomainstatus/");
        PageContext::addJsVar("registerDomain", BASE_URL . "index/registerdomain/");
        PageContext::addStyle("payment_newstyle.css");
        PageContext::addPostAction('cloudlimitedfooter');

        //other paymant

        PageContext::addJsVar('otherpaymanturl', BASE_URL . "index/otherpaymantbuy/");
        PageContext::addJsVar('otherpaymanturldomain', BASE_URL . "index/registerdomainotherpay/");


        $productId = User::getProductId($productLookUpId);
        $this->view->footerType = 'limited';
        $this->view->productid = $productId;
        $this->view->productLookUpid = $productLookUpId;
        $this->view->productname = User::getproductName($productId);
        $this->view->productPrice = User::getproductPrice($productId, PRODUCT_PURCHASE_CATEGORY);
        $this->view->purchaseCategory = User::getPurchaseCategory($productId, PRODUCT_PURCHASE_CATEGORY, PRODUCT_PURCHASE_CATEGORY_FREE);
        //echopre($this->view->purchaseCategory);
        //getProductServices
        $pdServiceArr = User::getProductServices($productId, array(array('field' => 'PS.nSCatId', 'value' => PRODUCT_PURCHASE_CATEGORY)));
        //echo '<pre>'; print_r($pdServiceArr); echo '</pre>';
        $this->view->purchaseService = $pdServiceArr;
    }

    public function services($serviceNames, $billingDurations, $amounts) {

        $this->view->disableLayout();
        $this->view->disableView();
        $serviceNamesArray = explode(",", urldecode($serviceNames));
        $billingDurationsArray = explode(",", urldecode($billingDurations));
        $amountsArray = explode(",", urldecode($amounts));

        for ($i = 0; $i < count($serviceNamesArray); $i++) {
            $returnData.= '<div class="payment_right_item_new">
                           <div class="large_new l_float">
                           ' . $serviceNamesArray[$i] . '
                           </div>
                            <div class="centernew l_float">
            ' . $billingDurationsArray[$i] . '
                            </div>
                            <div class="small_new  right_text r_float_new">' .ADMIN_CURRENCY_SYMBOL. Utils::formatPrice($amountsArray[$i]) . '</div>
                            <div class="clear"></div>
                            </div>';
        }



        echo $returnData;
        exit();
    }

    public function signup() {

        if(!isset($_GET['plan_id']))
        {

           // $this->redirect("plan");
        }


        $userId = LibSession::get('userID');
          if($userId){
              $this->redirect('user/dashboard');
          }
        
        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //TODO: add theme folder existing validation
        PageContext::addStyle("themes/" . $themName . "/layout.css");
        PageContext::addStyle("themes/" . $themName . "/theme.css");
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addJsVar('BASE_URL', BASE_URL);
        PageContext::addScript("useraccounts.js");

        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');

        PageContext::includePath('recaptcha');

        User::$dbObj = new Db();
        PageContext::$response->recaptcha_enable = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_enable'");
        $recaptcha_public_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_public_key'");
        $recaptcha_private_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_private_key'");

        if (PageContext::$response->recaptcha_enable == 'Y') {
            // RECAPTCHA CUSTOM STYLE
            PageContext::$headerCodeSnippet = '<script type="text/javascript">
                                             var RecaptchaOptions = {
                                                theme : \'clean\'
                                             };
                                             </script>';
            // RECAPTCHA ELEMENT
            PageContext::$response->publickey = (!empty($recaptcha_public_key)) ? $recaptcha_public_key : RECAPTCHA_PUBLICKEY;
            PageContext::$response->privatekey = (!empty($recaptcha_private_key)) ? $recaptcha_private_key : RECAPTCHA_PRIVATEKEY;

            $recaptchaHTML = null;
            $recaptchaError = null;

            if (!empty(PageContext::$response->publickey) && !empty(PageContext::$response->privatekey)) {
                PageContext::$response->recaptchaHTML = recaptcha_get_html(PageContext::$response->publickey, $recaptchaError);
            }
        }

        $pageTitle = 'Sign Up';
        $this->view->pageTitle = $pageTitle;
        $errMsg = '';
        $dataArr = array();
        if ($this->isPost()) {

            // firstName/ lastName / emailAddress / password / confirmPassword
            $dataArr['firstName'] = addslashes($this->post('firstName'));
            $dataArr['lastName'] = addslashes($this->post('lastName'));
            $dataArr['emailAddress'] = addslashes($this->post('emailAddress'));
            $dataArr['password'] = $this->post('password');
            $dataArr['confirmPassword'] = $this->post('confirmPassword');
            //checkUserEmail
            $userExists = User::checkUserEmail($dataArr['emailAddress']);
            // End checkUserEmail


            if (PageContext::$response->recaptcha_enable == 'Y') {
                $resp = recaptcha_check_answer(PageContext::$response->privatekey, $_SERVER["REMOTE_ADDR"], PageContext::$request["recaptcha_challenge_field"], PageContext::$request["recaptcha_response_field"]);
                $captchaError = $resp->error;

                if (!empty($captchaError)) {
                    $errMsg.= 'Invalid security code<br />';
                }
            }

            if ($userExists == 1) {
                $errMsg .= "Email address already exists";
            }

            if ($errMsg == "") {

                $objSession = new LibSession();
                 //$objSession->set('userDetails', $_POST);

                        $planId = $objSession->get("plan_id");
                        $templateId = $objSession->get("template_id");

                        
                          
                            
                           // echopre1($dataArr);
                            //$this->redirect("plan");
                             User::createUser($dataArr);
                             User::validateLoginByEmail($this->post('emailAddress'));
                //------------Add user to supportdesk-----------------//
                include_once(BASE_PATH . "project/support/api/useradd.php");
                userAdd($dataArr['firstName'], $dataArr['password'], $dataArr['emailAddress']);

                if($planId && $templateId)
                        {
                            $this->redirect("paynow?plan_id=$planId&template_id=$templateId");
                        }else{
                
                $this->redirect('user/dashboard');
                        }






                // Register New User
                User::createUser($dataArr);
                //------------Add user to supportdesk-----------------//
                /*include_once(BASE_PATH . "project/support/api/useradd.php");
                userAdd($dataArr['firstName'], $dataArr['password'], $dataArr['emailAddress']);
*/
                 User::createSupportuser($dataArr['firstName'],  $dataArr['emailAddress'],$dataArr['password']);

                $this->redirect("thankyousignup");
            }
        }
        //
        //echo '<pre>'; print_r($dataArr); echo '</pre>';
        $this->view->errMsg = $errMsg;
        $this->view->dataArr = $dataArr;
        $this->view->setLayout("productpage");

        PageContext::$response->selectedLink = 'signup';
    }



    public function signin() {

$userId = LibSession::get('userID');
          if($userId){
              $this->redirect('user/dashboard');
          }

        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //TODO: add theme folder existing validation
        PageContext::addStyle("themes/" . $themName . "/layout.css");
        PageContext::addStyle("themes/" . $themName . "/theme.css");
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addJsVar('BASE_URL', BASE_URL);
        PageContext::addScript("useraccounts.js");

        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');

        PageContext::includePath('recaptcha');

        User::$dbObj = new Db();

          

        $pageTitle = 'Sign In';
        $this->view->pageTitle = $pageTitle;
        $errMsg = '';
        $dataArr = array();

        if ($this->post('emailAddress')) {

            $userName = $this->post('emailAddress');
            $password = $this->post('password');
            if ($userName != "" && $password != "") {

                $status = User::validateLogin($userName, md5($password));

                if ($status < 0) {
                    $errMsg = "Your account is no longer active!!!";

                } else if ($status == true) {


                    //-----------Set supportdesk session----------//
                   // echo "SELECT * FROM sptbl_users WHERE vEmail = '{$userName}'";exit;

                    $sptbl_user = User::getUserFromSupport($userName);
                   // $sptbl_user = mysqli_query($connection,"SELECT * FROM sptbl_users WHERE vEmail = '{$userName}'");
                    if ($sptbl_user[0]->nUserId > 0) {
                       // $sptbl_res = mysqli_fetch_array($sptbl_user);

                        $_SESSION["sess_username"] = $sptbl_user[0]->vUserName;
                        $_SESSION["sess_userid"] = $sptbl_user[0]->nUserId;
                        $_SESSION["sess_useremail"] = $sptbl_user[0]->vEmail;
                        $_SESSION["sess_userfullname"] = $sptbl_user[0]->vUserName;
                        $_SESSION["sess_usercompid"] = 1;







                    }

                        $sessionObj = new LibSession();
                        $planId = $sessionObj->get("plan_id");
                        $templateId = $sessionObj->get("template_id");

                        if($planId && $templateId)
                        {
                            $this->redirect("paynow?plan_id=$planId&template_id=$templateId");
                        }else{
                            $this->redirect('user/dashboard');
                        }







                } else {
                    $errMsg = "Invalid login details!!!";

                }
            }
        }



        //
        //echo '<pre>'; print_r($dataArr); echo '</pre>';
        $this->view->errMsg = $errMsg;
        $this->view->dataArr = $dataArr;
        $this->view->setLayout("productpage");

        PageContext::$response->selectedLink = 'login';
    }


// End Function

    public function checkduplicateuser() {
        $this->view->disableLayout();
        $this->view->disableView();

        $msg = 0;

        if ($this->isPost()) {
            $id = addslashes(($this->post('email') != '') ? $this->post('email') : $this->get('email'));
            if (!empty($id)) {
                $msg = User::checkUserEmail($id);
            }
        } // End Post

        echo $msg;
    }

// End Function

    public function thankyou($msgType) {
        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //TODO: add theme folder existing validation
        PageContext::addStyle("themes/" . $themName . "/layout.css");
        PageContext::addStyle("themes/" . $themName . "/theme.css");
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addJsVar('BASE_URL', BASE_URL);
        PageContext::addScript("useraccounts.js");

        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');

        //TODO: add option to assign the theme dynamicaly
        PageContext::$response->themeUrl = BASE_URL . 'project/styles/themes/theme1/';
        $pageTitle = 'Thank You';
        $this->view->pageTitle = $pageTitle;
        $successMsg = NULL;

        if ($msgType == 'thankyousignup') {
            $successMsg = "Please log in and use all the great features on the site. All you have
                to do is sign in using the Email and password you chose, and you will have access
                to your account. Until then you can always enjoy browsing through all the site features.";
        }

        $this->view->message = $successMsg;
        $this->view->setLayout("productpage");
    }

    //Create account using paymnet gateway out of system starts Ak



    public function createaccountafterpaymentother($userArray, $subdom, $userId, $productArray, $payInfoArr = NULL, $upgradeFlag = 0, $productLookUpId = 0) {
        set_time_limit(0);
        PageContext::includePath('cpanel');
        $cpanelObj = new cpanel();
        $dbArray = array();
        //  $this->view->disableView();
        $productInstallPath = BASE_PATH . '' . $subdom . '/';

        $dataArr = array();
        if ($upgradeFlag == 1) {
            $pdLookupId = $productLookUpId;
//            $this->upgradeSubDomainAfterPayment();
            $dataArr['upgrade'] = 1;
            $plId = $productLookUpId;
            $accountDetails = unserialize(User::getserverDetails($plId));
            $statusArray = $cpanelObj->upgradesubdomainaccount($accountDetails['c_user'], $accountDetails['c_pass'], $accountDetails['c_host'], $RegistrantEmailAddress, $productArray);
        } else {
            /*
             * Commen the line and chnaged the procedure to individual account
             */
            $domainName = $subdom . '.' . DOMAIN_NAME;

            $username = substr(strtolower($userArray['user_name']), 0, 3);
            $username = $username . substr(md5($userArray['user_name'] . time()), 0, 3);
            $statusArray = $cpanelObj->createcpanelaccountforsubdomain($username, $userArray['userpassw'], $domainName, $userArray['user_email'], $productArray);
            $userArray['c_user'] = $username;
            $userArray['c_pass'] = $userArray['userpassw'];
            $userArray['c_host'] = $domainName;
            Utils::reconnect();
            Utils::reconnect();
            $pdLookupId = $this->updateuser($userArray, $subdom, $userId);

            if ($statusArray['status'] == 0) {
                //Failed
                if (isset($statusArray['tech_statusmsg']) && trim($statusArray['tech_statusmsg']) <> '') {
                    $contents = $statusArray['tech_statusmsg'];
                } else {
                    $contents = "Account setup failed. Please be patient our customer care agent will fix the issue and inform you.";
                }

                $data = array('failed' => 1, 'list' => $contents);
                echo json_encode($data);
                die;
            }
        }
        Utils::reconnect();

        // Update Transaction Session
        $transactionSession = $productArray["transactionSession"];
        Admincomponents::saveTransactionSessionID($transactionSession, $pdLookupId);

        $dataArr = array('nUId' => $userId,
            'nPLId' => $pdLookupId,
            'services' => $productArray['productServices'],
            'domainService' => array(),
            'couponNo' => $productArray['couponNo'],
            'terms' => '',
            'notes' => '',
            'paymentstatus' => 'paid',
            'vMethod' => $payInfoArr['paymentMethod'],
            'vTxnId' => $payInfoArr['transactionId'],
            'upgrade' => $upgradeFlag,
            'subscriptionType' => 'PAID');

        Utils::reconnect();
        Admincomponents::generateInvoice($dataArr);
//              $contents="
//Congratulations! Your installation was successful!<br>
//
//
//Site Login Details<br>
//
//Admin URL :   <a href='http://$subdom.cloud.iscripts.com/admins/' target='_blank'>http://$subdom.cloud.iscripts.com/admins/</a><br>
//
//Admin Credentials :   Username : admin<br>
//Password : admin<br>
//
//Home URL :    <a href='http://$subdom.cloud.iscripts.com/index.php' target='_blank'>http://$subdom.cloud.iscripts.com/</a><br>
//";
        $contents = '<div class="store_success">
            <div class="store_success_label"></div>
                    <h2>Congratulations!</h2>';
        if ($upgradeFlag == 1) {
            $contents.= '<h3>The Upgrade Process was completed successfully!</h3>
                    <p class="head">Site Login Details</p>';
        } else {
            $contents.= '<h3>Your installation was successful!</h3>
                    <p class="head">Site Login Details</p>';
        }
        $contents.= '<table cellpadding="0" cellspacing="0" border="0" width="400px" align="center" >
                    <tr>
                    <td align="left" valign="top" width="20%">Admin URL </td>
                    <td align="left" valign="top">:&nbsp;<a href="http://' . $subdom . '.' . DOMAIN_NAME . '/admins/"  target="_blank">http://' . $subdom . '.' . DOMAIN_NAME . '/admins/</a></td>
                    </tr>
                    <tr>
                    <td align="left" valign="top">Home URL </td>
                    <td align="left" valign="top">:&nbsp;<a href="http://' . $subdom . '.' . DOMAIN_NAME . '/index.php"  target="_blank">http://' . $subdom . '.' . DOMAIN_NAME . '/</a></td>
                    </tr>
                    </table>
                    <p class="head">Admin Credentials</p>
                    <table cellpadding="0" cellspacing="0" border="0" width="400px" align="center">
                    <tr>
                    <td align="left" valign="top" width="20%">Username</td>
                    <td align="left" valign="top">:&nbsp;admin</td>
                    </tr>
                    <tr>
                    <td align="left" valign="top" >Password</td>
                    <td align="left" valign="top">:&nbsp;admin</td>
                    </tr>
                    </table>

            </div>';

        $data = array('success' => 1, 'list' => $contents);

        return $data;
    }

    public function otherpaymantbuy($paystatus = "") {

        $connection = new Db();

        if (isset(PageContext::$request['http_status'])) {

            $objSession = new LibSession(); //echo $objSession->get('paymantflage');echopre($objSession->get('arrtwoPaySettings'));exit;
            if ($objSession->get('paymantflage') == 1) {
                $objSession->set('requestBrain', PageContext::$request);
                $this->redirect('index/otherpaymantsucess/braintree/sucess');
                exit;
            }
        }

        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addScript('paynow.js');
        //set layout starts
        Utils::loadActiveTheme();
        //PageContext::$response->themeurl = BASE_URL.'themes/theme1/';
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //set layout ends

        set_time_limit(0);
        $authorizeInfo = array();
        $productArray = array();
        $authorizeInfo['fName'] = $this->post('fname');
        $authorizeInfo['lName'] = $this->post('lname');
        $authorizeInfo['add1'] = $this->post('add1');
        $authorizeInfo['city'] = $this->post('city');
        $authorizeInfo['state'] = $this->post('state');
        $authorizeInfo['country'] = $this->post('country');
        // RegistrantCountry
        $idRegistrantCountry = $authorizeInfo['country'];
        global $usStates;
        $registrantCountry = $usStates[$idRegistrantCountry];
        $authorizeInfo['zip'] = $this->post('zip');
        $storeName = $this->post('txtStoreName');
        $authorizeInfo['email'] = $this->post('email');
        $productId = PRODUCT_ID;
        $authorizeInfo['amount'] = $this->post('ServiceAmount');


        $productArray['id'] = $productId;
        $productArray['packname'] = User::getproductPackName($productId);
        $productArray['permissionlist'] = User::getproductPermission($productId);
        $productArray['couponNo'] = $this->post('couponNumber');
        $productArray['productreleaseid'] = User::getproductReleaseID($productId);
        $upgradeFlag = $this->post('upgradeFlag');
        //$productLookUpId = $this->post('productLookUpid');
        $productLookUpId = $this->post('productLookUpId');
        if ($productLookUpId != "")
            $upgradeFlag = 1;

        //*****************************Akhil Code Paymant start********
        if ($this->post('paymentmethod') != "") // for paypalrpo only
            $paymantArray['paymentmethod'] = $this->post('paymentmethod'); // credit card
        if ($this->post('currentpaymant') != "")
            $paymantArray['currentpaymant'] = $this->post('currentpaymant'); // current paymant method
        $productServices = $this->post('productId');
        $productSerArr = array();
        if (!empty($productServices)) {
            $productSerArr[0] = $productServices;
        }

        $productArray['productServices'] = $productSerArr;

        //$paymantArray['currentpaymant']      = "paypalpro";
        //*****************************Akhil Code Paymant ends********
        //******************** Product Services
        /*
          $productServices = $this->post('serCat');
          $productSerArr = array();
          if (!empty($productServices)) {
          $productSerArr = explode(",", $productServices);
          }
          // Purchase Service Id
          $purchaseServiceId = User::getProductServicesId($productId, array(array('field' => 'PS.nSCatId', 'value' => PRODUCT_PURCHASE_CATEGORY)));
          //Append Purchase Service Id
          $productSerArr = User::mergeProductServicesId(array($purchaseServiceId), $productSerArr);
          $productArray['productServices'] = $productSerArr;
         *
         */

        //******************** Product Services End

        $storeName = $this->post('txtStoreName');
        $userEmail = $this->post('email');
        $userName = $this->post('fname');
        $userLname = $this->post('lname');
        $userPassword = $storeName . '' . rand(1, 1000);
        $userArray = array(
            'user_name' => $userName,
            'user_email' => $userEmail,
            'store_name' => $storeName,
            'userpassw' => $userPassword,
            'user_lname' => $userLname,
        );

        $subdom = $storeName;
        $subdom = strtolower($subdom);
        $subdom = str_replace(" ", '', $subdom);
        $productArray['planProductRestriction'] = User::getPlanproductRestriction($this->post('productId'));
        $productArray['xmlproductdata'] = User::setXmlData($productArray['planProductRestriction'], md5($subdom));

        if (LibSession::get('userID') == "") {
            /*             * ****** User Details Updation  ********** */
            $userUpdateArr = array();
            $userUpdateArr['vAddress'] = $authorizeInfo['add1'];
            $userUpdateArr['vCountry'] = $registrantCountry;
            $userUpdateArr['vState'] = $authorizeInfo['state'];
            $userUpdateArr['vCity'] = $authorizeInfo['city'];
            $userUpdateArr['vZipcode'] = $authorizeInfo['zip'];

            $userCreditArr = array();
            $userCreditArr['vFirstName'] = $authorizeInfo['fName'];
            $userCreditArr['vLastName'] = $authorizeInfo['lName'];
            $userCreditArr['vNumber'] = $authorizeInfo['ccno'];
            $userCreditArr['vCode'] = $authorizeInfo['cvv'];
            $userCreditArr['vMonth'] = $authorizeInfo['expMonth'];
            $userCreditArr['vYear'] = $authorizeInfo['expYear'];
            $userCreditArr['vAddress'] = $authorizeInfo['add1'];
            $userCreditArr['vCity'] = $authorizeInfo['city'];
            $userCreditArr['vState'] = $authorizeInfo['state'];
            $userCreditArr['vZipcode'] = $authorizeInfo['zip'];
            $userCreditArr['vCountry'] = $registrantCountry;
            $userCreditArr['vEmail'] = $authorizeInfo['email'];
            $userCreditArr['vUserIp'] = $_SERVER['REMOTE_ADDR'];

            /*             * ****** User Details Updation  ********** */
            $userId = User::createUserAccount($userArray, $userUpdateArr, $userCreditArr);

            //------------Add user to supportdesk-----------------//
            /*include_once(BASE_PATH . "project/support/api/useradd.php");
            userAdd($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);*/

            User::$dbObj = new Db();
User::createSupportuser($userArray['user_name'], $userArray['userpassw'], $userArray['user_email']);



            //-----------Set supportdesk session----------//
            $sptbl_user = mysqli_query($connection,"SELECT * FROM sptbl_users WHERE vEmail = '{$userArray['user_email']}'");
            if (mysqli_num_rows($sptbl_user) > 0) {
                $sptbl_res = mysqli_fetch_array($sptbl_user);

                $_SESSION["sess_username"] = $sptbl_res['vUserName'];
                $_SESSION["sess_userid"] = $sptbl_res['nUserId'];
                $_SESSION["sess_useremail"] = $sptbl_res['vEmail'];
                $_SESSION["sess_userfullname"] = $sptbl_res['vUserName'];
                $_SESSION["sess_usercompid"] = 1;
            }
            LibSession::set('mailSendFlag', 1);
        } else {
            $userId = LibSession::get('userID');
            /*         UpDating Billing Details        */
            $userDataArray = array();
            $userDataArray['nUserId'] = LibSession::get('userID');
            $userDataArray['vFirstName'] = $authorizeInfo['fName'];
            $userDataArray['vLastName'] = $authorizeInfo['lName'];
            $userDataArray['vNumber'] = $authorizeInfo['ccno'];
            $userDataArray['vCode'] = $authorizeInfo['cvv'];
            $userDataArray['vMonth'] = $authorizeInfo['expMonth'];
            $userDataArray['vYear'] = $authorizeInfo['expYear'];
            $userDataArray['vAddress'] = $authorizeInfo['add1'];
            $userDataArray['vCity'] = $authorizeInfo['city'];
            $userDataArray['vState'] = $authorizeInfo['state'];
            $userDataArray['vZipcode'] = $authorizeInfo['zip'];
            $userDataArray['vCountry'] = $registrantCountry;
            $userDataArray['vEmail'] = $authorizeInfo['email'];

            $tbs = User::updateUserCreditCardDetails($userDataArray);

            /*        Ending of  UpDating Billing Details        */
        }


        //*******************************

        /*
         * Session value setup
         */
        $userFullInfoArr = Admincomponents::getUserdetails($userId);
        LibSession::set('reg_usr_id', $userId);
        LibSession::set('userID', $userId);
        LibSession::set('firstName', $userFullInfoArr->vFirstName);
        LibSession::set('planid', 1);
        LibSession::set('planpackage', 1);
        LibSession::set('purchase_amt', $this->post('ServiceAmount'));
        LibSession::set('package_desc', 'Full Pack');
        LibSession::set('productid', $productId);
        LibSession::set('productreleaseid', $productArray['productreleaseid']);

        LibSession::set('fname', $authorizeInfo['fName']);
        LibSession::set('lname', $authorizeInfo['lName']);

        // Wallet Balance Check
        $walletBalance = $walletDiscount = $walletNewBalance = $discount = 0;
        $totalAmount = $authorizeInfo['amount'];
        if (!empty($userId)) {
            $walletBalance = Admincomponents::getUserWalletBalance($userId);
            $walletDiscount +=($totalAmount < $walletBalance) ? $totalAmount : $walletBalance;
            $walletNewBalance = $walletBalance - $walletDiscount;
            $discount +=$walletDiscount;
            /*             * ******************** Update Wallet ********************** */
            $updateWalletArr = array();
            $updateWalletArr['nUId'] = $userId;
            $updateWalletArr['newBalance'] = $walletNewBalance;
//            Admincomponents::updateWallet($updateWalletArr);
            /*             * ******************** Update Wallet ********************** */
            $authorizeInfo['amount'] = $totalAmount - $discount;
        } // End If
        // End Wallet Balance Check
//        $status  =   User::creditPayment($authorizeInfo);
        /*
         * Comment the line for testing. Need to enable it.
         */

        //*****Akhil Paymant code other paymant //****


        $arrtwoPaySettings = array();
        //   $authorizeInfo['amount'] = 0.05;
        $arrtwoPaySettings['Grandtotal'] = urlencode($authorizeInfo['amount']);
        $arrtwoPaySettings['Currency'] = urlencode(CURRENCY); //urlencode('USD');
        $arrtwoPaySettings['ItemNumber'] = NULL;

        $objSession = new LibSession();
        if ($objSession->get('paymantflage') == "") {
            $objSession->set('authorizeInfo', $authorizeInfo);
            $objSession->set('storeName', $storeName);
            $objSession->set('userArray', $userArray);
            $objSession->set('userId', $userId);
            $objSession->set('productArray', $productArray);
            $objSession->set('status', $status);
            $objSession->set('upgradeFlag', $upgradeFlag);
            $objSession->set('productLookUpId', $productLookUpId);
        }


// $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/otherpaymantsucess/".$paymantArray['currentpaymant'];
// Paymant check ********************
        //echo $paymantArray['currentpaymant'];exit;
        if ($paymantArray['currentpaymant'] == 'twocheckout') { // if paymant method is paypalpro
            $twocheckoutSettings = Payments::getTwoCheckoutSettings();
            // $arrtwoPaySettings = array();
            $arrtwoPaySettings['Vendorid'] = $twocheckoutSettings['TwoCheckoutvendorid']; //'1877160'; // vendor id from payment settings
            $arrtwoPaySettings['Company'] = "-NA-";
            $arrtwoPaySettings['Email'] = $authorizeInfo['email']; // User Email
            $arrtwoPaySettings['Currency'] = 'USD';
            if ($twocheckoutSettings['TwoCheckouttestmode'] == 'Y')
                $arrtwoPaySettings['Testmode'] = "Y";
            $planDetails = User::getPlanDetails($productServices);
            $arrtwoPaySettings['Itemname'] = $planDetails[0]->vServiceName;
            $arrtwoPaySettings['Cartid'] = rand(1, 1000);
            $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/sucess";
            // $arrtwoPaySettings['ReturnURL'] = BASE_URL . "payments/twocheckout/sucess";

            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                $objSession->set('paymantflage', 1);
            }


            PageContext::$response->renderPaymant = Payments::payTwoCheckout($arrtwoPaySettings);
        } else if ($paymantArray['currentpaymant'] == 'paypalflowlink') {
            $paypalflowlinkSettings = Payments::getPaypallinkSettings();
            $arrtwoPaySettings['Paypallinkvendorid'] = $paypalflowlinkSettings['Paypalflowlinkvendorid']; // "armiapayflow";
            $arrtwoPaySettings['Paypallinkpartnerid'] = $paypalflowlinkSettings['Paypalflowlinkpartnerid']; //  'PayPal';
            $arrtwoPaySettings['Paymenttype'] = 'S'; // Constant
            $arrtwoPaySettings['Method'] = 'CC'; // Constant
            // $arrtwoPaySettings['Grandtotal'] = '0.05';
            $arrtwoPaySettings['Customerid'] = rand(1, 10000);
            $arrtwoPaySettings['Orderform'] = true;
            $arrtwoPaySettings['Showconfirm'] = true;

            if ($paypalflowlinkSettings['Paypalflowlinktestmode'] == 'Y')
                $arrtwoPaySettings['Testmode'] = 'Y';

            $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/sucess";

            $arrtwoPaySettings['Firstname'] = urlencode($authorizeInfo['fName']);
            $arrtwoPaySettings['Address'] = urlencode($authorizeInfo['add1']);
            $arrtwoPaySettings['City'] = urlencode($authorizeInfo['city']);
            $arrtwoPaySettings['Zip'] = urlencode($authorizeInfo['zip']);
            $arrtwoPaySettings['Country'] = urlencode($authorizeInfo['country']);
            $arrtwoPaySettings['Currency'] = urlencode('USD');
            $arrtwoPaySettings['Phone'] = '';
            $arrtwoPaySettings['Fax'] = '';
            //echopre($arrtwoPaySettings);exit;

            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                $objSession->set('paymantflage', 1);
            }


            PageContext::$response->renderPaymant = Payments::payPaypalflowlink($arrtwoPaySettings);
        } else if ($paymantArray['currentpaymant'] == 'paypalxpress') {
            $paypalXpresSettings = Payments::getPaypalXpresSettings();
            $arrtwoPaySettings['Paypalexpressusername'] = $paypalXpresSettings['PaypalXpresUsername']; //"seller_1297271002_biz_api1.yahoo.com";
            $arrtwoPaySettings['Paypalexpresspassword'] = $paypalXpresSettings['PaypalXpresPassword']; //'1297271011';
            $arrtwoPaySettings['Paypalexpresssignature'] = $paypalXpresSettings['PaypalXpresSignature']; //'AFcWxV21C7fd0v3bYYYRCpSSRl31A-Vd1YRxIrhGWvUd2XnlrhGdk6rY';


            if ($paypalXpresSettings['PaypalXprestestmode'] == 'Y')
                $arrtwoPaySettings['Testmode'] = 'Y';

            $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/sucess";
            $arrtwoPaySettings['CancelURL'] = BASE_URL . BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/cancel";

            //set session pf payment
            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                $objSession->set('paymantflage', 1);
            }


            $redirectURL = Payments::payPaypalexpress($arrtwoPaySettings);
            if ($redirectURL != "") {
                Headerredirect::httpRedirect($redirectURL);
            }
        } else if ($paymantArray['currentpaymant'] == 'paypaladvanced') {
            $paypaladvancedSettings = Payments::getPaypaladvancedSettings();
            $arrtwoPaySettings['Paypaladvancedvendorid'] = $paypaladvancedSettings['Paypaladvancedvendorid']; //"palexanderpayflowtest";
            $arrtwoPaySettings['Paypaladvancedpassword'] = $paypaladvancedSettings['Paypaladvancedpassword']; //'demopass123';
            $arrtwoPaySettings['Paypaladvancedpartner'] = $paypaladvancedSettings['Paypaladvancedpartner']; //'PayPal';
            $arrtwoPaySettings['Paypaladvanceduser'] = $paypaladvancedSettings['Paypaladvancedusername']; //'palexanderpayflowtestapionly';


            $arrtwoPaySettings['Paymenttype'] = urlencode('A');
            $arrtwoPaySettings['Createsecuretocken'] = 'Y';
            // $arrtwoPaySettings['Currency'] = "USD";
            $arrtwoPaySettings['Securetockenid'] = uniqid('MySecTokenID-');


            if ($paypaladvancedSettings['Paypaladvancedtestmode'] == "Y")
                $arrtwoPaySettings['Testmode'] = 'Y';

            $arrtwoPaySettings['ReturnURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/sucess";
            $arrtwoPaySettings['CancelURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/cancel";
            $arrtwoPaySettings['ErrorURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/error";

            $arrtwoPaySettings['Billtofirstname'] = $authorizeInfo['fName'];
            $arrtwoPaySettings['Billtolastname'] = $authorizeInfo['lName'];
            $arrtwoPaySettings['Billtostreet'] = $authorizeInfo['add1'];
            $arrtwoPaySettings['Billtocity'] = $authorizeInfo['city'];
            $arrtwoPaySettings['Country'] = $authorizeInfo['country'];

            //set session pf payment
            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                $objSession->set('paymantflage', 1);
            }

            $redirectURL = Payments::setPaypaladvancedUrl(Payments::payPaypaladvanced($arrtwoPaySettings), $arrtwoPaySettings);
            if ($redirectURL != false) {
                Headerredirect::httpRedirect($redirectURL);
            }
        } else if ($paymantArray['currentpaymant'] == 'braintree') {

            $braintreeSettings = Payments::getBraintreeSettings();
            $arrtwoPaySettings['Braintreemerchantid'] = $braintreeSettings['BraintreemerchantId']; //"f7mgykzp5b7txjf7";
            $arrtwoPaySettings['Braintreepublickey'] = $braintreeSettings['Braintreepublickey']; //'qfhh854tm6g6md9x';
            $arrtwoPaySettings['Braintreeprivatekey'] = $braintreeSettings['Braintreeprivatekey']; //'863323bad983dc6eca5dea1a7913a90f';
            $arrtwoPaySettings['Paymenttype'] = 'sale'; // Constant
            if ($braintreeSettings['Braintreetestmode'] == "Y")
                $arrtwoPaySettings['Testmode'] = 'Y';


            $arrtwoPaySettings['Firstname'] = $authorizeInfo['fName'];
            $arrtwoPaySettings['Lastname'] = $authorizeInfo['lName'];
            $arrtwoPaySettings['Email'] = $authorizeInfo['email'];

            //set session pf payment
            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                $objSession->set('paymantflage', 1);
            }

            $configValues = Payments::payBreantree($arrtwoPaySettings);
            if (isset($configValues) && count($configValues) > 0) {

                $renderFrom = '<form action="' . $configValues['form_url'] . '" method="post" name="frmPayment" >';
                $renderFrom .='<table width="40%"  border="0" cellspacing="4" cellpadding="0" align="center">
  <tr>
    <td align="left">Card Number</td>
    <td align="left"><input type="text" size="27" class="box2_admin" value="" maxlength="16" id="txtCCNumber" name="transaction[credit_card][number]"></td>
  </tr>
  <tr>
    <td align="left">Expiry Date(MM/YYYY)</td>
    <td align="left"><input type="text" size="27" class="box2_admin" maxlength="10" id="expiration_date" value="" name="transaction[credit_card][expiration_date]"></td>
  </tr>
  <tr>
    <td align="left">CVV/CVV2 No</td>
    <td align="left"><input type="text" size="27" class="box2_admin" maxlength="10" value="" id="txtCVV2" name="transaction[credit_card][cvv]"></td>
  </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td align="left" valign="top" height="35"><input type="hidden" name="tr_data" value="' . $configValues['tr_data'] . '" />
                                        <input type="hidden" name="transaction[customer][first_name]" value="' . $configValues['firstName'] . '" />
                                        <input type="hidden" name="transaction[customer][last_name]" value="' . $configValues['lastName'] . '" />
                                        <input type="hidden" name="transaction[customer][email]" value="' . $configValues['email'] . '" />
                    <br><input type="submit"  name="btnCompleteOrderbraintree" value="Pay Now" onclick="return validateForm(document.frmPayment);" class="btn-usr01"></td>
  </tr>
</table>';




                $renderFrom .= '</form>';

                PageContext::$response->renderPaymant = $renderFrom;
            }
        } else if ($paymantArray['currentpaymant'] == 'ogone') {

            $ogoneSettings = Payments::getOgoneSettings();
            $arrtwoPaySettings['Ogonepspid'] = $ogoneSettings['Ogonepartnerid']; // "rajath";
            $arrtwoPaySettings['Ogonepassphrase'] = $ogoneSettings['Ogonevendorid']; //'shainarmia247~!@';

            if ($ogoneSettings['Ogonetestmode'] == "Y")
                $arrtwoPaySettings['Testmode'] = 'Y';

            $arrtwoPaySettings['DeclineURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/decline";
            $arrtwoPaySettings['CancelURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/cancel";
            $arrtwoPaySettings['ExceptionURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/exception";
            $arrtwoPaySettings['AcceptURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/sucess";
            ; //sucess return url

            $arrtwoPaySettings['Orderid'] = RAND(10000, 895689596);

            $arrtwoPaySettings['Language'] = "en_us";
            $arrtwoPaySettings['Logo'] = "Logo.jpg";
            $arrtwoPaySettings['Operation'] = 'SAL'; //Constant

            $arrtwoPaySettings['Firstname'] = $authorizeInfo['fName'];
            $arrtwoPaySettings['Lastname'] = $authorizeInfo['lName'];
            $arrtwoPaySettings['Email'] = $authorizeInfo['email'];
            //set session pf payment
            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                $objSession->set('paymantflage', 1);
            }
            PageContext::$response->renderPaymant = Payments::payOgone($arrtwoPaySettings);
        } else if ($paymantArray['currentpaymant'] == 'paypal') {

            $paypalSettings = Payments::getPaypalSettings();

            $arrtwoPaySettings['Paypalemail'] = $paypalSettings['Paypalemail']; //"mahi_1_1321000734_biz@yahoo.com";
            $arrtwoPaySettings['resultURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/sucess";
            $arrtwoPaySettings['cancelURL'] = BASE_URL . "index/otherpaymantsucess/" . $paymantArray['currentpaymant'] . "/cancel";
            $arrtwoPaySettings['notifyURL'] = BASE_URL . "index/otherpaymentipn/" . $paymantArray['currentpaymant'] . "/ipn";
            $planDetails = User::getPlanDetails($productServices);
            $arrtwoPaySettings['Itemname'] = $planDetails[0]->vServiceName;
            $arrtwoPaySettings['ItemNumber'] = Admincomponents::getTransactionSessionID();
            $arrtwoPaySettings['Transactid'] = RAND(10000, 895689596);

            $planPrice = $planDetails[0]->price;
            $planDuration = $planDetails[0]->vBillingInterval;
            $planDuration = ($planDuration == 'M') ? 'D' : $planDuration;
            $planDurationLength = $planDetails[0]->nBillingDuration;


            // subscription related variables
            $arrtwoPaySettings['a1'] = $authorizeInfo['amount']; // price_of_first_trial_period
            $arrtwoPaySettings['p1'] = $planDurationLength; // duration_length_of_first_trial_period
            $arrtwoPaySettings['t1'] = $planDuration; // duration_of_first_trial_period
            $arrtwoPaySettings['a3'] = $planPrice; // price_of_subscription
            $arrtwoPaySettings['p3'] = $planDurationLength; // length_of_the_regular_billing_cycle
            $arrtwoPaySettings['t3'] = $planDuration; // regular_billing_cycle_units

            $arrtwoPaySettings['src'] = '1';
            $arrtwoPaySettings['sra'] = '1';
            $arrtwoPaySettings['no_note'] = '1';
            $arrtwoPaySettings['modify'] = '0';
            $arrtwoPaySettings['subscr_date'] = date('Y-m-d');


            if ($paypalSettings['Paypaltestmode'] == "Y")
                $arrtwoPaySettings['Testmode'] = 'Y';
            //set session pf payment
            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrtwoPaySettings', $arrtwoPaySettings);
                $objSession->set('paymantflage', 1);
            }
            //   echopre($arrtwoPaySettings);exit; // check point
            PageContext::$response->renderPaymant = Payments::paypalsubscription($arrtwoPaySettings);
        } else if ($paymantArray['currentpaymant'] == 'googlecheckout') {

            $planDetails = User::getPlanDetails($productServices);
            // assign the product informations
            $arrGCheckDetails['items']['item_name'] = SITE_NAME;
            $arrGCheckDetails['items']['item_desc'] = $planDetails[0]->vServiceName;
            $arrGCheckDetails['items']['count'] = 1;
            $arrGCheckDetails['items']['amount'] = $authorizeInfo['amount'];


            $arrGCheckDetails['url_edit_cart'] = BASE_URL . "index/otherpaymantbuy";


            $arrGCheckDetails['url_continue_shopping'] = BASE_URL . "index/otherpaymantsucess/googlecheckout/success";
            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrGooglecheckout', $arrGCheckDetails);
                $objSession->set('paymantflage', 1);
            }


            PageContext::$response->renderPaymant = Payments::doGoogleCheckOut($arrGCheckDetails);
        } else if ($paymantArray['currentpaymant'] == 'yourpay') {
            //echopre(PageContext::$request);
            $YourPaySettings = Payments::getYoursPaySettings();
            $arrYourPay['yourpay_storeid'] = $YourPaySettings['yourpay_storeid'];
            $arrYourPay['yourpay_demo'] = $YourPaySettings['yourpay_demo'];
            $arrYourPay['keyfile'] = $YourPaySettings['yourpay_pemfile'];
            $arrYourPay['ordertype'] = "SALE";
            $arrYourPay['userinfo'] = $authorizeInfo;
            $arrYourPay['yp_cardno'] = PageContext::$request['yp_cardno'];
            $arrYourPay['yp_expm'] = PageContext::$request['yp_expm'];
            $arrYourPay['yp_expy'] = PageContext::$request['yp_expy'];
            $arrYourPay['yp_cvno'] = PageContext::$request['yp_cvno'];
            $resPayment = Payments::doYourPay($arrYourPay);
            //echopre($resPayment);
            //TODO : need to add the transaction checking
            $result = Payments::chkYourPay($resPayment, $arrtwoPaySettings);
            if ($result) {  // lets hope the payment success
                $this->redirect('index/otherpaymantsucess/yourpay/success/transactid/');
                exit;
            } else {  // the payment fails
                $this->redirect('index/paynow/2/error');
                exit;
            }
        } else if ($paymantArray['currentpaymant'] == 'moneybookers') {

            $MoneyBookerSettings = Payments::getMoneyBookersSettings();

            $moneyBookersInfo = array();
            $moneyBookersInfo['pay_to_email'] = $MoneyBookerSettings['moneybookers_emailid'];
            $moneyBookersInfo['status_url'] = BASE_URL . "index/otherpaymantsucess/moneybookers/sucess";
            //$moneyBookersInfo['status_url']           = 'http://clients.iscripts.com/testspace/googlecheckout.php';
            $moneyBookersInfo['language'] = 'EN';
            $moneyBookersInfo['amount'] = $authorizeInfo['amount'];
            $moneyBookersInfo['currency'] = 'USD';
            $moneyBookersInfo['detail1_description'] = 'Description';
            $moneyBookersInfo['detail1_text'] = 'Order Purchase';
            $moneyBookersInfo['return_url'] = BASE_URL . "index/otherpaymantsucess/moneybookers/success";
            $moneyBookersInfo['confirmation_note'] = "Payment Sucess";



            if ($objSession->get('paymantflage') == "") {
                $objSession->set('arrMoneyBookers', $moneyBookersInfo);
                $objSession->set('paymantflage', 1);
            }
            PageContext::$response->renderPaymant = Payments::doMoneyBookers($moneyBookersInfo);
        } else if ($paymantArray['currentpaymant'] == 'quickbook') {

            $quickbookSettings = Payments::getQuickBookSettings();
            $quickbookSettings['qb_cardno'] = PageContext::$request['yp_cardno'];
            $quickbookSettings['qb_expm'] = PageContext::$request['yp_expm'];
            $quickbookSettings['qb_expy'] = PageContext::$request['yp_expy'];
            $quickbookSettings['qb_cvno'] = PageContext::$request['yp_cvno'];
            $quickbookSettings['amount'] = $authorizeInfo['amount'];

            $quickbookSettings['transid'] = rand(1, 1000);

            $quickbookSettings['datetime'] = date("Y-m-d H:i:s");
            if ($quickbookSettings['quickbook_testmode'] == 'Y')
                $quickbookSettings['host'] = 'https://webmerchantaccount.ptc.quickbooks.com/j/AppGateway';
            else
                $quickbookSettings['host'] = 'https://merchantaccount.ptc.quickbooks.com/j/AppGateway';



            $result = Payments::doQuickbookPayment($quickbookSettings);
            $_SESSION['quickbookpay'] = $result;
            if ($result['success'] == 1) {
                $this->redirect('index/otherpaymantsucess/quickbook/success/');
                exit;
            } else {  // the payment fails
                $this->redirect('index/otherpaymantsucess/quickbook/paymentfailed');
                exit;
            }
            //echopre1($result);
        }



        //************Akhil Paymant code ends**************
    //
    }

    public function otherpaymantsucess($paystatus = "", $msg = "") {

        PageContext::addStyle("global.css");
        PageContext::addStyle("product_details.css");
        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addScript('paynow.js');
        //set layout starts
        Utils::loadActiveTheme();
        //PageContext::$response->themeurl = BASE_URL.'themes/theme1/';
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //set layout ends
        $objSession = new LibSession();

        if ($objSession->get('paymantflage') == 1) {
            $authorizeInfo = $objSession->get('authorizeInfo');
            $storeName = $objSession->get('storeName');
            $userArray = $objSession->get('userArray');
            $userId = $objSession->get('userId');
            $productArray = $objSession->get('productArray');
            $status = $objSession->get('status');
            $upgradeFlag = $objSession->get('upgradeFlag');
            $productLookUpId = $objSession->get('productLookUpId');
            $arrtwoPaySettings = $objSession->get('arrtwoPaySettings');
            $arrGcheckSettings = $objSession->get('arrGooglecheckout');
            $arrMoneyBookers = $objSession->get('arrMoneyBookers');
            $productArray["transactionSession"] = $arrtwoPaySettings["ItemNumber"];
            $objSession->set('paymantflage', "");


            if (isset($paystatus) && $paystatus == 'twocheckout') {
                $status = Payments::chkTwoCheckoutPayment(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypalflowlink') {

                $status = Payments::chkPaypalflowlink(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypalxpress') {
                $status = Payments::chkpayPaypalexpress(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypaladvanced') {
                $status = Payments::chkPaypaladvanced(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'braintree' && isset($msg) && $msg == 'sucess') {
                $braintreeResponce = $objSession->get('requestBrain');
                $status = Payments::chkBreantree($braintreeResponce, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'ogone' && isset($msg) && $msg == 'sucess') {


                $status = Payments::chkOgone(PageContext::$request, $arrtwoPaySettings);
                //echopre1($status)
            } else if (isset($paystatus) && $paystatus == 'paypal' && isset($msg) && $msg == 'sucess') {
                $status = Payments::chkPaypal(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'googlecheckout') {
                $status = Payments::chkGoogleCheckOut(PageContext::$request, $arrGcheckSettings);
            } else if (isset($paystatus) && $paystatus == 'moneybookers') {


                $status = Payments::chkMoneyBookers(PageContext::$request, $arrMoneyBookers);
            } else if (isset($paystatus) && $paystatus == 'quickbook') {
                $status = $_SESSION['quickbookpay'];
            }

            // Append Payment Method with the success method
            $status['paymentMethod'] = $paystatus;

            //   $status = Payments::doAllPaymants($paymantArray['currentpaymant'], $arrtwoPaySettings);
            if ($status['success'] == 1) {
                //  if(1){
                $subdom = $storeName;
                $subdom = strtolower($subdom);
                $subdom = str_replace(" ", '', $subdom);
                User::storePaymentsEntry($status['Amount'], $paystatus, $status['TransactionId']);
                if ($upgradeFlag == 1) {
                    $subdom = User::getSubDomainName($productLookUpId);
                }

                $data = $this->createaccountafterpaymentother($userArray, $subdom, $userId, $productArray, $status, $upgradeFlag, $productLookUpId);
            } else if ($status['failed'] == 1) {
                //$contents    = 'Please enter the Card Details';
                $data = array('failed' => 1, 'list' => $status['Message']);
            } else {
                $data = array('failed' => 1, 'list' => $status['Message']);
            }
            PageContext::$response->registerDomain = $data;
            // die;
            /// $this->redirect('index/viewlisting/' . $listId . '/1/2/');
            // exit;
        }
    }

    public function otherpaymentipn($paystatus = "", $msg = "") {
        $this->view->disableView();
        $objSession = new LibSession();


        $responseArr = Payments::paypalsubscriptionIPN();

        /*         * ********** Expected Results in Response Array ********* */
        //$responseArr["error"] = ""; // returns the error message if there is any error
        //$responseArr["status"] = ""; // returns the status of the payment 1 => success, 0 => failure
        //$responseArr["data"] = ""; // returns the post data with key and value
        /*         * ****************************************************** */

        $responseArr["error"];
        $responseArr["status"];
        $responseArr["data"];
        $responseArr["txn_type"];


//        $mm = NULL;
//
//        foreach($responseArr as $responseItemKey => $responseItemValue){
//            if(is_array($responseItemValue)){
//                foreach($responseItemValue as $rk => $rv){
//                    $mm .= $rk." = ".$rv."<br>";
//                }
//            } else {
//                $mm .= $responseItemKey." = ".$responseItemValue;
//            }
//        }



        switch ($responseArr["data"]["txn_type"]) {
            case 'subscr_signup': //subscription sign-up
                // Donot do any action here as initial bill is already generated on initial purchase
                break;
            case 'subscr_payment': //subscription payment
                if (isset($responseArr["data"]["item_number"])) {
                    //Transaction Session
                    $transactionSession = $responseArr["data"]["item_number"];
                    if (!empty($transactionSession)) {
                        $productLookUpID = Admincomponents::getProductLookupIDwithTransactionSessionID($transactionSession);


                        if ($responseArr["status"] == 1) {

                            // To Do : Bill process
                            $payArr = array('paymentSuccessful' => true, 'paymentError' => NULL, 'transactionId' => NULL);
                            Cronhelper::generateBillSubscription($responseArr, $payArr);
                        } else {

                            // On failure update billing entry as failure and mark attempt as 1
                            //mapTransactionSessionWithBill
                            $billArr = Admincomponents::mapTransactionSessionWithBill($transactionSession);
                            if (!empty($billArr)) {
                                foreach ($billArr as $billItem) {
                                    $billMainID = NULL;
                                    $billMainID = $itemBill->nBmId;
                                    $attemptFlag = false;
                                    if ($itemBill->vDomain == 1) {
                                        // Check whether Domain renewal date falls within this month
                                        $chkRenewalDate = Cronhelper::checkDateFallsWithinCurrentMonth($itemBill->dDateNextBill);
                                        if ($chkRenewalDate == true) {
                                            //$attemptFlag = true; // for the time being domain renewal is not considered in PayPal Subscription with IPN
                                        }
                                    } else {
                                        $attemptFlag = true;
                                    }
                                    if ($attemptFlag == true) {
                                        Admincomponents::updateBillingAttempt($billMainID);
                                    }
                                }
                            }
                            // End entry as failure
                        }
                    }
                }
                break;
            case 'subscr_eot': //subscription's end-of-term
                $msg = NULL;
                if (isset($responseArr["data"]["item_number"])) {

                    //Transaction Session
                    $transactionSession = $responseArr["data"]["item_number"];
                    if (!empty($transactionSession)) {
                        $productLookUpID = Admincomponents::getProductLookupIDwithTransactionSessionID($transactionSession);

                        if ($responseArr["status"] == 1) {
                            // To Do : Bill process
                            $payArr = array('paymentSuccessful' => true, 'paymentError' => NULL, 'transactionId' => NULL);

                            Cronhelper::generateBillSubscription($responseArr, $payArr);
                            // Since its an end of the term detach billing
                            Admincomponents::updateInvoice($productLookUpID, 1);
                        } else {
                            // On failure update billing entry as failure
                            Admincomponents::updateInvoice($productLookUpID, 1);
                            $msg = "Last Payment was failure";
                        }
                        Cronhelper::generateSubscriptionEndOfTermNotification($productLookUpID, "admin", $msg);
                        Cronhelper::generateSubscriptionEndOfTermNotification($productLookUpID, "user", $msg);
                    }
                }
                break;
            case 'subscr_failed': //subscription payment failure
                if (isset($responseArr["data"]["item_number"])) {

                    //Transaction Session
                    $transactionSession = $responseArr["data"]["item_number"];



                    if (!empty($transactionSession)) {
                        //mapTransactionSessionWithBill
                        $billArr = Admincomponents::mapTransactionSessionWithBill($transactionSession);
                        if (!empty($billArr)) {
                            foreach ($billArr as $billItem) {
                                $billMainID = NULL;
                                $billMainID = $itemBill->nBmId;
                                $attemptFlag = false;
                                if ($itemBill->vDomain == 1) {
                                    // Check whether Domain renewal date falls within this month
                                    $chkRenewalDate = Cronhelper::checkDateFallsWithinCurrentMonth($itemBill->dDateNextBill);
                                    if ($chkRenewalDate == true) {
                                        //$attemptFlag = true; // for the time being domain renewal is not considered in PayPal Subscription with IPN
                                    }
                                } else {
                                    $attemptFlag = true;
                                }
                                if ($attemptFlag == true) {
                                    Admincomponents::updateBillingAttempt($billMainID);
                                }
                            }
                        }
                        // End entry as failure
                    }
                }
                break;
            case 'subscr_cancel': //subscription cancellation
                $operationType = 0;

                if (isset($responseArr["data"]["item_number"])) {

                    //Transaction Session
                    $transactionSession = $responseArr["data"]["item_number"];

                    if (!empty($transactionSession)) {
                        $productLookUpID = Admincomponents::getProductLookupIDwithTransactionSessionID($transactionSession);

                        $storeServerInfoArr = Admincomponents::getStoreServerInfo($productLookUpID);

                        if (!empty($storeServerInfoArr)) {

                            PageContext::includePath('cpanel');

                            $cpanelObj = new cpanel();

                            $operationMode = ($operationType == 1) ? 'enable' : 'disable';

                            //$res = $cpanelObj->enableDisableCpanelAccount($storeServerInfoArr, $operationMode);
                            $res = $cpanelObj->terminateaccount($storeServerInfoArr['c_user'], $storeServerInfoArr['c_pass'], $storeServerInfoArr['c_host']);

                            if ($res) {

                                $msg = "Successfully ";

                                $msg .= ($operationType == 1) ? 'activated ' : 'terminated ';

                                $msg .= ' the account too!';

                                $invOperationMode = ($operationType == 1) ? 0 : 1; // 0 => enabling the invoice back, changing the delete status in billing Main to 0,
                                // 1 => disabling the invoice, changing the delete status in billing Main to 1,

                                $storeOperationMode = ($operationType == 1) ? 1 : 0;

                                Admincomponents::updateExpiredDomain($productLookUpID, $storeOperationMode);

                                Admincomponents::updateInvoice($productLookUpID, $invOperationMode);
                            } else {

                                $msg = "Some technical issues to ";

                                $msg .= ($operationType == 1) ? 'activate ' : 'terminate ';

                                $msg .= 'account';

                                Admincomponents::updateInvoice($productLookUpID, 1);
                                Cronhelper::generateAccountSuspensionFailureNotification($productLookUpID, $msg);
                            }
                        } else {
                            $msg = "Some technical issues to ";

                            $msg .= ($operationType == 1) ? 'activate ' : 'suspend ';

                            $msg .= 'account';

                            Admincomponents::updateInvoice($productLookUpID, 1);
                            Cronhelper::generateAccountSuspensionFailureNotification($productLookUpID, $msg);
                        }
                        Cronhelper::generateAdministratorSubscriptionCancellationNotification($productLookUpID, $msg);
                        Cronhelper::generateUserSubscriptionCancellationNotification($productLookUpID, "Thank you for doing business with us!");
                    }
                }

                break;
            case 'web_accept':
                // Donot do any action here as initial bill is already generated on initial purchase
                break;
        }

        die();
    }

// End Function

    public function templates() {
        $sessionObj = new LibSession();

        $userID = $sessionObj->get("userID");


        $userDetails = $sessionObj->get('userDetails');
PageContext::$response->userLogged = false;
//echopre($userDetails);


        if (!empty($userID) || !empty($userDetails)) {

            PageContext::$response->userLogged = true;
        }


        Utils::loadActiveTheme();
        PageContext::addPostAction('cloudtopmenupage');

        PageContext::$response->plan_id = ($_GET['plan_id'])?$_GET['plan_id']:0;


        PageContext::addPostAction('cloudfooterpage');
        PageContext::addPostAction('loginpop', 'index');
        $this->view->setLayout("productpage");

        PageContext::$response->screenshotsUrl = BASE_URL . 'project/styles/screenshots/';

        PageContext::$response->selectedLink = 'templates';
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        $dataArr1 = Admincomponents::getPaidTemplates(array(array('field' => 'vActive', 'value' => 1)));
        //echopre($dataArr1);
        $dataArr = array();
        // Filter dataArr
        foreach ($dataArr1 as $item) {
            //if (is_file(FILE_UPLOAD_DIR . $item->zipFile) && is_file(FILE_UPLOAD_DIR . $item->homeScreenshot)) {
                $dataArr[] = $item;
            //}
        }

        PageContext::$response->pageContents = $dataArr;
        //echopre($dataArr1);
        PageContext::$response->userID = $userID;
        $pageTitle = "Templates";
        $this->view->pageTitle = $pageTitle;
    }

// End Function

    public function templatedetails($templateID) {
        $sessionObj = new LibSession();

        $userID = $sessionObj->get("userID");
        Utils::loadActiveTheme();
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        PageContext::addPostAction('loginpop', 'index');
        $this->view->setLayout("productpage");

        PageContext::$response->screenshotsUrl = BASE_URL . 'project/styles/screenshots/';

        PageContext::$response->selectedLink = 'templates';
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        $dataArr = Admincomponents::getPaidTemplates(array(array('field' => 'nTemplateId', 'value' => $templateID)));
        //echopre($dataArr1);
        PageContext::$response->pageContents = $dataArr;
        PageContext::$response->userID = $userID;
        $pageTitle = "Templates";
        $this->view->pageTitle = $pageTitle;

        // Slider Css for templates
        PageContext::addScript("templateslider.js");
        PageContext::addScript("jquery.easing.1.3.js");
        PageContext::addStyle("templateslider.css");
    }

// End Function

    public function loadlogin() {

    }

// End Function

    public function buytemplates($templateID = NULL) {



        $sessionObj = new LibSession();
        $userID = $sessionObj->get("userID");

        // Login Check
        If (!$userID) {
            $this->redirect('index');
        }

        //Login Check Ends
        PageContext::addJsVar("image_file_url", IMAGE_FILE_URL);
        PageContext::addJsVar("image_url", IMAGE_URL);
        // get user stores
        PageContext::addStyle("proceed_to_buy.css");
        Utils::loadActiveTheme();
        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');
        PageContext::addScript('validatepayment.js');
        PageContext::addPostAction('renderallpayment', 'payments');

        $this->view->setLayout("productpage");

        if ($templateID) {
            $sessionObj->set("templateID", $templateID);
        }

        $cardDetailsArr = array();
        if (!empty($userID)) {

            $cardDetailsArr = User::fetchUserCreditCardDetails();
            if (!empty($cardDetailsArr)) {
                PageContext::$response->cardDetails = $cardDetailsArr;
            }
        }

        $userDetails = User::fetchUserProfile();

        PageContext::$response->userDetails = $userDetails;

        $templateID = $sessionObj->get("templateID");

        PageContext::$response->screenshotsUrl = BASE_URL . 'project/styles/screenshots/';

        PageContext::$response->selectedLink = 'templates';
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        PageContext::$response->templateID = $templateID;
        $dataArr = Admincomponents::getPaidTemplates(array(array('field' => 'vActive', 'value' => 1)));
        //echopre($dataArr);
        PageContext::$response->pageContents = $dataArr;
        PageContext::$response->userID = $userID;

        //
        $pageTitle = "Buy Templates";
        $this->view->pageTitle = $pageTitle;
        $userStores = Admincomponents::getUserStore($userID);
        PageContext::$response->userStores = $userStores;


        $paymentsEnabled = Payments::getEnabledPaymnets();

        PageContext::$response->creditcard = Payments::getCreditCardPaypalpro();
        PageContext::$response->paymnetsEnabled = $paymentsEnabled;
    }

// End Function

    public function templatepaysuccess() {
        $templateID = $sessionObj->get("templateID");
        $lookupID = $sessionObj->get("lookupID");

        $dataArr = Admincomponents::getPaidTemplates(array(array('field' => 'nTemplateId', 'value' => $templateID)));
        // Installation process

        $serverInfoArr = Admincomponents::getStoreServerInfo($lookupID);    // getStoreServerInfo
        if (!empty($serverInfoArr)) {
            PageContext::includePath('cpanel');
            $cpanelObj = new cpanel();

            $file = $dataArr[0]->zipFile; // the template zip file should come here

            $ftpPathArr = array('source_path' => FILE_UPLOAD_DIR . $file,
                'destination_path' => '/public_html/app/webroot/' . $file);

            $operationArgArr = array(
                'sourcefiles' => '/public_html/app/webroot/' . $file,
                'destfiles' => '/public_html/app/webroot/',
                'op' => 'extract',
            );

            $cpanelObj->doFtpUploadAndCpanelOperations($serverInfoArr, $ftpPathArr = array(), $operationArgArr = array());
            // End Installation Process
        }
    }

    //Create account using paymnet gateway out of system ends Ak
// Update Banner click count
    public function ajaxBannerCount() {
        $updateStatus = User::setClickCount($this->post('banner'));
        echo "sucess";
        exit;
    }

    public function checkPaymentResult() {

        exit();
    }

    public function paymentmiddleware() {
        $this->view->disableLayout();
        $this->view->disableView();

        $frmStr = $_POST; // Posted array
        // User ID
        $sessionObj = new LibSession();
        $userID = $sessionObj->get("userID");

        //Template
        $frmStr['template'];
        $templateArr = explode("||", $frmStr['template']);

        $templateID = $templateArr[0];
        $templateFile = $templateArr[1];
        $templateName = $templateArr[2];
        $templateCost = Utils::formatPrice($templateArr[3]);

        //Store
        $frmStr['store'];
        $storeArr = explode("||", $frmStr['store']);
        $lookupID = $storeArr[0];

        $dataArr = array();
        $status = $installationStatus = $process = array();

        //Payment Option
        switch ($frmStr['paymentOption']) {
            case 'authorize':
                $dataArr = array('desc' => 'Template Purchase for ' . $storeArr[1] . ' - ' . $templateName,
                    'amount' => $templateCost,
                    'expMonth' => $frmStr['expM'],
                    'expYear' => $frmStr['expY'],
                    'cvv' => $frmStr['cvv'],
                    'ccno' => $frmStr['ccno'],
                    'fName' => stripslashes($frmStr['fname']),
                    'lName' => stripslashes($frmStr['lname']),
                    'add1' => stripslashes($frmStr['add1']),
                    'city' => stripslashes($frmStr['city']),
                    'state' => stripslashes($frmStr['state']),
                    'country' => stripslashes($frmStr['country']),
                    'zip' => stripslashes($frmStr['zip']));

                $status = Payments::authoriz($dataArr);

                break;
            case 'paypalpro':
                $settings = Payments::getPaypalproSettings();

                $dataArr = array('Paypalprousername' => $settings['Paypalprousername'],
                    'Paypalpropassword' => $settings['Paypalpropassword'],
                    'Paypalprosignature' => $settings['Paypalprosignature'],
                    'Paymenttype' => 'Sale',
                    'Testmode' => $settings['Paypalprotestmode'],
                    'Grandtotal' => $templateCost,
                    'Creditcardtype' => $frmStr['paymentmethod_paypalpro'],
                    'Creditcardnumber' => $frmStr['ccno'],
                    'Currency' => CURRENCY,
                    'Expdate' => $frmStr['expM'] . $frmStr['expY'],
                    'Cvv2' => $frmStr['cvv'],
                    'Firstname' => stripslashes($frmStr['fname']),
                    'Lastname' => stripslashes($frmStr['lname']),
                    'Street' => 'Sale',
                    'City' => stripslashes($frmStr['city']),
                    'Zip' => $frmStr['zip'],
                    'State' => $frmStr['state'],
                    'Countrycode' => stripslashes($frmStr['country']));

                $payment = Payments::payPaypalpro($dataArr);
                $status = Payments::chkpayPaypalpro($payment, $dataArr);

                break;
            case 'paypalflow':
                $settings = Payments::getPaypalflowSettings();

                $dataArr = array('Paypalflowvendorid' => $settings['Paypalflowvendorid'],
                    'Paypalflowpassword' => $settings['Paypalflowpassword'],
                    'Paypalflowpartnerid' => $settings['Paypalflowpartnerid'],
                    'Testmode' => $settings['Paypalflowtestmode'],
                    'Paymenttype' => 'S',
                    'Tender' => 'C',
                    'Grandtotal' => $templateCost,
                    'Comment1' => 'Sale',
                    'Creditcardnumber' => $frmStr['ccno'],
                    'Cvv2' => $frmStr['cvv'],
                    'Expdate' => $frmStr['expM'] . $frmStr['expY'],
                    'Firstname' => stripslashes($frmStr['fname']),
                    'Lastname' => stripslashes($frmStr['lname']),
                    'Street' => 'Sale',
                    'City' => stripslashes($frmStr['city']),
                    'Zip' => $frmStr['zip'],
                    'State' => $frmStr['state'],
                    'Countrycode' => stripslashes($frmStr['country']));

                $payment = Payments::payPaypalflow($dataArr);
                $status = Payments::chkPaypalflow($payment, $dataArr);
                break;
            case 'yourpay':
                $YourPaySettings = Payments::getYoursPaySettings();
                $arrYourPay['yourpay_storeid'] = $YourPaySettings['yourpay_storeid'];
                $arrYourPay['yourpay_demo'] = $YourPaySettings['yourpay_demo'];
                $arrYourPay['ordertype'] = "SALE";
                $userInfoArr = array('amount' => $templateCost,
                    'fName' => $frmStr['fname'],
                    'lName' => $frmStr['lname'],
                    'add1' => $frmStr['add1'],
                    'city' => $frmStr['city'],
                    'state' => $frmStr['state'],
                    'country' => $frmStr['country'],
                    'email' => $frmStr['email']);
                $arrYourPay['userinfo'] = $userInfoArr;
                $arrYourPay['yp_cardno'] = PageContext::$request['yp_cardno'];
                $arrYourPay['yp_expm'] = PageContext::$request['yp_expm'];
                $arrYourPay['yp_expy'] = PageContext::$request['yp_expy'];
                $arrYourPay['yp_cvno'] = PageContext::$request['yp_cvno'];
                $resPayment = Payments::doYourPay($arrYourPay);

                //TODO : need to add the transaction checking
                $status = Payments::chkYourPay($resPayment, $dataArr);
                break;
            case 'quickbook':
                $quickbookSettings = Payments::getQuickBookSettings();
                $quickbookSettings['qb_cardno'] = PageContext::$request['yp_cardno'];
                $quickbookSettings['qb_expm'] = PageContext::$request['yp_expm'];
                $quickbookSettings['qb_expy'] = PageContext::$request['yp_expy'];
                $quickbookSettings['qb_cvno'] = PageContext::$request['yp_cvno'];
                $quickbookSettings['amount'] = $templateCost;

                $quickbookSettings['transid'] = rand(1, 1000);

                $quickbookSettings['datetime'] = date("Y-m-d H:i:s");
                if ($quickbookSettings['quickbook_testmode'] == 'Y')
                    $quickbookSettings['host'] = 'https://webmerchantaccount.ptc.quickbooks.com/j/AppGateway';
                else
                    $quickbookSettings['host'] = 'https://merchantaccount.ptc.quickbooks.com/j/AppGateway';

                $status = Payments::doQuickbookPayment($quickbookSettings);



                break;
        }

        $logComment = NULL;
        if ($status['success'] == 1) {
            $installationStatus = Paymenthelper::doTemplateInstallation($templateID, $lookupID);

            if ($installationStatus['ftp'] == 1) {
                $logComment = 'Installed';
            } else {
                $logComment = 'Installation failed';
            }

            $logArr = array('nTemplateId' => $templateID,
                'nUId' => $userID,
                'nPLId' => $lookupID,
                'amount' => $templateCost,
                'paymentMethod' => $frmStr['paymentOption'],
                'transactionId' => $status['TransactionId'],
                'comments' => $logComment, 'lookupID' => $lookupID);
            Admincomponents::logTemplatePurchase($logArr);
        }

        $process = array('payment' => $status,
            'installation' => $installationStatus);
        //header('Content-Type: application/json');

        echo json_encode($process);

        exit();
    }

//End Function

    public function paymentmiddlewareformpost() {
        PageContext::addJsVar("image_file_url", IMAGE_FILE_URL);
        PageContext::addJsVar("image_url", IMAGE_URL);
        // get user stores
        PageContext::addStyle("proceed_to_buy.css");
        Utils::loadActiveTheme();
        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');
        PageContext::addScript('validatepayment.js');
        $this->view->setLayout("productpage");
        $frmStr = $_POST; // Posted array
        //echopre($frmStr);
        // User ID
        $sessionObj = new LibSession();
        $userID = $sessionObj->get("userID");

        //Template
        $frmStr['template'];
        $templateArr = explode("||", $frmStr['template']);

        $templateID = $templateArr[0];
        $templateFile = $templateArr[1];
        $templateName = $templateArr[2];
        $templateCost = Utils::formatPrice($templateArr[3]);

        //Store
        $frmStr['store'];
        $storeArr = explode("||", $frmStr['store']);
        $lookupID = $storeArr[0];

        //Item Name
        $itemName = 'Template Purchase for ' . $storeArr[1] . ' - ' . $templateName;

        $dataArr = array();
        $status = $installationStatus = $process = array();

//        exit('Control comes here!');
        $dataArr['Grandtotal'] = $templateCost;
        $dataArr['Currency'] = CURRENCY;

        //set Template Custom

        $sessionObj->set('template-custom', $frmStr);

        //Payment Option
        switch ($frmStr['paymentOption']) {
            case 'paypal':
                $paypalSettings = Payments::getPaypalSettings();
                $dataArr['Paypalemail'] = $paypalSettings['Paypalemail']; //"mahi_1_1321000734_biz@yahoo.com";
                $dataArr['resultURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/success";
                $dataArr['cancelURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/cancel";
                $dataArr['notifyURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/process";
                $dataArr['Itemname'] = $itemName;
                $dataArr['Transactid'] = RAND(10000, 895689596);

                if ($paypalSettings['Paypaltestmode'] == "Y")
                    $dataArr['Testmode'] = 'Y';
                //set session pf payment
                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrtwoPaySettings', $dataArr);
                //   echopre($dataArr);exit;
                PageContext::$response->renderPaymant = Payments::paypal($dataArr);

                break;
            case 'paypaladvanced':
                $paypaladvancedSettings = Payments::getPaypaladvancedSettings();
                $dataArr['Paypaladvancedvendorid'] = $paypaladvancedSettings['Paypaladvancedvendorid']; //"palexanderpayflowtest";
                $dataArr['Paypaladvancedpassword'] = $paypaladvancedSettings['Paypaladvancedpassword']; //'demopass123';
                $dataArr['Paypaladvancedpartner'] = $paypaladvancedSettings['Paypaladvancedpartner']; //'PayPal';
                $dataArr['Paypaladvanceduser'] = $paypaladvancedSettings['Paypaladvancedusername']; //'palexanderpayflowtestapionly';


                $dataArr['Paymenttype'] = urlencode('A');
                $dataArr['Createsecuretocken'] = 'Y';
                // $dataArr['Currency'] = "USD";
                $dataArr['Securetockenid'] = uniqid('MySecTokenID-');


                if ($paypaladvancedSettings['Paypaladvancedtestmode'] == "Y")
                    $dataArr['Testmode'] = 'Y';

                $dataArr['ReturnURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/success";
                $dataArr['CancelURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/cancel";
                $dataArr['ErrorURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/error";

                $dataArr['Billtofirstname'] = addslashes($frmStr['fname']);
                $dataArr['Billtolastname'] = addslashes($frmStr['lname']);
                $dataArr['Billtostreet'] = addslashes($frmStr['add1']);
                $dataArr['Billtocity'] = addslashes($frmStr['city']);
                $dataArr['Country'] = $frmStr['country'];

                //set session pf payment
                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrtwoPaySettings', $dataArr);

                $redirectURL = Payments::setPaypaladvancedUrl(Payments::payPaypaladvanced($dataArr), $dataArr);
                if ($redirectURL != false) {
                    Headerredirect::httpRedirect($redirectURL);
                }
                break;
            case 'paypalxpress':
                $paypalXpresSettings = Payments::getPaypalXpresSettings();
                $dataArr['Paypalexpressusername'] = $paypalXpresSettings['PaypalXpresUsername']; //"seller_1297271002_biz_api1.yahoo.com";
                $dataArr['Paypalexpresspassword'] = $paypalXpresSettings['PaypalXpresPassword']; //'1297271011';
                $dataArr['Paypalexpresssignature'] = $paypalXpresSettings['PaypalXpresSignature']; //'AFcWxV21C7fd0v3bYYYRCpSSRl31A-Vd1YRxIrhGWvUd2XnlrhGdk6rY';


                if ($paypalXpresSettings['PaypalXprestestmode'] == 'Y')
                    $dataArr['Testmode'] = 'Y';

                $dataArr['ReturnURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/success";
                $dataArr['CancelURL'] = BASE_URL . BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/cancel";

                //set session pf payment
                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrtwoPaySettings', $dataArr);


                $redirectURL = Payments::payPaypalexpress($dataArr);
                if ($redirectURL != "") {
                    Headerredirect::httpRedirect($redirectURL);
                }
                break;
            case 'paypalflowlink':
                $paypalflowlinkSettings = Payments::getPaypallinkSettings();
                $dataArr['Paypallinkvendorid'] = $paypalflowlinkSettings['Paypalflowlinkvendorid']; // "armiapayflow";
                $dataArr['Paypallinkpartnerid'] = $paypalflowlinkSettings['Paypalflowlinkpartnerid']; //  'PayPal';
                $dataArr['Paymenttype'] = 'S'; // Constant
                $dataArr['Method'] = 'CC'; // Constant
                $dataArr['Customerid'] = rand(1, 10000);
                $dataArr['Orderform'] = true;
                $dataArr['Showconfirm'] = true;

                if ($paypalflowlinkSettings['Paypalflowlinktestmode'] == 'Y')
                    $dataArr['Testmode'] = 'Y';

                $dataArr['ReturnURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/success";

                $dataArr['Firstname'] = urlencode($frmStr['fname']);
                $dataArr['Address'] = urlencode($frmStr['add1']);
                $dataArr['City'] = urlencode($frmStr['city']);
                $dataArr['Zip'] = urlencode($frmStr['zip']);
                $dataArr['Country'] = urlencode($frmStr['country']);
                $dataArr['Currency'] = urlencode('USD');
                $dataArr['Phone'] = '';
                $dataArr['Fax'] = '';

                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrtwoPaySettings', $dataArr);

                PageContext::$response->renderPaymant = Payments::payPaypalflowlink($dataArr);
                break;
            case 'ogone':
                $ogoneSettings = Payments::getOgoneSettings();
                $dataArr['Ogonepspid'] = $ogoneSettings['Ogonepartnerid'];
                $dataArr['Ogonepassphrase'] = $ogoneSettings['Ogonevendorid'];

                if ($ogoneSettings['Ogonetestmode'] == "Y")
                    $dataArr['Testmode'] = 'Y';

                $dataArr['DeclineURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/decline";
                $dataArr['CancelURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/cancel";
                $dataArr['ExceptionURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/exception";
                $dataArr['AcceptURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/success";
                ; //sucess return url

                $dataArr['Orderid'] = RAND(10000, 895689596);

                $dataArr['Language'] = "en_us";
                $dataArr['Logo'] = "Logo.jpg";
                $dataArr['Operation'] = 'SAL'; //Constant

                $dataArr['Firstname'] = $frmStr['fname'];
                $dataArr['Lastname'] = $frmStr['lname'];
                $dataArr['Email'] = $frmStr['email'];
                //set session pf payment
                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrtwoPaySettings', $dataArr);
                PageContext::$response->renderPaymant = Payments::payOgone($dataArr);
                break;
            case 'twocheckout':
                $twocheckoutSettings = Payments::getTwoCheckoutSettings();

                $dataArr['Vendorid'] = $twocheckoutSettings['TwoCheckoutvendorid']; //'1877160'; // vendor id from payment settings
                $dataArr['Company'] = "-NA-";
                $dataArr['Email'] = $frmStr['email']; // User Email
                $dataArr['Currency'] = 'USD';
                if ($twocheckoutSettings['TwoCheckouttestmode'] == 'Y') {
                    $dataArr['Testmode'] = "Y";
                }
                $dataArr['Cartid'] = rand(1, 1000);
                $dataArr['ReturnURL'] = BASE_URL . "index/paymentmiddlewaresuccess/" . $frmStr['paymentOption'] . "/success";

                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrtwoPaySettings', $dataArr);


                PageContext::$response->renderPaymant = Payments::payTwoCheckout($dataArr);

                break;
            case 'braintree':
                $braintreeSettings = Payments::getBraintreeSettings();
                $dataArr['Braintreemerchantid'] = $braintreeSettings['BraintreemerchantId']; //"f7mgykzp5b7txjf7";
                $dataArr['Braintreepublickey'] = $braintreeSettings['Braintreepublickey']; //'qfhh854tm6g6md9x';
                $dataArr['Braintreeprivatekey'] = $braintreeSettings['Braintreeprivatekey']; //'863323bad983dc6eca5dea1a7913a90f';
                $dataArr['Paymenttype'] = 'sale'; // Constant
                if ($braintreeSettings['Braintreetestmode'] == "Y") {
                    $dataArr['Testmode'] = 'Y';
                }

                $dataArr['Firstname'] = $frmStr['fname'];
                $dataArr['Lastname'] = $frmStr['lname'];
                $dataArr['Email'] = $frmStr['email'];

                //set session pf payment
                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrtwoPaySettings', $dataArr);

                $configValues = Payments::payBreantree($dataArr);
                if (isset($configValues) && count($configValues) > 0) {

                    /*  $renderFrom = '<form action="'. $configValues['form_url'].'" method="post" name="frmPayment" >';
                      $renderFrom .='<br>CCNumber :<input type="text" size="27" class="box2_admin" value="" maxlength="16" id="txtCCNumber" name="transaction[credit_card][number]">';
                      $renderFrom .='<br>CVV2<input type="text" size="27" class="box2_admin" maxlength="10" value="" id="txtCVV2" name="transaction[credit_card][cvv]">';
                      $renderFrom .='<br>expiration_date<input type="text" size="27" class="box2_admin" maxlength="10" id="expiration_date" value="" name="transaction[credit_card][expiration_date]">';
                      $renderFrom .=   '<input type="hidden" name="tr_data" value="'. $configValues['tr_data'].'" />
                      <input type="hidden" name="transaction[customer][first_name]" value="'. $configValues['firstName'].'" />
                      <input type="hidden" name="transaction[customer][last_name]" value="'. $configValues['lastName'].'" />
                      <input type="hidden" name="transaction[customer][email]" value="'. $configValues['email'].'" />
                      <br><input type="submit"  name="btnCompleteOrderbraintree" value="Pay Now" onclick="return validateForm(document.frmPayment);" class="btn-usr01">';

                      $renderFrom .= '</form>'; */
                    $renderFrom = '<form action="' . $configValues['form_url'] . '" method="post" name="frmPayment" >';
                    $renderFrom .='<table width="40%"  border="0" cellspacing="4" cellpadding="0" align="center">
  <tr>
    <td align="left">Card Number</td>
    <td align="left"><input type="text" size="27" class="box2_admin" value="" maxlength="16" id="txtCCNumber" name="transaction[credit_card][number]"></td>
  </tr>
  <tr>
    <td align="left">Expiry Date(MM/YYYY)</td>
    <td align="left"><input type="text" size="27" class="box2_admin" maxlength="10" id="expiration_date" value="" name="transaction[credit_card][expiration_date]"></td>
  </tr>
  <tr>
    <td align="left">CVV/CVV2 No</td>
    <td align="left"><input type="text" size="27" class="box2_admin" maxlength="10" value="" id="txtCVV2" name="transaction[credit_card][cvv]"></td>
  </tr>
  <tr>
    <td height="35">&nbsp;</td>
    <td align="left" valign="top" height="35"><input type="hidden" name="tr_data" value="' . $configValues['tr_data'] . '" />
                                        <input type="hidden" name="transaction[customer][first_name]" value="' . $configValues['firstName'] . '" />
                                        <input type="hidden" name="transaction[customer][last_name]" value="' . $configValues['lastName'] . '" />
                                        <input type="hidden" name="transaction[customer][email]" value="' . $configValues['email'] . '" />
                    <br><input type="submit"  name="btnCompleteOrderbraintree" value="Pay Now" onclick="return validateForm(document.frmPayment);" class="btn-usr01"></td>
  </tr>
</table>';




                    $renderFrom .= '</form>';

                    PageContext::$response->renderPaymant = $renderFrom;
                }
                break;
            case 'googlecheckout':
                $arrGCheckDetails = array();
                // assign the product informations
                $arrGCheckDetails['items']['item_name'] = $itemName;
                $arrGCheckDetails['items']['item_desc'] = $itemName;
                $arrGCheckDetails['items']['count'] = 1;
                $arrGCheckDetails['items']['amount'] = $dataArr['Grandtotal'];

                $arrGCheckDetails['url_edit_cart'] = BASE_URL . "index/paymentmiddlewareformpost";

                $arrGCheckDetails['url_continue_shopping'] = BASE_URL . "index/paymentmiddlewaresuccess/googlecheckout/success";
                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrGooglecheckout', $arrGCheckDetails);


                PageContext::$response->renderPaymant = Payments::doGoogleCheckOut($arrGCheckDetails);
                break;

            case 'moneybookers':

                $MoneyBookerSettings = Payments::getMoneyBookersSettings();

                $moneyBookersInfo = array();
                $moneyBookersInfo['pay_to_email'] = $MoneyBookerSettings['moneybookers_emailid'];
                $moneyBookersInfo['status_url'] = BASE_URL . "index/paymentmiddlewaresuccess/moneybookers/success";
                $moneyBookersInfo['language'] = 'EN';
                $moneyBookersInfo['amount'] = $templateCost;
                $moneyBookersInfo['currency'] = CURRENCY;
                $moneyBookersInfo['detail1_description'] = $itemName;
                $moneyBookersInfo['detail1_text'] = $itemName;
                $moneyBookersInfo['return_url'] = BASE_URL . "index/otherpaymantsucess/moneybookers/success";
                $moneyBookersInfo['confirmation_note'] = "Payment Sucess";



                if ($sessionObj->get('paymantflage') == "") {

                    $sessionObj->set('paymantflage', 1);
                }
                $sessionObj->set('arrMoneyBookers', $moneyBookersInfo);
                PageContext::$response->renderPaymant = Payments::doMoneyBookers($moneyBookersInfo);
                break;
        }

        $pageTitle = "Payment";
        $this->view->pageTitle = $pageTitle;
    }

//End Function

    public function paymentmiddlewaresuccess($paystatus = "", $msg = "") {

        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addScript('paynow.js');
        //set layout starts
        Utils::loadActiveTheme();
        //PageContext::$response->themeurl = BASE_URL.'themes/theme1/';
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        $dataArr = array();
        $status = $installationStatus = $process = array();
        //set layout ends
        $objSession = new LibSession();

        //$objSession->set('paymantflage',  1);
        $userID = $objSession->get("userID");
        //set Template Custom
        $frmStr = $objSession->get('template-custom');

        //Template
        $frmStr['template'];
        $templateArr = explode("||", $frmStr['template']);

        $templateID = $templateArr[0];
        $templateFile = $templateArr[1];
        $templateName = $templateArr[2];
        $templateCost = Utils::formatPrice($templateArr[3]);

        //Store
        $frmStr['store'];
        $storeArr = explode("||", $frmStr['store']);
        $lookupID = $storeArr[0];



        $objSession->set('template-custom', '');

        if ($objSession->get('paymantflage') == 1) {

            $authorizeInfo = $objSession->get('authorizeInfo');

            $userId = $objSession->get('userID');
            $arrtwoPaySettings = $objSession->get('arrtwoPaySettings');

            $arrGcheckSettings = $objSession->get('arrGooglecheckout');
            $arrMoneyBookers = $objSession->get('arrMoneyBookers');

            $objSession->set('paymantflage', "");
            if ($storeName == "")
                $storeName = time();
            if (isset($paystatus) && $paystatus == 'twocheckout') {
                $status = Payments::chkTwoCheckoutPayment(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypalflowlink') {

                $status = Payments::chkPaypalflowlink(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypalxpress') {
                $status = Payments::chkpayPaypalexpress(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypaladvanced') {
                $status = Payments::chkPaypaladvanced(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'braintree' && isset($msg) && $msg == 'success') {
                $braintreeResponce = $objSession->get('requestBrain');
                $status = Payments::chkBreantree($braintreeResponce, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'ogone' && isset($msg) && $msg == 'success') {
                $status = Payments::chkOgone(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'paypal' && isset($msg) && $msg == 'success') {
                $status = Payments::chkPaypal(PageContext::$request, $arrtwoPaySettings);
            } else if (isset($paystatus) && $paystatus == 'googlecheckout') {
                $status = Payments::chkGoogleCheckOut(PageContext::$request, $arrGcheckSettings);
            } else if (isset($paystatus) && $paystatus == 'moneybookers') {
                $status = Payments::chkMoneyBookers(PageContext::$request, $arrMoneyBookers);
            }

            //echopre($status); exit('Control');

            if ($status['success'] == 1) {
                $installationStatus = Paymenthelper::doTemplateInstallation($templateID, $lookupID);
                if ($installationStatus['ftp'] == 1) {
                    $logComment = 'Installed';
                } else {
                    $logComment = 'Installation failed';
                }

                $logArr = array('nTemplateId' => $templateID,
                    'nUId' => $userID,
                    'nPLId' => $lookupID,
                    'amount' => $templateCost,
                    'paymentMethod' => $frmStr['paymentOption'],
                    'transactionId' => $status['TransactionId'],
                    'comments' => $logComment, 'lookupID' => $lookupID);
                Admincomponents::logTemplatePurchase($logArr);
            }

            $process = array('payment' => $status,
                'installation' => $installationStatus);


            $processMessage = NULL;

            if ($process['payment']['success'] == 1 && $process['installation']['ftp'] == 1) {
                $processMessage = "Payment Completed and template installed into your store!.";
            } else if ($process['payment']['success'] == 1 && $process['installation']['ftp'] == 0) {
                $processMessage = "Payment Completed and template installation failed!.";
            } else if ($process['payment']['success'] == 0 && $process['installation']['ftp'] == 0) {
                $process['payment']['success'] = 0;
                $processMessage = "Payment and template installation failed!.";
            }

            $pageTitle = "Payment";
            $this->view->pageTitle = $pageTitle;
            PageContext::$response->processMsg = $processMessage;
            PageContext::$response->processStatus = $process['payment']['success'];
        }
    }

// End Function

    public function checkUserAccount() {
        $LibSession = new LibSession();
        if ($LibSession->get('userID')) {
            $data ["account_message"] = NULL;
            $data ["faild"] = 0;
            echo json_encode($data);
            exit;
        } else {

            $emailVal = $this->post('email');
            if ($emailVal != '' && User::checkAccount($emailVal)) {
                $data ["account_message"] = " This email address " . $emailVal . " is already associated with an active account. Please <a class='error-link' href='signin' title='" . $emailVal . "'>Login</a> to continue.";
                $data ["faild"] = 1;
            } else {
                $data ["account_message"] = NULL;
                $data ["faild"] = 0;
            }
            echo json_encode($data);
            exit;
        }
    }

    public function storedemo($cmsName='') {
        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //TODO: add theme folder existing validation
        $this->view->staticContentTitle = 'Demo';

        PageContext::$response->selectedLink = 'storedemo';
        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");
    }

// End Function

    public function loginpop() {

    }

//End Function

    public function contactus() {
        $sessionObj         = new LibSession();

        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        //TODO: add theme folder existing validation
        PageContext::$response->selectedLink = 'contactus';
        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");

        PageContext::addStyle("proceed_to_buy.css");
        PageContext::addStyle("global.css");
        PageContext::addScript("feedbackvalidation.js");

        PageContext::includePath('recaptcha');

        User::$dbObj = new Db();
        PageContext::$response->recaptcha_enable = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_enable'");
        $recaptcha_public_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_public_key'");
        $recaptcha_private_key = User::$dbObj->selectRow("Settings", "value", "settingfield='recaptcha_private_key'");

        if (PageContext::$response->recaptcha_enable == 'Y') {
            // RECAPTCHA CUSTOM STYLE
            PageContext::$headerCodeSnippet = '<script type="text/javascript">
                                             var RecaptchaOptions = {
                                                theme : \'clean\'
                                             };
                                             </script>';
            // RECAPTCHA ELEMENT
            PageContext::$response->publickey = (!empty($recaptcha_public_key)) ? $recaptcha_public_key : RECAPTCHA_PUBLICKEY;
            PageContext::$response->privatekey = (!empty($recaptcha_private_key)) ? $recaptcha_private_key : RECAPTCHA_PRIVATEKEY;

            $recaptchaHTML = null;
            $recaptchaError = null;

            if (!empty(PageContext::$response->publickey) && !empty(PageContext::$response->privatekey)) {
                PageContext::$response->recaptchaHTML = recaptcha_get_html(PageContext::$response->publickey, $recaptchaError);
            }
        }

        //  PageContext::addStyle("product_details.css");
        //  PageContext::addStyle("userproduct.css");
        $error = "";
        if (isset(PageContext::$request['btnFeedback'])) {

            if (!isset(PageContext::$request['name']) || trim(PageContext::$request['name'] == "")) {
                $error.= "Please enter name ! <br>";
            }
            if (!isset(PageContext::$request['email']) || trim(PageContext::$request['email'] == "")) {
                $error.= "Please enter email ! <br>";
            } else if (Utils::is_valid_email(PageContext::$request['email']) == 0) {
                $error.= "Please enter valid email ! <br>";
            }if
            (!isset(PageContext::$request['feedback']) || trim(PageContext::$request['feedback'] == "")) {
                $error.= "Please enter feedback ! <br>";
            }

            if (PageContext::$response->recaptcha_enable == 'Y') {
                $resp = recaptcha_check_answer(PageContext::$response->privatekey, $_SERVER["REMOTE_ADDR"], PageContext::$request["recaptcha_challenge_field"], PageContext::$request["recaptcha_response_field"]);
                $captchaError = $resp->error;

                if (!empty($captchaError)) {
                    $error.= 'Invalid security code<br />';
                }
            }

            if ($error == '') {
                $data = array('name' => PageContext::$request['name'],
                    'email' => PageContext::$request['email'],
                    'feedback' => PageContext::$request['feedback']);
                User::saveFeedback($data);

                PageContext::$request['name'] = '';
                PageContext::$request['email'] = '';
                PageContext::$request['feedback'] = '';

                $sessionObj->set("contactus_message", "Your feedback submitted successfully !");
                header("location:".ConfigUrl::base()."contactus");
                exit(0);
            }
        }
        
        $message = $sessionObj->get("contactus_message");
        PageContext::$response->message = $message;
        $sessionObj->delete("contactus_message");
        PageContext::$response->error = $error;
        $content = User::loadStaticContent('contactus');
        PageContext::$response->staticContentTitle = $content->cms_title;
        PageContext::$response->staticContent = $content->cms_desc;
    }

// End Function

    public function error() {
        Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        PageContext::addPostAction('cloudtopmenupage');
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");
    }

// End Function
}

?>
