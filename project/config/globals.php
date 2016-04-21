<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | file to define the global variables needed in the application        |
// | File name : globals.php                                                 |
// | PHP version >= 5.2                                                   |
// | Created On 19 Dec 2011                                               |
// +----------------------------------------------------------------------+
// | Author: JINSON MATHEW <jinson.m@armiasystems.com>                    |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems ? 2011                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+
include_once('config.php');
date_default_timezone_set('America/Los_Angeles');

$dbObj = new Db();
$settingArr = $dbObj->selectResult("Settings", "settingfield, value");
$generalSettingArr = array();

foreach ($settingArr as $sItem) {
    $generalSettingArr[$sItem->settingfield] = $sItem->value;
}


$themesArr = $dbObj->selectResult("themes", "*","theme_status='1'");

//echopre($themesArr);

define('THEME', $themesArr[0]->theme_name);

define('SITE_NAME', stripslashes($generalSettingArr['siteName']));
define('SITE_URL', stripslashes($generalSettingArr['siteUrl']));
define('SECURE_URL', stripslashes($generalSettingArr['secureURL']));

define('ADMIN_EMAILS', $generalSettingArr['adminEmail']);
define('STRIPE_WEBHOOK', $generalSettingArr['WebhookURL']);

define('ADMIN_CURRENCY_SYMBOL', stripslashes($generalSettingArr['currency_symbol']));

define('CURRENCY', stripslashes($generalSettingArr['admin_currency']));

define('CURRENCY_SYMBOL', stripslashes($generalSettingArr['currency_symbol']));

define('COMPANY_NAME', stripslashes($generalSettingArr['company_name']));

define('COMPANY_ADDRESS', stripslashes(nl2br($generalSettingArr['company_address'])));

define('COMPANY_WEBSITE', stripslashes($generalSettingArr['company_website']));

define('COMPANY_EMAIL', stripslashes($generalSettingArr['company_email']));

define('COMPANY_PHONE', stripslashes($generalSettingArr['company_phone']));

define('COMPANY_PHONE_INT', stripslashes($generalSettingArr['company_phone_internat']));

define('META_TITLE', stripslashes($generalSettingArr['siteTitle']));
//
//define('META_DES', stripslashes($generalSettingArr['metaDescription']));
//
//define('META_KEYWORDS', stripslashes($generalSettingArr['metaKeywords']));

define('SITE_LOGO_FILE', stripslashes($generalSettingArr['siteLogo']));

define('SITE_LOGO_PREFIX', 'siteLogo_');

$logoArr = $dbObj->selectResult("files", "file_path", "file_id=" . $generalSettingArr['siteLogo']);
//echopre($logoArr);
    $logoArr[0]->file_path;
    if (is_file(FILE_UPLOAD_DIR . $logoArr[0]->file_path)) {
        $siteLogo = IMAGE_FILE_URL  . $logoArr[0]->file_path;
    }
//SITE LOGO
//echo $siteLogo; exit;
if (empty($siteLogo)) {
    $siteLogoFile = SITE_LOGO_FILE;
    $siteLogo = IMAGE_URL . 'gostores_logo.jpg';
}

define('SITE_LOGO', $siteLogo);

$siteEmailLogo = IMAGE_URL . 'gostores_logo.png';
define('SITE_EMAIL_LOGO', $siteEmailLogo);

//SITE LOGO END
//Recaptcha

define('RECAPTCHA_PUBLICKEY', '6LfryNQSAAAAAHh7zgGqZUYgp7oJDDmq49tBj9Rz');
define('RECAPTCHA_PRIVATEKEY', '6LfryNQSAAAAAEQGT_gjWxwxVAlfRu37pDR3sR1f');

// Server Operation mode
define('OPERATION_MODE_SERVER', stripslashes($generalSettingArr['site_operation_mode']));
define('OPERATION_MODE_PARK_DOMAIN', stripslashes($generalSettingArr['site_operation_park_domain']));

// Google Analytics
$googleAnalytics = $generalSettingArr['googleAnalytics'];
if (!empty($googleAnalytics)) {
    PageContext::$footerCodeSnippet = stripslashes($googleAnalytics);
}


$serverInfoArr = $dbObj->selectResult("ServerInfo", "vserver_name, vserver_hosting_plan,whmuser,whmpass,whmip,vserver_configfilename,vserver_configfilepath, whm_port, cpanel_port", "vmakethisserver_default='1'"); {
    /*
     * Ip, username,password,hostname,server package name, current domain name, product pack ocation and dfeult installation location.
     */

    define('WHM_USER_IP', $serverInfoArr[0]->whmip);
    define('WHM_USER_LOGIN', $serverInfoArr[0]->whmuser);
    $whmpass = User::decrytCreditCardDetails($serverInfoArr[0]->whmpass);
    define('WHM_USER_PASSWORD', $whmpass);
    define('WHM_USER_HOST', $serverInfoArr[0]->vserver_name);
    define('SERVER_PACKAGE_NAME', $serverInfoArr[0]->vserver_hosting_plan);
    define('DOMAIN_NAME', $serverInfoArr[0]->vserver_name);
    define('PRODUCT_LOCATION', $serverInfoArr[0]->vserver_configfilename);
    define('SUBDOMAIN_IN_SAME_SERVER', $serverInfoArr[0]->vserver_configfilepath);
    define('WHM_PORT', $serverInfoArr[0]->whm_port);
    define('CPANEL_PORT', $serverInfoArr[0]->cpanel_port);
}

