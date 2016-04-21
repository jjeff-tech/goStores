<?php
$configfile = "../config/settings.php";

$configcontents = @fread(@fopen($configfile, 'r'), @filesize($configfile));
$pos = strpos($configcontents, "INSTALLED");
if ($pos === false) {
    header("location:install.php");
} else {
    header("location:../index.php");
}
?>