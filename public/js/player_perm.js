$(document).ready(
    function() {
        var rank = $("#rank");
        rank.change(
            function() {
                if (rank.val() == "guardian") {
                    $("#guardian_field").css("display", "block");
                } else {
                    $("#guardian_field").css("display", "none");
                }
            });
    });
