<?php

$var_compid=$_SESSION["sess_usercompid"];
$var_userid=$_SESSION["sess_userid"];
if ($_GET["stylename"] != "") {
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
}
else {
    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
}	
if ($_POST["postback"] == "S") { //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
    $var_uploadfiles=$_POST['uploadfiles'];
    $var_title=$_POST['tckttitle'];
    $var_deptpid=$_POST['deptid'];
    $var_prty=$_POST['prty'];
    $var_desc=$_POST['tcktdesc'];

    //unlink the upload file
    $sql="select vAtt from sptbl_temp_tickets where nTpUserId=$var_userid and vStatus=0";
    $rs = executeSelect($sql,$conn);
    if(mysql_num_rows($rs)>0) {
        $row = mysql_fetch_array($rs);
        $vAttachmentfiles=$row['vAtt'];
        if($vAttachmentfiles !="") {
            $vAttacharr=explode("|",$vAttachmentfiles);
            foreach($vAttacharr as $key=>$value) {

                $split_name_url=explode("*",$value);
                @unlink("./attachments/".$split_name_url[0]);

            }
        }
        $sql="delete from sptbl_temp_tickets where nTpUserId=$var_userid and vStatus=0";
        executeQuery($sql,$conn);
    }
    //insert into temparary table
    $sql="insert into sptbl_temp_tickets(nTpTicketId,nTpUserId,nTDeptId,vTpTitle,tTpQuestion,vTpPriority,dTpPostDate,vAtt,vStatus)";
    $sql.=" values('','$var_userid','".mysql_real_escape_string($var_deptpid)."',";
    $sql .="'".mysql_real_escape_string($var_title)."',"."'".mysql_real_escape_string($var_desc)."',"."'".mysql_real_escape_string($var_prty)."',";
    $sql .="now(),'".mysql_real_escape_string($var_uploadfiles)."','0')";
    executeQuery($sql,$conn);
}	 


if($_GET["mt"] == "y") {
    $var_numBegin = $_GET["numBegin"];
    $var_start = $_GET["start"];
    $var_begin = $_GET["begin"];
    $var_num = $_GET["num"];
    $var_styleminus = $_GET["styleminus"];
    $var_stylename = $_GET["stylename"];
    $var_styleplus = $_GET["styleplus"];
    $var_title=$_GET['tckttitle'];
    $var_deptpid=$_GET['deptid'];
}
elseif($_POST["mt"] == "y") {
    $var_numBegin = $_POST["numBegin"];
    $var_start = $_POST["start"];
    $var_begin = $_POST["begin"];
    $var_num = $_POST["num"];
    $var_styleminus = $_POST["styleminus"];
    $var_stylename = $_POST["stylename"];
    $var_styleplus = $_POST["styleplus"];
    $var_title=$_POST['tckttitle'];
    $var_deptpid=$_POST['deptid'];
}

if($_POST["postback"] == "CN") {
    require("./includes/saveticket.php");
}else {
    if($var_deptpid=='') {
        //header('location:'.SiteURL.'tickets.php');
        //exit;
        echo '<script language="JavaScript" type="text/javascript">window.location.href="tickets.php?mt=y&tp=o&stylename=VIEWTICKETS&styleplus=oneplus&styleminus=oneminus&"</script>';
        exit;
    }
    $sql = "Select * from sptbl_kb,sptbl_categories  where match(vKBTitle,tKBDesc) against ('".mysql_real_escape_string($var_title)."')";
    $sql .=" and sptbl_kb.nCatId=sptbl_categories.nCatId and sptbl_categories.nDeptId=$var_deptpid" ;
    //echo "sql==$sql";
    // echo $sql;
    $totalrows = mysql_num_rows(executeSelect($sql,$conn));

    if($totalrows=="0") {
        require("./includes/saveticket.php");
    }else {
        require("./includes/showkb.php");
    }
}
?>
