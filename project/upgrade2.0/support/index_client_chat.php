<?php
   $comp = $_GET["comp"];
   $ref = $_GET["ref"];
   if ( $ref == 'visitorChat') header("location:client_prechat.php?comp=".$comp."");
   else header("location:invoke_chat.php?comp=".$comp."");
?>
