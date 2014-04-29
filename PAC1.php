<!DOCTYPE html>
<head>
    <title>Loyola PAC Lab</title>
    <link rel="stylesheet" href="PAC.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> </script>
    <script language="javascript" type="text/javascript" src="jquery.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.navigate.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.selection.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.time.js"></script>    

    <script type="text/javascript">
        $(function () {
            var timeInSeconds = new Array();
            var xA = [], yA = [], zA = [];
            var xM = [], yM = [], zM = [];
            var xG = [], yG = [], zG = [];
            var XA = [], YA = [], ZA = [];
            var XM = [], YM = [], ZM = [];
            var XG = [], YG = [], ZG = [];
            var activity = new Array();
            var d1 = [];
            var d2 = [];
            var d3 = [];
            var d4 = [];
            var d5 = [];
            var d6 = [];
            var amtData = 0;
            var url ='https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/all.json';

            $.getJSON(url, function(data) {
                $.each(data, function(i, f) {
                     
                     timeInSeconds[amtData] = f.propertyMap.seconds;
                    if (f.propertyMap.XA === 'undefined' )
                    	    xA[amtData] = null;
                    else
                    	xA[amtData] = f.propertyMap.XA;
                    if (f.propertyMap.YA === 'undefined')
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
                d1.push([timeInSeconds[t], -1.9]);
              else
                d1.push(null);
              if (activity[t]=="Wheeling")
                d2.push([timeInSeconds[t], -1.2]);
              else
                d2.push(null);
              if (activity[t]=="Walking")
                d3.push([timeInSeconds[t], -0.4]);
              else
                d3.push(null);
              if (activity[t]=="Sitting")
                d4.push([timeInSeconds[t], 0.4]);
              else
                d4.push(null);
              if (activity[t]=="Standing")
                d5.push([timeInSeconds[t], 1.2]);
              else
                d5.push(null);
              if (activity[t]=="Misc")
                d6.push([timeInSeconds[t], 1.9]);
              else
                d6.push(null);
            }

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
                mode: "time",
                tickSize: [1, "second"],
                tickFormatter: function (v, axis) {
                    var date = new Date(v);
                    if (date.getSeconds() % 1 == 0) {
                        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
                        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
                        return hours + ":" + minutes + ":" + seconds;
                    } else {
                        return "";
                    }
                },  zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000] },
                yaxis: { zoomRange: [0.1, 0.1], panRange: [-1000000, 1000000], ticks: ticks },
                selection: { mode: "x"},
                zoom: { interactive: true },
                pan: { interactive: true },
                grid: {
                    backgroundColor: {
                      colors: ["#fff", "#eee"]
                    }
                }
            };
                     
            var options2 = {
              series: {
                lines: {show: true},
                shadowSize: 0
              },
              xaxis: { 
                mode: "time",
                tickSize: [1, "second"],
                tickFormatter: function (v, axis) {
                    var date = new Date(v);
                    if (date.getSeconds() % 1 == 0) {
                        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
                        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
                        return hours + ":" + minutes + ":" + seconds;
                    } else {
                        return "";
                    }
                },zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000] },
              yaxis: { zoomRange: [0.1, 0.1], panRange: [-1000000, 1000000] },
              selection: { mode: "x"},
              zoom: { interactive: true },
              pan: { interactive: true },
              grid: {
                backgroundColor: {
                  colors: ["#fff", "#eee"]
                }
              }
            };

            var plots = [];
            var placeholders = $(".flot");
            plots.push($.plot(placeholder1, [d1, d2, d3, d4, d5, d6], options1));
            plots.push($.plot(placeholder2, [XA, YA, ZA], options2));
            plots.push($.plot(placeholder3, [XM, YM, ZM], options2));
            plots.push($.plot(placeholder4, [XG, YG, ZG], options2));

            placeholders.bind("plotselected", function (event, ranges) {
              plot = $.plot(placeholder1, [d1, d2, d3, d4, d5, d6], $.extend(true, {}, options1, {
                xaxis: {
                  min: ranges.xaxis.from,
                  max: ranges.xaxis.to
                },
                yaxis: { ticks: ticks }
              }));
              plot = $.plot(placeholder2, [XA, YA, ZA], $.extend(true, {}, options2, {
                xaxis: {
                  min: ranges.xaxis.from,
                  max: ranges.xaxis.to
                }
              }));
              plot = $.plot(placeholder3, [XM, YM, ZM], $.extend(true, {}, options2, {
                xaxis: {
                  min: ranges.xaxis.from,
                  max: ranges.xaxis.to
                }
              }));
              plot = $.plot(placeholder4, [XG, YG, ZG], $.extend(true, {}, options2, {
                xaxis: {
                  min: ranges.xaxis.from,
                  max: ranges.xaxis.to
                }
              }));
              placeholders.bind("plotselected", function (event, ranges) {
                plots[1].setSelection(ranges);
              });
            });

            placeholders.bind("plotpan plotzoom", function (event, plot) {
              var axes = plot.getAxes();
              for (var i=0; i< plots.length; i++) {
                if (plot == plots[i])
                  continue;
                plots[i].getOptions().xaxes[0].min = axes.xaxis.min;
                plots[i].getOptions().xaxes[0].max = axes.xaxis.max;
                if (i!=0){
                plots[i].getOptions().yaxes[0].min = axes.yaxis.min;
                plots[i].getOptions().yaxes[0].max = axes.yaxis.max;}
                plots[i].setupGrid();
                plots[i].draw();
              }
            });
          });
        });
    </script>
</head>

<body>
    <div id="left">
        <script type="text/javascript">
            // Get patient ID, type and in lab/at home
            $(function() {
                $.getJSON('https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/all.json', function(data) {
                    var obj = data[0];
            
                	document.getElementById("patient").innerHTML
                        ="Patient ID: "+obj.key.id+"<br>"+"Patient Name: "+obj.key.name+"<br>"+"Where Is Device: "+obj.propertyMap.WhereIsDevice+"<br>";
                });
            });
       
        </script>

        <p id="LoyolaPACLab">Loyola PAC Lab<br>
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
    </div>

</body>
</html>