$imageTypes = array("siteLogo" => array('prefix' => SITE_LOGO_PREFIX, 'height' => '75', 'width' => '345'));

function DisplayLookUp($name) {
    $sql = mysql_query("select set_value from " . MYSQL_TABLE_PREFIX . "settings where set_name='" . addslashes($name) . "'") or die(mysql_error());
    if (mysql_num_rows($sql) > 0) {
        return (mysql_result($sql, 0, 'set_value'));
    }//end if
}

function updateSettings($param, $value) {
    if ($param != '' && $value != '') {
        $sql = mysql_query("UPDATE " . MYSQL_TABLE_PREFIX . "settings SET set_value = '" . addslashes($value) . "' WHERE set_name='" . addslashes($param) . "'") or die(mysql_error());
    }
}

/*

  Function to print the array
 */

function echopre($printArray) {
    echo "<pre>";
    print_r($printArray);
    echo "</pre>";
}

function echopre1($printArray) {
    echo "<pre>";
    print_r($printArray);
    echo "</pre>";
    exit();
}

function fdate($dateval) {
    if ($dateval != '')
        return date('m-d-Y', $dateval);
}

/* Email validation */

function is_valid_email($address) {
    $rx = "^[a-z0-9\\_\\.\\-]+\\@[a-z0-9\\-]+\\.[a-z0-9\\_\\.\\-]+\\.?[a-z]{1,4}$";
    return (preg_match("~" . $rx . "~i", $address));
}

/*
  function to calculate the age. It shows how old our details with the current time

 */

