<?php
     include_once("./includes/applicationheader.php");
     include_once("./includes/functions/miscfunctions.php");
   
	$q=$_GET['q'];
        
        $txtUserid = $_REQUEST['txtUserid'];

        $txtTemplate = $_REQUEST['txtTemplate'];

      // Auto Complete User

      if($q!=''){
	$my_data=mysql_real_escape_string($q);

	$sql = "select nUserId,vLogin,nCompId,vEmail,vUserName from sptbl_users where (vUserName LIKE '%$my_data%' || vLogin LIKE '%$my_data%') AND   vBanned='0' AND vDelStatus='0' ";
        $result =  executeSelect($sql,$conn);

	if(mysql_num_rows($result)>0)
	{
		while($row=mysql_fetch_array($result))
		{
			echo $row['nUserId']."~".$row['vUserName']. " (" . $row["vEmail"] . ")"."\n";
		}
	}
        else
            {
                echo "No Users Found with Matching Criteria";
          }
      }

      // Populate Department
      if($txtUserid!='')
          {
                    $leafdeptarr=getLeafDepts();
                     if($leafdeptarr !=""){
                    $leaflvldeptids=implode(",",$leafdeptarr);
                     }else{
                        $leaflvldeptids=0;
                     }

                         $sql = "select d.nDeptId,d.vDeptCode,d.vDeptDesc from sptbl_users u inner join sptbl_depts
                            d on u.nCompId = d.nCompId Where u.nUserId='$txtUserid' AND d.nDeptId IN($leaflvldeptids)";
                        $rs = mysql_query($sql) or die(mysql_error());
                            if(mysql_num_rows($rs)>0)
                                {
                                                     while($row = mysql_fetch_array($rs)) {
                                                         $options ="<option value='".$row['nDeptId']."'";
                                                          if ($var_deptid == $row['nDeptId']){
                                                                $options .=" selected=\"selected\"";
                                                              }
                                                          $options .=">[".htmlentities($row['vDeptCode'])."]&nbsp;".htmlentities($row['vDeptDesc'])."</option>\n";
                                                          echo $options;

                                                        
                                              }
                                }
                                else
                                    {
                                        echo '<option> No Result Found </option>';
                                    }
                                                                     
              }


  // Populate Temaplete Salutaion


           if($txtTemplate!='')
               {
                    $sql="select vTemplateTitle,tTemplateDesc from sptbl_templates where vStatus='1' and nTemplateId='".addslashes($txtTemplate)."'";
                     $result = executeSelect($sql,$conn);
                            if (mysql_num_rows($result) > 0) {
                                    $var_row = mysql_fetch_array($result);
                                    $var_templatedesc = $var_row["tTemplateDesc"];
                                    $var_templatetitle = $var_row["vTemplateTitle"];
                                    echo $var_replymatter = "------$var_templatetitle------\n".$var_templatedesc."\n\n\n".$var_replymatter;
                            }
               }


exit;
              
?>

