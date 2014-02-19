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

$(function () {
      var patientID = "";
      var patientType = "";
      var inLabAtHome = "";
      $.getJSON('PatientInfo.json', function(data) {
          document.getElementById("patient").innerHTML="Patient ID: "+data.PatientID+'<br>'+"Patient type: "+data.PatientType+'<br>'+"In lab/at home: "+data.InLabAtHome;
          });
    });
    </script>

		<p id="LoyolaPACLab">Loyola PAC Lab<br>
		<div id="patient"></div><br></p>

		<p>Comments:
		<textarea rows="10" cols="45"></textarea></p>
	</div>

	<div id="right">
		<p><img src="luc-logo.png" alt="Loyola logo" align="right"></p><br><br><br><br><br><br>

    Activity
    <div id="placeholder"></div>
      <script type="text/javascript">
        // **************************************************
        // ******* FIGURE OUT HOW TO AUTOMATE THIS!!! *******
        var amtData = 16400;
        // **************************************************

        // Data from JSON file is stored in an array
        var timeInSeconds = new Array();
        var x = new Array();
        var y = new Array();
        var z = new Array();
        var i = 0;

        $.getJSON('SampleData.json', function(data) {
      
          // Read data from SampleData.json
          $.each(data.patientData, function(i, f) {
            timeInSeconds[i] = f.TimeInSeconds;
            x[i] = f.X;
            y[i] = f.Y;
            z[i] = f.Z;
            i++;
          });
          
          $(function () {
            var d1 = [];
            var d2 = [];
            var d3 = [];

            for (var t = 0; t <= amtData; t++) {
              d1.push([timeInSeconds[t],x[t]]);
              d2.push([timeInSeconds[t],y[t]]);
              d3.push([timeInSeconds[t],z[t]]);
            }

            var xData = [d1];
            var yData = [d2];
            var zData = [d3];
  
            var placeholder = $("#placeholder");
            var options = {
              series: {
                lines: {show: true},
                shadowSize: 0
              },
              xaxis: {
                zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000]
              },
              yaxis: {
                zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000]
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
          });
        });
    </script>

    <br>Activity Type
    <div id="placeholder2"></div>
    <table>
    <tr><td bgcolor="#FFD700">&nbsp;</td><td>Lying</td><td bgcolor="#87CEEB">&nbsp;</td><td>Wheeling</td><td bgcolor="#DC143C">&nbsp;</td><td>Walking</td></tr>
<tr><td bgcolor="#228B22">&nbsp;</td><td>Sitting</td><td bgcolor="#9400D3">&nbsp;</td><td>Misc</td><td bgcolor="#B8860B">&nbsp;</td><td>Standing</td></tr>
</table>

      <script type="text/javascript">
        // Data from JSON file is stored in an array
        var timeInSeconds = new Array();
        var activity = new Array();
        var i = 0;

        $.getJSON('SampleData.json', function(data) {
      
          // Read data from SampleData.json
          $.each(data.patientData, function(i, f) {
            timeInSeconds[i] = f.TimeInSeconds;
            activity[i] = f.Activity;
            i++;
          });
          
          $(function () {
            var d1 = [];
            var d2 = [];
            var d3 = [];
            var d4 = [];
            var d5 = [];
            var d6 = [];

            for (var t = 0; t <= amtData; t++) {
              if (activity[t]=="Lying" && activity[t+1]=="Lying")
                d1.push([timeInSeconds[t],timeInSeconds[t]]);
              else
                d1.push(null);
              if (activity[t]=="Wheeling" && activity[t+1]=="Wheeling")
                d2.push([timeInSeconds[t],timeInSeconds[t]]);
              else
                d2.push(null);
              if (activity[t]=="Walking" && activity[t+1]=="Walking")
                d3.push([timeInSeconds[t],timeInSeconds[t]]);
              else
                d3.push(null);
              if (activity[t]=="Sitting" && activity[t+1]=="Sitting")
                d4.push([timeInSeconds[t],timeInSeconds[t+1]]);
              else
                d4.push(null);
              if (activity[t]=="Misc" && activity[t+1]=="Misc")
                d5.push([timeInSeconds[t],timeInSeconds[t+1]]);
              else
                d5.push(null);
              if (activity[t]=="Standing" && activity[t+1]=="Standing")
                d6.push([timeInSeconds[t],timeInSeconds[t+1]]);
              else
                d6.push(null);
            }
  
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
                zoomRange: [0.1, 1000000], panRange: [-1000000, 1000000]
              },
              zoom: {interactive: true},
              pan: {interactive: true},
              grid: {
                backgroundColor: {
                  colors: ["#fff", "#eee"]
                }
              }
            };
  
            var plot = $.plot(placeholder2, [d1,d2,d3,d4,d5], options);
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

	</div>

</body>
</html>