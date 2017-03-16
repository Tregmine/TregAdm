$(document).ready(
    function() {
        $("#zone_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/index.php/zone/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });
