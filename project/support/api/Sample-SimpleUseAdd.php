<?php

echo "Before user addition <br>";





//====================== Supportdesk User Addition ==================

include("useradd.php");		
userAdd('name','password','company-name','email-address');

//====================== End Supportdesk User Addition ==============



echo "After user addition <br>";


?>