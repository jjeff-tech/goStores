<?php
function format_html($content) {
$content = "

" . str_replace("\r\n", "<br/>", $content) . "";
$content = "" . str_replace("<br/><br/>", "

", $content) . "";
return "" . str_replace("<br/><li>", "<li>", $content) . "";
}

$maxPageLimit = getSettingsValue('MaxPostsPerPage');

?>

<div class="content_section">

<form name="frmShowKb" action="<?php echo($_SERVER["REQUEST_URI"]); ?>" method="post">
<table width="100%"  border="0" cellspacing="10" cellpadding="0">
<tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="dotedhoriznline">
            <tr>
                <td><img src="./images/spacerr.gif" width="1" height="1"></td>
            </tr>
        </table>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="1" class="vline" ><img src="./images/spacerr.gif" width="1" height="1"></td>
                <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <td width="93%" class="heading"><?php echo HEADING_KB_ENTRY ?></td>
                        </tr>
                    </table>
                    <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="column1">
                        <tr>
                            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="whitebasic">
                                    <tr>
                                        <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">

                                                <tr>
                                                    <td class="bodycolor" ><table width="100%"  border="0" cellpadding="0" cellspacing="3"  >

                                                            <tr align="left" >
                                                                <td colspan="5" class="listingmaintext">&nbsp;</td>
                                                            </tr>
                                                            <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                                                            <tr align="left" >
                                                                <td colspan="5" class="listingmaintext">
                                                                    <table width="100%"  border="0" cellspacing="10" cellpadding="0">
                                                                        <tr>
                                                                            <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="dotedhoriznline">
                                                                                    <tr>
                                                                                        <td><img src="./images/spacerr.gif" width="1" height="1"></td>
                                                                                    </tr>
                                                                                </table>
                                                                                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                                                                    <tr>
                                                                                        <td width="1" class="vline"><img src="./images/spacerr.gif" width="1" height="1"></td>
                                                                                        <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                                                                                <tr>
                                                                                                    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                                                                                            <tr align="center" class="listingbtnbar">
                                                                                                                <td>
                                                                                                                    <span>
                                                                                                                        <?php
                                                                                                                        echo TEXT_ENTRY_FOUND ."<br>";
                                                                                                                        echo TEXT_CHECK_IF_IT_SOLVES ."<br>";
                                                                                                                        echo TEXT_IF_NOT_CONTINUE ."<br>";
                                                                                                                        ?>
                                                                                                                    </span>
                                                                                                                </td></tr>
                                                                                                        </table></td>
                                                                                                </tr>
                                                                                            </table></td>
                                                                                        <td width="1" class="vline"><img src="./images/spacerr.gif" width="1" height="1"></td>
                                                                                    </tr>
                                                                                </table>
                                                                                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                                                                    <tr>
                                                                                        <td class="dotedhoriznline"><img src="./images/spacerr.gif" width="1" height="1"></td>
                                                                                    </tr>
                                                                                </table></td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->

                                                            <tr align="left" >
                                                                <td colspan="5" class="listingmaintext" align=center><b><?php echo $var_title; ?></b></td>
                                                            </tr>
                                                            <tr align="left" class="pagecolor">
                                                                <td colspan="5" class="dotedhoriznline" height="1"><img src="./images/spacerr.gif" width="1" height="1"></td>
                                                            </tr>
                                                            <?php
                                                            //$totalrows = mysql_num_rows(mysql_query($sql,$conn));

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
                                                            $navigate = pageBrowser($totalrows,10,$maxPageLimit,"&mt=y&deptid=$var_deptpid&tckttitle=" . urlencode($var_title) . "&stylename=POSTTICKETS&styleminus=twominus&styleplus=twoplus&",$var_numBegin,$var_start,$var_begin,$var_num);

                                                            //execute the new query with the appended SQL bit returned by the function
                                                            $sql = $sql.$navigate[0];

                                                            //echo $sql;
                                                            //echo "<br>".time();
                                                            //$rs = mysql_query($sql,$conn);
                                                            $rs = executeSelect($sql,$conn);
                                                            $cnt = 1;
                                                            while($row = mysql_fetch_array($rs)) {
                                                                ?>

                                                            <tr align="left"  class="listingmainboldtext">

                                                                <td colspan=2>

                                                                    <b><?PHP echo stripslashes($row["vKBTitle"]); ?></b>
                                                                </td>

                                                            </tr>
                                                            <tr align="left"  class="listingmaintext">
                                                                <td colspan=2>
                                                                        <?PHP// echo format_html(nl2br(htmlentities($row["tKBDesc"]))); ?>
                                                                        <?PHP echo format_html(nl2br(stripslashes($row["tKBDesc"]))); ?>
                                                                </td>
                                                            </tr>
                                                            <tr align="left"  class="listingmaintext">
                                                                <td colspan="5" class="dotedhoriznline" height="1"><img src="./images/spacerr.gif" width="1" height="1"></td>
                                                            </tr>
                                                                <?php
                                                                $cnt++;
                                                            }
                                                            mysql_free_result($rs);
                                                            ?>
                                                            <tr   class="listingmaintext">
                                                                <td align="left" width="30%"><?php echo($navigate[1] ."&nbsp;".TEXT_OF."&nbsp;".$totalrows."&nbsp;" .TEXT_RESULTS ); ?></td>
                                                                <td align="right" style="padding-right: 10px;"  ><?php echo($navigate[2]); ?>
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
                <td width="1" class="vline"><img src="./images/spacerr.gif" width="1" height="1"></td>
            </tr>

        </table>

        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="dotedhoriznline"><img src="./images/spacerr.gif" width="1" height="1"></td>
            </tr>
        </table></td>
</tr>
</table>

<table width="100%"  border="0" cellspacing="10" cellpadding="0">
<tr>
    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="dotedhoriznline">
            <tr>
                <td><img src="./images/spacerr.gif" width="1" height="1"></td>
            </tr>
        </table>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="1" class="vline"><img src="./images/spacerr.gif" width="1" height="1"></td>
                <td class="pagecolor"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr align="left" class="listingbtnbar">
                                        <td>
                                            <input name="btnDelete" type="button" class="button" value="<?php echo BUTTON_TEXT_CONTINUE; ?>" onClick="javascript:clickContinue();">                                    </td></tr>
                                </table></td>
                        </tr>
                    </table></td>
                <td width="1" class="vline"><img src="./images/spacerr.gif" width="1" height="1"></td>
            </tr>
        </table>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="dotedhoriznline"><img src="./images/spacerr.gif" width="1" height="1"></td>
            </tr>
        </table></td>
</tr>
</table>
</form>