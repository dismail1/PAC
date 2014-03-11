<!DOCTYPE html>
<head>
    <title>Loyola PAC Lab</title>
    <link rel="stylesheet" href="PAC.css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> </script>
    <script language="javascript" type="text/javascript" src="jquery.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.navigate.js"></script>
    <script language="javascript" type="text/javascript" src="jquery.flot.selection.js"></script>


    <script type="text/javascript">
        $(function () {
            var timeInSeconds = new Array();
            var x = new Array();
            var y = new Array();
            var z = new Array();
            var activity = new Array();
            var amtData = 0;

            $.getJSON('SampleData.json', function(data) {
                $.each(data.patientData, function(i, f) {
                    timeInSeconds[amtData] = f.TimeInSeconds;
                    x[amtData] = f.X;
                    y[amtData] = f.Y;
                    z[amtData] = f.Z;
                    activity[amtData] = f.Activity;
                    amtData++;
                });

            var d1 = [];
            var d2 = [];
            var d3 = [];
            var d4 = [];
            var d5 = [];
            var d6 = [];
            var d7 = [];
            var d8 = [];
            var d9 = [];

            for (var t = 0; t <= amtData; t++) {
              d1.push([timeInSeconds[t],x[t]]);
              d2.push([timeInSeconds[t],y[t]]);
              d3.push([timeInSeconds[t],z[t]]);
              if (activity[t]=="Lying")
                d4.push([timeInSeconds[t], -2]);
              else
                d4.push(null);
              if (activity[t]=="Wheeling")
                d5.push([timeInSeconds[t], -1]);
              else
                d5.push(null);
              if (activity[t]=="Walking")
                d6.push([timeInSeconds[t], 0]);
              else
                d6.push(null);
              if (activity[t]=="Sitting")
                d7.push([timeInSeconds[t], 1]);
              else
                d7.push(null);
              if (activity[t]=="Standing")
                d8.push([timeInSeconds[t], 2]);
              else
                d8.push(null);
              if (activity[t]=="Misc")
                d9.push([timeInSeconds[t], 3]);
              else
                d9.push(null);
            }

            var plots = [];
            var placeholders = $(".flot");
    
/*            var d1 = [];
            for (var i = 0; i < Math.PI * 2; i += 0.25)
                d1.push([i, Math.sin(i)]);
    
            var d2 = [];
            for (var i = 0; i < Math.PI * 2; i += 0.25)
                d2.push([i, Math.cos(i)]); */
    
            var options = {
              series: {
                lines: {show: true},
                shadowSize: 0
              },
              xaxis: { zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000]},
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

            var ticks = [
                [-2, "Lying"],
                [-1, "Wheeling"],
                [0, "Walking"],
                [1, "Sitting"],
                [2, "Standing"],
                [3, "Misc"]
            ];
            
            var options2 = {
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
     
                     
            plots.push($.plot(placeholder, [d1, d2, d3], options));
            plots.push($.plot(placeholder2, [d4, d5, d6, d7, d8, d9], options2));
            placeholders.bind("plotselected", function (event, ranges) {
                                        
                   plot = $.plot(placeholder, [d1, d2, d3], $.extend(true, {}, options, {
                          xaxis: {
                                  min: ranges.xaxis.from,
                                  max: ranges.xaxis.to
                                  }
                          
                    }));
                    plot = $.plot(placeholder2, [d4, d5, d6, d7, d8, d9], $.extend(true, {}, options, {
                           xaxis: {
                                   min: ranges.xaxis.from,
                                   max: ranges.xaxis.to
                                   },
                           yaxis:{ticks: ticks }
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

        Activity
        <div id="placeholder" class="flot"></div><br>

        Activity Type
        <div id="placeholder2" class="flot"></div>
    </div>

</body>
</html>