<!DOCTYPE html>
<html>
<head>
	<title>Loyola PAC Lab</title>
	<link rel="stylesheet" href="PAC_v1.css">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> </script>
<script language="javascript" type="text/javascript" src="jquery.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="jquery.flot.navigate.js"></script>
    <script>

        </script>

<style type="text/css">
#placeholder .button {
position: absolute;
cursor: pointer;
}
#placeholder div.button {
font-size: smaller;
color: #999;
background-color: #eee;
padding: 2px;
}
.message {
    padding-left: 50px;
    font-size: smaller;
}
</style>
</head>

<body>
	<div id="left">
		<p id="LoyolaPACLab">Loyola PAC Lab<br>
		<span id="patient">Patient ID:</span><br>
		<span id="patient">Patient type:</span><br>
		<span id="patient">In lab/at home:</span><br>
		<span id="patient">Start time:</span></p>

		<p>Comments:
		<textarea rows="10" cols="45"></textarea></p>

		<form>
			<input type="checkbox" name="chartType" value="vigor">Vigor<br>
			<input type="checkbox" name="chartType" value="tremor">Tremor<br>
			<input type="checkbox" name="chartType" value="cadence">Cadence<br>
			<input type="checkbox" name="chartType" value="leaning">Leaning<br>
		</form>
	</div>

	<div id="right">
		<p><img src="luc-logo.png" alt="Loyola logo" align="right"></p><br><br>

		<p>
      
        Raw data:
<p id="showResults"></p>
<div id="placeholder" style="width:600px;height:300px;"></div>

<p class="message"></p></p>

		<p>Activity:
		<textarea rows="15" cols="125"></textarea>
        </p>
		<p>Supplemental information:
			<select name="extraChart">
			<option value="Vigor">Vigor</option>
			<option value="Tremor">Tremor</option>
			<option value="Cadence">Cadence</option>
			<option value="Leaning">Leaning</option>
		</select>
		<textarea rows="15" cols="125"></textarea></p>
	</div>
<script type="text/javascript">
// Data from JSON file is stored in an array
var timeInSeconds = new Array();
var x = new Array();
var y = new Array();
var z = new Array();
var date = new Array();
var time = new Array();
var activity = new Array();
var whereIsDevice = new Array();
var i = 0;

$.getJSON('SampleData.json', function(data) {
          
          // Read data from SampleData.json
          $.each(data.patientData, function(i, f) {
                 timeInSeconds[i] = f.TimeInSeconds;
                 x[i] = f.X;
                 y[i] = f.Y;
                 z[i] = f.Z;
                 date[i] = f.Date;
                 time[i] = f.Time;
                 activity[i] = f.Activity;
                 whereIsDevice[i] = f.whereIsDevice;
                 i++;
                 });
          
          // Manipulate the data
          for (i = 0; i < timeInSeconds.length; i++) {
          timeInSeconds[i] = timeInSeconds[i] - 1;
          }
          
          // Display a small bit of the data to show that it is being read in correctly
          var displayResults;
          for (i = 0; i < 10-1; i++) {
          displayResults = "<p>" + timeInSeconds[i] + "  " + x[i] + "  " + y[i] + "  " + z[i] + "  "
          + date[i] + "  " + time[i] + "  " + activity[i] + "  "
          + whereIsDevice[i] + "</p>";
          $(displayResults).appendTo("#showResults");
         
          

$(function () {
 
  var d1 = [];
  var d2 = [];
  var d3 = [];
  //for (var t = 0; t <= 2 * Math.PI; t += 0.01)
//d1.push([sumf(Math.cos, t, 10), sumf(Math.sin, t, 10)]);
for (var t = 0; t <= 10; t ++){
  d1.push([timeInSeconds[t],x[t]]);
  d2.push([timeInSeconds[t],y[t]]);
  d3.push([timeInSeconds[t],z[t]]);
}
  var xData = [ d1 ];
  var yData = [ d2 ];
  var zData = [ d3 ];
  
  var placeholder = $("#placeholder");
  var options = {
  series: { lines: { show: true }, shadowSize: 0 },
  xaxis: { zoomRange: [0.1, 2069], panRange: [-10, 2069] },
  yaxis: { zoomRange: [0.1, 2069], panRange: [-10, 2069] },
  zoom: {
  interactive: true
  },
  pan: {
  interactive: true
  }
  };
  
  var plot = $.plot(placeholder,xData, options);
  //var Yplot = $.plot(placeholder,YData , options);
  // show pan/zoom messages to illustrate events
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
  
  // add zoom out button
  $('<div class="button" style="right:20px;top:20px">zoom out</div>').appendTo(placeholder).click(function (e) {
                                                                                                  e.preventDefault();
                                                                                                  plot.zoomOut();
                                                                                                  });
  
  // and add panning buttons
  
  // little helper for taking the repetitive work out of placing
  // panning arrows
  function addArrow(dir, right, top, offset) {
  $('<img class="button" src="arrow-' + dir + '.gif" style="right:' + right + 'px;top:' + top + 'px">').appendTo(placeholder).click(function (e) {
                                                                                                                                    e.preventDefault();
                                                                                                                                    plot.pan(offset);
                                                                                                                                    });
  }
  
  addArrow('left', 55, 60, { left: -100 });
  addArrow('right', 25, 60, { left: 100 });
  addArrow('up', 40, 45, { top: -100 });
  addArrow('down', 40, 75, { top: 100 });
  });
 }//Display a small bit of the data to show that it is being read in correctly
});
</script>

</body>
</html>