<!DOCTYPE html>
<html>
    <head>

    </head>

    <body>

    <br><br><br>
    <!--menampilkan grafik-->
    <!--ukuran grafik-->
    <div id="chartContainer" style="height: 250px; max-width: 520px; margin: 0px auto;"></div>

    <h2 style="text-align: center">Monitoring Suhu Real Time</h2>

    <!--import library canva js-->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://code.jquery.com/jquery.com/jquery-3.5.1.js"
        integrity="sha256-Qwo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    
        <script>
            window.onload = function () {
               
                ///inisialisasi data array
                var dps = [];
                var dataLength  = 10;
                var updateInterval  =   1000;
                var xVal    =   0;
                var yVal    =   0;

                ///inisaial chart js
                var chart = new CanvaJS.Chart("*chartContainer", {
                    title: {
                        Text: "Grafik Suhu Realtime"
                    },
                    data: [{
                        type: "Line",
                        dataPoints:dps
                    }]
                });

                var updateChart = function (count) {
                    ///data json

                    $.getJSON("*http://localhost/suhu/getdata.php", function (data) {

                        var suhu    = data.suhu //

                        console.log(suhu)
                        yVal    = suhu
                        
                        count = count || 1;

                        for (var j=0; j < count; j++){
                            dps.push({
                                x: xVal,
                                y: yVal
                            });
                            xVal++;
                        }

                        if (dps.length > dataLength) {
                            dps.shift();
                        }

                    })
                    chart.render();
                };

                updateChart(dataLength);

                setInterval(function () {
                    updateChart()
                },updateInterval);
            }

            </script>
    </body>
</html>