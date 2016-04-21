<?php
/*******************************************************************************
 *                      PHP Paypal IPN Integration Class
 *******************************************************************************
 *      Author:     Micah Carrick
 *      Email:      email@micahcarrick.com
 *      Website:    http://www.micahcarrick.com
 *
 *      File:       paypal.class.php
 *      Version:    1.3.0
 *      Copyright:  (c) 2005 - Micah Carrick
 *                  You are free to use, distribute, and modify this software
 *                  under the terms of the GNU General Public License.  See the
 *                  included license.txt file.
 *
 *******************************************************************************
 *  VERION HISTORY:
 *      v1.3.0 [10.10.2005] - Fixed it so that single quotes are handled the
 *                            right way rather than simple stripping them.  This
 *                            was needed because the user could still put in
 *                            quotes.
 *
 *      v1.2.1 [06.05.2005] - Fixed typo from previous fix :)
 *
 *      v1.2.0 [05.31.2005] - Added the optional ability to remove all quotes
 *                            from the paypal posts.  The IPN will come back
 *                            invalid sometimes when quotes are used in certian
 *                            fields.
 *
 *      v1.1.0 [05.15.2005] - Revised the form output in the submit_paypal_post
 *                            method to allow non-javascript capable browsers
 *                            to provide a means of manual form submission.
 *
 *      v1.0.0 [04.16.2005] - Initial Version
 *
 *******************************************************************************
 *  DESCRIPTION:
 *
 *      NOTE: See www.micahcarrick.com for the most recent version of this class
 *            along with any applicable sample files and other documentaion.
 *
 *      This file provides a neat and simple method to interface with paypal and
 *      The paypal Instant Payment Notification (IPN) interface.  This file is
 *      NOT intended to make the paypal integration "plug 'n' play". It still
 *      requires the developer (that should be you) to understand the paypal
 *      process and know the variables you want/need to pass to paypal to
 *      achieve what you want.
 *
 *      This class handles the submission of an order to paypal aswell as the
 *      processing an Instant Payment Notification.
 *
 *      This code is based on that of the php-toolkit from paypal.  I've taken
 *      the basic principals and put it in to a class so that it is a little
 *      easier--at least for me--to use.  The php-toolkit can be downloaded from
 *      http://sourceforge.net/projects/paypal.
 *
 *      To submit an order to paypal, have your order form POST to a file with:
 *
 *          $p = new paypal_class;
 *          $p->add_field('business', 'somebody@domain.com');
 *          $p->add_field('first_name', $_POST['first_name']);
 *          ... (add all your fields in the same manor)
 *          $p->submit_paypal_post();
 *
 *      To process an IPN, have your IPN processing file contain:
 *
 *          $p = new paypal_class;
 *          if ($p->validate_ipn()) {
 *          ... (IPN is verified.  Details are in the ipn_data() array)
 *          }
 *
 *
 *      In case you are new to paypal, here is some information to help you:
 *
 *      1. Download and read the Merchant User Manual and Integration Guide from
 *         http://www.paypal.com/en_US/pdf/integration_guide.pdf.  This gives
 *         you all the information you need including the fields you can pass to
 *         paypal (using add_field() with this class) aswell as all the fields
 *         that are returned in an IPN post (stored in the ipn_data() array in
 *         this class).  It also diagrams the entire transaction process.
 *
 *      2. Create a "sandbox" account for a buyer and a seller.  This is just
 *         a test account(s) that allow you to test your site from both the
 *         seller and buyer perspective.  The instructions for this is available
 *         at https://developer.paypal.com/ as well as a great forum where you
 *         can ask all your paypal integration questions.  Make sure you follow
 *         all the directions in setting up a sandbox test environment, including
 *         the addition of fake bank accounts and credit cards.
 *
 *******************************************************************************
*/

class paypal_class {

   var $last_error;                 // holds the last error encountered

   var $ipn_log;                    // bool: log IPN results to text file?

   var $ipn_log_file;               // filename of the IPN log

   var $ipn_response = array();     // holds the IPN response from paypal

   var $ipn_response_data = array();     // holds the IPN response from paypal

   var $ipn_data = array();         // array contains the POST values for IPN

   var $fields = array();           // array holds the fields to submit to paypal


   function paypal_class() {

      // initialization constructor.  Called when class is created.

     // $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';

      $this->paypal_url = ' https://www.sandbox.paypal.com/cgi-bin/webscr';

      $this->last_error = '';

      $this->ipn_log_file = '.ipn_results.log';
      $this->ipn_log = true;
      $this->ipn_response = '';

      // populate $fields array with a few default values.  See the paypal
      // documentation for a list of fields and their data types. These defaul
      // values can be overwritten by the calling script.

      $this->add_field('rm','2');           // Return method = POST
      $this->add_field('cmd','_xclick');

   }

