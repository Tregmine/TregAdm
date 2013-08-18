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
