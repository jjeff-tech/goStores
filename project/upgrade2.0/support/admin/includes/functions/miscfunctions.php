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
                mail($EMail,$subject,$mailBody,$Headers);

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
                       .$queryStr."' class=listing>"
                       .$rarrow."</a>\n";
                        $wholePiece .= $piece;
                        break;
                }
                $piece = "<a href='?begin=".$start
                        ."&num=".$i
                        ."&numBegin=".$numBegin
                        .$queryStr
                        ."' class=listing>";
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
        if($cycle == 1)
            $wholePiece = '';
        $wheBeg = $begin+1;
        $wheEnd = $begin+$amm;
        $wheToWhe = "<b>".$wheBeg."</b> - <b>";
        if ($totalrows <= $wheEnd) {
                $wheToWhe .= $totalrows."</b>";
        } else {
                $wheToWhe .= $wheEnd."</b>";
        }
        if($begin<0) $begin = 0;
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
                            $day=$split_date[1];
                            $mnth=$split_date[0];
                            $year=$split_date[2];
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
function datetimefrommysql($vdate,$mode=''){
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
           if($mode != '')
           return $mnth."-".$day."-".$year." ".$hour.":".$minute.":".$second;
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
			$filename1	=	time().$_FILES[$fname]['name'];
			$blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
			foreach ($blacklist as $file)
			{
				if(preg_match("/\.$file\$/i", "$filename1"))
				{
				   return "IT";
				}
			}
////////
	        if(!isValidFileName(time().$_FILES[$fname]['name'])){
				 return "IF";
			}
	        $size = $_FILES[$fname]['size'];
	        $altype="";
			$alsize=0;
		    $sql = "Select * from sptbl_lookup where vLookUpName IN('Attachments','MaxfileSize')";
			$result = executeSelect($sql,$conn);
			if(mysql_num_rows($result) > 0) {
				while($row = mysql_fetch_array($result)) {
				    switch($row["vLookUpName"]) {
			          case "Attachments":
									$var_attach_typearr =explode("|",$row["vLookUpValue"]);
					                $altype=$altype.$var_attach_typearr[1].",";
									$atype_extension=$atype_extension.$var_attach_typearr[0].",";
									break;
					   case "MaxfileSize":
									$alsize = $row["vLookUpValue"];
									break;
			       }

				}
			}
		    mysql_free_result($result);
		    $altype = substr($altype,0,-1);

			if ($size >$alsize or $size <=0){
	     				return "IS";
	        }


	   if($atype !="all"){
			  $allowetypearray=explode(",",$altype);
			  $allowetype_extn_array=explode(",",$atype_extension);
			  $file_type=$_FILES[$fname]['type'];
			  $file_type_extension=substr($_FILES[$fname]['name'],strrpos($_FILES[$fname]['name'],".")+1);
			  $allowed_flag=0;
			  $allowedextn_flag=0;
			  foreach($allowetypearray as $key=>$value){
				  if(strcasecmp($file_type,$value)==0){
					$allowed_flag=1;
					break;
				  }

			  }
			   foreach($allowetype_extn_array as $key=>$value){
				  if(strcasecmp($file_type_extension,$value)==0){
					$allowedextn_flag=1;
					break;
				  }
			  }
			 if($allowed_flag=="0" or $allowedextn_flag=="0" ){
			   return "IT";
			 }
	   }

	   if($ufilename==""){
	     $ufilename=time().$_FILES[$fname]['name'];
	   }
	   $file_name=$upath.$ufilename;

	   if(is_file($file_name)){
	        return "FE";
	   }

	   if(substr(trim($ufilename),0,1) == ".") {
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

function upload_logo($fname,$upath,$ufilename,$atype,$alsize,$logoURL=""){
    global $conn;
    if (is_uploaded_file($_FILES[$fname]['tmp_name'])){
///////// to prevent executable file uploading
		$filename1	=	$_FILES[$fname]['name'];
		$tempname	=	$_FILES[$fname]['tmp_name'];

		$blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
		foreach ($blacklist as $file)
		{
			if(preg_match("/\.$file\$/i", "$filename1"))
			{
			   return "IT";
			}
		}
		if (!getimagesize($tempname))
		{
			return "IT";
		}
////////

		if( ! isValidFileName($_FILES[$fname]['name'])){
			 return "IF";
		}
		$size = $_FILES[$fname]['size'];
		//$atype="";
		$alsize=0;
		$sql = "Select * from sptbl_lookup where vLookUpName IN('Attachments','MaxfileSize')";
		$result = executeSelect($sql,$conn);
		if(mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				switch($row["vLookUpName"]) {
				  case "Attachments":
						//$var_attach_typearr =explode("|",$row["vLookUpValue"]);
						//$atype=$atype.$var_attach_typearr[1].",";
						//$atype_extension=$atype_extension.$var_attach_typearr[0].",";
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
				  if(strcasecmp($file_type,$value)==0){
					$allowed_flag=1;
					break;
				  }

			  }
                          $allowedextn_flag = 1;
			/*   foreach($allowetype_extn_array as $key=>$value){
				  if(strcasecmp($file_type_extension,$value)==0){

					$allowedextn_flag=1;
					break;
				  }
			  }*/
			 if($allowed_flag=="0" or $allowedextn_flag=="0" ){
			   return "IT";
			 }
	   }

	   if($ufilename==""){
	     	$ufilename=$_FILES[$fname]['name'];
	   }
	   $file_name=$upath.$ufilename;

	  if(substr(trim($ufilename),0,1) == ".") {
	   		return "IF";
	  }

	  if(file_exists("../".$logoURL)){  //modified to upload file with the same filename by roshith on 6-11-06
		  unlink("../".$logoURL);
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
//        $ddl .="<option value=''";
//    $ddl .="></option>\n";
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
    if ( preg_match ( "[^0-9a-zA-Z+_]", $str ) ) {
    	return false;
    }else{
		return true;
    }
}
function isValidUsername($str){
    if ( preg_match ( "[^0-9a-zA-Z+_]", $str ) ) {
    	return false;
    }else{
		return true;
    }
}
function isValidEmail($email) {
	if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
		return false;
	}
	return true;
}
//this is to check uniqueness of user
function isUniqueEmailUser($email,$var_id=0,$var_compid=0) {
	global $conn;
	$var_str = "";
	if($var_compid != 0) {
		$var_str .= " AND u.nCompId = '{$var_compid}'";
	}
	if($var_id != 0) {
		$var_str .= " AND u.nUserId != '" . addslashes($var_id) . "'";
	}
	$sql = "Select * from dummy d
		Left join sptbl_users u on (d.num=0 AND  u.vDelStatus = '0' and u.vEmail='" . addslashes($email) . "'{$var_str})
		Left JOIN sptbl_staffs s on (d.num=1 AND s.vMail='" . addslashes($email) . "')
		Left join sptbl_depts dt on (d.num=2 AND dt.vDeptMail='" . addslashes($email) . "')
		Left join sptbl_companies c on(d.num=3 AND c.vCompMail='" . addslashes($email) . "')
		where d.num < 4  AND (u.nUserId IS NOT NULL OR s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
//	echo $sql."<br><br>";
	if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
		return false;
	}
	else {
		$sql = "Select nLookUpId from sptbl_lookup where vLookUpValue='" . addslashes($email) . "'
		AND vLookUpName IN('MailAdmin','MailTechnical','MailEscalation','MailFromMail','MailReplyMail')";
		if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			return false;
		}
		else {
			return true;
		}
	}
}


//this is to check uniqueness of other categories like staff,company,department.
function isUniqueEmail($email,$var_id=0,$var_type="") {
	global $conn;
	if($var_id != 0) {
		switch($var_type) {
			case "u":
				$var_str_user = " AND u.nUserId != '" . addslashes($var_id) . "'";
				break;
			case "s":
				$var_str_staff = " AND s.nStaffId != '" . addslashes($var_id) . "'";
				break;
			case "d":
				$var_str_dept = " AND dt.nDeptId != '" . addslashes($var_id) . "'";
				break;
			case "c":
				$var_str_comp = " AND c.nCompId != '" . addslashes($var_id) . "'";
				break;
		}
	}
	$sql = "Select * from dummy d
		Left join sptbl_users u on (d.num=0 AND u.vEmail='" . addslashes($email) . "'{$var_str_user})
		Left JOIN sptbl_staffs s on (d.num=1 AND s.vMail='" . addslashes($email) . "'{$var_str_staff})
		Left join sptbl_depts dt on (d.num=2 AND dt.vDeptMail='" . addslashes($email) . "'{$var_str_dept})
		Left join sptbl_companies c on(d.num=3 AND c.vCompMail='" . addslashes($email) . "'{$var_str_comp})
		where d.num < 4  AND (u.nUserId IS NOT NULL OR s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
	if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
		return false;
	}
	else {
		$sql = "Select nLookUpId from sptbl_lookup where vLookUpValue='" . addslashes($email) . "'
		AND vLookUpName IN('MailAdmin','MailTechnical','MailEscalation','MailFromMail','MailReplyMail')";
		if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
			return false;
		}
		else {
			return true;
		}
	}
}

function isUniqueEmail1($email,$var_id=0,$var_type="") {
	global $conn;
	if($var_id != 0) {
		switch($var_type) {
			case "u":
				$var_str_user = " AND u.nUserId != '" . addslashes($var_id) . "'";
				break;
			case "s":
				$var_str_staff = " AND s.nStaffId != '" . addslashes($var_id) . "'";
				break;
			case "d":
				$var_str_dept = " AND dt.nDeptId != '" . addslashes($var_id) . "'";
				break;
			case "c":
				$var_str_comp = " AND c.nCompId != '" . addslashes($var_id) . "'";
				break;
		}
	}
	$sql = "Select * from dummy d
		Left join sptbl_users u on (d.num=0 AND u.vEmail='" . addslashes($email) . "'{$var_str_user})
		Left JOIN sptbl_staffs s on (d.num=1 AND s.vMail='" . addslashes($email) . "'{$var_str_staff})
		Left join sptbl_depts dt on (d.num=2 AND dt.vDeptMail='" . addslashes($email) . "'{$var_str_dept})
		Left join sptbl_companies c on(d.num=3 AND c.vCompMail='" . addslashes($email) . "'{$var_str_comp})
		where d.num < 4  AND (u.nUserId IS NOT NULL OR s.nStaffId IS NOT NULL OR dt.nDeptId IS NOT NULL OR c.nCompId IS NOT NULL)";
	if(mysql_num_rows(executeSelect($sql,$conn)) > 0) {
		return false;
	}
	return true;
}

function isValidFileName($str){
   // if ( eregi ( "[^0-9a-zA-Z+_.' ]", $str ) ) {
     if (preg_match( "/^[^\\/?*:;{}\\\\]+\\.[^\\/?*:;{}\\\\]{3}$/", $str ) ) {
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
	$var_reply = round(($var_reply),2);
	return (($var_reply <= 0)?"<font color='red'>" . displayReadable($var_reply) . " </font>":"<font color='blue'>" . displayReadable($var_reply) . " </font>");
}
function displayReadable($mts){
		$return = "";
		if($mts < 0) {
			//$final = "-";
			$final = "";
			$mts = -1 * $mts;
		}
		else{
			//$final = "";
			$final = "";
		}
		$hrs = (int) ($mts/60);
		$mts = $mts % 60;
		if($hrs >= 24) {
			$days = (int) ($hrs/24);
			$hrs = $hrs % 24;
		}
		$return = ($hrs > 0)?(($days > 0)?($days . " d," . $hrs . "h"):$hrs . " h"):(($days > 0)?($days . " d"):"");
		$return .= ((strlen($return) > 0))?(($mts > 0)?("," . $mts . " m"):""):$mts . " m";
		return ($return . $final);
}
//This is the function deleteChecked that is modified in admin section
//to discard the deptid checking and owner checking
//enabling admin to delete any ticket owned by any user
//belonging to any department in any company
function deleteChecked($var_list,&$message,$check=true) {
	global $var_staffid,$conn,$lst_dept,$flag_del;
	$var_new_list = "";
	if($var_list != "") {
		$sql = "Select distinct nTicketId from sptbl_tickets where nTicketId IN($var_list)";
		$result = executeSelect($sql,$conn);
		if(mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result)) {
				$var_new_list .= $row["nTicketId"] . ",";
			}
		}
		$var_new_list = substr($var_new_list,0,-1);
		if($var_new_list != "") {
			$var_reply_list = "";
			$sql = "Select distinct nReplyId from sptbl_replies Where nTicketId IN($var_new_list)";
			$result = executeSelect($sql,$conn);
			if(mysql_num_rows($result) > 0) {
				while($row = mysql_fetch_array($result)) {
					$var_reply_list .= $row["nReplyId"] . ",";
				}
			}
			$var_reply_list = substr($var_reply_list,0,-1);
			$var_attach_list = "";
			$sql  = "Select nAttachId,vAttachUrl from sptbl_attachments where nTicketId IN($var_new_list)";
			if($var_reply_list != "") {
				$sql .= " OR nReplyId IN($var_reply_list)";
			}
			$result = executeSelect($sql,$conn);
			if(mysql_num_rows($result) > 0) {
				while($row = mysql_fetch_array($result)) {
					$var_attach_list .= $row["nAttachId"] . ",";
					@unlink("../attachments/". $row["vAttachUrl"]);
				}
			}
			//Delete attachments for the selected tickets
			if($var_attach_list != "") {
				$var_attach_list = substr($var_attach_list,0,-1);
				$sql = "Delete from sptbl_attachments Where nAttachId IN($var_attach_list)";
				executeQuery($sql,$conn);
			}
			//Delete from personalnotes
			$sql = "Delete from sptbl_personalnotes where nTicketId IN($var_new_list)";
			executeQuery($sql,$conn);

			//Delete from feedback
			$sql = "Delete from sptbl_feedback where nTicketId IN($var_new_list)";
			executeQuery($sql,$conn);

			//Delete from replies
			$sql = "Delete from sptbl_replies where nTicketId IN($var_new_list)";
			executeQuery($sql,$conn);

			//Delete from tickets
			$sql = "Delete from sptbl_tickets where nTicketId IN($var_new_list)";
			executeQuery($sql,$conn);

			$arr_new = explode(",",$var_new_list);
			$message = "<font color=\"red\">" . count($arr_new) .  " ticket/s deleted successfully.</font><br>";
                        $flag_del = true;

			if($var_list != $var_new_list) {
				$arr_original = explode(",",$var_list);
				$arr_calc = array_diff($arr_original,$arr_new);
				$message .= "<font color=\"red\">" . count($arr_calc) . " of the selected ticket/s cannot be deleted!.</font>";
                                $flag_del = false;
			}
		}
		else {
			$arr_original = explode(",",$var_list);
			$message .= "<font color=\"red\">" . count($arr_original) . " of the selected ticket/s cannot be deleted!.</font>";
                        $flag_del = false;
		}
	}
}
function isValidCredentials($var_userid,$deptid,$priority) {
	global $conn;
	$sql = "Select nUserId from sptbl_users where nUserId='$var_userid' AND vBanned='0' AND vDelStatus='0'";
	if(mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
		return false;
	}
	$sql = "Select nDeptId from sptbl_depts where nDeptId='$deptid'";
	if(mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
		return false;
	}
	$sql = "Select nPriorityValue from sptbl_priorities where nPriorityValue='$priority'";
	if(mysql_num_rows(executeSelect($sql,$conn)) <= 0) {
		return false;
	}
	return true;
}

