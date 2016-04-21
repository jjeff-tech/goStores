<?php
class Payments {

    //public static $paymentResult = array();
    // Two checkout
    public static function  payTwoCheckout($arrtwoPaySettings = array()) {


        PageContext::includePath('paymentgateways/twocheckout');

        $objTwoCheckout =  new TwoCheckout();
        // Specify your 2CheckOut vendor id
        $objTwoCheckout->addField('sid', $arrtwoPaySettings['Vendorid']);

        // Specify the order information
        $objTwoCheckout->addField('cart_order_id', $arrtwoPaySettings['Cartid']);
        $objTwoCheckout->addField('total', $arrtwoPaySettings['Grandtotal']);

        // Specify the url where authorize.net will send the IPN
        $objTwoCheckout->addField('x_Receipt_Link_URL', $arrtwoPaySettings['ReturnURL']);

        $objTwoCheckout->addField('tco_currency',$arrtwoPaySettings['Currency']);

        $objTwoCheckout->addField('c_name_1',$arrtwoPaySettings['Itemname']);

        // Enable test mode if needed
        if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
            $objTwoCheckout->enableTestMode();

        return $objTwoCheckout->submitPaymentDisplay();
    }//end of twocheckout

    public static function chkTwoCheckoutPayment($ipnData, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($ipnData) && count($ipnData) > 0) {

            if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
                $orderNumber = "1";


            if($ipnData["key"] != '' && $ipnData["credit_card_processed"] == 'Y' && $ipnData["total"] == $arrtwoPaySettings['Grandtotal']) {
                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $ipnData["order_number"];
                return $paymentResult;
                // return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                // return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }


// Paypalpro

    public static function  payPaypalpro($arrtwoPaySettings = array()) {
        /*
             Expected Input

        */
        
echopre1($arrtwoPaySettings);
        PageContext::includePath('paymentgateways/paypalpro');
        $objPaypalpro =  new Paypalpro();


        //Admin paymnet details
        $objPaypalpro->API_UserName = $arrtwoPaySettings['Paypalprousername'];
        $objPaypalpro->API_Password = $arrtwoPaySettings['Paypalpropassword'];
        $objPaypalpro->API_Signature = $arrtwoPaySettings['Paypalprosignature'];


        // Specify payment action eg: Sales

        $objPaypalpro->addField('PAYMENTACTION', $arrtwoPaySettings['Paymenttype']);

        // Paymnet amount
        $objPaypalpro->addField('AMT', $arrtwoPaySettings['Grandtotal']);

        // Credit card type
        $objPaypalpro->addField('CREDITCARDTYPE', $arrtwoPaySettings['Creditcardtype']);

        // Credit card number
        $objPaypalpro->addField('ACCT', $arrtwoPaySettings['Creditcardnumber']);
        // $objPaypalpro->addField('ACCT','4055825683869610');

        // Currency Code
        $objPaypalpro->addField('CURRENCYCODE', $arrtwoPaySettings["Currency"]);

        // Credit card Expiration Date
        $objPaypalpro->addField('EXPDATE', $arrtwoPaySettings['Expdate']);


        //enable test mode
        if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
            $objPaypalpro->enableTestMode();


        $objPaypalpro->addField('CVV2', $arrtwoPaySettings['Cvv2']);
        // User Details
        $objPaypalpro->addField('FIRSTNAME', $arrtwoPaySettings['Firstname']);
        $objPaypalpro->addField('LASTNAME', $arrtwoPaySettings['Lastname']);
        $objPaypalpro->addField('STREET', $arrtwoPaySettings['Street']);
        $objPaypalpro->addField('CITY', $arrtwoPaySettings['City']);
        $objPaypalpro->addField('STATE', $arrtwoPaySettings['State']);
        $objPaypalpro->addField('ZIP', $arrtwoPaySettings['Zip']);
        $objPaypalpro->addField('COUNTRYCODE', $arrtwoPaySettings['Countrycode']);

        //pay pal ADVANCE button
        $dbObj = new Db();
        $BNCode =  $dbObj->selectRow("Settings","value","settingfield='paypalpro_bn_code'");
        $objPaypalpro->addField('BUTTONSOURCE', $BNCode);


        return $objPaypalpro->submitPaymentDisplay();

    }

    public static function  chkpayPaypalpro($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($paymnetResult['ACK'] == 'Success' && $paymnetResult['AMT'] == $arrtwoPaySettings['Grandtotal']) {
                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $paymnetResult["TRANSACTIONID"];
                $paymentResult['paymentMethod'] = 'paypalpro';
                return $paymentResult;
                // return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = $paymnetResult["L_LONGMESSAGE0"];
                $paymentResult['TransactionId'] = "";
                $paymentResult['paymentMethod'] = 'paypalpro';
                return $paymentResult;
                // return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            $paymentResult['paymentMethod'] = 'paypalpro';
            return $paymentResult;
            //  return false; // payment failed
            exit;
        }
    }

    public static function  getCreditCardPaypalpro($country = 'US') {

        if(isset($country)) {
            if($country == 'GB') {
                $paymentCards = array('Visa'=>'Visa','Visa Electron'=>'Visa Electron','Visa Debit'=>'Visa Debit','Maestro'=>'Maestro','Mastercard'=>'Mastercard');
            }elseif($country == 'US') {
                $paymentCards = array('Visa'=>'Visa','Mastercard'=>'Mastercard','Discover'=>'Discover','AmericanExpress'=>'American Express');
            }elseif($country == 'CA') {
                $paymentCards = array('Visa'=>'Visa','Mastercard'=>'Mastercard');
            }else {
                $paymentCards = array('Visa'=>'Visa','Mastercard'=>'Mastercard');
            }
        }else {
            $paymentCards = array('Visa'=>'Visa','Mastercard'=>'Mastercard');
        }
        return $paymentCards;
    }

//Paypalpro ends


// Paypalflow

    public static function  payPaypalflow($arrtwoPaySettings = array()) {

        /* Expected inputs

        */

        PageContext::includePath('paymentgateways/paypalflow');
        $objPaypalflow =  new Paypalflow();



        //Admin paymnet details
        $objPaypalflow->API_UserName = $arrtwoPaySettings['Paypalflowvendorid'];
        $objPaypalflow->API_Password = $arrtwoPaySettings['Paypalflowpassword'];
        $objPaypalflow->API_partnerID = $arrtwoPaySettings['Paypalflowpartnerid'];


        // Specify payment action eg: Sales

        $objPaypalflow->addField('TRXTYPE', $arrtwoPaySettings['Paymenttype']);
        $objPaypalflow->addField('TENDER', $arrtwoPaySettings['Tender']);

        // Paymnet amount
        $objPaypalflow->addField('AMT', $arrtwoPaySettings['Grandtotal']);


        // Credit card type
        $objPaypalflow->addField('COMMENT1', $arrtwoPaySettings['Comment1']);

        // Credit card number
        $objPaypalflow->addField('ACCT', $arrtwoPaySettings['Creditcardnumber']);
        // $objPaypalpro->addField('ACCT','4055825683869610');

        $objPaypalflow->addField('CVV2', $arrtwoPaySettings['Cvv2']);

        // Credit card Expiration Date
        $objPaypalflow->addField('EXPDATE', $arrtwoPaySettings['Expdate']);


        //enable test mode
        if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
            $objPaypalflow->enableTestMode();



        // User Details
        $objPaypalflow->addField('FIRSTNAME', $arrtwoPaySettings['Firstname']);
        $objPaypalflow->addField('LASTNAME', $arrtwoPaySettings['Lastname']);
        $objPaypalflow->addField('STREET', $arrtwoPaySettings['Street']);
        $objPaypalflow->addField('CITY', $arrtwoPaySettings['City']);
        $objPaypalflow->addField('STATE', $arrtwoPaySettings['State']);
        $objPaypalflow->addField('ZIP', $arrtwoPaySettings['Zip']);
        $objPaypalflow->addField('COUNTRYCODE', $arrtwoPaySettings['Countrycode']);



        return $objPaypalflow->submitPaymentDisplay();

    }

    public static function  chkPaypalflow($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($paymnetResult['RESULT'] == '0' && $paymnetResult['RESPMSG'] == 'Approved' && isset ($paymnetResult['PNREF']) ) {
                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $paymnetResult["PNREF"];
                $paymentResult['paymentMethod'] = 'paypalflow';
                return $paymentResult;
                //return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = $paymnetResult['RESPMSG'];
                $paymentResult['TransactionId'] = "";
                $paymentResult['paymentMethod'] = 'paypalflow';
                return $paymentResult;
                //return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            $paymentResult['paymentMethod'] = 'paypalflow';
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }

// Paypalflowlink

    public static function  payPaypalflowlink($arrtwoPaySettings = array()) {


        PageContext::includePath('paymentgateways/payflowlink');
        $objPaypalflowlink =  new Payflowlink();



        //Admin paymnet details
        $objPaypalflowlink->addField('LOGIN', $arrtwoPaySettings['Paypallinkvendorid']);
        $objPaypalflowlink->addField('PARTNER', $arrtwoPaySettings['Paypallinkpartnerid']);


        $objPaypalflowlink->addField('TYPE', $arrtwoPaySettings['Paymenttype']);
        $objPaypalflowlink->addField('METHOD', $arrtwoPaySettings['Method']);

        $objPaypalflowlink->addField('AMOUNT', $arrtwoPaySettings['Grandtotal']);
        $objPaypalflowlink->addField('CUSTID', $arrtwoPaySettings['Customerid']);
        $objPaypalflowlink->addField('ORDERFORM', $arrtwoPaySettings['Orderform']);
        $objPaypalflowlink->addField('SHOWCONFIRM', $arrtwoPaySettings['Showconfirm']);
        $objPaypalflowlink->addField('RETURNURL', $arrtwoPaySettings['ReturnURL']);




        //enable test mode
        if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
            $objPaypalflowlink->enableTestMode();



        // User Details
        $objPaypalflowlink->addField('NAME', $arrtwoPaySettings['Firstname']);
        $objPaypalflowlink->addField('ADDRESS', $arrtwoPaySettings['Address']);
        $objPaypalflowlink->addField('CITY', $arrtwoPaySettings['City']);
        $objPaypalflowlink->addField('STATE', $arrtwoPaySettings['State']);
        $objPaypalflowlink->addField('ZIP', $arrtwoPaySettings['Zip']);
        $objPaypalflowlink->addField('COUNTRY', $arrtwoPaySettings['Country']);
        $objPaypalflowlink->addField('PHONE', $arrtwoPaySettings['Phone']);
        $objPaypalflowlink->addField('FAX', $arrtwoPaySettings['Fax']);


        return $objPaypalflowlink->submitPaymentDisplay();

    }

    public static function  chkPaypalflowlink($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($paymnetResult['RESULT'] == '0' && $paymnetResult['RESPMSG'] == 'Approved' &&$paymnetResult['AMT'] == $arrtwoPaySettings['Grandtotal'] ) {
                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $paymnetResult["PNREF"];
                return $paymentResult;

                // return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                // return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }



// Paypaladvanced

    public static function  payPaypaladvanced($arrtwoPaySettings = array()) {


        PageContext::includePath('paymentgateways/paypaladvanced');
        $objPaypaladvanced =  new Paypaladvanced();



        //Admin paymnet details
        $objPaypaladvanced->API_Vendor = $arrtwoPaySettings['Paypaladvancedvendorid'];
        $objPaypaladvanced->API_Password = $arrtwoPaySettings['Paypaladvancedpassword'];
        $objPaypaladvanced->API_partnerID = $arrtwoPaySettings['Paypaladvancedpartner'];

        $objPaypaladvanced->addField('USER', $arrtwoPaySettings['Paypaladvanceduser']);

        $objPaypaladvanced->addField('TRXTYPE', $arrtwoPaySettings['Paymenttype']);
        $objPaypaladvanced->addField('CREATESECURETOKEN', $arrtwoPaySettings['Createsecuretocken']);

        $objPaypaladvanced->addField('AMT', $arrtwoPaySettings['Grandtotal']);
        $objPaypaladvanced->addField('CURRENCY', $arrtwoPaySettings['Currency']);
        $objPaypaladvanced->addField('SECURETOKENID', $arrtwoPaySettings['Securetockenid']);
        $objPaypaladvanced->addField('CANCELURL', $arrtwoPaySettings['CancelURL']);
        $objPaypaladvanced->addField('RETURNURL', $arrtwoPaySettings['ReturnURL']);
        $objPaypaladvanced->addField('ERRORURL', $arrtwoPaySettings['ErrorURL']);

        //enable test mode
        if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
            $objPaypaladvanced->enableTestMode();



        // User Details
        $objPaypaladvanced->addField('BILLTOFIRSTNAME', $arrtwoPaySettings['Billtofirstname']);
        $objPaypaladvanced->addField('BILLTOLASTNAME', $arrtwoPaySettings['Billtolastname']);
        $objPaypaladvanced->addField('BILLTOSTREET', $arrtwoPaySettings['Billtostreet']);
        $objPaypaladvanced->addField('BILLTOCITY', $arrtwoPaySettings['Billtocity']);
        $objPaypaladvanced->addField('BILLTOSTATE', $arrtwoPaySettings['Billtostate']);
        $objPaypaladvanced->addField('BILLTOZIP', $arrtwoPaySettings['Billtozip']);
        $objPaypaladvanced->addField('BILLTOCOUNTRY', $arrtwoPaySettings['Billtocountry']);

        //pay pal ADVANCE button
        $dbObj = new Db();
        $BNCode =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_bn_code'");
        $objPaypaladvanced->addField('BUTTONSOURCE', $BNCode);

        return $objPaypaladvanced->submitPaymentDisplay();

    }

    public static function  setPaypaladvancedUrl($paymnetResult, $arrtwoPaySettings = array()) {

        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if ($paymnetResult['RESULT'] != 0) {
                return false;
                exit;

            } else {
                $securetoken = $paymnetResult['SECURETOKEN'];
                $securetokenid = $paymnetResult['SECURETOKENID'];
            }
            //enable test mode
            if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
                $mode='TEST'; else $mode='LIVE';


            /* $paypaladvancedrender = '<div style="border: 1px dashed; margin-left:40px; width:492px; height:567px;">'; // wrap iframe in a dashed wireframe for demo purposes
            $paypaladvancedrender .= "  <iframe src='https://payflowlink.paypal.com?SECURETOKEN=$securetoken&SECURETOKENID=$securetokenid&MODE=$mode' width='490' height='565' border='0' frameborder='0' scrolling='no' allowtransparency='true'>\n</iframe>";
            $paypaladvancedrender .=  "</div>";*/
            return  "https://payflowlink.paypal.com?SECURETOKEN=$securetoken&SECURETOKENID=$securetokenid&MODE=$mode";

        }else {
            return false; // payment failed
            exit;
        }
    }

    public static function  chkPaypaladvanced($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($paymnetResult['RESULT'] == '0' && $paymnetResult['RESPMSG'] == 'Approved' &&$paymnetResult['AMT'] == $arrtwoPaySettings['Grandtotal'] ) {

                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $paymnetResult["PNREF"];
                return $paymentResult;
                // return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;

                // return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;

            //return false; // payment failed
            exit;
        }
    }

// Paypalflowlink

    public  function  payBreantree($arrtwoPaySettings = array()) {

        PageContext::includePath('paymentgateways/braintree/lib');
        try {
            Braintree_Configuration::merchantId($arrtwoPaySettings['Braintreemerchantid']);
            Braintree_Configuration::publicKey($arrtwoPaySettings['Braintreepublickey']);
            Braintree_Configuration::privateKey($arrtwoPaySettings['Braintreeprivatekey']);

//Check test mode


            if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y') {
                Braintree_Configuration::environment('sandbox');
            }

        }catch (Exception $e) {
            return $e;
        }


        $form_url = Braintree_TransparentRedirect::url();
        $firstName = $arrtwoPaySettings['fname'];
        $lastName = $arrtwoPaySettings['lname'];
        $email = $billadd['UserAddress']['email'];
        $tr_data = Braintree_TransparentRedirect::transactionData(
                array('redirectUrl' => "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH),
                'transaction' => array('amount' =>  $arrtwoPaySettings['Grandtotal'], 'type' => $arrtwoPaySettings['Paymenttype'])));


        $configValues = array('form_url' => $form_url,
                'firstName' => $arrtwoPaySettings['Firstname'],
                'lastName' => $arrtwoPaySettings['Lastname'],
                'email' => $arrtwoPaySettings['Email'],
                'tr_data' => $tr_data
        );

        return  $configValues;



    }

    public static function  chkBreantree($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($paymnetResult['http_status'] == 200 && $paymnetResult['kind'] == 'create_transaction') {
                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = "";
                return $paymentResult;

                //return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                //  return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }

// Paypalexpress

    public static function  payPaypalexpress($arrtwoPaySettings = array()) {


        PageContext::includePath('paymentgateways/paypalexpress');
        $objPaypalexpress =  new Paypalexpress();



        //Admin paymnet details

        $credential_array['api_uname']    = $arrtwoPaySettings["Paypalexpressusername"];
        $credential_array['api_pass']     = $arrtwoPaySettings["Paypalexpresspassword"];
        $credential_array['api_sig']      = $arrtwoPaySettings["Paypalexpresssignature"];

        if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y') {
            $credential_array['api_env']    = "sandbox";
        }
        else {
            $credential_array['api_env']    = "live";
        }


        //prevent timeout
        set_time_limit(0);

        $objPaypalexpress->setCredentials($credential_array);
        // $do_transaction = $objPaypalexpress->setExpressCheckout("PAYMENTACTION=Sale&AMT={$arrtwoPaySettings['Grandtotal']}&RETURNURL={$arrtwoPaySettings['ReturnURL']}&CANCELURL={$arrtwoPaySettings['CancelURL']}");

        $do_transaction = $objPaypalexpress->setExpressCheckout("PAYMENTACTION=".$arrtwoPaySettings['Grandtotal']."&AMT=".$arrtwoPaySettings['Grandtotal']."&RETURNURL=".$arrtwoPaySettings['ReturnURL']."&CANCELURL=".$arrtwoPaySettings['CancelURL']);

        if($do_transaction) {
            $paypalurl = $objPaypalexpress->getPaypalUrl() . "&useraction=commit";

            //  header("location:$paypalurl&useraction=commit");
        }

        return $paypalurl;
    }

    public static function  chkpayPaypalexpress($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($paymnetResult['PayerID'] != "" && $paymnetResult['token'] != "") {
                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $paymnetResult['PayerID'];
                return $paymentResult;

                //return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                //  return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }




    // function to check the google checkout is valid or not
    public static function chkGoogleCheckOut($paymnetResult, $arrGcheckSettings = array()) {

        if(isset ($paymnetResult) && count($paymnetResult) > 0) {
            if($paymnetResult['serial-number'] != "" ) {
                $paymentResult['Amount'] 		= $arrGcheckSettings['items']['amount'];
                $paymentResult['success'] 		= 1;
                $paymentResult['Message'] 		= "";
                $paymentResult['TransactionId'] = $paymnetResult['serial-number'];
                return $paymentResult;
                exit;
            }
            else {			// trnasaction fails
                $paymentResult['Amount'] 		= 0;
                $paymentResult['success'] 		= 0;
                $paymentResult['Message'] 		= "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                exit;
            }
        }
        else {			// transaction fails
            $paymentResult['Amount'] 		= 0;
            $paymentResult['success'] 		= 0;
            $paymentResult['Message'] 		= "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // 	return false; // payment failed
            exit;
        }
    }


    /*
	 * function to check the moneybookers payment success
    */
    Public static function chkMoneyBookers($paymnetResult,$arrMoneyBookers = array()) {
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($paymnetResult['mb_transaction_id'] != "" ) {
                $paymentResult['Amount'] 		= $paymnetResult['amount'];
                $paymentResult['success'] 		= 1;
                $paymentResult['Message'] 		= "";
                $paymentResult['TransactionId'] = $paymnetResult['mb_transaction_id'];
                return $paymentResult;
                exit;
            }
            else {			// trnasaction fails
                $paymentResult['Amount'] 		= 0;
                $paymentResult['success'] 		= 0;
                $paymentResult['Message'] 		= "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                exit;
            }
        }
        else {			// transaction fails
            $paymentResult['Amount'] 		= 0;
            $paymentResult['success'] 		= 0;
            $paymentResult['Message'] 		= "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            exit;
        }
    }





//Ogone

    public static function  payOgone($arrtwoPaySettings = array()) {


        PageContext::includePath('paymentgateways/ogone');
        $objOgone =  new Ogone();

        $objOgone->addField('PSPID', $arrtwoPaySettings["Ogonepspid"]);

        $objOgone->passPhrase = $arrtwoPaySettings["Ogonepassphrase"];

        $objOgone->addField('AMOUNT', $arrtwoPaySettings["Grandtotal"]);

        $objOgone->addField('CANCELURL', $arrtwoPaySettings["CancelURL"]);

        $objOgone->addField('DECLINEURL', $arrtwoPaySettings["DeclineURL"]);

        $objOgone->addField('EXCEPTIONURL', $arrtwoPaySettings["ExceptionURL"]);

        $objOgone->addField('ACCEPTURL', $arrtwoPaySettings["AcceptURL"]);

        $objOgone->addField('ORDERID', $arrtwoPaySettings["Orderid"]);


        $objOgone->addField('CURRENCY', $arrtwoPaySettings["Currency"]);


        $objOgone->addField('LANGUAGE', $arrtwoPaySettings["Language"]);

        $objOgone->addField('LOGO', $arrtwoPaySettings["Logo"]);

        $objOgone->addField('OPERATION',$arrtwoPaySettings["Operation"]);


        // Enable test mode if needed
        if($arrtwoPaySettings['Testmode'] == "Y" || $arrtwoPaySettings['Testmode'] == 'Y')
            $objOgone->enableTestMode();


        // User Details
        $objOgone->addField('CN', $arrtwoPaySettings["Firstname"] ." " . $arrtwoPaySettings["Lastname"]);
        $objOgone->addField('EMAIL',  $arrtwoPaySettings["Email"]);


        return  $objOgone->submitPaymentDisplay();



    }

    public static function  chkOgone($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if(($paymnetResult['NCERROR'] == '0' || $paymnetResult['NCERROR'] == 0)  && $paymnetResult['PAYID'] != "" && $paymnetResult['amount'] == $arrtwoPaySettings["Grandtotal"]) {


                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $paymnetResult["PAYID"];
                return $paymentResult;
// return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                // return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }

//Ogone

    public static function  payMoneybookers($arrtwoPaySettings = array()) {


        PageContext::includePath('paymentgateways/moneybookers');
        $objMoneybookers =  new Moneybrokers();

        // Specify your Moneybrokers email id
        $objMoneybookers->addField('pay_to_email', $arrtwoPaySettings["Paytoemail"]);

        // Url getting return post data
        $objMoneybookers->addField('status_url', $arrtwoPaySettings["StatusURL"]);
        $objMoneybookers->addField('return_url', $arrtwoPaySettings["ReturnURL"]);

        // Language
        $objMoneybookers->addField('language', $arrtwoPaySettings["Language"]);

        // Amount
        $objMoneybookers->addField('amount', $arrtwoPaySettings["Grandtotal"]);
        $objMoneybookers->addField('currency', $arrtwoPaySettings["Currency"]);

        $objMoneybookers->addField('detail1_description', $arrtwoPaySettings["Description"]);
        $objMoneybookers->addField('detail1_text', $arrtwoPaySettings["Detailtext"]);

        $objMoneybookers->addField('confirmation_note', $arrtwoPaySettings["Confirmationnote"]);


        return   $objMoneybookers->submitPaymentDisplay();



    }

    public static function  chkMoneybookers1($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if(($paymnetResult['NCERROR'] == '0' || $paymnetResult['NCERROR'] == 0)  && $paymnetResult['PAYID'] != "" && $paymnetResult['amount'] == $arrtwoPaySettings["Grandtotal"]) {

                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                $paymentResult['TransactionId'] = $paymnetResult["PAYID"];
                return $paymentResult;
                // return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                //return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }

    public static function paypal($arrtwoPaySettings = array()) {

        PageContext::includePath('paymentgateways/paypal');
        $p = new paypal_class;

        if($arrtwoPaySettings['Testmode'] == 'Y') {
            $paypalurl 			= "https://www.sandbox.paypal.com/cgi-bin/webscr";
            $paypalbuttonurl 	= "https://www.sandbox.paypal.com/en_US/i/btn/x-click-but23.gif" ;
        }else {
            $paypalurl 			= "https://www.paypal.com/cgi-bin/webscr";
            $paypalbuttonurl 	= "https://www.paypal.com/en_US/i/btn/x-click-but23.gif" ;
        }

        $p->paypal_url 			= $paypalurl;
        $p->add_field('business', 		$arrtwoPaySettings['Paypalemail']);
        $p->add_field('return', 		$arrtwoPaySettings['resultURL']);
        $p->add_field('cancel_return', $arrtwoPaySettings['cancelURL']);
        $p->add_field('notify_url', 	 $arrtwoPaySettings['notifyURL']);
        $p->add_field('item_name', 	$arrtwoPaySettings['Itemname']);
        $p->add_field('amount', 		$arrtwoPaySettings['Grandtotal']);
        $p->add_field('custom', 	$arrtwoPaySettings['Transactid']);


        //pay pal button
        $dbObj = new Db();
        $BNCode =  $dbObj->selectRow("Settings","value","settingfield='paypal_bn_code'");
        $p->add_field('bn', 	$BNCode);

//echo $p->submit_paypal_post();
        return $p->submit_paypal_post();

    }

    public static function paypalsubscription($arrtwoPaySettings = array()) {

    PageContext::includePath('paymentgateways/paypal');

     $p = new paypal_class;

     $paypalurl = "https://www.paypal.com/cgi-bin/webscr";

     $paypalbuttonurl 	= "https://www.paypal.com/en_US/i/btn/x-click-but23.gif" ;

      if($arrtwoPaySettings['Testmode'] == 'Y')  {

        $paypalurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        $paypalbuttonurl = "https://www.sandbox.paypal.com/en_US/i/btn/x-click-but23.gif" ;

      }
            
             $p->paypal_url 			= $paypalurl;
             if($arrtwoPaySettings['t1']!='L') {
                $p->add_field('cmd','_xclick-subscriptions');
             }
             $p->add_field('business', 		$arrtwoPaySettings['Paypalemail']);
             $p->add_field('item_name', 	$arrtwoPaySettings['Itemname']);
             if(isset($arrtwoPaySettings['ItemNumber'])) {
                 // Item Number
                 $p->add_field('item_number', 	$arrtwoPaySettings['ItemNumber']);
             }
             $p->add_field('notify_url', 	 $arrtwoPaySettings['notifyURL']);
             $p->add_field('return', 		$arrtwoPaySettings['resultURL']);
             $p->add_field('cancel_return', $arrtwoPaySettings['cancelURL']);
             $p->add_field('amount', 		$arrtwoPaySettings['Grandtotal']);
             $p->add_field('custom', 	$arrtwoPaySettings['Transactid']);
              if($arrtwoPaySettings['t1']!='L') {
             $p->add_field('a1', 	$arrtwoPaySettings['a1']);
             $p->add_field('p1', 	$arrtwoPaySettings['p1']);
             $p->add_field('t1', 	$arrtwoPaySettings['t1']);
             $p->add_field('a3', 	$arrtwoPaySettings['a3']);
             $p->add_field('p3', 	$arrtwoPaySettings['p3']);
             $p->add_field('t3', 	$arrtwoPaySettings['t3']);
             $p->add_field('src', 	$arrtwoPaySettings['src']);
             $p->add_field('sra', 	$arrtwoPaySettings['sra']);
             $p->add_field('no_note', 	$arrtwoPaySettings['no_note']);
             $p->add_field('currency_code', 	CURRENCY);
             $p->add_field('modify', 	$arrtwoPaySettings['modify']);
             $p->add_field('subscr_date', 	$arrtwoPaySettings['subscr_date']);
              }

             //pay pal button

             $dbObj = new Db();
             $BNCode =  $dbObj->selectRow("Settings","value","settingfield='paypal_bn_code'");
             $p->add_field('bn', $BNCode);
             //echo $p->submit_paypal_post();

             return $p->submit_paypal_post();

    } // End Function

    public static function  chkPaypal($paymnetResult, $arrtwoPaySettings = array()) {
        $paymentResult = array();
        if(isset ($paymnetResult) && count($paymnetResult) > 0) {

            if($arrtwoPaySettings['Testmode'] == 'Y')
                $paymnetResult['payment_status'] = 'Completed';
            if(($paymnetResult['payment_status'] == 'Completed' || $paymnetResult['st'] == 'Completed')  && ($paymnetResult['payment_fee'] == $arrtwoPaySettings["Grandtotal"] || $paymnetResult['amt'] == $arrtwoPaySettings["Grandtotal"])) {

                $paymentResult['Amount'] = $arrtwoPaySettings['Grandtotal'];
                $paymentResult['success'] = 1;
                $paymentResult['Message'] = "";
                // $paymentResult['Date'] = date('m-d-y');
                if(isset ($paymnetResult["tx"]))
                    $paymentResult['TransactionId'] = $paymnetResult["tx"];
                else if(isset ($paymnetResult["txn_id"]))
                    $paymentResult['TransactionId'] = $paymnetResult["txn_id"];
                else
                    $paymentResult['TransactionId'] = "";
                return $paymentResult;
                // return true; // payment sucess
                exit;
            }
            else {
                $paymentResult['Amount'] = 0;
                $paymentResult['success'] = 0;
                $paymentResult['Message'] = "Payment Falied";
                $paymentResult['TransactionId'] = "";
                return $paymentResult;
                //return false; // payment failed
                exit;
            }
        }else {
            $paymentResult['Amount'] = 0;
            $paymentResult['success'] = 0;
            $paymentResult['Message'] = "Payment Falied";
            $paymentResult['TransactionId'] = "";
            return $paymentResult;
            // return false; // payment failed
            exit;
        }
    }

    /*
	 * function to check the your pay result
    */
    public static function chkYourPay($paymnetResult, $arrtwoPaySettings = array()) {
        //TODO: need to add the validation in live ssl
        return true;

    }

    public static function authoriz($dataArr) {

        PageContext::includePath('paymentgateways/authorize');
        $authorizeObj   = new  Authorize_class();
        $authorizeInfo =array();

        $paySettings = Payments::getAuthorizeSettings();
        $adminCurrency = $dataArr['Currency'];
        $authorizeLoginId =  $paySettings['authorizeLoginId'];
        $authorizeTransKey =  $paySettings['authorizeTransKey'];
        $authorizeEmail =  $paySettings['authorizeEmail'];
        $authorizeTestMode =  $paySettings['authorizeTestMode'];

        $authorizeInfo['desc'] = $dataArr['desc'];
        $authorizeInfo['x_login'] = $authorizeLoginId;
        $authorizeInfo['x_tran_key'] = $authorizeTransKey;
        $authorizeInfo['email'] = $authorizeEmail;
        $authorizeInfo['testMode'] = $authorizeTestMode;
        $authorizeInfo['currency_code'] =$adminCurrency;
        $authorizeInfo['amount'] = $dataArr['amount'];
        $authorizeInfo['expMonth'] = $dataArr['expMonth'];
        $authorizeInfo['expYear'] = $dataArr['expYear'];
        $authorizeInfo['cvv'] = $dataArr['cvv'];
        $authorizeInfo['ccno'] = $dataArr['ccno'];
        $authorizeInfo['fName'] = $dataArr['fName'];
        $authorizeInfo['lName'] = $dataArr['lName'];
        $authorizeInfo['add1'] = $dataArr['add1'];
        $authorizeInfo['city'] = $dataArr['city'];
        $authorizeInfo['state'] = $dataArr['state'];
        $authorizeInfo['country'] = $dataArr['country'];
        $authorizeInfo['zip'] = $dataArr['zip'];

        $return = $authorizeObj->submit_authorize_post($authorizeInfo);

        $details = $return[0];

        $transaction_id = $return[1];
        switch ($details) {
            case "1": // Credit Card Successfully Charged
                $paymentsuccessful = 1;
                $transactionid = $return[6];
                break;
            case "2":
                $paymentsuccessful = 0;
                $paymenterror = "The card has been declined";
                $paymenterror .= "<br>" . $return[3];
                $transactionid = NULL;
                break;
            case "4":
                $paymentsuccessful = 0;
                $paymenterror = "The card has been held for review";
                $paymenterror .= "<br>" . $return[3];
                $transactionid = NULL;
                break;
            default: // Credit Card Not Successfully Charged
                $paymentsuccessful = 0;
                $paymenterror = "Error";
                $paymenterror .= "<br>" . $return[3];
                $transactionid = NULL;
                break;
        }


        $paymentResult['Amount'] = $dataArr['amount'];
        $paymentResult['success'] = $paymentsuccessful;
        $paymentResult['Message'] = $paymenterror;
        $paymentResult['TransactionId'] = $transactionid;

        return $paymentResult;

        /***************************** Payment Area *******************************/

    }

    public static function getAuthorizeSettings() {
        Paymenthelper::$dbObj = new Db();

        $paySettings = array();

        $paySettings['authorizeEnable']      =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_enable'");
        $paySettings['authorizeLoginId']    =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_loginid'");
        $paySettings['authorizeTransKey']    =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_transkey'");
        $paySettings['authorizeEmail']      =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_email'");
        $paySettings['authorizeTestMode']    =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='authorize_test_mode'");
        if($paySettings['authorizeTestMode'] =="on")
            $paySettings['authorizeTestMode'] ="Y";
// $paySettings['adminCurrency']     =   Paymenthelper::$dbObj->selectRow("Settings","value","settingfield='admin_currency'");
        return $paySettings;
    }


//Payment settings


    public static function getStripeSettings($testmode)
    {
      $dbObj = new Db();
      $paySettings = array();

      if($testmode=='Y')
      {
        $paySettings['SecretKey'] =  $dbObj->selectRow("Settings","value","settingfield='stripe_sandbox_secretkey'");
        $paySettings['PublishKey'] =  $dbObj->selectRow("Settings","value","settingfield='stripe_sandbox_publishkey'");
        $paySettings['WebhookSecretKey'] =  $dbObj->selectRow("Settings","value","settingfield='stripe_webhook_secret_key'");
        
      }
      else
      {

          $paySettings['SecretKey'] =  $dbObj->selectRow("Settings","value","settingfield='stripe_live_secretkey'");
          $paySettings['PublishKey'] =  $dbObj->selectRow("Settings","value","settingfield='stripe_live_publishkey'");
          $paySettings['WebhookSecretKey'] =  $dbObj->selectRow("Settings","value","settingfield='stripe_live_webhook_secret_key'");
      }

      $paySettings['WebhookURL'] =  $dbObj->selectRow("Settings","value","settingfield='stripe_webhook_url'");

      return $paySettings;


    }

    public static function getpaymentData($payment_id)
    {
User::$dbObj = new Db();
$paydata = User::$dbObj->selectQuery("SELECT * FROM ".User::$dbObj->tablePrefix. "payments WHERE vTransactionId='".$payment_id."'");
return $paydata;

    }

    public static function getwebhookData($user_id)
    {
User::$dbObj = new Db();
$paydata = User::$dbObj->selectQuery("SELECT * FROM ".User::$dbObj->tablePrefix. "payments WHERE nUId='".$user_id."' ORDER BY nPaymentId DESC LIMIT 1");
return $paydata;

    }

    public static function getProductLookup($productLookUpId)
    {
User::$dbObj = new Db();
$lookupdata = User::$dbObj->selectQuery("SELECT * FROM ".User::$dbObj->tablePrefix. "ProductLookup WHERE nPLId='".$productLookUpId."'");
return $lookupdata;

    }

    public static function getPaypalproSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['Paypalprousername'] =  $dbObj->selectRow("Settings","value","settingfield='paypalpro_username'");
        $paySettings['Paypalpropassword'] =  $dbObj->selectRow("Settings","value","settingfield='paypalpro_password'");
        $paySettings['Paypalprosignature'] =  $dbObj->selectRow("Settings","value","settingfield='paypalpro_signature'");
        $paySettings['Paypalprotestmode'] =  $dbObj->selectRow("Settings","value","settingfield='paypalpro_testmode'");
        if($paySettings['Paypalprotestmode'] =="on")
            $paySettings['Paypalprotestmode'] ="Y";
        $paySettings['Paypalproenable'] =  $dbObj->selectRow("Settings","value","settingfield='paypalpro_enable'");
        return $paySettings;

    }


    public static function getPaypalflowSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['Paypalflowvendorid'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflow_vendorid'");
        $paySettings['Paypalflowpassword'] =  $dbObj->selectRow("Settings","value","settingfield='payflow_password'");
        $paySettings['Paypalflowpartnerid'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflow_partnerid'");
        $paySettings['Paypalflowtestmode'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflow_testmode'");
        if($paySettings['Paypalflowtestmode'] =="on")
            $paySettings['Paypalflowtestmode'] ="Y";
        $paySettings['Paypalflowenable'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflow_enable'");
        return $paySettings;

    }

    public static function getPaypaladvancedSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['Paypaladvancedpassword'] =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_password'");
        $paySettings['Paypaladvancedusername'] =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_username'");
        $paySettings['Paypaladvancedvendorid'] =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_vendorid'");
        $paySettings['Paypaladvancedpartner'] =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_partnerid'");
        $paySettings['Paypaladvancedtestmode'] =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_testmode'");
        if($paySettings['Paypaladvancedtestmode'] =="on")
            $paySettings['Paypaladvancedtestmode'] ="Y";
        $paySettings['Paypaladvancedenable'] =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_enable'");
        return $paySettings;

    }

    public static function getPaypallinkSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['Paypalflowlinkpartnerid'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflowlink_partnerid'");
        $paySettings['Paypalflowlinkvendorid'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflowlink_vendorid'");
        $paySettings['Paypalflowlinktestmode'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflowlink_testmode'");
        if($paySettings['Paypalflowlinktestmode'] =="on")
            $paySettings['Paypalflowlinktestmode'] ="Y";
        $paySettings['Paypalflowlinkenable'] =  $dbObj->selectRow("Settings","value","settingfield='paypalflowlink_enable'");
        return $paySettings;

    }
    public static function getTwoCheckoutSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['TwoCheckoutvendorid'] =  $dbObj->selectRow("Settings","value","settingfield='twoco_vendorId'");
        $paySettings['TwoCheckouttestmode'] =  $dbObj->selectRow("Settings","value","settingfield='twoco_testmode'");
        if($paySettings['TwoCheckouttestmode'] =="on")
            $paySettings['TwoCheckouttestmode'] ="Y";
        $paySettings['TwoCheckoutenable'] =  $dbObj->selectRow("Settings","value","settingfield='twoco_enable'");
        return $paySettings;

    }

    public static function getPaypalXpresSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['PaypalXpresSignature'] =  $dbObj->selectRow("Settings","value","settingfield='paypalexpress_signature'");
        $paySettings['PaypalXpresPassword'] =  $dbObj->selectRow("Settings","value","settingfield='paypalexpress_password'");
        $paySettings['PaypalXpresUsername'] =  $dbObj->selectRow("Settings","value","settingfield='paypalexpress_username'");

        $paySettings['PaypalXprestestmode'] =  $dbObj->selectRow("Settings","value","settingfield='paypalexpress_testmode'");
        if($paySettings['PaypalXprestestmode'] =="on")
            $paySettings['PaypalXprestestmode'] ="Y";
        $paySettings['PaypalXpresenable'] =  $dbObj->selectRow("Settings","value","settingfield='paypalexpress_enable'");
        if($paySettings['PaypalXpresenable'] =="on")
            $paySettings['PaypalXpresenable'] ="Y";

        return $paySettings;

    }


    public static function getBraintreeSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['BraintreemerchantId'] =  $dbObj->selectRow("Settings","value","settingfield='braintree_merchantId'");
        $paySettings['Braintreepublickey'] =  $dbObj->selectRow("Settings","value","settingfield='braintree_publickey'");
        $paySettings['Braintreeprivatekey'] =  $dbObj->selectRow("Settings","value","settingfield='braintree_privatekey'");
        $paySettings['Braintreetestmode'] =  $dbObj->selectRow("Settings","value","settingfield='braintree_testmode'");
        if($paySettings['Braintreetestmode'] =="on")
            $paySettings['Braintreetestmode'] ="Y";
        $paySettings['Braintreeenable'] =  $dbObj->selectRow("Settings","value","settingfield='braintree_enable'");
        return $paySettings;

    }

    public static function getOgoneSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['Ogonepartnerid'] =  $dbObj->selectRow("Settings","value","settingfield='ogone_partnerid'");
        $paySettings['Ogonevendorid'] =  $dbObj->selectRow("Settings","value","settingfield='ogone_vendorid'");
        $paySettings['Ogonetestmode'] =  $dbObj->selectRow("Settings","value","settingfield='ogone_testmode'");
        if($paySettings['Ogonetestmode'] =="on")
            $paySettings['Ogonetestmode'] ="Y";
        $paySettings['Ogoneenable'] =  $dbObj->selectRow("Settings","value","settingfield='ogone_enable'");
        return $paySettings;

    }
    public static function getPaypalSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['Paypalidentitytoken'] =  $dbObj->selectRow("Settings","value","settingfield='paypalidentitytoken'");
        $paySettings['Paypalemail'] =  $dbObj->selectRow("Settings","value","settingfield='paypalemail'");
        $paySettings['Paypaltestmode'] =  $dbObj->selectRow("Settings","value","settingfield='enablepaypalsandbox'");
        if($paySettings['Paypaltestmode'] =="on")
            $paySettings['Paypaltestmode'] ="Y";
        $paySettings['Paypalenable'] =  $dbObj->selectRow("Settings","value","settingfield='enablepaypal'");
        return $paySettings;

    }

    /*
	 * function to get moneybookers account details
    */
    public static function getMoneyBookersSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['moneybookers_emailid'] = $dbObj->selectRow("Settings","value","settingfield='moneybookers_emailid'");
        return $paySettings;
    }



    /*
	 * function to get quickbook settings
    */

    public static function getQuickBookSettings() {
        $dbObj = new Db();
        $paySettings = array();
        $paySettings['quickbook_appname'] 	= $dbObj->selectRow("Settings","value","settingfield='quickbook_appname'");
        $paySettings['quickbook_key'] 		= $dbObj->selectRow("Settings","value","settingfield='quickbook_key'");
        $paySettings['quickbook_testmode'] 		= $dbObj->selectRow("Settings","value","settingfield='quickbook_testmode'");
        return $paySettings;

    }




    /*
	 * function to get google checkout informations
    */

    public static function getGoogleCheckoutSettings() {
        $dbObj 			= new Db();
        $paySettings 	= array();
        $paySettings['gcheck_merchant_id'] 	= $dbObj->selectRow("Settings","value","settingfield='gcheck_merchant_id'");
        $paySettings['gcheck_merchant_key'] = $dbObj->selectRow("Settings","value","settingfield='gcheck_merchant_key'");
        $paySettings['gcheck_server_type'] 	= $dbObj->selectRow("Settings","value","settingfield='gcheck_server_type'");
        $paySettings['gcheck_currency'] 	= $dbObj->selectRow("Settings","value","settingfield='gcheck_currency'");
        $paySettings['gcheck_btn_checkout'] = $dbObj->selectRow("Settings","value","settingfield='gcheck_btn_checkout'");
        return $paySettings;
    }


    /*
	 * function to get your pay account details
    */
    public static function getYoursPaySettings() {
        $dbObj 			= new Db();
        $paySettings 	= array();
        $paySettings['yourpay_storeid'] 	= $dbObj->selectRow("Settings","value","settingfield='yourpay_storeid'");
        $paySettings['yourpay_demo'] 		= $dbObj->selectRow("Settings","value","settingfield='yourpay_demo'");
        $paySettings['yourpay_pemfile'] 	= $dbObj->selectRow("Settings","value","settingfield='yourpay_pemfile'");

        return $paySettings;
    }


    public static function getEnabledPaymnets() {
        $dbObj = new Db();
        $enabledPaymnets       = array();
        $temp =  $dbObj->selectRow("Settings","value","settingfield='authorize_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['authorize_enable'] = "Y";
        
        
        $temp =  $dbObj->selectRow("Settings","value","settingfield='bluedog_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['bluedog_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='paypalflow_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['paypalflow_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='paypalpro_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['paypalpro_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='twoco_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['twoco_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='paypalexpress_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['paypalexpress_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='paypalflowlink_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['paypalflowlink_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='ogone_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['ogone_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='moneybookers_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['moneybookers_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='braintree_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['braintree_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='paypaladvanced_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['paypaladvanced_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='enablepaypal'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['paypal_enable'] = "Y";


        $temp =  $dbObj->selectRow("Settings","value","settingfield='enable_googlecheckout'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['googlecheckout_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='yourpay_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['yourpay_enable'] = "Y";

        $temp =  $dbObj->selectRow("Settings","value","settingfield='quickbook_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['quickbook_enable'] = "Y";

         $temp =  $dbObj->selectRow("Settings","value","settingfield='stripe_enable'");
        if($temp =='Y' || $temp == "on")
            $enabledPaymnets['stripe_enable'] = "Y";


        return $enabledPaymnets;

    }



    public static function doAllPaymants($paymantMethod = "",$arrtwoPaySettings = array(),$authorizeInfo=array()) {

        

        /*  if($paymantMethod == "paypalpro") {
        $paymnetResult = Payments::payPaypalpro($arrtwoPaySettings);
        if(isset ($paymnetResult)) {
            $paymentResult = Payments::chkpayPaypalpro($paymnetResult,$arrtwoPaySettings);
        }
    }*/



      
        $paymentResult = array();
        $paymentResult['Amount'] = 0;
        $paymentResult['Status'] = "Error";
        $paymentResult['Message'] = "";
        $paymentResult['TransactionId'] = "";

        if(count($arrtwoPaySettings) > 0) {


            switch ($paymantMethod) {
                case "paypalpro":
                    $paymnetResult = Payments::payPaypalpro($arrtwoPaySettings);
                    if(isset ($paymnetResult)) {
                        $paymentResult = Payments::chkpayPaypalpro($paymnetResult,$arrtwoPaySettings);

                    }
                    break;

                case "paypalflow":
                    $paymnetResult = Payments::payPaypalflow($arrtwoPaySettings);
                    if(isset ($paymnetResult)) {
                        $paymentResult = Payments::chkPaypalflow($paymnetResult,$arrtwoPaySettings);
                    }
                    break;

                case "authorize":
                    $paymentResult  =   User::authorizeCreditCardPayment($arrtwoPaySettings);
                    
                    break;


               case "bluedog":
                    $paymentResult  =   User::blueDogcreditPayment($authorizeInfo);
                    
                    break;

               case "stripe":


                     $paymentResult  =   1;
                    
                     break;
                         

               

                default:

                    break;
            }
        }

        return $paymentResult;
    } //End Function


    /*
	 * function to do the google checkout
    */

    public static function doGoogleCheckOut($arrGcheckOut = array()) {
        PageContext::includePath('paymentgateways/googlecheckout');


        $gCheckoutSettings = self::getGoogleCheckoutSettings();



        //check out parameters
        $arrGoogleCheckOut = array();
        $arrGoogleCheckOut['merchant_id'] 			= $gCheckoutSettings['gcheck_merchant_id'];
        $arrGoogleCheckOut['merchant_key'] 			= $gCheckoutSettings['gcheck_merchant_key'];
        $arrGoogleCheckOut['server_type'] 			= $gCheckoutSettings['gcheck_server_type'];
        $arrGoogleCheckOut['currency'] 				= $gCheckoutSettings['gcheck_currency'];
        $arrGoogleCheckOut['btn_checkout'] 			= $gCheckoutSettings['gcheck_btn_checkout'];
        $arrGoogleCheckOut['url_edit_cart'] 		= $arrGcheckOut['url_edit_cart'];
        $arrGoogleCheckOut['url_continue_shopping'] = $arrGcheckOut['url_continue_shopping'];
        $arrGoogleCheckOut['items'] 				= $arrGcheckOut['items'];


        // create the object and initialise the google checkout
        $gCheck 	= new GoogleCheckOut();
        $display 	= $gCheck->initiator($arrGoogleCheckOut);
        return $display;
    }




    /*
	 * your pay payment gateway
    */
    public static function doYourPay($arrYourPay=array()) {
        
        

        PageContext::includePath('paymentgateways/yourpay');

        $myorder["port"]       	= "1129";
        
        $myorder["keyfile"]    	= BASE_URL."project/lib/paymentgateways/yourpay/pem/".$arrYourPay['keyfile'];
       
        

        $myorder["configfile"] 	= urlencode($arrYourPay['yourpay_storeid']);        # Change this to your store number
        $myorder["ordertype"]   = $arrYourPay['ordertype'];
        if($arrYourPay['yourpay_demo'] =="Y") {
            $myorder["host"] 	= "staging.linkpt.net";
            $myorder["result"]  = "SALE";# For a test, set result to GOOD, DECLINE, or DUPLICATE
        }else {
            $myorder["host"]    = "secure.linkpt.net";
            $myorder["result"]  = "LIVE";
        }

        $myorder["cardnumber"]  = $arrYourPay['yp_cardno'];
        $myorder["cardexpmonth"]= $arrYourPay['yp_expm'] ;
        $myorder["cardexpyear"] = $arrYourPay['yp_expy'];
        $myorder["chargetotal"] = urlencode($arrYourPay['userinfo']['amount']);


        # BILLING INFO 4111111111111111
        $myorder["name"]     	= urlencode($arrYourPay['userinfo']['fName'])." ".urlencode($arrYourPay['userinfo']['lName']);
        $myorder["company"]  	= "-NA-";
        $myorder["address1"] 	= urlencode($arrYourPay['userinfo']['add1']);
        $myorder["city"]     	= urlencode($arrYourPay['userinfo']['city']);
        $myorder["state"]    	= urlencode($arrYourPay['userinfo']['state']);
        $myorder["country"]  	= urlencode($arrYourPay['userinfo']['country']);
        $myorder["phone"]    	= urlencode('');
        $myorder["email"]    	= urlencode($arrYourPay['userinfo']['email']);
        //$myorder["debugging"] 	= "true";  # for development only - not intended for production use
        
        $yourpay 	= new YourpayComponent();
        $result 	= $yourpay->curl_process($myorder);  # use curl methods
                
        return $result;
    }


    /*
	 * money bookers payment gateway
    */
    public static function doMoneyBookers($moneyBookersInfo = array()) {
        PageContext::includePath('paymentgateways/moneybookers');
        $moneyBrokers 	= new Moneybrokers();

        // Specify your Moneybrokers details
        $moneyBrokers->addField('pay_to_email', 		$moneyBookersInfo['pay_to_email']);
        $moneyBrokers->addField('status_url', 			$moneyBookersInfo['status_url']);
        $moneyBrokers->addField('language', 			$moneyBookersInfo['language']);
        $moneyBrokers->addField('amount', 				$moneyBookersInfo['amount']);
        $moneyBrokers->addField('currency',				$moneyBookersInfo['currency']);
        $moneyBrokers->addField('detail1_description', 	$moneyBookersInfo['detail1_description']);
        $moneyBrokers->addField('detail1_text', 		$moneyBookersInfo['detail1_text']);
        $moneyBrokers->addField('return_url', 			$moneyBookersInfo['return_url']);
        $moneyBrokers->addField('confirmation_note',	$moneyBookersInfo['confirmation_note']);
        return $moneyBrokers->submitPaymentDisplay();

    }



    /*
	 * quickbook payment gateway
    */

    public static function doQuickbookPayment($quickbookinfo = array()) {
        PageContext::includePath('paymentgateways/quickbook');
        $objQuickbook 		= new quickbook();

        $result 			= $objQuickbook->curl_process($quickbookinfo);

        $payResult 			= $result->QBMSXMLMsgsRs->CustomerCreditCardChargeRs;
        // check the payment result
        if($payResult->PaymentStatus == 'Completed')	// payment success
        {
            $payRes['success'] 			= 1;
            $payRes['TransactionId'] 	= (string)$payResult->CreditCardTransID;
            $payRes['ReconBatchID'] 	= (string)$payResult->ReconBatchID;
            $payRes['ClientTransID'] 	= (string)$payResult->ClientTransID;

        }
        else // payment error
        {
            $payRes['success'] 			= 0;
            $payRes['TransactionId'] 	= '';
            $payRes['ReconBatchID'] 	= '';
            $payRes['ClientTransID'] 	= '';
        }
        return $payRes;
    }

    public static function paypalsubscriptionIPN($dataSettingsArr = array()) {

         $dbObj = new Db();

         $responseArr = array();

         PageContext::includePath('paymentgateways/paypal');

         $p = new paypal_class;

         $paypalurl = "ssl://www.paypal.com";

         $testMode =  $dbObj->selectRow("Settings","value","settingfield='enablepaypalsandbox'");

         if($testMode == 'Y')  {
            $paypalurl = "ssl://www.sandbox.paypal.com";
         }

         $p->paypal_url = $paypalurl;

         $responseArr = $p->validate_ipn();

         /************ Expected Results in Response Array **********/
         //$dataArr["error"] = ""; // returns the error message if there is any error
         //$dataArr["status"] = ""; // returns the status of the payment 1 => success, 0 => failure
         //$dataArr["data"] = ""; // returns the post data with key and value
         /*********************************************************/

        return $responseArr;

    } // End Function

}//End of class

?>