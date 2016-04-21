<?php
PageContext::includePath('wapi');
// Load the classes constructed by wsdl2php
class goDaddy
{
	// Define the SOAP options.
	public $a_SoapOptions = array(
                                    'encoding'  => 'UTF-8',
                                    'exception' => True, // Turn SOAPFaults into Exceptions so PHP can report upon them.
                                    'trace'     => True, // Turn on tracing so we can see EXACTLY what the headers and request/response are.
                                );

	public $s_WsdlUrl;

	public $account;

	public $password;

	public $errmessage;

	public $succmessage;

        public $godaddy_testmode;

        public $godaddy_id;

        public $godaddy_password;

        public function goDaddy() {
      // initialization constructor.  Called when class is created.
            User::$dbObj          = new Db();
            $this->godaddy_testmode   = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_testmode'");
            $this->godaddy_id         = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_id'");
            $godaddy_password   = User::$dbObj->selectRow("Settings","value","settingfield='godaddy_password'");
            $this->godaddy_password   = User::decrytCreditCardDetails($godaddy_password);
        }

	public function checkdomainavailability(array $domainName)
	{
		try
		{

			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$this->account  = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_CheckDomains = new CheckDomains;
			$o_CheckDomains->credential = new Credential;

			$o_CheckDomains->sCLTRID              	  = 'reseller'.time();
			$o_CheckDomains->credential->Account  	  = $this->account;
			$o_CheckDomains->credential->Password 	  = $this->password;

			foreach($domainName as $domainkey => $domainval)
                        {
                            $checkdomain = new CheckDomain;

                            $checkdomain->name         =   $domainval;
                            $ocheckdomain->idnScript   =   'Latin';
                            $DomainArray[] = $checkdomain;
                        }

			$o_CheckDomains->domainArray		 	 = $DomainArray;

			$o_CheckDomainsResponse = $o_WAPI->CheckDomains($o_CheckDomains);
			$o_CheckDomainsResponse->CheckDomainsResult = str_replace("UTF-16", "UTF-8", $o_CheckDomainsResponse->CheckDomainsResult);

			$o_CheckDomainsResults = simplexml_load_string($o_CheckDomainsResponse->CheckDomainsResult);

			$i = 0;

                        //print_r($o_CheckDomainsResults);exit;

			foreach($o_CheckDomainsResults->children() as $child)
			{
				foreach($child->attributes() as $a => $b)
				{
					if($a == 'avail') $isavail[$i]['avail'] = $b;
					elseif($a == 'name') $isavail[$i]['name'] = $b;
				}
				$i++;
			}

			return $isavail;

		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
	}

	public function registerdomain($email, $fname, $lname, $phone, $addr, $city, $state, $province, $country, $domainArray, $NSArr, $overridePass = '', $isCertification = false)
	{

		try
		{
			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$o_OrderDomains                                 = new OrderDomains;
			$o_OrderDomains->credential                     = new Credential;
			$shopper 					= new Shopper;

			$this->account = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_OrderDomains->sCLTRID              	  = 'reseller'.time();
			$o_OrderDomains->credential->Account  	  = $this->account;
			$o_OrderDomains->credential->Password 	  = $this->password;

			$shopper->user                            = 'createNew';
                        if($overridePass <> ''){
                            $shopper->pwd                         = $overridePass;
                        }
                        else
                        {
                            $shopper->pwd		   	= $this->__passwordgen();
                        }

                        $shopper->acceptOrderTOS		= 'agree';
			$shopper->email				= $email;
			$shopper->firstname			= $fname;
			$shopper->lastname			= $lname;
			$shopper->phone				= '+1.'.$phone;      // 1 is for US it seems

			$o_OrderDomains->shopper    			  = $shopper;

			foreach($domainArray as $key => $val)
			{
				$domain_name = explode('.', $val['domain']);

                                //subdomain registration is not allowed
                                $dom_name_parts_cnt = count($domain_name);
                                $top_level_domain = '';
                                if($dom_name_parts_cnt > 2){
                                    for($cnt = 1; $cnt < $dom_name_parts_cnt; $cnt++){
                                        if($cnt <> $dom_name_parts_cnt - 1){
                                            $top_level_domain .= $domain_name[$cnt].'.';
                                        }
                                        else{
                                            $top_level_domain .= $domain_name[$cnt];
                                        }
                                    }
                                }
                                else{
                                    $top_level_domain = $domain_name[1];
                                }

				$domain						= new DomainRegistration;
				$orderitem					= new OrderItem;
				$contact					= new ContactInfo;
                                $nexus                                          = new Nexus;

				$orderitem->productid     = $val['prd_id'];
				$orderitem->quantity      = 1;
				$orderitem->duration      = $val['duration'];

				$contact->fname      	  = $fname;
				$contact->lname      	  = $lname;
				$contact->email      	  = $email;
				$contact->sa1	      	  = $addr;
				$contact->city      	  = $city;
				$contact->sp	      	  = $state;
				$contact->pc	      	  = $province;
				$contact->cc	      	  = $country;
				$contact->phone	      	  = '+1.'.$phone;    // 1 is for US it seems

                                if($isCertification)
                                {
                                    $nexus->category      = 'citizen of US';
                                    $nexus->country       = 'US';
                                    $nexus->use           = 'personal';

                                    $domain->nexus    		    = $nexus;
                                }

                                if(count($NSArr) <> 0)
                                {
                                    $nameServerArray          = array();

                                    foreach($NSArr as $nkey => $nval)
                                    {
                                        $nameServer           = new NS;
                                        $nameServer->name     = $nval;

                                        $nameServerArray[]    = $nameServer;
                                    }

                                    $domain->nsArray   		    = $nameServerArray;
                                }

				$domain->order    		    = $orderitem;
				$domain->sld    		    = $domain_name[0];
				$domain->tld    		    = $top_level_domain;
				$domain->period    		    = $val['duration'];
				$domain->registrant                 = $contact;
				$domain->autorenewflag              = 1; 			// its ON atm

				$Domains[] = $domain;
			}

			$o_OrderDomains->items	    			  = $Domains;

			$o_OrderDomainsResponse = $o_WAPI->OrderDomains($o_OrderDomains);
			$o_OrderDomainsResponse->OrderDomainsResult = str_replace("UTF-16", "UTF-8", $o_OrderDomainsResponse->OrderDomainsResult);

			$o_OrderDomainsResults = simplexml_load_string($o_OrderDomainsResponse->OrderDomainsResult);

			//---------------------------Works on ALL OR NONE policy so incase of multiple domains, if 1 request fails, everything fails-------------------------//
			foreach($o_OrderDomainsResults->attributes() as $rootKey => $rootVal)
                        {
                            if($rootKey == 'user') $result['user'] = $rootVal;
                        }
                        foreach($o_OrderDomainsResults->children() as $child)
			{
				foreach($child->attributes() as $a => $b)
				{
					if($a == 'code') $result['code'] = $b == 1000 ? 'Success' : 'Error';
				}
				$result['code'] == 'Error' ? $this->__checkErrors($child) : $this->__successReturn($child);
				$result['msg'] = $result['code'] == 'Error' ? $this->errmessage : $this->succmessage;
			}

			//print_r($o_OrderDomainsResults);exit;

			return $result;
		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
	}

	public function transferdomain($tld, $sld, $authkey)
	{
		try
		{
			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$this->account = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_OrderDomainTransfers                         = new OrderDomainTransfers;
			$o_OrderDomainTransfers->credential 		= new Credential;
			$shopper					= new Shopper;
			$domainTransfer                                 = new DomainTransfer;
                        $orderitem                                      = new OrderItem;

                        $returnArray = array();

                        //-------------get the shopper ID-------------//
                        $shopper_details = $this->getinfo($sld.'.'.$tld, true);
                        if($shopper_details[0] == 'Error')
                        {
                            $returnArray[0] = 'Error';
                            return $returnArray;
                            exit;
                        }
                        else
                        {
                            $o_OrderDomainTransfers->sCLTRID              	  = 'reseller'.time();
                            $o_OrderDomainTransfers->credential->Account  	  = $this->account;
                            $o_OrderDomainTransfers->credential->Password 	  = $this->password;

                            $shopper->user                              = $shopper_details[1];
                            $shopper->acceptOrderTOS                    = 'agree';
                            /*$shopper->pwd		   		= $this->__passwordgen();
                            $shopper->email				= 'asd@gmail.com';
                            $shopper->firstname                         = 'asdf';
                            $shopper->lastname                          = 'dser';
                            $shopper->phone				= '+1.23212344';      */

                            $o_OrderDomainTransfers->shopper                 = $shopper;

                            $orderitem->productid                  = '350011';
                            $orderitem->quantity                   = '1';
                            $orderitem->duration                   = '1';

                            $domainTransfer->order                 = $orderitem;
                            $domainTransfer->sld                   = $sld;
                            $domainTransfer->tld                   = $tld;
                            $domainTransfer->authInfo              = $authkey;

                            $domaintransferArray[]                 = $domainTransfer;

                            $o_OrderDomainTransfers->items                  = $domaintransferArray;



                            $o_OrderDomainTransfersResponse = $o_WAPI->OrderDomainTransfers($o_OrderDomainTransfers);
                            $o_OrderDomainTransfersResponse->OrderDomainTransfersResult = str_replace("UTF-16", "UTF-8", $o_OrderDomainTransfersResponse->OrderDomainTransfersResult);

                            $o_OrderDomainTransfersResults = simplexml_load_string($o_OrderDomainTransfersResponse->OrderDomainTransfersResult);

                            foreach($o_OrderDomainTransfersResults->children() as $child)
                            {
                                    foreach($child->attributes() as $a => $b)
                                    {
                                            if($a == 'code') $returnArray[0] = $b == 1000 ? 'Success' : 'Error';
                                            if($returnArray[0] == "Error")
                                            {
                                                return $returnArray;
                                                exit;
                                            }
                                    }

                                    if($child->getName() == 'resdata')
                                    {
                                        foreach($child as $k => $v)
                                        {
                                            $returnArray[1] = $v;
                                        }
                                    }
                            }
                            return $returnArray;
                            exit;
                        }

		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
	}

        public function domainprivacy($resourceID, $productID, $shopperID, $domain, $duration, $email, $fname, $lname, $phone, $overridePass = '')
        {
            try
            {
                    $this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
                    $o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

                    $this->account = $this->godaddy_id;
                    $this->password = $this->godaddy_password;

                    $o_OrderDomainPrivacy               = new OrderDomainPrivacy;
                    $o_OrderDomainPrivacy->credential   = new Credential;
                    $shopper                            = new Shopper;
                    $proxydomains                       = new DomainByProxy;
                    $orderitem                          = new OrderItem;

                    $o_OrderDomainPrivacy->sCLTRID              	  = 'reseller'.time();
                    $o_OrderDomainPrivacy->credential->Account  	  = $this->account;
                    $o_OrderDomainPrivacy->credential->Password 	  = $this->password;

                    $shopper->user                      = $shopperID;
                    if($overridePass <> '') $shopper->pwd		   	= $overridePass;
                    else                    $shopper->pwd		   	= $this->__passwordgen();

                    $shopper->acceptOrderTOS		= 'agree';
                    $shopper->email                     = $email;
                    $shopper->firstname			= $fname;
                    $shopper->lastname			= $lname;
                    $shopper->phone                     = '+1.'.$phone;      // 1 is for US it seems
                    $shopper->dbpuser                   = 'createNew';
                    $shopper->dbpemail                  = $email;
                    $shopper->dbppwd                    = $shopper->pwd;

                    $o_OrderDomainPrivacy->shopper              	  = $shopper;

                    $domParts = explode(".", $domain);

                    //subdomain registration is not allowed
                    $dom_name_parts_cnt = count($domParts);
                    $top_level_domain = '';
                    if($dom_name_parts_cnt > 2){
                        for($cnt = 1; $cnt < $dom_name_parts_cnt; $cnt++){
                            if($cnt <> $dom_name_parts_cnt - 1){
                                $top_level_domain .= $domParts[$cnt].'.';
                            }
                            else{
                                $top_level_domain .= $domParts[$cnt];
                            }
                        }
                    }
                    else{
                        $top_level_domain = $domParts[1];
                    }

                    $orderitem->productid               = $productID;
                    $orderitem->duration                = $duration;
                    $orderitem->quantity                = 1;

                    $proxydomains->order                = $orderitem;
                    $proxydomains->sld                  = $domParts[0];
                    $proxydomains->tld                  = $top_level_domain;
                    $proxydomains->resourceid           = $resourceID;

                    $proxyDomainsArray[]                = $proxydomains;

                    $o_OrderDomainPrivacy->items                         = $proxyDomainsArray;

                    $o_OrderDomainPrivacyResponse                           = $o_WAPI->OrderDomainPrivacy($o_OrderDomainPrivacy);
                    $o_OrderDomainPrivacyResponse->OrderDomainPrivacyResult = str_replace("UTF-16", "UTF-8", $o_OrderDomainPrivacyResponse->OrderDomainPrivacyResult);

                    $o_OrderDomainPrivacyResults                            = simplexml_load_string($o_OrderDomainPrivacyResponse->OrderDomainPrivacyResult);

                    return $o_OrderDomainPrivacyResults;
                    //echo print_r($o_OrderDomainPrivacyResults, True), PHP_EOL;exit;
            }
            catch(Exception $e)
            {
                    $this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
            }
        }

	public function domainrenewal($email, $fname, $lname, $phone, $productid, $duration, $sld, $tld)
	{
            try
            {
                $resourceid = '';
                $userID     = '';

                $this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
                $o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);
                $quantity      = 1;

                // get info about the domain
                $domainInfo = $this->getinfo($sld. '.' .$tld, true);

                if($domainInfo[0] == 'Error') die('Invalid domain submitted');
                else
                {
                    $resourceid = $domainInfo[0];
                    $userID =  $domainInfo[1];

                    $this->account = $this->godaddy_id;
                    $this->password = $this->godaddy_password;

                    $o_OrderDomainRenewals                                      = new OrderDomainRenewals;
                    $o_OrderDomainRenewals->credential                          = new Credential;
                    $shopper 							= new Shopper;
                    $domainRenewal						= new DomainRenewal;
                    $orderitem							= new OrderItem;

                    $o_OrderDomainRenewals->sCLTRID              	  = 'reseller'.time();
                    $o_OrderDomainRenewals->credential->Account  	  = $this->account;
                    $o_OrderDomainRenewals->credential->Password 	  = $this->password;

                    $shopper->user        		= $userID;
                    $shopper->acceptOrderTOS		= 'agree';
                    $shopper->pwd                       = $this->__passwordgen();
                    $shopper->email			= $email;
                    $shopper->firstname			= $fname;
                    $shopper->lastname			= $lname;
                    $shopper->phone			= '+1.' . $phone;      // 1 is for US it seems

                    $orderitem->productid     = $productid;
                    $orderitem->quantity      = $quantity;
                    $orderitem->duration      = $duration;

                    $domainRenewal->order		= $orderitem;
                    $domainRenewal->resourceid          = $resourceid;
                    $domainRenewal->sld			= $sld;
                    $domainRenewal->tld			= $tld;
                    $domainRenewal->period		= $duration;

                    $renewalArray[] 			= $domainRenewal;

                    $o_OrderDomainRenewals->shopper    			= $shopper;
                    $o_OrderDomainRenewals->items                       = $renewalArray;
                    $o_OrderDomainRenewals->sROID   	           	= '';


                    $o_OrderDomainRenewalsResponse = $o_WAPI->OrderDomainRenewals($o_OrderDomainRenewals);
                    $o_OrderDomainRenewalsResponse->OrderDomainRenewalsResult = str_replace("UTF-16", "UTF-8", $o_OrderDomainRenewalsResponse->OrderDomainRenewalsResult);

                    $o_OrderDomainRenewalsResults = simplexml_load_string($o_OrderDomainRenewalsResponse->OrderDomainRenewalsResult);

                    return $o_OrderDomainRenewalsResults;

                    //echo print_r($o_OrderDomainRenewalsResults, True), PHP_EOL;exit;
                }
            }
            catch(Exception $e)
            {
                    $this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
            }
	}

        public function certifytransferdomain($paramArray)
	{
		try
		{
			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$this->account = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_OrderDomainTransfers                         = new OrderDomainTransfers;
			$o_OrderDomainTransfers->credential 		= new Credential;
			$shopper					= new Shopper;
			$domainTransfer                                 = new DomainTransfer;
                        $orderitem                                      = new OrderItem;


                        $o_OrderDomainTransfers->sCLTRID              	  = 'reseller'.time();
                        $o_OrderDomainTransfers->credential->Account  	  = $this->account;
                        $o_OrderDomainTransfers->credential->Password 	  = $this->password;

                        $shopper->user                              = 'createNew';
                        $shopper->acceptOrderTOS		    = 'agree';
                        $shopper->pwd                               = $paramArray['pass'];
                        $shopper->email                             = $paramArray['email'];
                        $shopper->firstname                         = $paramArray['fname'];
                        $shopper->lastname                          = $paramArray['lname'];
                        $shopper->phone                             = $paramArray['phone'];

                        $o_OrderDomainTransfers->shopper                 = $shopper;

                        $orderitem->productid                  = $paramArray['prodID'];
                        $orderitem->quantity                   = '1';
                        $orderitem->duration                   = '1';

                        $domainTransfer->order                 = $orderitem;
                        $domainTransfer->sld                   = $paramArray['sld'];
                        $domainTransfer->tld                   = $paramArray['tld'];

                        $domaintransferArray[]                 = $domainTransfer;

                        $o_OrderDomainTransfers->items                  = $domaintransferArray;


                        $o_OrderDomainTransfersResponse = $o_WAPI->OrderDomainTransfers($o_OrderDomainTransfers);
                        $o_OrderDomainTransfersResponse->OrderDomainTransfersResult = str_replace("UTF-16", "UTF-8", $o_OrderDomainTransfersResponse->OrderDomainTransfersResult);

                        $o_OrderDomainTransfersResults = simplexml_load_string($o_OrderDomainTransfersResponse->OrderDomainTransfersResult);

                        return $o_OrderDomainTransfersResults;
                        //print_r($o_OrderDomainTransfersResults);  exit;
		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
	}

	public function privatedomainrenewal($userID, $privacyUID, $domains, $privacy, $overridePass = '')
	{
            try
            {
                $this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
                $o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);
                $quantity      = 1;

                $this->account = $this->godaddy_id;
                $this->password = $this->godaddy_password;

                $o_OrderPrivateDomainRenewals                                       = new OrderPrivateDomainRenewals;
                $o_OrderPrivateDomainRenewals->credential                           = new Credential;
                $shopper                                                            = new Shopper;


                $o_OrderPrivateDomainRenewals->sCLTRID              	  = 'reseller'.time();
                $o_OrderPrivateDomainRenewals->credential->Account  	  = $this->account;
                $o_OrderPrivateDomainRenewals->credential->Password 	  = $this->password;

                $shopper->user        		= $userID;
                $shopper->acceptOrderTOS	= 'agree';

                if($overridePass <> '') $shopper->pwd		   	= $overridePass;
                else                    $shopper->pwd		   	= $this->__passwordgen();
                $shopper->dbpuser               = $privacyUID;
                if($overridePass <> '') $shopper->dbppwd		= $overridePass;
                else                    $shopper->dbppwd		= $this->__passwordgen();
                /*$shopper->email			= $email;
                $shopper->firstname             = $fname;
                $shopper->lastname		= $lname;
                $shopper->phone			= '+1.' . $phone;      // 1 is for US it seems*/

                foreach($domains as $key => $val)
                {
                    $domainRenewal                                                      = new DomainRenewal;
                    $orderitem                                                          = new OrderItem;

                    $orderitem->productid     = $val['prodID'];
                    $orderitem->quantity      = $quantity;
                    $orderitem->duration      = $val['duration'];

                    $dom_parts = explode('.', $val['domain']);

                    //subdomain registration is not allowed
                    $dom_name_parts_cnt = count($dom_parts);
                    $top_level_domain = '';
                    if($dom_name_parts_cnt > 2){
                        for($cnt = 1; $cnt < $dom_name_parts_cnt; $cnt++){
                            if($cnt <> $dom_name_parts_cnt - 1){
                                $top_level_domain .= $dom_parts[$cnt].'.';
                            }
                            else{
                                $top_level_domain .= $dom_parts[$cnt];
                            }
                        }
                    }
                    else{
                        $top_level_domain = $dom_parts[1];
                    }

                    $domainRenewal->order		= $orderitem;
                    $domainRenewal->resourceid          = $val['resID'];
                    $domainRenewal->sld                 = $dom_parts[0];
                    $domainRenewal->tld                 = $top_level_domain;
                    $domainRenewal->period		= $val['duration'];

                    $renewalArray[] 		= $domainRenewal;
                }

                foreach($privacy as $pkey => $pval)
                {
                    $resourceRenewal                                                    = new ResourceRenewal;
                    $orderitem                                                          = new OrderItem;

                    $orderitem->productid     = $pval['prodID'];
                    $orderitem->quantity      = $quantity;
                    $orderitem->duration      = $pval['duration'];

                    $resourceRenewal->order		= $orderitem;
                    $resourceRenewal->resourceid        = $pval['resID'];

                    $resRenewalArray[] 		= $resourceRenewal;
                }

                $o_OrderPrivateDomainRenewals->shopper    		= $shopper;
                $o_OrderPrivateDomainRenewals->items                    = $renewalArray;
                $o_OrderPrivateDomainRenewals->dbpItems                 = $resRenewalArray;
                $o_OrderPrivateDomainRenewals->sROID                    = '';


                $o_OrderPrivateDomainRenewalsResponse = $o_WAPI->OrderPrivateDomainRenewals($o_OrderPrivateDomainRenewals);
                $o_OrderPrivateDomainRenewalsResponse->OrderPrivateDomainRenewalsResult = str_replace("UTF-16", "UTF-8", $o_OrderPrivateDomainRenewalsResponse->OrderPrivateDomainRenewalsResult);

                $o_OrderPrivateDomainRenewalsResults = simplexml_load_string($o_OrderPrivateDomainRenewalsResponse->OrderPrivateDomainRenewalsResult);

                return $o_OrderPrivateDomainRenewalsResults;

                //echo print_r($o_OrderPrivateDomainRenewalsResults, True), PHP_EOL;exit;

            }
            catch(Exception $e)
            {
                    $this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
            }
	}

	public function pollserver()
	{
		try
		{
			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$this->account = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_Poll = new Poll;
			$o_Poll->credential = new Credential;

			$o_Poll->sCLTRID              	  = 'reseller'.time();
			$o_Poll->credential->Account  	  = $this->account;
			$o_Poll->credential->Password 	  = $this->password;

			$o_Poll->sOp	              	  = 'req';

			$o_PollResponse = $o_WAPI->Poll($o_Poll);
			$o_PollResponse->PollResult = str_replace("UTF-16", "UTF-8", $o_PollResponse->PollResult);

			$o_PollResults = simplexml_load_string($o_PollResponse->PollResult);

			return $o_PollResults;
		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
	}

	public function getinfo($domainName, $parsedResult = false, $orderID = 0, $resID = '', $infoType = 'standard')
	{
		try
		{
			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$this->account = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_Info = new Info;
			$o_Info->credential = new Credential;

			$o_Info->sCLTRID              	  = 'reseller'.time();
			$o_Info->credential->Account  	  = $this->account;
			$o_Info->credential->Password 	  = $this->password;

                        if($orderID == 0)
                        {
                            $o_Info->sDomain              	  = $domainName;
                        }
                        elseif($orderID == 1)
                        {
                            $o_Info->sResourceID            	  = $resID;
                        }
                        else
                        {
                            $o_Info->sOrderID              	  = $orderID;
                        }

                        $o_Info->sType             	= $infoType;

			$o_InfoResponse = $o_WAPI->Info($o_Info);
			$o_InfoResponse->InfoResult = str_replace("UTF-16", "UTF-8", $o_InfoResponse->InfoResult);

			$o_InfoResults = simplexml_load_string($o_InfoResponse->InfoResult);

                        if($parsedResult)
                        {
                            // for success case
                            foreach($o_InfoResults->attributes() as $a => $b)
                            {
                                    if($a == 'resourceid') $retVal[0] = $b;
                                    elseif($a == 'ownerID') $retVal[1] = $b;
                            }
                            // for unsuccessful cases
                            foreach($o_InfoResults->children() as $child)
                            {
                                    foreach($child->attributes() as $a => $b)
                                    {
                                            if($b == 1001) $retVal[0] = 'Error';
                                    }
                            }
                            return $retVal;
                        }
			else return $o_InfoResults;
		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
	}

        public function resetcertification()
        {
            try
            {
                $this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
                $o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

                $this->account = $this->godaddy_id;
                $this->password = $this->godaddy_password;

                $o_ProcessRequest                          = new ProcessRequest;
                $o_ProcessRequest->sRequestXML             = "<wapi clTRID='reseller".time()."' account='".$this->account."' pwd='".$this->password."'><manage><script cmd='reset' /></manage></wapi>";

                $o_ProcessRequestResponse = $o_WAPI->ProcessRequest($o_ProcessRequest);

		// No need to process the response at the moment as this would reset the certification back to step 1.
            }
            catch(Exception $e)
            {
                $this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
            }
        }

        public function updateNS($domainResourceID, $ns_array)
	{
		try
		{
			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$this->account = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_updateNS = new UpdateNameServer;
			$o_updateNS->credential = new Credential;

			$o_updateNS->sCLTRID              	  = 'reseller'.time();
			$o_updateNS->credential->Account  	  = $this->account;
			$o_updateNS->credential->Password 	  = $this->password;

                        $domain = new Domain;
                        $domain->resourceid = $domainResourceID;
                        $domain->mngTRID    = 'Tran'.time();

                        $domain_array[]     = $domain;

                        $o_updateNS->domainArray                  = $domain_array;

                        foreach($ns_array as $nkey => $nval){
                            $nameServer           = new NS;
                            $nameServer->name     = $nval;

                            $nameServerArray[]    = $nameServer;
                        }

                        $o_updateNS->nsArray                      = $nameServerArray;

			$o_updateNSResponse = $o_WAPI->UpdateNameServer($o_updateNS);
			$o_updateNSResponse->UpdateNameServerResult = str_replace("UTF-16", "UTF-8", $o_updateNSResponse->UpdateNameServerResult);

			$o_updateNSResults = simplexml_load_string($o_updateNSResponse->UpdateNameServerResult);

			return $o_updateNSResults;
		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
	}

        function updateDomainContactDetails($domainResourceID, $registrantArray, $adminArray, $billingArray, $techArray)
        {
                try
		{
			$this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
			$o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

			$this->account = $this->godaddy_id;
			$this->password = $this->godaddy_password;

			$o_updateDomainContactDetails = new UpdateDomainContact;
			$o_updateDomainContactDetails->credential = new Credential;

			$o_updateDomainContactDetails->sCLTRID              	  = 'reseller'.time();
			$o_updateDomainContactDetails->credential->Account  	  = $this->account;
			$o_updateDomainContactDetails->credential->Password 	  = $this->password;

                        $domain = new Domain;
                        $domain->resourceid = $domainResourceID;
                        $domain->mngTRID    = 'Tran'.time();

                        $domain_array[]     = $domain;

                        $o_updateDomainContactDetails->domainArray                = $domain_array;

                        $registrant_contact                         =  new ContactInfo;
                        $registrant_contact->fname                  = $registrantArray['fname'];
                        $registrant_contact->lname                  = $registrantArray['lname'];
                        $registrant_contact->email                  = $registrantArray['email'];
                        $registrant_contact->sa1                    = $registrantArray['addr'];
                        $registrant_contact->city                   = $registrantArray['city'];
                        $registrant_contact->sp                     = $registrantArray['state'];
                        $registrant_contact->pc                     = $registrantArray['province'];
                        $registrant_contact->cc                     = $registrantArray['country'];
                        $registrant_contact->phone                  = $registrantArray['phone'];

                        $o_updateDomainContactDetails->registrant                 = $registrant_contact;

                        $admin_contact                         =  new ContactInfo;
                        $admin_contact->fname                  = $adminArray['fname'];
                        $admin_contact->lname                  = $adminArray['lname'];
                        $admin_contact->email                  = $adminArray['email'];
                        $admin_contact->sa1                    = $adminArray['addr'];
                        $admin_contact->city                   = $adminArray['city'];
                        $admin_contact->sp                     = $adminArray['state'];
                        $admin_contact->pc                     = $adminArray['province'];
                        $admin_contact->cc                     = $adminArray['country'];
                        $admin_contact->phone                  = $adminArray['phone'];

                        $o_updateDomainContactDetails->admin                      = $admin_contact;

                        $billing_contact                         =  new ContactInfo;
                        $billing_contact->fname                  = $billingArray['fname'];
                        $billing_contact->lname                  = $billingArray['lname'];
                        $billing_contact->email                  = $billingArray['email'];
                        $billing_contact->sa1                    = $billingArray['addr'];
                        $billing_contact->city                   = $billingArray['city'];
                        $billing_contact->sp                     = $billingArray['state'];
                        $billing_contact->pc                     = $billingArray['province'];
                        $billing_contact->cc                     = $billingArray['country'];
                        $billing_contact->phone                  = $billingArray['phone'];

                        $o_updateDomainContactDetails->billing                    = $billing_contact;

                        $tech_contact                         =  new ContactInfo;
                        $tech_contact->fname                  = $techArray['fname'];
                        $tech_contact->lname                  = $techArray['lname'];
                        $tech_contact->email                  = $techArray['email'];
                        $tech_contact->sa1                    = $techArray['addr'];
                        $tech_contact->city                   = $techArray['city'];
                        $tech_contact->sp                     = $techArray['state'];
                        $tech_contact->pc                     = $techArray['province'];
                        $tech_contact->cc                     = $techArray['country'];
                        $tech_contact->phone                  = $techArray['phone'];

                        $o_updateDomainContactDetails->tech                       = $tech_contact;

			$o_updateDomainContactDetailsResponse = $o_WAPI->UpdateDomainContact($o_updateDomainContactDetails);
			$o_updateDomainContactDetailsResponse->UpdateDomainContactResult = str_replace("UTF-16", "UTF-8", $o_updateDomainContactDetailsResponse->UpdateDomainContactResult);

			$o_updateDomainContactDetailsResults = simplexml_load_string($o_updateDomainContactDetailsResponse->UpdateDomainContactResult);

			return $o_updateDomainContactDetailsResults;
		}
		catch(Exception $e)
		{
			$this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
		}
        }

        function setUnsetAutoRenew($domainResID, $onOff = 'restore')
        {
            try
            {
                    $this->s_WsdlUrl = $this->godaddy_testmode == 'Y' ?  "https://api.ote.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL" : "https://api.wildwestdomains.com/wswwdapi/wapi.asmx?WSDL";
                    $o_WAPI = new WAPI($this->s_WsdlUrl, $this->a_SoapOptions);

                    $this->account = $this->godaddy_id;
                    $this->password = $this->godaddy_password;

                    $o_setUnsetAutoRenew = new Cancel;
                    $o_setUnsetAutoRenew->credential = new Credential;

                    $o_setUnsetAutoRenew->sCLTRID              	  = 'reseller'.time();
                    $o_setUnsetAutoRenew->credential->Account  	  = $this->account;
                    $o_setUnsetAutoRenew->credential->Password 	  = $this->password;

                    $o_setUnsetAutoRenew->sType                   = $onOff;

                    $domainResArray[]                             = $domainResID;
                    $o_setUnsetAutoRenew->sIDArray                = $domainResArray;

                    $o_setUnsetAutoRenewResponse = $o_WAPI->Cancel($o_setUnsetAutoRenew);
                    $o_setUnsetAutoRenewResponse->CancelResult = str_replace("UTF-16", "UTF-8", $o_setUnsetAutoRenewResponse->CancelResult);

                    $o_setUnsetAutoRenewResults = simplexml_load_string($o_setUnsetAutoRenewResponse->CancelResult);

                    return $o_setUnsetAutoRenewResults;
            }
            catch(Exception $e)
            {
                    $this->__displayErrors($o_WAPI->__getLastRequestHeaders(), $o_WAPI->__getLastRequest(), $o_WAPI->__getLastResponseHeaders(), $o_WAPI->__getLastResponse(), $e->getMessage());
            }
        }

	//-----------------------------------------------------MISCELLANEOUS FUNCTIONS-------------------------------------------//
	function __passwordgen($length = 8)
	{
		return substr(md5(rand().rand()), 0, $length);
	}

	function __checkErrors(SimpleXMLElement $param)
	{
		foreach($param->children() as $errchild)
		{
			if($errchild->getName() == 'error')
			{
				foreach($errchild->attributes() as $k => $v)
				{
					if($k == 'desc' || $k == 'displaystring') $optext .= $v.'&nbsp';
				}
				$this->errmessage = $optext;
			}
			else $this->__checkErrors($errchild);
		}
	}

	function __successReturn(SimpleXMLElement $param)
	{
		foreach($param->children() as $succchild)
		{
			if($succchild->getName() == 'orderid') $this->succmessage = $succchild[0];
		}
	}

	function __displayErrors($requestheader, $request, $responseheader, $response, $thrown_exception)
	{
		echo PHP_EOL,

		// Show trace.
		'Request Headers', PHP_EOL,
		'---------------', PHP_EOL,
		$requestheader, PHP_EOL,

		'Request', PHP_EOL,
		'-------', PHP_EOL,
		$request, PHP_EOL,

		'Response Headers', PHP_EOL,
		'----------------', PHP_EOL,
		$responseheader, PHP_EOL,

		'Response', PHP_EOL,
		'--------', PHP_EOL,
		$response, PHP_EOL,

		// Show exception.
		'Exception', PHP_EOL,
		'---------', PHP_EOL,
		$thrown_exception, PHP_EOL;

		exit;
	}
}

?>