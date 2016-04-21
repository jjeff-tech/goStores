<?php

function isNotNull($value) {
    if (is_array($value)) {
        if (sizeof($value) > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
            return true;
        } else {
            return false;
        }
    }
}

function sendMail($id, $to, $mailBody, $subject, $conn) {
    $sql = "Select * from tbl_help_address  where id='$id'";
    $result = executeSelect($sql, $conn);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $replyName = $row["vReplyName"];
        $replyAddress = $row["vReplyAddress"];
        $fromName = $row["vFromName"];
        $fromAddress = $row["vFromAddress"];
    }

    $EMail = $to;
    $Headers = "From: $fromName <$fromAddress>\n";
    $Headers.="Reply-To: $replyName <$replyAddress>\n";
    $Headers.="MIME-Version: 1.0\n";
    $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    mail($EMail, $subject, $mailBody, $Headers);
}

function pageBrowser($totalrows, $numLimit, $amm, $queryStr, $numBegin, $start, $begin, $num) {
    $larrow = "&nbsp;<<&nbsp;"; //You can either have an image or text, eg. Previous
    $rarrow = "&nbsp;>>&nbsp;"; //You can either have an image or text, eg. Next
    $wholePiece = ""; //This appears in front of your page numbers
    if ($totalrows > 0) {
        $numSoFar = 1;
        $cycle = ceil($totalrows / $amm);
        if (!isset($numBegin) || $numBegin < 1 || $numBegin == "") {
            $numBegin = 1;
            $num = 1;
        }
        if (!isset($start) || $start < 0 || $start == "") {
            $minus = $numBegin - 1;
            $start = $minus * $amm;
        }
        if (!isset($begin) || $begin == "") {
            $begin = $start;
        }
        $preBegin = $numBegin - $numLimit;
        $preStart = $amm * $numLimit;
        $preStart = $start - $preStart;
        $preVBegin = $start - $amm;
        $preRedBegin = $numBegin - 1;
        if ($start > 0 || $numBegin > 1) {
            $wholePiece .= "<a href='?num=" . $preRedBegin
                    . "&start=" . $preStart
                    . "&numBegin=" . $preBegin
                    . "&begin=" . $preVBegin
                    . $queryStr . "' class=listing>"
                    . $larrow . "</a>\n";
        }
        for ($i = $numBegin; $i <= $cycle; $i++) {
            if ($numSoFar == $numLimit + 1) {
                $piece = "<a href='?numBegin=" . $i
                        . "&num=" . $i
                        . "&start=" . $start
                        . $queryStr . "' class=listing>"
                        . $rarrow . "</a>\n";
                $wholePiece .= $piece;
                break;
            }
            $piece = "<a href='?begin=" . $start
                    . "&num=" . $i
                    . "&numBegin=" . $numBegin
                    . $queryStr
                    . "' class=listing>";
            if ($num == $i) {
                $piece .= "<b>$i</b>";
            } else {
                $piece .= "$i";
            }
            $piece .= "</a>\n";
            $start = $start + $amm;
            $numSoFar++;
            $wholePiece .= $piece;
        }
        $wholePiece .= "\n";
        if ($cycle == 1)
            $wholePiece = '';
        $wheBeg = $begin + 1;
        $wheEnd = $begin + $amm;
        $wheToWhe = "<b>" . $wheBeg . "</b> - <b>";
        if ($totalrows <= $wheEnd) {
            $wheToWhe .= $totalrows . "</b>";
        } else {
            $wheToWhe .= $wheEnd . "</b>";
        }
        if ($begin < 0)
            $begin = 0;
        $sqlprod = " LIMIT " . $begin . ", " . $amm;
    } else {
        $wholePiece = "<div class='msg_common msgwidth1' style='width:948px;'>" . MESSAGE_NO_RECORDS . "</div>";
        $wheToWhe = "<b>0</b> - <b>0</b>";
    }
    return array($sqlprod, $wheToWhe, $wholePiece);
}