function time_elapsed_string($ptime) {
    $etime = time() - $ptime;

    if ($etime < 1) {
        return '0 seconds';
    }

    $a = array(12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}

function verify_email($email) {

    if (!preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/', $email)) {
        return false;
    } else {
        return true;
    }
}

$countries = array(
    "US" => "United States",
    "GB" => "United Kingdom",
    "AF" => "Afghanistan",
    "AL" => "Albania",
    "DZ" => "Algeria",
    "AS" => "American Samoa",
    "AD" => "Andorra",
    "AO" => "Angola",
    "AI" => "Anguilla",
    "AQ" => "Antarctica",
    "AG" => "Antigua And Barbuda",
    "AR" => "Argentina",
    "AM" => "Armenia",
    "AW" => "Aruba",
    "AU" => "Australia",
    "AT" => "Austria",
    "AZ" => "Azerbaijan",
    "BS" => "Bahamas",
    "BH" => "Bahrain",
    "BD" => "Bangladesh",
    "BB" => "Barbados",
    "BY" => "Belarus",
    "BE" => "Belgium",
    "BZ" => "Belize",
    "BJ" => "Benin",
    "BM" => "Bermuda",
    "BT" => "Bhutan",
    "BO" => "Bolivia",
    "BA" => "Bosnia And Herzegowina",
    "BW" => "Botswana",
    "BV" => "Bouvet Island",
    "BR" => "Brazil",
    "IO" => "British Indian Ocean Territory",
    "BN" => "Brunei Darussalam",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "KY" => "Cayman Islands",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CX" => "Christmas Island",
    "CC" => "Cocos (Keeling) Islands",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CG" => "Congo",
    "CD" => "Congo, The Democratic Republic Of The",
    "CK" => "Cook Islands",
    "CR" => "Costa Rica",
    "CI" => "Cote D'Ivoire",
    "HR" => "Croatia (Local Name: Hrvatska)",
    "CU" => "Cuba",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "TP" => "East Timor",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FK" => "Falkland Islands (Malvinas)",
    "FO" => "Faroe Islands",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "FX" => "France, Metropolitan",
    "GF" => "French Guiana",
    "PF" => "French Polynesia",
    "TF" => "French Southern Territories",
    "GA" => "Gabon",
    "GM" => "Gambia",
    "GE" => "Georgia",
    "DE" => "Germany",
    "GH" => "Ghana",
    "GI" => "Gibraltar",
    "GR" => "Greece",
    "GL" => "Greenland",
    "GD" => "Grenada",
    "GP" => "Guadeloupe",
    "GU" => "Guam",
    "GT" => "Guatemala",
    "GN" => "Guinea",
    "GW" => "Guinea-Bissau",
    "GY" => "Guyana",
    "HT" => "Haiti",
    "HM" => "Heard And Mc Donald Islands",
    "VA" => "Holy See (Vatican City State)",
    "HN" => "Honduras",
    "HK" => "Hong Kong",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran (Islamic Republic Of)",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "Korea, Democratic People's Republic Of",
    "KR" => "Korea, Republic Of",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Lao People's Democratic Republic",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libyan Arab Jamahiriya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MO" => "Macau",
    "MK" => "Macedonia, Former Yugoslav Republic Of",
    "MG" => "Madagascar",
    "MW" => "Malawi",
    "MY" => "Malaysia",
    "MV" => "Maldives",
    "ML" => "Mali",
    "MT" => "Malta",
    "MH" => "Marshall Islands",
    "MQ" => "Martinique",
    "MR" => "Mauritania",
    "MU" => "Mauritius",
    "YT" => "Mayotte",
    "MX" => "Mexico",
    "FM" => "Micronesia, Federated States Of",
    "MD" => "Moldova, Republic Of",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "MS" => "Montserrat",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "AN" => "Netherlands Antilles",
    "NC" => "New Caledonia",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NU" => "Niue",
    "NF" => "Norfolk Island",
    "MP" => "Northern Mariana Islands",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PN" => "Pitcairn",
    "PL" => "Poland",
    "PT" => "Portugal",
    "PR" => "Puerto Rico",
    "QA" => "Qatar",
    "RE" => "Reunion",
    "RO" => "Romania",
    "RU" => "Russian Federation",
    "RW" => "Rwanda",
    "KN" => "Saint Kitts And Nevis",
    "LC" => "Saint Lucia",
    "VC" => "Saint Vincent And The Grenadines",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome And Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SK" => "Slovakia (Slovak Republic)",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "GS" => "South Georgia, South Sandwich Islands",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SH" => "St. Helena",
    "PM" => "St. Pierre And Miquelon",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SJ" => "Svalbard And Jan Mayen Islands",
    "SZ" => "Swaziland",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syrian Arab Republic",
    "TW" => "Taiwan",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania, United Republic Of",
    "TH" => "Thailand",
    "TG" => "Togo",
    "TK" => "Tokelau",
    "TO" => "Tonga",
    "TT" => "Trinidad And Tobago",
    "TN" => "Tunisia",
    "TR" => "Turkey",
    "TM" => "Turkmenistan",
    "TC" => "Turks And Caicos Islands",
    "TV" => "Tuvalu",
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "UM" => "United States Minor Outlying Islands",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VE" => "Venezuela",
    "VN" => "Viet Nam",
    "VG" => "Virgin Islands (British)",
    "VI" => "Virgin Islands (U.S.)",
    "WF" => "Wallis And Futuna Islands",
    "EH" => "Western Sahara",
    "YE" => "Yemen",
    "YU" => "Yugoslavia",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
);

$usStates = array('AL' => "Alabama",
    'AK' => "Alaska",
    'AZ' => "Arizona",
    'AR' => "Arkansas",
    'CA' => "California",
    'CO' => "Colorado",
    'CT' => "Connecticut",
    'DE' => "Delaware",
    'DC' => "District Of Columbia",
    'FL' => "Florida",
    'GA' => "Georgia",
    'HI' => "Hawaii",
    'ID' => "Idaho",
    'IL' => "Illinois",
    'IN' => "Indiana",
    'IA' => "Iowa",
    'KS' => "Kansas",
    'KY' => "Kentucky",
    'LA' => "Louisiana",
    'ME' => "Maine",
    'MD' => "Maryland",
    'MA' => "Massachusetts",
    'MI' => "Michigan",
    'MN' => "Minnesota",
    'MS' => "Mississippi",
    'MO' => "Missouri",
    'MT' => "Montana",
    'NE' => "Nebraska",
    'NV' => "Nevada",
    'NH' => "New Hampshire",
    'NJ' => "New Jersey",
    'NM' => "New Mexico",
    'NY' => "New York",
    'NC' => "North Carolina",
    'ND' => "North Dakota",
    'OH' => "Ohio",
    'OK' => "Oklahoma",
    'OR' => "Oregon",
    'PA' => "Pennsylvania",
    'RI' => "Rhode Island",
    'SC' => "South Carolina",
    'SD' => "South Dakota",
    'TN' => "Tennessee",
    'TX' => "Texas",
    'UT' => "Utah",
    'VT' => "Vermont",
    'VA' => "Virginia",
    'WA' => "Washington",
    'WV' => "West Virginia",
    'WI' => "Wisconsin",
    'WY' => "Wyoming");

function call_user_func_refined($functionName, $params1 = null, $params2 = null) {
    if (phpversion() < '5.2') {
        $dataVal = explode("::", $functionName);
        if ($params1 != '' && $params2 != '')
            $functionVal = call_user_func(array($dataVal[0], $dataVal[1]), $params1, $params2);
        else if ($params1 != '')
            $functionVal = call_user_func(array($dataVal[0], $dataVal[1]), $params1);
        else
            $functionVal = call_user_func(array($dataVal[0], $dataVal[1]));
    } else {
        if ($params1 != '' && $params2 != '')
            $functionVal = call_user_func($functionName, $params1, $params2);
        else if ($params1 != '')
            $functionVal = call_user_func($functionName, $params1);
        else
            $functionVal = call_user_func($functionName);
    }
    return $functionVal;
}


?>
