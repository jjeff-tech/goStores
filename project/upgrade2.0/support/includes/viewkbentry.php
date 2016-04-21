<?php
if($_GET["numBegin"] != "") {
    $numBegin = $_GET["numBegin"];
	$start = $_GET["start"];
	$begin = $_GET["begin"];
	$num = $_GET["num"];
}
if ($_GET["stylename"] != "") {
	$styleminus = $_GET["styleminus"];
	$stylename = $_GET["stylename"];
	$styleplus = $_GET["styleplus"];
}else {
	$styleminus = $_POST["styleminus"];
	$stylename = $_POST["stylename"];
	$styleplus = $_POST["styleplus"];
}

if($_POST["ddlCategory"] != ""){
	$ddlCategory = $_POST["ddlCategory"];
}else{
	$ddlCategory = $_GET["ddlCategory"];
}
if($_POST["ddlDepartment"] != ""){
	$ddlDepartment = $_POST["ddlDepartment"];
}else{
	$ddlDepartment = $_GET["ddlDepartment"];
}

$error = false;
$errormessage = "" ;

if(isNotNull($_GET["id"])){
	$kbid = $_GET["id"];
	
	settype($kbid,integer);
	$sql = " SELECT nKBID, vKBTitle, tKBDesc ";
	$sql .=" FROM  sptbl_kb  ";
	$sql .=" WHERE nKBID = '$kbid' ";

	$rs = executeSelect($sql,$conn);
	if(mysql_num_rows($rs) > 0){
		$row = mysql_fetch_array($rs);
		$title = $row["vKBTitle"];
		$description = $row["tKBDesc"]; 
	}else{
		$error = true;
		$errormessage = "" ;
	}
}else{
	$error = true;
}

if($error){
	$errormessage = MESSAGE_ERRORS_FOUND . "<br>" .$errormessage; 
}

?>
<script>
<!--

 function validateProfileForm(){
	var frm = window.document.frmKB;
	var errors="";
	if(frm.txtName.value.length == 0){
            errors += "<?php echo MESSAGE_NAME_REQUIRED; ?>"+ "\n";
	}
	if(frm.txtEmail.value.length == 0){
            errors += "<?php echo MESSAGE_EMAIL_REQUIRED; ?>"+ "\n";
	}else if(!isValidEmail(frm.txtEmail.value)){
            errors += "<?php echo MESSAGE_INVALID_EMAIL; ?>"+ "\n";
	}
	if(errors !=""){
            errors = "<?php echo MESSAGE_ERRORS_FOUND; ?>"+  "\n" +  "\n" + errors;
            alert(errors);
            return false;
	}else{
            frm.postback.value = "Save Changes";
            frm.submit();
	}
    }
function cancel(){
	;
}
function changeDepartment(){
  document.frmKB.postback.value="CD";
  document.frmKB.method="post";
  document.frmKB.submit();
}	
function changeCategory(){
  document.frmKB.postback.value="CC";
  document.frmKB.method="post";
  document.frmKB.submit();
  
}
function clickDelete() {
var i=1;
var flag = false;
for(i=1;i<=10;i++) {
try{
if(eval("document.getElementById('c" + i + "').checked") == true) {
                        flag = true;
break;
}
}catch(e) {}
}
        if(flag == true) {
                                        if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
                                                document.frmKB.postback.value="DA";
                document.frmKB.method="post";
                document.frmKB.submit();
                                        }
        }
                                else {
                                        alert('<?php echo MESSAGE_JS_OPERATION_ERROR; ?>');
                                }
        }


function deleted(id) {
    if(confirm('<?php echo MESSAGE_JS_DELETE_TEXT; ?>')) {
            document.frmKB.id.value=id;
            document.frmKB.postback.value="D";
            document.frmKB.method="post";
            document.frmKB.submit();
    }
}
-->
</script>
<div class="content_section">

<form action="" method="post" name="frmKB">

<div class="content_section_title">
<h3><?php echo TEXT_KNOWLEDGEBASE?></h3>
</div>

<div class="content_section">
<div class="content_section_data">
<?php if($error){
        echo "<div class='msg_error'>".$errormessage."</div>";
}
 $sql_rate_exist = "SELECT sKBRId FROM  sptbl_kb_rating WHERE nKBID='$kbid' AND nUserId='".$_SESSION['sess_userid']."'";
