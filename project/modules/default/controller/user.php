<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | This page is for user section management. Login checking , new user registration, user listing etc.                                      |
// | File name : index.php                                                  |
// | PHP version >= 5.2                                                   |
// | Created On	: 	Nov 17 2011                                               |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2010                                      |
// | All rights reserved                                                  |
// +------------------------------------------------------


class ControllerUser extends BaseController {
    /*
		construction function. we can initialize the models here
    */
    public function init() {
        parent::init();
        PageContext::$body_class="home";

        PageContext::addScript("login.js");
        PageContext::addScript("hoverIntent.js");
        //PageContext::addScript("jquery-1.2.6.min.js");
        PageContext::addScript("superfish.js");
        //Tool Tip
        PageContext::addScript("jquery.tooltipster.min.js");
        PageContext::addScript("banner.js");

        User::googleAnalytics();
        PageContext::addPostAction('cloudfooterpage','index');
        User::getFwMetaData(METHOD);

    }

    /*
    function to load the index template
    */
    public function index() {
         if(LibSession::get('userID')){
            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            User::siteAnalytics();
            Logger::info("hello world");
        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("home");
    }

    public function products($page = NULL,$action = NULL,$id = NULL) {
        PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
        PageContext::addStyle("global.css");
        PageContext::addStyle("home.css");
        PageContext::addStyle("product_details.css");
        PageContext::addScript("userlogin.js");
        PageContext::addScript("jquery.form.js");
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addPostAction('cloudfooter');
//        User::siteAnalytics();
        $pageFullContent    = User::getUserProducts();
        $pageFullCount      = count($pageFullContent);
        // PAGINATION AREA
        $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT

        $limit = $pageInfoArr['limit'];
        $this->view->pageInfo = $pageInfoArr;
        $this->view->txtSearch = $txtSearch;
        $this->view->pageContents = User::getUserProducts($txtSearch, $limit);
        Logger::info("hello world");
        $this->view->setLayout("dashboard");

    }

    public function subscriptions() {
        PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
        PageContext::addStyle("global.css");
        PageContext::addStyle("home.css");
        PageContext::addStyle("product_details.css");
        PageContext::addScript("userlogin.js");
        PageContext::addScript("jquery.form.js");
        PageContext::addPostAction('cloudtopmenu');
        PageContext::addPostAction('cloudfooter');
        User::siteAnalytics();
        Logger::info("hello world");
        $this->view->setLayout("home");

    }
    //Profile functionality
    public function profile($activeTab = 'accountdetails') {

        if(LibSession::get('userID')){
            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
         //   PageContext::addStyle("home.css");
          //  PageContext::addStyle("product_details.css");
            PageContext::addScript("userlogin.js");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.metadata.js");
            PageContext::addScript("jquery.validate.js");
         //   PageContext::addPostAction('cloudtopmenu');
         // PageContext::addPostAction('cloudfooter');
              PageContext::addPostAction('cloudtopmenupage','index');
            User::siteAnalytics();
  		Utils::loadActiveTheme();
  			PageContext::$response->themeUrl = Utils::getThemeUrl();
            $this->view->activeTab = $activeTab;
            switch($activeTab) {
                case 'accountdetails':
                    $this->view->accountDetailsStyle ="selected";
                    $this->view->changePasswordStyle ="";
                    $this->view->changeCreditCardStyle ="";
                    if($this->isPost()) {
                        $status = User::updateUserProfile($_POST);
                    }
                    $this->view->userDetails = User::fetchUserProfile();
                    break;
                case 'changepassword':
                    $this->view->accountDetailsStyle ="";
                    $this->view->changePasswordStyle ="selected";
                    $this->view->changeCreditCardStyle ="";
                    if($this->isPost()) {
                        $status = User::updateUserPassword($_POST);
                    }
                    break;
                case 'changecreditcard':
                    $this->view->accountDetailsStyle ="";
                    $this->view->changePasswordStyle ="";
                    $this->view->changeCreditCardStyle ="selected";
                    if($this->isPost()) {
                        $status = User::updateUserCreditCardDetails($_POST);
                    }
                    $this->view->cardDetails = User::fetchUserCreditCardDetails();
                    break;
            }
            if($status) {
                unset ($_POST);
                $this->view->messageFunction = 'successmessage';
            }else {
                $this->view->messageFunction = 'errormessage';
            }
        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");

    }

    public function payments($page = NULL,$action = NULL,$id = NULL) {
        //LibSession::set('userID','1');
        if(LibSession::get('userID')){
            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            //PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.addplaceholder.min.js");
            User::siteAnalytics();
            //PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudfooter');

            //$pageFullContent    = User::userPayments();
           // $pageFullCount      = count($pageFullContent);

            PageContext::addPostAction('cloudtopmenupage','index');
            Utils::loadActiveTheme();
            PageContext::$response->themeUrl = Utils::getThemeUrl();


            // PAGINATION AREA
            $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT
            $limit = $pageInfoArr['limit'];

            if($this->isPost() && $_POST['search']) {
                $txtSearch = trim($_POST['search']);
                $this->view->searchParam = $txtSearch;
                //$searchArr = array(array('field' => 'vPlanDescription', 'value' => $txtSearch), array('field' => 'nAmount', 'value' => $txtSearch), array('field' => 'vTransactionId', 'value' => $txtSearch), array('field' => 'dPaymentDate', 'value' => date('Y-m-d',strtotime($txtSearch))));
            }

            $this->view->pageInfo = $pageInfoArr;
            $this->view->txtSearch = $txtSearch;
            $this->view->pageContents = User::userPayments($txtSearch);

        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");

    }

    /*
    Function to logout the user
    */
    public function logout() {
        session_destroy();
        session_unset($_SESSION['user']);

        header("location:".ConfigUrl::base());
        $this->view->disableView();
        exit();
    }

    public function dashboard($statusFlag='') {
        //LibSession::set('userID','1');
        if(LibSession::get('userID')){
            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
          //  PageContext::addStyle("home.css");
           // PageContext::addStyle("product_details.css");
           // PageContext::addScript("jquery.form.js");
           // PageContext::addScript("jquery.metadata.js");
           // PageContext::addScript("jquery.validate.js");
            PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudtopmenupage','index');

            //PageContext::addPostAction('cloudfooter');

           // PageContext::addPostAction('cloudfooterpage','index');


            PageContext::$response->themeUrl = Utils::getThemeUrl();

            User::siteAnalytics();
            Utils::loadActiveTheme();
            PageContext::$response->freeTrials = User::fetchFreeTrialsOfLoggedInUser();
            PageContext::$response->subscriptions = User::fetchSubscriptionsOfLoggedInUser();
            //echopre(PageContext::$response->subscriptions);
        }else{
            $this->redirect('index');
        }
        if($statusFlag==1)
        {
            PageContext::$response->success_message = "Successfully removed the account details!";
            PageContext::addPostAction('successmessage');
            $this->view->messageFunction = 'successmessage';
        }
        elseif($statusFlag==2)
        {
            PageContext::$response->error_message = "Account termination failed, please retry after some time";
            PageContext::addPostAction('errormessage');
            $this->view->messageFunction = 'errormessage';
        }
        elseif($statusFlag==3)
        {
            PageContext::$response->success_message = "Successfully Susbscribed Inventory Source Plugin!";
            PageContext::addPostAction('successmessage');
            $this->view->messageFunction = 'successmessage';
        }elseif($statusFlag==4)
        {
            PageContext::$response->success_message = "Successfully Un Susbscribed Inventory Source Plugin!";
            PageContext::addPostAction('successmessage');
            $this->view->messageFunction = 'successmessage';
        }elseif($statusFlag==5)
        {
            PageContext::$response->success_message = "Successfully Updated Card Details!";
            PageContext::addPostAction('successmessage');
            $this->view->messageFunction = 'successmessage';
        }
        $sessionObj = new LibSession();


       $sessionObj->set('plan_id','');

       $sessionObj->set('template_id','');
        
        
        $this->view->setLayout("dashboard");
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

    public function successmessage() {

    }

    public function errormessage() {

    }

    
    public function enable_inventory($productLookUpId,$status)
    {
       if(LibSession::get('userID')){

            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            //PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.addplaceholder.min.js");
            User::siteAnalytics();
            //PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudfooter');

            //$pageFullContent    = User::userPayments();
           // $pageFullCount      = count($pageFullContent);
            if($_POST['btnConfirm'])
            {
                $ProductDetails = User::getProductDetails($productLookUpId);
                $userDetails    = User::getUserAllDetails(LibSession::get('userID'));
                
            $inventory_source_amount = User::getallSettings('inventory_source_amount');
            $inventory_source_plan_duration = User::getallSettings('inventory_source_plan_duration');
                
                $storeHost = Admincomponents::getStoreHost($productLookUpId);
                
                $bluedogdetails = unserialize($ProductDetails->bluedogdetails);
                
                $payArr1 = array();
                
                $payArr1['desc'] = $storeHost.'-'."Inventory Source Plugin Activation";
                $payArr1['amount'] = $inventory_source_amount;
                $payArr1['email'] = $userDetails->vEmail;
                $payDataArr = array_merge($payArr1, $bluedogdetails);
                $payArr=Paymenthelper::doBlueDogPayment($payDataArr); // Authorize.net Payment Gateway
                       // echopre($payArr);
                //echopre1($payDataArr);
                
                if(!empty($payArr)) {
                        // $payArr['paymentSuccessful'];
                        // $payArr['paymentError'];
                        // $payArr['transactionId'];

                        if($payArr['transactionId']!='' && $payArr['paymentSuccessful']) {
                            $vSubscriptionType = 'PAID';
                            $paymentDate = date('Y-m-d H:i:s');
                            $paymentMethod = 'CC';
                            $paymentFlag = true;
                            $transactionID = $payArr['transactionId'];
                            
                            
                            
                           $nInvsPid =  User::addInventorySourcePlanEntry($productLookUpId,$inventory_source_plan_duration);
                            
                           $nInvsInvId =  User::addInventorySourcePlanInventory($nInvsPid,$inventory_source_plan_duration,$inventory_source_amount,$transactionID);
                            
                            $description = 'Inventory Subscription'.'###'.$payArr['email'];;
                            User::storePaymentsEntry($payArr['amount'], 'bluedog', $payArr['transactionId'],$description);
                           
                           
                           
                           $card_number = $bluedogdetails['customer_data']['payment_method']['card']['last_four'];
                           
                           
                           
                           $userarray = array();
                           $userarray['MEMBER_NAME'] = $userDetails->vFirstName.' '.$userDetails->vLastName;
                           $userarray['STORENAME'] = '<b>'.$storeHost.'</b> ';
                           $userarray['EMAIL'] = $userDetails->vEmail;
                           $userarray['CARD'] = $card_number;
                           $userarray['SITE_NAME'] = SITE_NAME;
                           $userarray['HERE'] =  "<a href='".SITE_URL."'>here</a>";
                           $userarray['AMOUNT'] = '$'.$inventory_source_amount;
                           $userarray['EXPIRY_DATE'] = date('m/d/Y', strtotime(date('Y-m-d') . " +$inventory_source_plan_duration day"));
                           
                           $storeArray = array();
                           $storeArray['inventory_plugin_status'] = 1;
            
             
             $url = 'http://' . $storeHost . '/Settings/updatesystemsettings';


            $post = ['settings_data' => json_encode($storeArray)];


            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // execute!
            $response = curl_exec($ch);

            // close the connection, release resources used
            curl_close($ch);
                           
                           
                           User::sendInventorySourceMail($userarray);
                           
                           
                           
                           //echo $nInvsInvId; 
                            
                            
                            $this->redirect('user/dashboard/3');

                        } else {

                            $errorMsg .= '<br/>'.'Payment Failure -'.$payArr['paymentError'];
                            
PageContext::$response->error_message = $errorMsg;
            PageContext::addPostAction('errormessage');
            $this->view->messageFunction = 'errormessage';
                            
                        }
                    }else{
                    $errorMsg .= '<br/>'.'Incomplete User Credit Card Credentials';
                    
                    PageContext::$response->error_message = $errorMsg;
            PageContext::addPostAction('errormessage');
            $this->view->messageFunction = 'errormessage';
                }
                
                
            }

            PageContext::addPostAction('cloudtopmenupage','index');
            Utils::loadActiveTheme();
            PageContext::$response->themeUrl = Utils::getThemeUrl();
            $ProductDetails = User::getProductDetails($productLookUpId);
            $bluedog = unserialize($ProductDetails->bluedogdetails);
            //echopre($bluedog);
            PageContext::$response->card_number = $bluedog['customer_data']['payment_method']['card']['masked_card'];
            PageContext::$response->expiration_date = $bluedog['customer_data']['payment_method']['card']['expiration_date'];
            PageContext::$response->inventory_source_amount = User::getallSettings('inventory_source_amount');
            PageContext::$response->inventory_source_plan_duration = User::getallSettings('inventory_source_plan_duration');
                    //echopre(PageContext::$response->inventory_source_plan_duration);
            
            
        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");
        
    }
    
      public function disable_inventory($productLookUpId,$status)
    {
       if(LibSession::get('userID')){

            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            //PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.addplaceholder.min.js");
            User::siteAnalytics();
            //PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudfooter');

            //$pageFullContent    = User::userPayments();
           // $pageFullCount      = count($pageFullContent);
            
            
            $ProductDetails = User::getProductDetails($productLookUpId);
                $userDetails    = User::getUserAllDetails(LibSession::get('userID'));
                
           PageContext::$response->InvPlanDetails = User::getInventoryPlanDetails($productLookUpId);
               //echopre($InvPlanDetails); 
                $storeHost = Admincomponents::getStoreHost($productLookUpId);
            
            
            if($_POST['btnConfirm'])
            {
                User::disableInventoryService($productLookUpId);
                $this->redirect('user/dashboard/4');
                       // echopre($payArr);
                //echopre1($payDataArr);
                
              
                
                
            }

            PageContext::addPostAction('cloudtopmenupage','index');
            Utils::loadActiveTheme();
            PageContext::$response->themeUrl = Utils::getThemeUrl();
            $ProductDetails = User::getProductDetails($productLookUpId);
            $bluedog = unserialize($ProductDetails->bluedogdetails);
            //echopre($bluedog);
            PageContext::$response->card_number = $bluedog['customer_data']['payment_method']['card']['masked_card'];
            PageContext::$response->expiration_date = $bluedog['customer_data']['payment_method']['card']['expiration_date'];
            PageContext::$response->inventory_source_amount = User::getallSettings('inventory_source_amount');
            PageContext::$response->inventory_source_plan_duration = User::getallSettings('inventory_source_plan_duration');
                    //echopre(PageContext::$response->inventory_source_plan_duration);
            
            
        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");
        
    }

    public function unjoin($productLookUpId)
    {
        $statusArray = Cronhelper::doStoreTermination($productLookUpId);
        $this->view->disableView();

        if($statusArray)
        {

            $this->redirect('user/dashboard/1/');
        }
        else
        {
            $this->redirect('user/dashboard/2/');
        }
        die;
    }

    
    public function edit_card($productLookUpId)
    {

        require_once('project/lib/stripe/init.php');
        
        if(LibSession::get('userID')){

            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            //PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.addplaceholder.min.js");
            User::siteAnalytics();
            //PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudfooter');

            //$pageFullContent    = User::userPayments();
           // $pageFullCount      = count($pageFullContent);
            
            
            $ProductDetails = User::getProductDetails($productLookUpId);
            
            $bluedoddetails = unserialize($ProductDetails->bluedogdetails);

            $payment_type=$bluedoddetails['payment_gateway'];

           

            //if stripe hide the card details section

            PageContext::$response->payment_type=$payment_type;
            PageContext::$response->stripetoken=$stripetoken;
            PageContext::$response->stripesettings=$stripesettings;
            
                    
              PageContext::$response->billing =   $bluedoddetails['customer_data']['billing_address']; 
              PageContext::$response->card =   $bluedoddetails['customer_data']['payment_method']['card']; 
                    
                $userDetails    = User::getUserAllDetails(LibSession::get('userID'));
                
            PageContext::$response->InvPlanDetails = User::getInventoryPlanDetails($productLookUpId);
               //echopre($InvPlanDetails); 
                $storeHost = Admincomponents::getStoreHost($productLookUpId);
            
            
            if($_POST['btnProfile'])
            {
               //echopre($_POST);exit;
                $error = 0;
                
                
                
                    $cardArray = array();
                    $cardArray['description'] =  $_POST['first_name'].' '.$_POST['vFirstName'].' MakereadyArms Customer';
                    $cardArray['payment_method']['card']['card_number'] = $_POST['vNumber'];
                    $cardArray['payment_method']['card']['expiration_date'] = $_POST['vMonth'].'/'.$_POST['vYear'];
                    $cardArray['billing_address']['first_name'] = $_POST['vFirstName'];
                    $cardArray['billing_address']['last_name'] = $_POST['vLastName'];
                    $cardArray['billing_address']['company'] = $_POST['vFirstName'].' '.$_POST['vLastName'];
                    $cardArray['billing_address']['email'] = $_POST['vEmail'];
                    $cardArray['billing_address']['address_line_1'] = $_POST['vAddress'];
                    $cardArray['billing_address']['address_line_2'] = '';
                    $cardArray['billing_address']['city'] = $_POST['vCity'];
                    $cardArray['billing_address']['state'] = $_POST['vState'];
                    $cardArray['billing_address']['postal_code'] = $_POST['vZipcode'];
                    $cardArray['billing_address']['country'] = $_POST['vCountry'];
                    $cardArray['billing_address']['phone'] = $_POST['vPhone'];
                    $cardArray['billing_address']['fax'] = $_POST['vFax'];
                    
                    $cardArray['shipping_address']['first_name'] = $_POST['vFirstName'];
                    $cardArray['shipping_address']['last_name'] = $_POST['vLastName'];
                    $cardArray['shipping_address']['company'] = $_POST['vFirstName'].' '.$_POST['vLastName'];
                    $cardArray['shipping_address']['email'] = $_POST['vEmail'];
                    $cardArray['shipping_address']['address_line_1'] = $_POST['vAddress'];
                    $cardArray['shipping_address']['address_line_2'] = '';
                    $cardArray['shipping_address']['city'] = $_POST['vCity'];
                    $cardArray['shipping_address']['state'] = $_POST['vState'];
                    $cardArray['shipping_address']['postal_code'] = $_POST['vZipcode'];
                    $cardArray['shipping_address']['country'] = $_POST['vCountry'];
                    $cardArray['shipping_address']['phone'] = $_POST['vPhone'];
                    $cardArray['shipping_address']['fax'] = $_POST['vFax'];
                    
                    
                    
                    //$res = User::SaveBluedogCardAddressDetails($cardArray,$bluedoddetails['customer_id'],$bluedoddetails['billing_address_id'],$bluedoddetails['shipping_address_id']);

                    //get payment type

                    

                    //echopre($bluedoddetails);exit;


                    

                     if($payment_type=='stripe')
                    {

                        //check validation


                        

                       
                            //check test mode
                        $testmode=User::getallSettings('stripe_test_mode');

                        $payment_id=$bluedoddetails['payment_method_id'];

                        $payment_details=Payments::getpaymentData($payment_id);
                        $webhookdata=unserialize($payment_details[0]->webhookdata);

                        

                        



                        $stripesettings = Payments::getStripeSettings($testmode);

                        

                        //stripe functions

                        $secretKey=$stripesettings['SecretKey'];
                        $email=$bluedoddetails['customer_data']['billing_address']['email'];
                        $planname='Gostores';
                        $planprice=$payment_details[0]->nAmount;




                       

                        $updatedpayment=User::UpdateStripemethod($secretKey,$webhookdata['customer_id'],$webhookdata['payment_method_id'],$_POST);
                        $updatedpayment=json_decode($updatedpayment);

                        

                        if($updatedpayment->success==1)
                        {

$productlookup=Payments::getProductLookup($productLookUpId);

$bluedoddetails = unserialize($productlookup[0]->bluedogdetails);


$bluedoddetails['payment_intent_id']=$bluedoddetails['payment_method_id'];
$bluedoddetails['payment_method_id']=$updatedpayment->data;
$bluedoddetails['customer_data']['billing_address']['first_name'] = $_POST['vFirstName'];
$bluedoddetails['customer_data']['billing_address']['last_name'] = $_POST['vLastName'];
$bluedoddetails['customer_data']['billing_address']['address_line_1'] = $_POST['vAddress'];
$bluedoddetails['customer_data']['billing_address']['city'] = $_POST['vCity'];
$bluedoddetails['customer_data']['billing_address']['state'] = $_POST['vState'];
$bluedoddetails['customer_data']['billing_address']['postal_code'] = $_POST['vZipcode'];
$bluedoddetails['customer_data']['billing_address']['country'] = $_POST['vCountry'];
$bluedoddetails['customer_data']['billing_address']['email'] = $_POST['vEmail'];
$bluedoddetails['customer_data']['billing_address']['phone'] = $_POST['vPhone'];
           

$bluedoddetails['customer_data']['shipping_address']['first_name'] = $_POST['vFirstName'];
$bluedoddetails['customer_data']['shipping_address']['last_name'] = $_POST['vLastName'];
$bluedoddetails['customer_data']['shipping_address']['address_line_1'] = $_POST['vAddress'];
$bluedoddetails['customer_data']['shipping_address']['city'] = $_POST['vCity'];
$bluedoddetails['customer_data']['shipping_address']['state'] = $_POST['vState'];
$bluedoddetails['customer_data']['shipping_address']['postal_code'] = $_POST['vZipcode'];
$bluedoddetails['customer_data']['shipping_address']['country'] = $_POST['vCountry'];
$bluedoddetails['customer_data']['shipping_address']['email'] = $_POST['vEmail'];
$bluedoddetails['customer_data']['shipping_address']['phone'] = $_POST['vPhone'];


           User::UpdateProductLookUp($bluedoddetails,$productLookUpId);

                        }

                        else
                        {

                            $error = 1;
                      $message = $errormsg;
                      PageContext::$response->error_message = $updatedpayment->data;
                      PageContext::addPostAction('errormessage');
                      $this->view->messageFunction = 'errormessage';
                        }

                        //get productlookup

                        

                        //update bluedog details

                        



                        

                        // }

                        

                        


                    }

                    else
                    {
 $res = User::addNewBluedogCard($cardArray);
                    
                    if($res['status']=='failed')
                    {

                        
                        
                        $errormsg.= preg_replace('/0/',' ', $res['msg']);
                      $error = 1;
                      $message = $errormsg;
                      PageContext::$response->error_message = $message;
                      PageContext::addPostAction('errormessage');
                      $this->view->messageFunction = 'errormessage';
                    }else{
                        User::updateBlueDogDetails($res,$productLookUpId);
                        
                    }

                    }

                    
              
                   
                    
              
                
               
            


//User::disableInventoryService($productLookUpId);
                
                
                
                if($error==0){
                $this->redirect('user/dashboard/5');
                }
                       // echopre($payArr);
                //echopre1($payDataArr);
                
              
                
                
            }

            PageContext::addPostAction('cloudtopmenupage','index');
            Utils::loadActiveTheme();
            PageContext::$response->themeUrl = Utils::getThemeUrl();
            $ProductDetails = User::getProductDetails($productLookUpId);
            $bluedog = unserialize($ProductDetails->bluedogdetails);
            //echopre($bluedog);
            PageContext::$response->card_number = $bluedog['customer_data']['payment_method']['card']['masked_card'];
            PageContext::$response->expiration_date = $bluedog['customer_data']['payment_method']['card']['expiration_date'];
            PageContext::$response->inventory_source_amount = User::getallSettings('inventory_source_amount');
            PageContext::$response->inventory_source_plan_duration = User::getallSettings('inventory_source_plan_duration');
                    //echopre(PageContext::$response->inventory_source_plan_duration);
            
            
        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");
    }
    
    

    // Function for Settlements

    public function settlements($page = NULL,$action = NULL,$id = NULL) {

        if(LibSession::get('userID')){

            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            //PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.addplaceholder.min.js");
            User::siteAnalytics();
            //PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudfooter');

            //$pageFullContent    = User::userPayments();
           // $pageFullCount      = count($pageFullContent);

            PageContext::addPostAction('cloudtopmenupage','index');
            Utils::loadActiveTheme();
            PageContext::$response->themeUrl = Utils::getThemeUrl();


            // PAGINATION AREA
            $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT
            $limit = $pageInfoArr['limit'];

            if($this->isPost() && $_POST['search']) {
                $txtSearch = trim($_POST['search']);
                $this->view->searchParam = $txtSearch;
                //$searchArr = array(array('field' => 'vPlanDescription', 'value' => $txtSearch), array('field' => 'nAmount', 'value' => $txtSearch), array('field' => 'vTransactionId', 'value' => $txtSearch), array('field' => 'dPaymentDate', 'value' => date('Y-m-d',strtotime($txtSearch))));
            }

            $addFormDisplay = false;
            /*if(isset($_REQUEST['add_request'])){
                $addFormDisplay = true;
            }*/

            $this->view->pageInfo = $pageInfoArr;
            $this->view->addFormDisplay = $addFormDisplay;
            $this->view->pageContents = User::userSettlements();
        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");

    }
    public function addrequest($page = NULL,$action = NULL,$id = NULL) {
            $objLib = new LibSession;
            $userId = $objLib->get('userID');
            $editFormDisplay=1;
        if(LibSession::get('userID')){
            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            //PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.addplaceholder.min.js");
            PageContext::addScript("addrequest.js");
            User::siteAnalytics();
            //PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudfooter');

            PageContext::addPostAction('cloudtopmenupage','index');
            Utils::loadActiveTheme();
            PageContext::$response->themeUrl = Utils::getThemeUrl();

            // PAGINATION AREA
            $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT
            $limit = $pageInfoArr['limit'];
            if($_GET['id']!='')
            {
                $details = User::getSettlements(trim($_GET['id']));

               if($details->eStatus=="Pending")
                {
                    $this->view->pageInfo = $details;
                }
                else
                {
                    PageContext::$response->message = "This request cannot be edited.";
                    $editFormDisplay=0;
                }
            }

            if($this->isPost()) {
               $requestdata['nId']=$this->post('nId');
               $requestdata['nUId']=$userId;
               $requestdata['nRequestedAmount']=$this->post('nRequestedAmount');
               $requestdata['tUserComments']=$this->post('tUserComments');
                if (User::saveSettlements($requestdata))
                     $this->redirect('user/settlements');
                else
                     PageContext::$response->message = "Internal Errors";

            }

           $this->view->editFormDisplay = $editFormDisplay;


        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");

    }

    public function doUserLogInForCMS($userid=''){
        $this->view->disableView();
        $session = new LibSession();
        $status = 0;
         if($session->get('admin_logged_in','cms') == 1 ){

            $userData = Admincomponents::getUserdetails($userid);
            $userName = $userData->vEmail;
            $password = $userData->vPassword;
            $status = User::validateLogin($userName,$password);

        }
        if($status==1){
          Admin::setSupportDeskSessions($userName);
          $this->redirect('user/dashboard');
        } else {
            $this->redirect('index');
        }

        die();
    }
     public function invoiceDetails($page = NULL,$action = NULL,$id = NULL) {
            $objLib = new LibSession;
            $userId = $objLib->get('userID');
            $editFormDisplay=1;
        if(LibSession::get('userID')){
            PageContext::addStyle("http://fonts.googleapis.com/css?family=Lato");
            PageContext::addStyle("global.css");
            //PageContext::addStyle("home.css");
            PageContext::addStyle("product_details.css");
            PageContext::addScript("jquery.form.js");
            PageContext::addScript("jquery.addplaceholder.min.js");
            PageContext::addScript("addrequest.js");
            User::siteAnalytics();
            //PageContext::addPostAction('cloudtopmenu');
            PageContext::addPostAction('cloudfooter');

            PageContext::addPostAction('cloudtopmenupage','index');
            Utils::loadActiveTheme();
            PageContext::$response->themeUrl = Utils::getThemeUrl();

            // PAGINATION AREA
            $pageInfoArr = Utils::pageInfo($page, $pageFullCount, PAGE_LIST_COUNT); //PAGE_LIST_COUNT
            $limit = $pageInfoArr['limit'];
            if($_GET['id']!='')
            {
                if($_GET['vtype']=='invoice'){
                   //$this->view->pageContents =  User::userInvoiceDetails(trim($_GET['id']));
                   $this->view->dataArr = User::getInvoiceDetails(trim($_GET['id']));
                   $this->view->dataDomArr = User::getInvoiceDomainDetails(trim($_GET['id']));
                }else {
                    $this->view->dataArr = User::getInvoiceTemplateDetails(trim($_GET['id']));
                }

            }


        }else{
            $this->redirect('index');
        }
        $this->view->setLayout("dashboard");

    }



}

?>
