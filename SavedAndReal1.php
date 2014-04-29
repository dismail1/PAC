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
        var updateInterval = 5000;
        var now = new Date().getTime();
        var lw = 1.2;
        var count=0;
        var url ='https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/all.json';
        var url2='https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/last-second.json';
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
            $.getJSON(url, function(data) {
                $.each(data, function(i, f) {
                   //https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/all.json?callback=?
                    if (f.propertyMap.XA ==='undefined')
                    	    timeInSeconds[amtData] =null;
                    else
                    	    timeInSeconds[amtData] =f.propertyMap.seconds ;
                    if (f.propertyMap.XA ==='undefined')
                    	    xA[amtData] = null;
                    else
                    	xA[amtData] = f.propertyMap.XA;
                    if (f.propertyMap.YA ==='undefined')
                    	yA[amtData] = null
                    else
                    	yA[amtData] = f.propertyMap.YA;
                    if (f.propertyMap.ZA ==='undefined')
                    	    zA[amtData] = null;
                    else
                    	zA[amtData] = f.propertyMap.ZA;
                    if (f.propertyMap.XM ==='undefined')
                    	    xM[amtData] =null;
                    else
                    	xM[amtData] = f.propertyMap.XM;
                    if (f.propertyMap.YM ==='undefined')
                    	    yM[amtData] = null;
                    else
                    	yM[amtData] = f.propertyMap.YM;
                    if (f.propertyMap.ZM ==='undefined')
                    	    zM[amtData] =null;
                    else
                    	zM[amtData] = f.propertyMap.ZM;
                    if (f.propertyMap.XG ==='undefined')
                    	    xG[amtData]  = null;
                    else
                    	xG[amtData] = f.propertyMap.XG;
                    if (f.propertyMap.YG ==='undefined')
                    	    yG[amtData] =null;
                    else
                    	yG[amtData] = f.propertyMap.YG;
                    if (f.propertyMap.ZG ==='undefined')
                    	    zG[amtData] = null;
                    else
                    	zG[amtData] = f.propertyMap.ZG;
                    activity[amtData] = f.propertyMap.Activity;
                    amtData++;
                  
                  
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
                  if (activity[t]=="Lying")
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
                    activity6.push(null);
                }
                
                
                    
                datasetA = [        
        { label: "X:"+ xA[0] , data: XA, lines:{ lineWidth:lw}},
        { label: "Y:"+ yA[0] , data: YA, lines: { lineWidth:lw}},
        { label: "Z:"+ zA[0] , data: ZA, lines: { lineWidth:lw}}
    ];
    
    datasetM = [        
        { label: "X:"+ xM[0], data: XM, lines:{ lineWidth:lw}},
        { label: "Y:"+ yM[0], data: YM, lines: { lineWidth:lw}},
        { label: "Z:"+ zM[0], data: ZM, lines: { lineWidth:lw}}
    ];
    
    datasetG = [        
        { label: "X:"+ xG[0], data: XG, lines:{ lineWidth:lw}},
        { label: "Y:"+ yG[0], data: YG, lines: { lineWidth:lw}},
        { label: "Z:"+ zG[0], data: ZG, lines: { lineWidth:lw}}
    ];
            plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));
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
                url: url,
                dataType: 'json',
                success: update,
                error: function () {
                    setTimeout(getData, updateInterval);
                }
            });
        }

        function update(_data) {
    
   

    now += updateInterval

    if (_data[0].propertyMap.Activity=="Lying")
                    activity1.push([now, -1.9]);
                  else
                    activity1.push(null);
                  if (_data[0].propertyMap.Activity=="Wheeling")
                    activity2.push([now, -1.2]);
                  else
                    activity2.push(null);
                  if (_data[0].propertyMap.Activity=="Walking")
                    activity3.push([now, -0.4]);
                  else
                    activity3.push(null);
                  if (_data[0].propertyMap.Activity=="Sitting")
                    activity4.push([now, 0.4]);
                  else
                    activity4.push(null);
                  if (_data[0].propertyMap.Activity=="Standing")
                    activity5.push([now, 1.2]);
                  else
                    activity5.push(null);
                  if (_data[0].propertyMap.Activity=="Misc")
                    activity6.push([now, 1.9]);
                  else
                    activity6.push(null);
                    
       
    temp = [now, _data[0].propertyMap.XA];
    XA.push(temp);

    temp = [now, _data[0].propertyMap.YA];
    YA.push(temp);

    temp = [now, _data[0].propertyMap.ZA];
    ZA.push(temp);

    datasetA = [
        { label: "X:" + _data[0].propertyMap.XA , data: XA, lines: { lineWidth: lw }},
        { label: "Y:" + _data[0].propertyMap.YA , data: YA, lines: { lineWidth: lw }},
        { label: "Z:" + _data[0].propertyMap.ZA , data: ZA, lines: { lineWidth: lw}}        
    ];
    
    temp = [now, _data[0].propertyMap.XM];
    XM.push(temp);

    temp = [now, _data[0].propertyMap.YM];
    YM.push(temp);

    temp = [now, _data[0].propertyMap.ZM];
    ZM.push(temp);
    
    datasetM = [
        { label: "X:" + _data[0].propertyMap.XM , data: XM, lines: { lineWidth: lw }},
        { label: "Y:" + _data[0].propertyMap.YM , data: YM, lines: { lineWidth: lw }},
        { label: "Z:" + _data[0].propertyMap.ZM , data: ZM, lines: { lineWidth: lw}}        
    ];
    
      temp = [now, _data[0].propertyMap.XG];
    XG.push(temp);

    temp = [now, _data[0].propertyMap.YG];
    YG.push(temp);

    temp = [now, _data[0].propertyMap.ZG];
    ZG.push(temp);
    
    datasetG = [
        { label: "X:" + _data[0].propertyMap.XG , data: XG, lines: { lineWidth: lw }},
        { label: "Y:" + _data[0].propertyMap.YG , data: YG, lines: { lineWidth: lw }},
        { label: "Z:" + _data[0].propertyMap.ZG , data: ZG, lines: { lineWidth: lw}}        
    ];
    
    plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));

    plots.push($.plot(placeholder2, datasetA, options2));
    plots.push($.plot(placeholder3, datasetM, options2));
    plots.push($.plot(placeholder4, datasetG, options2));
    placeholders.bind("plotpan plotzoom", function (event, plot) {
        var axes = plot.getAxes();
        for(var i=0; i< plots.length; i++) {
            plots[i].getOptions().xaxes[0].min = axes.xaxis.min;
            plots[i].getOptions().xaxes[0].max = axes.xaxis.max;
            plots[i].getOptions().yaxes[0].min = axes.yaxis.min;
            plots[i].getOptions().yaxes[0].max = axes.yaxis.max;
            plots[i].setupGrid();
            plots[i].draw();
        }
    });
    setTimeout(getData, updateInterval);
        /*	
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
           
            	    
            	    
                    timeInSeconds[0] = _data[0].propertyMap.seconds;
                    xA[0] = _data[0].propertyMap.XA;
                    yA[0] = _data[0].propertyMap.YA;
                    zA[0] = _data[0].propertyMap.ZA;
                    xM[0] = _data[0].propertyMap.XM;
                    yM[0] = _data[0].propertyMap.YM;
                    zM[0] = _data[0].propertyMap.ZM;
                    xG[0] = _data[0].propertyMap.XG;
                    yG[0] = _data[0].propertyMap.YG;
                    zG[0] = _data[0].propertyMap.ZG;
                    activity[0] = _data[0].propertyMap.Activity;
          
           
            if (_data.Activity=="Lying")
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

            XA.push([timeInSeconds[0], xA[0]]);
            YA.push([timeInSeconds[0], yA[0]]);
            ZA.push([timeInSeconds[0], zA[0]]);
            XM.push([timeInSeconds[0], xM[0]]);
            YM.push([timeInSeconds[0], yM[0]]);
            ZM.push([timeInSeconds[0], zM[0]]);
            XG.push([timeInSeconds[0], xG[0]]);
            YG.push([timeInSeconds[0], yG[0]]);
            ZG.push([timeInSeconds[0], zG[0]]);
                	
            datasetA = [
                { label: "X:" + _data[0].propertyMap.XA , data: XA, lines: { lineWidth: lw }},
                { label: "Y:" + _data[0].propertyMap.YA , data: YA, lines: { lineWidth: lw }},
                { label: "Z:" + _data[0].propertyMap.ZA , data: ZA, lines: { lineWidth: lw }}
            ];

            datasetM = [
                { label: "X:" + _data[0].propertyMap.XM , data: XM, lines: { lineWidth: lw }},
                { label: "Y:" + _data[0].propertyMap.YM , data: YM, lines: { lineWidth: lw }},
                { label: "Z:" + _data[0].propertyMap.ZM , data: ZM, lines: { lineWidth: lw }}
            ];

            datasetG = [
                { label: "X:" + _data[0].propertyMap.XG , data: XG, lines: { lineWidth: lw }},
                { label: "Y:" + _data[0].propertyMap.YG , data: YG, lines: { lineWidth: lw }},
                { label: "Z:" + _data[0].propertyMap.ZG , data: ZG, lines: { lineWidth: lw }}
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
          
        */
        }

        $(document).ready(function () {
            initData();
           // setTimeout(getData, updateInterval);  // Every updateInterval milliseconds, run the getData function
        });
    </script>
</head>

<body>
    <div id="left">
        <script type="text/javascript">
            // Get patient ID, type and in lab/at home
            $(function() {
                $.getJSON(url, function(data) {
                    var obj = data[0];
            
                	document.getElementById("patient").innerHTML
                        ="Patient ID: "+obj.key.id+"<br>"+"Patient Name: "+obj.key.name+"<br>"+"Where Is Device: "+obj.propertyMap.WhereIsDevice+"<br>";
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
        <div id="title">Activity</div><br>
        <div id="placeholder1" class="flot"></div><br> 
        <div id="title">Accelerometer</div><br>
        <div id="placeholder2" class="flot"></div><br>
        <div id="title">Magnetometer</div><br>
        <div id="placeholder3" class="flot"></div><br>
        <div id="title">Gyroscope</div><br>
        <div id="placeholder4" class="flot"></div>
</body>
</html>