<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>  		                          |
// |          									                          |
// +----------------------------------------------------------------------+
        require_once("./includes/applicationheader.php");
        include("./includes/functions/miscfunctions.php");
        $page = 'home';
	include("./languages/".$_SP_language."/main.php");
// code added by roshith on 22-11-06 for private message alert when login
		if($_SESSION["sess_login_flag"] == 1){   // alert only once. ie, in login time only
			//include("./includes/pvtmsgalert.php");
			$_SESSION["sess_login_flag"] = 0;
		}
		
		if($_POST["post_back"] == "CL"){
				$_SESSION["sess_language"] = $_POST["cmbLan"];
				$_SESSION["sess_stafflangchange"] ="1";
				header("location:staffmain.php");
				exit;
		}

        include("./languages/".$_SP_language."/staffmain.php");
        $conn = getConnection();

                $sql = "Select vLookUpName,vLookUpValue from sptbl_lookup WHERE vLookUpName IN('LangChoice',
                                'DefaultLang','HelpdeskTitle','Logourl')";
                $rs = executeSelect($sql,$conn);
                if (mysql_num_rows($rs) > 0) {
                        while($row = mysql_fetch_array($rs)){
                                switch($row["vLookUpName"]) {
                                        case "LangChoice":
                                                        $_SESSION["sess_langchoice"] = $row["vLookUpValue"];
                                                        break;
                                        case "DefaultLang":
                                                        $_SESSION["sess_defaultlang"] = $row["vLookUpValue"];
                                                        break;
                                        case "HelpdeskTitle":
                                                        $_SESSION["sess_helpdesktitle"] = $row["vLookUpValue"];
                                                        break;
                                        case "Logourl":
                                                        $_SESSION["sess_logourl"] = $row["vLookUpValue"];
                                                        break;
                                }
                        }
                }
                mysql_free_result($rs);

                /*//$_SESSION["sess_language"] = "en";
                if ($_SESSION["sess_defaultlang"] != "en") {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        header("location:index.php");
                        exit;
                }*/

				if($_SESSION["sess_stafflangchange"] =="1"){
				   ;
				}else{

				    if (($_SESSION["sess_defaultlang"] !=$_SESSION["sess_language"])) {
                        $_SESSION["sess_language"] = $_SESSION["sess_defaultlang"];
                        echo("<script>window.location.href='staffmain.php'</script>");
						exit();

						//header("location:index.php");
                        //exit;
                   }
			 }
				//echo("here");
?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_STAFF_MAIN ?></title>
<?php include("./includes/headsettings.php"); ?>

<!-- Tooltip -->

<link type="text/css" rel="stylesheet" media="screen" href="./../scripts/tooltip/jquery.qtip.css" />
<script type="text/javascript" src="./../scripts/tooltip/jquery.qtip-1.0.0-rc3.js"></script>

<script type="text/javascript">
$(document).ready(function(){

    $('.tooltip').each(function()
    {

       var string = $(this).attr("id");
       var newarray = string.split('x');
       var tkid = newarray[0];
       var usid = newarray[1];
       $(this).qtip(
       {

          content: {
             text: 'Loading...',
             url: 'ajax.php?act=ticketdetails&id='+ tkid,
             data: ''
            },
          position: {
             corner: {
                target: 'rightBottom',
                tooltip: 'topLeft'
             }
          },
          style: {
             tip: true, // No need to specify the corner explicitly if you want it to default to the tooltip corner
             name: 'defaults'
          }
       });

    });

});
</script>

<!-- Tooltip -->

<style>
    .imgFollow{ cursor: pointer;}
</style>

<script language="javascript" type="text/javascript">
    /*  For Ticket Follow   */
    $(document).ready(function(){

        $('img.imgFollow').each(function(){

            var id  =   $(this).attr('id');

            var dataString = {"ticketId":id};

            $.ajax({
            url	        :"ajax_response.php",
            type	:"post",
            data	:dataString,
            dataType 	:"json",
            success	:function(data){

                if(data.count == 0){
                    $('#'+ data.id).attr('src','./../images/star-grey.png');
                }
                else{
                    $('#'+ data.id).attr('src','./../images/star-yellow.png');
                }

            }
            });

        });

        $('img.imgFollow').click(function(){

            var img  =   $(this).attr('src');
            var id  =   $(this).attr('id');
            var follow  =   '';

            if(img.indexOf('yellow') == -1){

                $('#'+id).attr('src','./../images/star-yellow.png');
                follow = 1;

            }else{

                $('#'+id).attr('src','./../images/star-grey.png');
                follow = 0;

            }

            var dataString = {"follow":follow,"ticketId":id};

            $.ajax({
            url	        :"ajax_response.php",
            type	:"post",
            data	:dataString,
            dataType 	:"json",
            success	:function(data){

                followcount();
                var pageUrl = $(location).attr('href');

                if(pageUrl.indexOf('tp=f') != -1){
                    window.location.reload();
                }

            }
            });

        });

    });

        function followcount(){

        var dataString = {"count":'followcount'};
        $.ajax({
            url	        :"ajax_response.php",
            type	:"post",
            data	:dataString,
            dataType 	:"json",
            success	:function(data){

                $('#follow_count').html(data.totCount);

            }
            });

        }

