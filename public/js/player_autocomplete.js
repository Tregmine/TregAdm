$(document).ready(
    function() {
        $("#player_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/index.php/player/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });
