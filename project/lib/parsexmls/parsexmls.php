<?php
Class parsexmls
{
	function parseXML(SimpleXMLElement $param)
        {
            global $retVal;
            if($param->children())
            {
                foreach($param->children() as $children)
                {
                        if($children->getName() == 'ITEM')
                        {
                            foreach($children->attributes() as $k => $v)
                            {
                                if($k == 'resourceid') $retVal[] = $v;
                            }
                        }
                        $this->parseXML($children);
                }
            }
        }

    // obtain the userid for the privacy purchased
    function parseUserXML(SimpleXMLElement $param)
    {
        global $privacyUserID;
        foreach($param->attributes() as $k => $v)
        {
            if($k == 'dbpuser') $privacyUserID = $v;
        }
    }
    
    //parse the domain renewal result
    function parseDomainRenewalResultXML(SimpleXMLElement $param)
    {
        $responseArr = array();
        foreach($param->children() as $children){
            if($children->getName() == 'result')
            {
                foreach($children->attributes() as $k => $v)
                {
                    $res1 = NULL;
                    if($k == 'code') {
                        $res1 = $v;
                        
                        

                    }
                }
                foreach($children->children() as $child){
                    $res2 = NULL;
                    if($child->getName() == 'msg')
                    {
                        $res2 = $child;
                        
                       
                    }
                }

                
                
            } else if($children->getName() == 'resdata'){
                foreach($children->children() as $child){
                    $res3 = NULL;
                    if($child->getName() == 'orderid')
                    {
                        $res3 = $child;
                        
                        
                    }
                }
                
            }

        }

       
        // This is to avoid the SimpleXMLElement from the array element
        // do not remove this formatting
        $newVal = $res1."#".$res2."#".$res3;
        $responseArr1 = explode("#",$newVal);
        $responseArr = array("code" => $responseArr1[0],
                              "msg" => $responseArr1[1],
                              "orderid" => $responseArr1[2]
                               );
        

        return $responseArr;

        
        
    } // End Function


    

}