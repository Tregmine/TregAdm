google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(
    function() {
        $.getJSON("/index.php/player/loginstats",
            function(raw_data) {
                var data = google.visualization.arrayToDataTable(raw_data);

                var options =
                    {
                        title: 'Total and unique logins'
                    };

                var chart = new google.visualization.LineChart(document.getElementById('logins_chart'));
                chart.draw(data, options);
            });

        $.getJSON("/index.php/player/orelog/stats",
            function(raw_data) {
                var data = google.visualization.arrayToDataTable(raw_data);

                var options =
                    {
                        title: 'Ores mined'
                    };

                var chart = new google.visualization.LineChart(document.getElementById('orelog_chart'));
                chart.draw(data, options);
            });
    });

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

function kickPlayer(subject) {
    var message = prompt("Kick message", "");
    if (message) {
        $.getJSON('/index.php/player/kick?subject=' + subject + '&message=' + encodeURIComponent(message),
            function(res) {
                alert("Player kicked!");
            });
    }
}
