<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +----------------------------------------------------------------------+
// | File name : Utils.php                                         		  |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: ARUN SADASIVAN<arun.s@armiasystems.com>              		  |
// +----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                    |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+

class Utils {

    public static function sendUserMail($content) {
        //echo $content;exit;
    }

    /*
     * Function to create random string
     * Input : string length <M>
     */

    public static function rand_string($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        } // End For
        return $str;
    }

// End Function

    public static function pageInfo($currentPage, $fullCount, $maxRows) {
        $data = array();
        $maxPages = ceil($fullCount / $maxRows);
        $base = (empty($currentPage)) ? 0 : ($currentPage - 1) * $maxRows;
        $page = (empty($currentPage)) ? 1 : $currentPage;
        $indent = $maxRows;
        // Page Components
        $data['maxPages'] = $maxPages;
        $data['base'] = $base;
        $data['indent'] = $indent;
        $data['limit'] = $base . ',' . $indent;
        $data['page'] = $page;
        $data['pagecount'] = $fullCount;

        return $data;
    }

// End Function

    public static function stripWhitespaces($string = NULL) {
        $stringStripped = NULL;
        if (!empty($string)) {
            $stringStripped = preg_replace('/\s+/', '', $string);
        }
        return $stringStripped;
    }

// End Function

    public static function stripChar($charArr, $string) {
        /*
         * example
          $stringArr[] = "'";
          $stringArr[] = '"';
          $stringArr[] = "-";
         */

        $stringN = str_replace($charArr, "", $string);
        return $stringN;
    }

