$(document).ready(
    function() {
        $("#player_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/player/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
        $("#zone_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/zone/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(
    function() {
    	$.getJSON("/player/hourstats").then(raw_data => {
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
    		}).catch(e => console.error(e))
    });

function kickPlayer(subject) {
    var message = prompt("Kick message", "");
    if (message) {
        $.getJSON('/player/kick?subject=' + subject + '&message=' + encodeURIComponent(message),
            function(res) {
                alert("Player kicked!");
            });
    }
}
