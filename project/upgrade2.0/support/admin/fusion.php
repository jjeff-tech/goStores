<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script language='javascript' src='JSClass/FusionCharts.js'></script>
<title>Untitled Document</title>
</head>
<?php

  # Include FusionCharts PHP Class
  if(include('Class/FusionCharts.php'))
       if(include('graph/graph.php'))
  	//echo "class included";
for($i=1;$i<=12;$i++){
$arrData[$i-1][0] = date("M", mktime(0, 0, 0, $i, 10));
$arrData[$i-1][1] = rand(1,100);
}
$graphObj3 = new graph(8);
$graphObj3->setChartParams("Upgradation Statistics");
$graphObj3->addChartData($arrData);

$graphObj3->renderChart();
?>
  <body>




  </body>
</html>