function isValidEmail($email) {
    //if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
    if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
        return false;
    }
    return true;
}

function isUniqueEmail($email, $var_id = 0, $var_compid = 0) {
    global $conn;
    $var_str = "";
    if ($var_compid != 0) {
        $var_str .= " AND u.nCompId = '{$var_compid}'";
    }
    if ($var_id != 0) {
        $var_str .= " AND u.nUserId != '" . mysql_real_escape_string($var_id) . "'";
    }
    $sql = "Select * from dummy d
		Left join sptbl_users u on (d.num=0 AND u.vDelStatus='0' AND u.vEmail='" . mysql_real_escape_string($email) . "'{$var_str})
		Left JOIN sptbl_staffs s on (d.num=1 AND s.vMail='" . mysql_real_escape_string($email) . "')
		Left join sptbl_depts dt on (d.num=2 AND dt.vDeptMail='" . mysql_real_escape_string($email) . "')
		Left join sptbl_companies c on(d.num=3 AND c.vCompMail='" . mysql_real_escape_string($email) . "')
		where d.num < 4  AND (u.nUserId IS NOT NULL OR s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
    if (mysql_num_rows(executeSelect($sql, $conn)) > 0) {
        return false;
    } else {
        $sql = "Select nLookUpId from sptbl_lookup where vLookUpValue='" . mysql_real_escape_string($email) . "'
		AND vLookUpName IN('MailAdmin','MailTechnical','MailEscalation','MailFromMail','MailReplyMail')";
        if (mysql_num_rows(executeSelect($sql, $conn)) > 0) {
            return false;
        } else {
            return true;
        }
    }
}

function isValidUsername($str) {
    //if ( eregi ( "[^0-9a-zA-Z+_]", $str ) ) {
    if (!preg_match("/^[A-Za-z0-9.-]*$/", $str)) {
        return false;
    } else {
        return true;
    }
}

function userNameExists($username) {
    global $conn;
    $sql = "SELECT vLogin FROM sptbl_users  WHERE vLogin = '" . mysql_real_escape_string($username) . "' and vDelStatus = '0' ";
    $rs = executeSelect($sql, $conn);
    if (mysql_num_rows($rs) != 0) {
        return true;
    } else {
        return false;
    }
}

function getCompanyList() {
    global $conn;
    $complist = "";
    $sql = "SELECT nCompId, vCompName FROM sptbl_companies  WHERE vDelStatus = '0'";
    $rs = executeSelect($sql, $conn);
    if (mysql_num_rows($rs) != 0) {
        while (list($cid, $cname) = mysql_fetch_row($rs)) {
            $complist[$cid] = htmlentities($cname);
        }
        return $complist;
    } else {
        return null;
    }
}

function makeDropDownList($ddlname, $list, $selectedindex, $class, $properties, $behaviors, $def = true) {
    $ddl = "";
    $ddl.="<select name=\"$ddlname\" class=\"$class\"";
    if (isNotNull($properties)) {
        $ddl.= " \"$properties\"";
    }
    if (isNotNull($behaviors)) {
        $ddl.= " $behaviors ";
    }
    $ddl.= " >";
    if ($def)
        $ddl .="<option value=''>Select</option>\n";
    if (count($list) > 0) {
        foreach ($list as $key => $value) {
            $ddl .= "<option value=\"$key\"";
            if ($selectedindex == "$key") {
                $ddl .=" selected=\"selected\"";
            }
            $ddl .=">" . $value . "</option>\n";
        }
    }
    $ddl.="</select>";
    return $ddl;
}

function makeCompanyDepartmentList($countryid) {
    static $options;
    global $conn;
    $sql = "SELECT nDeptId as id, vDeptDesc as name ";
    $sql .=" FROM sptbl_depts WHERE nCompId = '$countryid'";
    $resoptions = mysql_query($sql);
    $numoptions = mysql_num_rows($resoptions);
    if ($numoptions > 0) {
        while (list($deptid, $deptname) = mysql_fetch_row($resoptions)) {
            $options[$deptid] = $deptname;
        }
    }
    return $options;
}

function makeDepartmentList($current_dept_id, $count, $cmpid) {
    static $option_results;
    if (!isset($current_dept_id)) {
        $current_dept_id = 0;
    }
    $count = $count + 1;

    $sql = "SELECT nDeptId as id, vDeptDesc as name from sptbl_depts where nDeptParent = '$current_dept_id' and nCompId='$cmpid' order by name asc";
    $get_options = mysql_query($sql);
    $num_options = mysql_num_rows($get_options);
    if ($num_options > 0) {
        while (list($dept_id, $dept_name) = mysql_fetch_row($get_options)) {
            if ($current_dept_id != 0) {
                $indent_flag = "&nbsp;&nbsp;";
                for ($x = 2; $x <= $count; $x++) {
                    $indent_flag .= "--&gt;&nbsp;";
                }
            }
            $dept_name = $indent_flag . htmlentities($dept_name);
            $option_results[$dept_id] = $dept_name;
            makeDepartmentList($dept_id, $count, $cmpid);
        }
    }
    return $option_results;
}

//function to display categories in nested manner
function makeCategoryList($current_parentcat_id, $count, $deptid) {
    static $catlist;
    if (!isset($current_parentcat_id)) {
        $current_parentcat_id = 0;
    }
    $count = $count + 1;
    $sql = "SELECT nCatId as id, vCatDesc as name from sptbl_categories where nParentId = '$current_parentcat_id' and nDeptId= '$deptid' order by name asc";
    $get_options = mysql_query($sql);
    $num_options = mysql_num_rows($get_options);
    if ($num_options > 0) {
        while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) {
            if ($current_parentcat_id != 0) {
                $indent_flag = "&nbsp;&nbsp;";
                for ($x = 2; $x <= $count; $x++) {
                    $indent_flag .= "--&gt;&nbsp;";
                }
            }
            $cat_name = $indent_flag . htmlentities($cat_name);
            $catlist[$cat_id] = $cat_name;
            makeCategoryList($cat_id, $count, $deptid);
        }
    }
    return $catlist;
}

