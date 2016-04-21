<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of supportpro supportdesk                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>   			                      |
// |                           											  |
// +----------------------------------------------------------------------+

require_once("./includes/applicationheader.php");
//include("./includes/functions/miscfunctions.php");
include("./languages/".$_SP_language."/assignfields.php");
$conn = getConnection();

?>
<?php include("../includes/docheader.php"); ?>

<title><?php echo HEADING_ASSIGN_FIELDS ?></title>
<?php include("./includes/headsettings.php"); ?>
<script language="javascript" type="text/javascript">
    <!--
    function saveMe(AssignStaff) { 
        var strValues = "";
        var boxLength = AssignStaff.cmbSelected.length;
        var count = 0;
        if(AssignStaff.cmbSelected.value=="0"){
            alert('<?php echo MESSAGE_JS_STAFF_SELECTION_ERROR; ?>');
            return false;
        }
        if (boxLength != 0) {
            for (i = 0; i < boxLength; i++) {
                if (count == 0) {
                    strValues = AssignStaff.cmbSelected.options[i].value;
                }
                else {
                    strValues = strValues + "," + AssignStaff.cmbSelected.options[i].value;
                } 
                count++;
            }
        }
        //if (strValues.length == 0) {
        //      alert('<?php echo MESSAGE_JS_STAFF_SELECTION_ERROR; ?>');
        //}
        //else {
        AssignStaff.tosave.value=strValues;
        //                              document.frmPvtMessage.postback.value="SM";
        AssignStaff.mt.value="u";
        AssignStaff.method="post";
        AssignStaff.submit();
        //}
    }

    function changestaff(){
        document.frmPvtMessage.postback.value="C";
        document.frmPvtMessage.method="post";
        document.frmPvtMessage.submit();

    }

    function alloted(AssignStaff){

        var i;
        var x;
        var arrSelected = new Array();
        var arrSelected_text = new Array();
        var arrSelected_index = new Array();
        var isNew = true;
        var count = 0;
        var del_rows=0;
        var boxLength = AssignStaff.cmbSelected.length;
        var av_boxLength = AssignStaff.cmbAvailable.length;
        for (i = 0; i < av_boxLength; i++) {

            if (AssignStaff.cmbAvailable.options[i].selected) {
                arrSelected_index[count]=i;
                arrSelected_text[count] = AssignStaff.cmbAvailable.options[i].text;
                arrSelected[count] = AssignStaff.cmbAvailable.options[i].value;
                count++;
            }

        }
        if(arrSelected.length<=0){
            alert('<?php echo MESSAGE_JS_SELECTION_ERROR; ?>');
            return false;
        }

        for (x = 0; x < arrSelected.length; x++) {
            newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
            AssignStaff.cmbSelected.options[boxLength] = newoption;

            boxLength++;
        }
        for (x = 0; x < arrSelected.length; x++) {
            AssignStaff.cmbAvailable.options[arrSelected_index[x]-del_rows]=null;
            del_rows++;
        }
        AssignStaff.cmbAvailable.selectedIndex=0;
    }
    function makeavailableall(AssignStaff){
        var i;
        var x;
        var arrSelected = new Array();
        var arrSelected_text = new Array();
        var arrSelected_index = new Array();
        var isNew = true;
        var count = 0;
        var del_rows=0;
        var boxLength = AssignStaff.cmbSelected.length;
        var av_boxLength = AssignStaff.cmbAvailable.length;
        for (i = 0; i < av_boxLength; i++) {
            arrSelected_index[count]=i;
            arrSelected_text[count] = AssignStaff.cmbAvailable.options[i].text;
            arrSelected[count] = AssignStaff.cmbAvailable.options[i].value;
            count++;
        }
        for (x = 0; x < arrSelected.length; x++) {
            newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
            AssignStaff.cmbSelected.options[boxLength] = newoption;
            boxLength++;
        }
        for (x = 0; x < arrSelected.length; x++) {
            AssignStaff.cmbAvailable.options[ arrSelected_index[x]-del_rows]=null;
            del_rows++;
        }
        AssignStaff.cmbAvailable.selectedIndex=-1;
    }
    function availbaletoalloted(AssignStaff) {
        var i;
        var x;
        var arrSelected = new Array();
        var arrSelected_text = new Array();
        var arrSelected_index = new Array();
        var isNew = true;
        var count = 0;
        var del_rows=0;
        var boxLength = AssignStaff.cmbAvailable.length;
        var choice_boxLength = AssignStaff.cmbSelected.length;
        for (i = 0; i < choice_boxLength; i++) {
            if (AssignStaff.cmbSelected.options[i].selected) {
                arrSelected_index[count]=i;
                arrSelected_text[count] = AssignStaff.cmbSelected.options[i].text;
                arrSelected[count] = AssignStaff.cmbSelected.options[i].value;
                count++;
            }

        }
        if(arrSelected.length<=0){
            alert('<?php echo MESSAGE_JS_SELECTION_ERROR; ?>');
            return false;
        }

        for (x = 0; x < arrSelected.length; x++) {
            newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
            AssignStaff.cmbAvailable.options[boxLength] = newoption;
            boxLength++;
        }
        for (x = 0; x < arrSelected.length; x++) {
            AssignStaff.cmbSelected.options[ arrSelected_index[x]-del_rows]=null;
            del_rows++;
        }
        AssignStaff.cmbSelected.selectedIndex=0;
    }

    function makeallottedall(AssignStaff) {
        var i;
        var x;
        var arrSelected = new Array();
        var arrSelected_text = new Array();
        var arrSelected_index = new Array();
        var isNew = true;
        var count = 0;
        var del_rows=0;
        var boxLength = AssignStaff.cmbAvailable.length;
        var choice_boxLength = AssignStaff.cmbSelected.length;
        for (i = 0; i < choice_boxLength; i++) {
            arrSelected_index[count]=i;
            arrSelected_text[count] = AssignStaff.cmbSelected.options[i].text;
            arrSelected[count] = AssignStaff.cmbSelected.options[i].value;
            count++;
        }
        for (x = 0; x < arrSelected.length; x++) {
            newoption = new Option(arrSelected_text[x], arrSelected[x], false, false);
            AssignStaff.cmbAvailable.options[boxLength] = newoption;
            boxLength++;
        }
        for (x = 0; x < arrSelected.length; x++) {
            AssignStaff.cmbSelected.options[ arrSelected_index[x]-del_rows]=null;
            del_rows++;
        }
    }

    -->
</script>
</head>

<body>
    <!--  Top Part  -->
    <?php
    include("./includes/top.php");
    ?>
    <!--  Top Ends  -->

    <!--  Top links  -->

    <?php
//                 include("./includes/toplinks.php");
    ?>

    <!--  End Top Links -->

    <!-- header  -->
    <?php
    include("./includes/header.php");
    ?>
    <!-- end header -->

    <div class="content_column_small">

        <!-- sidelinks -->
        <?php
        include("./includes/staffside.php");
        ?>
        <!-- End of side links -->


    </div>


    <div class="content_column_big">


        <!-- admin header -->
        <?php
        //include("./includes/staffheader.php");
        ?>
        <!--  end admin header -->
        <!-- Private Message Section -->

        <?php
        include("./includes/assignfields.php");
        ?>

        <!-- End Private Message section -->

    </div>




    <!-- Main footer -->
    <?php
    include("./includes/mainfooter.php");
    ?>
    <!-- End Main footer -->

</body>
</html>