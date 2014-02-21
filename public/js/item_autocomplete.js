$(document).ready(
    function() {
        $("#itemName_search")
            .autocomplete({
                "source": function(req, res) {
                    $.getJSON('/index.php/economy/autocomplete?q=' +
                              encodeURIComponent(req.term), res);
                }
            });
    });
