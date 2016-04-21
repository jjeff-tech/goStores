<?php
		define("MAX_PARAMETER_LENGTH",4);		// set this number 	as the number of fields to be encrypted
//Initialization section
	$var_key=MAX_PARAMETER_LENGTH * 24;
	$glob_date_check="D"; // m - 1
	$glob_date_days=0;	  // m - 2
	$glob_domain_name="";
	$glob_licence_type="";
	define("MAX_KEY_LENGTH",$var_key);
	$arr_key=array();
	srand((double)microtime()*1000000);
	for($i=0;$i<=255;$i++) {
		$arr_key[$i] = chr($i);
	}
//End of Initialization section

	function decode($strDomain,$sep,$off) {
		global $arr_key;
		$result_domain="";
		for($i=0;$i<strlen($strDomain);($i=++$i + $sep)) {
			$result_domain .= $arr_key[array_search($strDomain{$i},$arr_key) - $off];
		}
		return $result_domain;
	}

	function decodeall($licenseKey) {
		global $arr_key;

		$arr_return=array();
		$search_start = -1 * (MAX_KEY_LENGTH + 300);
        $licenseKey=trim($licenseKey);
		$sub_value = substr($licenseKey,$search_start,MAX_KEY_LENGTH);
		$sub_value = decode($sub_value,0,120);

		$start=0;
		for($i=0;$i<MAX_PARAMETER_LENGTH;$i++) {
			$var_beg=substr($sub_value,$start,6); $start += 6;
			$var_wc=substr($sub_value,$start,6);  $start += 6;
			$var_sep=substr($sub_value,$start,6);  $start += 6;
			$var_off=substr($sub_value,$start,6);  $start += 6;

			$var_wc = $var_wc * $var_sep;

			$var_string = substr($licenseKey,(int)$var_beg,$var_wc);
			$var_string = decode($var_string,(int)$var_sep,(int)$var_off);
			$arr_return[$i]=$var_string;
		}
		return $arr_return;
	}

	function isValidDomain($var_domain)	{
		global $glob_domain_name;
		$var_domain=strtolower(trim($var_domain));
		$check_www = "www." . $var_domain; //eg: www.jeeva.org
		$main_domain = strtolower(trim($_SERVER['HTTP_HOST']));  //eg: jeeva.org
		$check_main = "www." . $main_domain;
		if (strcmp($main_domain,$var_domain) == 0 || strcmp($check_www,$main_domain) == 0 || strcmp($var_domain,$check_main) == 0) {
			$glob_domain_name=$var_domain;
			return true;
		}
		else {
			$glob_domain_name=$var_domain;
			return true;
		}
	}

	function isValidPackage($var_type,$var_sd,$var_ed) {
		//(strtotime(date("Y-m-d",strtotime($arr["ddue"]))) < strtotime(date("Y-m-d"))
		global $glob_date_check,$glob_date_days,$glob_licence_type;
		$glob_licence_type=$var_type;
		if($var_type == "SDESK3" || $var_type == "PROD") {
			return true;
		}
		elseif($var_type == "FREE") {
			if(strtotime($var_ed) < strtotime(date("Y-m-d"))) {
				return false;
			}
			else {

				//MODIFIED AS PER REQUEST
				$var_temp = strtotime($var_ed);
				$var_temp=mktime(0,0,0,date('m',$var_temp),date('d',$var_temp) - 10,date('Y',$var_temp));
				if($var_temp <= strtotime(date("Y-m-d")))
				{
					$glob_date_check = "Y";  //Indicates that the present day is nearly 10 or less days away from end day
					$glob_date_days = (strtotime($var_ed) - strtotime(date("Y-m-d"))) / 86400;
				}
				//END MODIFICATION
				return true;
			}
		}
		else {
			return false;
		}
	}

	function isValid($rel=0,$comp='A') {
		$path="license/license.txt";
		if ($rel == "1") {


			$path = "../license/license.txt";
		}
		if($fp = @fopen($path,"r")) {
			$buffer = fread($fp,filesize($path));
			$buffer=trim($buffer);
			$arr_keys=decodeall($buffer);
			fclose($fp);
			switch($comp) {
				case 'A':
						if (!isValidDomain($arr_keys[0])) {
							//echo("Invalid Domain");
							return false;
						}
						elseif(!isValidPackage($arr_keys[1],$arr_keys[2],$arr_keys[3])) {
							//echo("Invalid Package" . $arr_keys[1] . " : " . $arr_keys[2] . " : " . $arr_keys[3]);
							return false;
						}
						else{
							return true;
						}
						;
						break;
				case 'D':
						if(!isValidDomain($arr_keys[0])) {
							//echo("Invalid Domain");
							return false;
						}
						else {
							return true;
						}
						break;
				case 'P':
						if(!isValidPackage($arr_keys[1],$arr_keys[2],$arr_keys[3])) {
							//echo("Invalid Package");
							return false;
						}
						else {
							return true;
						}
						break;
			}
		}
		else {
			//echo("Invalid file.");
			return false;
		}
	}

	function isValidForParser($rel=0,$comp='A',$realpath) {
		$path="license/license.txt";
		if ($rel == "1") {

			$path = "$realpath/license/license.txt";

		}
		if($fp = @fopen($path,"r")) {

			$buffer = fread($fp,filesize($path));
			$buffer=trim($buffer);
			$arr_keys=decodeall($buffer);
			fclose($fp);

			switch($comp) {
				case 'A':
						if (!isValidDomain($arr_keys[0])) {
							echo("Invalid Domain");
							return false;
						}
						elseif(!isValidPackage($arr_keys[1],$arr_keys[2],$arr_keys[3])) {
							echo("Invalid Package" . $arr_keys[1] . " : " . $arr_keys[2] . " : " . $arr_keys[3]);
							return false;
						}
						else{
							return true;
						}
						;
						break;
				case 'D':
						if(!isValidDomain($arr_keys[0])) {
							echo("Invalid Domain");
							return false;
						}
						else {
							return true;
						}
						break;
				case 'P':
						if(!isValidPackage($arr_keys[1],$arr_keys[2],$arr_keys[3])) {
							echo("Invalid Package");
							return false;
						}
						else {
							return true;
						}
						break;
			}
		}
		else {
			//echo("Invalid file.");
			return false;
		}

	}

?>