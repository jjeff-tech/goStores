<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                            |
// |                                                                                                            |
// +----------------------------------------------------------------------+


        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        include("./languages/".$_SP_language."/spamdetails.php");
        $conn = getConnection();
        if ($_POST["postback"] == "NS") {
           $sqlFilter = "Select * from sptbl_lookup where vLookUpName ='spamfiltertype'";
		   $resultFilter = executeSelect($sqlFilter,$conn);
		   $rowFilterType = mysql_fetch_array($resultFilter);
		   $filtertype=$rowFilterType['vLookUpValue'];


            $spamticketid=$_POST['spamticketid'];
            $sql = "Select nSpamTicketId,tQuestion,vTitle,tcontent,dPostDate from sptbl_spam_tickets where nSpamTicketId='".addslashes($spamticketid)."' ";
            $rs = executeSelect($sql,$conn);
            $row = mysql_fetch_array($rs);
			$dpostdate = $row['dPostDate'];
            //$emailmarkedasspam=$row['tcontent'];
            $emailmarkedasspam=$row['nSpamTicketId'];
            $dotreal="../parser";
            $dotdotreal="..";
            require("../parser/spamparser_include.php");

			if($emailmarkedasspam != ""){
            	require("../parser/spamparser.php");
			    $var_message = TEXT_SPAM_REASSIGN;
			}else
				$var_message = TEXT_SPAM_CANNOT_REASSIGN;


                 require("../spamfilter/spamfilterclass.php");
                 $_REQUEST['cat']='notspam';
			     $_REQUEST['docid']="notspamticket_".time().$row['nSpamTicketId'];

			     if($filtertype=="SUBJECT"){
                      $_REQUEST['document']=$row['vTitle'];
				 }else if($filtertype=="BODY"){
                      $_REQUEST['document']= $row['tQuestion'];
				 }else if($filtertype=="BOTH"){
                      $_REQUEST['document']=$row['vTitle'] ." ".$row_spam['tQuestion'];
				 }
			     train();


			if($emailmarkedasspam != ""){
        $sql = "Update sptbl_tickets join sptbl_spam_tickets on sptbl_tickets.nTicketId = sptbl_spam_tickets.nTicketID and sptbl_spam_tickets.nSpamTicketId='".addslashes($spamticketid)."' set vDelStatus = 0";
        $result = executeQuery($sql,$conn);
				//delete from spam
				$sql = "delete from sptbl_spam_tickets where nSpamTicketId='".addslashes($spamticketid)."' ";
				mysql_query($sql);
				$loc=$_SESSION["sess_spamticketbackurl"];
				header("location:$loc");
				exit;
			}

        }elseif ($_POST["postback"] == "D") {
            $spamticketid=$_POST['spamticketid'];
            $sql = "delete from sptbl_spam_tickets where nSpamTicketId='".addslashes($spamticketid)."' ";
            mysql_query($sql);
            $loc=$_SESSION["sess_spamticketbackurl"];
            header("location:$loc");
            exit;
        }elseif ($_POST["postback"] == "GB") {

            $loc=$_SESSION["sess_spamticketbackurl"];
            header("location:$loc");
            exit;
        }

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo HEADING_SPAM_DETAILS?></title>
<?php include("./includes/headsettings.php"); ?>
<script>

function spamdelete(){
document.frmSpamEmails.postback.value="D";
frmSpamEmails.submit();
}
function notspam() {
 document.frmSpamEmails.postback.value="NS";
 frmSpamEmails.submit();
}
function goback(){
  document.frmSpamEmails.postback.value="GB";
 frmSpamEmails.submit();
}
</script>
</head>

<body bgcolor="#EDEBEB">
<!--  Top Part  -->
<?php
        include("./includes/top.php");
?>
<!--  Top Ends  -->
        <!-- header  -->
    <?php
                include("./includes/header.php");
        ?>
        <!-- end header -->

      		<div class="content_column_small">
  
                          <!-- sidelinks -->

                          <?php
                                include("./includes/adminside.php");
                        ?>
</div>
                   <!-- End of side links -->
        
				<!-- admin header -->
				<?php
						//include("./includes/adminheader.php");
				?>
				<!--  end admin header -->
                <!-- Detail Section -->
						<div class="content_column_big">
                <?php
                          include("./includes/spamdetails.php");
                ?>
				</div>
	            <!-- End Detail section -->
       
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
  
</body>
</html>