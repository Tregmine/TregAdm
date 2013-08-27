google.load("visualization", "1", {packages:["corechart", "geochart"]});
google.setOnLoadCallback(
    function() {
        $.getJSON("/index.php/player/loginstats",
            function(raw_data) {
                var data = google.visualization.arrayToDataTable(raw_data);

                var options =
                    {
                        title: 'Total and unique logins by Date'
                    };

                var chart = new google.visualization.LineChart(document.getElementById('logins_chart'));
                chart.draw(data, options);
            });

        $.getJSON("/index.php/player/hourstats",
            function(raw_data) {
                var data = google.visualization.arrayToDataTable(raw_data);

                var options =
                    {
                        title: 'Total logins by Hour (CET, Server Time)'
                    };

                var chart = new google.visualization.LineChart(document.getElementById('hour_chart'));
                chart.draw(data, options);
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
