<?php
/*
 * All User Entity Logics should come here.
*/
class Headerredirect {

    public static function  httpRedirect($url){
        header("location:".$url);
    }
   
} //End Class


?>