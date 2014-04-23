<!DOCTYPE html>
<head>
    <title>Loyola PAC Lab</title>
    <link rel="stylesheet" href="SavedAndReal.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> </script>
<script type="text/javascript" src="jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.navigate.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.selection.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.time.js"></script>    
<script language="javascript" type="text/javascript" src="jquery.flot.axislabels.js"></script>
<script language="javascript" type="text/javascript" src="jshashtable-2.1.js"></script>
<script language="javascript" type="text/javascript" src="jquery.numberformatter-1.2.3.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.symbol.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.navigate.js"></script>

    <script type="text/javascript">
        var activity1 = [], activity2 = [], activity3 = [], activity4 = [], activity5 = [], activity6 = [];
        var timeInSeconds = [];
        var amtData = 0;
        var activity = [];
        var xA = [], yA = [], zA = [];
        var xM = [], yM = [], zM = [];
        var xG = [], yG = [], zG = [];
        var XA = [], YA = [], ZA = [];
        var XM = [], YM = [], ZM = [];
        var XG = [], YG = [], ZG = [];
        var plots = [];
        var placeholders = $(".flot");
        var datasetA, datasetM, datasetG;
        var totalPoints = 100;
        var updateInterval = 1000;
        var now = new Date().getTime();
        var lw = 1.2;
        var count=0;
        var ticks = [
            [-1.9, "Lying"],
            [-1.2, "Wheeling"],
            [-0.4, "Walking"],
            [0.4, "Sitting"],
            [1.2, "Standing"],
            [1.9, "Misc"]
        ];
            
        var options1 = {
            series: {
                lines: {
                    show: true,
                    lineWidth: 10
                },
                shadowSize: 0
            },
            colors: ["#FFD700", "#87CEEB", "#DC143C", "#228B22", "#B8860B", "#A9A9A9"],
            xaxis: {
                font: { size: 8 },
                mode: "time",
                tickSize: [10, "second"],
                tickFormatter: function (v, axis) {
                    var date = new Date(v);
                    if (date.getSeconds() % 10 == 0) {
                        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
                        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
                        return hours + ":" + minutes + ":" + seconds;
                    } else {
                        return "";
                    }
                },
                zoomRange: [0.1, 1000000],
                panRange: [-1000000, 1000000]
            },
            yaxis: { zoomRange: [0.1, 0.1], panRange: [-1000000, 1000000], ticks: ticks },
            selection: { mode: "x"},
            zoom: { interactive: true },
            pan: { interactive: true },
            grid: {
                backgroundColor: {
                    colors: ["#ffffff", "#ffffff"]
                }
            }
        };

        var options2 = {
            xaxis: {
                font: { size: 8 },
                mode: "time",
                tickSize: [10, "second"],
                tickFormatter: function (v, axis) {
                    var date = new Date(v);
                    if (date.getSeconds() % 10 == 0) {
                        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
                        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
                        return hours + ":" + minutes + ":" + seconds;
                    } else {
                        return "";
                    }
                },
                zoomRange: [0.1, 1000000],
                panRange: [-1000000, 1000000]
            },
            yaxes: [
                {
                    min: -2,
                    max: 2,
                    zoomRange: [0.1, 0.1],
                    panRange: [-1000000, 1000000]
                },
                {
                    max: 5120,
                    position: "right",
                }
            ],
            legend: {
                noColumns: 0,
                position:"nw"
            },
            zoom: { interactive: true },
            pan: { interactive: true },
            grid: {      
                backgroundColor: { colors: ["#ffffff", "#ffffff"] }
            }
        };
 
        function initData() {
            $.getJSON('https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/all.json', function(data) {
                $.each(data, function(i, f) {
                   var createdAt=f.propertyMap._createdAt;
                   var dat = createdAt.split(" ");
                   var month = dat[0];
                   if (month == "Jan")
                   	   month = 1;
                   else if (month =="Feb")
                   	   month = 2;
                   else if (month =="Mar")
                   	   month = 3;
                   else if (month =="Apr")
                   	   month = 4;
                   else if (month =="May")
                   	   month = 5;
                   else if (month =="Jun")
                   	   month = 6;
                   else if (month =="Jul")
                   	   month = 7;
                   else if (month =="Aug")
                   	   month = 8;
                   else if (month =="Sep")
                   	   month = 9;
                   else if (month =="Oct")
                   	   month = 10;
                   else if (month =="Nov")
                   	   month = 11;
                   else if (month =="Dec")
                   	   month = 12;
                   var day = dat[1].substr(0,1);
                   var year = dat[2];
                   var time = dat[3].split(":");
                   var timeZone = dat[4];
                   if (timeZone == "PM")
                   	   time[0]+=12; 
                   var d = new Date (year,month,day,time[0],time[1],time[2]);
                   /*var seconds = time[3].split(":");
                   var second = parseInt(seconds[2], 10) + (parseInt(seconds[1], 10) * 60)+ (parseInt(seconds[0], 10) * 60*60);*/
                   for(var k=0; k<amtData; k++){ 
                   	   if (timeInSeconds[amtData]==d.getTime())
                   	   	   Break;
                   	   else{
                   timeInSeconds[amtData] = d.getTime();
                    xA[amtData] = f.propertyMap.XA;
                    yA[amtData] = f.propertyMap.YA;
                    zA[amtData] = f.propertyMap.ZA;
                    xM[amtData] = f.propertyMap.XM;
                    yM[amtData] = f.propertyMap.YM;
                    zM[amtData] = f.propertyMap.ZM;
                    xG[amtData] = f.propertyMap.XG;
                    yG[amtData] = f.propertyMap.YG;
                    zG[amtData] = f.propertyMap.ZG;
                    document.getElementById("p").innerHTML
                        ="time: "+timeInSeconds[amtData]+"<br>";
              
                    //activity[amtData] = f.Activity;
                    amtData++;
                   }
                   }
                });

                for (var t = 0; t <= amtData; t++) {
                  XA.push([timeInSeconds[t],xA[t]]);
                  YA.push([timeInSeconds[t],yA[t]]);
                  ZA.push([timeInSeconds[t],zA[t]]);
                  XM.push([timeInSeconds[t],xM[t]]);
                  YM.push([timeInSeconds[t],yM[t]]);
                  ZM.push([timeInSeconds[t],zM[t]]);
                  XG.push([timeInSeconds[t],xG[t]]);
                  YG.push([timeInSeconds[t],yG[t]]);
                  ZG.push([timeInSeconds[t],zG[t]]);
                 /* if (activity[t]=="Lying")
                    activity1.push([timeInSeconds[t], -1.9]);
                  else
                    activity1.push(null);
                  if (activity[t]=="Wheeling")
                    activity2.push([timeInSeconds[t], -1.2]);
                  else
                    activity2.push(null);
                  if (activity[t]=="Walking")
                    activity3.push([timeInSeconds[t], -0.4]);
                  else
                    activity3.push(null);
                  if (activity[t]=="Sitting")
                    activity4.push([timeInSeconds[t], 0.4]);
                  else
                    activity4.push(null);
                  if (activity[t]=="Standing")
                    activity5.push([timeInSeconds[t], 1.2]);
                  else
                    activity5.push(null);
                  if (activity[t]=="Misc")
                    activity6.push([timeInSeconds[t], 1.9]);
                  else
                    activity6.push(null);*/
                }
                
                
                    
                datasetA = [        
        { label: "X:"+ xA[amtData-1] , data: XA, lines:{ lineWidth:lw}},
        { label: "Y:"+ yA[amtData-1] , data: YA, lines: { lineWidth:lw}},
        { label: "Z:"+ zA[amtData-1] , data: ZA, lines: { lineWidth:lw}}
    ];
    
    datasetM = [        
        { label: "X:"+ xM[amtData-1], data: XM, lines:{ lineWidth:lw}},
        { label: "Y:"+ yM[amtData-1], data: YM, lines: { lineWidth:lw}},
        { label: "Z:"+ zM[amtData-1], data: ZM, lines: { lineWidth:lw}}
    ];
    
    datasetG = [        
        { label: "X:"+ xG[amtData-1], data: XG, lines:{ lineWidth:lw}},
        { label: "Y:"+ yG[amtData-1], data: YG, lines: { lineWidth:lw}},
        { label: "Z:"+ zG[amtData-1], data: ZG, lines: { lineWidth:lw}}
    ];
           // plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));
            plots.push($.plot(placeholder2, datasetA, options2));
            plots.push($.plot(placeholder3, datasetM, options2));
            plots.push($.plot(placeholder4, datasetG, options2));


            placeholders.bind("plotpan plotzoom", function (event, plot) {
              var axes = plot.getAxes();
              for (var i=0; i< plots.length; i++) {
                if (plot == plots[i])
                  continue;
                plots[i].getOptions().xaxes[0].min = axes.xaxis.min;
                plots[i].getOptions().xaxes[0].max = axes.xaxis.max;
                plots[i].getOptions().yaxes[0].min = axes.yaxis.min;
                plots[i].getOptions().yaxes[0].max = axes.yaxis.max;
                plots[i].setupGrid();
                plots[i].draw();
              }
            });
          });
 setTimeout(getData, updateInterval);
        }

        function getData() {
            $.ajaxSetup({ cache: false });
            $.ajax({
                url: "https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/all.json",
                dataType: 'json',
                success: update,
                error: function () {
                    setTimeout(getData, updateInterval);
                }
            });
        }

        function update(_data) {
        	
            activity1.shift();
            activity2.shift();
            activity3.shift();
            activity4.shift();
            activity5.shift();
            activity6.shift();
            XA.shift();
            YA.shift();
            ZA.shift();
            xM.shift();
            yM.shift();
            zM.shift();
            xG.shift();
            yG.shift();
            zG.shift();
            now += updateInterval;
           
            if (_data[amtData].propertyMap._createdAt=== 'undefined'){
                	 
            }else{
            	    
            	    var createdAt=f.propertyMap._createdAt;
                   var time = createdAt.split(" ");
                   var seconds = time[3].split(":");
                   var second = parseInt(seconds[2], 10) + (parseInt(seconds[1], 10) * 60)+ (parseInt(seconds[0], 10) * 60*60);
                    timeInSeconds[amtData] = second;
                       
                    
                    xA[amtData] = _data[amtData].propertyMap.XA;
                    yA[amtData] = _data[amtData].propertyMap.YA;
                    zA[amtData] = _data[amtData].propertyMap.ZA;
                    xM[amtData] = _data[amtData].propertyMap.XM;
                    yM[amtData] = _data[amtData].propertyMap.YM;
                    zM[amtData] = _data[amtData].propertyMap.ZM;
                    xG[amtData] = _data[amtData].propertyMap.XG;
                    yG[amtData] = _data[amtData].propertyMap.YG;
                    zG[amtData] = _data[amtData].propertyMap.ZG;
                    //activity[amtData] = _data.patientData[amtData].Activity;
          
           
           /* if (_data.Activity=="Lying")
                activity1.push([now, -1.9]);
            else
                activity1.push(null);
            if (_data.Activity=="Wheeling")
                activity2.push([now, -1.2]);
            else
                activity2.push(null);
            if (_data.Activity=="Walking")
                activity3.push([now, -0.4]);
            else
                activity3.push(null);
            if (_data.Activity=="Sitting")
                activity4.push([now, 0.4]);
            else
                activity4.push(null);
            if (_data.Activity=="Standing")
                activity5.push([now, 1.2]);
            else
                activity5.push(null);
            if (_data.Activity=="Misc")
                activity6.push([now, 1.9]);
            else
                activity6.push(null);

            XA.push([timeInSeconds[amtData], xA[amtData]]);
            YA.push([timeInSeconds[amtData], yA[amtData]]);
            ZA.push([timeInSeconds[amtData], zA[amtData]]);
            XM.push([timeInSeconds[amtData], xM[amtData]]);
            YM.push([timeInSeconds[amtData], yM[amtData]]);
            ZM.push([timeInSeconds[amtData], zM[amtData]]);
            XG.push([timeInSeconds[amtData], xG[amtData]]);
            YG.push([timeInSeconds[amtData], yG[amtData]]);
            ZG.push([timeInSeconds[amtData], zG[amtData]]);*/
                	
            datasetA = [
                { label: "X:" + _data.key[amtData].XA , data: XA, lines: { lineWidth: lw }},
                { label: "Y:" + _data.key[amtData].YA , data: YA, lines: { lineWidth: lw }},
                { label: "Z:" + _data.key[amtData].ZA , data: ZA, lines: { lineWidth: lw }}
            ];

            datasetM = [
                { label: "X:" + _data.key[amtData].XM , data: XM, lines: { lineWidth: lw }},
                { label: "Y:" + _data.key[amtData].YM , data: YM, lines: { lineWidth: lw }},
                { label: "Z:" + _data.key[amtData].ZM , data: ZM, lines: { lineWidth: lw }}
            ];

            datasetG = [
                { label: "X:" + _data.key[amtData].XG , data: XG, lines: { lineWidth: lw }},
                { label: "Y:" + _data.key[amtData].YG , data: YG, lines: { lineWidth: lw }},
                { label: "Z:" + _data.key[amtData].ZG , data: ZG, lines: { lineWidth: lw }}
            ];
            
            amtData++;
            
            plots.push($.plot(placeholder2, datasetA, options2));
            plots.push($.plot(placeholder3, datasetM, options2));
            plots.push($.plot(placeholder4, datasetG, options2));

            placeholders.bind("plotpan plotzoom", function (event, plot) {
                var axes = plot.getAxes();
                for (var i=0; i< plots.length; i++) {
                    plots[i].getOptions().xaxes[0].min = axes.xaxis.min;
                    plots[i].getOptions().xaxes[0].max = axes.xaxis.max;
                    plots[i].getOptions().yaxes[0].min = axes.yaxis.min;
                    plots[i].getOptions().yaxes[0].max = axes.yaxis.max;
                    plots[i].setupGrid();
                    plots[i].draw();
                }
            });
            getData();
            //setTimeout(getData, updateInterval);
          
        }
        }

        $(document).ready(function () {
            initData();
            setTimeout(getData, updateInterval);  // Every updateInterval milliseconds, run the getData function
        });
    </script>
</head>

<body>
    <div id="left">
        <script type="text/javascript">
            // Get patient ID, type and in lab/at home
            $(function() {
                $.getJSON('all.json', function(data) {
                    var obj = data[0];
            
                	document.getElementById("patient").innerHTML
                        ="Patient ID: "+obj.key.id+"<br>"+"Patient Name: "+obj.key.name+"<br>";
                });
            });
        </script>

        <p id="LoyolaPACLab">Loyola PAC Lab<br>
        <div id="p"></div></p>
        <div id="patient"></div></p>

        <p>Comments:
        <textarea rows="10" cols="45"></textarea></p>
    </div>

    <div id="right">
        <p><img src="luc-logo.png" alt="Loyola logo" align="right"></p><br><br><br><br>
       <!-- <div id="title">Activity</div><br>
        <div id="placeholder1" class="flot"></div><br> -->
        <div id="title">Accelerometer</div><br>
        <div id="placeholder2" class="flot"></div><br>
        <div id="title">Magnetometer</div><br>
        <div id="placeholder3" class="flot"></div><br>
        <div id="title">Gyroscope</div><br>
        <div id="placeholder4" class="flot"></div>
</body>
</html>