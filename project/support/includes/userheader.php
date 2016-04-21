<div class="lang_selector">

    <?php 
    if ($_SESSION["sess_langchoice"] == "1") {
        $lang = getSettingsValue('DefaultLang');
        $_SESSION["sess_language"] = $lang;
        //$sql="Select vLangCode,vLangDesc from sptbl_lang order by vLangDesc ";
        //$result=mysql_query($sql,$conn);
        ?>
    <!--form name="frmLanguage" action="<?php //echo SITE_URL;?>index.php" method="post">

        <label>
                <?php //echo(TEXT_SELECT_LANGUAGE); ?></label>&nbsp;&nbsp;
                <select name="cmbLan" class="selectbox1" onChange="javascript:changeLanguage();" style="width:80px;">
                <?php
                /*
                if (mysql_num_rows($result) > 0) {
                    while($row = mysql_fetch_array($result)) {
                        echo("<option value=\"" . htmlentities($row["vLangCode"]) . "\">" . $row["vLangDesc"] . "</option>");
                    }
                } */
                ?>
                </select>&nbsp;
        <script>
            var lc = '<?php //echo($_SESSION["sess_language"]); ?>';
            document.frmLanguage.cmbLan.value=lc;

            function changeLanguage() {
                document.frmLanguage.method="post";
                document.frmLanguage.post_back.value ="CL";
                document.frmLanguage.submit();
            }
        </script>
        <input type="hidden" name="post_back" value="">
    </form-->
        <?php
    }
    ?>

</div>