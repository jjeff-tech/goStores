<?php


class quickbook 
{
      


	 

	function curl_process($data)
	{
		 
		 
			$xml = $this->buildXML($data);
		 // echo $xml;
		
			
		 
			
			$header[] = 'Content-Type: application/x-qbmsxml'; 
			$header[] = 'Content-Length: ' . strlen($xml); 
	
	
			// echo $host.'<br>';
			//$host = 'https://merchantaccount.ptc.quickbooks.com/j/AppGateway';
			//$host = 'https://webmerchantaccount.ptc.quickbooks.com/j/AppGateway';
			$ch = curl_init($data['host']);
			  
			 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			 
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
 			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			
			
			$result = simplexml_load_string($output);
			//echo "<pre>";
			//print_r($result);
			//exit();
			return $result;
			 
		
		
	}

 

	 

	function buildXML($pdata)
	{

		
		
		$SignonDesktopRq .= '<SignonMsgsRq>';
 		$SignonDesktopRq .= '<SignonDesktopRq>';
  		$SignonDesktopRq .= '<ClientDateTime>'.$pdata['datetime'].'</ClientDateTime>';
  		$SignonDesktopRq .= '<ApplicationLogin>'.$pdata['quickbook_appname'].'</ApplicationLogin>';
  		$SignonDesktopRq .= '<ConnectionTicket>'.$pdata['quickbook_key'].'</ConnectionTicket>';
 		$SignonDesktopRq .= '</SignonDesktopRq>';
		$SignonDesktopRq .= '</SignonMsgsRq>';
		
		
		
		$QBMSXMLMsgsRq   = '<QBMSXMLMsgsRq>';
 		$QBMSXMLMsgsRq  .= '<CustomerCreditCardChargeRq>';
  		$QBMSXMLMsgsRq  .= '<TransRequestID>'.$pdata['transid'].'</TransRequestID>';
  		$QBMSXMLMsgsRq  .= '<CreditCardNumber>'.$pdata['qb_cardno'].'</CreditCardNumber>';
  		$QBMSXMLMsgsRq  .= '<ExpirationMonth>'.$pdata['qb_expm'].'</ExpirationMonth>';
  		$QBMSXMLMsgsRq  .= '<ExpirationYear>'.$pdata['qb_expy'].'</ExpirationYear>';
  		$QBMSXMLMsgsRq  .= '<IsCardPresent>false</IsCardPresent>';
 		$QBMSXMLMsgsRq  .= ' <Amount>'.$pdata['amount'].'.00</Amount>';
 		$QBMSXMLMsgsRq  .= '</CustomerCreditCardChargeRq>';
 		$QBMSXMLMsgsRq  .= '</QBMSXMLMsgsRq>';
		
		
		
		
		
 		$xml = '<?xml version="1.0"?><?qbmsxml version="4.5"?>
<QBMSXML>'.$SignonDesktopRq.$QBMSXMLMsgsRq.'</QBMSXML>';
 		
	return $xml;
	
	
	
	
	 
	}
}
?>