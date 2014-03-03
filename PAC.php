<!DOCTYPE html>
<html>
<head>
  <title>Loyola PAC Lab</title>
  <link rel="stylesheet" href="PAC.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> </script>
  <script language="javascript" type="text/javascript" src="jquery.js"></script>
  <script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
  <script language="javascript" type="text/javascript" src="jquery.flot.navigate.js"></script>
</head>

<body>
  <div id="left">
    <script type="text/javascript">
      // Get patient ID, type and in lab/at home
      $(function() {
        $.getJSON('PatientInfo.json', function(data) {
          document.getElementById("patient").innerHTML
            ="Patient ID: "+data.PatientID+"<br>"+"Patient type: "+data.PatientType+"<br>"+"In lab/at home: "+data.InLabAtHome;
        });
      });
    </script>

    <p id="LoyolaPACLab">Loyola PAC Lab<br>
    <div id="patient"></div></p>

    <p>Comments:
    <textarea rows="10" cols="45"></textarea></p>
  </div>

  <div id="right">
    <script type="text/javascript">
        // Data from JSON file is stored in an array
        var timeInSeconds = new Array();
        var x = new Array();
        var y = new Array();
        var z = new Array();
        var timeInSeconds = new Array();
        var activity = new Array();
        var amtData = 0;

        $.getJSON('SampleData.json', function(data) {
      
          // Read data from SampleData.json
          $.each(data.patientData, function(i, f) {
            timeInSeconds[amtData] = f.TimeInSeconds;
            x[amtData] = f.X;
            y[amtData] = f.Y;
            z[amtData] = f.Z;
            timeInSeconds[amtData] = f.TimeInSeconds;
            activity[amtData] = f.Activity;
            amtData++;
          });
          
          $(function () {
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
                d4.push([timeInSeconds[t], 1]);
              else
                d4.push(null);
              if (activity[t]=="Wheeling")
                d5.push([timeInSeconds[t], 1]);
              else
                d5.push(null);
              if (activity[t]=="Walking")
                d6.push([timeInSeconds[t], 1]);
              else
                d6.push(null);
              if (activity[t]=="Sitting")
                d7.push([timeInSeconds[t], 1]);
              else
                d7.push(null);
              if (activity[t]=="Standing")
                d8.push([timeInSeconds[t], 1]);
              else
                d8.push(null);
              if (activity[t]=="Misc")
                d9.push([timeInSeconds[t], 1]);
              else
                d9.push(null);
            }

            var xData = [d1];
            var yData = [d2];
            var zData = [d3];
  
            var placeholder = $("#placeholder");
            var placeholder2 = $("#placeholder2");

            var options = {
              series: {
                lines: {show: true},
                shadowSize: 0
              },
              xaxis: {
                zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000]
              },
              yaxis: {
                zoomRange: [0.1, 0.1], panRange: [-1000000, 1000000]
              },
              zoom: {interactive: true},
              pan: {interactive: true},
              grid: {
                backgroundColor: {
                  colors: ["#fff", "#eee"]
                }
              }
            };

            var options2 = {
              series: {
                lines: {
                  show: true,
                  lineWidth: 10
                },
                shadowSize: 0
              },
              colors: ["#FFD700", "#87CEEB", "#DC143C", "#228B22", "#B8860B", "#A9A9A9"],
              xaxis: {
                zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000]
              },
              yaxis: {
                zoomRange: [0.1, 0.1], panRange: [-1000000, 1000000]
              },
              zoom: {interactive: true},
              pan: {interactive: true},
              grid: {
                backgroundColor: {
                  colors: ["#fff", "#eee"]
                }
              }
            };
  
            var plot = $.plot(placeholder, [d1, d2, d3], options);
            placeholder.bind('plotpan', function (event, plot) {
              var axes = plot.getAxes();
              $(".message").html("Panning to x: "  + axes.xaxis.min.toFixed(2)
               + " &ndash; " + axes.xaxis.max.toFixed(2)
               + " and y: " + axes.yaxis.min.toFixed(2)
               + " &ndash; " + axes.yaxis.max.toFixed(2));
            });
  
            placeholder.bind('plotzoom', function (event, plot) {
              var axes = plot.getAxes();
              $(".message").html("Zooming to x: "  + axes.xaxis.min.toFixed(2)
               + " &ndash; " + axes.xaxis.max.toFixed(2)
               + " and y: " + axes.yaxis.min.toFixed(2)
               + " &ndash; " + axes.yaxis.max.toFixed(2));
            });
  
            // Add zoom out button
            $('<div class="button" style="right:20px;top:20px">zoom out</div>').appendTo(placeholder).click(function (e) {
              e.preventDefault();
              plot.zoomOut();
            });
  
            // Little helper for taking the repetitive work out of placing panning arrows
            function addArrow(dir, right, top, offset) {
              $('<img class="button" src="arrow-' + dir + '.gif" style="right:' + right + 'px;top:' + top
                + 'px">').appendTo(placeholder).click(function (e) {
                  e.preventDefault();
                  plot.pan(offset);
              });
            }
  
            addArrow('left', 55, 60, {left: -100});
            addArrow('right', 25, 60, {left: 100});
            addArrow('up', 40, 45, {top: -100});
            addArrow('down', 40, 75, {top: 100});

            var plot2 = $.plot(placeholder2, [d4, d5, d6, d7, d8, d9], options2);
            placeholder2.bind('plotpan', function (event, plot) {
              var axes = plot.getAxes();
              $(".message").html("Panning to x: "  + axes.xaxis.min.toFixed(2)
               + " &ndash; " + axes.xaxis.max.toFixed(2)
               + " and y: " + axes.yaxis.min.toFixed(2)
               + " &ndash; " + axes.yaxis.max.toFixed(2));
            });
  
            placeholder2.bind('plotzoom', function (event, plot) {
              var axes = plot.getAxes();
              $(".message").html("Zooming to x: "  + axes.xaxis.min.toFixed(2)
               + " &ndash; " + axes.xaxis.max.toFixed(2)
               + " and y: " + axes.yaxis.min.toFixed(2)
               + " &ndash; " + axes.yaxis.max.toFixed(2));
              });
  
            // Add zoom out button
            $('<div class="button" style="right:20px;top:20px">zoom out</div>').appendTo(placeholder2).click(function (e) {
              e.preventDefault();
              plot.zoomOut();
            });
  
            // Little helper for taking the repetitive work out of placing panning arrows
            function addArrow(dir, right, top, offset) {
              $('<img class="button" src="arrow-' + dir + '.gif" style="right:' + right + 'px;top:' + top
                + 'px">').appendTo(placeholder2).click(function (e) {
                  e.preventDefault();
                  plot.pan(offset);
              });
            }
  
            addArrow('left', 55, 60, {left: -100});
            addArrow('right', 25, 60, {left: 100});
            addArrow('up', 40, 45, {top: -100});
            addArrow('down', 40, 75, {top: 100});
          });
        });
    </script>

    <p><img src="luc-logo.png" alt="Loyola logo" align="right"></p><br><br><br><br>

    Activity
    <div id="placeholder"></div><br>

    Activity Type
    <div id="placeholder2"></div>

    <table>
      <tr>
        <td id="cell"></td>
        <td id="colorBox" bgcolor="#FFD700"></td>
        <td id="cell">&nbsp;Lying</td>
        <td id="colorBox" bgcolor="#228B22"></td>
        <td id="cell">&nbsp;Sitting</td>
        <td id="colorBox" bgcolor="#B8860B"></td>
        <td id="cell">&nbsp;Standing</td>
        <td id="colorBox" bgcolor="#87CEEB"></td>
        <td id="cell">&nbsp;Wheeling</td>
        <td id="colorBox" bgcolor="#DC143C"></td>
        <td id="cell">&nbsp;Standing</td>
        <td id="colorBox" bgcolor="#A9A9A9"></td>
        <td id="cell">&nbsp;Misc.</td>
       </tr>
    </table>
  </div>

</body>
</html>