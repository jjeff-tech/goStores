<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : cls_cronhelper.php                                                |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: Meena Susan Joseph<meena.s@armiasystems.com>              	  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class Paymenthelper {

    public static $dbObj = NULL;

    public static function doAuthorizePayment($dataArr) {
        /* Example Input
        $dataArr['desc'] = '';
        $dataArr['amount'] = '';
        $dataArr['expMonth'] = '';
        $dataArr['expYear'] = '';
        $dataArr['cvv'] = '';
        $dataArr['ccno'] = '';
        $dataArr['fName'] = '';
        $dataArr['lName'] = '';
        $dataArr['add1'] = '';
        $dataArr['city'] = '';
        $dataArr['state'] = '';
        $dataArr['country'] = '';
        $dataArr['zip'] = '';
        End Example Input */

        $payArr = array();
        
        $paymentsuccessful = $paymenterror = $transactionid = NULL;

        PageContext::includePath('authorize');
        $authorizeObj   = new  Authorize_class();

        Paymenthelper::$dbObj = new Db();

        /***************************** Payment Area *******************************/

        $authorizeEnable      =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_enable'");
        $authorizeLoginId     =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_loginid'");
        $authorizeTransKey    =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_transkey'");
        $authorizeEmail       =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_email'");
        $authorizeTestMode    =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_test_mode'");
        $adminCurrency        =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");

        $authorizeInfo =array();

        $authorizeInfo['desc'] = $dataArr['desc'];
        $authorizeInfo['x_login'] = $authorizeLoginId;
        $authorizeInfo['x_tran_key'] = $authorizeTransKey;
        $authorizeInfo['email'] = $authorizeEmail;
        $authorizeInfo['testMode'] = $authorizeTestMode;
        $authorizeInfo['currency_code'] =$adminCurrency;
        $authorizeInfo['amount'] = $dataArr['amount'];
        $authorizeInfo['expMonth'] = User::decrytCreditCardDetails($dataArr['expMonth']);
        $authorizeInfo['expYear'] = User::decrytCreditCardDetails($dataArr['expYear']);
        $authorizeInfo['cvv'] = User::decrytCreditCardDetails($dataArr['cvv']);
        $authorizeInfo['ccno'] = User::decrytCreditCardDetails($dataArr['ccno']);
        $authorizeInfo['fName'] = User::decrytCreditCardDetails($dataArr['fName']);
        $authorizeInfo['lName'] = User::decrytCreditCardDetails($dataArr['lName']);
        $authorizeInfo['add1'] = User::decrytCreditCardDetails($dataArr['add1']);
        $authorizeInfo['city'] = User::decrytCreditCardDetails($dataArr['city']);
        $authorizeInfo['state'] = User::decrytCreditCardDetails($dataArr['state']);
        $authorizeInfo['country'] = User::decrytCreditCardDetails($dataArr['country']);
        $authorizeInfo['zip'] = User::decrytCreditCardDetails($dataArr['zip']);

        $return = $authorizeObj->submit_authorize_post($authorizeInfo);

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
                $paymenterror .= "<br>" . $return[3];
                $transactionid = NULL;
                break;
            case "4":
                $paymentsuccessful = false;
                $paymenterror = "The card has been held for review";
                $paymenterror .= "<br>" . $return[3];
                $transactionid = NULL;
                break;
            default: // Credit Card Not Successfully Charged
                $paymentsuccessful = false;
                $paymenterror = "Error";
                $paymenterror .= "<br>" . $return[3];
                $transactionid = NULL;
                break;
        }
        
        $payArr['paymentSuccessful'] = $paymentsuccessful;
        $payArr['paymentError'] = $paymenterror;
        $payArr['transactionId'] = $transactionid;

        /***************************** Payment Area *******************************/

        return $payArr;

    } // End Function   
    
    
    public static function doBlueDogPayment($dataArr){
        
        $payArr = array();
        
        $paymentsuccessful = $paymenterror = $transactionid = NULL;
        
        
        User::$dbObj          = new Db();

        $authorizeEnable      =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_enable'");
        $authorizeLiveApikey    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_live_apikey'");
        $authorizeSandboxApikey       =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_sandbox_apikey'");
        $authorizeTestMode    =   User::$dbObj->selectRow("Settings","value","settingfield='bluedog_test_mode'");
        $listName            = 'Test';//User::$dbObj->selectRow("product","Title","nBusId='".$listId."'");
        $adminCurrency    =   User::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");
        
        
        if($authorizeTestMode=='Y')
        {
        $apiurl = 'https://sandbox.bluedogpayments.com/api/';
        $apikey = $authorizeSandboxApikey;
        }else{
        $apiurl = 'https://app.bluedogpayments.com/api/';
        $apikey = $authorizeLiveApikey;     
        }
        
        
        
           $customer_id = $dataArr['customer_id'];
           $payment_method_id = $dataArr['payment_method_id'];
           $billing_address_id = $dataArr['billing_address_id'];
           $shipping_address_id = $dataArr['shipping_address_id'];
           $payment_method_type = $dataArr['payment_method_type'];
           
           
          
           
           //echopre1($dataArr);
           
          
           
           
           
           
            $order_id = time();
            $po_number = "po_".$order_id;
            $transactionArray = array();
            $transactionArray['type'] = 'sale';
            //$transactionArray['amount'] = $authorizeInfo['amount'] * 100;
            if($authorizeTestMode=='Y')
            {
            $transactionArray['amount'] = $dataArr['amount']*1; // for sandbox mode only need to keep the amount below $100
            }else{
            $transactionArray['amount'] = $dataArr['amount']*100; // converting amount  to cents
            }
            $transactionArray['tax_amount'] = 0;
            $transactionArray['shipping_amount'] = 0;
            $transactionArray['currency'] = 'USD';
            $transactionArray['description'] = $dataArr['desc'].' subscription from MakereadyArms';
            $transactionArray['order_id'] = "$order_id";
            $transactionArray['po_number'] = "$po_number";
            $transactionArray['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $transactionArray['email_receipt'] = true;
            $transactionArray['email_address'] = $dataArr['email'];
            $transactionArray['create_vault_record'] = true;
            $transactionArray['payment_method']['customer']['id'] = $customer_id;
            $transactionArray['payment_method']['customer']['payment_method_type'] = $payment_method_type;
            $transactionArray['payment_method']['customer']['payment_method_id'] = $payment_method_id;
            $transactionArray['payment_method']['customer']['billing_address_id'] = $billing_address_id;
            $transactionArray['payment_method']['customer']['shipping_address_id'] = $shipping_address_id;
            $payload = json_encode($transactionArray);
    
    //echo $cutomerArray;
    
    
            $url = $apiurl.'transaction';
           // echo $url;
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
           // echopre($headr);
//echopre($payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);


            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            
            
            
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            # Send request.
            $result = curl_exec($ch);
            curl_close($ch);
            
            $paymentres = json_decode($result,TRUE);
      //  echopre($paymentres);
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
            //echopre1($paymentres);
            
            
            
        $payArr['paymentSuccessful'] = $paymentsuccessful;
        $payArr['paymentError'] = $paymenterror;
        $payArr['transactionId'] = $transactionid;
        $payArr['amount'] = $transactionArray['amount'];    
        $payArr['email'] = $dataArr['email'];   
        $dataArr['payment_method'] = 'bluedog';
        return $payArr;    
        
        
    }
    
    
    

    
    public static function doPaypalProPayment($dataArr){
            //required basic input stack  - amount / fName / lName / add1 / city / zip / country /
            // required input stack  -  paymentmethod / ccno / expMonth / expYear / cvv - [particular to the payment gateway]
            Paymenthelper::$dbObj = new Db();
            $payArr = array();

            $paymentResult = array("Amount" => 0.00,
                                    "success" => 0,
                                    "Message" => "",
                                    "TransactionId" => "");

            //... get paypal pro settings / configurations
            $paypayproSettings = Payments::getPaypalproSettings();

            //... configured currency code
            $adminCurrency        =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");

            //... basic info
            $transactionInfo['Grandtotal'] = urlencode($dataArr['amount']);

            $transactionInfo['Firstname'] = urlencode($dataArr['fName']);
            $transactionInfo['Lastname'] = urlencode($dataArr['lName']);
            $transactionInfo['Street'] = urlencode($dataArr['add1']);
            $transactionInfo['City'] = urlencode($dataArr['city']);
            $transactionInfo['Zip'] = urlencode($dataArr['zip']);
            $transactionInfo['Countrycode'] = urlencode($dataArr['country']);
            $transactionInfo['Currency'] = urlencode($adminCurrency);
            //... payment gateway info
            $transactionInfo['Paypalprousername'] = $paypayproSettings['Paypalprousername']; //"mahiat_1351864475_biz_api1.yahoo.com";
            $transactionInfo['Paypalpropassword'] = $paypayproSettings['Paypalpropassword'];//"1351864518";
            $transactionInfo['Paypalprosignature'] = $paypayproSettings['Paypalprosignature'];//'A0yYhIEfABicc8vcPNDIgocAdlatAiI9tB-cYE4rdnod1VbCLzTG6fu6';
            $transactionInfo['Paymenttype'] = urlencode('Sale'); // Constant
            //... credit card info
            $transactionInfo['Creditcardtype'] = urlencode($dataArr['paymentmethod']);//urlencode('Visa');
            $transactionInfo['Creditcardnumber'] = urlencode($dataArr['ccno']);//urlencode('4055825683869610');
            $transactionInfo['Expdate'] = urlencode($dataArr['expMonth'].$dataArr['expYear']);//urlencode('112017');//mmyyyy
            $transactionInfo['Cvv2'] = urlencode($dataArr['cvv']);//urlencode('123');

            if($paypayproSettings['Paypalprotestmode'] == 'Y') {
                $transactionInfo['Testmode'] = 'Y';
            } else {
                $transactionInfo['Testmode'] = 'N';
            }

            $paymentResult = Payments::payPaypalpro($transactionInfo);
                if(isset ($paymentResult)) {
                    //... groom the transaction result in the way we require
                    $paymentResult = Payments::chkpayPaypalpro($paymentResult,$transactionInfo);

                }

            $payArr  = array("paymentSuccessful" => $paymentResult["success"],
                            "paymentError" => $paymentResult["Message"],
                            "transactionId" => $paymentResult["TransactionId"]);
            return $payArr;

    } // End Function

    
    public static function doPaypalPayFlowPayment($dataArr){
            //required basic input stack  - amount / fName / lName / add1 / city / zip / country /
            // required input stack  -  ccno / expMonth / expYear / cvv - [particular to the payment gateway]
            Paymenthelper::$dbObj = new Db();
            $payArr = array();

            $paymentResult = array("Amount" => 0.00,
                                    "success" => 0,
                                    "Message" => "",
                                    "TransactionId" => "");

            //... get paypal pay flow settings / configurations
            $paypalflowSettings = Payments::getPaypalflowSettings();

            //... configured currency code
            $adminCurrency        =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");

            //... basic info
            $transactionInfo['Grandtotal'] = urlencode($dataArr['amount']);

            $transactionInfo['Firstname'] = urlencode($dataArr['fName']);
            $transactionInfo['Lastname'] = urlencode($dataArr['lName']);
            $transactionInfo['Street'] = urlencode($dataArr['add1']);
            $transactionInfo['City'] = urlencode($dataArr['city']);
            $transactionInfo['Zip'] = urlencode($dataArr['zip']);
            $transactionInfo['Countrycode'] = urlencode($dataArr['country']);
            $transactionInfo['Currency'] = urlencode($adminCurrency);
            //... payment gateway info
            $transactionInfo['Paypalflowvendorid'] = $paypalflowSettings['Paypalflowvendorid'];//"armiapaypal";
            $transactionInfo['Paypalflowpassword'] = $paypalflowSettings['Paypalflowpassword'];//"armia247";
            $transactionInfo['Paypalflowpartnerid'] = $paypalflowSettings['Paypalflowpartnerid'];//'PayPal';
            $transactionInfo['Paymenttype'] = urlencode('S'); // Constant
            $transactionInfo['Tender'] = urlencode('C');// Constant
            //... credit card info
            $transactionInfo['Creditcardnumber'] = urlencode($dataArr['ccno']);//urlencode('5105105105105100');
            $transactionInfo['Expdate'] = urlencode($dataArr['expMonth'].$dataArr['expYear']);//urlencode('1117');//mmyy
            $transactionInfo['Cvv2'] = urlencode($dataArr['cvv']);//urlencode('123');

            if($paypalflowSettings['Paypalflowtestmode'] == 'Y') {
                $transactionInfo['Testmode'] = 'Y';
            } else {
                $transactionInfo['Testmode'] = 'N';
            }

            $paymentResult = Payments::payPaypalflow($transactionInfo);

                if(isset ($paymentResult)) {
                    //... groom the transaction result in the way we require
                    $paymentResult = Payments::chkPaypalflow($paymentResult,$transactionInfo);
                }

           $payArr  = array("paymentSuccessful" => $paymentResult["success"],
                            "paymentError" => $paymentResult["Message"],
                            "transactionId" => $paymentResult["TransactionId"]);

           return $payArr;
        
    } // End Function

    public static function doYourPayPayment($dataArr){

        $payArr = array();

        $paymentsuccessful = 0;
        $paymenterror = $transactionid = NULL;

        $YourPaySettings = Payments::getYoursPaySettings();

        $transactionInfo['yourpay_storeid'] 	= $YourPaySettings['yourpay_storeid'];
        $transactionInfo['yourpay_demo'] 	= $YourPaySettings['yourpay_demo'];
        $transactionInfo['keyfile'] 			= $YourPaySettings['yourpay_pemfile'];
        $transactionInfo['ordertype'] 		= "SALE";
        $transactionInfo['userinfo'] 		= $dataArr['userinfo'];
        $transactionInfo['yp_cardno'] 		= $dataArr['yp_cardno'];
        $transactionInfo['yp_expm'] 		= $dataArr['yp_expm'];
        $transactionInfo['yp_expy'] 		= $dataArr['yp_expy'];
        $transactionInfo['yp_cvno'] 		= $dataArr['yp_cvno'];

        $return				= Payments::doYourPay($transactionInfo);

        //TODO : need to add the transaction checking
        $paymentResult = Payments::chkYourPay($resPayment, $arrtwoPaySettings);

        $haystackPay = strtolower($paymentResult);
        $needlePay = 'approved';

        if (strpos($haystackPay,$needlePay) !== false) {
            $paymentsuccessful = 1;
        }

        $paymentsuccessful = 0;
        $paymenterror = $transactionid = NULL;

        $payArr  = array("paymentSuccessful" => $paymentsuccessful,
                        "paymentError" => $paymenterror,
                        "transactionId" => $transactionid);

       return $payArr;

    } // End Function

    public static function doQuickbookPayment($dataArr){
        // ... this hook handles payment with quick book
    } // End Function

    public static function doTemplateInstallation($templateID, $lookupID){
        $dataArr = Admincomponents::getPaidTemplates(array(array('field' => 'nTemplateId', 'value' => $templateID)));
        // Installation process
        $serverInfoArr = Admincomponents::getStoreServerInfo($lookupID);    // getStoreServerInfo
        $status = array();
        if(!empty($serverInfoArr)) {
            PageContext::includePath('cpanel');
            $cpanelObj = new cpanel();

            $file= $dataArr[0]->zipFile; // the template zip file should come here

            $ftpPathArr = array('source_path' => FILE_UPLOAD_DIR.$file,
                                'destination_path' => '/public_html/app/webroot/'.$file);

            $operationArgArr = array(
                        'sourcefiles'      => '/public_html/app/webroot/'.$file,
                        'destfiles' => '/public_html/app/webroot/',
                        'op' => 'extract',
                        );

            $status = $cpanelObj->doFtpUploadAndCpanelOperations($serverInfoArr, $ftpPathArr, $operationArgArr);
            // End Installation Process
        }

        return $status;

    } // End Function


} // End Class


?>