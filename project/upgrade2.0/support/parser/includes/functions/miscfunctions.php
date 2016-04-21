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

        function sendMail($id,$to,$mailBody,$subject,$conn) {

                $sql = "Select * from tbl_help_address  where id='$id'";
                $result = executeSelect($sql,$conn);
                if(mysql_num_rows($result) > 0) {
                        $row = mysql_fetch_array($result);

                        $replyName = $row["vReplyName"];
                        $replyAddress = $row["vReplyAddress"];
                        $fromName = $row["vFromName"];
                        $fromAddress = $row["vFromAddress"];
                }

                $EMail = $to;
                $Headers="From: $fromName <$fromAddress>\n";
                $Headers.="Reply-To: $replyName <$replyAddress>\n";
                $Headers.="MIME-Version: 1.0\n";
                $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

				 // it is for smtp mail sending
				 if($_SESSION["sess_smtpsettings"] == 1){
						$var_smtpserver = $_SESSION["sess_smtpserver"];
						$var_port = $_SESSION["sess_smtpport"];
			
						SMTPMail($fromAddress,$EMail,$var_smtpserver,$var_port,$subject,$mailBody);
				 }
				 else				
						@mail($EMail,$subject,$mailBody,$Headers);
        }

        function pageBrowser($totalrows,$numLimit,$amm,$queryStr,$numBegin,$start,$begin,$num) {
        $larrow = "&nbsp;<<&nbsp;"; //You can either have an image or text, eg. Previous
        $rarrow = "&nbsp;>>&nbsp;"; //You can either have an image or text, eg. Next
        $wholePiece = ""; //This appears in front of your page numbers
        if ($totalrows > 0) {
                $numSoFar = 1;
                $cycle = ceil($totalrows/$amm);
        if (!isset($numBegin) || $numBegin < 1 || $numBegin == "") {
            $numBegin = 1;
            $num = 1;
        }
        if (!isset($start) || $start < 0 || $start == "") {
            $minus = $numBegin-1;
            $start = $minus*$amm;
        }
        if (!isset($begin) || $begin == "") {
                $begin = $start;
        }
        $preBegin = $numBegin-$numLimit;
        $preStart = $amm*$numLimit;
        $preStart = $start-$preStart;
        $preVBegin = $start-$amm;
        $preRedBegin = $numBegin-1;
    if ($start > 0 || $numBegin > 1) {
                $wholePiece .= "<a href='?num=".$preRedBegin
                    ."&start=".$preStart
                ."&numBegin=".$preBegin
                ."&begin=".$preVBegin
                .$queryStr."'>"
                .$larrow."</a>\n";
        }
        for ($i=$numBegin;$i<=$cycle;$i++) {
                if ($numSoFar == $numLimit+1) {
                        $piece = "<a href='?numBegin=".$i
                       ."&num=".$i
                       ."&start=".$start
                       .$queryStr."'>"
                       .$rarrow."</a>\n";
                        $wholePiece .= $piece;
                        break;
                }
                $piece = "<a href='?begin=".$start
                        ."&num=".$i
                        ."&numBegin=".$numBegin
                        .$queryStr
                        ."'>";
                if ($num == $i) {
                        $piece .= "<b>$i</b>";
                } else {
                        $piece .= "$i";
                }
                $piece .= "</a>\n";
                $start = $start+$amm;
                $numSoFar++;
                $wholePiece .= $piece;
        }
        $wholePiece .= "\n";
        $wheBeg = $begin+1;
        $wheEnd = $begin+$amm;
        $wheToWhe = "<b>".$wheBeg."</b> - <b>";
        if ($totalrows <= $wheEnd) {
                $wheToWhe .= $totalrows."</b>";
        } else {
                $wheToWhe .= $wheEnd."</b>";
        }
        $sqlprod = " LIMIT ".$begin.", ".$amm;
        } else {
                $wholePiece = "<div class='msg_common msgwidth1'>".MESSAGE_NO_RECORDS."</div>";
                $wheToWhe = "<b>0</b> - <b>0</b>";
        }
        return array($sqlprod,$wheToWhe,$wholePiece);
        }
function trim_the_string($str){
   if(strlen($str)<=20)
      return $str;
   else
        return substr($str,0,20 ).".."          ;
}
function htmlpath($relative_path) {
   $realpath=realpath($relative_path);
   $htmlpath=str_replace($_SERVER['DOCUMENT_ROOT'],'',$realpath);
   return $htmlpath;
}
function valid_date($datetovalidate){
        $vdate=$datetovalidate;
                $vdate_ar=explode(" ",$vdate);

                if(count($vdate_ar)!=2){
                   return "invalid format";
                }else{
                     $split_date=explode("-",$vdate_ar[0]);
                     if(count($split_date)!=3)
                         return "Invalid format";
                         else{
                            $day=$split_date[1]        ;
                                $mnth=$split_date[0]        ;
                                $year=$split_date[2]        ;
                    }
                }
    if($day<1 or $day >31){
          return "Invalid Day!!!";
        }
        if($mnth<1 or $mnth >12){
          return "Invalid Month!!!!";
        }
    if($day >30 &&($mnth==2 or $mnth==4 or $mnth==6 or $mnth==9 or $mnth==11)){
          $dt= date("M",mktime('0','0','0',$mnth,'12','2000'));
          return "$dt  does not have 31 Days";
        }
        if($day >=30 && $mnth==2 ){
          return "Februvary Does not have $day Days";
        }
        if($day ==29 && $mnth==2 ){
            if( $year %4 ==0 &&($year % 100 !=0 or $year %400 ==0) ){
                   return "1";
                }else{
                                  return "Date is Not Valid!!!";
                }

        }
 return "1";
}
function datetimetomysql($vdate){
            $vdate_ar=explode(" ",$vdate);
            $split_date=explode("-",$vdate_ar[0]);
                $split_time=explode(":",$vdate_ar[1]);
            $day=$split_date[1]        ;
                $mnth=$split_date[0];
                $year=$split_date[2];
                $hour=$split_time[0];
                $minute=$split_time[1];
                $second=$split_time[2];
                if($second=="")
                   $second="59";
                   return $year."-".$mnth."-".$day." ".$hour.":".$minute.":".$second;

}
function datetimefrommysql($vdate){
  $vdate_ar=explode(" ",$vdate);
  $split_date=explode("-",$vdate_ar[0]);
  $split_time=explode(":",$vdate_ar[1]);
  $day=$split_date[2]        ;
  $mnth=$split_date[1];
  $year=$split_date[0];
  $hour=$split_time[0];
  $minute=$split_time[1];
  $second=$split_time[2];
   if($second=="")
           $second="59";
           //return $year."-".$mnth."-".$day." ".$hour.":".$minute.":".$second;
          // return $mnth."-".$day."-".$year." ".$hour.":".$minute.":".$second;
          return $mnth."-".$day."-".$year;

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
/*function upload($fname,$upath,$ufilename,$atype,$alsize){
    if (is_uploaded_file($_FILES[$fname]['tmp_name'])){
           $size = $_FILES[$fname]['size'];
           if ($size >$alsize or $size <=0){
                                             return "IS";
           }
           if($atype !="all"){
                                      $allowetypearray=explode(",",$atype);
                                          $file_type=$_FILES[$fname]['type'];

                                          $allowed_flag=0;
                                          foreach($allowetypearray as $key=>$value){
                                              if($file_type == $value){
                                                    $allowed_flag=1;
                                                        break;
                                                  }

                                          }
                                     if($allowed_flag=="0"){
                                           return "IT";
                                         }
           }


           if($ufilename==""){
             $ufilename=$_FILES[$fname]['name'];
           }
           $file_name=$upath.$ufilename;

           if(is_file($file_name)){
                return "FE";
           }



           $mvstatus=move_uploaded_file($_FILES[$fname]['tmp_name'],$file_name);
           if(! $mvstatus){

               return "NW";
           }
           chmod($file_name,0777);
      return $ufilename;
           }else{
            return "FNA";
        }

}*/
function upload($fname,$upath,$ufilename,$atype,$alsize){
    global $conn;
    if (is_uploaded_file($_FILES[$fname]['tmp_name'])){
///////// to prevent executable file uploading
			$filename1	=	$_FILES[$fname]['name'];
			$blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
			foreach ($blacklist as $file)
			{
				if(preg_match("/\.$file\$/i", "$filename1"))
				{
				   return "IT";
				}
			}
////////
	        if( ! isValidFileName($_FILES[$fname]['name'])){
				 return "IF";
			}
	        $size = $_FILES[$fname]['size'];
	        $atype="";
			$alsize=0;
		    $sql = "Select * from sptbl_lookup where vLookUpName IN('Attachments','MaxfileSize')";
			$result = executeSelect($sql,$conn);
			if(mysql_num_rows($result) > 0) {
				while($row = mysql_fetch_array($result)) {
				    switch($row["vLookUpName"]) {
			          case "Attachments":
									$var_attach_typearr =explode("|",$row["vLookUpValue"]);
					                $atype=$atype.$var_attach_typearr[1].",";
									$atype_extension=$atype_extension.$var_attach_typearr[0].",";
									break;
					   case "MaxfileSize":
									$alsize = $row["vLookUpValue"];
									break;
			       }

				}
			}
		    mysql_free_result($result);
		    $atype = substr($atype,0,-1);
			if ($size >$alsize or $size <=0){
	     				return "IS";
	        }


	   if($atype !="all"){
				      $allowetypearray=explode(",",$atype);
					  $allowetype_extn_array=explode(",",$atype_extension);
					  $file_type=$_FILES[$fname]['type'];
					  $file_type_extension=substr($_FILES[$fname]['name'],strrpos($_FILES[$fname]['name'],".")+1);
					  $allowed_flag=0;
					  $allowedextn_flag=0;
					  foreach($allowetypearray as $key=>$value){
					      if($file_type == $value){
						    $allowed_flag=1;
							break;
						  }

					  }
					   foreach($allowetype_extn_array as $key=>$value){
					      if($file_type_extension == $value){
						    $allowedextn_flag=1;
							break;
						  }

					  }
				     if($allowed_flag=="0" or $allowedextn_flag=="0" ){
					   return "IT";
					 }
	   }

	   if($ufilename==""){
	     $ufilename=$_FILES[$fname]['name'];
	   }
	   $file_name=$upath.$ufilename;
	   if(is_file($file_name)){
	        return "FE";
	   }
	   elseif(substr(trim($ufilename),0,1) == ".") {
	   		return "IF";
	   }

	  $mvstatus=@move_uploaded_file($_FILES[$fname]['tmp_name'],$file_name);
	   if(! $mvstatus){

	       return "NW";
	   }
	   chmod($file_name,0777);
      return $ufilename;
   	}else{
	    return "FNA";
	}

}

function updateCount($catid, $sign){
                global $conn;
                $sql = "UPDATE sptbl_categories SET nCount = nCount ".$sign." 1 WHERE  nCatId = '".$catid."' ";
                executeQuery($sql,$conn);
}
function deleteEntry($id){
        global $conn;
        global $var_staffid;
        $catid = getCategoryId($id);
        $sql = "DELETE FROM  sptbl_kb  where nKBID=$id ";
        executeQuery($sql,$conn);

        updateCount($catid,"-");
        //Insert the actionlog
		if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_DELETION . "','Knowledgebase','" . addslashes($id) . "',now())";
			executeQuery($sql,$conn);
		}
}
function changeStatus($id,$newstat){
        global $conn;
        global $var_staffid;
        $sql = "UPDATE sptbl_kb SET vStatus = '$newstat'  where nKBID= $id ";
        //echo "<br>".$sql ."<br>";
        executeQuery($sql,$conn);
        //Insert the actionlog
		if(logActivity()) {
			$sql = "Insert into sptbl_actionlog(nALId,nStaffId,vAction,vArea,nRespId,dDate) Values('','$var_staffid','" . TEXT_STATUS_CHANGE . "','Knowledgebase','" . addslashes($id) . "',now())";
			executeQuery($sql,$conn);
		}
}


function batchUpdate($list, $newstat){
        global $conn;
        global $var_staffid;
        $arr = explode(",",$list);
        for($i=0;$i<count($arr);$i++){
                changeStatus($arr[$i],$newstat);
        }
}
function getCategoryId($kbid){
        global $conn;
        $sqlparentcheck="select nCatId from sptbl_kb where nKBID='" . addslashes($kbid) . "'";
        $rs = executeSelect($sqlparentcheck,$conn);
        if(mysql_num_rows($rs)>0){
                  $row=mysql_fetch_array($rs);
                return $row['nCatId'];
        }else{
                return "";
        }
}
function getCategoriesWithEntries(){
        global $conn;
        $cids = "";
        $sql = "SELECT nCatId FROM sptbl_kb  ";
        $rs = executeSelect($sql,$conn);
        if(mysql_num_rows($rs)!=0){
                while($row = mysql_fetch_array($rs)){
                        $cids .= ",".$row["nCatId"];
                }
        }else{
                return "";
        }
        $cids = substr($cids,1);
        $ar = explode(",",$cids );
        reset($ar);
        $ar = array_values($ar);
        $ar = array_unique($ar);
        return implode(",",$ar);
}
function getParentCategories($deptid){
        global $conn;
        $pids = "";
        $sql = "SELECT nParentId FROM sptbl_categories  ";
        if($deptid !=""){
                $sql .= " WHERE nDeptId = $deptid ";
        }
        $rs = executeSelect($sql,$conn);
        if(mysql_num_rows($rs)!=0){
                while($row = mysql_fetch_array($rs)){
                        if($row["nParentId"]!= "0" ){
                                $pids .= ",".$row["nParentId"];
                        }
                }
        }else{
                return "";
        }
        $pids = substr($pids,1);
        $ar = explode(",",$pids );
        reset($ar);
        $ar = array_values($ar);
        $ar = array_unique($ar);
        return implode(",",$ar);
}
function isKBApprovalNeeded(){
        global $conn;
        $sql = "SELECT vLookUpValue FROM sptbl_lookup  WHERE vLookUpName = 'VerifyKB'";
        $rs = executeSelect($sql,$conn);
        if(mysql_num_rows($rs)!=0){
                $row = mysql_fetch_array($rs);
                if($row["vLookUpValue"] == "1"){
                        return true;
                }else{
                        return false;
                }
        }
        return true;
}
function isDuplicateKBEntry($title,$catid){
        global $conn;
        $sql = "SELECT vKBTitle FROM sptbl_kb  WHERE vKBTitle = '".addslashes($title)."' AND nCatId= '$catid' ";
        $rs = executeSelect($sql,$conn);
        if(mysql_num_rows($rs)==0){
                return false;
        }else{
                return true;
        }
}
function getLeafDepts(){
          global $conn;
          $dids="";
          $pids = "";
          $sql ="select nDeptId,nDeptParent from sptbl_depts ";
          $rs = executeSelect($sql,$conn);
          if(mysql_num_rows($rs)!=0){
                        while($row = mysql_fetch_array($rs)){
                                        $dids .= ",".$row["nDeptId"];
                                        $pids .= ",".$row["nDeptParent"];

                        }
          }else{
                        return "";
          }
                $pids = substr($pids,1);
                $dids = substr($dids,1);

                if($dids !=""){
                  $pidarr=explode(",",$pids );
                  $didarr=explode(",",$dids );
                  $diffarray=array_diff($didarr,$pidarr);
                  return  $diffarray;
                }else{
                  return "";
                }
}
function getCategoryName($catid){
                global $conn;
                $sqlparentcheck="select vCatDesc from sptbl_categories where nCatId='" . addslashes($catid) . "'";
                $rs = executeSelect($sqlparentcheck,$conn);
                if(mysql_num_rows($rs)>0){
                    $row=mysql_fetch_array($rs);
                        return $row['vDeptDesc'];
                }else{
                        return "";
                }
}
function getDepartmentName($depid){
                global $conn;
                $sql="select vDeptDesc from sptbl_depts where nDeptId='" . addslashes($depid) . "'";
                $rs = executeSelect($sql,$conn);
                if(mysql_num_rows($rs)>0){
                    $row=mysql_fetch_array($rs);
                        return $row['vDeptDesc'];
                }else{
                        return "";
                }
}
function getEmailsToNotifyKB($depid){
                global $conn;
                $sql = "SELECT vMail FROM sptbl_staffs s ";
                $sql .= "INNER JOIN  sptbl_staffdept sd ON s.nStaffId = sd.nStaffId  ";
                $sql .= " WHERE  nDeptId= '$depid' AND s.nNotifyKB= '1' ";
                $rs = executeSelect($sql,$conn);
                if(mysql_num_rows($rs)!=0){
                        while($row = mysql_fetch_array($rs)){
                                $emails .= ",".$row["vMail"];
                        }
                }else{
                        return "";
                }
                return substr($emails,1);
}
function makeDropDownList($ddlname,$list,$selectedindex, $class, $properties, $behaviors ){
        $ddl="";
        $ddl.="<select name=\"$ddlname\" class=\"$class\"";
        if(isNotNull($properties)){
                $ddl.= " \"$properties\"";
        }
        if(isNotNull($behaviors)){
                $ddl.= " $behaviors ";
        }
        $ddl.= " >";
        $ddl .="<option value=''";
    $ddl .="></option>\n";
        if(count($list)>0){
                foreach($list  as $key => $value){
                        $ddl .= "<option value=\"$key\"";
                        if($selectedindex == "$key"){
                                $ddl .=" selected=\"selected\"";
                        }
                $ddl .=">$value</option>\n";
                }
        }
        $ddl.="</select>";
        return $ddl;
}