function loadCSS($userid) {
    global $conn;
    if (isNotNull($userid)) {
        $sql = " SELECT c.vCSSURL FROM sptbl_css c INNER JOIN sptbl_users u ON u.nCSSId = c.nCSSId ";
        $sql .=" WHERE u.nUserId='" . mysql_real_escape_string($userid) . "'";
        $result = executeSelect($sql, $conn);
        if (mysql_num_rows($result) > 0) {
            $row = mysql_fetch_array($result);
            $cssurl = $row["vCSSURL"];
        } else {
            $cssurl = "styles/helpdesk.css";
        }
    } else {
        $cssurl = "styles/helpdesk.css";
    }
    return "<link href=\"$cssurl\" rel=\"stylesheet\"  type=\"text/css\">";
}

/* function for upload file
  fname=name of the file filed
  upath=path to upload;
  ufilename=name of the file to be stored on server.
  atype=allowed type  all-for all types
  seperate by coma
  eg:image/gif,image/gpeg
  alsize=allowed file upload size
  return parameter
  FNA-file not uploaded
  IS- Size is invalid
  IT-Invalid Type
  NW-No Write Permission
  FE-File Already Exists
  $ufilename-successfully uploaded and return the name of the uploaded file
  eg-upload("txtUrl","../downloads/","","text/plain,text/richtext,image/jpeg,image/gif","10000");
 */

