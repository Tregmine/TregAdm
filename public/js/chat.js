$(document).ready(
    function() {
        var ranks = {
                "senior_admin": "dark_red",
                "junior_admin": "red",
                "builder": "yellow",
                "coder": "dark_purple",
                "guardian": "dark_blue",
                "donator": "gold",
                "resident": "dark_green",
                "settler": "green",
                "tourist": "white",
                "unverified": "white"
            };

        var log = $("#log");
        log.addMessage =
            function(channel, sender, rank, text) {
                var tr = $(document.createElement("TR"));

                    var channelCell = $(document.createElement("TD"));
                    channelCell.append(document.createTextNode(channel.toUpperCase()));
                    tr.append(channelCell);

                    var color = null;
                    if (rank) {
                        color = ranks[rank.toLowerCase()];
                    }

                    var senderCell = $(document.createElement("TD"));
                    senderCell.append(document.createTextNode(sender));
                    if (color) {
                        senderCell.addClass(color);
                    }
                    tr.append(senderCell);

                    text = text.replace(/&/g, "&amp;");
                    text = text.replace(/</g, "&lt;");
                    text = text.replace(/>/g, "&gt;");
                    text = text.replace(/(https?:\/\/[^ ]+)/g, "<a target=\"_blank\" href=\"$1\">$1</a>");

                    var messageCell = $(document.createElement("TD"));
                    messageCell.html(text);
                    tr.append(messageCell);

                this.prepend(tr);
            };

        log.addMessage("INFO", "Server", null, "Loaded");

        var connection = new WebSocket('ws://mc.tregmine.info:9192/chat/', ['soap', 'xmpp']);
        //var connection = new WebSocket('ws://localhost:9192/chat/', ['soap', 'xmpp']);
        connection.onopen =
            function() {
                log.addMessage("INFO", "Server", null, "Connected");
            };

        connection.onclose =
            function (error) {
                log.addMessage("INFO", "Server", null, "Disconnected");
            };

        connection.onmessage =
            function (e) {
                var message = JSON.parse(e.data);
                var channel = $("#channel").val();
                if (channel.toLowerCase() != message.channel.toLowerCase()) {
                    return;
                }

                log.addMessage(
                    message.channel, message.sender, message.rank, message.text);
            };

        var sendMessage =
            function(text) {
                var authToken = $("#token").val();
                var channel = $("#channel").val();

                var message = {};
                message.authToken = authToken;
                message.channel = channel;
                message.text = text;

                var data = JSON.stringify(message);
                connection.send(data);
            };

        $("#message").keypress(
            function(e) {
                if (e.which != 13) {
                    return;
                }

                var msg = $("#message").val();
                if (msg.trim() == "") {
                    e.preventDefault();
                    return;
                }

                sendMessage(msg);
                $("#message").val("");

                e.preventDefault();
            });

        $("#send").click(
            function() {
                sendMessage($("#message").val());
                $("#message").val("");
            });

        $("#message").focus();
    });

