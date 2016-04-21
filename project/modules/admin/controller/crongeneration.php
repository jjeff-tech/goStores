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

class ControllerCrongeneration extends BaseController {
    public function init() {
        parent::init();    
        echo date('Y-m-d H:i:s');
    }

    public function index(){
        //$this->view->disableLayout();
        //$this->view->disableView();
        // Billing Cron generation
        
        Cronhelper::generateBillInitial(0);
        exit();
        
    } //End Function
    
    public function billattempt(){
        //TODO : Second and Third attempt on bill payment
        //$this->view->disableLayout();
        //$this->view->disableView();
        Cronhelper::generateBillInitial(1);
        exit();
    } // End Function

    
    
    public function inventorySusbcription()
    {
        
       //TODO : Inventory source plugin renewal of client store will process one store at a time
        //$this->view->disableLayout();
        //$this->view->disableView();
       
        
         Cronhelper::generateinventorySusbcription();
         exit();
         
         
         
    }
    
    public function freetrialexpirynotification(){
         //Will fetch all reocrds that match condition and send mails
        //TODO : Notification on free trial expiry
        //$this->view->disableLayout();
        //$this->view->disableView();
        
        // 5 Days Prior Notification
        Cronhelper::generateFreeTrialExpiryNotification(5);

        // Expired Notification
        Cronhelper::generateFreeTrialExpiryNotification(1,true);
        exit();
    } // End Function

    public function billnotification(){
       
        //TODO : Notification on bill
        Cronhelper::generateBillNotification(5);
        exit();
    } // End Function

    public function disableExpiredDomains() {
        
        //Will Process 5 Stores per run
        //TODO : Suspend free plan account on plan expiration        
        Cronhelper::disableCpanelExpiredDomains();
        exit();
    }
    
    /*
     * Invoice INV, PLU ProductLookUp
     * INV.vSubscriptionType NOT LIKE 'FREE' AND CURDATE() > INV.dDueDate AND CURDATE() < PLU.dPlanExpiryDate AND INV.dPayment LIKE '0000-00-00' AND DATEDIFF(CURDATE(), INV.dGeneratedDate) = $dateRange
     */
    
    public function failedinvoicealert() {
        $alertDates = array(2, 5, 15, 20, 25, 30, 35, 40, 45, 50);
        Cronhelper::generateFailedInvoiceAlert($alertDates);
        exit();
    }

     public function faileddomainrenewalattempt() {       
        Cronhelper::domainRenewalAttempt();
        exit();
    }

    public function domainRenewalTest(){ //GoDaddy
        //GoDaddy
        PageContext::includePath('parsexmls');
        PageContext::includePath('godaddy');

        $goDaddyObj = new goDaddy();
        
        $parse  = new parsexmls();

        /* Sample Data */
        $email = "jamessmith121212@gmail.com";
        $fname = "James";
        $lname = "Smith";
        $phone = "8885551212";
        $productid = "350012";
        $duration = "1";
        $sld = "plansmsj009a"; // PLANSMSJ009A.COM
        $tld = "com";
        /* Sample Data Ends */


        $opStatus = $goDaddyObj->domainrenewal($email, $fname, $lname, $phone, $productid, $duration, $sld, $tld);
        //echopre($opStatus);
        $opParse = $parse->parseDomainRenewalResultXML($opStatus);
        echopre($opParse);
        die("Here comes the end");
    }

    public function domainRenewalTestEnom(){
        // do domain renewal for enom
        Admincomponents::$dbObj = new Db();
        // Set account username and password
            $enom_username  =   Admincomponents::$dbObj->selectRow("Settings","value","settingfield='enom_user'");
            $enom_password  =   Admincomponents::$dbObj->selectRow("Settings","value","settingfield='enom_password'");
            $enom_mode      =   Admincomponents::$dbObj->selectRow("Settings","value","settingfield='enom_testmode'");

        /* Sample Data */
           
            $sld = "plansmsj006md";
            $tld = "com";
            $username = $enom_username;
            $password = $enom_password;
            $password = User::decrytCreditCardDetails($password);
            $duration = "1"; // renewal duration
            $enduserip = "174.123.32.42";
            $enommode = $enom_mode;
        /* Sample Data Ends */
		
	PageContext::includePath('enom');
        // Create URL Interface class
        $Enom = new Enominterface();

        $Enom->NewRequest();
        // Set TLD and SLD of domain to register
        $Enom->AddParam( "tld", $tld );
        $Enom->AddParam( "sld", $sld );
        // Set account username and password
        $Enom->AddParam( "uid", $username );
        $Enom->AddParam( "pw", $password );
        // Set number of years to extend
        if ( $duration != "" ) {
                $Enom->AddParam( "NumYears", $duration );
        }

        $Enom->AddParam( "EndUserIP", $enduserip );

        
        $Enom->AddParam( "command", "extend" );
        // All the info has been entered, now register the name
	$Enom->DoTransaction($enom_mode);
        echopre($Enom->Values); 
            // Were there errors?
            if ( $Enom->Values[ "ErrCount" ] != "0" ) {
                    // Yes, get the first one
                    $cErrorMsg = $Enom->Values[ "Err1" ];


            } else {
                //domain renewal processed successfully
                /* Sample response for a successful transaction
                 Array
                    (
                        [Extension] => successful
                        [DomainName] => plansmsj006md.com
                        [OrderID] => 157919807
                        [RRPCode] => 200
                        [RRPText] => Command completed successfully
                        [RegistryExpDate] => 2015-07-09 09:26:26.000
                        [Command] => EXTEND
                        [Language] => eng
                        [ErrCount] => 0
                        [ResponseCount] => 0
                        [MinPeriod] => 1
                        [MaxPeriod] => 10
                        [Server] => SJL0VWRESELL_T1
                        [Site] => eNom
                        [IsLockable] => True
                        [IsRealTimeTLD] => True
                        [TimeDifference] => +08.00
                        [ExecTime] => 0.610
                        [Done] => true
                        [RequestDateTime] => 7/9/2013 3:03:51 AM
                    )
                 */
                    
            }

            die("Here comes the end");

		
    } // End Function
   

   
} // End Class
?>