function upload($fname, $upath, $ufilename, $atype, $alsize) { 
    global $conn;
    if (is_uploaded_file($_FILES[$fname]['tmp_name'])) { //echo '<pre>'; print_r($_FILES); echo '</pre>';
///////// to prevent executable file uploading
        $filename1 = time() . $_FILES[$fname]['name']; 
        $blacklist = array("phtml", "php3", "php4", "js", "shtml", "pl", "py", "exe");
        foreach ($blacklist as $file) {
            if (preg_match("/\.$file\$/i", "$filename1")) { 
                return "IT";
            }
        }
////////

        if (!isValidFileName($_FILES[$fname]['name'])) {
            return "IF";
        }
        $size = $_FILES[$fname]['size'];
        $atype = "";
        $alsize = 0;
        $sql = "Select * from sptbl_lookup where vLookUpName IN('Attachments','MaxfileSize')";
        $result = executeSelect($sql, $conn);
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                switch ($row["vLookUpName"]) {
                    case "Attachments":
                        $var_attach_typearr = explode("|", $row["vLookUpValue"]);
//                                      
                        $atype = $atype . $var_attach_typearr[1] . ",";
                        $atype_extension = $atype_extension . $var_attach_typearr[0] . ",";
                        break;
                    case "MaxfileSize":
                        $alsize = $row["vLookUpValue"];
                        break;
                }
            }
        }
        mysql_free_result($result);
        $atype = substr($atype, 0, -1); 

        if ($size > $alsize or $size <= 0) {
            return "IS";
        }

        if ($atype != "all") {
            $allowetypearray = explode(",", $atype); 
            $allowetype_extn_array = explode(",", $atype_extension); 
            $file_type = $_FILES[$fname]['type']; 
            $file_type_extension = substr($_FILES[$fname]['name'], strrpos($_FILES[$fname]['name'], ".") + 1);
            
            $allowed_flag = 0;
            $allowedextn_flag = 0;
          /*  foreach ($allowetypearray as $key => $value) {

                if (strcasecmp($file_type, $value) == 0) {
                    $allowed_flag = 1;
                    break;
                }
            }*/
            foreach ($allowetype_extn_array as $key => $value) {
                if (strcasecmp($file_type_extension, $value) == 0) {
                    $allowedextn_flag = 1;
                    $allowed_flag = 1;
                    break;
                }
            }
            if ($allowed_flag == "0" or $allowedextn_flag == "0") {
                return "IT";
            }
        }

        if ($ufilename == "") {
            $ufilename = time() . $_FILES[$fname]['name'];
        }
        $file_name = $upath . $ufilename;
        if (is_file($file_name)) {
            return "FE";
        } elseif (substr(trim($ufilename), 0, 1) == ".") {
            return "IF";
        }

        $mvstatus = @move_uploaded_file($_FILES[$fname]['tmp_name'], $file_name);
        if (!$mvstatus) {

            return "NW";
        }
        chmod($file_name, 0777);
        return $ufilename;
    } else {
        return "FNA";
    }
}

function trim_the_string($str,$length="20") {
    if (strlen($str) <= $length)
        return $str;
    else
        return substr($str, 0, $length) . "..";
}

function datetimetomysql($vdate) {
    $vdate_ar = explode(" ", $vdate);
    $split_date = explode("-", $vdate_ar[0]);
    $split_time = explode(":", $vdate_ar[1]);
    $day = $split_date[1];
    $mnth = $split_date[0];
    $year = $split_date[2];
    $hour = $split_time[0];
    $minute = $split_time[1];
    $second = $split_time[2];
    if ($second == "")
        $second = "59";
    return $year . "-" . $mnth . "-" . $day . " " . $hour . ":" . $minute . ":" . $second;
}

function datetimefrommysql($vdate) {
    $vdate_ar = explode(" ", $vdate);
    $split_date = explode("-", $vdate_ar[0]);
    $split_time = explode(":", $vdate_ar[1]);
    $day = $split_date[2];
    $mnth = $split_date[1];
    $year = $split_date[0];
    $hour = $split_time[0];
    $minute = $split_time[1];
    $second = $split_time[2];
    if ($second == "")
        $second = "59";
    //return $year."-".$mnth."-".$day." ".$hour.":".$minute.":".$second;
    // return $mnth."-".$day."-".$year." ".$hour.":".$minute.":".$second;
    return $mnth . "-" . $day . "-" . $year;
}

