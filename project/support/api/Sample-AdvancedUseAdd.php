<?php

//generate  request xml to send to the API
$request = "<?xml version=\"1.0\"?>
<users>
<function>add</function>
<values>
<username>tom</username>
<password>getmein</password>
<email>tom@mydomain.com</email>
<company>mydomain.com</company>
</values>
</users>
";


//decide the API URL 
$url = "http://yourhelpdeskinstallationfolder/api/usermanage.php";  


//send data to the API using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch);
curl_close($ch);

//Get result in xml format
print $result;

?> 