<!--
        function clickSearch() {
                if (validateSearch() == true) {
                        document.frmSearch.method="post";
                        document.frmSearch.submit();
                }
        }

        function changeDepartment() {
                document.frmDetail.method="post";
                document.frmDetail.submit();
        }

        function validateSearch() {
                var frm = document.frmSearch;
                if (frm.cmbCompany.value == "" && frm.txtDepartment.value == "" && frm.txtStatus.value == "" && frm.txtOwner.value == "" && frm.txtUser.value == "" && frm.txtTicketNo.value == "" && frm.txtTitle.value == "" && frm.txtLabel.value == "" && frm.txtQuestion.value == "" && frm.txtFrom.value == "" && frm.txtTo.value == "") {
                        return false;
                }
                else {
                        return true;
                }
        }
		function setRefresh(){
				document.frmDetail.method="post";
				document.frmDetail.submit();
		}

        function changeRefresh() {
                var tm = parseInt(document.frmDetail.cmbRefresh.value);
                if(!isNaN(tm) && tm > 0) {
                setTimeout("callMe()", (tm*60*1000));
                }
        }
        function callMe() {
                if(document.frmDetail.cmbRefresh.value != "0") {
                        document.frmDetail.method="post";
                        document.frmDetail.submit();
                }
        }
		function deleteTickets(chk, maxp ) {
			var flag = false;
			if(chk == 0) {
			 document.frmDetail.del.value="DM";
			  for(i=1;i<=maxp;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			else {
			 document.frmDetail.del.value="DN";
			  for(i=1;i<=maxp;i++) {
				try{
					if(eval("document.getElementById('chkDeleteTickets2" + i + "').checked") == true) {
						flag = true;
						break;
					}
				 }catch(e) {}
				}
			}
			if(flag == true) {
				  if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
					document.frmDetail.method="post";
					document.frmDetail.submit();
				  }
			}
			else {
				alert('<?php echo MESSAGE_JS_SELECT_ONE; ?>');
			}
		}

function checkvalid(id) {
	if (str.length > 0)
	{
		var url="ajax.php?id=" + id;
		xmlHttp.open("GET", url , true);
		xmlHttp.onreadystatechange=function() {
  			if(xmlHttp.readyState==4 ){

		   			if(trim(xmlHttp.responseText)=="invalid"){

						alert("This user name does not exists!");
		   				document.getElementById("login_username").value="";
		   				document.getElementById("login_username").focus()
					}
  			}
 		}
		xmlHttp.send(null)
	 }
}

	function checkallfn(maxp){
		            if(document.frmDetail.checkall.checked){
			             for(i=1;i<=maxp;i++) {
			               try{

		                     document.getElementById('chkDeleteTickets' + i ).checked=true;
			               }catch(e) {}
			             }

		            }else{
		                  for(i=1;i<=maxp;i++) {
			               try{

		                     document.getElementById('chkDeleteTickets' + i ).checked=false;
			               }catch(e) {}
			             }
		            }
            }

            function checkallfn1(maxp){
		            if(document.frmDetail.checkall1.checked){
			             for(i=1;i<=maxp;i++) {
			               try{
			                     document.getElementById('chkDeleteTickets2' + i ).checked=true;
				              }catch(e) {}
			             }
		            }else{
		                  for(i=1;i<=maxp;i++) {
			               try{

		                     document.getElementById('chkDeleteTickets2' + i ).checked=false;
			               }catch(e) {}
			             }
		            }
            }

-->
</script>
<link href="./../styles/calendar.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="./../scripts/calendar.js"></script>
    <script type="text/javascript" src="./../scripts/calendar-setup.js"></script>
    <script type="text/javascript" src="./languages/<?php echo $_SP_language; ?>/calendar.js"></script>
<script type="text/javascript" src="./includes/functions/ajax.js"></script>
</head>

<body>
<!-- Ajax tool tip-->

<div id="tooltipBox" onMouseOver="clearAdInterval();" onMouseOut="hideAd();" style="z-index:5000;position:absolute;cursor:pointer;"></div>
<!--end  Ajax tool tip-->
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
							include("./includes/staffside.php");
							include("./includes/dept_overview.php");
							include("./includes/dataentries.php");
						?>
     <!-- End of side links -->
   </div>
   
   <div class="content_column_big">
   		 <?php
            			   // include("./includes/staffheader.php");
			        ?>
                  <!-- Tickets Assigned Section -->
	               <form name="frmDetail" action="<?php echo   $_SERVER['PHP_SELF']?>" method="post">
    	              <?php
        		              include("./includes/ticketsassigned.php");
                	  ?>
                  <!-- End Tickets Assigned  section -->
                   </form>
                  <!-- Advanced Search -->
                 <?php
                          include("./includes/advancedsearch.php");
                  ?>
                  <!-- End Advanced Search -->
        </div>
          <!-- Main footer -->
          <?php
                  include("./includes/mainfooter.php");
          ?>
          <!-- End Main footer -->
   
  
   
    
			       
</body>
<script>
<!--
        var rf='<?php echo($_SESSION["sess_refresh"]); ?>';
		var df = '<?php echo($var_deptid); ?>';
		document.frmDetail.cmbDepartment.value=df;
        document.frmDetail.cmbRefresh.value=rf;
        changeRefresh();
-->
</script>
</html>