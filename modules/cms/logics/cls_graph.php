<?php 
/*
 * All User Entity Logics like authentication,doLogin,Registeruser,forgot password are coming here.
*/
class Graph {

    function getTimeflag($fromDate='',$toDate='') {
        $timeFlag=0;
        $loopCounter=0;
        $loopValue='';
        $timeDuration=strtotime($toDate)-strtotime($fromDate);

        $timeDuration=$timeDuration/(24*60*60);
        if($timeDuration<=7) {
            $loopCounter=$timeDuration;
            $loopValue='Day';
            $timeFlag=1;
        }
        elseif($timeDuration>7 && $timeDuration<=28) {
            $loopCounter=$timeDuration/7;
            $loopValue='Week';
            $timeFlag=2;
        }
        else if($timeDuration>28 && $timeDuration<=365) {
            $loopCounter=$timeDuration/30;
            $loopValue='Month';
            $timeFlag=3;
        }
        else {

            $loopValue='Year';
            $timeFlag=4;

        }
        return $timeFlag;
    }
    public static function plotGraph($fromDate,$toDate,$graphConfig) {


        $graphType              =   $graphConfig->type;
        $graphWidth             =   $graphConfig->width;
        $graphHeight            =   $graphConfig->height;
        $graphxAxisName         =   $graphConfig->xAxisName;
        if($graphxAxisName)
            $chartParams        .=   "xAxisName=".$graphxAxisName.";";
        $graphyAxisName         =   $graphConfig->yAxisName;
        if($graphyAxisName)
            $chartParams        .=   "yAxisName=".$graphyAxisName.";";
        $graphCaption           =   $graphConfig->caption;
//        if($graphCaption)
//            $chartParams        .=   "caption=".$graphCaption.";";


        $FC = new FusionCharts($graphType,$graphWidth,$graphHeight,"",1);
        # Set Relative Path of swf file.
        $FC->setSwfPath(BASE_URL."public/fusioncharts/FusionCharts/");
        #Store the chart attributes in a variable
        $strParam=$chartParams." YAxisMaxValue=10;YAxisMinValue=0;numdivlines=1;canvasBgColor=FFFFFF;borderColor=0;showPlotBorder=0;showValues=0;showNames=1;canvasBorderColor=FFFFFF;bgAlpha=0,0;canvasBgAlpha=0;showBorder=0;divLineDashLen=2;divLineIsDashed=1;formatNumber=0;";
        $timeFlag    =    Graph::getTimeflag($fromDate,$toDate);
        # Create combination chart object


        # Set chart attributes
        $FC->setChartParams($strParam);

        if($graphType!="Pie3D") {

        $endDate            =   strtotime($fromDate);
        $toDate1             =   strtotime($toDate);
        $flag               =   0;
        $counter            =   0;
        while($endDate<=$toDate1) {

            if($timeFlag==1) {
                $startDate  =   $endDate;
                $endDate    =   $startDate+(24*60*60);
                $dateStr=$counter++;
                $dateStr1=date("D",$startDate);
            }
            elseif($timeFlag==2) {
                $startDate=$endDate;
                $endDate=$startDate+(7*24*60*60);
                $dateStr1=date("d M",$startDate)."-".date("d M",$endDate);
                $dateStr=date("d",$startDate)."-".date("d",$endDate);
                $dateStr=$counter++;
//                        $dateStr=date("d M",$endDate);
            }
            elseif($timeFlag==3) {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate)+1,1,date("Y",$startDate));//$startDate+(30*24*60*60);
                $dateStr=date("M",$startDate);
                $dateStr1=date("M",$startDate);
                $dateStr=$counter++;
            }
            else {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate),date("d",$startDate),date("Y",$startDate)+1);//$startDate+(30*24*60*60);
                $dateStr=date("Y",$startDate);
                $dateStr1=date("Y",$startDate);
                $dateStr=$counter++;
            }


            if($endDate>$toDate1) {
                $endDate=$toDate1;

                $FC->addCategory($dateStr1);

                break;
            }


            //2010-12-01,2010-12-21

            $FC->addCategory($dateStr1);

            }
//echopre($dateStr1);
        }

        
        $dataSets       =   $graphConfig->dataSets;
        $dataSetsCount  =   $graphConfig->dataSetsCount;

        for($loop=1;$loop<=$dataSetsCount;$loop++) {
            $dataSet=   "dataset".$loop;
            $color  =   $dataSets->$dataSet->color;
            if($graphType=="Pie3D") {

                $customAction   =   $dataSets->$dataSet->fetchValue;
                $val= call_user_func($customAction);
                $FC->addChartDataFromArray($val, "", "","");
            }
            else {
            if($color=="")
                $Linecolor= "color=#464646;";
            else
                $Linecolor= "color=$color;";
            
            if($dataSets->$dataSet->parentYAxis)
                    $axis   =   "parentYAxis=".$dataSets->$dataSet->parentYAxis.";";
            if($dataSets->$dataSet->renderAs)
                    $renderAs   =   "renderAs=".$dataSets->$dataSet->renderAs.";";
                if($renderAs=="Line" || $graphType=="MSLine")
                    $lineParams =   "anchorSides=4;anchorBgColor=$color;anchorBorderColor=$color;anchorRadius=3;lineThickness=1;";
                $FC->addDataset($dataSets->$dataSet->name,$Linecolor.$axis.$renderAs.$lineParams);
                $customAction   =   $dataSets->$dataSet->fetchValue;
                self::populateGraph($FC,$customAction, strtotime($fromDate),strtotime($toDate),$timeFlag);
            }
        }






        // return $FC;
        # Render the chart
        return $FC;

    }

public static function getDomains($fromDate="",$toDate="",$graphConfig="") {
       
        
         $timeFlag    =    Graph::getTimeflag($fromDate,$toDate);
         $plotArr = array();
         $customAction   =   $graphConfig;
         $plotArr[] = self::populateDomainGraph($customAction, strtotime($fromDate),strtotime($toDate),$timeFlag);
        
         return $plotArr;
    }

 public static function getUsers($fromDate="",$toDate="",$graphConfig="") {
       
        
         $timeFlag    =    Graph::getTimeflag($fromDate,$toDate);
         $plotArr = array();
         $customAction   =   $graphConfig;
         $plotArr[] = self::populateDomainGraph($customAction, strtotime($fromDate),strtotime($toDate),$timeFlag);
        
         return $plotArr;
    }
    public static function getStores($fromDate="",$toDate="",$graphConfig="") {
       
        
         $timeFlag    =    Graph::getTimeflag($fromDate,$toDate);
         $plotArr = array();
         $customAction   =   $graphConfig;
         $plotArr[] = self::populateDomainGraph($customAction, strtotime($fromDate),strtotime($toDate),$timeFlag);
        
         return $plotArr;
    }
    public static function getFreetrials($fromDate="",$toDate="",$graphConfig="") {
       
        
         $timeFlag    =    Graph::getTimeflag($fromDate,$toDate);
         $plotArr = array();
         $customAction   =   $graphConfig;
         $plotArr[] = self::populateDomainGraph($customAction, strtotime($fromDate),strtotime($toDate),$timeFlag);
        
         return $plotArr;
    }
function populateDomainGraph($sourceFunction='',$fromDate='',$toDate='',$timeFlag='') 
    {
           

        $valArr = array();
        
        $startDate=$fromDate;
        $endDate=$fromDate;
        $flag=0;


        $counter=0;

        while($endDate<=$toDate) {
            if($timeFlag==1) {
                $startDate=$endDate;
                $endDate=$startDate+(24*60*60);
                $dateStr=date("M d, Y",$startDate);
                $counter++;
            }
            elseif($timeFlag==2) {
                $startDate=$endDate;
                $endDate=$startDate+(7*24*60*60);
                $dateStr=date("d M",$startDate)."-".date("d M",$endDate);
                $counter++;
            }
            elseif($timeFlag==3) {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate)+1,1,date("Y",$startDate));//$startDate+(30*24*60*60);
                $dateStr=date("F Y",$startDate);
                $counter++;
            }
            else {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate),date("d",$startDate),date("Y",$startDate)+1);//$startDate+(30*24*60*60);
                $dateStr=date("Y",$startDate);
                $counter++;

            }
            
             $val= call_user_func($sourceFunction,date("Y-m-d",$startDate),date("Y-m-d",$endDate));
            
            

            if($val=="NULL" || $val=="") {
                $val=0;
            }
            else
                array_push($valArr,$val);


        }
        // if($type=='store'){
        // ///echo"store-->";echopre1($valArr);
        // }
        return $valArr;
    }
    
    public static  function setMSCategory($FC,$fromDate,$toDate,$timeFlag) {


        $endDate            =   $fromDate;
        $flag               =   0;
        $counter            =   0;
        while($endDate<=$toDate) {

            if($timeFlag==1) {
                $startDate  =   $endDate;
                $endDate    =   $startDate+(24*60*60);
                $dateStr=$counter++;
                $dateStr1=date("D",$startDate);
            }
            elseif($timeFlag==2) {
                $startDate=$endDate;
                $endDate=$startDate+(7*24*60*60);
                $dateStr1=date("d M",$startDate)."-".date("d M",$endDate);
                $dateStr=date("d",$startDate)."-".date("d",$endDate);
                $dateStr=$counter++;
//                        $dateStr=date("d M",$endDate);
            }
            elseif($timeFlag==3) {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate)+1,1,date("Y",$startDate));//$startDate+(30*24*60*60);
                $dateStr=date("M",$startDate);
                $dateStr1=date("M",$startDate);
                $dateStr=$counter++;
            }
            else {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate),date("d",$startDate),date("Y",$startDate)+1);//$startDate+(30*24*60*60);
                $dateStr=date("Y",$startDate);
                $dateStr1=date("Y",$startDate);
                $dateStr=$counter++;
            }


            if($endDate>$toDate) {
                $endDate=$toDate;

                $FC->addCategory("$dateStr1");

                break;
            }


            //2010-12-01,2010-12-21

            $FC->addCategory("$dateStr1");



        }
        return $FC;

    }
    function populateGraph($FC,$sourceFunction='',$fromDate='',$toDate='',$timeFlag='') {

        $startDate=$fromDate;
        $endDate=$fromDate;
        $flag=0;


        $counter=0;

        while($endDate<=$toDate) {
            if($timeFlag==1) {
                $startDate=$endDate;
                $endDate=$startDate+(24*60*60);
                $dateStr=date("M d, Y",$startDate);
                $counter++;
            }
            elseif($timeFlag==2) {
                $startDate=$endDate;
                $endDate=$startDate+(7*24*60*60);
                $dateStr=date("d M",$startDate)."-".date("d M",$endDate);
                $counter++;
            }
            elseif($timeFlag==3) {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate)+1,1,date("Y",$startDate));//$startDate+(30*24*60*60);
                $dateStr=date("F Y",$startDate);
                $counter++;
            }
            else {
                $startDate=$endDate;
                $endDate=mktime(0,0,0,date("m",$startDate),date("d",$startDate),date("Y",$startDate)+1);//$startDate+(30*24*60*60);
                $dateStr=date("Y",$startDate);
                $counter++;

            }



            $val= call_user_func($sourceFunction,date("Y-m-d",$startDate),date("Y-m-d",$endDate));
            
            if($val=="NULL" || $val=="") {
                $val=0;
            }
            //$val=number_format($val,0,'.',',');

            $FC->addChartData((int)$val,"toolText= $toolText");

//echopre($val);


        }

//echopre($FC);
    }

}

?>