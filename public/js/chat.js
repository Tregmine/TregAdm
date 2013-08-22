$(document).ready(
    function() {
        var log = $("#log");
        log.addMessage =
            function(channel, sender, text) {
                var tr = $(document.createElement("TR"));

                    var channelCell = $(document.createElement("TD"));
                    channelCell.append(document.createTextNode(channel));
                    tr.append(channelCell);

                    var senderCell = $(document.createElement("TD"));
                    senderCell.append(document.createTextNode(sender));
                    tr.append(senderCell);

                    var messageCell = $(document.createElement("TD"));
                    messageCell.append(document.createTextNode(text));
                    tr.append(messageCell);

                this.prepend(tr);
            };

        log.addMessage("INFO", "Server", "Loaded");

        //var connection = new WebSocket('ws://mc.tregmine.info:9192/chat/', ['soap', 'xmpp']);
        var connection = new WebSocket('ws://localhost:9192/chat/', ['soap', 'xmpp']);
        connection.onopen =
            function() {
                log.addMessage("INFO", "Server", "Connected");
            };

        connection.onclose =
            function (error) {
                log.addMessage("INFO", "Server", "Disconnected");
            };

        connection.onmessage =
            function (e) {
                var message = JSON.parse(e.data);
                log.addMessage(
                    message.channel, message.sender, message.text);
            };

        var sendMessage =
            function(text) {
                var authToken = $("#token").val();

                var message = {};
                message.authToken = authToken;
                message.channel = "GLOBAL";
                message.text = text;

                var data = JSON.stringify(message);
                connection.send(data);
            };

        $("#message").keypress(
            function(e) {
                if (e.which != 13) {
                    return;
                }

                sendMessage($("#message").val());
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

