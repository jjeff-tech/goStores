<?php
$var_staffid = $_SESSION["sess_staffid"];

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

$sql = "SELECT u.nUserId,u.vUserName,c.vCompName,u.vOnline,u.ddate,u.vOnline,u.vBanned ,u.vLogin
		FROM 
		sptbl_users u INNER JOIN sptbl_companies c on  u.nCompId=c.nCompId 
		WHERE (u.vDelStatus='0') and (c.nCompId IN (".getStaffCompanies($_SESSION["sess_staffid"]).")) ";

$qryopt="";

if($_POST["txtSearch"] != "") {
    $var_search = $_POST["txtSearch"];
}else if($_GET["txtSearch"] != "") {
    $var_search = $_GET["txtSearch"];
}

if($_POST["cmbSearch"] != "") {
    $var_cmbSearch = $_POST["cmbSearch"];
}else if($_GET["cmbSearch"] != "") {
    $var_cmbSearch = $_GET["cmbSearch"];
}

if($var_search != "") {
    if($var_cmbSearch == "cname") {
        $qryopt .= " AND c.vCompName like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "username") {
        $qryopt .= " AND u.vUserName like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "loginname") {
        $qryopt .= " AND u.vLogin like '" . mysql_real_escape_string($var_search) . "%'";
    }elseif($var_cmbSearch == "email") {
        $qryopt .= " AND u.vEmail like '" . mysql_real_escape_string($var_search) . "%'";
    }
}

//$var_back="companies.php?mt=ybegin=" . $var_begin . "&num=" . $var_num . "&numBegin=" . $var_numBegin . "&start=$var_start&cmbSearch=" . $var_cmbSearch . "&txtSearch=" . urlencode($var_search) . "&";
$_SESSION["sess_backurl"] = getPageAddress();

$sql .= $qryopt . " Order By u.ddate DESC ";

//echo "sql==$sql";
?>

<form name="frmDetail" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">


    <div class="content_section">





        <div class="content_section_title">
            <h3><?php echo HEADING_USER_DETAILS ?></h3>
        </div>








        <td align="center" colspan="3"><b><?php echo($var_message); ?></b></td>
        </tr>

        <div style="background-color:#ffffff; " class="content_search_container">
            <div class="left rightmargin topmargin"> <?php echo(TEXT_SEARCH); ?></div>
            <div class="left rightmargin">

                <select name="cmbSearch" class="comm_input input_width1">
                    <option value="username" <?php echo(($var_cmbSearch == "username" || $var_cmbSearch == "")?"Selected":""); ?>><?php echo TEXT_USER ?></option>
                    <option value="cname" <?php echo(($var_cmbSearch == "cname")?"Selected":""); ?>><?php echo TEXT_COMPANY ?></option>
                    <option value="loginname" <?php echo(($var_cmbSearch == "loginname")?"Selected":""); ?>><?php echo TEXT_LOGIN ?></option>
                    <option value="email" <?php echo(($var_cmbSearch == "email")?"Selected":""); ?>><?php echo TEXT_MAIL ?></option>
                </select>
            </div>
            <div class="left">
                <input type="text" name="txtSearch" value="<?php echo(htmlentities($var_search)); ?>" class="comm_input input_width1" onKeyPress="if(window.event.keyCode == '13'){ return false; }" style="width:140px; height:20px!important; margin-right:5px;">
                </div>

            <div class="left"><a href="javascript:clickSearch();"><img src="./../languages/<?php echo $_SESSION['sess_language'];?>/images/go.gif" border="0"></a>
            </div>
            <div class="clear"></div>
        </div>


        <tr>
            <td class="whitebasic" >

                <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="list_tbl" >
                    <tr align="left"  class="listing">
                        <th width="3%">&nbsp;</th>
                        <th width="26%" ><?php echo "<b>".TEXT_USER."</b>"; ?></th>
                        <th width="22%"><?php echo "<b>".TEXT_COMPANY."</b>"; ?></th>
                        <th width="9%"><?php echo "<b>".TEXT_ONLINE."</b>"; ?></th>
                        <th width="9%"><?php echo "<b>".TEXT_BANNED."</b>"; ?></th>
                        <th width="13%"><?php echo "<b>".TEXT_DATE."</b>"; ?></th>
                        <th width="10%"><?php echo "<b>".TEXT_LOGIN."</b>"; ?></th>
                        <th colspan="2" align="center"><?php echo "<b>".TEXT_ACTION."</b>"; ?></th>
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
                    $navigate = pageBrowser($totalrows,10,10,"&mt=y&cmbSearch=$var_cmbSearch&txtSearch=" . urlencode($var_search) . "&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&",$var_numBegin,$var_start,$var_begin,$var_num);

//execute the new query with the appended SQL bit returned by the function
                    $sql = $sql.$navigate[0];
//echo "sql==$sql";
//echo $sql;
//echo "<br>".time();
//$rs = mysql_query($sql,$conn);

                    $rs = executeSelect($sql,$conn);
                    $cnt = $var_begin+1;
                    while($row = mysql_fetch_array($rs)) {
                        ?>

                    <tr align="left"  class="whitebasic">
                        <td align="center"><?php echo($cnt);?>&nbsp;</td>
                        <td width="26%" style="word-break:break-all;"><?php  echo htmlentities($row["vUserName"]); ?></td>
                        <td width="22%" style="word-break:break-all;"><?php echo htmlentities($row["vCompName"]); ?></td>
                        <td width="9%" align="center"><?php echo (($row["vOnline"] == "0")?"No":"Yes"); ?></td>
                        <td width="9%" align="center"><?php echo (($row["vBanned"] == "0")?"No":"Yes"); ?></td>
                        <td width="13%"><?php echo date("m-d-Y",strtotime($row["ddate"])); ?></td>
                        <td width="10%" style="word-break:break-all;"><?php echo htmlentities($row["vLogin"]); ?></td>
                        <td width="8%" align="center" colspan="2"><a href="viewuser.php?id=<?php echo $row["nUserId"]; ?>&stylename=STYLEUSERS&styleminus=minus8&styleplus=plus8&"><img src="././../images/view.gif" width="13" height="13" border="0" title="<?php echo TEXT_VIEW_DETAILS ?>"></a></td>
                    </tr>
                        <?php
                        $cnt++;
                    }
                    mysql_free_result($rs);
                    ?>
                    <tr align="left"  class="whitebasic">
                        <td colspan="10">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr class="whitebasic">
                                <div class="pagination_info">
                                <?php
                                if($totalrows > 0){
                                    echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS );
                                }
                                ?>
                                </div>
                                <div class="pagination_links"><?php echo($navigate[2]); ?>
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
                                </div>
                    </tr>
                </table>
            </td>
        </tr>
        </table></td>




    </div>

</div>

</form>

<?php
//echo "<br>Staff Id:".$_SESSION["sess_staffid"];
//echo "<br>jkhjkhkjh".getStaffCompanies($_SESSION["sess_staffid"]);
?>