function makeCategoryList($current_parentcat_id, $count,$deptid) {
         static $catlist;
                 if(!isNotNull($deptid)){
                         $deptid = 0;
                 }
         if (!isset($current_parentcat_id)) {
              $current_parentcat_id =0;
         }
         $count = $count+1;
         $sql = "SELECT nCatId as id, vCatDesc as name from sptbl_categories where nParentId = '$current_parentcat_id' and nDeptId=$deptid order by name asc";
         $get_options = mysql_query($sql);
         $num_options = mysql_num_rows($get_options);
         if ($num_options > 0)
         {
             while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) {
                    if ($current_parentcat_id !=0 ) {
                        $indent_flag = "&nbsp;&nbsp;";
                        for ($x=2; $x<=$count; $x++) {
                             $indent_flag .= "--&gt;&nbsp;";
                        }
                    }
                    $cat_name = $indent_flag.htmlentities($cat_name);
                                        $catlist[$cat_id] = $cat_name;
                    makeCategoryList($cat_id, $count,$deptid );
             }
         }
         return $catlist;
}
function replacestr($rpstr){
          $var_replymatter_arr=explode("\n",$rpstr);
              $new_matter="";
                  foreach($var_replymatter_arr as $key =>$value){
                           $value =">".$value;
                     if(strlen($value)>32){
                            $var_reply = wordwrap($value, 32, "\n>");
                             $new_matter .=$var_reply;
                         }else{
                           $new_matter .=$value;
                         }
                  }

  return $new_matter;
}
function replacestrforemail($rpstr){
          $var_replymatter_arr=explode("\n",$rpstr);
              $new_matter="";
                  foreach($var_replymatter_arr as $key =>$value){
                           $value =">".$value;
                    $new_matter .=$value;
            }
}
function getClientIP(){
    // Get REMOTE_ADDR as the Client IP.
    $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );

    // Check for headers used by proxy servers to send the Client IP. We should look for HTTP_CLIENT_IP before HTTP_X_FORWARDED_FOR.
    if ($_SERVER["HTTP_CLIENT_IP"])
        $proxy_ip = $_SERVER["HTTP_CLIENT_IP"];
    elseif ($_SERVER["HTTP_X_FORWARDED_FOR"])
        $proxy_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

    // Proxy is used, see if the specified Client IP is valid. Sometimes it's 10.x.x.x or 127.x.x.x... Just making sure.
    if ($proxy_ip)
    {
        if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $proxy_ip, $ip_list) )
        {
            $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10.\.*/', '/^224.\.*/', '/^240.\.*/');
            $client_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
        }
    }
    // Return the Client IP.
    return $client_ip;
}
function getPageAddress(){
	return basename($_SERVER["SCRIPT_FILENAME"])."?".$_SERVER["QUERY_STRING"];
}
function colorCode(){

     $r1=dechex(rand(0,15));
	 $r2=dechex(rand(0,15));
	 $g1=dechex(rand(0,15));
	 $g2=dechex(rand(0,15));
	 $b1=dechex(rand(0,15));
	 $b2=dechex(rand(0,15));
	 return "#".$r1.$r2.$g1.$g2.$b1.$b2;
   }

