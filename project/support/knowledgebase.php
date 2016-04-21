<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>                                  |
// +----------------------------------------------------------------------+
require_once("./includes/applicationheader.php");
include "languages/".$_SP_language."/knowledgebase.php";

if(!userLoggedIn()) {
    require_once("./includes/withoutlogindata.php");
}

$conn = getConnection();

?>

<?php
include("./includes/docheader.php");
include("./includes/functions/functions.php");
?>

<title><?php echo HEADER_KB;?></title>
<?php include("./includes/headsettings.php"); ?>

<script language="javascript" src="<?php echo SITE_URL; ?>scripts/jquery.js"></script>
<script language="javascript" src="<?php echo SITE_URL; ?>scripts/jquery-rating.js"></script>
<script language="javascript" src="<?php echo SITE_URL; ?>scripts/rating.js"></script>
<script language="javascript" src="<?php echo SITE_URL; ?>scripts/kb.js"></script>
<script language="javascript" >
    function validateKbKey()
    {
        var base_url = '<?php echo SITE_URL; ?>';
        var txtSearch = $("#txtKbTitleSearch").val();
        frmkbSearch.action =base_url+'kb/search?q='+urlencode(txtSearch);
        return true;
    }
    
</script>

</head>
<body>
    <!--  Top Part  -->
    <?php
    include("./includes/top.php");
    ?>
    <!--  Top Ends  -->
    <!-- header  -->
    <?php
    include("./includes/header.php");
    ?>
    <!-- end header -->

    <div class="content_column_small">
        <!-- sidelinks -->
        <?php
        include("./includes/userside.php");
        include("./includes/kb_categories.php");
        ?>
        <!-- End of side links -->
    </div>


    <div class="content_column_big">

        <!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% Detail Section %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
        <?php
        include("./includes/knowledgebase.php");
        ?>

    </div>

    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>