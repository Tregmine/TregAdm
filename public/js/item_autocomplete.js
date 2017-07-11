$(document).ready(
    function() {
        $("#itemName_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/items/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });
