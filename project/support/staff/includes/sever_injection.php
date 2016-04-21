<?php 

$excludeFilelist=array("replies.php","postticket.php","postticketkb.php");
$mystring = strtoupper($_SERVER['QUERY_STRING']);

$postvalues = $_REQUEST;
//$postvalues = array('1'=>array('a'=>array('b'=>array('1'=>'p'))),'2'=>array('b'=>'q'),'3'=>'z');

multi_implode($postvalues, '',$excludeFilelist);

function multi_implode($array, $glue,$excludeFilelist) {
    $ret = '';

    foreach($array as $postval ){
        
        if (is_array($postval)) {
            $ret = multi_implode($postval, '');
        } else {
            $ret = $postval;
        }//end else
	
        if (!in_array(basename($_SERVER['PHP_SELF']),$excludeFilelist))
        {
        	if (stripos($ret, "SELECT") !== false || strpos($ret, "UNION") !== false) {
                header('location:index.php');
                exit();
            }//end if
        }

    }//end foreach

    //$ret = substr($ret, 0, 0-strlen($glue));
    
}//end function


/****** Old Function commented due to recursive child array issue ******/

/*foreach($postvalues as $postval ){
     if(stripos($postval,"SELECT")!== false || strpos($postval,"UNION")!== false){
        header('location:index.php');
	exit();
  }

}*/
//exit;
if (!in_array(basename($_SERVER['PHP_SELF']),$excludeFilelist)) {
	$server_injec1=strpos($mystring, 'SELECT');
	$server_injec2=strpos($mystring, 'UNION');
} else {
	$server_injec1='0';
	$server_injec2='0';
}

if (($server_injec1 === false) && ($server_injec2 === false) || ($server_injec1 === '0') && ($server_injec2 === '0')) 
{
	;
}//end if
else
{
	header('location:index.php');
	exit();
}//end else
?>