$res_rate_exist =  executeSelect($sql_rate_exist,$conn);
$sql_rating = "Select SUM(nMarks) as Rating, count(*) as TotalRatings from  sptbl_kb_rating where nKBID = '".$kbid."' GROUP BY nKBID ";
$rs_rating  = executeSelect($sql_rating,$conn);
if(mysql_num_rows($rs_rating)>0){
    $row_rating = mysql_fetch_array($rs_rating);
   $avgrating  = ceil($row_rating['Rating']/$row_rating['TotalRatings']);
}
if(mysql_num_rows($res_rate_exist)>0){?>
    <b><?php echo htmlentities($title); ?></b>
    <br><span class='rating_<?php echo $avgrating ?>'></span>
    <br><br>
<?php
}else{?>
    <b><?php echo htmlentities($title); ?> </b>&nbsp; &nbsp; (<b><a href='#' class='prdetails_link1' onclick='return rateKB()' style="color:blue; "><?php echo TEXT_KNOWLEDGEBASE_RATENOW?></a></b>)
    <br><span class='rating_<?php echo $avgrating ?>'></span>
    <br><br>
<?php
}?>


 <?php
 //echo strip_tags(htmlentities($description));
 echo $description; ?>
<!---------------------------------------RATING POP UP------------------------------->
<div id="jqRatingPop" class="jqRatingPop" style="display:none;">
    <div style="width:280px;"><h1 style="font-size:14px; "><?php echo TEXT_KNOWLEDGEBASE_RATENOW ?></h1></div>
    <div id="jqLoader" style="display:none;"><img src="images/loading.gif" border="0" class="loader" alt="" /></div>
    <div id="ratingArea">
        <form name="frmRate" method="post" action="#" >
            <table cellpadding="1" cellspacing="1" width="100%" border="0" class="ratingBox">
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td class="emailStoryStyle"><strong><?php echo TEXT_KNOWLEDGEBASE_RATE?>:</strong></td>
                    <td>
                        <input name="star"  type="radio" class="star"  value="1" />
                        <input name="star"  type="radio" class="star"  value="2" />
                        <input name="star"  type="radio" class="star"  value="3" />
                        <input name="star"  type="radio" class="star"  value="4" />
                        <input name="star"  type="radio" class="star"  value="5" />
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>

                <tr><td colspan="2">&nbsp; </td></tr>
                <tr>
                    <td align="center" colspan="2">
                        <input type="hidden" name="hid_user_id" id="hid_user_id" value="<?php echo $_SESSION[sess_userid]; ?>" />
                        <input type="hidden" name="txtKbSearchid" id="txtKbSearchid" value="<?php echo $kbid; ?>" />
                        <input type="hidden" name="site_url" id="site_url" value="<?php echo SITE_URL; ?>" />
                        <input type="button" value="<?php echo TEXT_KNOWLEDGEBASE_RATE?>" style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqPostProductRating" onclick="submitKBRating()" id="comn_button_blue1"/>
                        <input type="button" value="<?php echo BUTTON_TEXT_CANCEL; ?>"  style="background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; " class="jqProductRatingCancel" id="comn_button_blue2" onclick="closeKBRating()"/>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php
include("./includes/releatedresultsuser.php");
getReleatedResults($title, $kbid);
?>

<!---------------------------------------EOF RATING POP UP------------------------------->
<div align="center"><br><br>
<!-- <input type="button" value=" Back " onClick = "location.href='<?php echo "knowledgebase.php?ddlCategory=".$ddlCategory."&ddlDepartment=".$ddlDepartment."&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&numBegin=".$numBegin."&start=".$start."&begin=".$begin."&num=".$num."&"?>';" class="button"> -->
    <input type="button" value=" <?php echo BUTTON_TEXT_BACK; ?> " class="comm_btn" onClick = "location.href='<?php echo SITE_URL."knowledgebase.php?ddlCategory=".$ddlCategory."&ddlDepartment=".$ddlDepartment."&stylename=KNOWLEDGEBASE&styleminus=threeminus&styleplus=threeplus&numBegin=".$numBegin."&start=".$start."&begin=".$begin."&num=".$num."&"?>';">
    <input type="hidden" name="numBegin" value="<?php echo   $numBegin?>">
    <input type="hidden" name="start" value="<?php echo   $start?>">
    <input type="hidden" name="begin" value="<?php echo   $begin?>">
    <input type="hidden" name="num" value="<?php echo   $num?>">
    <input type="hidden" name="mt" value="y">
    <input type="hidden" name="stylename" value="<?php echo($stylename); ?>" >
    <input type="hidden" name="styleminus" value="<?php echo($styleminus); ?>">
    <input type="hidden" name="styleplus" value="<?php echo($styleplus); ?>">
    <input type="hidden" name="id" value="<?php echo($id); ?>">
    <input type="hidden" name="postback" value="">
</div>
</div>
</div>
    
<!-- Buttons were placed here ---- -->
<!-- Buttons were placed here ---- -->
</form>
</div>