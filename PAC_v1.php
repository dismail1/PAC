<!DOCTYPE html>
<html>
<head>
	<title>Loyola PAC Lab</title>
	<link rel="stylesheet" href="PAC_v1.css">
    <script type="text/javascript" src="smoothie.js"></script>
    <script type="text/javascript" src="jquery.parse.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"> </script>
    <script>
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
                  for (i = 0; i < 10; i++) {
                  displayResults = "<p>" + timeInSeconds[i] + "  " + x[i] + "  " + y[i] + "  " + z[i] + "  "
                  + date[i] + "  " + time[i] + "  " + activity[i] + “  "
                  + whereIsDevice[i] + "</p>";
                  $(displayResults).appendTo("#showResults");
                  }
                  });
        </script>
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
        <div class="holder"><canvas id="mycanvas4" width="600" height="100"></canvas></div>
        <script type="text/javascript">
         
            (function() {
             var line1 = new TimeSeries();
             var line2 = new TimeSeries();
             var line3 = new TimeSeries();
             setInterval(function() {
                         line1.append(new Date().getTime(), Math.random());
                         line2.append(new Date().getTime(), Math.random());
                         line3.append(new Date().getTime(), Math.random());
                         }, 2000);
             
             var smoothie = new SmoothieChart({grid:{fillStyle:'#ffffff'}});
             smoothie.addTimeSeries(line1, { strokeStyle: 'rgb(0, 255, 0)', lineWidth: 3 });
             smoothie.addTimeSeries(line2, { strokeStyle: 'rgb(255, 0, 0)', lineWidth: 3 });
             smoothie.addTimeSeries(line3, { strokeStyle: 'rgb(0, 0, 255)', lineWidth: 3 });
             smoothie.streamTo(document.getElementById("mycanvas4"));
             })();
             
            </script></p>

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

</body>
</html>