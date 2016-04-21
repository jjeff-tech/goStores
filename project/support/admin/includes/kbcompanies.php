<?php
//$var_userid = $_SESSION["sess_staffid"];
$var_staffid = "1";


if($_GET["mt"] == "y") {
	$var_numBegin = $_GET["numBegin"];
	$var_start = $_GET["start"];
	$var_begin = $_GET["begin"];
	$var_num = $_GET["num"];
	$var_styleminus = $_GET["styleminus"];
	$var_stylename = $_GET["stylename"];
	$var_styleplus = $_GET["styleplus"];
}
elseif($_POST["mt"] == "y") {
	$var_numBegin = $_POST["numBegin"];
	$var_start = $_POST["start"];
	$var_begin = $_POST["begin"];
	$var_num = $_POST["num"];
	$var_styleminus = $_POST["styleminus"];
	$var_stylename = $_POST["stylename"];
	$var_styleplus = $_POST["styleplus"];
}

$sql = "Select * from sptbl_companies where vDelStatus='0'";

$qryopt="";
if($_POST["txtSearch"] != ""){
		$var_search = $_POST["txtSearch"];
}else if($_GET["txtSearch"] != ""){
		$var_search = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != ""){
		$var_cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != ""){
		$var_cmbSearch = $_GET["cmbSearch"];
}

if($var_search != ""){
	if($var_cmbSearch == "name"){
			$qryopt .= " AND vCompName like '" . mysql_real_escape_string($var_search) . "%'";
	}elseif($var_cmbSearch == "city"){
			$qryopt .= " AND vCompCity like '" . mysql_real_escape_string($var_search) . "%'";
	}
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
//$_SESSION["backurl"] = $sess_back;
$sql .= $qryopt . " Order By vCompName Asc ";

?>

<form name="frmDetail" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">
<table width="100%"  border="0" cellspacing="10" cellpadding="0">
            <tr>
              <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td><img src="./../images/spacerr.gif" width="1" height="1"></td>
                  </tr>
                </table>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="1"  ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                      <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                          <tr>
                            <td width="93%" class="heading"><?php echo TEXT_SELECT_COMPANY ?></td>
                          </tr>
                        </table>
                          <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="column1">
                            <tr>
                              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
											<td width="100%">
												<table width="100%" cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td width="39%" align="left"><b><?php echo($var_message); ?></b>
														</td>
													    <td width="61%" align="right">Search
                                                          <select name="cmbSearch" class="textbox">
                                                            <option value="name" <?php echo(($var_cmbSearch == "name" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_COMPANY ?></option>
                                                            <option value="city" <?php echo(($var_cmbSearch == "city")?"Selected":""); ?>><?php echo TEXT_CITY ?></option>
                                                          </select>
&nbsp;
<input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="textbox" onKeyPress="if(window.event.keyCode == '13'){ return false; }">
<a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a></td>
												    </tr>
													<tr><td colspan="2">&nbsp;</td></tr>
												</table>
											</td>
										</tr>	
									    <tr>
                                          <td class="whitebasic" ><table width="100%"  border="0" cellpadding="2" cellspacing="1" class="column1" >
                                              <tr align="left"  class="whitebasic">
                                                <td ><?php echo "<b>".TEXT_COMPANY."</b>"; ?></td>
                                                <td ><?php echo "<b>".TEXT_CITY."</b>"; ?></td>
                                                <td ><?php echo "<b>".TEXT_EMAIL."</b>"; ?></td>
                                                
                                              </tr>
<?php
        

//$totalrows = mysql_num_rows(mysql_query($sql,$conn));
$totalrows = mysql_num_rows(executeSelect($sql,$conn));
settype($totalrows,integer);
settype($var_begin,integer);
settype($var_num,integer);
settype($var_numBegin,integer);
settype($var_start,integer);

$var_calc_begin = ($var_begin == 0)?$var_start:$var_begin;
 if(($totalrows <= $var_calc_begin)) {
         $var_nor = 10;
        $var_nol = 10;
         if($var_num > $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin;
                $var_begin = $var_begin - $var_nor;
        }
        elseif($var_num == $var_numBegin) {
                $var_num = $var_num - 1;
                $var_numBegin = $var_numBegin - $var_nol;
                $var_begin = $var_calc_begin - $var_nor;
                $var_start="";
        }
 }

//echo("$totalrows,2,2,\"&ddlSearchType=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&id=$var_batchid&\",$var_numBegin,$var_start,$var_begin,$var_num");
$navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLECOMPANIES&styleminus=minus5&styleplus=plus5&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);
$rs = executeSelect($sql,$conn);
$cnt = 1;
while($row = mysql_fetch_array($rs)) {
?>

                                              <tr align="left"  class="whitebasic">
                                                <td><a href="#"><?PHP echo htmlentities($row["vCompName"]); ?></a></td>
                                                <td><?php echo htmlentities($row["vCompCity"]); ?></td>
                                                <td><?php echo htmlentities($row["vCompMail"]); ?></td>
                                              </tr>
                                               <tr align="left"  class="whitebasic">
                                                <td colspan="3"  height="1"><img src="./../images/spacerr.gif" width="1" height="1"></td>
                                              </tr>
<?php
$cnt++;
}
mysql_free_result($rs);
?>
                                              <tr align="left"  class="listingmaintext">
                                                <td><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></td>
                                                <td colspan="2"><?php echo($navigate[2]); ?>
												  <input type="hidden" name="numBegin" value="<?php echo   $var_numBegin?>">
												  <input type="hidden" name="start" value="<?php echo   $var_start?>">
												  <input type="hidden" name="begin" value="<?php echo   $var_begin?>">
												  <input type="hidden" name="num" value="<?php echo   $var_num?>">    
												   <input type="hidden" name="mt" value="y">
													<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>">
													<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
													<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">																
													  <input type="hidden" name="postback" value="">
												  <input type="hidden" name="id" value="">
											   </td>
                                             </tr>
                                          </table></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                              </table></td>
                            </tr>
                        </table></td>
                      <td width="1" ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>

                  </table>

                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><img src="./../images/spacerr.gif" width="1" height="1"></td>
                    </tr>
                </table></td>
            </tr>
          </table>
</form>