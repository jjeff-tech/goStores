<?php
$qryopt="";
$txtSearch="";
$cmbSearch="";
?>

<div class="content_section" >
<form action="" method="post" name="frmKB">
<div class="content_section_title"><h3><?php echo TEXT_KB?></h3></div>


         												 <?php
														  if($error){?>
														 <div class="content_section_data">
														  <div class="msg_error">
														  <?php echo $errormessage;?>
														  </div>
														</div>

														  <?php }
														  if($message){ ?>
														  <div class="content_section_data">
														  <div class="msg_common">
														  <?php echo $messagetext;?>
														  </div>
														  </div>

														 <?php }?>

<form name="frmkbSearch" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">

<div class="content_section_data" style="padding:10px; ">

<table border="0" width="100%" cellpadding="0" cellspacing="0">
    
    <tr>
        <td>
		<div class="content_search_container">
		<h3 style="margin:0; padding:5px 0; "><?php echo TEXT_SEARCH;?></h3>
		</div>
		</td>
       
        <td colspan="2">
		<div class="content_search_container">
            <input name="txtKbTitleSearch" id="txtKbTitleSearch" type="text" class="comm_input input_width2"  value="<?php echo htmlentities($_POST['txtKbTitleSearch']);?>" size="200">
            <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">
&nbsp;&nbsp;
        <input type="submit" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_GO;?>" class="comm_btn">
		</div>
		</td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3"><h3><?php echo TEXT_SEARCH_RESULT;?></h3></td></tr>
    
    <tr><td colspan="3">
            <div id ="kbSearchResult">

                <?php
if($_POST['btnKbSearch']){
$txtKbSearchTitle  =trim($_POST['txtKbTitleSearch']);
     //Search by title
     /* if($txtKbSearchTitle !='')
          {*/
                    $my_data=mysql_real_escape_string($txtKbSearchTitle);

                   $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (vKBTitle = '$my_data') AND   vStatus ='A'";
        $result_kbtitle =  executeSelect($sql,$conn);

                    if(mysql_num_rows($result_kbtitle)>0){
                        $row=mysql_fetch_array($result_kbtitle);

            ?>
                <table cellpadding="0" cellspacing="0" border="0" class="">
                    <tr>
                        <td><?php echo "<b>".TEXT_TITLE. " : " . $row['vKBTitle']."</b>";?></td>
                    </tr>
                    <tr>
                        <td><?php echo stripslashes($row["tKBDesc"]);?></td>
                    </tr>
                </table>
                <?php

                    }

else{
	 $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (vKBTitle like  '%$my_data%') AND   vStatus ='A'";
        $result_kbtitle =  executeSelect($sql,$conn);

                    if(mysql_num_rows($result_kbtitle)>0)
                {
            ?>
<table cellpadding="5" cellspacing="1" border="0" class="" style="background-color:#cfcfcf;" width="100%">
<?php
		while($row=mysql_fetch_array($result_kbtitle)){
                $viewkbentry_seo_link = "viewuserkbsearchresult/".stripslashes($row['vKBTitle']). "/".$row["nKBID"]."/KNOWLEDGEBASE/threeminus/threeplus";
                ?>
    <tr>
        <td align="left" style="background-color:#ffffff;">
            <a href="<?php echo $viewkbentry_seo_link?>" class="listing"><?php echo trimString(htmlentities($row["vKBTitle"]),80); ?></a>
        </td>
    </tr>

                <?php
                }


	}
        else{
            ?>

      <tr>
        <td align="left" style="background-color:#ffffff;">
            <?php
    echo MESSAGE_NO_RECORDS;
    ?>
        </td>
      </tr>
            <?php
}

      }
//}
}else{
      ?>

      <tr>
        <td align="left" style="background-color:#ffffff;">
            <?php
    echo MESSAGE_NO_RECORDS;
    ?>
        </td>
      </tr>
            <?php
}
        ?>


</table>
            </div>
        </td></tr>
    <tr>
        <td align="center" colspan="3" style="padding:10px 0; ">
           <input type="button" value=" Back " onClick = "window.location.href = '<?php echo SITE_URL; ?>'" class="button" style="margin: 0 0 10px 0">
        </td>
    </tr>
</table>

</div>


</form>
 <script type="text/javascript" src="./scripts/jquery.autocomplete_kbsearch.js"></script>
                                                        <script type="text/javascript">
                                                        $(document).ready(function(){
                                                            var site_url ='<?php echo SITE_URL?>';

                                                          $("#txtKbTitleSearch").autocomplete(site_url+"kb_search_home.php", {
                                                                        selectFirst: true


                                                                });

                                                                // getKbSearchdata();
                                                        });

                                                            function getKbSearchdata(){

                                                                var txtKbSearchid = $("#txtKbSearchid").val();
                                                                var dataString = {"txtKbSearchid":txtKbSearchid};

                                                                $.ajax({

                                                                    url			:"kb_search_home.php",

                                                                    type		:"POST",

                                                                    data		:dataString,

                                                                    dataType            : "html",

                                                                    success		:function(response){

                                                                        if(response!='')
                                                                        {
                                                                          //  alert(response);
                                                                            $("#kbSearchResult").html(response);
                                                                          //  $("#txt_kbSearchResult").val(response);
                                                                        }
                                                                        else
                                                                        {
                                                                           $("#kbSearchResult").html("No Result Found !");
                                                                        }


                                                                    }

                                                                });
                                                            }


                                                        </script>
                                                        <!--Show KnowlaGE Base ends-->













<input type="hidden" name="numBegin" value="<?php echo $var_numBegin; ?>">
			<input type="hidden" name="start" value="<?php echo $var_start; ?>">
			<input type="hidden" name="begin" value="<?php echo $var_begin; ?>">
			<input type="hidden" name="num" value="<?php echo $var_num; ?>">
			<input type="hidden" name="mt" value="y">
			<input type="hidden" name="stylename" value="<?php echo($var_stylename); ?>" >
			<input type="hidden" name="styleminus" value="<?php echo($var_styleminus); ?>">
			<input type="hidden" name="styleplus" value="<?php echo($var_styleplus); ?>">
			<input type="hidden" name="id" value="<?php echo($var_id); ?>">
			<input type="hidden" name="postback" value="">
			</form>
</div>