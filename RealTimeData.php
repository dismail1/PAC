<!DOCTYPE html>
<head>
<title>Loyola PAC Lab</title>
<link rel="stylesheet" href="PAC.css">
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
var ticks = [
            [-1.9, "Lying"],
            [-1.2, "Wheeling"],
            [-0.4, "Walking"],
            [0.4, "Sitting"],
            [1.2, "Standing"],
            [1.9, "Misc"]
        ];
var xA = [], yA = [], zA = [];
var xM = [], yM = [], zM = [];
var xG = [], yG = [], zG = [];
var lw = 1.2;
var plots = [];
var placeholders = $(".flot");
var dataset;
var totalPoints = 100;
var updateInterval = 500;
var now = new Date().getTime();
var url='https://cloudbackend-dot-handy-reference-545.appspot.com/api/patient/last-second.json';
var options = {
    series: {
        lines: {
            lineWidth: 1.2
        },
        bars: {
            align: "center",
            fillColor: { colors: [{ opacity: 1 }, { opacity: 1}] },
            barWidth: 500,
            lineWidth: 1
        }
    },
    xaxis: {
        mode: "time",
        tickSize: [60, "second"],
        tickFormatter: function (v, axis) {
            var date = new Date(v);

            if (date.getSeconds() % 20 == 0) {
                var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
                var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();

                return hours + ":" + minutes + ":" + seconds;
            } else {
                return "";
            }
        },
        zoomRange: [0.1, 1000000],
        panRange: [-10, 10],
        axisLabel: "Time",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10
    },
    yaxes: [
        {
            min: -2,
            max: 2,
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 6,
            zoomRange: [0.1, 0.1],
            panRange: [-10, 10]
        }, {
            max: 5120,
            position: "right",
            axisLabelUseCanvas: true,
            axisLabelFontSizePixels: 12,
            axisLabelFontFamily: 'Verdana, Arial',
            axisLabelPadding: 6
        }
    ],
    legend: {
        noColumns: 0,
        position:"nw"
    },
    zoom: { interactive: true },
    pan: { interactive: true },
    grid: {      
        backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
    }
};

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
                tickSize: [60, "second"],
                tickFormatter: function (v, axis) {
                    var date = new Date(v);
                    if (date.getSeconds() % 20 == 0) {
                        var hours = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
                        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
                        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
                        return hours + ":" + minutes + ":" + seconds;
                    } else {
                        return "";
                    }
                },
                zoomRange: [0.1, 1000000],
                panRange: [-10, 10],
                axisLabel: "Time",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10
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

function initData() {
    for (var i = 0; i < totalPoints; i++) {
        var temp = [now += updateInterval, 0];

        xA.push(temp);
        yA.push(temp);
        zA.push(temp);
        xM.push(temp);
        yM.push(temp);
        zM.push(temp);
        xG.push(temp);
        yG.push(temp);
        zG.push(temp);
        
    }
}

function GetData() {
    $.ajaxSetup({ cache: false });

    $.ajax({
        url: url,
        dataType: 'json',
        success: update,
        error: function () {
            setTimeout(GetData, updateInterval);
        }
    });
}

var temp;

