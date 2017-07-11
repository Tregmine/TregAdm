$(document).ready(
    function() {
        $("#zone_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/zone/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });
