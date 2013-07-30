<?php
require_once '_init.php';
require_once '_check.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Tregmine Admin Tool</title>
    <style type="text/css">
    @import 'style.css';
    </style>

    <link rel="stylesheet" href="jquery-ui.css" />
    <script src="jquery-1.9.1.js"></script>
    <script src="jquery-ui.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(
        function() {
            $.getJSON("player_loginstats.php",
                function(raw_data) {
                    var data = google.visualization.arrayToDataTable(raw_data);

                    var options =
                        {
                            title: 'Total and unique logins'
                        };

                    var chart = new google.visualization.LineChart(document.getElementById('logins_chart'));
                    chart.draw(data, options);
                });
        });

    $(document).ready(
        function() {
            $("#player_search")
                .autocomplete({
                    "source": function(req, res) {
                        $.getJSON('player_autocomplete.php?q=' +
                                  encodeURIComponent(req.term), res);
                    }
                });
            $("#zone_search")
                .autocomplete({
                    "source": function(req, res) {
                        $.getJSON('zone_autocomplete.php?q=' +
                                  encodeURIComponent(req.term), res);
                    }
                });
        });
    </script>
</head>
<body>
    <div id="layout_wrapper">
        <h1 id="banner"><span>Tregmine Admin Tool</span></h1>

        <?php require 'menu.php'; ?>

        <h2 class="info">Welcome to Tregmine!</h2>

        <div class="col75">
            <h3 class="infoHeader">User quicksearch</h3>

            <form method="get" action="search.php">
                <div class="field">
                    <label for="player_search">User</label>
                    <div class="element">
                        <input type="text" name="q" id="player_search" />
                    </div>
                    <div class="end">&nbsp;</div>
                </div>

                <div class="button">
                    <button type="submit">Search</button>
                </div>
            </form>

            <h3 class="infoHeader">Zone quicksearch</h3>

            <form method="get" action="zones.php">
                <div class="field">
                    <label for="zone_search">Zone</label>
                    <div class="element">
                        <input type="text" name="q" id="zone_search" />
                    </div>
                    <div class="end">&nbsp;</div>
                </div>

                <div class="button">
                    <button type="submit">Search</button>
                </div>
            </form>

            <h3 class="infoHeader">Login stats</h3>
            <div id="logins_chart" style="width: 100%; height: 400px;"></div>
        </div>

        <div class="col_clear">&nbsp;</div>
    </div>
</body>
</html>
