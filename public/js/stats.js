google.load("visualization", "1", {packages:["corechart", "geochart"]});
google.setOnLoadCallback(
    function() {
        $.getJSON("/index.php/player/loginstats",
            function(raw_data) {
                var data1 = google.visualization.arrayToDataTable(raw_data.logins);
                var data2 = google.visualization.arrayToDataTable(raw_data.unique);
                var data3 = google.visualization.arrayToDataTable(raw_data.online);

                var chart1 = new google.visualization.LineChart(document.getElementById('logins_chart'));
                chart1.draw(data1, { 
                        title: 'Total logins by Date'
                    });

                var chart2 = new google.visualization.LineChart(document.getElementById('unique_chart'));
                chart2.draw(data2, {
                        title: 'Unique players by Date'
                    });

                var chart3 = new google.visualization.LineChart(document.getElementById('online_chart'));
                chart3.draw(data3, { 
                        title: 'Max online players by Date',
                        curveType: "function"
                    });
            });

        $.getJSON("/index.php/player/hourstats",
            function(raw_data) {
                var data = google.visualization.arrayToDataTable(raw_data);

                var chart = new google.visualization.LineChart(document.getElementById('hour_chart'));
                chart.draw(data, {
                        title: 'Number of players online (CET, Server Time)'
                    });
            });

        $.getJSON("/index.php/player/geostats",
            function(raw_data) {
                var data = google.visualization.arrayToDataTable(raw_data);

                var options =
                    {
                        title: 'Total and unique logins by Country'
                    };

                var chart = new google.visualization.GeoChart(document.getElementById('geo_chart'));
                chart.draw(data, options);
            });

        var orelogChart = document.getElementById('orelog_chart');
        if (orelogChart) {
            $.getJSON("/index.php/player/orelog/stats",
                function(raw_data) {
                    var data = google.visualization.arrayToDataTable(raw_data);

                    var options =
                        {
                            title: 'Ores mined'
                        };

                    var chart = new google.visualization.LineChart(orelogChart);
                    chart.draw(data, options);
                });
        }
    });