function colorCodelight(){

     $r1=dechex(rand(12,15));
	 $r2=dechex(rand(12,15));
	 $g1=dechex(rand(12,15));
	 $g2=dechex(rand(12,15));
	 $b1=dechex(rand(12,15));
	 $b2=dechex(rand(12,15));
	 return "#".$r1.$r2.$g1.$g2.$b1.$b2;
   }
function makeChildList($currid, $count) {
         static $childlist="";
                 if(!isNotNull($deptid)){
                         $deptid = 0;
                 }
         if (!isset($current_parentcat_id)) {
              $current_parentcat_id =0;
         }
         $count = $count+1;
		 $sql="select nDeptId as id,vDeptDesc as name from sptbl_depts where nDeptParent=$currid ";
         $get_options = mysql_query($sql);
         $num_options = mysql_num_rows($get_options);
         if ($num_options > 0)
         {
             while (list($child_id, $child_name) = mysql_fetch_row($get_options)) {
            		$childlist .= "" .$child_id . ",";
			        makeChildList($child_id, $count);
             }
         }

         return $childlist;
}
function isValidStatus($str){
    if ( preg_match ( "~[^0-9a-zA-Z+_]~i", $str ) ) {
    	return false;
    }else{
		return true;
    }
}
function isValidUsername($str){
    if ( preg_match ( "~[^0-9a-zA-Z+_]~i", $str ) ) {
    	return false;
    }else{
		return true;
    }
}
function isValidEmail($email) {
	if (!preg_match("~^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$~i", $email)) {
		return false;
	}
	return true;
}
function isValidFileName($str){
    if ( preg_match ( "~[^0-9a-zA-Z+_.' ]~i", $str ) ) {
    	return false;
    }else{
		return true;
    }
}
//New to Supportdesk1.0.9
//This takes in the response time,timestamp of surrent data-time, and time-of arival of ticket
//It calculates the difference in timestamp and ticket arrival time, ,then compares it with the
//response time of department and diplays it in blue if the time limit has not exceeded limit, else in red.
function getResponseTime($var_responseTime,$var_time,$timeOfArrival) {
	$var_reply = $var_responseTime - (($var_time - $timeOfArrival)/60);
	$var_reply = number_format(round(($var_reply),2),2);
	return (($var_reply <= 0)?"<font color='red'>" . $var_reply . " mts </font>":"<font color='blue'>" . $var_reply . " mts </font>");
}
//-------Auto return mail for new ticket---------------
function isAutoReturnMailNeeded(){
        global $conn;
        $sql = "SELECT vLookUpValue FROM sptbl_lookup  WHERE vLookUpName = 'NewTicketAutoReturnMail'";
        $rs = executeSelect($sql,$conn);
        if(mysql_num_rows($rs)!=0){
                $row = mysql_fetch_array($rs);
                if($row["vLookUpValue"] == "1"){
                        return true;
                }else{
                        return false;
                }
        }
        return true;
}
//-------Auto return mail for new ticket---------------
?>