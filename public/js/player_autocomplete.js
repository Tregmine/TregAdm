$(document).ready(
    function() {
        $("#player_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/player/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });
