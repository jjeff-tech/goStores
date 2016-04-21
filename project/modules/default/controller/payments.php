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


class ControllerPayments extends BaseController {
    /*
      construction function. we can initialize the models here
     */

    public function init() {
        parent::init();
        PageContext::$body_class = "home";
        PageContext::addJsVar("createnewsletter", BASE_URL . "index/createnewsletter/");
        PageContext::addScript("jquery.metadata.js");
        PageContext::addScript("jquery.validate.js");
        PageContext::addScript("general.js");
        PageContext::addJsVar('userLogin', BASE_URL . "index/userlogin/");
        PageContext::addJsVar('loginSuccess', BASE_URL . "user/dashboard/");
        PageContext::addScript("dropmenu.js");
        PageContext::addScript("dropmenu_ready.js");
        PageContext::addStyle("dropdown_style.css");
        //PageContext::addScript("jquery.min.js");
        PageContext::addScript('jquery.timer.js');
        PageContext::addScript("login.js");
        PageContext::addScript("hoverIntent.js");
        //PageContext::addScript("jquery-1.2.6.min.js");
        PageContext::addScript("superfish.js");
        User::googleAnalytics();
        PageContext::addStyle("custom_progress/progress.css");
        PageContext::$headerCodeSnippet = "<script>
  (function() {
    var cx = '001817314419920192210:y32ewf88ube';
    var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gcse, s);
  })();
</script>";
    }

    /*
      function to load the index template
     */

    
    //Payment Gateways Akhil Starts
    public function twocheckout($paystatus = "") { //Payment Gateways twocheckout 
        $twocheckoutSettings = Payments::getTwoCheckoutSettings();
        $arrtwoPaySettings = array();
        $arrtwoPaySettings['Vendorid'] = $twocheckoutSettings['TwoCheckoutvendorid'];//'1877160'; // vendor id from payment settings
        $arrtwoPaySettings['Company'] = "-NA-";
        $arrtwoPaySettings['Email'] = "testpay@armiasystems.com";// User Email
        $arrtwoPaySettings['Currency'] = 'USD';
        if($twocheckoutSettings['TwoCheckouttestmode'] == 'Y')
            $arrtwoPaySettings['Testmode'] = "Y";
        
        $arrtwoPaySettings['Cartid'] = rand(1, 1000);
        $arrtwoPaySettings['Grandtotal'] = 0.05;
        $arrtwoPaySettings['ReturnURL'] = BASE_URL . "payments/twocheckout/sucess";


        if(isset ($paystatus) && $paystatus == 'sucess') {
            $paymentResult = Payments::chkTwoCheckoutPayment(PageContext::$request,$arrtwoPaySettings);
           
          /* if(Payments::chkTwoCheckoutPayment(PageContext::$request,$arrtwoPaySettings)){
                echo 'sucess';exit;
            }else{
                echo 'failed';exit;
            }*/
        }else { 
            echo  Payments::payTwoCheckout($arrtwoPaySettings);
           // exit;
        }

        exit;

    }//Payment Gateways twocheckout ends



    public function paypalpro() { //Payment Gateways Paypalpro
        $currentCountry = 'US';
        PageContext::$response->creditcard = Payments::getCreditCardPaypalpro($currentCountry);

        // $this->disableLayout();
      // Credit card for Canada /Briten/US ends
  //  echo 'paypalpro';exit;
    /*    $currentCountry = 'US';
        PageContext::$response->postURL = BASE_URL . "payments/paypalpro";
        PageContext::$response->creditcard = Payments::getCreditCardPaypalpro($currentCountry);
       // echopre(PageContext::$response->creditcard);
        // Credit card for Canada /Briten/US ends
        if(isset (PageContext::$request->btnCompleteOrderpaypro)){// &&  PageContext::$request->btnCompleteOrderpaypro == "Pay Now"){
        $arrtwoPaySettings = array();
        $paypayproSettings = Payments::getPaypalproSettings();

        
        $arrtwoPaySettings['Paypalprousername'] = $paypayproSettings['Paypalprousername']; //"mahiat_1351864475_biz_api1.yahoo.com";
        $arrtwoPaySettings['Paypalpropassword'] = $paypayproSettings['Paypalpropassword'];//"1351864518";
        $arrtwoPaySettings['Paypalprosignature'] = $paypayproSettings['Paypalprosignature'];//'A0yYhIEfABicc8vcPNDIgocAdlatAiI9tB-cYE4rdnod1VbCLzTG6fu6';
        $arrtwoPaySettings['Paymenttype'] = urlencode('Sale'); // Constant
        $arrtwoPaySettings['Grandtotal'] = urlencode('0.05');
        $arrtwoPaySettings['Creditcardtype'] = urlencode(PageContext::$request['paymentmethod']);//urlencode('Visa');
        $arrtwoPaySettings['Creditcardnumber'] = urlencode(PageContext::$request['ccno1']);//urlencode('4055825683869610');
        $arrtwoPaySettings['Expdate'] = urlencode(PageContext::$request['expM'].PageContext::$request['expY']);//urlencode('112017');//mmyyyy
        $arrtwoPaySettings['Cvv2'] = urlencode(PageContext::$request['cvv1']);//urlencode('123');
        $arrtwoPaySettings['Currency'] = urlencode('USD');
        if($paypayproSettings['Paypalprotestmode'] == 'Y')
            $arrtwoPaySettings['Testmode'] = 'Y';
        else
             $arrtwoPaySettings['Testmode'] = 'N';

       
        $arrtwoPaySettings['Firstname'] = urlencode('Test');
        $arrtwoPaySettings['Lastname'] = urlencode('T');
        $arrtwoPaySettings['Street'] = urlencode('Sale');
        $arrtwoPaySettings['City'] = urlencode('Sale');
        $arrtwoPaySettings['Zip'] = urlencode('35001');
        $arrtwoPaySettings['Countrycode'] = urlencode('US');
        $paymantResult = Payments::payPaypalpro($arrtwoPaySettings);
         if(isset ($paymantResult)) {
        return  $paymantResult = Payments::chkpayPaypalpro($paymantResult,$arrtwoPaySettings);
         
          if(Payments::chkpayPaypalpro($paymnetResult,$arrtwoPaySettings)){
                echo 'sucess';exit;
            }else{
                echo 'failed';exit;
            }*
        }else {
            return $paymantResult;
             //echo 'failed';exit;
        }
        }*/


    }//Payment Gateways Paypalpro ends

    public function paypalprodomain() { //Payment Gateways Paypalpro
        $currentCountry = 'US';
        PageContext::$response->creditcard = Payments::getCreditCardPaypalpro($currentCountry);
    }
    public function paypalflowdomain() {

    }

public function paypalflow() { //Payment Gateways Paypalflow
   // $this->disableLayout();
   //  echo 'paypalflow';exit;
  /*  if(isset (PageContext::$request->btnCompleteOrderpaypro)) {// &&  PageContext::$request->btnCompleteOrderpaypro == "Pay Now"){
        $paypalflowSettings = Payments::getPaypalflowSettings();
        
        $arrtwoPaySettings = array();
        $arrtwoPaySettings['Paypalflowvendorid'] = $paypalflowSettings['Paypalflowvendorid'];//"armiapaypal";
        $arrtwoPaySettings['Paypalflowpassword'] = $paypalflowSettings['Paypalflowpassword'];//"armia247";
        $arrtwoPaySettings['Paypalflowpartnerid'] = $paypalflowSettings['Paypalflowpartnerid'];//'PayPal';
        $arrtwoPaySettings['Paymenttype'] = urlencode('S'); // Constant
        $arrtwoPaySettings['Grandtotal'] = urlencode('0.05');
        $arrtwoPaySettings['Tender'] = urlencode('C');// Constant
        $arrtwoPaySettings['Creditcardnumber'] = urlencode(PageContext::$request['ccno1']);//urlencode('5105105105105100');
        $arrtwoPaySettings['Expdate'] = urlencode(PageContext::$request['expM'].PageContext::$request['expY']);//urlencode('1117');//mmyy
        $arrtwoPaySettings['Cvv2'] = urlencode(PageContext::$request['cvv1']);//urlencode('123');

        if($paypalflowSettings['Paypalflowtestmode'] == 'Y')
            $arrtwoPaySettings['Testmode'] = 'Y';
        else
            $arrtwoPaySettings['Testmode'] = 'N';
        $arrtwoPaySettings['Firstname'] = urlencode('Test');
        $arrtwoPaySettings['Lastname'] = urlencode('T');
        $arrtwoPaySettings['Street'] = urlencode('Sale');
        $arrtwoPaySettings['City'] = urlencode('Sale');
        $arrtwoPaySettings['Zip'] = urlencode('35001');
        $arrtwoPaySettings['Countrycode'] = urlencode('US');


        $paymantResult = Payments::payPaypalflow($arrtwoPaySettings);

        if(isset ($paymantResult)) {
         return   $paymantResult = Payments::chkPaypalflow($paymantResult,$arrtwoPaySettings);
            //if(Payments::chkPaypalflow($paymentResult,$arrtwoPaySettings)) {

                //  echo 'sucess';
              //  exit;
          //  }else {
              //  echo 'failed';
              //  exit;
          //  }
        }else {
          return   $paymantResult ;
           // exit;
        }
    }*/


    }//Payment Gateways Paypalflow ends



public function paypalflowlink($paystatus = "") { //Payment Gateways Paypalflowlink
        $paypalflowlinkSettings = Payments::getPaypallinkSettings();
        $arrtwoPaySettings = array();
        $arrtwoPaySettings['Paypallinkvendorid'] = $paypalflowlinkSettings['Paypalflowlinkvendorid'];// "armiapayflow";
        $arrtwoPaySettings['Paypallinkpartnerid'] = $paypalflowlinkSettings['Paypalflowlinkpartnerid'];//  'PayPal';
        $arrtwoPaySettings['Paymenttype'] = 'S'; // Constant
        $arrtwoPaySettings['Method'] = 'CC';// Constant
        $arrtwoPaySettings['Grandtotal'] = '0.05';
        $arrtwoPaySettings['Customerid'] = rand(1,10000);
        $arrtwoPaySettings['Orderform'] = true;
        $arrtwoPaySettings['Showconfirm'] = true;

        if($paypalflowlinkSettings['Paypalflowlinktestmode'] == 'Y')
        $arrtwoPaySettings['Testmode'] = 'Y';
        
        $arrtwoPaySettings['ReturnURL'] = BASE_URL . "payments/paypalflowlink/sucess";

        $arrtwoPaySettings['Firstname'] = 'Test';
        $arrtwoPaySettings['Address'] = 'Address';
        $arrtwoPaySettings['City'] = 'Sale';
        $arrtwoPaySettings['Zip'] = '35001';
        $arrtwoPaySettings['Country'] = 'US';
        $arrtwoPaySettings['Phone'] = '35001';
        $arrtwoPaySettings['Fax'] = '546546';

                

       if(isset ($paystatus) && $paystatus == 'sucess') {
           if(Payments::chkPaypalflowlink(PageContext::$request,$arrtwoPaySettings)){
                echo 'sucess';exit;
            }else{
                echo 'failed';exit;
            }
        }else {
            echo  Payments::payPaypalflowlink($arrtwoPaySettings);
           // exit;
        }

exit;

    }//Payment Gateways Paypalflowlink ends


public function paypaladvanced($paystatus = "") { //Payment Gateways Paypaladvanced

$this->disableLayout();
if(isset (PageContext::$request->btnCompleteOrderpaypro)) {// &&  PageContext::$request->btnCompleteOrderpaypro == "Pay Now"){
    $paypaladvancedSettings = Payments::getPaypaladvancedSettings();
    $arrtwoPaySettings = array();
    $arrtwoPaySettings['Paypaladvancedvendorid'] =  $paypaladvancedSettings['Paypaladvancedvendorid'];//"palexanderpayflowtest";
    $arrtwoPaySettings['Paypaladvancedpassword'] = $paypaladvancedSettings['Paypaladvancedpassword'];//'demopass123';
    $arrtwoPaySettings['Paypaladvancedpartner'] = $paypaladvancedSettings['Paypaladvancedpartner'];//'PayPal';
    $arrtwoPaySettings['Paypaladvanceduser'] = $paypaladvancedSettings['Paypaladvancedusername'];//'palexanderpayflowtestapionly';


    $arrtwoPaySettings['Grandtotal'] = '0.05';


    $arrtwoPaySettings['Paymenttype'] = urlencode('A');
    $arrtwoPaySettings['Createsecuretocken'] = 'Y';
    $arrtwoPaySettings['Currency'] = "USD";
    $arrtwoPaySettings['Securetockenid'] = uniqid('MySecTokenID-');


if($paypalflowSettings['Paypaladvancedtestmode'] == "Y")
    $arrtwoPaySettings['Testmode'] = 'Y';

    $arrtwoPaySettings['ReturnURL'] = BASE_URL . "payments/paypaladvanced/sucess";
    $arrtwoPaySettings['CancelURL'] = BASE_URL . "payments/paypaladvanced/cancel";
    $arrtwoPaySettings['ErrorURL']  = BASE_URL . "payments/paypaladvanced/error";

    $arrtwoPaySettings['Billtofirstname'] = 'Test';
    $arrtwoPaySettings['Billtolastname'] = 'Address';
    $arrtwoPaySettings['Billtostreet'] = 'Street';
    $arrtwoPaySettings['Billtozip'] = '35001';
    $arrtwoPaySettings['Billtocity'] = 'City';
    $arrtwoPaySettings['Billtostate'] = 'AL';
    $arrtwoPaySettings['Billtocountry'] = 'US';


    if(isset ($paystatus) && $paystatus == "sucess") {
        if(Payments::chkPaypaladvanced(PageContext::$request,$arrtwoPaySettings)) {
            echo 'sucess';
            exit;
        }else {
            echo 'failed';
            exit;
        }
    }else {
        $redirectURL =  Payments::setPaypaladvancedUrl(Payments::payPaypaladvanced($arrtwoPaySettings), $arrtwoPaySettings);
        if($redirectURL != false) {
            Headerredirect::httpRedirect($redirectURL);
        }
        else {
            echo 'failed';
        }
    }

}


    }//Payment Gateways Paypaladvanced ends

public function braintree($paystatus = "") { //Payment Gateways Breantree

        $arrtwoPaySettings = array();
        $arrtwoPaySettings['Braintreemerchantid'] = "f7mgykzp5b7txjf7";
        $arrtwoPaySettings['Braintreepublickey'] = 'qfhh854tm6g6md9x';
        $arrtwoPaySettings['Braintreeprivatekey'] = '863323bad983dc6eca5dea1a7913a90f';
        $arrtwoPaySettings['Paymenttype'] = 'sale';// Constant
        $arrtwoPaySettings['Grandtotal'] = '0.05';
        

        $arrtwoPaySettings['Testmode'] = 'Y';


        $arrtwoPaySettings['Firstname'] = 'Test';
        $arrtwoPaySettings['Lastname'] = 'Lastname';
        $arrtwoPaySettings['Email'] = 'test@armiasystems.com';

        if(isset(PageContext::$request->http_status) ) {
            if(Payments::chkBreantree(PageContext::$request,$arrtwoPaySettings)) {
                echo 'sucess';
                exit;
            }else {
                echo 'failed';
                exit;
            }
        }else{

$configValues =  Payments::payBreantree($arrtwoPaySettings);
if(isset ($configValues) && count($configValues) > 0){

            $renderFrom = '<form action="'. $configValues['form_url'].'" method="post" name="frmPayment" >';
            $renderFrom .='<br>CCNumber :<input type="text" size="27" class="box2_admin" value="" maxlength="16" id="txtCCNumber" name="transaction[credit_card][number]">';
            $renderFrom .='<br>CVV2<input type="text" size="27" class="box2_admin" maxlength="10" value="" id="txtCVV2" name="transaction[credit_card][cvv]">';
            $renderFrom .='<br>expiration_date<input type="text" size="27" class="box2_admin" maxlength="10" id="expiration_date" value="" name="transaction[credit_card][expiration_date]">';
            $renderFrom .=   '<input type="hidden" name="tr_data" value="'. $configValues['tr_data'].'" />
                                        <input type="hidden" name="transaction[customer][first_name]" value="'. $configValues['firstName'].'" />
                                        <input type="hidden" name="transaction[customer][last_name]" value="'. $configValues['lastName'].'" />
                                        <input type="hidden" name="transaction[customer][email]" value="'. $configValues['email'].'" />
					<br><input type="submit"  name="btnCompleteOrderbraintree" value="Pay Now" onclick="return validateForm(document.frmPayment);" class="btn-usr01">';

            $renderFrom .= '</form>';

            echo $renderFrom;
}
}

    }//Payment Gateways Breantree ends



    public function paypalexpress($paystatus = "") { //Payment Gateways Paypalexpress

        $arrtwoPaySettings = array();
        $arrtwoPaySettings['Paypalexpressusername'] = "seller_1297271002_biz_api1.yahoo.com";
        $arrtwoPaySettings['Paypalexpresspassword'] = '1297271011';
        $arrtwoPaySettings['Paypalexpresssignature'] = 'AFcWxV21C7fd0v3bYYYRCpSSRl31A-Vd1YRxIrhGWvUd2XnlrhGdk6rY';
        

        $arrtwoPaySettings['Grandtotal'] = '0.05';

        $arrtwoPaySettings['Testmode'] = 'Y';

        $arrtwoPaySettings['ReturnURL'] = BASE_URL . "payments/paypalexpress/sucess";
        $arrtwoPaySettings['CancelURL'] = BASE_URL . "payments/paypalexpress/cancel";
        
 
  if(isset ($paystatus) && $paystatus != ""){
        if( $paystatus == "sucess")
               echo 'sucess';
        elseif($paystatus == "cancel")
           echo 'failed';
        
  }else {
       $redirectURL =  Payments::payPaypalexpress($arrtwoPaySettings);
       if($redirectURL != "") {
           Headerredirect::httpRedirect($redirectURL);
       }
       else {
           echo 'failed';
       }
   }
 }//Payment Gateways Paypalexpress ends


  public function ogone($paystatus = "") { //Payment Gateways Ogone

        $arrtwoPaySettings = array();
        
        $arrtwoPaySettings['Ogonepspid'] = "rajath";
        $arrtwoPaySettings['Ogonepassphrase'] = 'shainarmia247~!@';

        $arrtwoPaySettings['Grandtotal'] = '0.05';

        $arrtwoPaySettings['Testmode'] = 'Y';

        $arrtwoPaySettings['DeclineURL'] = BASE_URL . "payments/ogone/decline";
        $arrtwoPaySettings['CancelURL'] = BASE_URL . "payments/ogone/cancel";
        $arrtwoPaySettings['ExceptionURL'] = BASE_URL . "payments/ogone/exception";
        $arrtwoPaySettings['AcceptURL'] = BASE_URL . "payments/ogone/sucess"; //return if sucess

        $arrtwoPaySettings['Orderid'] =  RAND(10000,895689596);
        $arrtwoPaySettings['Currency'] = "USD";
        $arrtwoPaySettings['Language'] =  "en_us";
        $arrtwoPaySettings['Logo'] = "Logo.jpg";
        $arrtwoPaySettings['Operation'] =  'SAL'; //Constant
        $arrtwoPaySettings['Currency'] = "USD";

        $arrtwoPaySettings['Firstname'] = 'Test';
        $arrtwoPaySettings['Lastname'] = 'Name';
        $arrtwoPaySettings['Email'] = 'test@armiasystems.com';
        

  if(isset ($paystatus) && $paystatus != ""){
        if($paystatus == "sucess"){
              if(Payments::chkBreantree(PageContext::$request,$arrtwoPaySettings)) {
                echo 'sucess';
                exit;
            }else {
                echo 'failed';
                exit;
            }
        }
        elseif($paystatus == "cancel")
           echo 'failed';

  }else {
       echo Payments::payOgone($arrtwoPaySettings);
      
   }
 }//Payment Gateways Ogone ends

 public function moneybookers($paystatus = "") { //Payment Gateways Moneybookers

        $arrtwoPaySettings = array();

        $arrtwoPaySettings['Paytoemail'] = "developeraccount@armiasystems.com";
        
        $arrtwoPaySettings['Grandtotal'] = '0.05';

        $arrtwoPaySettings['Testmode'] = 'Y';

        $arrtwoPaySettings['status_url'] = BASE_URL . "payments/moneybookers/postsucess";
        $arrtwoPaySettings['return_url'] = BASE_URL . "payments/ogone/sucess";

        $arrtwoPaySettings['Currency'] = "USD";
        $arrtwoPaySettings['Language'] =  "en_us";
        
        $arrtwoPaySettings['Description'] =  "Description";
        
        $arrtwoPaySettings['detail1_text'] = "Order Purchase";
        $arrtwoPaySettings['confirmation_note'] =  "Payment Sucess"; //Constant
        


  if(isset ($paystatus) && $paystatus != ""){
        if($paystatus == "sucess"){
              if(Payments::chkMoneybookers(PageContext::$request,$arrtwoPaySettings)) {
                echo 'sucess';
                exit;
            }else {
                echo 'failed';
                exit;
            }
        }
        elseif($paystatus == "cancel")
           echo 'failed';

  }else {
       echo Payments::payMoneybookers($arrtwoPaySettings);

   }
 }//Payment Gateways Moneybookers ends

  public function authorize() {
     
  } public function authorizedomain() {
     
  }
  
public function quickbook() {
     
  }
  public function quickbookdomain() {

  }



  public function personalinfo() {
    
  }
  
  public function paypal($result ="") {

     
      $arrtwoPaySettings['Paypalemail'] = "mahi_1_1321000734_biz@yahoo.com";
     // $arrtwoPaySettings['Paypalemail'] = 'armiaseller@armia.com';
      $arrtwoPaySettings['resultURL'] = BASE_URL . "payments/paypal/sucess";
      $arrtwoPaySettings['cancelURL'] = BASE_URL . "payments/paypal/cancel";
      $arrtwoPaySettings['notifyURL'] = BASE_URL . "payments/paypal/ipn";
      $arrtwoPaySettings['Itemname'] = "Test";
      $arrtwoPaySettings['Grandtotal'] = 0.05;
      $arrtwoPaySettings['Transactid'] = RAND(10000,895689596);
      $arrtwoPaySettings['Testmode'] = 'Y';
      if($result == "ipn"){
            $fp = fopen('data1111.txt', 'w');
            fwrite($fp, $ipnTxnId);
            fwrite($fp, '23');
            fclose($fp);
         if($p->validate_ipn()){
           $ipnTxnId 	= $p->ipn_data['txn_id'];
            $transId 	= $p->ipn_data['custom'];
            
          echo $result;
          echopre(PageContext::$request);
         }
         exit;
      }
      if($result == "sucess"){
          echo $result;
          echopre(PageContext::$request);
         $status = Payments::chkPaypal(PageContext::$request, $arrtwoPaySettings);
         echopre($status);
           exit;
      }
      echo 'herer';
      
      if($result == ""){
         $p = Payments::paypal($arrtwoPaySettings);
       // $p =  $p->submit_paypal_post();
      }
      echopre($p);
      

     exit;
  }



  public function chkEnable($paystatus = "") {
        $ebablePaymnets =  Payments::getEnabledPaymnets();
        echopre($ebablePaymnets);exit;
  }

  public function chkPaymenttest($paystatus = "") {



      $authorizeInfo['expMonth']   = "01";
        $authorizeInfo['expYear']    = "14";
        $authorizeInfo['cvv']        = 123;
        $authorizeInfo['ccno']       = "5105105105105100";
        $authorizeInfo['fName']      = 'fname';
        $authorizeInfo['lName']      = 'lname';
        $authorizeInfo['add1']       = 'add1';
        $authorizeInfo['city']       = 'city';
        $authorizeInfo['state']      = 'state';
        $authorizeInfo['country']    = 'US';
        $authorizeInfo['zip']        = "35001";

$authorizeInfo['amount'] = 0.05;
$paymantArray['paymentmethod'] = "Visa";
$paymantArray['currentpaymant'] ="paypalpro";
$paymantArray['currentpaymant'] = "paypalflow";



      $arrtwoPaySettings = array();
           /* $paypayproSettings = Payments::getPaypalproSettings();


            $arrtwoPaySettings['Paypalprousername'] = $paypayproSettings['Paypalprousername']; //"mahiat_1351864475_biz_api1.yahoo.com";
            $arrtwoPaySettings['Paypalpropassword'] = $paypayproSettings['Paypalpropassword'];//"1351864518";
            $arrtwoPaySettings['Paypalprosignature'] = $paypayproSettings['Paypalprosignature'];//'A0yYhIEfABicc8vcPNDIgocAdlatAiI9tB-cYE4rdnod1VbCLzTG6fu6';
            $arrtwoPaySettings['Paymenttype'] = urlencode('Sale'); // Constant
            $arrtwoPaySettings['Grandtotal'] = urlencode($authorizeInfo['amount']);
            $arrtwoPaySettings['Creditcardtype'] = urlencode($paymantArray['paymentmethod']);//urlencode('Visa');
            $arrtwoPaySettings['Creditcardnumber'] = urlencode($authorizeInfo['ccno']);//urlencode('4055825683869610');
            $arrtwoPaySettings['Expdate'] = urlencode($authorizeInfo['expMonth'].$authorizeInfo['expYear']);//urlencode('112017');//mmyyyy
            $arrtwoPaySettings['Cvv2'] = urlencode($authorizeInfo['cvv']);//urlencode('123');*/

     /* $paypalflowSettings = Payments::getPaypalflowSettings();
        $arrtwoPaySettings['Paypalflowvendorid'] = $paypalflowSettings['Paypalflowvendorid'];//"armiapaypal";
        $arrtwoPaySettings['Paypalflowpassword'] = $paypalflowSettings['Paypalflowpassword'];//"armia247";
        $arrtwoPaySettings['Paypalflowpartnerid'] = $paypalflowSettings['Paypalflowpartnerid'];//'PayPal';
        $arrtwoPaySettings['Paymenttype'] = urlencode('S'); // Constant
        $arrtwoPaySettings['Tender'] = urlencode('C');// Constant
        $arrtwoPaySettings['Creditcardnumber'] = urlencode($authorizeInfo['ccno']);//urlencode('5105105105105100');
        $arrtwoPaySettings['Expdate'] = urlencode($authorizeInfo['expMonth'].$authorizeInfo['expYear']);//urlencode('1117');//mmyy
        $arrtwoPaySettings['Cvv2'] = urlencode($authorizeInfo['cvv']);//urlencode('123');
        $arrtwoPaySettings['Grandtotal'] = urlencode($authorizeInfo['amount']);
        if($paypalflowSettings['Paypalflowtestmode'] == 'Y')
            $arrtwoPaySettings['Testmode'] = 'Y';
        else
            $arrtwoPaySettings['Testmode'] = 'N';

        
        
            $arrtwoPaySettings['Firstname'] = urlencode($authorizeInfo['fName']);
            $arrtwoPaySettings['Lastname'] = urlencode($authorizeInfo['lName']);
            $arrtwoPaySettings['Street'] = urlencode($authorizeInfo['add1']);
            $arrtwoPaySettings['City'] = urlencode($authorizeInfo['city']);
            $arrtwoPaySettings['Zip'] = urlencode($authorizeInfo['zip']);
            $arrtwoPaySettings['Countrycode'] = urlencode($authorizeInfo['country']);*/
$paymantArray['currentpaymant'] ="authorize";
      $authorizeInfo               = array();
        $productArray                = array();
        $authorizeInfo['expMonth']   = '01';
        $authorizeInfo['expYear']    = 15;
        $authorizeInfo['cvv']        = '123';
        $authorizeInfo['ccno']       = '4111111111111111';
        $authorizeInfo['fName']      = 'sfsf';
        $authorizeInfo['lName']      = 'lname';
        $authorizeInfo['add1']       = 'add1';
        $authorizeInfo['city']       = 'city';
        $authorizeInfo['state']      = 'state';
        $authorizeInfo['country']    = 'US';

        $authorizeInfo['zip']        = 35001;

        $authorizeInfo['email']      = 'usdgfjh@jkdfh.in';
        $authorizeInfo['amount']     = .05;
//$status  =   User::creditPayment($authorizeInfo);
    $arrtwoPaySettings = $authorizeInfo;
             $status = Payments::doAllPaymants($paymantArray['currentpaymant'], $arrtwoPaySettings);
             echopre($status);
        exit;
  }