// boolToNum function converts boolean values to numeric (1/0)
function boolToNum($bVal) {
    return (($bVal==true) ? 1 : 0);
}

function secondsToTime($seconds)
{
    // extract hours
    $hours = floor($seconds / (60 * 60));
 
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);
 
    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);
 
    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );
    return $obj;
}

function sendMailUserTicketClose($mail_refno)
{
global $conn;
    $sql = " Select * from sptbl_lookup where vLookUpName IN('MailFromName','MailFromMail',";
	    $sql .="'MailReplyName','MailReplyMail','Emailfooter','Emailheader','MailEscalation','HelpdeskTitle')";
			$result = executeSelect($sql,$conn);
			if(mysql_num_rows($result) > 0) {
					while($row2 = mysql_fetch_array($result)) {
							switch($row2["vLookUpName"]) {
									case "MailFromName":
													$var_fromName = $row2["vLookUpValue"];
													break;
									case "MailFromMail":
													$var_fromMail = $row2["vLookUpValue"];
													break;
									case "MailReplyName":
													$var_replyName = $row2["vLookUpValue"];
													break;
									case "MailReplyMail":
													$var_replyMail = $row2["vLookUpValue"];
													break;
									case "Emailfooter":
													$var_emailfooter = $row2["vLookUpValue"];
													break;
									case "Emailheader":
													$var_emailheader = $row2["vLookUpValue"];
													break;
									case "MailEscalation":
													$var_emailescalation = $row2["vLookUpValue"];
													break;
									case "HelpdeskTitle":
													$var_helpdesktitle = $row2["vLookUpValue"];
													break;
							}
					}
			}

    $sql = "Select u.nUserId, u.vUserName, u.vEmail, t.nTicketId   from sptbl_tickets t INNER JOIN sptbl_users u ON t.nUserId = u.nUserId WHERE t.vRefNo = '" . mysql_real_escape_string(trim($mail_refno)) . "' ORDER BY t.nTicketId  DESC LIMIT 1";

    $result_user = executeSelect($sql,$conn);
    if(mysql_num_rows($result_user) > 0) {
        $row_user = mysql_fetch_array($result_user);
        $toemail = $row_user['vEmail'];
        $var_body = $var_emailheader."<br>".TEXT_MAIL_START."&nbsp; ". $row_user['vUserName'] .",<br>";
        $var_body .= TEXT_CLOSED_BODY ." ". $mail_refno . TEXT_MAIL_BY . htmlentities($_SESSION['sess_staffname']) ."<br><br>";

       // $sql_reply    =   "SELECT nReplyId FROM sptbl_replies WHERE nTicketId='".$row_user['nTicketId']."'";
        $sql_reply    =   "SELECT vStaffLogin  FROM  sptbl_tickets WHERE nTicketId='".$row_user['nTicketId']."' AND ( vStaffLogin  !='NULL' OR  	vStaffLogin  !='' OR  	vStaffLogin  !='0')";

       $res_reply = executeSelect($sql_reply,$conn);
       if(mysql_num_rows($res_reply) > 0) {
           $row_staff = mysql_fetch_array($res_reply);
           if($row_staff['vStaffLogin'] != "")
               $var_body .= TEXT_RATE_URL_MSG1 ."  <a href='".SITE_URL."rating.php?uid=".$row_user['nUserId']."&ticket_id=".$row_user['nTicketId']."'> ". TEXT_RATE_URL_MSG2 ." </a>  ". TEXT_RATE_URL_MSG3 ." <br><br>";
       }
        $var_body .= TEXT_MAIL_THANK. "<br>" . htmlentities($var_helpdesktitle)  . "<br>" . $var_emailfooter;
        $var_subject = TEXT_CLOSED_SUB ." ". $mail_refno;
        $Headers="From: $var_fromName <$var_fromMail>\n";
        $Headers.="Reply-To: $var_replyName <$var_replyMail>\n";
        $Headers.="MIME-Version: 1.0\n";
        $Headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
       //  echo $var_body;exit;
        // it is for smtp mail sending

         $useremail = getUserEmail($row_user['nUserId']);//get user email
        if (!in_array($toemail,$useremail)) {
            $useremail[] = $toemail;
        }

        if(count($useremail) > 0) {
            foreach ($useremail as $key => $value) {
                $toemail_us = $value;

        if($_SESSION["sess_smtpsettings"] == 1) {
            $var_smtpserver = $_SESSION["sess_smtpserver"];
            $var_port = $_SESSION["sess_smtpport"];

            SMTPMail($var_fromMail,$toemail_us,$var_smtpserver,$var_port,$var_subject,$var_body);
        }
        else
            $mailstatus=@mail($toemail_us,$var_subject,$var_body,$Headers);

        
            }//end of for loop user email
        }//end of if email count
    }
}
function upload_ticket_prior_logo($fname,$upath,$ufilename,$atype,$alsize,$logoURL=""){
    global $conn;
    if (is_uploaded_file($_FILES[$fname]['tmp_name'])){
///////// to prevent executable file uploading
		$filename1	=	time().$_FILES[$fname]['name'];
		$tempname	=	$_FILES[$fname]['tmp_name'];

		$blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
		foreach ($blacklist as $file)
		{
			if(preg_match("/\.$file\$/i", "$filename1"))
			{
			   return "IT";
			}
		}
		if (!getimagesize($tempname))
		{
			return "IT";
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
				  if(strcasecmp($file_type,$value)==0){
					$allowed_flag=1;
					break;
				  }

			  }
			   foreach($allowetype_extn_array as $key=>$value){
				  if(strcasecmp($file_type_extension,$value)==0){

					$allowedextn_flag=1;
					break;
				  }
			  }
			 if($allowed_flag=="0" or $allowedextn_flag=="0" ){
			   return "IT";
			 }
	   }

	   if($ufilename==""){
	     	$ufilename= time().$_FILES[$fname]['name'];
	   }
	   $file_name=$upath.$ufilename;

	  if(substr(trim($ufilename),0,1) == ".") {
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

function getCSSList(){
	global $conn;
	$sql = "SELECT nCSSId, vCSSName FROM sptbl_css ";
	$result = executeSelect($sql,$conn);
	if (mysql_num_rows($result) > 0) {
		while(list($cid,$cname) = mysql_fetch_array($result)){
			$list[$cid] = htmlentities($cname);
		}
	}
	return $list;
}

//get user emailas 
function getUserEmail($userid){
        global $conn;
        $useremail = array();
       $sql = "Select DISTINCT vEmail from sptbl_useremail u ";
                            $sql .=" where u.nUserId ='".addslashes($userid)."' AND u.vStatus = 'Y'";
                            $result = executeSelect($sql,$conn);
                            if (mysql_num_rows($result) > 0) {
                            while($row = mysql_fetch_array($result)){
                                $useremail[] = $row['vEmail'];;
                            }
                            }
        return $useremail;
}


function dateDifference($date1, $date2)
{
$date1=strtotime($date1);
$date2=strtotime($date2);
$diff = abs($date1 - $date2);
$day = $diff/(60*60*24); // in day
$dayFix = floor($day);
$dayPen = $day - $dayFix;

if($dayPen > 0)
{
$hour = $dayPen*(24); // in hour (1 day = 24 hour)
$hourFix = floor($hour);
$hourPen = $hour - $hourFix;
if($hourPen > 0)
{
$min = $hourPen*(60); // in hour (1 hour = 60 min)
$minFix = floor($min);
$minPen = $min - $minFix;
if($minPen > 0)
{
$sec = $minPen*(60); // in sec (1 min = 60 sec)
$secFix = floor($sec);
}
}
}
$str = "";
$strArr = array();
$strArr['day'] = '';
$strArr['hour'] = '';
$strArr['min'] = '';
$strArr['sec'] = '';
if($dayFix > 0) {
$str.= $dayFix." day ";
$strArr['day'] = $dayFix;
}
if($hourFix > 0) {
$str.= $hourFix." hour ";
$strArr['hour'] = $hourFix;
}
if($minFix > 0) {
$str.= $minFix." min ";
$strArr['min'] = $minFix;
}
if($secFix > 0) {
$str.= $secFix." sec ";
$strArr['sec'] = $secFix;

}


return $strArr;
}


function doConvertToMin($dataArr){
    $min = 0;
    if(!empty($dataArr)){
        if(!empty($dataArr['day'])) {
            $min += $dataArr['day']*24*60;
        }
        if(!empty($dataArr['hour'])) {
            $min += $dataArr['hour']*60;
        }
        if(!empty($dataArr['min'])) {
            $min += $dataArr['min'];
        }
        if(!empty($dataArr['sec'])) {
            $min += $dataArr['sec']/10;
        }

    }
    return floor($min);

} // End Function
function dateFormat($input_date, $input_format, $output_format)
{

    @preg_match("/^([\w]*)/i", $input_date, $regs);
    $sep = @substr($input_date, strlen($regs[0]), 1);
    $label = @explode($sep, $input_format);
    $value = @explode($sep, $input_date);
    $array_date = @array_combine($label, $value);
    if (@in_array('Y', $label)) {
        $year = $array_date['Y'];
    } elseif (@in_array('y', $label)) {
        $year = $year = $array_date['y'];
    } else {
        return false;
    }

    $output_date = @date($output_format, @mktime(0, 0, 0, $array_date['m'], $array_date['d'], $year));
    return $output_date;
}

?>