// End Function

    public static function isValidDomainname($str) {
        if (trim($str) != "") {
            if (preg_match("/[^0-9a-zA-Z+-]/", $str)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function formatBytes($size, $precision = 2) {
        $base = log($size) / log(1024);
        $suffixes = array('', 'k', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
    }

    public static function formatDate($date, $time = FALSE, $type = 'timestamp') {
        $newDate = '--';
        if (!empty($date)) {
            if (Utils::checkDateTime($date, $type)) {
                $newDate = ($time) ? date('d M Y H:i', strtotime($date)) : date('d M Y', strtotime($date));
            }
        }
        return $newDate;
    }

    public static function formatDateUS($date, $time = FALSE, $type = 'timestamp') {
        $newDate = '--';
        if (!empty($date)) {
            if (Utils::checkDateTime($date, $type)) {
                $newDate = ($time) ? date('m/d/Y H:i', strtotime($date)) : date('m/d/Y', strtotime($date));
            }
        }
        return $newDate;
    }

    public static function reconnect() {

        @mysqli_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD,MYSQL_DB);

        //mysql_select_db(MYSQL_DB);
        return true;
    }

    public static function formatPrice($amount) {
        $amount = (empty($amount)) ? 0 : $amount;
        $price = number_format($amount, 2, '.', '');
        return $price;
    }

//End Function

    public static function serializeNencodeArr($dataArr) {
        return urlencode(serialize($dataArr));
    }

//End Function

    public static function unserializeNdecodeArr($dataArr) {
        return unserialize(urldecode($dataArr));
    }

//End Function

    public static function is_valid_email($address) {
        Logger::info("Checking validity of email :");
        $rx = "^[a-z0-9\\_\\.\\-]+\\@[a-z0-9\\-]+\\.[a-z0-9\\_\\.\\-]+\\.?[a-z]{1,4}$";
        return (preg_match("~" . $rx . "~i", $address));
    }

    public static function replacDateBySlashes($date) {
        $newDate = (!empty($date)) ? str_replace('-', '/', $startDate) : NULL;
        return $newDate;
    }

// End Function

    public static function doExcelExport($fieldHeaders, $fieldValues, $filePrefix = 'Report') {

        $filename = $filePrefix . "_" . date('Ymd') . ".xls";
        PageContext::includePath('Excel');
        $objPHPExcel = new PHPExcel;
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        $numberFormat = '#,#0.##;[Red]-#,#0.##';
        $objSheet = $objPHPExcel->getActiveSheet();
        $objSheet->setTitle('My sales report');
        $rows = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        $index = 0;
        foreach ($fieldHeaders as $row) {
            $column = $rows[$index] . '1';
            $objSheet->getCell($column)->setValue($row);
            $objPHPExcel->getActiveSheet()->getColumnDimension($rows[$index])->setAutoSize(true);
            ;
            $index++;
        }
        $objSheet->getStyle('A1:' . $column)->getFont()->setBold(true)->setSize(12);
        $rowIndex = 2;
        foreach ($fieldValues as $row) {
            $index = 0;
            foreach ($row as $item) {
                $column = $rows[$index] . $rowIndex;
                $objSheet->getCell($column)->setValue($item);
                $index++;
            }
            $rowIndex++;
        }
        ob_end_clean();

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();

        $objWriter->save('php://output');
        $objPHPExcel->disconnectWorksheets();
        unset($objPHPExcel);
        exit;
    }

    public static function number_to_words($number) {

        if ($number > 999999999) {

            throw new Exception("Number is out of range");

        }

        $Gn = floor($number / 1000000);  /* Millions (giga) */

        $number -= $Gn * 1000000;

        $kn = floor($number / 1000);     /* Thousands (kilo) */

        $number -= $kn * 1000;

        $Hn = floor($number / 100);      /* Hundreds (hecto) */

        $number -= $Hn * 100;

        $Dn = floor($number / 10);       /* Tens (deca) */

        $n = $number % 10;               /* Ones */

        $cn = round(($number - floor($number)) * 100); /* Cents */

        $result = "";



        if ($Gn) {

            $result .= Utils::number_to_words($Gn) . " Million";

        }



        if ($kn) {

            $result .= (empty($result) ? "" : " ") . Utils::number_to_words($kn) . " Thousand";

        }



        if ($Hn) {

            $result .= (empty($result) ? "" : " ") . Utils::number_to_words($Hn) . " Hundred";

        }



        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",

            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",

            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",

            "Nineteen");

        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",

            "Seventy", "Eigthy", "Ninety");



        if ($Dn || $n) {

            if (!empty($result)) {

                $result .= " and ";

            }



            if ($Dn < 2) {

                $result .= $ones[$Dn * 10 + $n];

            } else {

                $result .= $tens[$Dn];

                if ($n) {

                    $result .= "-" . $ones[$n];

                }

            }

        }



        if ($cn) {

            if (!empty($result)) {

                $result .= ' and ';

            }

            $title = '';


            //$title = $cn==1 ? 'cent ': 'cents';
            $title = $cn==1 ? 'coin ': 'coins';

            $coinInWordsArr = Utils::coinInWords();
            $coinInWordsSingleArr = Utils::coinInWordsSingle();


            $currency = CURRENCY;
           if(!empty($currency)) {
               if(array_key_exists($currency, $coinInWordsSingleArr) && array_key_exists($currency, $coinInWordsArr)){

                   $title = $cn==1 ? $coinInWordsSingleArr[$currency].' ': $coinInWordsArr[$currency];
               }
           }

            $result .= strtolower(Utils::number_to_words($cn)) . ' ' . $title;

        }



        if (empty($result)) {

            $result = "zero";

        }



        return $result;

    } //End Function

    public static function checkDateTime($data, $type = 'timestamp') {
        $checkDate = ($type == 'date') ? date('Y-m-d', strtotime($data)) : date('Y-m-d H:i:s', strtotime($data));
        if ($checkDate == $data) {
            return true;
        } else {
            return false;
        }
    }

// End Function

    public static function formatServiceExpiry($dataArr) {
        /* Example
          $dataArr = array('dGeneratedDate' => '',
          'vBillingInterval' => '',
          'nBillingDuration' => '');
         */
        $bStartDate = $dataArr['dGeneratedDate'];

        switch ($dataArr['vBillingInterval']) { // $dataArr['nBillingDuration']
            case 'M':
                $addDays = NULL;
                if ($dataArr['nBillingDuration'] == 1) {
                    $addDays = " +" . $dataArr['nBillingDuration'] . " day";
                } else if ($dataArr['nBillingDuration'] > 1) {
                    $addDays = " +" . $dataArr['nBillingDuration'] . " days";
                }

                $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addDays);
                $bStopDate = date("Y-m-d H:i:s", $bStopDate);
                break;
            case 'Y':
                $addYear = NULL;
                $addYear = " +" . $dataArr['nBillingDuration'] . " years";
                $bStopDate = strtotime(date("Y-m-d", strtotime($bStartDate)) . $addYear);
                $bStopDate = date("Y-m-d H:i:s", $bStopDate);
                break;
            case 'L':
                $bStopDate = NULL;  // Unlimited Service
                break;
        }
        Logger::info($bStopDate);

        return $bStopDate;
    }

