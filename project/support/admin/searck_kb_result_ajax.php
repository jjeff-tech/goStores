<?php
     include_once("./includes/applicationheader.php");
     include_once("./includes/functions/miscfunctions.php");
    include("./languages/".$_SP_language."/replies.php");
	$q=$_GET['q'];
        if($_POST['txtKbSearchid']){
        $txtKbSearchid = $_POST['txtKbSearchid'];
        }
        
       // $txtUserid = $_REQUEST['txtUserid'];

       // $txtTemplate = $_REQUEST['txtTemplate'];

      // Auto Complete KB Title

      if($q!=''){
	$my_data=mysql_real_escape_string($q);

	$sql = "select nKBID,vKBTitle from sptbl_kb where (vKBTitle LIKE '%$my_data%') AND   vStatus ='A'";
        $result =  executeSelect($sql,$conn);

	if(mysql_num_rows($result)>0)
	{
		while($row=mysql_fetch_array($result))
		{
			echo $row['nKBID']."~".$row['vKBTitle']."\n";
		}
	}
        else
            {
                echo "No result found";
                exit;
          }
      }

      // Search KB 
      if($txtKbSearchid!='')
          {
                    $my_data=mysql_real_escape_string($txtKbSearchid);

	 $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (nKBID = '$my_data') AND   vStatus ='A'";
        $result =  executeSelect($sql,$conn);
        ?>
<div class="content_section_title"><h4><?php echo TEXT_KB_SERACH_RESULT;?></h4></div>
<?php
	if(mysql_num_rows($result)>0)
	{
		$row=mysql_fetch_array($result);
                echo $row['tKBDesc'];
                exit;
		
	}
       
      }
       
exit;
              
?>

