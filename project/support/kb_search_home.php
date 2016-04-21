<?php
include("config/settings.php");
include("includes/session.php");

if (!isset($_SESSION["sess_language"]) or ($_SESSION["sess_language"] == "")) {
    $_SP_language = "en";
} else {
    $_SP_language = $_SESSION["sess_language"];
}
include("includes/functions/dbfunctions.php");
include("includes/functions/miscfunctions.php");
include("includes/functions/impfunctions.php");
include("./includes/functions/functions.php");
include("includes/main_smtp.php");
include("./languages/" . $_SP_language . "/main.php");


$conn = getConnection();

$q=$_GET['q'];

if($_POST['txtKbSearchid']) {
    $txtKbSearchid = $_POST['txtKbSearchid'];
}
if($_POST['txtKbSearchTitle']) {
    $txtKbSearchTitle = $q;
}

// $txtUserid = $_REQUEST['txtUserid'];

// $txtTemplate = $_REQUEST['txtTemplate'];

// Auto Complete KB Title

if($q!='') {
    $my_data=mysql_real_escape_string($q);

    $sql = "select nKBID,vKBTitle from sptbl_kb where (vKBTitle LIKE '%$my_data%') AND vStatus ='A'";
    $result =  executeSelect($sql,$conn);

    if(mysql_num_rows($result)>0) {
        while($row=mysql_fetch_array($result)) {
            echo $row['nKBID']."~".$row['vKBTitle']."\n";
        }
    }
    else {
        //echo '0~'.MESSAGE_NO_RECORDS;
        echo '0~0';
        exit;
    }
}

// Search KB
if($txtKbSearchid!='') {
    $my_data=mysql_real_escape_string($txtKbSearchid);

    $sql = "select nKBID,vKBTitle,tKBDesc from sptbl_kb where (nKBID = '$my_data') AND   vStatus ='A'";
    $result =  executeSelect($sql,$conn);

    if(mysql_num_rows($result)>0) {
        $row=mysql_fetch_array($result);
        $textdisp = "<b>".TEXT_TITLE. " : " . $row['vKBTitle']."</b><br><br>";
        echo  $textdisp .= $row['tKBDesc']."<br/><br/>";

        $starRatingContent = getStarRatingContent($txtKbSearchid);
        echo $starRatingContent."<br/><br/>";

        include("./includes/releatedresults.php");
        getReleatedResults($row['vKBTitle'], $row['nKBID']);
        exit;

    }

}


exit;

?>

