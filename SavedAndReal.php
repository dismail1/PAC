<!DOCTYPE html>
<head>
    <title>Loyola PAC Lab</title>
    <link rel="stylesheet" href="SavedAndReal.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> </script>
    <script language="javascript" type="text/javascript" src="jquery.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.navigate.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.selection.js"></script>

    <script type="text/javascript">
        $(function() {
            var timeInSeconds = [];
            var xA = [], yA = [], zA = [];
            var xM = [], yM = [], zM = [];
            var xG = [], yG = [], zG = [];
            var activity = [];
            var amtData = 0;

            $.getJSON('SampleData.JSON', function(data) {
                $.each(data.patientData, function(i, f) {
                    timeInSeconds[amtData] = f.TimeInSeconds;
                    xA[amtData] = f.XA;
                    yA[amtData] = f.YA;
                    zA[amtData] = f.ZA;
                    xM[amtData] = f.XM;
                    yM[amtData] = f.YM;
                    zM[amtData] = f.ZM;
                    xG[amtData] = f.XG;
                    yG[amtData] = f.YG;
                    zG[amtData] = f.ZG;
                    activity[amtData] = f.Activity;
                    amtData++;
                });

                var XA = [], YA = [], ZA = [];
                var XM = [], YM = [], ZM = [];
                var XG = [], YG = [], ZG = [];
                var activity1 = [];
                var activity2 = [];
                var activity3 = [];
                var activity4 = [];
                var activity5 = [];
                var activity6 = [];

                for (var t = 0; t < amtData; t++) {
                    XA.push([timeInSeconds[t], xA[t]]);
                    YA.push([timeInSeconds[t], yA[t]]);
                    ZA.push([timeInSeconds[t], zA[t]]);
                    XM.push([timeInSeconds[t], xM[t]]);
                    YM.push([timeInSeconds[t], yM[t]]);
                    ZM.push([timeInSeconds[t], zM[t]]);
                    XG.push([timeInSeconds[t], xG[t]]);
                    YG.push([timeInSeconds[t], yG[t]]);
                    ZG.push([timeInSeconds[t], zG[t]]);
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

                var plots = [];
                var placeholders = $(".flot");
                var ticks = [
                    [-1.9, "Lying"],
                    [-1.2, "Wheeling"],
                    [-0.4, "Walking"],
                    [ 0.4, "Sitting"],
                    [ 1.2, "Standing"],
                    [ 1.9, "Misc"]
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
                    xaxis: { zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000] },
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
                        lines: { show: true },
                        shadowSize: 0
                    },
                    xaxis: { zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000] },
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

                plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));
                plots.push($.plot(placeholder2, [XA, YA, ZA], options2));
                plots.push($.plot(placeholder3, [XM, YM, ZM], options2));
                plots.push($.plot(placeholder4, [XG, YG, ZG], options2));

                placeholders.bind("plotselected", function (event, ranges) {
                    plot = $.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6],
                      $.extend(true, {}, options, {
                        xaxis: {
                            min: ranges.xaxis.from,
                            max: ranges.xaxis.to
                        }
                    }));
                    plot = $.plot(placeholder2, [XA, YA, ZA], $.extend(true, {}, options, {
                        xaxis: {
                            min: ranges.xaxis.from,
                            max: ranges.xaxis.to
                        },
                        yaxis: { ticks: ticks }
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
                        plots[i].getOptions().yaxes[0].min = axes.yaxis.min;
                        plots[i].getOptions().yaxes[0].max = axes.yaxis.max;
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

        <div id="title">Activity</div>
        <div id="placeholder1" class="flot"></div><br>

        <div id="title">Accelerometer</div>
        <div id="placeholder2" class="flot"></div><br>

        <div id="title">Magnetometer</div>
        <div id="placeholder3" class="flot"></div><br>

        <div id="title">Gyroscope</div>
        <div id="placeholder4" class="flot"></div>
    </div>
</body>
</html>