<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/
include("includes/constants.php");
include("./languages/" . $_SP_language . "/viewuserkbsearchresult.php");
function getReleatedResults($title, $kbid) {
    $kbkey = explode(' ', $title);
    $keyCondition = "WHERE vStatus ='A' AND nKBID != '". $kbid ."' ";
    $ketCount1 = count($kbkey);
    if($ketCount1 > 0) {
        $keyCondition .= " AND (vKBTitle like  '%". mysql_real_escape_string($kbkey[0])."%' ";
        for($kbc = 1;$kbc < $ketCount1; $kbc++) {
            $keyCondition .= " OR vKBTitle like  '%". mysql_real_escape_string($kbkey[$kbc])."%'";
        }
        $keyCondition .= ")";
    }

    $sql = "SELECT DISTINCT kb.nKBID,kb.vKBTitle,kb.tKBDesc,c.vCatDesc from sptbl_kb kb
            INNER JOIN sptbl_categories c ON kb.nCatId = c.nCatId ".$keyCondition . " LIMIT 0, 10";
    
    //    $result_kbtitle =  executeSelect($sql,$conn);
    $result_kbtitle =  mysql_query($sql);
    $kbCount =mysql_num_rows($result_kbtitle);
    if(mysql_num_rows($result_kbtitle)>0) {
    ?>
    <div class="content_section_title">
    <h3><?php echo TEXT_RELATED_RESULTS;?></h3></div>
    <table cellpadding="5" cellspacing="1" border="0" class="" style="background-color:#cfcfcf;" width="100%">
		<div class="popular_kbs1">
		<ul>
        <?php
        while($row=mysql_fetch_array($result_kbtitle)) {
            //echo '<pre>';print_r($row);echo '</pre>';
            $seolink=str_replace(" ","-", stripslashes($row['vKBTitle']));
			$seolink=preg_replace('/[^a-zA-Z0-9__.-]/s', '', $seolink);
			$seolink=strtolower($seolink);
			$seolink=substr($seolink,0,100);
			$viewkbentry_seo_link = SITE_URL . "kb/".str_replace("?","", $seolink). "/".$row["nKBID"];
            ?>
				
				<li>
					<a href="<?php echo $viewkbentry_seo_link?>" class="listing"><span><?php echo $row["vCatDesc"]?></span> - <?php echo trimString(htmlentities($row["vKBTitle"]),140); ?></a> 
				</li>
					
            <?php
        }
    }
    if($kbCount > 10) {
        ?>
        <tr>
            <td align="left" style="background-color:#ffffff;">
                <a href="<?php echo $viewkbentry_seo_link?>" class="listing"><?php echo trimString(htmlentities($row["vKBTitle"]),140); ?></a>
            </td>
        </tr>
        <?php
    }
    ?>
	</ul>
	</div>
    </table>
    <?php
    }
    ?>
