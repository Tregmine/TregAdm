$(document).ready(
    function() {
        $("#owner")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/player/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });
