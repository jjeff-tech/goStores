 <script src="https://charts.iscripts.com/ChartJs/chart-min.js"></script> 

<?php if(PageContext::$response->dashboardPanel2) {  ?>

<div class="section_list_view "> 

    <div class="row have-margin">
        <span class="legend">
                <div class="pad_left50"><?php  echo "Section : ".PageContext::$response->sectionName; ?></div>
        </span>
        <!-- graph display -->
            <div class="row-fluid general_content_boxes">
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Total Domain Registrations</p> 
                     <div id="">
                        
                        <canvas id='myChartd' ></canvas> 
                    </div>              
               </div>
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Total Users</p> 
                     <div id="">
                        <canvas id='myChartu' ></canvas> 
                    </div>              
               </div>
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Total Stores Created </p> 
                     <div id="">
                        <canvas id='myChart_3'></canvas> 
                    </div>              
               </div>
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Free Trials By Month</p> 
                     <div id="">
                        <canvas id='myChart_4' ></canvas> 
                    </div>              
               </div>
        </div>
       
       
                
                <?php for($rowLoop=1;$rowLoop<=PageContext::$response->dashboardRowCount;$rowLoop++) { ?>

                   <div class="row-fluid general_content_boxes">

                     <?php for($colLoop=1;$colLoop<=PageContext::$response->rowArray[$rowLoop]->columnCount;$colLoop++) {  ?>



            <div class="span4 <?php if(PageContext::$response->rowArray[$rowLoop]->columnCount>1) {  ?>span4 dboard_block1<?php } else {  ?>dboard_block2<?php } ?>">

                <?php if(PageContext::$response->columnConfig[$rowLoop][$colLoop]->display=="listing") {
                                ?>

                <table  id="" class="table  table-striped table-bordered table-hover " >

                    <span class="legend hdstyle"><?php echo PageContext::$response->columnConfig[$rowLoop][$colLoop]->title;?><?php if(PageContext::$response->columnConfig[$rowLoop][$colLoop]->titlelink) { ?><a class="btn btn-link" href="<?php echo PageContext::$response->columnTitleLink[$rowLoop][$colLoop];?>" ><?php echo PageContext::$response->columnConfig[$rowLoop][$colLoop]->titlelink;?></a><?php } ?></span>
                    <thead>

                        <tr>
                                            <?php //echopre(PageContext::$response->panelConfig[$rowLoop][$colLoop]->listcolumns);
                                            $columnCount = 0;
                                            foreach(PageContext::$response->columnConfig[$rowLoop][$colLoop]->listcolumns as $key=>$column) {
                                                ?>
                            <th class="table-header"><?php echo $column->name;?></th>
                                                <?php $columnCount ++;
                                            } ?>
                        </tr> </thead>
                    <tbody> <?php if (PageContext::$response->columnData[$rowLoop][$colLoop]) { ?>
                                            <?php
                                            foreach(PageContext::$response->columnData[$rowLoop][$colLoop] as $data) {


                                                ?><tr><?php

                                                    foreach(PageContext::$response->columnConfig[$rowLoop][$colLoop]->listcolumns as $key=>$column) {
                                                        ?><td class="wordWrap"><?php    echo $data->$key;
                                                            ?></td><?php
                                                    }
                                                        ?></tr><?php


                                                    ?>


                                                <?php  } ?>
                                            <?php } else { ?>
                        <tr><td colspan="<?php echo $columnCount;?>">     No Records Found</td></tr>
                                            <?php } ?>
                    </tbody>
                </table>

                                <?php } ?>
            </div><?php
                    }?>

        </div>

                <?php } ?>

        <!-- listing-->
    </div>
</div>


    <?php }  else { ?>
<div class="section_list_view ">

    <div class="row have-margin">
        <div class="tophding_blk">
            <span class="legend tophding_h3">
                <div class="pad_left50"><?php  echo "Section : ".PageContext::$response->sectionName; ?></div>
            </span>
        </div>
        <!-- graph display -->
            <div class="row-fluid general_content_boxes">
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Total Domain Registrations</p> 
                     <div id="">
                        
                        <canvas id='myChartd' ></canvas> 
                    </div>              
               </div>
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Total Users</p> 
                     <div id="">
                        <canvas id='myChartu' ></canvas> 
                    </div>              
               </div>
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Total Stores Created </p> 
                     <div id="">
                        <canvas id='myChart_3'></canvas> 
                    </div>              
               </div>
               <div class="span4 span4 dboard_block1">
                  <p class="graph_hdr2">Free Trials By Month</p> 
                     <div id="">
                        <canvas id='myChart_4' ></canvas> 
                    </div>              
               </div>
        </div>
        <!-- graph display END-->

        <!-- listing-->
            <?php for($rowLoop=1;$rowLoop<=PageContext::$response->listinPanelRow;$rowLoop++) {  ?>
        <div class="row-fluid general_content_boxes ">
                    <?php for($colLoop=1;$colLoop<=PageContext::$response->listingPanels[$rowLoop]->columnCount;$colLoop++) { ?>

            <div class="<?php if(PageContext::$response->listingPanels[$rowLoop]->columnCount>1) {  ?>span4 <?php if($colLoop==2) { ?>dboard_block1_rt<?php } else  {  ?>dboard_block1<?php } } else {  ?>dboard_block2<?php } ?>">
                <table  id="" class="table  table-striped table-bordered table-hover " >

                    <span class="legend hdstyle"><?php echo PageContext::$response->panelConfig[$rowLoop][$colLoop]->title;?><?php if(PageContext::$response->panelConfig[$rowLoop][$colLoop]->titlelink) { ?><a class="btn btn-link" href="<?php echo PageContext::$response->listTitleLink[$rowLoop][$colLoop];?>" ><?php echo PageContext::$response->panelConfig[$rowLoop][$colLoop]->titlelink;?></a><?php } ?></span>
                    <thead>

                        <tr>
                                        <?php //echopre(PageContext::$response->panelConfig[$rowLoop][$colLoop]->listcolumns);
                                        $columnCount = 0;
                                        foreach(PageContext::$response->panelConfig[$rowLoop][$colLoop]->listcolumns as $key=>$column) {
                                            ?>
                            <th class="table-header"><?php echo $column->name;?></th>
                                            <?php $columnCount ++;
                                        } ?>
                        </tr> </thead>
                    <tbody> <?php if (PageContext::$response->listDataArray[$rowLoop][$colLoop]) { ?>
                                        <?php
                                        foreach(PageContext::$response->listDataArray[$rowLoop][$colLoop] as $data) {


                                            ?><tr><?php

                                                foreach(PageContext::$response->panelConfig[$rowLoop][$colLoop]->listcolumns as $key=>$column) {
                                                    ?><td class="wordWrap"><?php    echo $data->$key;
                                                        ?></td><?php
                                                }
                                                    ?></tr><?php


                                                ?>


                                            <?php  } ?>
                                        <?php } else { ?>
                        <tr><td colspan="<?php echo $columnCount;?>">     No Records Found</td></tr>
                                        <?php } ?>
                    </tbody>
                </table>
            </div>
                        <?php } ?>
        </div>
                <?php } ?>
        <!-- listing-->
    </div>
</div>

    <?php } ?>

    <script type="text/javascript">
     
    var ctx = document.getElementById('myChartd').getContext('2d');
//alert(jArray1['arrData']);
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        //labels: ["hfd","hfd","hfd","hfd","hfd","hfd","hfd","hfd"],
        labels: [<?php echo PageContext::$response->categoryNames;?>],
        datasets: [{
           
           // data: [2,3,4,5,2,1,0,9],
            data: [<?php echo PageContext::$response->graphDomains;?>],
            lineTension: 0.3,
            fill: false,
            borderColor: '#007790',
            backgroundColor: ['#007790','#007790','#007790','#007790','#007790','#007790','#007790','#007790'],
           // pointBorderColor: 'red',
           
            borderWidth: 1
        }]
    },
     options: {
        layout: {
            padding: {
                left: 20,
                right: 0,
                top: 30,
                bottom: 0
            }
        },
        legend: {
            
                display: false,
                    
        },
        // tooltips: {
        //      mode: 'dataset'
        // },
         tooltips: {
      mode: 'index',
      intersect: false
   },
   hover: {
      mode: 'index',
      intersect: false
   },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    //stepSize : 1
                },
                scaleLabel: {
        display: true,
        labelString: "Domain Registration Count",
        fontColor:'black'
      },
      gridLines: {
                color: "rgba(0, 0, 0, 0)",
            }  
            }],
            xAxes: [{
                 scaleLabel: {
        display: true,
        labelString: 'Last 7 Days',
        fontColor:'black'
      }

            }]
        }
    }
});


var ctx1 = document.getElementById('myChartu').getContext('2d');

