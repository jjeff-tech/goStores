<?php

/**
 * 2CheckOut Class
 *
 * Integrate the 2CheckOut payment gateway in your site using this easy
 * to use library. Just see the example code to know how you should
 * proceed. Btw, this library does not support the recurring payment
 * system. If you need that, drop me a note and I will send to you.
 *
 * @package     Payment Gateway
 * @category    Library
 * @author      Md Emran Hasan <phpfour@gmail.com>
 * @link        http://www.phpfour.com
 */
class Paypaladvanced {

    /**
     * Secret word to be used for IPN verification
     *
     * @var string
     */
    public $secret;

    /**
     * Initialize the 2CheckOut gateway
     *
     * @param none
     * @return void
     */

    /**
     * Holds the last error encountered
     *
     * @var string
     */
    public $lastError;

    /**
     * Do we need to log IPN results ?
     *
     * @var boolean
     */
    public $logIpn;

    /**
     * File to log IPN results
     *
     * @var string
     */
    public $ipnLogFile;

    /**
     * Payment gateway IPN response
     *
     * @var string
     */
    public $ipnResponse;

    /**
     * Are we in test mode ?
     *
     * @var boolean
     */
    public $testMode;

    /**
     * Field array to submit to gateway
     *
     * @var array
     */
    public $fields = array();

    /**
     * IPN post values as array
     *
     * @var array
     */
    public $ipnData = array();

    /**
     * Payment gateway URL
     *
     * @var string
     */
    public $gatewayUrl;

    //*****new properties
    public $environment;
    public $methodName;
    public $API_Vendor;
    public $API_Password;
    public $API_partnerID;


    /**
     * Initialization constructor
     *
     * @param none
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
        $this->lastError = '';
        $this->logIpn = TRUE;
        $this->ipnResponse = '';
        $this->testMode = FALSE;
        // Some default values of the class
        $this->gatewayUrl = 'https://payflowpro.paypal.com';
        $this->ipnLogFile = '';
        
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
    public function enableTestMode()
    {
        $this->testMode = TRUE;

    }

    /**
     * Set the secret word
     *
     * @param string the scret word
     * @return void
     */
    public function setSecret($word)
    {
        if (!empty($word))
        {
            $this->secret = $word;
        }
    }

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
    public function validateIpn()
    {

    }
     /**
     * Adds a key=>value pair to the fields array
     *
     * @param string key of field
     * @param string value of field
     * @return
     */
    function addField($field, $value)
    {
        $this->fields[$field] = $value;
    }

    /**
     * Submit Payment Request
     *
     * Generates a form with hidden elements from the fields array
     * and submits it to the payment gateway URL. The user is presented
     * a redirecting message along with a button to click.
     *
     * @param none
     * @return void
     */
   function submitPayment()
    {

        $this->prepareSubmit();


    }

    function submitPaymentDisplay()
    {

         $this->prepareSubmit();

        // Set up your API credentials, PayPal end point, and API version.
	/*$API_UserName = urlencode('mahiat_1351864475_biz_api1.yahoo.com'); //urlencode('my_api_username');
	$API_Password = urlencode('1351864518'); //urlencode('my_api_password');
	$API_Signature = urlencode('A0yYhIEfABicc8vcPNDIgocAdlatAiI9tB-cYE4rdnod1VbCLzTG6fu6');//urlencode('my_api_signature');
        $API_Endpoint = "https://api-3t.paypal.com/nvp";
	*/
	$this->version = urlencode('51.0');

	// Set the curl parameters.
	$this->ch = curl_init();
	curl_setopt($this->ch, CURLOPT_URL, $this->gatewayUrl);
	curl_setopt($this->ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($this->ch, CURLOPT_POST, 1);




        // Add request-specific fields to the request string.

        foreach ($this->fields as $name => $value)
        {
              $this->nvpStr.="&$name=$value";
        }

	
        // Set the request as a POST FIELD for curl.
        $this->nvpreq = "VENDOR=$this->API_Vendor&PWD=$this->API_Password&PARTNER=$this->API_partnerID$this->nvpStr";
       //echo '<br>'. $this->nvpreq;exit;
        
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->nvpreq);

	// Get response from the server.
	$this->httpResponse = curl_exec($this->ch);
 //echo '<br>'. $this->httpResponse;exit;
        if(!$this->httpResponse) {
		//exit("$this->methodName failed: ".curl_error($this->ch).'('.curl_errno($this->ch).')');
                return array('RESULT'=>"1",
                'ERROR' =>"$this->methodName failed: ".curl_error($this->ch.'('.curl_errno($this->ch).')'));
	}

	// Extract the response details.
	$this->httpResponseAr = explode("&", $this->httpResponse);

	$this->httpParsedResponseAr = array();
	foreach ($this->httpResponseAr as $i => $value) {
		$this->tmpAr = explode("=", $value);
		if(sizeof($this->tmpAr) > 1) {
			$this->httpParsedResponseAr[$this->tmpAr[0]] = urldecode($this->tmpAr[1]);
		}
	}

	if((0 == sizeof($this->httpParsedResponseAr)) || !array_key_exists('RESULT', $this->httpParsedResponseAr)) {
            return array('RESULT'=>"1",
                'ERROR' =>"$this->methodName failed: ".curl_error($this->ch.'('.curl_errno($this->ch).')'));
		//exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

	return $this->httpParsedResponseAr;
    }

    /**
     * Perform any pre-posting actions
     *
     * @param none
     * @return none
     */
    function prepareSubmit()
    {
        if($this->testMode){
            $this->gatewayUrl = "https://pilot-payflowpro.paypal.com";;
        }
    }

    /**
     * Enables the test mode
     *
     * @param none
     * @return none
     */
  //  abstract protected function enableTestMode();

    /**
     * Validate the IPN notification
     *
     * @param none
     * @return boolean
     */
  //  abstract protected function validateIpn();

    /**
     * Logs the IPN results
     *
     * @param boolean IPN result
     * @return void
     */
    function logResults($success)
    {

        if (!$this->logIpn) return;

        // Timestamp
        $text = '[' . date('m/d/Y g:i A').'] - ';

        // Success or failure being logged?
        $text .= ($success) ? "SUCCESS!\n" : 'FAIL: ' . $this->lastError . "\n";

        // Log the POST variables
        $text .= "IPN POST Vars from gateway:\n";
        foreach ($this->ipnData as $key=>$value)
        {
            $text .= "$key=$value, ";
        }

        // Log the response from the paypal server
        $text .= "\nIPN Response from gateway Server:\n " . $this->ipnResponse;

        // Write to log
        $fp = fopen($this->ipnLogFile,'a');
        fwrite($fp, $text . "\n\n");
        fclose($fp);
    }
}