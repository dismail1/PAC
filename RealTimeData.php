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
var x = [], y = [], z = [];
var plots = [];
var placeholders = $(".flot");
var dataset;
var totalPoints = 100;
var updateInterval = 5000;
var now = new Date().getTime();

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

function initData() {
    for (var i = 0; i < totalPoints; i++) {
        var temp = [now += updateInterval, 0];

        x.push(temp);
        y.push(temp);
        z.push(temp);
    }
}

function GetData() {
    $.ajaxSetup({ cache: false });

    $.ajax({
        url: "1.json",
        dataType: 'json',
        success: update,
        error: function () {
            setTimeout(GetData, updateInterval);
        }
    });
}

var temp;

function update(_data) {
    x.shift();
    y.shift();
    z.shift();

    now += updateInterval

    temp = [now, _data.X];
    x.push(temp);

    temp = [now, _data.Y];
    y.push(temp);

    temp = [now, _data.Z];
    z.push(temp);

    dataset = [
        { label: "X:" + _data.X , data: x, lines: { lineWidth: 1.2 }, color: "#00FF00" },
        { label: "Z:" + _data.Z , data: z, lines: { lineWidth: 1.2 }, color: "#0000FF" },
        { label: "X:" + _data.Y , data: y, lines: { lineWidth: 1.2}, color: "#FF0000" }        
    ];

    plots.push($.plot(placeholder, dataset, options));
    plots.push($.plot(placeholder2, dataset, options));
    plots.push($.plot(placeholder3, dataset, options));
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

    dataset = [        
        { label: "X:", data: x, lines:{ lineWidth:1.2}, color: "#00FF00" },
        { label: "Z:", data: z, lines: { lineWidth: 1.2 }, color: "#0000FF" },
        { label: "X:", data: y, lines: { lineWidth: 1.2}, color: "#FF0000" }
    ];

    plots.push($.plot(placeholder, dataset, options));
    plots.push($.plot(placeholder2, dataset, options));	
    plots.push($.plot(placeholder3, dataset, options));
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
  $.getJSON('PatientInfo.json', function(data) {
            document.getElementById("patient").innerHTML
            ="Patient ID: "+data.PatientID+"<br>"+"Patient type: "+data.PatientType+"<br>"
            +"In lab/at home: "+data.InLabAtHome;
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
<div id="title">Accelerometer</div>
<div id="placeholder" class="flot" style="width:900px;height:300px;margin:0 auto"></div>
<div id="title">Magnetometer</div>
<div id="placeholder2" class="flot" style="width:900px;height:300px;margin:0 auto"></div>
<div id="title">Gyroscope</div>
<div id="placeholder3" class="flot" style="width:900px;height:300px;margin:0 auto"></div>

</body>
</html>