function getClientIP() {
    // Get REMOTE_ADDR as the Client IP.
    $client_ip = (!empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( (!empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );

    // Check for headers used by proxy servers to send the Client IP. We should look for HTTP_CLIENT_IP before HTTP_X_FORWARDED_FOR.
    if ($_SERVER["HTTP_CLIENT_IP"])
        $proxy_ip = $_SERVER["HTTP_CLIENT_IP"];
    elseif ($_SERVER["HTTP_X_FORWARDED_FOR"])
        $proxy_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

    // Proxy is used, see if the specified Client IP is valid. Sometimes it's 10.x.x.x or 127.x.x.x... Just making sure.
    if ($proxy_ip) {
        if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $proxy_ip, $ip_list)) {
            $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10.\.*/', '/^224.\.*/', '/^240.\.*/');
            $client_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
        }
    }
    // Return the Client IP.
    return $client_ip;
}

function getLeafDepts() {
    global $conn;
    $dids = "";
    $pids = "";
    $sql = "select nDeptId,nDeptParent from sptbl_depts ";
    $rs = executeSelect($sql, $conn);
    if (mysql_num_rows($rs) != 0) {
        while ($row = mysql_fetch_array($rs)) {
            $dids .= "," . $row["nDeptId"];
            $pids .= "," . $row["nDeptParent"];
        }
    } else {
        return "";
    }
    $pids = substr($pids, 1);
    $dids = substr($dids, 1);

    if ($dids != "") {
        //$pidarr=split(",",$pids );
        //$didarr=split(",",$dids );
        $pidarr = preg_split("/,/", $pids);
        $didarr = preg_split("/,/", $dids);
        $diffarray = array_diff($didarr, $pidarr);
        return $diffarray;
    } else {
        return "";
    }
}

function trimString($str, $numchars) {
    if (strlen($str) <= $numchars)
        return $str;
    else
        return substr($str, 0, $numchars) . "..";
}

function getCSSList() {
    global $conn;
    $sql = "SELECT nCSSId, vCSSName FROM sptbl_css ";
    $result = executeSelect($sql, $conn);
    if (mysql_num_rows($result) > 0) {
        while (list($cid, $cname) = mysql_fetch_array($result)) {
            $list[$cid] = htmlentities($cname);
        }
    }
    return $list;
}

function replacestr($rpstr) {
    //$var_replymatter_arr=split("\n",$rpstr);
    $var_replymatter_arr = preg_split("/\n/", $rpstr);
    $new_matter = "";
    foreach ($var_replymatter_arr as $key => $value) {
        $value = ">" . $value;
        if (strlen($value) > 32) {
            $var_reply = wordwrap($value, 32, "\n>");
            $new_matter .=$var_reply;
        } else {
            $new_matter .=$value;
        }
    }

    return $new_matter;
}

function replacestrforemail($rpstr) {
    $var_replymatter_arr = preg_split("/\n/", $rpstr);
    $new_matter = "";
    foreach ($var_replymatter_arr as $key => $value) {
        $value = ">" . $value;
        $new_matter .=$value;
    }
}

function getPageAddress() {
    return basename($_SERVER["SCRIPT_FILENAME"]) . "?" . $_SERVER["QUERY_STRING"];
}

function getPath() {
    $host = $_SERVER["HTTP_HOST"];
    $scriptname = $_SERVER["PHP_SELF"];
    $pos = strrpos($scriptname, "/");
    if ($pos === false) { //not found
        $path = $scriptname;
    } else {//found
        $path = substr($scriptname, 0, $pos);
    }
    return "http://" . $host . $path;
}

