<?php
class Paypalexpress {
    public $name = 'Paypal';
    public $useTable = false;
     
    //configuration
    public $environment = 'sandbox';   // or 'beta-sandbox' or 'live'
    public $version = '65.1';
    //give correct info below
    public $API_UserName  = null;
    public $API_Password  = null;
    public $API_Signature = null;      
    //variables 
    public $errors        = null;    
    public $token         = null;
    public $transId       = null;
        
     
    /**
     * Send HTTP POST Request
     *
     * @param   string  The API method name
     * @param   string  The POST Message fields in &name=value pair format
     * @return  array   Parsed HTTP Response body
     */
    function PPHttpPost($methodName, $nvpStr) {
        // Set up your API credentials, PayPal end point, and API version.
        $API_UserName = $this->API_UserName;
        $API_Password = $this->API_Password;
        $API_Signature = $this->API_Signature;
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
        if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {
            $API_Endpoint = "https://api-3t.$this->environment.paypal.com/nvp";
        }
        $version = urlencode($this->version);
 
        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
 
        // Turn off the server and peer verification (TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
 
        // Set the API operation, version, and API signature in the request.
        $nvpreq = "METHOD=$methodName&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature&$nvpStr";
 
        // Set the request as a POST FIELD for curl.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
 
        // Get response from the server.
        $httpResponse = curl_exec($ch);
 
        if(!$httpResponse) {
            exit("$methodName failed: ".curl_error($ch).'('.curl_errno($ch).')');
        }
 
        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);
 
        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if(sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }
 
        if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }
 
        return $httpParsedResponseAr;
    }
    
    function setCredentials($credential_array)
    {
        $this->API_UserName     = $credential_array['api_uname'];
        $this->API_Password     = $credential_array['api_pass'];
        $this->API_Signature    = $credential_array['api_sig'];
        $this->environment      = $credential_array['api_env'];
    }
     
    /*
     * get PayPal Url for redirecting page
     */
    function getPaypalUrl() {     
        $payPalURL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token={$this->token}";
        if("sandbox" === $this->environment || "beta-sandbox" === $this->environment) {         
            $payPalURL = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token={$this->token}";
        }
        return $payPalURL;
    }
 
         
    /*
     * call PayPal API: SetExpressCheckout
     */
    function setExpressCheckout($nvpStr) {
        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = $this->PPHttpPost('SetExpressCheckout', $nvpStr);
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            $this->token = urldecode($httpParsedResponseAr["TOKEN"]);           
            return true;
        } else  {
            $this->errors = $httpParsedResponseAr;
            return false;           
        }
    }
 
    /*
     * call PayPal API: DoExpressCheckoutPayment
     */
    function doExpressCheckoutPayment($nvpStr) {
        // Execute the API operation; see the PPHttpPost function above.
        $httpParsedResponseAr = $this->PPHttpPost('DoExpressCheckoutPayment', $nvpStr); 
        if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            $this->transId = urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);         
            return true;
        } else  {
            $this->errors = $httpParsedResponseAr;
            return false;
        }       
    }
}