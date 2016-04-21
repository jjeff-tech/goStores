<?php

 $comp = $_GET["comp"];
 $page = ($_GET["page"] !='') ? $_GET["page"] : '';
 $url = "getChatIcon_det.php?comp=".$comp."&page=".$page; 
 header("location:".$url);
?>