function isValidStatus($str) {
    if (preg_match("~[^0-9a-zA-Z+_']~i", $str)) {
        return false;
    } else {
        return true;
    }
}

function isValidFileName($str) {
    // if ( eregi ( "[^0-9a-zA-Z+_.' ]", $str ) ) {
    //if ( preg_match ( "/^[^\\/?*:;{}\\\\]+\\.[^\\/?*:;{}\\\\]{3}$/", $str ) ) {
    if (preg_match("/^[a-z0-9-]+$/", $str)) {
        return false;
    } else {
        return true;
    }
}

function displayReadable($mts) {
    $return = "";
    if ($mts < 0) {
        $final = "-";
        $mts = -1 * $mts;
    } else {
        $final = "";
    }
    $hrs = (int) ($mts / 60);
    $mts = $mts % 60;
    if ($hrs >= 24) {
        $days = (int) ($hrs / 24);
        $hrs = $hrs % 24;
    }
    $return = ($hrs > 0) ? (($days > 0) ? ($days . " days," . $hrs . "hrs") : $hrs . " hrs") : (($days > 0) ? ($days . " days") : "");
    $return .= ((strlen($return) > 0)) ? (($mts > 0) ? ("," . $mts . " mts") : "") : $mts . " mts";
    return ($final . $return);
}

function isValidCredentials($var_userid, $deptid, $priority) {
    global $conn;
    $sql = "Select nUserId from sptbl_users where nUserId='$var_userid' AND vBanned='0' AND vDelStatus='0'";
    if (mysql_num_rows(executeSelect($sql, $conn)) <= 0) {
        return false;
    }
    $sql = "Select nDeptId from sptbl_depts where nDeptId='$deptid'";
    if (mysql_num_rows(executeSelect($sql, $conn)) <= 0) {
        return false;
    }
    $sql = "Select nPriorityValue from sptbl_priorities where nPriorityValue='$priority'";
    if (mysql_num_rows(executeSelect($sql, $conn)) <= 0) {
        return false;
    }
    return true;
}

function getLicense() {
    global $conn;
    $sql = "SELECT * FROM sptbl_lookup WHERE  vLookUpName = 'vLicenceKey'";
    $result = executeSelect($sql, $conn);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $var_licencekey = stripslashes($row["vLookUpValue"]);
    }
    return $var_licencekey;
}

function getAdminMail() {
    global $conn;
    $sql = "SELECT * FROM sptbl_lookup WHERE vLookUpName = 'MailAdmin'";
    $result = executeSelect($sql, $conn);
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $var_adminmail = stripslashes($row["vLookUpValue"]);
    }
    return $var_adminmail;
}

//-------Auto return mail for new ticket---------------
function isAutoReturnMailNeeded() {
    global $conn;
    $sql = "SELECT vLookUpValue FROM sptbl_lookup  WHERE vLookUpName = 'NewTicketAutoReturnMail'";
    $rs = executeSelect($sql, $conn);
    if (mysql_num_rows($rs) != 0) {
        $row = mysql_fetch_array($rs);
        if ($row["vLookUpValue"] == "1") {
            return true;
        } else {
            return false;
        }
    }
    return true;
}

//-------Auto return mail for new ticket---------------
//-------Get theme url---------------
function getCurrentThemeUrl() {
    global $conn;
    $sql = "SELECT c.vCSSURL FROM sptbl_lookup l
                LEFT JOIN sptbl_css c ON c.nCSSId =l.vLookUpValue
                WHERE vLookUpName = 'Theme'";
    $rs = executeSelect($sql, $conn);
    if (mysql_num_rows($rs) != 0) {
        $row = mysql_fetch_array($rs);
        return $row["vCSSURL"];
    }
}

//-------Auto return mail for new ticket---------------

