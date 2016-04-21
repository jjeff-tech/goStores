<?php
     include_once("./includes/applicationheader.php");
     include_once("./includes/functions/miscfunctions.php");


	$sql = "Select * from sptbl_depts where nDeptParent='0' ";
        $result =  executeSelect($sql,$conn);

	if(mysql_num_rows($result)>0)
        {
            while($row = mysql_fetch_array($result)) {
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
            echo '<option> No Departments Found </option>';
        }
      

      
              
?>

