<?php
include_once './pdo.php';
include_once './functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Breede-Gouritz Station: H7H006</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Load the Visualization API and the corechart package.
        let load = google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.

            let data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            data.addColumn('number', 'Flow (m³/s)');
            data.addRows([
                <?php fetch_chart_data($pdo); ?>
            ]);

            // Set chart options
            let options = {

                hAxis: {
                    slantedText: true
                },
                height: 600,
                lineWidth: 5,
                pointSize: 15
            };

            // Instantiate and draw chart.
            let chart = new google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
<h2 class="row justify-content-center m-3">Breede-Gouritz Station: H7H006</h2>
<!--Div that will hold the line chart-->
<div class="row align-items-center" id="chart_div"></div>
</body>
</html>