// End Function


    /*
     * funtion to get the active theme
     */

    public static function loadActiveTheme() {
        $db = new Db();
        $themeData = $db->selectResult('themes', 'theme_name', 'theme_status=1');
        $activeTheme = $themeData[0]->theme_name;
        //TODO: add theme folder existing validation
        PageContext::addStyle("themes/" . $activeTheme . "/layout.css");
        PageContext::addStyle("themes/" . $activeTheme . "/theme.css");
        //return $activeTheme;
    }

    public static function getThemeUrl() {
        $db = new Db();
        $themeData = $db->selectResult('themes', 'theme_name', 'theme_status=1');
        $activeTheme = $themeData[0]->theme_name;
        return BASE_URL . 'project/styles/themes/' . $activeTheme . '/';
    }

    public static function loadScreenShots() {
        $db = new Db();
        $res = $db->selectResult('Products', '*', 'nStatus=1');
        return $res;
    }

    // For Stripslashes
    public static function stripslashes($string) {

        return stripslashes($string);
    }

    // Escape Strings before post  // data sanitising
    public static function formatPostData($string) {
        //return mysqli_real_escape_string($string);
        return ($string);
    }

    public static function subString($string, $length = 100, $append = NULL, $start = 0) {
        $content = NULL;
        if (!empty($string)) {
            $content = stripslashes(substr($string, $start, $length));
            $content .= $append;
        }
        return $content;
    }


    public static function getSettingsData($field)
    {
    	$db = new Db();
        $settingsData = $db->selectResult('Settings', 'value', "settingfield='".$field."'");
        $settingsVal = $settingsData[0]->value;
        return $settingsVal;

    }

    public function uploadFile($files) {
        $fileHandler = new Filehandler();

        $upload = $fileHandler->handleUpload($files);
        return $upload;
    }

    /*
    * function to generate thumbnail
    */
    public function createThumbnail($photoid,$thumbtype,$crop=true) {

        global $imageTypes;

        $model 			= new Db();
        $fileDet 	= $model->selectRecord("files","file_orig_name,file_path","file_id=".$photoid);
        $sourceFile = FILE_UPLOAD_DIR.'/'. $fileDet->file_path;
        $destFile  = FILE_UPLOAD_DIR.'/'.$imageTypes[$thumbtype]['prefix']. $fileDet->file_path;
        $ih = new Gdimagehandler($sourceFile);
        $ih->generateThumbnail($destFile,$imageTypes[$thumbtype]['width'],$imageTypes[$thumbtype]['height'],$crop);

    }

    public function generateThumbnail($imgUrl,$thumbtype,$crop=true) {

        global $imageTypes;

        $sourceFile = FILE_UPLOAD_DIR.'/'. $imgUrl;
        $destFile  = FILE_UPLOAD_DIR.'/'.$imageTypes[$thumbtype]['prefix']. $imgUrl;
        $ih = new Gdimagehandler($sourceFile);
        $ih->generateThumbnail($destFile,$imageTypes[$thumbtype]['width'],$imageTypes[$thumbtype]['height'],$crop);
        return $imageTypes[$thumbtype]['prefix']. $imgUrl;
    }

    public static function bindEmailTemplate($mailContent){
        $mailMsgArr = Admincomponents::getListItem("Cms", array('cms_title','cms_desc'), array(array('field' => 'LOWER(cms_name)', 'value' => strtolower('email_template'))));
            Logger::info($mailMsg);

           $mailMsg = NULL;

            if(count($mailMsgArr) > 0) {

                $mailMsg = $mailMsgArr[0]->cms_desc;
            } // End If

            if(!empty($mailMsg)){
                $mailMsg = str_replace("{SITE_LOGO}", SITE_LOGO, $mailMsg);
                $mailMsg = str_replace("{DATE}", Utils::formatDateUS(date('Y-m-d'), false, 'date'), $mailMsg);
                $mailMsg = str_replace("{MAIL_CONTENT}", $mailContent, $mailMsg);
                $mailMsg = str_replace("{SITE_NAME}", SITE_NAME, $mailMsg);
            } else {
                $mailMsg =$mailContent;
            }
            return $mailMsg;

    }

    public static function splitEmailIntoParts($email, $opType = 'all'){
        $partsArr = array();
        if(!empty($email)){
            $parts = explode('@', $email);
            $user = $parts[0];
            //$domain = "@" . $parts[1];
            $domain = $parts[1];
            $partsArr = array('user' => $user,
                               'domain' => $domain);
        } // End If

        switch($opType){
            case 'user':
                return $user;
                break;
            case 'domain':
                return $domain;
                break;
            case 'all':
                return $partsArr;
                break;
        }

    } // End Function

    public static function coinInWordsSingle(){
        $dataArr = array("USD" => "cent",
                          "CAD" => "cent",
                         "GBP" => "pence");
        return $dataArr;
    } // End Function

    public static function coinInWords(){
        $dataArr = array("USD" => "cents",
                          "CAD" => "cents",
                         "GBP" => "pence");

        return $dataArr;
    } // End Function

}

// End Class
?>