var myChart = new Chart(ctx1, {
     type: 'line',
    data: {
        labels: [<?php echo PageContext::$response->categoryNames;?>],
        datasets: [{
           
            data: [<?php echo PageContext::$response->graphUsers;?>],
            lineTension: 0.3,
            fill: false,
            borderColor: '#007790',
            backgroundColor: 'transparent',
           // pointBorderColor: 'red',
            pointBackgroundColor: '#007790',
            pointRadius: 1,
            pointHoverRadius: 5,
            pointHitRadius: 8,
            pointBorderWidth: 2,
            pointStyle: 'circle',
           
            borderWidth: 1
        }]
    },
     options: {
        layout: {
            padding: {
                left: 20,
                right: 0,
                top: 30,
                bottom: 0
            }
        },
        legend: {
            
                display: false,
                    
        },
        // tooltips: {
        //      mode: 'dataset'
        // },
         tooltips: {
      mode: 'index',
      intersect: false
   },
   hover: {
      mode: 'index',
      intersect: false
   },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    //stepSize : 1
                },
                scaleLabel: {
        display: true,
        labelString: "User Count",
        fontColor:'black'
      }
            }],
            xAxes: [{
                 scaleLabel: {
        display: true,
        labelString: 'Last 7 days',
        fontColor:'black'
      }

            }]
        }
    }
});


   
    var ctx2 = document.getElementById('myChart_3').getContext('2d');
//alert(jArray1['arrData']);
var myChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        //labels: ["hfd","hfd","hfd","hfd","hfd","hfd","hfd","hfd"],
        labels: [<?php echo PageContext::$response->categoryNames;?>],
        datasets: [{
           
           // data: [2,3,4,5,2,1,0,9],
            data: [<?php echo PageContext::$response->graphStores;?>],
            lineTension: 0.3,
            fill: false,
            borderColor: '#007790',
            backgroundColor: ['#007790','#007790','#007790','#007790','#007790','#007790','#007790','#007790'],
           // pointBorderColor: 'red',
           
            borderWidth: 1
        }]
    },
     options: {

        layout: {
            padding: {
                left: 20,
                right: 0,
                top: 30,
                bottom: 0
            }
        },
        legend: {
            
                display: false,
                    
        },
        // tooltips: {
        //      mode: 'dataset'
        // },
         tooltips: {
      mode: 'index',
      intersect: false
   },
   hover: {
      mode: 'index',
      intersect: false
   },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    //stepSize : 1
                },

                scaleLabel: {
        display: true,
        labelString: "Store Count",
        fontColor:'black'
      },
      gridLines: {
                color: "rgba(0, 0, 0, 0)",
                
            },

            }],
            xAxes: [{
                 scaleLabel: {
        display: true,
        labelString: 'Last 7 Days',
        fontColor:'black'
      },
      

            }]
        }
    }
});



 var ctx3 = document.getElementById('myChart_4').getContext('2d');
//alert(jArray1['arrData']);
var myChart = new Chart(ctx3, {
    type: 'bar',
    data: {
        //labels: ["hfd","hfd","hfd","hfd","hfd","hfd","hfd","hfd"],
        labels: [<?php echo PageContext::$response->categoryNames;?>],
        datasets: [{
           
           // data: [2,3,4,5,2,1,0,9],
            data: [<?php echo PageContext::$response->graphFreetrials;?>],
            lineTension: 0.3,
            fill: false,
            borderColor: '#007790',
            backgroundColor: ['#007790','#007790','#007790','#007790','#007790','#007790','#007790','#007790'],
           // pointBorderColor: 'red',
           
            borderWidth: 1
        }]
    },
     options: {
        layout: {
            padding: {
                left: 20,
                right: 0,
                top: 30,
                bottom: 0
            }
        },
        legend: {
            
                display: false,
                    
        },
        // tooltips: {
        //      mode: 'dataset'
        // },
         tooltips: {
      mode: 'index',
      intersect: false
   },
   hover: {
      mode: 'index',
      intersect: false
   },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                   //callback: function(value) {if (value % 1 === 0) {return value;}}
                    //stepSize : 1
                },
                scaleLineColor: "black",
                scaleLabel: {
        display: true,
        labelString: "Free Trials Count",
        fontColor:'black'
      },
      gridLines: {
                color: "rgba(0, 0, 0, 0)",
            }  
            }],
            xAxes: [{
                 scaleLabel: {
        display: true,
        labelString: 'Last 7 Days',
        fontColor:'black'
      }

            }]
        }
    }
});



</script>  

   
