<?php
include "languages/".$_SP_language."/userkbsearck.php";
?>
 <script type="text/javascript" src="./scripts/jquery.autocomplete_kbsearch.js"></script>
                                                        <script type="text/javascript">
                                                        $(document).ready(function(){
                                                            var site_url ='<?php echo SITE_URL?>';

                                                          $("#txtKbTitleSearch").autocomplete("kb_search_home.php", {
                                                                        selectFirst: true


                                                                });

                                                                // getKbSearchdata();
                                                        });

                                                            function getKbSearchdata(){

                                                               
                                                            }


                                                        </script>
<form name="frmkbSearch" action="userkbsearchresult.php" method="post">
    <div class="left_item_block">
	<div class="left_item_title">
	<?php echo HEADER_KB;?>
</div>

	<div class="left_item_content">
<table  width="100%" border=0 cellpadding="0" cellspacing="0">
    <tr>
    </tr>
    <tr>
      <td colspan="2">
	  <?php echo TEXT_HOMEKNOWLEDGE?>
	<!--  The most frequently asked questions and the answers to them are stored in the knowledge base. Each department will have a category structure of its own, and the knowledge base entries will be added under the most fitting category.-->
	  </td>
    </tr>
    <tr>
        <td colspan="2"><h3><?php echo TEXT_SEARCH;?></h3></td>
    </tr>
    </br>
    <tr>
        <td>
            <input name="txtKbTitleSearch" id="txtKbTitleSearch" type="text" class="comm_input input_width3"  value="<?php echo htmlentities($var_cc);?>" >
            <input type="hidden" name="txtKbSearchid" id="txtKbSearchid">

        </td>
        <td><input type="submit" name="btnKbSearch" id="btnKbSearch" value="<?php echo TEXT_GO;?>" class="comm_btn2"></td>
    </tr>
    <tr>
        <td height="4" colspan="2">&nbsp;</td>
    </tr>
    <tr></tr>
     <tr><td colspan="4"> <!-- <input type="button" name="btnpostTicket" class="comm_btn3" value="Post Ticket" onClick="window.location.href='postticketbeforeregister.php'"> --> </td></tr>
    
</table>
      </div>

<div class="clear"></div>
</div>
</form>

                                                        <!--Show KnowlaGE Base ends-->




