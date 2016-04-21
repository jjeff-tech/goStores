<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
$filePath ="./FCKeditor/editor/filemanager/connectors/php/config.txt"; // Temp config file
$currentPath = "/supportdesk/fckeditorimages/"; // Current path
$dynamicPath = "/qa2/supportdesk/fckeditorimages/"; //Dynamic path
// Read config file
$FCK_Config_Image_content = file_get_contents($filePath ,true);
// Replace path
$FCK_Config_Image_content =str_replace($currentPath,$dynamicPath, $FCK_Config_Image_content);
$FCK_Config_Image_content = '<?php ' . $FCK_Config_Image_content . '?>';
$filePath ="./FCKeditor/editor/filemanager/connectors/php/config.php";
$fp = fopen($filePath, 'w');
// Write file
fwrite($fp, $FCK_Config_Image_content);
fclose($fp);
?>
