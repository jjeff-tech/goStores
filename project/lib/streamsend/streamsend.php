<?php

/**
    *Streamsend bulk emial api
 */
class StreamsendAPI {

    public $loginID;
    public $key;
    public $listID;


    ########## Function to save an emailaddress and details of a person at stream send................
    function addstreamsendpeople($person){
            ob_start();
            $login_id           =	$this->loginID;
            $key		=	$this->key;

            $ch 		= 	curl_init();
            $headers 	= 	array('Accept: application/xml','Content-Type: application/xml');
            curl_setopt($ch, CURLOPT_URL, "https://app.streamsend.com/audiences.xml");
            curl_setopt($ch, CURLOPT_URL, "https://app.streamsend.com/audiences/1/people.xml");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERPWD, "${login_id}:${key}");
           // curl_setopt($ch, CURLOPT_USERPWD, "$login_id:$key");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $person);
            $response = curl_exec($ch);
            $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $info = curl_getinfo($ch);
            if($http_status_code	==	201){
                    return "sucess";
            }
             return "failes";
    }

    ########## Function to send an email through stream send................
function sendstreamsendmail($mail){
	$login_id           =	$this->loginID;
        $key		=	$this->key;
	$ch 		= 	curl_init();
	$headers = array('Accept: application/xml','Content-Type: application/xml');
	curl_setopt($ch, CURLOPT_URL, "https://app.streamsend.com/audiences.xml");
	curl_setopt($ch, CURLOPT_URL, "https://app.streamsend.com/audiences/1/blasts.xml");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERPWD, "${login_id}:${key}");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $mail);
	$response = curl_exec($ch);
	$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$info = curl_getinfo($ch);
//	print_r($info);
	if($http_status_code == 201){
		return 'sucess';
	}
        return 'fail';
}

########## Function to activate an email as from email at stream send................
function fromemailactivation($frmemail){
	$mail		=	" <from-email-address><email-address>".$frmemail."</email-address></from-email-address>";
	$login_id       =	$this->loginID;
        $key            =	$this->key;
	$ch 		= 	curl_init();
	$headers 	= array('Accept: application/xml','Content-Type: application/xml');
	curl_setopt($ch, CURLOPT_URL, "https://app.streamsend.com/from_email_addresses.xml");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERPWD, "${login_id}:${key}");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $mail);
	$response = curl_exec($ch);
	$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$info = curl_getinfo($ch);
	if($http_status_code	==	201){
		return true;
	}
        return false;
}


########## Function to get people id from stream send................
function getpeople($email){
	$login_id       =	$this->loginID;
        $key            =	$this->key;
	$ch 		= 	curl_init();
  	$headers = array('Accept: application/xml','Content-Type: application/xml');
	curl_setopt($ch, CURLOPT_URL, "https://app.streamsend.com/audiences/1/people.xml?email_address=${email}");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERPWD, "${login_id}:${key}");
	$response = curl_exec($ch);
	#echo $response;
	#$response = substr($response,0,strpos($response,'</people>'));
	$fp = @fopen('pple.xml','w');
	@fwrite($fp,$response);
	@fclose($fp);
	$xml1 = simplexml_load_string(file_get_contents('pple.xml'));
        
	foreach ($xml1->person as $lst) {
	 $pid	= $lst->id;
	}
	$fp = @fopen('pple.xml','w');
	@fwrite($fp,'');
	@fclose($fp);
	return $pid;
}
########## Function to add an email to a list at stream send................
function addmembership($mid){

	$login_id       =	$this->loginID;
        $key            =	$this->key;
        $lid            =       $this->listID;
	$ch 		= 	curl_init();
	$headers = array('Accept: application/xml','Content-Type: application/xml');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_USERPWD, "${login_id}:${key}");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_URL, "https://app.streamsend.com/audiences/1/people/${mid}/lists/${lid}/memberships.xml");
	$mem	=	" <membership><list-id>".$lid."</list-id><person-id>".$mid."</person-id></membership>";
	curl_setopt($ch, CURLOPT_POSTFIELDS, $mem);
	$response = curl_exec($ch);
	$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	return true;
	/*if($http_status_code == 201){
		return true;
	}*/
}


    function getXMLAddEmail($person){

         if(count($person) > 0){
         $personxml = "<person>
                         <email-address>".$person['email']."</email-address>
                         <first-name>".$person['fname']."</first-name>
                         <last-name>".$person['lname']."</last-name>
                         <activate>true</activate>
                         <deliver-activation>false</deliver-activation>
                         <deliver-welcome>false</deliver-welcome>
                      </person>";

			
    }
     return $personxml;
    }

     function getXMLSendEmail($newsletter){

         if(count($newsletter) > 0){


         $newsletterxml = "<blast>
                        <from>
			  <name>".$newsletter['frname']."</name>
			  <email-address>".$newsletter['frmail']."</email-address>
			</from>
		   <reply-to>
			  <name>".$newsletter['frname']."</name>
			  <email-address>".$newsletter['frmail']."</email-address>
			</reply-to>
			<to>
			  <audience-id>1</audience-id>
			  <include-lists>".$this->listID."</include-lists>
			</to>
			<subject>".$newsletter['subject']."</subject>
			<body>
			 <html-part><![CDATA[ ".$newsletter['mailtemp']."]]></html-part>
		<text-part><![CDATA[  ]]></text-part>
			</body>
			<options>
			  <track-views>true</track-views>
			  <track-clicks>true</track-clicks>
			</options>
                        <scheduled-for>".$newsletter['scheduledfor']."</scheduled-for>
                         </blast>";


    }
   
     return $newsletterxml;
    }


}