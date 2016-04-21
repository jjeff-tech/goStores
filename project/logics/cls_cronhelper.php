<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : cls_cronhelper.php                                                |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: Meena Susan Joseph<meena.s@armiasystems.com>              	  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems © 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class Cronhelper {

    public static $dbObj = NULL;

    public static function generateBillInitial($attempt = 0) {

        /* Sample Invoice Generation
        Admincomponents::generateInvoice(array('nUId' => 59, 'nPId' => 1, 'services' => array('60','59','57'), 'domainService' => array(), 'couponNo' => NULL, 'terms' => NULL, 'notes' =>
        NULL, 'paymentstatus' => 'paid', 'vMethod' => 'cc', 'vTxnId' => 'ATXS3555'));
        // Sample Invoice Generation End */

        Cronhelper::$dbObj = new Db();

        $billArr = $storeGroupArr = $invGroupArr = $invDomainGroupArr = array();

        if($attempt == 1){
            $billArr = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus', 'cronAttempt'), array(array('field' => 'vExistDomainFlag', 'value' => '0', 'condition' => '='),array('field' => 'vDelStatus', 'value' => '1', 'condition' => '!='), array('field' => '(IFNULL( cronAttempt, 0 )', 'value' => '1 AND 3)','condition' => ' BETWEEN ','inputQuotes' => 'N')));
        }else{
            $billArr = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus', 'cronAttempt'), array(array('field' => 'vExistDomainFlag', 'value' => '0', 'condition' => '='),array('field' => 'vDelStatus', 'value' => '1', 'condition' => '!='), array('field' => 'dDateNextBill', 'value' => 'CASE WHEN (vDomain = 1) THEN CURDATE() ELSE CURDATE() END','inputQuotes' => 'N','condition' => '<='), array('field' => 'IFNULL( cronAttempt, 0 )', 'value' => '1','condition' => ' < ','inputQuotes' => 'N')));
        }
        echopre($billArr);
        //$billArr = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus', 'cronAttempt'), array(array('field' => 'vDelStatus', 'value' => '1', 'condition' => '!='), array('field' => 'dDateNextBill', 'value' => 'CASE WHEN (vDomain = 1) THEN (CURDATE() - INTERVAL 7 DAY) ELSE CURDATE() END','inputQuotes' => 'N','condition' => '<='), array('field' => 'IFNULL( cronAttempt, 0 )', 'value' => '1','condition' => ' < ','inputQuotes' => 'N')));
        
        /********************** All set of plans and domains  *******************/
        /* echo "<br/>===========================================================";
        echo "<br/>All set of plans and domains which expire today";
        echopre($billArr);
        Logger::info($billArr);
        echo "<br/>==========================================================="; */
        /********************** All set of plans and domains  *******************/

        /********************** Group all the users store account  *******************/         
        $storeGroupArr = Cronhelper::groupStoresForBill($billArr);
        /* echo "<br/>===========================================================";
        echo "<br/>Stores array";
        echopre($storeGroupArr);
        echo "<br/>==========================================================="; */
        /********************** Group all the users store account  *******************/
        
        /********************** Group of Service Plan Invoices *******************/
        $invGroupArr=Cronhelper::groupInvoices($billArr);  
        /* echo "<br/>===========================================================";
        echo "<br/>Service Plan Renewal Entries";
        echopre($invGroupArr);
        Logger::info($invGroupArr);        
        echo "<br/>==========================================================="; */
        /********************** Group of Service Plan Invoices *******************/
        
        /********************** Group of DomainRenewal Invoices *******************/        
        $invDomainGroupArr=Cronhelper::groupInvoicesForDomainRenewal($billArr);
        /* echo "<br/>===========================================================";
        echo "<br/>Domain Renewal Entries";
        echopre($invDomainGroupArr);
        echo "<br/>==========================================================="; */
        /********************** Group of DomainRenewal Invoices *******************/        

        /*********** Group Cron Attempt Counter for the today expiring store ******/
        if($attempt == 1){           
            $invCronArr = Cronhelper::groupCronAttempt($billArr);
            /*echo "<br/>===========================================================";
            echo "<br/>Cron attempt data";
            Logger::info($invCronArr);
            echo "<br/>==========================================================="; */
        }
        /*********** Group Cron Attempt Counter for the today expiring store ******/

        $domainRenewalPeriod    = 1; // Domain renewal is for 1 year
        $domainRenewalInterval  = 'Y';
        $contactMailID          = COMPANY_EMAIL;
        $contactMailID          = (empty($contactMailID)) ? ADMIN_EMAILS : $contactMailID;

        $cronUpdatedFlag = 0;
        /***************************** Generate bill for each store ************************/
        foreach($storeGroupArr as $productLookUpID => $userID){   
            /*echo "<br/>===========================================================";
            echo "<br/>Store ID: ".$productLookUpID."<-->".$userID;
            echo "<br/>==========================================================="; */ 

            $totalAmount    = 0;
            $discount       = 0;
            $grandTotal     = 0;
            $mailMsg        = $errorMsg = "";          
            $paymentFlag    = 0;
            $paymentMethod  = $paymentDate = $transactionID = "";
       
            $storeHost      = "";
            $storeHost      = Admincomponents::getStoreHost($productLookUpID);           

            $dataDomainArr          = array();
            $dataServiceArr         = array();
            $dataBillingArr         = array();
            $dataDomainBillingArr   = array();

            /***************************** Domain Renewal Failure *****************************/
            $domainRenewalNotificationFlag  = false;
            $domainRenewalNotificationMsg   = NULL;      
            /***************************** Domain Renewal Failure *****************************/    

            /****************************  Domain Billing Area Starts   **************************/
            //echopre($invDomainGroupArr[$productLookUpID]);            
            if(!empty($invDomainGroupArr[$productLookUpID])){                
                foreach($invDomainGroupArr[$productLookUpID] as $invoiceID => $servicesArr){ 
                    /* echo "<br/>===========================================================";
                    echo "<br/>Invoice ID: ".$invoiceID;
                    echo "<br/>===========================================================";  */  

                    $invDescription = $invTerms = $invNotes = NULL;                
                    foreach($servicesArr as $billMainID => $productLookUpID){       
                        /*echo "<br/>===========================================================";
                        echo "<br/>Billing Main ID: ".$billMainID." <--> ".$productLookUpID;
                        echo "<br/>==========================================================="; */    

                        /************** Fetch data from BillingMain Table **************/
                        $billItem = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus'), array(array('field' => 'nBmId', 'value' => $billMainID)));
                        Logger::info($billItem);               
                        /************** Fetch data from BillingMain Table **************/
                        
                        /******************* Invoice Domain Detail Item ****************/
                        $invDtItem = Admincomponents::getInvoiceDomainDetails($invoiceID);
                        Logger::info($invDtItem);                  
                        /*echo "<br/>===========================================================";
                        echo "<br/>Domain Invoice Details";
                        echopre($invDtItem);
                        echo "<br/>==========================================================="; */
                        /******************* Invoice Domain Detail Item ****************/                             
         
                        /****************** Service Summary  *******************/                  
                        $invDescription = $storeHost.' - Domain Renewal';   //Service Summary as payment title                                                        
                        $invTerms       = $invDtItem[0]->vTerms; // Invoice Terms
                        $invNotes       = $invDtItem[0]->vNotes; // Invoice Notes
                        $tldPrice       = User::gettldprice('');                        
                        /****************** Service Summary  *******************/

                        /****************** Start of valid Service *******************/
                        if(!empty($billItem)){                            
                            $bStartDate  = $bStopDate = $bNextDate = $planType = $amountNext= $serDiscount = NULL;
                            $planPrice   = 0;    
                            if(trim($tldPrice) <> "" && trim($tldPrice) > 0){
                                $planPrice  = trim($tldPrice);
                            }else{                    
                                $planPrice  = $invDtItem[0]->nRate; //Basic rate as per service plan table
                            }
                            $amountNext  = $planPrice * $domainRenewalPeriod; // Domain Renewal Price for next year                                              
                            $totalAmount += $amountNext; // Domain registration cost

                            $addYear    = "";
                            $addYear    = " +".$domainRenewalPeriod." years";
                            $bStartDate = date("Y-m-d");
                            $bStopDate  = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addYear);
                            $bStopDate  = date("Y-m-d", $bStopDate);
                            //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                            //$bNextDate = date("Y-m-d", $bNextDate);
                            $bNextDate  = $bStopDate;
                            $planType   = 'recurring';                                               
              
                            $dataDomainArr = array(
                                                'nUId'              => $userID,
                                                'nPLId'             => $productLookUpID,
                                                'vDescription'      => $invDescription,
                                                'nAmount'           => $amountNext,
                                                'nAmtNext'          => $amountNext,
                                                'vType'             => $planType,
                                                'nRate'             => $amountNext,
                                                'vBillingInterval'  => $domainRenewalInterval,
                                                'nBillingDuration'  => $domainRenewalPeriod,
                                                'nDiscount'         => NULL,
                                                'dDateStart'        => $bStartDate,
                                                'dDateStop'         => $bStopDate,
                                                'dDateNextBill'     => $bNextDate,
                                                'nPlanStatus'       => NULL
                                            );                 

                            /****************** Billing Main Data ******************/
                            if($planType == 'recurring'){
                                // Recurring
                                //nSCatId - will be filled only for domain registration case
                                $dataDomainBillingArr[] = array(
                                                            'nBmId'             => $billMainID,
                                                            'nUId'              => $userID,
                                                            'nServiceId'        => $serviceID,
                                                            'vDomain'           => $invDtItem[0]->nDomainStatus,
                                                            'vSpecials'         => NULL,
                                                            'nSpecialCost'      => NULL,
                                                            'nDiscount'         => NULL,
                                                            'nAmount'           => $planPrice,
                                                            'vType'             => $planType,
                                                            'vBillingInterval'  => $domainRenewalInterval,
                                                            'dDateStart'        => $bStartDate,
                                                            'dDateStop'         => $bStopDate,
                                                            'dDateNextBill'     => $bNextDate,
                                                            'dDatePurchase'     => $bStartDate,
                                                            'vDelStatus'        => '0'
                                                        );
                                
                            } 
                            /****************** Billing Main Data ******************/
                        } 
                        /****************** End Valid Service *******************/
                    } // End Services Loop
                        
                } // end bill loop for - Domain renewal plan
            }
            
            /*echo "<br/>===========================================================";
            echo "<br/>Domain Data Array";
            echopre($dataDomainArr);
            echo "<br/>Domain Billing Array";
            echopre($dataDomainBillingArr);       
            echo "<br/>==========================================================="; */    
            /****************************  Domain Billing Area End   **************************/

            /**************************** Service Plan Billing Area Starts **************************/
            if(!empty($invGroupArr[$productLookUpID])){
                $invDescription = $invTerms = $invNotes = NULL;                           
                foreach($invGroupArr[$productLookUpID] as $invoiceID => $servicesArr){// Bill Loop Start

                $serCnt = 0;
                foreach($servicesArr as $billMainID => $serviceID) {
                    ++$serCnt;

                    // Service Info / Checks whether current service plan is active or not
                    $serItemArr = Cronhelper::serviceInfo($serviceID, 'purchase', $invoiceID);
                    Logger::info($serItemArr);                   
                    // Service Info

                    // Billing Item
                    $billItem = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus'), array(array('field' => 'nBmId', 'value' => $billMainID)));
                    Logger::info($billItem);
                    // Billing Item

                    //echopre1($billItem);

                    // Specials
                    $specials       = NULL;
                    $specialsBill   = NULL;
                    $specialsArr    = array();
                    if(isset($billItem[0]->vSpecials) && !empty($billItem[0]->vSpecials)) { // vSpecials
                        
                        $specialsArr = json_decode($billItem[0]->vSpecials); // Specials
                    }
                   //echopre1($specialsArr);

                    // Invoice Detail Item
                    $invDtItem = Admincomponents::getInvoiceDetails($invoiceID, array(array('field' => 'ip.nServiceId', value => $serviceID)));
                    Logger::info($invDtItem);
                    //echopre1($invDtItem);
                    // Invoice Detail Item

                    // pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,

                    // Service Summary as payment title                                       
                    $invDescription = ($serCnt==1) ? $storeHost.' - '.$invDtItem[0]->vServiceName : ' - '.$invDtItem[0]->vServiceName;
                                       
                    $invTerms = $invDtItem[0]->vTerms;
                    $invNotes = $invDtItem[0]->vNotes;

                    /*echo "<br/>===========================================================";
                    echo "<br/>Existing Service Plan Details";
                    echopre($serItemArr);
                    echo "<br/>==========================================================="; */

                    if(!empty($serItemArr)) {
                        $totalAmount += $serItemArr['price'];
                        $productSpanArr['productBillingInterval']; // productBillingInterval
                        $productSpanArr['productBillingDuration']; // productBillingDuration
                        $serItemArr['vBillingInterval'];
                        $serItemArr['nBillingDuration'];
                        $bStartDate = $bStopDate = $bNextDate = $planType = $amountNext= $serDiscount = NULL;
                        $planPrice  = $serItemArr['price'];
                        switch($serItemArr['vBillingInterval']){
                            case 'M':
                            // recurring
                                $addDays = NULL;
                                if($serItemArr['nBillingDuration'] ==1) {
                                    $addDays = " +".$serItemArr['nBillingDuration']." day";
                                }else if($serItemArr['nBillingDuration'] > 1) {
                                    $addDays = " +".$serItemArr['nBillingDuration']." days";
                                }                                
                                //echo "<br/>Days interval -> ".$addDays;
                                
                                $bStartDate = date("Y-m-d");
                                $bStopDate  = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addDays);
                                $bStopDate  = date("Y-m-d", $bStopDate);
                                //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                                //$bNextDate = date("Y-m-d", $bNextDate);
                                $bNextDate  = $bStopDate;
                                $planType   = 'recurring';
                                $amountNext = $serItemArr['price']; 
                                //echopre1($amountNext);  
                                break;
                            case 'Y':
                                $addYear = NULL;
                                $addYear = " +".$serItemArr['nBillingDuration']." years";
                                $bStartDate = date("Y-m-d");
                                $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addYear);
                                $bStopDate = date("Y-m-d", $bStopDate);
                                //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                                //$bNextDate = date("Y-m-d", $bNextDate);
                                $bNextDate = $bStopDate;
                                $planType = 'recurring';
                                $amountNext = $serItemArr['price'];
                                break;
                            case 'L':
                            // one-time;
                                $planType = 'one time';
                                break;
                        } 

                        //Specials Fee
                        if(!empty($specialsArr)) {
                            $specialCost = NULL;
                            $specialCostBill = NULL;
                            $specialsBillArr = array();
                            foreach($specialsArr as $itemSpecial) {

                                switch($itemSpecial->capture) {
                                    case 'recurring':
                                        $specialCostBill +=$itemSpecial->cost;
                                        $specialsBillArr[]=$itemSpecial;
                                        break;
                                    case 'one-time':
                                    //  only one time capture. will never have an entry for next bill
                                        break;
                                }
                                $specialCost += $itemSpecial->cost;

                            }
                            $specials = json_encode($specialsArr);
                            if(count($specialsBillArr) > 0) {
                                $specialsBill = json_encode($specialsBillArr);
                            }
                            $totalAmount += $specialCost;                            
                        }
                        // End Specials

                        $dataServiceArr[] = array(
                                'nUId'              => $userID,
                                'nServiceId'        => $serviceID,
                                'nSpecialCost'      => $specialCost,
                                'vSpecials'         => $specials,
                                'nAmount'           => $serItemArr['price'],
                                'nAmtNext'          => ($amountNext + $specialCostBill),
                                'vType'             => $planType,
                                'vBillingInterval'  => $serItemArr['vBillingInterval'],
                                'nBillingDuration'  => $serItemArr['nBillingDuration'],
                                'nDiscount'         => $serDiscount,
                                'dDateStart'        => $bStartDate,
                                'dDateStop'         => $bStopDate,
                                'dDateNextBill'     => $bNextDate
                            );                        

                        // Billing Main Data
                        if($planType == 'recurring'){
                            // Recurring
                            //nSCatId - will be filled only for domain registration case
                            $dataBillingArr[] = array(
                                                    'nBmId'             => $billMainID,
                                                    'nUId'              => $userID,
                                                    'nServiceId'        => $serviceID,
                                                    'vDomain'           => NULL,
                                                    'vSpecials'         => $specialsBill,
                                                    'nSpecialCost'      => $specialCostBill,
                                                    'nDiscount'         => $serDiscount,
                                                    'nAmount'           => $serItemArr['price'],
                                                    'vType'             => $planType,
                                                    'vBillingInterval'  => $serItemArr['vBillingInterval'],
                                                    'dDateStart'        => $bStartDate,
                                                    'dDateStop'         => $bStopDate,
                                                    'dDateNextBill'     => $bNextDate,
                                                    'dDatePurchase'     => $bStartDate,
                                                    'vDelStatus'        => '0'
                                                );

                        } // End Billing Main Data                        
                    } // End Valid Service
                } // End Service Loop
              } // // End Bill Loop for service plan
            } 
            /*echo "<br/>===========================================================";
            echo "<br/>Service Plan Data Array";
            echopre($dataServiceArr);
            echo "<br/>Service Plan Billing Details Array";
            echopre($dataBillingArr); 
            echo "<br/>===========================================================";  */
            /**************************** Service Plan Billing Area  **************************/              

            /***************************** End of bill generation for store ************************/ 


            
            /***************************** Start of Generate Invoice Area ************************************/

            /******************************** Start of Payment Area ******************************************/
            $storeDetails = Admincomponents::getStoreWithUserDetailsFromProductLookupID($productLookUpID);
            /*echo "<br/>===========================================================";
            echo "<br/>Renewal needed store details";
            echo "<br/>===========================================================";
            echopre($storeDetails); 
            echo "<br/>===========================================================";*/

            $bluedogdetails = $storeDetails->bluedogdetails;
            $bluedogdetails = unserialize($bluedogdetails);  


            
            $grandTotal         = $totalAmount - $discount;
            //echo "<br/>Total amount to pay for renewal of domain/plan = ".$grandTotal;
            
            $payArr1['desc']    = $invDescription;
            $payArr1['amount']  = $grandTotal;
            $payArr1['email']   = $storeDetails->vEmail;
            $payDataArr         = array_merge($payArr1, $bluedogdetails);
            
            $payArr = array('paymentSuccessful' => false, 'paymentError' => NULL, 'transactionId' => NULL);
            if(!empty($bluedogdetails)){
                if($grandTotal > 0) {
                    if($bluedogdetails['payment_gateway']=='bluedog'){
                    $payArr=Paymenthelper::doBlueDogPayment($payDataArr); // Bluedog Payment Gateway
                    }
                    if($bluedogdetails['payment_gateway']=='authorize'){
                    $payDataArr         = array_merge($payArr1, $bluedogdetails['address']);
                    $payArr=User::proceessAuthorizeTokenPayment($bluedogdetails['customerProfileId'],$bluedogdetails['customerPaymentProfileIdList'],$payDataArr); // Bluedog Payment Gateway
                   
                    echo "Authorize Payment";
                    }

                    if($bluedogdetails['payment_gateway']=='stripe'){
                    $payDataArr         = array_merge($payArr1, $bluedogdetails['customer_data']['billing_address']);

                    $stripe_test_mode = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='stripe_test_mode'");

      if($stripe_test_mode=='Y')
      {
        $paySettings['SecretKey'] =  Cronhelper::$dbObj->selectRow("Settings","value","settingfield='stripe_sandbox_secretkey'");
                
      }
      else
      {

          $paySettings['SecretKey'] =  Cronhelper::$dbObj->selectRow("Settings","value","settingfield='stripe_live_secretkey'");
          
      }



           //if($bluedogdetails['payment_intent_id'])
                    
                     
                    $payArr=User::proceessStripePayment($bluedogdetails['customer_id'],$bluedogdetails['payment_method_id'],$paySettings['SecretKey'],$payDataArr,$bluedogdetails['payment_intent_id']); // Stripe Payment Gateway
                    //echopre($payArr);
                    echo "Stripe Payment";

                    }
                    /*echo "<br/>===========================================================";
                    echo "<br/>Bluedog payment details for renewal";
                    echo "<br/>===========================================================";
                    echopre($payArr);
                    echo "<br/>===========================================================";*/
                }
                if(!empty($payArr)){  
                    if($payArr['transactionId']!='' && $payArr['paymentSuccessful']) {
                        $vSubscriptionType  = 'PAID';
                        $paymentDate        = date('Y-m-d H:i:s');
                        $paymentMethod      = $payArr['payment_method'];
                        $paymentFlag        = 1;
                        $transactionID      = $payArr['transactionId'];
                    }else{
                        $errorMsg .= '<br/>'.'Payment Failure -'.$payArr['paymentError'];
                        if($grandTotal >= 0) {
                            $errorMsg .= '<br/>'.'Amount To Pay -'.CURRENCY_SYMBOL.' '.Utils::formatPrice($grandTotal);
                        }
                    }
                }
            }else{
                $errorMsg .= '<br/>'.'Incomplete User Credit Card Credentials';
            }
            /*echo "<br/>===========================================================";
            echo "<br/>Transaction ID   = ".$transactionID;
            echo "<br/>Payment Flag     = ".$paymentFlag;
            echo "<br/>===========================================================";*/



            /****************** Start of Domain renewal process ******************/
            if($paymentFlag){                    
                if(!empty($invDomainGroupArr[$productLookUpID])){
                    
                    $domainRenewalStatus = Admincomponents::doDomainRenewal($productLookUpID);
                    //echo "<br/>Domain Renewal Status = ".$domainRenewalStatus;
                    if($domainRenewalStatus){                            
                        //domain renewal successfully completed

                        //log domain renewal as Domain Renewed
                        Admincomponents::logDomainRenewal($productLookUpID, 1,'',$dataDomainArr['dDateStop']);
                        
                    } else {
                        //... domain renewal failed
                        //echo "<br/>Domain Renewal Failed";
                        $domainRenewalNotificationFlag = true;
                        $domainRenewalNotificationMsg = '<br/>'.'Domain renewal has failed due to some technical issues. Please feel free to contact '.$contactMailID.' for support.';
                        $errorMsg = $domainRenewalNotificationMsg;
                        // Admin Notification on Failed Domain Renewal                            
                        Cronhelper::generateDomainRenewalFailureNotification($productLookUpID, $userID, $errorMsg);
                        //... log domain renewal as Payment Received, Domain Renewal Failed
                        Admincomponents::logDomainRenewal($productLookUpID, 0,'',$dataDomainArr['dDateStop']);

                    }
                }
            }
            /****************** End of Domain renewal process ******************/

            /************* Start of Domain renewal cron attempt process ***************/
            if($attempt == 1) {
                if ($invCronArr[$productLookUpID] == 3) {
                    //.. if this is last (3rd) attempt generate invoice as due
                    $paymentFlag = 1; //Set as paid in last attempt case
                    $vSubscriptionType = "DUE"; //If subscription status is DUE

                    if(!empty($invDomainGroupArr[$productLookUpID])){
                        //... log domain renewal as Payment Due
                        Admincomponents::logDomainRenewal($productLookUpID, 2,'',$dataDomainArr['dDateStop']);
                    } // End If
                }
                //echo "<br/>Payment Flag value after last attempt = ".$paymentFlag;
            }        
            /************* End of Domain renewal cron attempt process ***************/

            if($paymentFlag){
                //echo "<br/>===========================================================";
                //echo "<br/>1) Generate Invoice -> Store ID = ".$productLookUpID."<br>";

                /******************** Generate Invoice  - Table Updates ********************/
                $invQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Invoice SET 
                        nInvId          = NULL, 
                        nUId            = '".$userID."', 
                        nPLId           = '".$productLookUpID."', 
                        dGeneratedDate  = NOW(),
                        dDueDate        = NOW(), 
                        nAmount         = '".$totalAmount."', 
                        nDiscount       = '".$discount."', 
                        nTotal          = '".$grandTotal."',
                        vCouponNumber   = NULL, 
                        vTerms          ='".$invTerms."', 
                        vNotes          = '".$invNotes."',
                        vSubscriptionType = '".$vSubscriptionType."', 
                        vMethod         = '".$paymentMethod."', 
                        vTxnId          = '".$transactionID."', 
                        dPayment        = '".$paymentDate."'";
                Admincomponents::$dbObj->execute($invQry);
                $invoiceIDN     = Admincomponents::$dbObj->lastInsertId();

                //Update Invoice Number
                $invUpdateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Invoice SET vInvNo='".$invoiceIDN."' WHERE nInvId='".$invoiceIDN."'";
                Admincomponents::$dbObj->execute($invUpdateQry);

               
                //End Invoice Creation

                /******************** Generate Invoice  - Table Updates ********************/

                /*********************  Invoice Plan Creation - Plan Creation Against Domain *************************/                    
                if(count($dataDomainArr) > 0){                        
                    //echo "2)Plan Creation Against Domain -> Store ID = ".$productLookUpID."<br>";                        
                    //echopre($dataDomainArr);                        
                    
                    if(empty($dataDomainArr['nDiscount'])){ 
                        $dataDomainArr['nDiscount'] = '0';
                    }
                    $invPlanDQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."InvoiceDomain SET 
                                nIDId               = NULL, 
                                nUId                = '".$dataDomainArr['nUId']."',
                                nInvId              = '".$invoiceIDN."', 
                                nPLId               = '".$dataDomainArr['nPLId']."', 
                                vDescription        = '".$dataDomainArr['vDescription']."', 
                                nAmount             = '".$dataDomainArr['nAmount']."', 
                                nAmtNext            = '".$dataDomainArr['nAmtNext']."', 
                                vType               = '".$dataDomainArr['vType']."', 
                                vBillingInterval    = '".$dataDomainArr['vBillingInterval']."', 
                                nBillingDuration    = '".$dataDomainArr['nBillingDuration']."', 
                                nRate               = '".$dataDomainArr['nRate']."',
                                nDiscount           = '".$dataDomainArr['nDiscount']."', 
                                dDateStart          = '".$dataDomainArr['dDateStart']."', 
                                dDateStop           = '".$dataDomainArr['dDateStop']."', 
                                dDateNextBill       = '".$dataDomainArr['dDateNextBill']."', 
                                dCreatedOn          = NOW(), 
                                nPlanStatus         = '1'";
                    Admincomponents::$dbObj->execute($invPlanDQry);
                }
                /*********************  Invoice Plan Creation - Plan Creation Against Domain *************************/
                
                /*********************  Invoice Plan Creation - Plan Creation Against Service *************************/                                        
                if(count($dataServiceArr) > 0){
                    //echo "3)Plan Creation Against Plan/Service -> Store ID = ".$productLookUpID."<br>";                        
                    //echopre($dataDomainArr); 
                    foreach($dataServiceArr as $itemSP) {                                 
                        if(empty($itemSP['nDiscount'])){ 
                            $itemSP['nDiscount'] = '0';
                        }

                        $invSerQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."InvoicePlan SET 
                                        nIPId               = NULL, 
                                        nUId                = '".$itemSP['nUId']."',
                                        nInvId              = '".$invoiceIDN."',
                                        nServiceId          = '".$itemSP['nServiceId']."',
                                        nSpecialCost        = '".$itemSP['nSpecialCost']."',
                                        vSpecials           = '".$itemSP['vSpecials']."',
                                        nAmount             = '".$itemSP['nAmount']."',
                                        nAmtNext            = '".$itemSP['nAmtNext']."',
                                        vType               = '".$itemSP['vType']."',
                                        vBillingInterval    = '".$itemSP['vBillingInterval']."',
                                        nBillingDuration    = '".$itemSP['nBillingDuration']."',
                                        nDiscount           = '".$itemSP['nDiscount']."',
                                        dDateStart          = '".$itemSP['dDateStart']."',
                                        dDateStop           = '".$itemSP['dDateStop']."',
                                        dDateNextBill       = '".$itemSP['dDateNextBill']."',
                                        dCreatedOn          = NOW(),
                                        nPlanStatus         = '1'";
                        Admincomponents::$dbObj->execute($invSerQry); 
                    } 
                } 
                /*********************  Invoice Plan Creation - Plan Creation Against Service *************************/
               
                /***************************** Billing Main Entry ****************************/
                /******************************* Domain Renewal Entry ***********************/                  
                //echopre($dataDomainBillingArr);
                 if(!empty($dataDomainBillingArr)){ 
                    foreach($dataDomainBillingArr as $itemBill) {
                        $invBillQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET 
                                nUId                = '".$itemBill['nUId']."',
                                nServiceId          = '".$itemBill['nServiceId']."', 
                                vInvNo              = '".$invoiceIDN."', 
                                vDomain             = '".$itemBill['vDomain']."', 
                                nDiscount           = '".$itemBill['nDiscount']."', 
                                nAmount             = '".$itemBill['nAmount']."', 
                                nSpecialCost        = '".$itemBill['nSpecialCost']."', 
                                vSpecials           = '".$itemBill['vSpecials']."', 
                                vType               = '".$itemBill['vType']."', 
                                vBillingInterval    = '".$itemBill['vBillingInterval']."', 
                                nBillingDuration    = '".$itemBill['nBillingDuration']."',
                                dDateStart          = '".$itemBill['dDateStart']."', 
                                dDateStop           = '".$itemBill['dDateStop']."', 
                                dDateNextBill       = '".$itemBill['dDateNextBill']."', 
                                dDatePurchase       = '".$itemBill['dDatePurchase']."', 
                                cronAttempt         = NULL, 
                                vDelStatus          = '".$itemBill['vDelStatus']."' 
                            WHERE 
                                nBmId = '".$itemBill['nBmId']."'"; //
                        Admincomponents::$dbObj->execute($invBillQry);

                    } 
                } 
                /******************************* Domain Renewal Entry ***********************/                  

                /******************************* Service (Plan) Renewal Entry ***********************/                  
                //echopre($dataBillingArr);
                if(!empty($dataBillingArr)) {                        
                    foreach($dataBillingArr as $itemBill) {//removed billing duration from query by Anoop 08.06.2019
                        $invBillQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET 
                                nUId                = '".$itemBill['nUId']."',
                                nServiceId          = '".$itemBill['nServiceId']."',
                                vInvNo              = '".$invoiceIDN."',
                                vDomain             = '".$itemBill['vDomain']."',
                                nDiscount           = '".$itemBill['nDiscount']."',
                                nAmount             = '".$itemBill['nAmount']."',
                                nSpecialCost        = '".$itemBill['nSpecialCost']."',
                                vSpecials           = '".$itemBill['vSpecials']."',
                                vType               = '".$itemBill['vType']."',
                                vBillingInterval    = '".$itemBill['vBillingInterval']."',
                                dDateStart          = '".$itemBill['dDateStart']."',
                                dDateStop           = '".$itemBill['dDateStop']."',
                                dDateNextBill       = '".$itemBill['dDateNextBill']."',
                                dDatePurchase       = '".$itemBill['dDatePurchase']."',
                                cronAttempt         = NULL, 
                                vDelStatus          = '".$itemBill['vDelStatus']."' 
                            WHERE 
                                nBmId = '".$itemBill['nBmId']."'"; //
                        Admincomponents::$dbObj->execute($invBillQry);
                        //secho $invBillQry;

                    } // End Foreach
                } // End If
                /******************************* Service (Plan) Renewal Ends ***********************/                  

                /******************************* Notify admin if it is last attempt ****************/
                if($attempt==1) {
                    if ($invCronArr[$productLookUpID] == 3) {
                        if (!empty($errorMsg)) {
                            $mailMsg .= "<br />" . "<br />" . "Payment could not be processed further for the following reason(s)<br/>" . $errorMsg;
                            // $invoiceIDN - Invoice ID
                            // INVOICE_PREFIX.$invoiceIDN - Invoice No
                            // $errorMsg
                            // Admin Notification on Payment Failure
                            Cronhelper::generateBillFailureNotification($invoiceIDN, $invoiceIDN, $errorMsg);
                        }
                    }
                }
                /******************************* Notify admin if it is last attempt ****************/
                        
                /******************************* Send Invoice Mail *********************************/
                Admincomponents::sendInvoiceMail($invoiceIDN, $mailMsg,'susbcription');
                /******************************* Send Invoice Mail *********************************/

                //echo "<br/>===========================================================";
                /******************** Generate Invoice  - Table Updates Ends ********************/
            }else{
                /*echo "<br/>===========================================================";
                echo "<br/>Update cron attempt counter incremented for store ID ".$productLookUpID." (".$storeDetails->vDomain.")";                        
                //echopre($invDomainGroupArr);
                //echopre($invGroupArr);
                echo "<br/>===========================================================";*/

                /******************************* Domain Plan Entries *******************************/
                if(!empty($invDomainGroupArr[$productLookUpID])){
                    foreach($invDomainGroupArr[$productLookUpID] as $invoiceID => $servicesArr) {
                        foreach($servicesArr as $billMainID => $productLookUpID) {
                            $itemUpdateQry = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "BillingMain SET cronAttempt= CASE WHEN (cronAttempt=3) THEN NULL ELSE IFNULL(cronAttempt, 0) + 1 END WHERE nBmId='" . $billMainID . "'";
                            Cronhelper::$dbObj->execute($itemUpdateQry);
                        }
                    }
                }
                /******************************* End Domain Plan Entries *******************************/

                if(!empty($invGroupArr[$productLookUpID])) {
                    foreach($invGroupArr[$productLookUpID] as $invoiceID => $servicesArr) { // Bill Loop Start
                        // Update cron Attempt in BILL
                        foreach($servicesArr as $billMainID => $serviceID) {
                            //$itemUpdateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET cronAttempt=IFNULL(cronAttempt, 0) + 1 WHERE nBmId='".$billMainID."'";
                            $itemUpdateQry = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "BillingMain SET cronAttempt= CASE WHEN (cronAttempt=3) THEN NULL ELSE IFNULL(cronAttempt, 0) + 1 END WHERE nBmId='" . $billMainID . "'";
                            Cronhelper::$dbObj->execute($itemUpdateQry);
                        } // End Foreach
                    } // End Bill Loop
                } // End If    
                //echo "<br/>===========================================================<br/>";                
            }                
            /*****************************Generate Invoice Area End *********************************/
            $cronUpdatedFlag = 1;
        }
        if($cronUpdatedFlag){
            echo "Cronjob executed successfully!";
        }else{
            echo "No Service plan/Domain found for renewal!";
        }
    } 

    public static function generateBillFailureNotification($invoiceID, $invoiceNo, $errorMsg) {
        //TODO : This method generates pending bill notification to the site Administrator
        Cronhelper::$dbObj = new Db();
        $adminEmail = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='adminEmail'");
        $mailMsg .= "<html>";
        $mailMsg .= "<head></head>";
        $mailMsg .= "<body>";
        $mailMsg .= "Dear Administrator,<br/>
                    Payment Failure on Invoice No: ".$invoiceNo." [Invoice ID - ".$invoiceID."]";
        $mailMsg .= "<br />"."<br />". "Payment could not be processed further for the following reason(s)<br/>".$errorMsg;
        $mailMsg .= "<br />"."<br />Support Team";
        $mailMsg .= "</body>";
        $mailMsg .= "</html>";
        $subject = "Payment Failure on Invoice No: ".$invoiceNo." from ".SITE_NAME;
        $mailMsg = Utils::bindEmailTemplate($mailMsg);
        $senderMailID = COMPANY_NAME . '<' . COMPANY_EMAIL . '>';
        $checkSenderMailID = str_replace(array("<"," ",">"), "", $senderMailID);
        $senderMailID = (empty($checkSenderMailID)) ? SITE_NAME . '<' . ADMIN_EMAILS . '>' : $senderMailID;

        //PageContext::includePath('email');
       // $emailObj    = new Emailsend();
        $emailData   = array("from"		=> COMPANY_EMAIL,
                "subject"	=> $subject,
                "message"	=> $mailMsg,
                "to"           => $adminEmail);
        //$emailObj->email_senderNow($emailData);
        Mailer::sendSmtpMail($emailData);
    } // End Function

    public static function generateDomainRenewalFailureNotification($productLookUpID, $userID, $errorMsg=NULL) {
        //TODO : This method generates domain renewal failure notification to the site Administrator
        Cronhelper::$dbObj = new Db();

        $adminEmail = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='adminEmail'");

        // Store Info
        $storeHost = Admincomponents::getStoreHost($productLookUpID);
        // User Info getUserInfo
        $userArr = Admincomponents::getUserInfo($userID);
        $userArr->Name;
        $userArr->vEmail;
        $userArr->vInvoiceEmail;
        
        $mailMsg .= "<html>";
        $mailMsg .= "<head></head>";
        $mailMsg .= "<body>";
        $mailMsg .= "Dear Administrator,<br/>
                    ".$storeHost." - Domain Renewal Failed due to some technical issues for user: ".$userArr->Name." [".$userArr->vEmail."]";
        if(!empty($errorMsg)) {
            $mailMsg .= "<br />"."<br />".$errorMsg;
        }
        $mailMsg .= "<br />"."<br />Support Team";
        $mailMsg .= "</body>";
        $mailMsg .= "</html>";
        $subject = "Domain Renewal Failed for: ".$storeHost." from ".SITE_NAME;
        $mailMsg = Utils::bindEmailTemplate($mailMsg);
        $senderMailID = COMPANY_NAME . '<' . COMPANY_EMAIL . '>';
        $checkSenderMailID = str_replace(array("<"," ",">"), "", $senderMailID);
        $senderMailID = (empty($checkSenderMailID)) ? SITE_NAME . '<' . ADMIN_EMAILS . '>' : $senderMailID;

       // PageContext::includePath('email');
        //$emailObj    = new Emailsend();
        $emailData   = array("from"		=> COMPANY_EMAIL,
                "subject"	=> $subject,
                "message"	=> $mailMsg,
                "to"           => $adminEmail);
        //$emailObj->email_senderNow($emailData);
        Mailer::sendSmtpMail($emailData);
        
    } // End Function

    public static function groupInvoices($billArr) {        
        $dataArr = array();
        if(!empty($billArr)) {
            foreach($billArr as $itemBill) {
                $productLookUpID = NULL;
                if(!empty($itemBill->nServiceId)){ 
                    $productLookUpID    = Admincomponents::getProductLookUpIDFromInvoice($itemBill->vInvNo);
                    $transactionSession = Admincomponents::getTransactionSessionIDFromInvoice($itemBill->vInvNo);
                    if(empty($transactionSession)){
                        $dataArr[$productLookUpID][$itemBill->vInvNo][$itemBill->nBmId] = $itemBill->nServiceId;
                        //$dataArr[$itemBill->vInvNo][$itemBill->nBmId] = $itemBill->nServiceId; //
                    }
                }
            } // End Foreach
        }// End If
        return $dataArr;
    } // End Function

    public static function groupInvoicesForDomainRenewal($billArr){       
        $dataArr = array();
        if(!empty($billArr)) {            
            foreach($billArr as $itemBill) {
                $productLookUpID = NULL;
                if($itemBill->vDomain == 1) {    //If paid plan and type of domain                 
                    $productLookUpID = Admincomponents::getProductLookUpIDFromInvoice($itemBill->vInvNo);
                    $dataArr[$productLookUpID][$itemBill->vInvNo][$itemBill->nBmId] = $productLookUpID;
                    //$dataArr[$itemBill->vInvNo][$itemBill->nBmId] = $productLookUpID;
                }
            } // End Foreach
        }// End If
        return $dataArr;
    } // End Function
   
    public static function groupStoresForBill($billArr) {

        /*
         * sample output - array("336" => "304") here 336 id the store lookup ID and 304 is the user ID. ie, user ID against each store.
         */
        
        $dataArr = $invArr = array();
        Admincomponents::$dbObj = new Db();

        if(!empty($billArr)) {
            foreach($billArr as $itemBill) {

                $invoiceID = $productLookUpID = $userID = NULL;
                    
                $invoiceID = $itemBill->vInvNo;
                $invArr = Admincomponents::$dbObj->selectRecord("Invoice INV INNER JOIN ".Admincomponents::$dbObj->tablePrefix."ProductLookup LP ON INV.nPLId = LP.nPLId "," LP.nPLId,LP.nUId ", " INV.nInvId = ".$invoiceID." GROUP BY LP.nPLId");

                   if(!empty($invArr)) {
                      $productLookUpID =  $invArr->nPLId;
                      $userID = $invArr->nUId;
                      $dataArr[$productLookUpID]= $userID; // group each store against user
                   }
                                
            } // End Foreach
        }// End If
        
        return $dataArr;

    } // End Function

    public static function groupCronAttempt($billArr) {
        $dataArr = array();
        $dataAttemptArr = array();
        // collect cron attempt per bill against each invoice
        if(!empty($billArr)) {
            foreach($billArr as $itemBill) {
                $productLookUpID = NULL;
                $productLookUpID = Admincomponents::getProductLookUpIDFromInvoice($itemBill->vInvNo);
                $dataArr[$productLookUpID][$itemBill->nBmId] = $itemBill->cronAttempt; //
            } // End Foreach
        }// End If

        // collect maximum cron attempt per invoice
        if(!empty($dataArr)) {
            foreach($dataArr as $productLookUpID => $billAttemptArr) {
                $dataAttemptArr[$productLookUpID] = (!empty($billAttemptArr)) ? max($billAttemptArr) : NULL;
            }
        } // End If

        return $dataAttemptArr;
    } // End Function


    /*
     serviceInfo mode could be either purchase / service
     Mode : purchase - will pull the service price as on purchase [on the basis of invoice generated during subscription of Product Service]
     Mode : service - on the basis of product service [price may vary on each Product Service Price revision]
     Default Mode : purchase
     Scope : calculate the service plan price in respect to the mode 1) purchase 2) service
    */
    public static function serviceInfo($serviceID, $mode = 'purchase', $invoiceID = NULL) {        
        $invArr = Admincomponents::getInvoiceDetails($invoiceID, array(array('field' => 'ip.nServiceId', value => $serviceID),array('field' => 'ip.nPlanStatus', value => 1))); // Check whether current service plan is active
        $serviceArr = array();
        if(!empty($invArr)) {
            switch($mode) {
                case 'purchase':
                    if(trim($invArr[0]->servicePrice) <> ""){
                        $serviceArr['price'] = $invArr[0]->servicePrice;
                    }else{
                        $serviceArr['price'] = $invArr[0]->ipAmount;
                    }
                    $serviceArr['vBillingInterval'] = $invArr[0]->ipBillingInterval;
                    $serviceArr['nBillingDuration'] = $invArr[0]->ipBillingDuration;
                    break;
                case 'service':
                    $serviceArr['price'] = $invArr[0]->servicePrice;
                    $serviceArr['vBillingInterval'] = $invArr[0]->serviceBillingInterval;
                    $serviceArr['nBillingDuration'] = $invArr[0]->serviceBillingDuration;
                    break;
            } // End Switch           
        }

        return $serviceArr;

    } // End Function

    public static function userInfoFilter($userId) {
        $dataArr = array();
        $userArr = Admincomponents::getListItem("general", array('nGId', 'nUserId', 'vFirstName', 'vLastName', 'vNumber', 'vCode', 'vMonth', 'vYear','vAddress', 'vCity', 'vState', 'vZipcode', 'vCountry', 'vEmail'), array(array('field' => 'nUserId', 'value' => $userId)));
        if(!empty($userArr)) {
            foreach($userArr as $userItem) {
                $dataArr['fName'] = $userItem->vFirstName;
                $dataArr['lName'] = $userItem->vLastName;
                $dataArr['ccno'] = $userItem->vNumber;
                $dataArr['cvv'] = $userItem->vCode;
                $dataArr['expMonth'] = $userItem->vMonth;
                $dataArr['expYear'] = $userItem->vYear;
                $dataArr['add1'] = $userItem->vAddress;
                $dataArr['city'] = $userItem->vCity;
                $dataArr['state'] = $userItem->vState;
                $dataArr['zip'] = $userItem->vZipcode;
                $dataArr['country'] = $userItem->vCountry;
                $dataArr['ccEmail'] = $userItem->vEmail;
            } // End Foreach
        }

        return $dataArr;

    } // End Function

    public static function generateFreeTrialExpiryNotification($triggerSpan = 5, $expired = false) {
        $freeTrialSpan = Admincomponents::getFreeTrialSpan();
        $expirySpan = ($expired) ? ((int)$freeTrialSpan + (int)$triggerSpan) : ((int)$freeTrialSpan - (int)$triggerSpan);
        Admincomponents::$dbObj = new Db();
        $sel = "SELECT i.nInvId, i.nUId, i.vInvNo, i.dGeneratedDate, i.dDueDate, i.nAmount, i.nDiscount,
             i.nTotal, i.vCouponNumber, i.vTerms, i.vNotes, i.vMethod, i.vSubscriptionType,
             i.vTxnId, i.dPayment, NOW() as currentDate,i.nPLId, ip.nAmount as ipAmount, ip.nDiscount as ipDiscount,ip.vType,ip.vBillingInterval as ipBillingInterval, ip.nBillingDuration as ipBillingDuration,DATEDIFF(CURDATE(), DATE(i.dGeneratedDate)) as iExpiryspan, DATE_ADD(i.dGeneratedDate, INTERVAL ".$freeTrialSpan." DAY) as iExpiryDate, ps.vServiceName, ps.vServiceDescription,
             ps.price as servicePrice, ps.vBillingInterval as serviceBillingInterval,
             ps.nBillingDuration as serviceBillingDuration, ps.nSCatId, p.vPName, u.vUsername, u.vFirstName, u.vLastName,
             u.vEmail, u.vInvoiceEmail, u.vAddress, u.vCountry, u.vState, u.vZipcode, u.vCity FROM ".Admincomponents::$dbObj->tablePrefix."Invoice i
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."InvoicePlan ip ON i.nInvId = ip.nInvId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."ProductServices ps ON ip.nServiceId = ps.nServiceId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."Products p ON ps.nPId = p.nPId
             LEFT JOIN ".Admincomponents::$dbObj->tablePrefix."User u ON i.nUId = u.nUId WHERE DATEDIFF(CURDATE(), DATE(i.dGeneratedDate))='".$expirySpan."' AND i.vSubscriptionType = 'FREE' AND i.upgraded!='1'";
        //echo '</br>'.$sel;
        $dataArr = Admincomponents::$dbObj->selectQuery($sel);
        Logger::info($dataArr);
        foreach($dataArr as $item) {
            //echopre($item);
            // Mail Message
            $mailMsgArr = ($expired) ? Admincomponents::getListItem("Cms", array('cms_title','cms_desc'), array(array('field' => 'LOWER(cms_name)', 'value' => strtolower('free_trial_expired_notification')))) : Admincomponents::getListItem("Cms", array('cms_title','cms_desc'), array(array('field' => 'LOWER(cms_name)', 'value' => strtolower('free_trial_expiry_notification'))));
            Logger::info($mailMsg);

            $subject = $mailMsg = NULL;

            if(!empty($mailMsgArr)) {
                $subject = $mailMsgArr[0]->cms_title;
                $mailMsg = $mailMsgArr[0]->cms_desc;
            } // End If

            // User Details
            $userName = stripslashes($item->vFirstName);
            $userName .= (!empty($item->vLastName)) ? '&nbsp;'.stripslashes($item->vLastName) : NULL;
            $userEmail = $item->vEmail;
            // User Details End

            $subject = str_replace("{SITE_NAME}", SITE_NAME, $subject);
            $subject = str_replace("{PRODUCT_NAME}", '', $subject);
            $subject = str_replace("{EXPIRY_DATE}", Utils::formatDateUS($item->iExpiryDate), $subject);
            $mailMsg = str_replace("{MEMBER_NAME}", $userName, $mailMsg);
            $mailMsg = str_replace("{SITE_NAME}", SITE_NAME, $mailMsg);
            $mailMsg = str_replace("{PRODUCT_NAME}", $item->vPName, $mailMsg);
            $mailMsg = str_replace("{EXPIRY_DATE}", Utils::formatDateUS($item->iExpiryDate), $mailMsg);
            $mailMsg = Utils::bindEmailTemplate($mailMsg);

            $senderMailID = COMPANY_NAME . '<' . COMPANY_EMAIL . '>';
            $checkSenderMailID = str_replace(array("<"," ",">"), "", $senderMailID);
            $senderMailID = (empty($checkSenderMailID)) ? SITE_NAME . '<' . ADMIN_EMAILS . '>' : $senderMailID;

            //PageContext::includePath('email');

            //$emailObj    = new Emailsend();
            $emailData   = array("from" => COMPANY_EMAIL,
                    "subject"   => $subject,
                    "message"   => $mailMsg,
                    "to"        => $userEmail);
            //$emailObj->email_senderNow($emailData);
            Mailer::sendSmtpMail($emailData);
        } // End Foreach

    } //End Function

    public static function generateBillNotification($triggerSpan = 5) { 
        $billArr = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'nSCatId', 'vDomain', 'nDiscount', 'nAmount', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus'), array(array('field' => 'vDelStatus', 'value' => '1', 'condition' => '!='), array('field' => 'DATE_SUB(dDateNextBill, INTERVAL '.$triggerSpan.' DAY)', 'value' => 'CURDATE()','inputQuotes' => 'N'), array('field' => 'IFNULL( cronAttempt, 0 )', 'value' => '1','condition' => ' < ','inputQuotes' => 'N')));
        Logger::info($billArr);
        //echopre($billArr);

        //groupInvoices
        $invGroupArr=Cronhelper::groupInvoices($billArr);
        Logger::info($invGroupArr);
        //echopre($invGroupArr);

        if(!empty($invGroupArr)){
            foreach($invGroupArr as $servicesArr){               
                //echopre($servicesArr);
                reset($servicesArr);
                $invoiceID      = key($servicesArr);                
                $totalAmount    = 0;
                $discount       = 0;
                $grandTotal     = 0;
                $errorMsg       = NULL;
                $userID         = NULL;
                $serCnt         = 0;
                $walletBalance  = $walletDiscount = $walletNewBalance = 0;
                $nextBillDate   = NULL;
                
                // Invoice Detail Item                
                $invDtItem = Admincomponents::getInvoiceDetails($invoiceID);
                //echopre($invDtItem);
                Logger::info($invDtItem);

                /*  echo 'INVOICE DETAIL ITEM <pre>';
                    print_r($invDtItem);
                    echo '</pre>';
                */

                // Invoice Detail Item                    
                foreach($servicesArr as $servicesDat){                     
                    $servicesData   = array_keys($servicesDat);
                    $billMainID     = $servicesData[0];
                    $serviceID      = $servicesDat[$billMainID];
                       
                    // Service Info / Checks whether current service plan is active or not
                    $serItemArr = Cronhelper::serviceInfo($serviceID, 'purchase', $invoiceID);                    
                    //echopre($serItemArr);
                    Logger::info($serItemArr);
                    /*
                    echo 'SERVICE ITEM -- <pre>';
                    print_r($serItemArr);
                    echo '</pre>';
                    */
        
                    // Billing Item
                    $billItem = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials','vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus'), array(array('field' => 'nBmId', 'value' => $billMainID)));
                    Logger::info($billItem);

                    // Specials
                    $specials       = NULL;
                    $specialsBill   = NULL;
                    $specialsArr    = array();
                    if(isset($billItem[0]->vSpecials) && !empty($billItem[0]->vSpecials)) { // vSpecials
                        // Specials
                        $specialsArr = json_decode($billItem[0]->vSpecials);
                        $totalAmount += $billItem[0]->nSpecialCost; // Add Specialcost
                    }

                    /*
                    echo 'BILLING ITEM -- <pre>';
                    print_r($billItem);
                    echo '</pre>';
                    */

                    // Billing Item
                    // Next Bill Date
                    $nextBillDate = $billItem[0]->dDateNextBill;
                    
                    if(!empty($serItemArr)) {
                        $totalAmount += $serItemArr['price'];
                        // Specials
                        //
                    } // End Valid Service

                } // End Service Loop

                //User ID
                $userID = $invDtItem[0]->nUId;

                // Wallet Balance Check
                $walletBalance      =  Admincomponents::getUserWalletBalance($userID);
                $walletDiscount     += ($totalAmount < $walletBalance) ? $totalAmount : $walletBalance;
                $walletNewBalance   =  $walletBalance-$walletDiscount;
                $discount           += $walletDiscount;
                // End Wallet Balance Check

                $grandTotal = $totalAmount - $discount;
                //echo "grandTotal = ".$grandTotal;

                //*************Mail Notification  Area*******************************
                //TODO : Mail notification Per Invoice
                $mailMsgArr = Admincomponents::getListItem("Cms", array('cms_title','cms_desc'), array(array('field' => 'LOWER(cms_name)', 'value' => strtolower('bill_notification'))));
                Logger::info($mailMsg);

                $subject = $mailMsg = NULL;

                if(!empty($mailMsgArr)) {
                    $subject = $mailMsgArr[0]->cms_title;
                    $mailMsg = $mailMsgArr[0]->cms_desc;
                } // End If

                // User Details
                $userName = stripslashes($invDtItem[0]->vFirstName);
                $userName .= (!empty($invDtItem[0]->vLastName)) ? '&nbsp;'.stripslashes($invDtItem[0]->vLastName) : NULL;
                $userEmail = $invDtItem[0]->vEmail;
                // User Details End

                $subject = str_replace("{SITE_NAME}", SITE_NAME, $subject);
                $subject = str_replace("{PRODUCT_NAME}", '', $subject);
                $subject = str_replace("{PAYMENT_DATE}", Utils::formatDateUS($nextBillDate, FALSE, 'date'), $subject);
                $mailMsg = str_replace("{MEMBER_NAME}", $userName, $mailMsg);
                $mailMsg = str_replace("{SITE_NAME}", SITE_NAME, $mailMsg);
                $mailMsg = str_replace("{PRODUCT_NAME}", $invDtItem[0]->vPName, $mailMsg);
                $mailMsg = str_replace("{PAYMENT_DATE}", Utils::formatDateUS($nextBillDate, FALSE, 'date'), $mailMsg);
                $mailMsg = str_replace("{AMOUNT}", CURRENCY_SYMBOL.Utils::formatPrice($grandTotal), $mailMsg);
                $mailMsg = str_replace("{COMPANY_NAME}", COMPANY_NAME, $mailMsg);
                $mailMsg = Utils::bindEmailTemplate($mailMsg);

                $senderMailID = COMPANY_NAME . '<' . COMPANY_EMAIL . '>';
                $checkSenderMailID = str_replace(array("<"," ",">"), "", $senderMailID);
                $senderMailID = (empty($checkSenderMailID)) ? SITE_NAME . '<' . ADMIN_EMAILS . '>' : $senderMailID;

                //PageContext::includePath('email');

                //$emailObj    = new Emailsend();
                $emailData   = array("from" => COMPANY_EMAIL,
                        "subject"   => $subject,
                        "message"   => $mailMsg,
                        "to"        => $userEmail);
                //$emailObj->email_senderNow($emailData);
                //echopre($emailData); die();
                Mailer::sendSmtpMail($emailData);
                //*************Mail Notification Area End **************************



            } // End Bill Loop

        } // End If  
        echo "Cronjob executed successfully!";
        exit(0);
    } // End Function

    public static function disableCpanelExpiredDomains() {
        $storeArr = Admincomponents::getExpiredDomains();
echopre($storeArr);        
        PageContext::includePath('cpanel');
        $cpanelObj = new cpanel();
        
        if(!empty($storeArr)){
            foreach($storeArr as $store){
                $lookupID = NULL;
                $lookupID = $store->nPLId;
                $storeServerInfoArr = array();
                $storeServerInfoArr = Admincomponents::getStoreServerInfo($lookupID);
                $res = $cpanelObj->enableDisableCpanelAccount($storeServerInfoArr, 'disable');
                //echopre($res);
                if ($res) {
                    //... update store status as 0 -> diable store
                    Admincomponents::updateExpiredDomain($lookupID, 0);
                } 

            } // End For Each
        } // End If
        echo "Cronjob executed successfully!";

    } // End Function

    public static function generateFailedInvoiceAlert($alertDates) {
        if (!empty($alertDates)) {

            // Mail Message
            $mailMsgArr = Admincomponents::getListItem("Cms", array('cms_title', 'cms_desc'), array(array('field' => 'LOWER(cms_name)', 'value' => 'failed_invoce_alert')));

            $subject = $mailMsg = NULL;

            if (!empty($mailMsgArr)) {
                $subject = stripslashes($mailMsgArr[0]->cms_title);
                $subject = str_replace("{SITE_NAME}", SITE_NAME, $subject);
                $mailMsg = stripslashes($mailMsgArr[0]->cms_desc);
            } // End If
           // PageContext::includePath('email');

            foreach ($alertDates as $dateRange) {

                $userArr = Admincomponents::getPendingPaymentList($dateRange);

                if (!empty($userArr)) {

                    foreach ($userArr as $users) {
                        
                        $inv_date = Utils::formatDateUS($users['inv_date'], false);

                        $contactEmail = COMPANY_EMAIL;
                        $contactEmail = (empty($contactEmail)) ? ADMIN_EMAILS : $contactEmail;

                        
                        $emailMsg = str_replace('{USER}', $users['user_name'], $mailMsg);
                        $emailMsg = str_replace('{INVOICENO}', $users['inv_no'], $emailMsg);
                        $emailMsg = str_replace('{INVOICEDATE}', $inv_date, $emailMsg);
                        $emailMsg = str_replace('{SITE_NAME}', SITE_NAME, $emailMsg);
                        $emailMsg = str_replace('{ADMIN_CONTACT_EMAIL}', $contactEmail, $emailMsg);
                        $emailMsg = Utils::bindEmailTemplate($emailMsg);
                        
                        $senderMailID = COMPANY_NAME . '<' . COMPANY_EMAIL . '>';
                        $checkSenderMailID = str_replace(array("<"," ",">"), "", $senderMailID);
                        $senderMailID = (empty($checkSenderMailID)) ? SITE_NAME . '<' . ADMIN_EMAILS . '>' : $senderMailID;

                        //$emailObj = new Emailsend();
                        $emailData = array("from" => COMPANY_EMAIL,
                                "subject" => $subject,
                                "message" => $emailMsg,
                                "to" => $users['user_mail']);

                        if (trim($users['user_mail']) <> '') {
                            //$emailObj->email_senderNow($emailData);
                            Mailer::sendSmtpMail($emailData);
                            
                            // notify failure on invoice mail too
                            if(!empty($users['invoice_mail'])){
                                $emailData["to"] = $users['invoice_mail'];
                                //$emailObj->email_senderNow($emailData);
                                Mailer::sendSmtpMail($emailData);
                            }
                            //send a notification to admin on 4th notice
                            if ($dateRange == 20) {
                                
                                $storeHost = Admincomponents::getStoreHost($users['productlookupID']);
                                $message = $users['user_name'] . '[' . $users['user_mail'] . '] for the account : ' . $storeHost . ' has not yet made the payment against invoice no - '.$users['inv_no'].' generated on '.$inv_date;
                                $subject = "Pending Invoice Alert from ".SITE_NAME;
                                $mailMsg = Utils::bindEmailTemplate($message);
                                $admin_emailData = array("from" => COMPANY_EMAIL,
                                        "subject" => $subject,
                                        "message" => $mailMsg,
                                        "to" => ADMIN_EMAILS);
                                //$emailObj->email_senderNow($admin_emailData);
                                Mailer::sendSmtpMail($admin_emailData);
                                
                            }
                        }
                    }
                }
                unset($userArr);
            }
        }
    } // End Function

    public static function domainRenewalAttempt(){
       //... Attempt for Payment Received, Domain Renewal Failed
       Cronhelper::$dbObj = new Db();
       $dataArr =Admincomponents::getListItem("domainrenewallog", array('id', 'nPLId', 'nUId', 'vDomain', 'status', 'comments', 'createdOn', 'cronAttempt'), array(array('field' => 'status', 'value' => '0'), array('field' => 'comments', 'value' => 'Payment Received, Domain Renewal Failed')));
       if(!empty($dataArr)) {
           foreach($dataArr as $item){
                $productLookUpID = $logID = $itemUpdateQry = $userID = NULL;
                $productLookUpID = $item->nPLId;
                $logID      = $item->id;
                $userID     = $item->nUId;
                $domainRenewalStatus = Admincomponents::doDomainRenewal($productLookUpID);

                if($domainRenewalStatus){
                    //... domain renewal successfully completed
                    //... log domain renewal as Domain Renewed
                    Admincomponents::logDomainRenewal($productLookUpID, 1, $logID);
                } else {
                    Admincomponents::updateDomainRenewalAttempt($logID);
                    if($item->cronAttempt==5){
                        $errorMsg = 'Sorry, This is the fifth attempt! This domain may be lost!';
                        Cronhelper::generateDomainRenewalFailureNotification($productLookUpID, $userID, $errorMsg);
                    }
                }
               
           }
       }
    } // End Function

  public static function generateBillSubscriptionWithDomainRenewal($dataArr = array(), $payArr = array()) {
        Cronhelper::$dbObj = new Db();
        $transactionSession = NULL;
        $billArr = $storeGroupArr = $invGroupArr = $invDomainGroupArr = array();
        if(!empty($dataArr)) {
             // $dataArr must be the IPN response
             /************ Expected Results in Response Array **********/
             //$responseArr["error"] = ""; // returns the error message if there is any error
             //$responseArr["status"] = ""; // returns the status of the payment 1 => success, 0 => failure
             //$responseArr["data"] = ""; // returns the post data with key and value
             /*********************************************************/

            /*
             Response Data
             EXPECTED SET OF RESULTS WOULD BE
             $dataArr = array(
                    "business" => $paypal_email,
                    "item_name" => "Baseball Hat Monthly",
                    "item_number" => "12",
                    "rm" => "2",
                    "image_url" => BASE_URL."/logo.gif",
                    "no_shipping" => "",
                    "notify_url" => BASE_URL."/paypal-ipn-handler.php",
                    "return" => BASE_URL."/thankyou.php",
                    "cancel_return" => BASE_URL."/cancel.php",
                    "a1" => "10.00",
                    "p1" => "30",
                    "t1" => "D",
                    "a3" => "15.00",
                    "p3" => "30",
                    "t3" => "D",
                    "src" => "1",
                    "sra" => "1",
                    "no_note" => "1",
                    "currency_code" => "GBP",
                    "modify" => "0",
                    "subscr_date" => "2013-06-04",
                    "custom" => "2-12"); // user_id-lookop_id
             */

            $responseArr = $dataArr["data"];

            //Transaction Session
            $transactionSession = $responseArr["item_number"];

            //mapTransactionSessionWithBill
            $billArr = Admincomponents::mapTransactionSessionWithBill($transactionSession);

            if(!empty($billArr)){


                Logger::info($billArr);

                //groupStores - Group all the users store account
                $storeGroupArr = Cronhelper::groupStoresForBill($billArr);
                //echopre($storeGroupArr);

                //groupInvoices - Service plan invoices
                $invGroupArr=Cronhelper::groupInvoices($billArr);
                Logger::info($invGroupArr);

                //echopre($invGroupArr);

                //groupDomainRenewal Invoices
                $invDomainGroupArr=Cronhelper::groupInvoicesForDomainRenewalSubscription($billArr);

                $domainRenewalPeriod    = 1; // domain renewal is for 1 year
                $domainRenewalInterval  = 'Y';
                $contactMailID          = COMPANY_EMAIL;
                $contactMailID          = (empty($contactMailID)) ? ADMIN_EMAILS : $contactMailID;

                // generate bill for each store
                foreach($storeGroupArr as $productLookUpID => $userID){
                    $totalAmount = 0;
                    $discount = 0;
                    $grandTotal = 0;
                    $mailMsg = $errorMsg = NULL;

                    $paymentFlag = 0;
                    $paymentMethod = $paymentDate = $transactionID = NULL;


                    // get Store Host
                    $storeHost = NULL;
                    $storeHost = Admincomponents::getStoreHost($productLookUpID);

                    $dataDomainArr = array();
                    $dataServiceArr = array();

                    $dataBillingArr = array();
                    $dataDomainBillingArr = array();

                    //... Domain Renewal Failure
                    $domainRenewalNotificationFlag = false;
                    $domainRenewalNotificationMsg = NULL;

                    // Domain Billing Area
                    if(!empty($invDomainGroupArr[$productLookUpID])){

                        foreach($invDomainGroupArr[$productLookUpID] as $invoiceID => $servicesArr) {

                        $invDescription = $invTerms = $invNotes = NULL;

                        foreach($servicesArr as $billMainID => $productLookUpID) {

                            // Bill Attempt
                            $attempt = NULL;

                            // Billing Item
                            $billItem = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus'), array(array('field' => 'nBmId', 'value' => $billMainID)));
                            Logger::info($billItem);
                            // Billing Item

                            // Invoice Domain Detail Item
                            $invDtItem = Admincomponents::getInvoiceDomainDetails($invoiceID);
                            Logger::info($invDtItem);
                            // Invoice Domain Detail Item

                            // pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,

                            // Service Summary as payment title
                            $invDescription = $storeHost.' - Domain Renewal';

                            $invTerms = $invDtItem[0]->vTerms; // Invoice Terms
                            $invNotes = $invDtItem[0]->vNotes; // Invoice Notes
                            $tldPrice = User::gettldprice('');   

                            if(!empty($billItem)){                                 
                                $bStartDate     = $bStopDate = $bNextDate = $planType = $amountNext= $serDiscount = NULL;
                                $planPrice      = 0;
                                if(trim($tldPrice) <> "" && trim($tldPrice) > 0){
                                    $planPrice  = trim($tldPrice);
                                }else{                    
                                    $planPrice  = $invDtItem[0]->nRate; //Basic rate as per service plan table
                                }
                                $amountNext     = $planPrice * $domainRenewalPeriod; // Domain Renewal Price for next year
                                $totalAmount    += $amountNext; // Domain registration cost

                                $addYear        = NULL;
                                $addYear        = " +".$domainRenewalPeriod." years";
                                $bStartDate     = date("Y-m-d");
                                $bStopDate      = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addYear);
                                $bStopDate      = date("Y-m-d", $bStopDate);
                                //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                                //$bNextDate = date("Y-m-d", $bNextDate);
                                $bNextDate      = $bStopDate;
                                $planType       = 'recurring';

                                $attempt= ($billItem[0]->cronAttempt == 1) ? 1 : 0;
                                //------------------------------------------------------

                                $dataDomainArr = array(
                                                    'nUId'              => $userID,
                                                    'nPLId'             => $productLookUpID,
                                                    'vDescription'      => $invDescription,
                                                    'nAmount'           => $amountNext,
                                                    'nAmtNext'          => $amountNext,
                                                    'vType'             => $planType,
                                                    'nRate'             => $amountNext,
                                                    'vBillingInterval'  => $domainRenewalInterval,
                                                    'nBillingDuration'  => $domainRenewalPeriod,
                                                    'nDiscount'         => NULL,
                                                    'dDateStart'        => $bStartDate,
                                                    'dDateStop'         => $bStopDate,
                                                    'dDateNextBill'     => $bNextDate,
                                                    'nPlanStatus'       => NULL
                                                );                            
                                // Billing Main Data
                                if($planType == 'recurring'){
                                    // Recurring
                                    //nSCatId - will be filled only for domain registration case
                                    $dataDomainBillingArr[] = array(
                                                                'nBmId'             => $billMainID,
                                                                'nUId'              => $userID,
                                                                'nServiceId'        => $serviceID,
                                                                'vDomain'           => $invDtItem[0]->nDomainStatus,
                                                                'vSpecials'         => NULL,
                                                                'nSpecialCost'      => NULL,
                                                                'nDiscount'         => NULL,
                                                                'nAmount'           => $planPrice,
                                                                'vType'             => $planType,
                                                                'vBillingInterval'  => $domainRenewalInterval,
                                                                'dDateStart'        => $bStartDate,
                                                                'dDateStop'         => $bStopDate,
                                                                'dDateNextBill'     => $bNextDate,
                                                                'dDatePurchase'     => $bStartDate,
                                                                'vDelStatus'        => '0'
                                                            );

                                } // End Billing Main Data

                            } // End Valid Service

                        } // End Service Loop

                        } // end bill loop for - Domain renewal plan
                    }
                    // Domain Billing Area End

                    // Service Plan Billing Area

                    if(!empty($invGroupArr[$productLookUpID])){
                        $invDescription = $invTerms = $invNotes = NULL;
                        foreach($invGroupArr[$productLookUpID] as $invoiceID => $servicesArr) {// Bill Loop Start
                        $serCnt = 0;
                        foreach($servicesArr as $billMainID => $serviceID) {
                            ++$serCnt;

                            // Service Info / Checks whether current service plan is active or not
                            $serItemArr = Cronhelper::serviceInfo($serviceID, 'purchase', $invoiceID);
                            Logger::info($serItemArr);
                            // Service Info

                            // Billing Item
                            $billItem = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus'), array(array('field' => 'nBmId', 'value' => $billMainID)));
                            Logger::info($billItem);
                            // Billing Item

                            // Specials
                            $specials = NULL;
                            $specialsBill=NULL;
                            $specialsArr = array();
                            if(isset($billItem[0]->vSpecials) && !empty($billItem[0]->vSpecials)) { // vSpecials
                                // Specials
                                $specialsArr = json_decode($billItem[0]->vSpecials);
                            }


                            // Invoice Detail Item
                            $invDtItem = Admincomponents::getInvoiceDetails($invoiceID, array(array('field' => 'ip.nServiceId', value => $serviceID)));
                            Logger::info($invDtItem);

                            // Invoice Detail Item

                            // pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,

                            // Service Summary as payment title
                            $invDescription = ($serCnt==1) ? $storeHost.' - '.$invDtItem[0]->vServiceName : ' - '.$invDtItem[0]->vServiceName;

                            $invTerms =$invDtItem[0]->vTerms;
                            $invNotes = $invDtItem[0]->vNotes;

                            if(!empty($serItemArr)) {
                                $totalAmount += $serItemArr['price'];
                                $productSpanArr['productBillingInterval']; // productBillingInterval
                                $productSpanArr['productBillingDuration']; // productBillingDuration
                                $serItemArr['vBillingInterval'];
                                $serItemArr['nBillingDuration'];
                                $bStartDate = $bStopDate = $bNextDate = $planType = $amountNext= $serDiscount = NULL;
                                $planPrice = $serItemArr['price'];
                                switch($serItemArr['vBillingInterval']) {
                                    case 'M':
                                    // recurring
                                        $addDays = NULL;
                                        if($serItemArr['nBillingDuration'] ==1) {
                                            $addDays = " +".$serItemArr['nBillingDuration']." day";
                                        }else if($serItemArr['nBillingDuration'] > 1) {
                                            $addDays = " +".$serItemArr['nBillingDuration']." days";
                                        }
                                        $bStartDate = date("Y-m-d");
                                        $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addDays);
                                        $bStopDate = date("Y-m-d", $bStopDate);
                                        //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                                        //$bNextDate = date("Y-m-d", $bNextDate);
                                        $bNextDate = $bStopDate;
                                        $planType = 'recurring';
                                        $amountNext = $serItemArr['price'];
                                        break;
                                    case 'Y':
                                        $addYear = NULL;
                                        $addYear = " +".$serItemArr['nBillingDuration']." years";
                                        $bStartDate = date("Y-m-d");
                                        $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addYear);
                                        $bStopDate = date("Y-m-d", $bStopDate);
                                        //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                                        //$bNextDate = date("Y-m-d", $bNextDate);
                                        $bNextDate = $bStopDate;
                                        $planType = 'recurring';
                                        $amountNext = $serItemArr['price'];
                                        break;
                                    case 'L':
                                    // one-time;
                                        $planType = 'one time';
                                        break;
                                } // End Switch

                                // Specials
                                if(!empty($specialsArr)) {
                                    $specialCost = NULL;
                                    $specialCostBill = NULL;
                                    $specialsBillArr = array();
                                    foreach($specialsArr as $itemSpecial) {

                                        switch($itemSpecial->capture) {
                                            case 'recurring':
                                                $specialCostBill +=$itemSpecial->cost;
                                                $specialsBillArr[]=$itemSpecial;
                                                break;
                                            case 'one-time':
                                            //  only one time capture. will never have an entry for next bill
                                                break;
                                        }
                                        $specialCost += $itemSpecial->cost;

                                    }
                                    $specials = json_encode($specialsArr);
                                    if(count($specialsBillArr) > 0) {
                                        $specialsBill = json_encode($specialsBillArr);
                                    }

                                    $totalAmount += $specialCost;

                                }
                                // End Specials

                                $dataServiceArr[] = array(
                                                        'nUId'              => $userID,
                                                        'nServiceId'        => $serviceID,
                                                        'nSpecialCost'      => $specialCost,
                                                        'vSpecials'         => $specials,
                                                        'nAmount'           => $serItemArr['price'],
                                                        'nAmtNext'          => ($amountNext + $specialCostBill),
                                                        'vType'             => $planType,
                                                        'vBillingInterval'  => $serItemArr['vBillingInterval'],
                                                        'nDiscount'         => $serDiscount,
                                                        'dDateStart'        => $bStartDate,
                                                        'dDateStop'         => $bStopDate,
                                                        'dDateNextBill'     => $bNextDate
                                                    );
                                // Billing Main Data
                                if($planType=='recurring') {
                                    // Recurring
                                    //nSCatId - will be filled only for domain registration case
                                    $dataBillingArr [] = array(
                                                            'nBmId'             => $billMainID,
                                                            'nUId'              => $userID,
                                                            'nServiceId'        => $serviceID,
                                                            'vDomain'           => NULL,
                                                            'vSpecials'         => $specialsBill,
                                                            'nSpecialCost'      => $specialCostBill,
                                                            'nDiscount'         => $serDiscount,
                                                            'nAmount'           => $serItemArr['price'],
                                                            'vType'             => $planType,
                                                            'vBillingInterval'  => $serItemArr['vBillingInterval'],
                                                            'dDateStart'        => $bStartDate,
                                                            'dDateStop'         => $bStopDate,
                                                            'dDateNextBill'     => $bNextDate,
                                                            'dDatePurchase'     => $bStartDate,
                                                            'vDelStatus'        => '0'
                                                            );

                                } // End Billing Main Data

                            } // End Valid Service

                        } // End Service Loop

                      } // // End Bill Loop for service plan

                    } // End Service plan billing

                    // Service Plan Billing Area End

                }
                // end bill generation for store



                        // *****************************Generate Invoice Area

                        // *****************************Payment Area
                        $grandTotal = $totalAmount - $discount;

                        $payArr1['desc'] = $invDescription;
                        $payArr1['amount'] = $grandTotal;
                        $payDataArr = array_merge($payArr1, $userInfoArr);
                        //echo '<pre>'; print_r($payDataArr); echo '</pre>';
                        //$payArr = array('paymentSuccessful' => false, 'paymentError' => NULL, 'transactionId' => NULL);


                            if(!empty($payArr)) {
                                // $payArr['paymentSuccessful'];
                                // $payArr['paymentError'];
                                // $payArr['transactionId'];

                                if($payArr['paymentSuccessful']) {
                                    $vSubscriptionType = 'PAID';
                                    $paymentDate = date('Y-m-d H:i:s');
                                    $paymentMethod = 'PP';
                                    $paymentFlag = 1;
                                    $transactionID = $payArr['transactionId'];

                                } else {

                                    $errorMsg .= '<br/>'.'Payment Failure -'.$payArr['paymentError'];
                                    if($grandTotal >= 0) {
                                        $errorMsg .= '<br/>'.'Amount To Pay -'.CURRENCY_SYMBOL.' '.Utils::formatPrice($grandTotal);
                                    }
                                }
                            }


                        //... Domain renewal
                        if($paymentFlag) {
                            if(!empty($invDomainGroupArr[$productLookUpID])){
                                $domainRenewalStatus = Admincomponents::doDomainRenewal($productLookUpID);

                                if($domainRenewalStatus){
                                    //... domain renewal successfully completed
                                    //... log domain renewal as Domain Renewed
                                    Admincomponents::logDomainRenewal($productLookUpID, 1,'',$dataDomainArr['dDateStop']);

                                } else {
                                    //... domain renewal failed
                                    $domainRenewalNotificationFlag = true;
                                    $domainRenewalNotificationMsg = '<br/>'.'Domain renewal failed due to some technical issues. Please feel free to contact '.$contactMailID.' for support.';
                                    $errorMsg = $domainRenewalNotificationMsg;
                                    // Admin Notification on Failed Domain Renewal
                                    Cronhelper::generateDomainRenewalFailureNotification($productLookUpID, $userID, $errorMsg);
                                    //... log domain renewal as Payment Received, Domain Renewal Failed
                                    Admincomponents::logDomainRenewal($productLookUpID, 0,'',$dataDomainArr['dDateStop']);

                                }
                            }
                        }
                        //... End Domain renewal

                        // ... Process last Cron Attempt
                        if($attempt==1) {

                            //.. if this is last (2nd) attempt generate invoice as due
                            $paymentFlag = 1;
                            $vSubscriptionType = "DUE"; //If subscription status is DUE
                            if(!empty($invDomainGroupArr[$productLookUpID])){
                                    //... log domain renewal as Payment Due
                                    Admincomponents::logDomainRenewal($productLookUpID, 2,'',$dataDomainArr['dDateStop']);
                                } // End If

                        }



                        if($paymentFlag){
                            echo "1) nPLId = ".$productLookUpID;
                            echo "<br>";
                            
                            //******************** Generate Invoice  - Table Updates
                            $invQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Invoice SET nInvId=NULL, nUId='".$userID."', nPLId = '".$productLookUpID."', dGeneratedDate = NOW(),
                                    dDueDate = NOW(), nAmount='".$totalAmount."', nDiscount = '".$discount."', nTotal ='".$grandTotal."',
                                        vCouponNumber = NULL, vTerms ='".$invTerms."', vNotes = '".$invNotes."',
                                            vSubscriptionType = '".$vSubscriptionType."', vMethod='".$paymentMethod."', vTxnId = '".$transactionID."', dPayment='".$paymentDate."'";

                            Admincomponents::$dbObj->execute($invQry);
                            $invoiceIDN = Admincomponents::$dbObj->lastInsertId();

                            // Update Invoice Number
                            $invUpdateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Invoice SET vInvNo='".$invoiceIDN."' WHERE nInvId='".$invoiceIDN."'";
                            Admincomponents::$dbObj->execute($invUpdateQry);

                            // End Invoice Creation

                            // ****************** Invoice Plan Creation *************************

                            // Plan Creation Against Domain
                            if(count($dataDomainArr) > 0) {
                                //echo "4)nPLId = ";
                                //echo "<br>";
                                //print_r($dataDomainArr);
                                
                                $invPlanDQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."InvoiceDomain SET nIDId=NULL, nUId='".$dataDomainArr['nUId']."',
                                            nInvId='".$invoiceIDN."', nPLId='".$dataDomainArr['nPLId']."', vDescription='".$dataDomainArr['vDescription']."', nAmount='".$dataDomainArr['nAmount']."', nAmtNext='".$dataDomainArr['nAmtNext']."', vType='".$dataDomainArr['vType']."', vBillingInterval='".$dataDomainArr['vBillingInterval']."', nBillingDuration = '".$dataDomainArr['nBillingDuration']."', nRate='".$dataDomainArr['nRate']."',
                                            nDiscount='".$dataDomainArr['nDiscount']."', dDateStart='".$dataDomainArr['dDateStart']."', dDateStop='".$dataDomainArr['dDateStop']."', dDateNextBill='".$dataDomainArr['dDateNextBill']."', dCreatedOn=NOW(), nPlanStatus='1'";
                                Admincomponents::$dbObj->execute($invPlanDQry);
                            }// End If
                            // End Plan Creation Against Domain

                            // Plan Creation Against Service
                            if(!empty($dataServiceArr)) {
                                foreach($dataServiceArr as $itemSP) {
                                    $invSerQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."InvoicePlan SET nIPId=NULL, nUId='".$itemSP['nUId']."',
                               nInvId='".$invoiceIDN."', nServiceId='".$itemSP['nServiceId']."', nSpecialCost='".$itemSP['nSpecialCost']."', vSpecials='".$itemSP['vSpecials']."', nAmount='".$itemSP['nAmount']."', nAmtNext='".$itemSP['nAmtNext']."', vType='".$itemSP['vType']."', vBillingInterval='".$itemSP['vBillingInterval']."', nBillingDuration ='".$itemSP['nBillingDuration']."',
                               nDiscount='".$itemSP['nDiscount']."', dDateStart='".$itemSP['dDateStart']."', dDateStop='".$itemSP['dDateStop']."', dDateNextBill='".$itemSP['dDateNextBill']."', dCreatedOn=NOW(), nPlanStatus='1'";
                                    Admincomponents::$dbObj->execute($invSerQry);
                                } //End Foreach
                            } // End If Service
                            // End Plan Creation Against Service

                            // ****************** Invoice Plan Creation End *********************

                            // ****************** Billing Main Entry ****************************
                            //Domain Renewal Entry
                             if(!empty($dataDomainBillingArr)) {
                                foreach($dataDomainBillingArr as $itemBill) {
                                    $invBillQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET nUId='".$itemBill['nUId']."',
                                nServiceId='".$itemBill['nServiceId']."', vInvNo='".$invoiceIDN."', vDomain='".$itemBill['vDomain']."', nDiscount='".$itemBill['nDiscount']."', nAmount='".$itemBill['nAmount']."', nSpecialCost= '".$itemBill['nSpecialCost']."', vSpecials ='".$itemBill['vSpecials']."', vType='".$itemBill['vType']."', vBillingInterval='".$itemBill['vBillingInterval']."', nBillingDuration ='".$itemBill['nBillingDuration']."',
                                dDateStart='".$itemBill['dDateStart']."', dDateStop='".$itemBill['dDateStop']."', dDateNextBill='".$itemBill['dDateNextBill']."', dDatePurchase='".$itemBill['dDatePurchase']."', cronAttempt=NULL, vDelStatus='".$itemBill['vDelStatus']."' WHERE nBmId='".$itemBill['nBmId']."'"; //
                                    Admincomponents::$dbObj->execute($invBillQry);

                                } // End Foreach
                            } // End If
                            //Domain Renewal Entry Ends

                            if(!empty($dataBillingArr)) {
                                foreach($dataBillingArr as $itemBill) {
                                    $invBillQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET nUId='".$itemBill['nUId']."',
                                nServiceId='".$itemBill['nServiceId']."', vInvNo='".$invoiceIDN."', vDomain='".$itemBill['vDomain']."', nDiscount='".$itemBill['nDiscount']."', nAmount='".$itemBill['nAmount']."', nSpecialCost= '".$itemBill['nSpecialCost']."', vSpecials ='".$itemBill['vSpecials']."', vType='".$itemBill['vType']."', vBillingInterval='".$itemBill['vBillingInterval']."', nBillingDuration ='".$itemBill['nBillingDuration']."',
                                dDateStart='".$itemBill['dDateStart']."', dDateStop='".$itemBill['dDateStop']."', dDateNextBill='".$itemBill['dDateNextBill']."', dDatePurchase='".$itemBill['dDatePurchase']."', cronAttempt=NULL, vDelStatus='".$itemBill['vDelStatus']."' WHERE nBmId='".$itemBill['nBmId']."'"; //
                                    Admincomponents::$dbObj->execute($invBillQry);

                                } // End Foreach
                            } // End If
                            // ****************** Billing Main Entry End ************************

                            // User Details

                            // Notify admin if it is last attempt
                            if($attempt==1) {
                                if ($invCronArr[$productLookUpID] == 3) {
                                    if (!empty($errorMsg)) {
                                        $mailMsg .= "<br />" . "<br />" . "Payment could not be processed further for the following reason(s)<br/>" . $errorMsg;
                                        // $invoiceIDN - Invoice ID
                                        // INVOICE_PREFIX.$invoiceIDN - Invoice No
                                        // $errorMsg
                                        // Admin Notification on Payment Failure
                                        Cronhelper::generateBillFailureNotification($invoiceIDN, $invoiceIDN, $errorMsg);
                                    }
                                }
                            }// End notification for last attempt
                            //
                            //
                            // Send Invoice Mail
                            Admincomponents::sendInvoiceMail($invoiceIDN, $mailMsg);

                            //******************** Generate Invoice  - Table Updates End
                        } else {
                            // ... service plan entries
                            // ... Domain Plan Entries
                            if(!empty($invDomainGroupArr[$productLookUpID])){
                                foreach($invDomainGroupArr[$productLookUpID] as $invoiceID => $servicesArr) {
                                    foreach($servicesArr as $billMainID => $productLookUpID) {
                                        $itemUpdateQry = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "BillingMain SET cronAttempt= CASE WHEN (cronAttempt=3) THEN NULL ELSE IFNULL(cronAttempt, 0) + 1 END WHERE nBmId='" . $billMainID . "'";
                                        Cronhelper::$dbObj->execute($itemUpdateQry);
                                    }
                                }
                            }

                            // ... End Domain Plan Entries
                            if(!empty($invGroupArr[$productLookUpID])) {
                                foreach($invGroupArr[$productLookUpID] as $invoiceID => $servicesArr) { // Bill Loop Start
                                    // Update cron Attempt in BILL
                                    foreach($servicesArr as $billMainID => $serviceID) {
                                        //$itemUpdateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET cronAttempt=IFNULL(cronAttempt, 0) + 1 WHERE nBmId='".$billMainID."'";
                                        $itemUpdateQry = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "BillingMain SET cronAttempt= CASE WHEN (cronAttempt=3) THEN NULL ELSE IFNULL(cronAttempt, 0) + 1 END WHERE nBmId='" . $billMainID . "'";
                                        Cronhelper::$dbObj->execute($itemUpdateQry);
                                    } // End Foreach
                                } // End Bill Loop
                            } // End If

                        }


                        // *****************************Payment Area End

                        // *****************************Generate Invoice Area End

                 } // End if Billing Arr

        } // End if Data Arr

    } // End Function


   public static function generateBillSubscription($dataArr = array(), $payArr = array()){
        Cronhelper::$dbObj = new Db();
        if(!empty($dataArr)) {
             // $dataArr must be the IPN response
             /************ Expected Results in Response Array **********/
             //$responseArr["error"] = ""; // returns the error message if there is any error
             //$responseArr["status"] = ""; // returns the status of the payment 1 => success, 0 => failure
             //$responseArr["data"] = ""; // returns the post data with key and value
             /*********************************************************/

            /*
             Response Data
             EXPECTED SET OF RESULTS WOULD BE
             $dataArr = array(
                    "business" => $paypal_email,
                    "item_name" => "Baseball Hat Monthly",
                    "item_number" => "12",
                    "rm" => "2",
                    "image_url" => BASE_URL."/logo.gif",
                    "no_shipping" => "",
                    "notify_url" => BASE_URL."/paypal-ipn-handler.php",
                    "return" => BASE_URL."/thankyou.php",
                    "cancel_return" => BASE_URL."/cancel.php",
                    "a1" => "10.00",
                    "p1" => "30",
                    "t1" => "D",
                    "a3" => "15.00",
                    "p3" => "30",
                    "t3" => "D",
                    "src" => "1",
                    "sra" => "1",
                    "no_note" => "1",
                    "currency_code" => "GBP",
                    "modify" => "0",
                    "subscr_date" => "2013-06-04",
                    "custom" => "2-12"); // user_id-lookop_id
             */

        $responseArr = $dataArr["data"];

        //Transaction Session
        $transactionSession = $responseArr["item_number"];

        //mapTransactionSessionWithBill
        $billArr = Admincomponents::mapTransactionSessionWithBill($transactionSession);
        if(!empty($billArr)){
            foreach($billArr as $billItem){
                $billMainID =  NULL;

                if($billItem->vDomain != 1){ // Exclude recurring billing for domain
                     // for the time being domain renewal is not considered in PayPal Subscription with IPN
                    $billMainID = $billItem->nBmId;
                }

            }
        }
        


        // Domain Billing Area - Excluded Domain for the time being

        // Domain Billing Area End

        if(!empty($billMainID)) {

                $dataServiceArr = array();
                $dataBillingArr = array();
                $totalAmount    = 0;
                $discount       = 0;
                $grandTotal     = 0;
                $mailMsg        = $errorMsg = NULL;
                $invDescription = $planLookupID = $userID = $invTerms = $invNotes = NULL;
                $serCnt         = 0;
                $walletBalance  = $walletDiscount = $walletNewBalance = 0;
                $paymentFlag    = 0;
                $paymentMethod  = $paymentDate = $transactionID = NULL;
                ++$serCnt;

                    // Billing Item
                    $billItem = Admincomponents::getListItem("BillingMain", array('nBmId', 'nUId', 'nServiceId', 'vInvNo', 'vDomain', 'nDiscount', 'nAmount', 'nSpecialCost', 'vSpecials', 'vType', 'vBillingInterval', 'dDateStart', 'dDateStop', 'dDateNextBill', 'dDatePurchase', 'vDelStatus','cronAttempt'), array(array('field' => 'nBmId', 'value' => $billMainID)));
                    Logger::info($billItem);

                    $invoiceID = $billItem[0]->vInvNo;
                    $serviceID = $billItem[0]->nServiceId;
                    $billItem[0]->cronAttempt;
                    $attempt= ($billItem[0]->cronAttempt == 1) ? 1 : 0;

                    // Service Info / Checks whether current service plan is active or not
                    $serItemArr = Cronhelper::serviceInfo($serviceID, 'purchase', $invoiceID);

                    Logger::info($serItemArr);

                    // Service Info


                    // Specials
                    $specials = NULL;
                    $specialsBill=NULL;
                    $specialsArr = array();
                    if(isset($billItem[0]->vSpecials) && !empty($billItem[0]->vSpecials)) { // vSpecials
                        // Specials
                        $specialsArr = json_decode($billItem[0]->vSpecials);
                    }
                    /*
                    echo 'BILLING ITEM -- <pre>';
                    print_r($billItem);
                    echo '</pre>';

                    */
                    // Billing Item

                    //

                    // Invoice Detail Item
                    $invDtItem = Admincomponents::getInvoiceDetails($invoiceID, array(array('field' => 'ip.nServiceId', value => $serviceID)));
                    Logger::info($invDtItem);
                    /*
                    echo 'INVOICE DETAIL ITEM <pre>';
                    print_r($invDtItem);
                    echo '</pre>';
                    */
                    // Invoice Detail Item

                    // pl.vSubDomain, pl.nSubDomainStatus, pl.vDomain, pl.nDomainStatus,

                    // Service Summary as payment title
                    $storeHost = NULL;
                    $storeHost = Admincomponents::getStoreHost($invDtItem[0]->nPLId);
                    $invDescription .= ($serCnt==1) ? $storeHost.' - '.$invDtItem[0]->vServiceName : ' - '.$invDtItem[0]->vServiceName;
                    // Plan Look Up ID
                    $planLookupID = $invDtItem[0]->nPLId;
                    $userID = $invDtItem[0]->nUId;
                    $invTerms =$invDtItem[0]->vTerms;
                    $invNotes = $invDtItem[0]->vNotes;

                    if(!empty($serItemArr)) {
                        $totalAmount += $serItemArr['price'];
                        $productSpanArr['productBillingInterval']; // productBillingInterval
                        $productSpanArr['productBillingDuration']; // productBillingDuration
                        $serItemArr['vBillingInterval'];
                        $serItemArr['nBillingDuration'];
                        $bStartDate = $bStopDate = $bNextDate = $planType = $amountNext= $serDiscount = NULL;
                        $planPrice = $serItemArr['price'];
                        switch($serItemArr['vBillingInterval']) {
                            case 'M':
                            // recurring
                                $addDays = NULL;
                                if($serItemArr['nBillingDuration'] ==1) {
                                    $addDays = " +".$serItemArr['nBillingDuration']." day";
                                }else if($serItemArr['nBillingDuration'] > 1) {
                                    $addDays = " +".$serItemArr['nBillingDuration']." days";
                                }
                                $bStartDate = $billItem[0]->dDateNextBill;
                                $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addDays);
                                $bStopDate = date("Y-m-d", $bStopDate);
                                //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                                //$bNextDate = date("Y-m-d", $bNextDate);
                                $bNextDate = $bStopDate;
                                $planType = 'recurring';
                                $amountNext = $serItemArr['price'];


                                break;
                            case 'Y':

                                $addYear = NULL;
                                $addYear = " +".$serItemArr['nBillingDuration']." years";
                                $bStartDate = $billItem[0]->dDateNextBill;
                                $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addYear);
                                $bStopDate = date("Y-m-d", $bStopDate);
                                //$bNextDate = strtotime(date("Y-m-d", strtotime($bStopDate)) . " +1 day");
                                //$bNextDate = date("Y-m-d", $bNextDate);
                                $bNextDate = $bStopDate;
                                $planType = 'recurring';
                                $amountNext = $serItemArr['price'];

                                break;
                            case 'L':
                            // one-time;
                                $planType = 'one time';
                                break;
                        } // End Switch

                        // Specials
                        if(!empty($specialsArr)) {
                            $specialCost = NULL;
                            $specialCostBill = NULL;
                            $specialsBillArr = array();
                            foreach($specialsArr as $itemSpecial) {

                                switch($itemSpecial->capture) {
                                    case 'recurring':
                                        $specialCostBill +=$itemSpecial->cost;
                                        $specialsBillArr[]=$itemSpecial;
                                        break;
                                    case 'one-time':
                                    // captured only one time. will never get entered into next bill
                                        break;
                                }
                                $specialCost += $itemSpecial->cost;

                            }
                            $specials = json_encode($specialsArr);
                            if(count($specialsBillArr) > 0) {
                                $specialsBill = json_encode($specialsBillArr);
                            }

                            $totalAmount += $specialCost;
                        }

                        // End Specials

                        $dataServiceArr[] = array('nUId'=> $userID,
                                'nServiceId'=>$serviceID,
                                'nSpecialCost'=>$specialCost,
                                'vSpecials'=>$specials,
                                'nAmount' => $serItemArr['price'],
                                'nAmtNext' => ($amountNext + $specialCostBill),
                                'vType'=>$planType,
                                'vBillingInterval' => $serItemArr['vBillingInterval'],
                                'nBillingDuration' => $serItemArr['nBillingDuration'],
                                'nDiscount' => $serDiscount,
                                'dDateStart'=>$bStartDate,
                                'dDateStop'=>$bStopDate,
                                'dDateNextBill'=>$bNextDate);

                        // Billing Main Data
                        if($planType=='recurring') {
                            // Recurring
                            //nSCatId - will be filled only for domain registration case
                            $dataBillingArr [] = array('nBmId'=> $billMainID,
                                    'nUId' => $userID,
                                    'nServiceId' => $serviceID,
                                    'vDomain' => NULL,
                                    'vSpecials'=>$specialsBill,
                                    'nSpecialCost'=>$specialCostBill,
                                    'nDiscount' => $serDiscount,
                                    'nAmount' => $serItemArr['price'],
                                    'vType' => $planType,
                                    'vBillingInterval' => $serItemArr['vBillingInterval'],
                                    'nBillingDuration' => $serItemArr['nBillingDuration'],
                                    'dDateStart' => $bStartDate,
                                    'dDateStop' => $bStopDate,
                                    'dDateNextBill' => $bNextDate,
                                    'dDatePurchase' => $bStartDate,
                                    'vDelStatus' => '0');

                        } // End Billing Main Data

                    } // End Valid Service



                // *****************************Generate Invoice Area

                // *****************************Payment Area
                $grandTotal = $totalAmount - $discount;
                $userInfoArr = Cronhelper::userInfoFilter($userID);
                $payArr1['desc'] = $invDescription;
                $payArr1['amount'] = $grandTotal;
                $payDataArr = array_merge($payArr1, $userInfoArr);
                //echo '<pre>'; print_r($payDataArr); echo '</pre>';
                //$payArr = array('paymentSuccessful' => false, 'paymentError' => NULL, 'transactionId' => NULL);


                    if(!empty($payArr)) {
                        // $payArr['paymentSuccessful'];
                        // $payArr['paymentError'];
                        // $payArr['transactionId'];

                        if($payArr['paymentSuccessful']) {
                            $vSubscriptionType = 'PAID';
                            $paymentDate = date('Y-m-d H:i:s');
                            $paymentMethod = 'PP';
                            $paymentFlag = 1;
                            $transactionID = $payArr['transactionId'];
                        } else {

                            $errorMsg .= '<br/>'.'Payment Failure -'.$payArr['paymentError'];
                            if($grandTotal >= 0) {
                                $errorMsg .= '<br/>'.'Amount To Pay -'.CURRENCY_SYMBOL.' '.Utils::formatPrice($grandTotal);
                            }
                        }
                    }




                // ... Process last Cron Attempt
                if($attempt==1) {

                        //.. if this is last (2nd) attempt generate invoice as due
                        $paymentFlag = 1;

                }

                if($paymentFlag) {

                    //******************** Generate Invoice  - Table Updates
echo "5)nPLId =".$planLookupID;
echo "<br>";

                    $invQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."Invoice SET nInvId=NULL, nUId='".$userID."', nPLId = '".$planLookupID."', dGeneratedDate = NOW(),
                            dDueDate = NOW(), nAmount='".$totalAmount."', nDiscount = '".$discount."', nTotal ='".$grandTotal."',
                                vCouponNumber = NULL, vTerms ='".$invTerms."', vNotes = '".$invNotes."',
                                    vSubscriptionType = '".$vSubscriptionType."', vMethod='".$paymentMethod."', vTxnId = '".$transactionID."', dPayment='".$paymentDate."'";

                    Admincomponents::$dbObj->execute($invQry);
                    $invoiceIDN = Admincomponents::$dbObj->lastInsertId();

                    // Update Invoice Number
                    $invUpdateQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."Invoice SET vInvNo='".$invoiceIDN."' WHERE nInvId='".$invoiceIDN."'";
                    Admincomponents::$dbObj->execute($invUpdateQry);

                    // End Invoice Creation

                    // ****************** Invoice Plan Creation *************************

                    // Plan Creation Against Service
                    if(!empty($dataServiceArr)) {
                        foreach($dataServiceArr as $itemSP) {
                            $invSerQry = "INSERT INTO ".Admincomponents::$dbObj->tablePrefix."InvoicePlan SET nIPId=NULL, nUId='".$itemSP['nUId']."',
                       nInvId='".$invoiceIDN."', nServiceId='".$itemSP['nServiceId']."', nSpecialCost='".$itemSP['nSpecialCost']."', vSpecials='".$itemSP['vSpecials']."', nAmount='".$itemSP['nAmount']."', nAmtNext='".$itemSP['nAmtNext']."', vType='".$itemSP['vType']."', vBillingInterval='".$itemSP['vBillingInterval']."', nBillingDuration ='".$itemSP['nBillingDuration']."',
                       nDiscount='".$itemSP['nDiscount']."', dDateStart='".$itemSP['dDateStart']."', dDateStop='".$itemSP['dDateStop']."', dDateNextBill='".$itemSP['dDateNextBill']."', dCreatedOn=NOW(), nPlanStatus='1'";
                            Admincomponents::$dbObj->execute($invSerQry);
                        } //End Foreach
                    } // End If Service
                    // End Plan Creation Against Service

                    // ****************** Invoice Plan Creation End *********************

                    // ****************** Billing Main Entry ****************************

                    if(!empty($dataBillingArr)) {
                        foreach($dataBillingArr as $itemBill) {
                            $invBillQry = "UPDATE ".Admincomponents::$dbObj->tablePrefix."BillingMain SET nUId='".$itemBill['nUId']."',
                        nServiceId='".$itemBill['nServiceId']."', vInvNo='".$invoiceIDN."', vDomain='".$itemBill['vDomain']."', nDiscount='".$itemBill['nDiscount']."', nAmount='".$itemBill['nAmount']."', nSpecialCost= '".$itemBill['nSpecialCost']."', vSpecials ='".$itemBill['vSpecials']."', vType='".$itemBill['vType']."', vBillingInterval='".$itemBill['vBillingInterval']."', nBillingDuration ='".$itemBill['nBillingDuration']."',
                        dDateStart='".$itemBill['dDateStart']."', dDateStop='".$itemBill['dDateStop']."', dDateNextBill='".$itemBill['dDateNextBill']."', dDatePurchase='".$itemBill['dDatePurchase']."', cronAttempt=NULL, vDelStatus='".$itemBill['vDelStatus']."' WHERE nBmId='".$itemBill['nBmId']."'"; //
                            Admincomponents::$dbObj->execute($invBillQry);

                        } // End Foreach
                    } // End If
                    // ****************** Billing Main Entry End ************************

                    // User Details

                    // Notify admin if it is last attempt
                    if($attempt==1) {
                            if (!empty($errorMsg)) {
                                $mailMsg .= "<br />" . "<br />" . "Payment could not be processed further for the following reason(s)<br/>" . $errorMsg;
                                // $invoiceIDN - Invoice ID
                                // INVOICE_PREFIX.$invoiceIDN - Invoice No
                                // $errorMsg
                                // Admin Notification on Payment Failure
                                Cronhelper::generateBillFailureNotification($invoiceIDN, $invoiceIDN, $errorMsg);
                            }
                    }// End notification for last attempt
                    //
                    //
                    // Send Invoice Mail
                    Admincomponents::sendInvoiceMail($invoiceIDN, $mailMsg);

                    //******************** Generate Invoice  - Table Updates End
                } else {
                    // Update cron Attempt in BILL
                        $itemUpdateQry = "UPDATE " . Admincomponents::$dbObj->tablePrefix . "BillingMain SET cronAttempt= CASE WHEN (cronAttempt=1) THEN NULL ELSE IFNULL(cronAttempt, 0) + 1 END WHERE nBmId='" . $billMainID . "'";
                        Cronhelper::$dbObj->execute($itemUpdateQry);

                }


                // *****************************Payment Area End

                // *****************************Generate Invoice Area End






        } // End If

        } // End $dataarr check

    } // End Function

    public static function generateAccountSuspensionFailureNotification($productLookUpID, $errors) {

        //TODO : This method generates account suspension failure notification to the site Administrator

        Cronhelper::$dbObj = new Db();

        $adminEmail = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='adminEmail'");

        $storeHost = Admincomponents::getStoreHost($productLookUpID);

        $userName = Admincomponents::getUserwithProductLookupID($productLookUpID);

        $mailMsg .= "<html>";

        $mailMsg .= "<head></head>";

        $mailMsg .= "<body>";

        $mailMsg .= "Dear Administrator,<br/>

                    Account Suspension Failure on Account: ".$storeHost." [User - ".$userName."]";

       if(!empty($errors)){
           $mailMsg .= "<br />".$errors;
       }

        $mailMsg .= "<br />"."<br />Support Team";

        $mailMsg .= "</body>";

        $mailMsg .= "</html>";

        $subject = "Account Suspension Failure on Account :".$storeHost." from ".SITE_NAME;

        $mailMsg = Utils::bindEmailTemplate($mailMsg);



        //PageContext::includePath('email');

       // $emailObj    = new Emailsend();

        $emailData   = array("from"		=> COMPANY_EMAIL,

                "subject"	=> $subject,

                "message"	=> $mailMsg,

                "to"           => $adminEmail);

       // $emailObj->email_senderNow($emailData);
        Mailer::sendSmtpMail($emailData);

    } // End Function

    public static function generateUserSubscriptionCancellationNotification($productLookUpID, $errors) {

        //TODO : This method generates subscription cancellation notification to the user

        Cronhelper::$dbObj = new Db();

        $adminEmail = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='adminEmail'");

        $storeHost = Admincomponents::getStoreHost($productLookUpID);

        $userName = Admincomponents::getUserwithProductLookupID($productLookUpID);

        $userEmail = Admincomponents::getUserEmailwithProductLookupID($productLookUpID);



        $mailMsg .= "<html>";

        $mailMsg .= "<head></head>";

        $mailMsg .= "<body>";

        $mailMsg .= "Dear $userName,<br/>

                    Your Subscription for Account: ".$storeHost." in ".SITE_NAME." has been cancelled. Your recurring billing has also discontinued.";

        if(!empty($errors)){
           $mailMsg .= "<br />".$errors;
       }


        $mailMsg .= "<br />"."<br />Support Team";

        $mailMsg .= "</body>";

        $mailMsg .= "</html>";

        $subject = "Cancelled subscription on Account :".$storeHost." from ".SITE_NAME;

        $mailMsg = Utils::bindEmailTemplate($mailMsg);



       // PageContext::includePath('email');

       // $emailObj    = new Emailsend();

        $emailData   = array("from"		=> ADMIN_EMAILS,

                "subject"	=> $subject,

                "message"	=> $mailMsg,

                "to"           => $userEmail);

       // $emailObj->email_senderNow($emailData);
        Mailer::sendSmtpMail($emailData);



    } // End Function

    public static function generateAdministratorSubscriptionCancellationNotification($productLookUpID, $errors) {

        //TODO : This method generates subscription cancellation notification to the site Administrator

        Cronhelper::$dbObj = new Db();

        $adminEmail = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='adminEmail'");

        $storeHost = Admincomponents::getStoreHost($productLookUpID);

        $userName = Admincomponents::getUserwithProductLookupID($productLookUpID);

        $userEmail = Admincomponents::getUserEmailwithProductLookupID($productLookUpID);



        $mailMsg .= "<html>";

        $mailMsg .= "<head></head>";

        $mailMsg .= "<body>";

        $mailMsg .= "Dear Administrator,<br/>

                    Subscription for Account: ".$storeHost." [User - ".$userName."] in ".SITE_NAME." has been cancelled. The recurring billing has also discontinued.";

        if(!empty($errors)){
           $mailMsg .= "<br />".$errors;
       }


        $mailMsg .= "<br />"."<br />Support Team";

        $mailMsg .= "</body>";

        $mailMsg .= "</html>";

        $subject = "Cancelled subscription on Account :".$storeHost." from ".SITE_NAME;

        $mailMsg = Utils::bindEmailTemplate($mailMsg);



        //PageContext::includePath('email');

        //$emailObj    = new Emailsend();

        $emailData   = array("from"		=> COMPANY_EMAIL,

                "subject"	=> $subject,

                "message"	=> $mailMsg,

                "to"           => $adminEmail);

        //$emailObj->email_senderNow($emailData);
        Mailer::sendSmtpMail($emailData);

    } // End Function


 public static function generateSubscriptionEndOfTermNotification($productLookUpID, $sendTo='admin', $errors) {

        //TODO : This method generates subscription end of term notification
        Cronhelper::$dbObj = new Db();
        $adminEmail     = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='adminEmail'");
        $storeHost      = Admincomponents::getStoreHost($productLookUpID);
        $userName       = Admincomponents::getUserwithProductLookupID($productLookUpID);
        $userEmail      = Admincomponents::getUserEmailwithProductLookupID($productLookUpID);
        $mailMsg .= "<html>";
        $mailMsg .= "<head></head>";
        $mailMsg .= "<body>";
        $mailMsgContent = "Dear $userName,<br/>
                    Subscription has ended for Account: ".$storeHost.". <br/> Thank you for doing business with us!";
        if($sendTo=="admin"){
        $mailMsgContent = "Dear Administrator,<br/>
                    Subscription has ended for Account: ".$storeHost." [User - ".$userName."]";
        }
        if(!empty($errors)){
           $mailMsg .= "<br />".$errors;
        }
        $mailMsg        .= "<br />"."<br />Support Team";
        $mailMsg        .= "</body>";
        $mailMsg        .= "</html>";
        $subject        = "Subscription has ended for Account :".$storeHost." from ".SITE_NAME;
        $mailMsg        = Utils::bindEmailTemplate($mailMsg);
        $mailSender     = $adminEmail;
        $mailReceiver   = $userEmail;

        if($sendTo=="admin"){
            $mailSender = COMPANY_EMAIL;
            $mailReceiver = $adminEmail;
        }
        //PageContext::includePath('email');
        //$emailObj    = new Emailsend();
        $emailData   = array(
                            "from"		=> COMPANY_EMAIL,
                            "subject"	=> $subject,
                            "message"	=> $mailMsg,
                            "to"        => $mailReceiver
                        );
        //$emailObj->email_senderNow($emailData);
        Mailer::sendSmtpMail($emailData);
    } // End Function

    public static function groupInvoicesForDomainRenewalSubscription($billArr) {
        $dataArr = array();
        if(!empty($billArr)) {
            foreach($billArr as $itemBill) {
                $productLookUpID = $chkRenewalDate = NULL;
                if(empty($itemBill->nServiceId) && $itemBill->vDomain == 1) {

                    // Check whether Domain renewal date falls within this month
                    $chkRenewalDate = Cronhelper::checkDateFallsWithinCurrentMonth($itemBill->dDateNextBill);
                    if($chkRenewalDate==true){
                        $productLookUpID = Admincomponents::getProductLookUpIDFromInvoice($itemBill->vInvNo);
                        $dataArr[$productLookUpID][$itemBill->vInvNo][$itemBill->nBmId] = $productLookUpID;
                    }
                    
                }
            } // End Foreach
        }// End If
        return $dataArr;
    } // End Function

    public static function checkDateFallsWithinCurrentMonth($date){
        /**
         Date Format Expected  : Y-m-d
         */
        $status = false;
        if(!empty($date)) {
            //get last day of month
            $lastDayofMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));

            $monthStart = strtotime(date('Y').'-'.date('m').'-01');
            $monthEnd = strtotime(date('Y').'-'.date('m').'-'.$lastDayofMonth);

            //Input Date
            $dateCurrent = strtotime($date);

            if( $monthStart <= $dateCurrent && $dateCurrent <= $monthEnd)
            {
               //..falls within range, ie current month
               $status = true;
            }
        }

        return $status;
        
    } // End Function

    public static function doStoreTermination($productLookUpID){
        $status = false;
        $msg = NULL;
        if(!empty($productLookUpID)){
            $accountDetails     = unserialize(User::getserverDetails($productLookUpID));
            PageContext::includePath('cpanel');
            $cpanelObj          = new cpanel();
            $status  =  $cpanelObj->terminateaccount($accountDetails['c_user'],$accountDetails['c_pass'],$accountDetails['c_host']);
        } // End if
        if($status == true){
            // Suspend Recurring Billing
            User::suspendinvoice($productLookUpID);
            // Deactivate the lookup entry
            User::clearlookupentry($productLookUpID);
            // do notify user on account termination
            Cronhelper::generateAccountTerminationUserNotification($productLookUpID);
            // do notify admin on account termination
            Cronhelper::generateAccountTerminationAdministratorNotification($productLookUpID);
        } else {
            // do notify admin on account termination failure
            $msg = "Oops! There were some technical issues for terminating this account.";
            Cronhelper::generateAccountTerminationAdministratorNotification($productLookUpID, $msg);
        }
        return $status;
    }

    public static function generateAccountTerminationAdministratorNotification($productLookUpID, $errors = NULL) {

        //TODO : This method generates account termination notification to the site Administrator

        Cronhelper::$dbObj = new Db();

        $adminEmail = Cronhelper::$dbObj->selectRow("Settings","value","settingfield='adminEmail'");

        $storeHost = Admincomponents::getStoreHost($productLookUpID);

        $userName = Admincomponents::getUserwithProductLookupID($productLookUpID);

        $userEmail = Admincomponents::getUserEmailwithProductLookupID($productLookUpID);



        $mailMsg .= "<html>";

        $mailMsg .= "<head></head>";

        $mailMsg .= "<body>";

        $mailMsg .= "Dear Administrator,<br/>

                    User Account: ".$storeHost." [User - ".$userName." (".$userEmail.")] in ".SITE_NAME." has been terminated. The recurring billing has also discontinued.";

        if(!empty($errors)){
           $mailMsg .= "<br />".$errors;
       }


        $mailMsg .= "<br />"."<br />Support Team";

        $mailMsg .= "</body>";

        $mailMsg .= "</html>";

        $subject = "Termination of Account :".$storeHost." from ".SITE_NAME;

        $mailMsg = Utils::bindEmailTemplate($mailMsg);



        //PageContext::includePath('email');

       // $emailObj    = new Emailsend();

        $emailData   = array("from"		=> COMPANY_EMAIL,

                "subject"	=> $subject,

                "message"	=> $mailMsg,

                "to"           => $adminEmail);

       // $emailObj->email_senderNow($emailData);
        Mailer::sendSmtpMail($emailData);

    } // End Function

    public static function generateAccountTerminationUserNotification($productLookUpID) {        

        Admincomponents::$dbObj = new Db();
        
            // Mail Message
            $mailMsgArr = Admincomponents::getListItem("Cms", array('cms_title','cms_desc'), array(array('field' => 'LOWER(cms_name)', 'value' => strtolower('account_cancellation_notification'))));
            Logger::info($mailMsg);

            $subject = $mailMsg = NULL;

            if(!empty($mailMsgArr)) {
                $subject = $mailMsgArr[0]->cms_title;
                $mailMsg = $mailMsgArr[0]->cms_desc;
            } // End If

            // User Details
            $userName = Admincomponents::getUserwithProductLookupID($productLookUpID);
            
            $userEmail = Admincomponents::getUserEmailwithProductLookupID($productLookUpID);
            // User Details End

            // Store Host
            $storeHost = Admincomponents::getStoreHost($productLookUpID);

            $subject = str_replace("{SITE_NAME}", SITE_NAME, $subject);
            $subject = str_replace("{ACCOUNT}", $storeHost, $subject);
           
            $mailMsg = str_replace("{MEMBER_NAME}", $userName, $mailMsg);
            $mailMsg = str_replace("{SITE_NAME}", SITE_NAME, $mailMsg);
            $mailMsg = str_replace("{ACCOUNT}", $storeHost, $mailMsg);
            
            $mailMsg = Utils::bindEmailTemplate($mailMsg);

            $senderMailID = COMPANY_NAME . '<' . COMPANY_EMAIL . '>';
            $checkSenderMailID = str_replace(array("<"," ",">"), "", $senderMailID);
            $senderMailID = (empty($checkSenderMailID)) ? SITE_NAME . '<' . ADMIN_EMAILS . '>' : $senderMailID;

            //PageContext::includePath('email');

            //$emailObj    = new Emailsend();
            $emailData   = array("from" => COMPANY_EMAIL,
                    "subject"   => $subject,
                    "message"   => $mailMsg,
                    "to"        => $userEmail);
            //$emailObj->email_senderNow($emailData);
            Mailer::sendSmtpMail($emailData);
        

    } //End Function
    
    
    
    
    
    public function generateinventorySusbcription()
    {
        Cronhelper::$dbObj = new Db();
       
        
        $date= date('Y-m-d');
        
        $query =  "SELECT * FROM ".Cronhelper::$dbObj->tablePrefix."ProductLookup PLK INNER JOIN "
                . " ".Cronhelper::$dbObj->tablePrefix."inventorysource_plan INVSP ON INVSP.nPId = PLK.nPLId"
                . " WHERE INVSP.dateEnd < '$date' AND INVSP.lastCronDate != '$date' AND PLK.inventory_source_status != '2' GROUP BY INVSP.nPId ORDER BY INVSP.nInvsPid ASC LIMIT 1";
        //echo $query;
        $res = Cronhelper::$dbObj->selectQuery($query);
        //echopre($res);
        if($res)
        {
            foreach ($res as $k=>$v)
            {
                 $userDetails  = User::getUserAllDetails($v->nUId);
                    $inventory_source_amount = User::getallSettings('inventory_source_amount');
                    $inventory_source_plan_duration = User::getallSettings('inventory_source_plan_duration');
                    $storeHost = Admincomponents::getStoreHost($v->nPLId);
                    $bluedogdetails = unserialize($v->bluedogdetails);
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
                                           
                
                if($v->cronAttempt < 4) // Checking failed payment limit
                {
                    if($v->inventory_source_status == 1){// Checking if user unsubscribed service 
                   
                    $payArr1 = array();
                
                    $payArr1['desc'] = $storeHost.'-'."Inventory Source Plugin Service Renewal";
                    $payArr1['amount'] = $inventory_source_amount;
                    $payArr1['email'] = $userDetails->vEmail;
                    $payDataArr = array_merge($payArr1, $bluedogdetails);
                    $payArr=Paymenthelper::doBlueDogPayment($payDataArr); // Bluedog Payment Gateway
                    
                   
                    
                    
                    if(!empty($payArr)) {
                        // $payArr['paymentSuccessful'];
                        // $payArr['paymentError'];
                        // $payArr['transactionId'];

                        if($payArr['transactionId']!='' && $payArr['paymentSuccessful']) {
                            $vSubscriptionType = 'PAID';
                            $paymentDate = date('Y-m-d H:i:s');
                            $paymentMethod = 'CC';
                            $paymentFlag = 1;
                            $transactionID = $payArr['transactionId'];
                            
                            
                            
                           $nInvsPid =  User::updateInventorySourcePlanEntry($v->nPLId,$inventory_source_plan_duration,$v->nInvsPid,0,1);
                            
                           $nInvsInvId =  User::addInventorySourcePlanInventory($nInvsPid,$inventory_source_plan_duration,$inventory_source_amount,$transactionID);
                            
                           
                           
                           
                           
                           
                           
                           
                          
                           
                           
                           User::sendInventorySourceRenewalMail($userarray,'inventory_source_subscription_renewal');
                           
                           
                           
                           //echo $nInvsInvId; 
                            
                            
                            //$this->redirect('user/dashboard/3');

                        } else {

                            
                             $cronAttempt = $v->cronAttempt+1; 
                             $date = date('Y-m-d');
                             $query = "UPDATE  ".User::$dbObj->tablePrefix."inventorysource_plan SET cronAttempt='$cronAttempt',lastCronDate = '$date' WHERE nInvsPid =".$v->nInvsPid;
                             Cronhelper::$dbObj->customQuery($query);
                            
                            
                            $errorMsg .= '<br/>'.'Payment Failure -'.$payArr['paymentError'];
                            $userarray['ERROR'] = $errorMsg;
                            User::sendInventorySourceRenewalMail($userarray,'inventory_source_subscription_renewal_failed');
                            

                            
                        }
                    }else{
                        
                    $cronAttempt = $v->cronAttempt+1; 
                             $date = date('Y-m-d');
                             $query = "UPDATE  ".User::$dbObj->tablePrefix."inventorysource_plan SET cronAttempt='$cronAttempt',lastCronDate = '$date' WHERE nInvsPid =".$v->nInvsPid;
                             Cronhelper::$dbObj->customQuery($query);    
                        
                    $errorMsg .= '<br/>'.'Incomplete User Credit Card Credentials';
                    $userarray['ERROR'] = $errorMsg;
                    User::sendInventorySourceRenewalMail($userarray,'inventory_source_subscription_renewal_failed');
                            
                    
                 
                }
                    
                }else{ //If user un subscribed servie then
                    
                     $storeArray = array();
                           $storeArray['inventory_plugin_status'] = 0;
            $storeHost = Admincomponents::getStoreHost($v->nPLId);
             
             $url = 'https://www.' . $storeHost . '/Settings/updatesystemsettings';


            $post = ['settings_data' => json_encode($storeArray)];


            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // execute!
            $response = curl_exec($ch);

            // close the connection, release resources used
            curl_close($ch);
            
            
             $query = "UPDATE  ".User::$dbObj->tablePrefix."inventorysource_plan SET lastCronDate = '$date',nStatus = 2 WHERE nInvsPid =".$v->nInvsPid;
                             Cronhelper::$dbObj->customQuery($query);
            
                             
            $query1 = "UPDATE  ".User::$dbObj->tablePrefix."ProductLookup SET inventory_source_status = 2 WHERE nPLId =".$v->nPLId;
                             Cronhelper::$dbObj->customQuery($query1);
            
            
                    
                }  
                }else{
                    
                     $storeArray = array();
                           $storeArray['inventory_plugin_status'] = 0;
            $storeHost = Admincomponents::getStoreHost($v->nPLId);
             
             $url = 'https://www.' . $storeHost . '/Settings/updatesystemsettings';


            $post = ['settings_data' => json_encode($storeArray)];


            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // execute!
            $response = curl_exec($ch);

            // close the connection, release resources used
            curl_close($ch);
           
            $query = "UPDATE  ".User::$dbObj->tablePrefix."inventorysource_plan SET lastCronDate = '$date',nStatus = 2 WHERE nInvsPid =".$v->nInvsPid;
                             Cronhelper::$dbObj->customQuery($query);
            
                             
            $query1 = "UPDATE  ".User::$dbObj->tablePrefix."ProductLookup SET inventory_source_status = 2 WHERE nPLId =".$v->nPLId;
                             Cronhelper::$dbObj->customQuery($query1);                 
            
               User::sendInventorySourceRenewalMail($userarray,'inventory_source_subscription_renewal_cancel');     
                }
                
                
            }
        }
        
    }
    
    


} // End Class


?>