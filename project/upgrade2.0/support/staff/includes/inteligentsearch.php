<style>
    .topsearch_label{
        padding:0 0 5px 0;
        font-weight:bold;
    }
    .topsearch_btn{
        background-color:#000000;
        font-family:Arial, Helvetica, sans-serif;
        font-size:12px;
        color:#FFF;
        padding:6px 10px 6px 10px;
        font-weight:bold;
        border:none;
        border-radius:3px;
    }
    .topsearch_input {
        background-color: #FFFFFF;
        border: 1px solid black;
        border-radius: 3px 3px 3px 3px;
        color: #666666;
        font-family: Arial,Helvetica,sans-serif;
        font-size: 12px;
        padding: 6px 10px;
    }

</style>



<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script language="javascript" type="text/javascript">
    function getInteligentSearch() {
        if(document.frmDetail.txtinteligentsearch.value == ""){
            alert("<?php echo TEXT_SEARCH_REQUIRE; ?>")
            document.frmDetail.txtinteligentsearch.focus();
        }else{
            document.frmDetail.numBegin.value="";
            document.frmDetail.num.value="";
            document.frmDetail.start.value="";
            document.frmDetail.begin.value="";
            document.frmDetail.method="post";
            document.frmDetail.action="inteligentsearchresult.php";
            document.frmDetail.submit();
        }
    }
</script>
<form name="frminteligentsearch" id="frminteligentsearch" action="<?php echo SITE_URL ?>staff/inteligentsearchresult.php" method="post">
    <div class="topsearch_label"><?php echo INTELIGENTSEARCH; ?></div>
    <div class="topmargin"><input type="text" name="txtinteligentsearch" id="txtinteligentsearch" class="topsearch_input width1" value="<?php echo $_POST['txtinteligentsearch']; ?>">
    <!--<input type="button" name="btninteligentsearch" id="btninteligentsearch" value="<?php echo HEADING_SEARCH; ?>" class="comm_btn" onclick="getInteligentSearch()">-->
        <input type="submit" name="btninteligentsearch" id="btninteligentsearch" value="<?php echo HEADING_SEARCH; ?>" class="topsearch_btn">
    </div>
</form>