function update(_data) {
    xA.shift();
    yA.shift();
    zA.shift();
    xM.shift();
    yM.shift();
    zM.shift();
    xG.shift();
    yG.shift();
    zG.shift();
    

    now += updateInterval

    if (_data.propertyMap.Activity=="Lying")
                    activity1.push([now, -1.9]);
                  else
                    activity1.push(null);
                  if (_data.propertyMap.Activity=="Wheeling")
                    activity2.push([now, -1.2]);
                  else
                    activity2.push(null);
                  if (_data.propertyMap.Activity=="Walking")
                    activity3.push([now, -0.4]);
                  else
                    activity3.push(null);
                  if (_data.propertyMap.Activity=="Sitting")
                    activity4.push([now, 0.4]);
                  else
                    activity4.push(null);
                  if (_data.propertyMap.Activity=="Standing")
                    activity5.push([now, 1.2]);
                  else
                    activity5.push(null);
                  if (_data.propertyMap.Activity=="Misc")
                    activity6.push([now, 1.9]);
                  else
                    activity6.push(null);
                    
       
    temp = [now, _data.propertyMap.XA];
    xA.push(temp);

    temp = [now, _data.propertyMap.YA];
    yA.push(temp);

    temp = [now, _data.propertyMap.ZA];
    zA.push(temp);

    datasetA = [
        { label: "X:" + _data.propertyMap.XA , data: xA, lines: { lineWidth: lw }},
        { label: "Y:" + _data.propertyMap.YA , data: yA, lines: { lineWidth: lw }},
        { label: "Z:" + _data.propertyMap.ZA , data: zA, lines: { lineWidth: lw}}        
    ];
    
    temp = [now, _data.propertyMap.XM];
    xM.push(temp);

    temp = [now, _data.propertyMap.YM];
    yM.push(temp);

    temp = [now, _data.propertyMap.ZM];
    zM.push(temp);
    
    datasetM = [
        { label: "X:" + _data.propertyMap.XM , data: xM, lines: { lineWidth: lw }},
        { label: "Y:" + _data.propertyMap.YM , data: yM, lines: { lineWidth: lw }},
        { label: "Z:" + _data.propertyMap.ZM , data: zM, lines: { lineWidth: lw}}        
    ];
    
      temp = [now, _data.propertyMap.XG];
    xG.push(temp);

    temp = [now, _data.propertyMap.YG];
    yG.push(temp);

    temp = [now, _data.propertyMap.ZG];
    zG.push(temp);
    
    datasetG = [
        { label: "X:" + _data.propertyMap.XG , data: xG, lines: { lineWidth: lw }},
        { label: "Y:" + _data.propertyMap.YG , data: yG, lines: { lineWidth: lw }},
        { label: "Z:" + _data.propertyMap.ZG , data: zG, lines: { lineWidth: lw}}        
    ];
    
    plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));

    plots.push($.plot(placeholder, datasetA, options));
    plots.push($.plot(placeholder2, datasetM, options));
    plots.push($.plot(placeholder3, datasetG, options));
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
    setTimeout(GetData, updateInterval);
}


$(document).ready(function () {
    initData();

    datasetA = [        
        { label: "X:", data: xA, lines:{ lineWidth:lw}},
        { label: "Z:", data: zA, lines: { lineWidth: lw }},
        { label: "X:", data: yA, lines: { lineWidth: lw}}
    ];
    datasetM = [
        { label: "X:"  , data: xM, lines: { lineWidth: lw }},
        { label: "Y:"  , data: yM, lines: { lineWidth: lw }},
        { label: "Z:" , data: zM, lines: { lineWidth: lw}}        
    ];
    datasetG = [
        { label: "X:" , data: xG, lines: { lineWidth: lw }},
        { label: "Y:" , data: yG, lines: { lineWidth: lw }},
        { label: "Z:"  , data: zG, lines: { lineWidth: lw}}        
    ];
    plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));
    plots.push($.plot(placeholder, datasetA, options));
    plots.push($.plot(placeholder2, datasetM, options));	
    plots.push($.plot(placeholder3, datasetG, options));
    placeholders.bind("plotpan plotzoom", function (event, plot) {
        var axes = plot.getAxes();
        for(var i=0; i< plots.length; i++) {
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
    setTimeout(GetData, updateInterval);
});


</script>

</head>

<body>
<div id="left">
<script type="text/javascript">
            // Get patient ID, type and in lab/at home
            $(function() {
                $.getJSON(url, function(data) {
                    var obj = data;
            
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
<div id="title">Accelerometer</div>
<div id="placeholder" class="flot" style="width:900px;height:300px;margin:0 auto"></div>
<div id="title">Magnetometer</div>
<div id="placeholder2" class="flot" style="width:900px;height:300px;margin:0 auto"></div>
<div id="title">Gyroscope</div>
<div id="placeholder3" class="flot" style="width:900px;height:300px;margin:0 auto"></div>

</body>
</html>
