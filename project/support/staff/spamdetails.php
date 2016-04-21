<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                          |
// |                           |
// +----------------------------------------------------------------------+

        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");

        include("./languages/".$_SP_language."/spamdetails.php");
        $conn = getConnection();
          $flag_msg = "";
        if ($_POST["postback"] == "NS") {
            $spamticketid=$_POST['spamticketid'];
            $sql = "Select tQuestion,vTitle,tcontent,dPostDate from sptbl_spam_tickets where nSpamTicketId='".addslashes($spamticketid)."' ";

            $rs = executeSelect($sql,$conn);
            $row = mysql_fetch_array($rs);
			$dpostdate = $row['dPostDate'];
            $emailmarkedasspam=$row['tcontent'];
            $dotreal="../parser";
            $dotdotreal="..";
            require("../parser/spamparser_include.php");

			if($emailmarkedasspam != ""){
            	require("../parser/spamparser.php");
			    $var_message = TEXT_SPAM_REASSIGN;
                              $flag_msg = "class='msg_success'";
			}else
				$var_message = TEXT_SPAM_CANNOT_REASSIGN;
                          $flag_msg = "class='msg_error'";
            
			/*require("../spamfilter/spamfilterclass.php");
            $_REQUEST['cat']='notspam';
			$_REQUEST['docid']="notspamticket_".$row['nSpamTicketId'];
			$_REQUEST['document']=$row['tQuestion'].$row['vTitle'];
			train();*/



				//delete from spam
			if($emailmarkedasspam != ""){
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
<title><?php echo HEADING_SPAM_DETAILS ?></title>
<?php include("./includes/headsettings.php"); ?>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript">


  function spamdelete(){
document.frmSpamEmails.postback.value="D";
document.frmSpamEmails.submit();
}
function notspam() {
 document.frmSpamEmails.postback.value="NS";
 document.frmSpamEmails.submit();
}
function goback1(){

  document.frmSpamEmails.postback.value="GB";
 document.frmSpamEmails.submit();
}

</script>
</head>

<body  bgcolor="#EDEBEB">
<!--  Top Part  -->
<?php
        include("./includes/top.php");
?>
<!--  Top Ends  -->

        <!--  Top links  -->

        <?php
//                 include("./includes/toplinks.php");
         ?>

        <!--  End Top Links -->

        <!-- header  -->
    <?php
                include("./includes/header.php");
        ?>
        <!-- end header -->
         
                     <!-- sidelinks -->
					  <div class="content_column_small">

                          <?php
                                include("./includes/staffside.php");
                        ?>

</div>
                   <!-- End of side links -->
         <div class="content_column_big">  
				<!-- admin header -->
				<?php
						//include("./includes/staffheader.php");
				?>
				<!--  end admin header -->
                <!-- Personal notes Section -->

                  <?php
                          include("./includes/spamdetails.php");
                  ?>
</div>
                  <!-- End Personal notes Section  -->




          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
    
</body>
</html>