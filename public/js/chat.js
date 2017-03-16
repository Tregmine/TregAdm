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

                    var timestampCell = $(document.createElement("TD"));
                    timestampCell.append(document.createTextNode(new Date().toLocaleTimeString()));
                    tr.append(timestampCell);

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

        log.addMessage("INFO", "Client", null, "Loaded");

        var connection = new WebSocket('ws://api.rabil.org:9192/chat/');
        connection.onopen =
            function() {
                log.addMessage("INFO", "Client", null, "Connected");

                var authToken = $("#token").val();

                var message = {};
                message.action = "auth";
                message.authToken = authToken;

                var data = JSON.stringify(message);
                connection.send(data);
            };

        connection.onclose =
            function (error) {
                log.addMessage("INFO", "Client", null, "Disconnected");
            };

        connection.onmessage =
            function (e) {
                var message = JSON.parse(e.data);
                var action = message.action;
                if (action == "msg") {
                    var channel = $("#channel").val();
                    if (channel.toLowerCase() != message.channel.toLowerCase()) {
                        return;
                    }

                    log.addMessage(
                        message.channel, message.sender, message.rank, message.text);
                }
                else if (action == "sysmsg") {
                    log.addMessage("INFO", "Server", null, message.text);
                }
            };

        var sendMessage =
            function(text) {
                var authToken = $("#token").val();
                var channel = $("#channel").val();

                text = text.replace(/§[0-9a-z]/g,"");
                if (text.length > 100) {
                    text = text.substring(0, 100);
                }

                var message = {};
                message.action = "msg";
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
            function(e) {
                var msg = $("#message").val();
                if (msg.trim() == "") {
                    e.preventDefault();
                    return false;
                }

                sendMessage(msg);
                $("#message").val("");

                e.preventDefault();

                return false;
            });

        $("#message").focus();
    });