public function rederallpayment($paystatus = "") {

/* Utils::loadActiveTheme();
        PageContext::$response->themeUrl = Utils::getThemeUrl();
        PageContext::addPostAction('cloudtopmenupage');
        $this->view->productId = 1;
        PageContext::addPostAction('cloudfooterpage');
        $this->view->setLayout("productpage");       
//$this->disableLayout();*/
 // Akhil payment integration
        PageContext::addScript('validatepayment.js');
             $paymentsEnabled = Payments::getEnabledPaymnets();
            // echopre($paymentsEnabled);
              if(isset ($paymentsEnabled['authorize_enable']) &&  $paymentsEnabled['authorize_enable'] == 'Y'){
                  PageContext::addPostAction('personalinfo','payments');
                  PageContext::addPostAction('authorize','payments');
             }

              if(isset ($paymentsEnabled['paypalpro_enable']) &&  $paymentsEnabled['paypalpro_enable'] == 'Y'){
                $currentCountry = 'US';
                //PageContext::addPostAction('personalinfo','payments');
                PageContext::$response->creditcard = Payments::getCreditCardPaypalpro($currentCountry);
              PageContext::addPostAction('paypalpro','payments');
              }

              if(isset ($paymentsEnabled['paypalflow_enable']) &&  $paymentsEnabled['paypalflow_enable'] == 'Y'){
              //PageContext::addPostAction('personalinfo','payments');
              PageContext::addPostAction('paypalflow','payments');
              }


              PageContext::$response->paymnetsEnabled = $paymentsEnabled;
}
     //Payment Gateways Akhil ends


public function renderallpayments(){

    $paymentsEnabled = Payments::getEnabledPaymnets();
    PageContext::$response->paymnetsEnabled = $paymentsEnabled;
        
} //End function


}

?>