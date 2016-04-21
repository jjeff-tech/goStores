<?php

        //function that gets the connection and selects the database
        function getConnection() {
                //values set in settings.inc
                global $glb_dbhost,$glb_dbuser,$glb_dbpass,$glb_dbname;

                $conn = mysql_connect($glb_dbhost,$glb_dbuser,$glb_dbpass) or die(mysql_error());
                mysql_select_db($glb_dbname,$conn) or die(mysql_error());
                return $conn;

        }


        //function to execute a select statement
        function executeSelect($sql,$conn) {

                $result = mysql_query($sql,$conn) or die(mysql_error());
                return $result;
        }

        //execute to execute a update/delete/insert statement
        function executeQuery($sql,$conn) {

                mysql_query($sql,$conn) or die(mysql_error());
                return true;
        }

        //returns the last inserted id
        function  getLastId($conn) {

                return mysql_insert_id($conn);

        }

        
?>