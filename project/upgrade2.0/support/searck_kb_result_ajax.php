<?php
     include_once("./includes/applicationheader.php");
     include_once("./includes/functions/miscfunctions.php");
    include "languages/".$_SP_language."/knowledgebase.php";
	$q=$_GET['q'];
       
        if($_POST['txtKbSearchid']){
        $txtKbSearchid = $_POST['txtKbSearchid'];
        }
        if($_POST['txtKbSearchTitle']){
        $txtKbSearchTitle = $q;
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
<div class="content_section_title"><h4 style="padding: 0px;"><?php echo TEXT_SEARCH_RESULT;?></h4></div><br>
<?php
	if(mysql_num_rows($result)>0)
	{
             include("./includes/releatedresultsuser.php");
		$row=mysql_fetch_array($result);
                $sql_rate_exist = "SELECT sKBRId FROM  sptbl_kb_rating WHERE nKBID='$my_data' AND nUserId='".$_SESSION['sess_userid']."'";
                $res_rate_exist =  executeSelect($sql_rate_exist,$conn);
                $sql_rating = "Select SUM(nMarks) as Rating, count(*) as TotalRatings from  sptbl_kb_rating where nKBID = '".$my_data."' GROUP BY nKBID ";
                $rs_rating  = executeSelect($sql_rating,$conn);
                if(mysql_num_rows($rs_rating)>0){
                    $row_rating = mysql_fetch_array($rs_rating);
                    $avgrating  = ceil($row_rating['Rating']/$row_rating['TotalRatings']);
                }
                if(mysql_num_rows($res_rate_exist)>0){
                    echo "<b>". TEXT_TITLE . " : " .$row['vKBTitle']."</b>&nbsp<br>";
                    echo "<br>";?><span class='rating_<?php echo $avgrating ?>'></span>
                    <?php
                    echo "<br>" . $row['tKBDesc'];
                    getReleatedResults($row['vKBTitle'],$row['nKBID']);
                }
                else{
                   echo "<b>" .$row['vKBTitle']."</b>";
                   echo "<br>";?><span class='rating_<?php echo $avgrating ?>'></span>
                   <?php
                    echo "<br>",$row['tKBDesc']."<br><br>";
                    echo "<div id=\"jqRateProduct\" style=\"font-size:16px; \">&nbsp;(<a href='#' class='prdetails_link1' onclick='return rateKB()' style=\"color:blue; \">".RATE_TITLE."</a>)</div>";
                    getReleatedResults($row['vKBTitle'],$row['nKBID']);
//                     echo "<div id=\"jqRatingPop\" class=\"jqRatingPop\" style=\"display:none;\">
//                            <div style=\"width:280px;\"><h1>Rate</h1></div>
//                            <div id=\"jqLoader\" style=\"display:none;\"><img src=\"images/loading.gif\" border=\"0\" class=\"loader\" /></div>
//                            <div id=\"ratingArea\">
//                                <form name=\"frmRate\" method=\"post\" action=\"#\" >
//                                    <table cellpadding=\"1\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"ratingBox\">
//                                        <tr><td colspan=\"2\">&nbsp;</td></tr>
//                                        <tr>
//                                            <td class=\"emailStoryStyle\"><strong>Rate:</strong></td>
//                                            <td>
//                                                <input name=\"star\"  type=\"radio\" class=\"star\" onclick=\"return ratingStarValue()\" value=\"1\" />
//                                                <input name=\"star\"  type=\"radio\" class=\"star\" onclick=\"return ratingStarValue()\" value=\"2\" />
//                                                <input name=\"star\"  type=\"radio\" class=\"star\" onclick=\"return ratingStarValue()\" value=\"3\" />
//                                                <input name=\"star\"  type=\"radio\" class=\"star\" onclick=\"return ratingStarValue()\" value=\"4\" />
//                                                <input name=\"star\"  type=\"radio\" class=\"star\" onclick=\"return ratingStarValue()\" value=\"5\" />
//                                            </td>
//                                        </tr>
//                                        <tr><td colspan=\"2\">&nbsp;</td></tr>
//
//                                        <tr><td colspan=\"2\">&nbsp; </td></tr>
//                                        <tr>
//                                            <td align=\"center\" colspan=\"2\">
//                                                <input type=\"hidden\" name=\"hid_kb_id\" id=\"hid_kb_id\" value='$my_data' />
//                                                <input type=\"hidden\" name=\"hid_user_id\" id=\"hid_user_id\" value=\"$_SESSION[sess_userid]\" />
//                                                <input type=\"button\" value=\"Rate\" style=\"background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; \" class=\"jqPostProductRating\" onclick=\"submitKBRating()\" id=\"comn_button_blue1\"/>
//                                                <input type=\"button\" value=\"Cancel\"  style=\"background-color:#333333; font-weight: bold; color:#FFFFFF; padding: 5px 8px; border:1px solid #333333; \" class=\"jqProductRatingCancel\" id=\"comn_button_blue2\" onclick=\"closeKBRating()\"/>
//                                            </td>
//                                        </tr>
//                                    </table>
//                                </form>
//                            </div>
//                        </div>";
                    exit;
                }
		
	}
        else{
             echo "No result found";
             exit;
        }
       
      }
     
       
exit;
              
?>

