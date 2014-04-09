<!DOCTYPE html>
<head>
    <title>Loyola PAC Lab</title>
    <link rel="stylesheet" href="RealTimeData.css">
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
        var xA = [], yA = [], zA = [];
        var xM = [], yM = [], zM = [];
        var xG = [], yG = [], zG = [];
        var plots = [];
        var placeholders = $(".flot");
        var datasetA, datasetM, datasetG;
        var totalPoints = 100;
        var updateInterval = 1000;
        var now = new Date().getTime();
        var lw = 1.2;
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
                panRange: [-10, 10]
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
            for (var i = 0; i < totalPoints; i++) {
                var temp = [now += updateInterval, 0];
                activity1.push(temp);
                activity2.push(temp);
                activity3.push(temp);
                activity4.push(temp);
                activity5.push(temp);
                activity6.push(temp);
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

        function getData() {
            $.ajaxSetup({ cache: false });
            $.ajax({
                url: "SampleData.json",
                dataType: 'json',
                success: update,
                error: function () {
                    setTimeout(getData, updateInterval);
                }
            });
        }

        function update(_data) {
            activity1.shift();
            activity2.shift();
            activity3.shift();
            activity4.shift();
            activity5.shift();
            activity6.shift();
            xA.shift();
            yA.shift();
            zA.shift();
            xM.shift();
            yM.shift();
            zM.shift();
            xG.shift();
            yG.shift();
            zG.shift();

            now += updateInterval;

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

            xA.push([now, _data.XA]);
            yA.push([now, _data.YA]);
            zA.push([now, _data.ZA]);
            xM.push([now, _data.XM]);
            yM.push([now, _data.YM]);
            zM.push([now, _data.ZM]);
            xG.push([now, _data.XG]);
            yG.push([now, _data.YG]);
            zG.push([now, _data.ZG]);

            datasetA = [
                { label: "X:" + _data.XA , data: xA, lines: { lineWidth: lw }, color: "#00FF00" },
                { label: "Y:" + _data.YA , data: yA, lines: { lineWidth: lw }, color: "#FF0000" },
                { label: "Z:" + _data.ZA , data: zA, lines: { lineWidth: lw }, color: "#0000FF" }
            ];

            datasetM = [
                { label: "X:" + _data.XM , data: xM, lines: { lineWidth: lw }, color: "#00FF00" },
                { label: "Y:" + _data.YM , data: yM, lines: { lineWidth: lw }, color: "#FF0000" },
                { label: "Z:" + _data.ZM , data: zM, lines: { lineWidth: lw }, color: "#0000FF" }
            ];

            datasetG = [
                { label: "X:" + _data.XG , data: xG, lines: { lineWidth: lw }, color: "#00FF00" },
                { label: "Y:" + _data.YG , data: yG, lines: { lineWidth: lw }, color: "#FF0000" },
                { label: "Z:" + _data.ZG , data: zG, lines: { lineWidth: lw }, color: "#0000FF" }
            ];

            plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));
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
        
            setTimeout(getData, updateInterval);
        }

        $(document).ready(function () {
            initData();

            datasetA = [
                { label: "X:", data: xA, lines: { lineWidth: lw }, color: "#00FF00" },
                { label: "Y:", data: yA, lines: { lineWidth: lw }, color: "#FF0000" },
                { label: "Z:", data: zA, lines: { lineWidth: lw }, color: "#0000FF" }
            ];

            datasetM = [
                { label: "X:", data: xM, lines: { lineWidth: lw }, color: "#00FF00" },
                { label: "Y:", data: yM, lines: { lineWidth: lw }, color: "#FF0000" },
                { label: "Z:", data: zM, lines: { lineWidth: lw }, color: "#0000FF" }
            ];

            datasetG = [
                { label: "X:", data: xG, lines: { lineWidth: lw }, color: "#00FF00" },
                { label: "Y:", data: yG, lines: { lineWidth: lw }, color: "#FF0000" },
                { label: "Z:", data: zG, lines: { lineWidth: lw }, color: "#0000FF" }
            ];

            plots.push($.plot(placeholder1, [activity1, activity2, activity3, activity4, activity5, activity6], options1));
            plots.push($.plot(placeholder2, datasetA, options2));
            plots.push($.plot(placeholder3, datasetM, options2));   
            plots.push($.plot(placeholder4, datasetG, options2));

/*            placeholders.bind("plotpan plotzoom", function (event, plot) {
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
            }); */

            setTimeout(getData, updateInterval);  // Every updateInterval milliseconds, run the getData function
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