function getUserEmail($userid) {
    global $conn;
    $useremail = array();
    $sql = "Select DISTINCT vEmail from sptbl_useremail u ";
    $sql .=" where u.nUserId ='" . mysql_real_escape_string($userid) . "' AND u.vStatus = 'Y'";
    $result = executeSelect($sql, $conn);
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            $useremail[] = $row['vEmail'];
            ;
        }
    }
    return $useremail;
}

/*
 * get default sort type
 */

function getDefaultSortOrder() {

    global $conn;
    $getSortSql = "SELECT vLookUpValue 
                          FROM sptbl_lookup
                          WHERE vLookUpName = 'OldestMessageFirst'";
    $getSortRs = executeSelect($getSortSql, $conn);
    $getSortRw = mysql_fetch_array($getSortRs);
    if ($getSortRw['vLookUpValue'] == '0') {
        $defaultSortOrder = "DESC";
    } else {
        $defaultSortOrder = "ASC";
    }
    return $defaultSortOrder;
}


/*
 * Function to get ticket count
 */
function getTicketCount($ticketId)
{
    global $conn;
$getSortSql    = "SELECT COALESCE(count(*),0)
                    FROM dummy d
                    LEFT JOIN sptbl_tickets t ON ( d.num =0 AND t.nTicketId = '$ticketId')
                    LEFT JOIN sptbl_replies r ON ( d.num =1 AND r.nTicketId = '$ticketId'AND r.nHold =0 )
                    WHERE d.num <2 AND ( t.nTicketId IS NOT NULL OR r.nReplyId IS NOT NULL )
                    ORDER BY r.dDate";
         $getSortRs     = executeSelect($getSortSql,$conn);
         $getSortRw     = mysql_fetch_array($getSortRs);
         return $getSortRw[0];
    
}
/*
 * end
 */

function getTicketStatus($ticketId) {

    $getTicketStatusSql = "SELECT vStatus FROM sptbl_tickets WHERE nTicketId = $ticketId";
    $ticketStatusRes = mysql_query($getTicketStatusSql);
    $ticketStatusRw = mysql_fetch_array($ticketStatusRes);
    $ticketStatus = $ticketStatusRw["vStatus"];
    return $ticketStatus;
}

//-------Auto return mail for new ticket---------------

/* 
 * Fucntion get the Staff login name
 */
function getStaffName($staffId) {
    global $conn;
     $sql = "SELECT vLogin FROM  sptbl_staffs
                WHERE nStaffId = '$staffId'"; 
    $rs = executeSelect($sql, $conn);
    if (mysql_num_rows($rs) != 0) {
        $row = mysql_fetch_array($rs);
        return $row["vLogin"];
    }
}
function getSettingsValue($fieldName) {
    global $conn;
    $sql = "SELECT vLookUpValue FROM sptbl_lookup
            WHERE vLookUpName = '".$fieldName."'";
    $rs = executeSelect($sql, $conn);
    if (mysql_num_rows($rs) != 0) {
        $row = mysql_fetch_array($rs);
        return $row["vLookUpValue"];
    }

}
function insertStattics($ticket_id)
        {
            global $conn;
            $currDate = date('Y-m-d H:i:s');                       
            $sql  = "insert into sptbl_ticket_statistics(ticket_id,posted_date) VALUES($ticket_id,'".mysql_real_escape_string($currDate)."')";    
            executeQuery($sql,$conn);
        }
        
function getWebsitebuilderSettingsValue($fieldName) {
    $sql="SELECT vvalue FROM tbl_lookup WHERE vname='".$fieldName."'";
    $settingRes = mysql_query($sql);
    $settingVal = mysql_fetch_assoc($settingRes);
    return $settingVal['vvalue'];
}

function getAutohosterSettingsValue($fieldName) {
    $sql="SELECT vLookUpName,vLookUpValue FROM autohoster_lookup WHERE vLookUpName = '".$fieldName."'";
    $settingRes = mysql_query($sql);
    $settingVal = mysql_fetch_assoc($settingRes);
    return $settingVal['vLookUpValue'];
}
?>