$(document).ready(
    function() {
        $("#player_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/index.php/player/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
        $("#zone_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/index.php/zone/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(
    function() {
        $.getJSON("/index.php/player/hourstats",
            function(raw_data) {
                var data = google.visualization.arrayToDataTable(raw_data);

                var options =
                    {
                        chartArea: {
                            top: "2%",
                            left: "2%",
                            width: "96%",
                            height: "96%"
                        },
                        hAxis: {
                            textPosition: "none"
                        },
                        vAxis: {
                            textPosition: "none",
                            gridLines: {
                                count: 2
                            }
                        },
                        legend: {
                            position: "none"
                        }
                    };

                var chart = new google.visualization.LineChart(document.getElementById('hour_chart'));
                chart.draw(data, options);
            });
    });

function kickPlayer(subject) {
    var message = prompt("Kick message", "");
    if (message) {
        $.getJSON('/index.php/player/kick?subject=' + subject + '&message=' + encodeURIComponent(message),
            function(res) {
                alert("Player kicked!");
            });
    }
}
