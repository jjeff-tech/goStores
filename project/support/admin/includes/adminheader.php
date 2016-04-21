<div class="lang_selector">
    <?php
    if ($_SESSION["sess_langchoice"] == "1") {
        $sql="Select vLangCode,vLangDesc from sptbl_lang order by vLangDesc ";
        $result=mysql_query($sql,$conn);
        ?>
    <form name="frmLanguage" action="adminmain.php" method="post">
        <label>
        <?php echo(TEXT_SELECT_LANGUAGE); ?></label>&nbsp;&nbsp;
        <select name="cmbLan" class="selectbox1" onChange="javascript:changeLanguage();">
                <?php
                if (mysql_num_rows($result) > 0) {
                    while($row = mysql_fetch_array($result)) {
                        echo("<option value=\"" . htmlentities($row["vLangCode"]) . "\">" . $row["vLangDesc"] . "</option>");
                    }
                }
                ?>
        </select>&nbsp;
        <script>
            var lc = '<?php echo($_SESSION["sess_language"]); ?>';
            if(document.frmLanguage.cmbLan)
                document.frmLanguage.cmbLan.value=lc;

            function changeLanguage() {
                document.frmLanguage.method="post";
                document.frmLanguage.postback.value ="CL";
                document.frmLanguage.submit();
            }
        </script>
        <input type="hidden" name="postback" value="">
    </form>
        <?php
    }
    ?>
</div>