   function add_field($field, $value) {

      // adds a key=>value pair to the fields array, which is what will be
      // sent to paypal as POST variables.  If the value is already in the
      // array, it will be overwritten.

      $this->fields["$field"] = $value;
   }

   function submit_paypal_post() {

      // this function actually generates an entire HTML page consisting of
      // a form with hidden elements which is submitted to paypal via the
      // BODY element's onLoad attribute.  We do this so that you can validate
      // any POST vars from you custom form before submitting to paypal.  So
      // basically, you'll have your own form which is submitted to your script
      // to validate the data, which in turn calls this function to create
      // another hidden form and submit to paypal.

      // The user will briefly see a message on the screen that reads:
      // "Please wait, your order is being processed..." and then immediately
      // is redirected to paypal.

      $renderHTML = "<html>\n";
      $renderHTML .= "<head><title>Processing Payment...</title></head>\n";
      $renderHTML .= "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
      $renderHTML .= "<center><h2>Please wait, your order is being processed and you";
      $renderHTML .=" will be redirected to the paypal website.</h2></center>\n";
      $renderHTML .= "<form method=\"post\" name=\"paypal_form\" ";
      $renderHTML .= "action=\"".$this->paypal_url."\">\n";

      foreach ($this->fields as $name => $value) {
         $renderHTML .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
      }
      $renderHTML .= "<center><br/><br/>If you are not automatically redirected to ";
      $renderHTML .= "paypal within 5 seconds...<br/><br/>\n";
      $renderHTML .= "<input type=\"submit\" value=\"Click Here\"></center>\n";

      $renderHTML .= "</form>\n";
      $renderHTML .= "</body></html>\n";
      return $renderHTML;

   }

   function validate_ipn() {

      // parse the paypal URL
      $url_parsed=parse_url($this->paypal_url);

      // generate the post string from the _POST vars aswell as load the
      // _POST vars into an array

        $request = 'cmd=_notify-validate';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $request .= "&$key=$value";
        }

        // post back to PayPal system to validate
        $header .= "POST https://".$url_parsed["host"]."/cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Host: ".$url_parsed["host"]."\r\n";
        $header .= "Content-Length: " . strlen($request) . "\r\n\r\n";

        $fp = fsockopen ($this->paypal_url, 443, $errno, $errstr, 30);

        if (!$fp) {
             // HTTP ERROR
             $this->last_error = "fsockopen error no. $errno: $errstr";
             $this->ipn_response["error"] = $this->last_error;
             $this->ipn_response["status"] = 0;

        } else {

            fputs ($fp, $header . $request);

            while (!feof($fp)) {

                $res = fgets ($fp, 1024);

                if (strcmp ($res, "VERIFIED") == 0) {

                // PAYMENT VALIDATED & VERIFIED!

                    if(isset($_POST)){
                        foreach($_POST as $key => $value){
                            $this->ipn_response_data["$key"] = $value;
                        }
                    }
                    if($_POST['payment_status'] == 'Completed' || $_POST['payment_status'] == 'Pending'){
                         $this->last_error = "";
                         $this->ipn_response["error"] = $this->last_error;
                         $this->ipn_response["status"] = 1;

                    } else {
                         $this->last_error = "Payment Failed";
                         $this->ipn_response["error"] = $this->last_error;
                         $this->ipn_response["status"] = 0;
                    }
                } else if (strcmp ($res, "INVALID") == 0) {

                // PAYMENT INVALID & INVESTIGATE MANUALY!
                    $this->last_error = "Payment Invalid";
                    $this->ipn_response["error"] = $this->last_error;
                    $this->ipn_response["status"] = 0;

                }
            }
            fclose ($fp);
        }


        $this->ipn_response["data"] = $this->ipn_response_data;

        return $this->ipn_response;

   }

   function log_ipn_results($success) {

      if (!$this->ipn_log) return;  // is logging turned off?

      // Timestamp
      $text = '['.date('m/d/Y g:i A').'] - ';

      // Success or failure being logged?
      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$this->last_error."\n";

      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
      foreach ($this->ipn_data as $key=>$value) {
         $text .= "$key=$value, ";
      }

      // Log the response from the paypal server
      $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;

      // Write to log
      $fp=fopen($this->ipn_log_file,'a');
      fwrite($fp, $text . "\n\n");

      fclose($fp);  // close file
   }

   function dump_fields() {

      // Used for debugging, this function will output all the field/value pairs
      // that are currently defined in the instance of the class using the
      // add_field() function.

      echo "<h3>paypal_class->dump_fields() Output:</h3>";
      echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>";

      ksort($this->fields);
      foreach ($this->fields as $key => $value) {
         echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
      }

      echo "</table><br>";
   }

} // End Class

?>