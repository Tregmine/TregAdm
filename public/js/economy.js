function show_fishyblock(id) {
    var url = "/index.php/economy/fishyblock?id=" + id;
    $.getJSON(url,
        function(data) {
            var container = $(document.createElement("DIV"));
            container.css("position", "absolute");
            container.css("backgroundColor", "#fff");
            container.css("width", "50%");
            container.css("min-width", "300px");
            container.css("height", "80%");
            container.css("min-height", "300px");
            container.css("overflow", "auto");
            container.css("top", (window.scrollY + 100) + "px");
            container.css("left", "25%");
            container.css("right", "25%");
            container.css("border", "5px solid #000");
            container.css("borderRadius", "5px");
            container.addClass("panel");
            container.addClass("panel-default");

                var headWrap = $(document.createElement("DIV"));
                headWrap.addClass("panel-heading");

                    var heading = $(document.createElement("H3"));
                    heading.addClass("panel-title");

                        heading.append(document.createTextNode("Fishyblock Transactions"));

                        var close = $(document.createElement("A"));
                        close.attr("href", "javascript:;");
                        close.append(document.createTextNode(" (Close)"));
                        close.click(
                            function() {
                                container.remove();
                            });
                        heading.append(close);

                    headWrap.append(heading);

                container.append(headWrap);

                var tbl = $(document.createElement("TABLE"));
                tbl.addClass("table");

                function addCell(row, content, type) {
                    var cell = $(document.createElement(type));
                    cell.html(content);
                    row.append(cell);
                }

                var desc = $(document.createElement("TR"));
                addCell(desc, "Player", "TH");
                addCell(desc, "Action", "TH");
                addCell(desc, "Amount", "TH");
                addCell(desc, "Item Cost", "TH");
                addCell(desc, "Total Cost", "TH");
                addCell(desc, "Timestamp", "TH");
                tbl.append(desc);

                for (var idx in data) {
                    var entry = data[idx];

                    var row = $(document.createElement("TR"));

                    addCell(row, entry["player_name"], "TD");
                    addCell(row, entry["transaction_type"] + " ", "TD");
                    addCell(row, entry["transaction_amount"] + " ", "TD");
                    addCell(row, entry["transaction_unitcost"], "TD");
                    addCell(row, entry["transaction_totalcost"], "TD");
                    addCell(row, entry["transaction_timestamp"] + " ", "TD");

                    tbl.append(row);
                }

                container.append(tbl);

            $("body").append(container);
        });
}

function show_account(id) {
    var url = "/index.php/economy/account?id=" + id;
    $.getJSON(url,
        function(data) {
        console.log("test");
            var container = $(document.createElement("DIV"));
            container.css("position", "absolute");
            container.css("backgroundColor", "#fff");
            container.css("width", "50%");
            container.css("min-width", "300px");
            container.css("height", "80%");
            container.css("min-height", "300px");
            container.css("overflow", "auto");
            container.css("top", (window.scrollY + 100) + "px");
            container.css("left", "25%");
            container.css("right", "25%");
            container.css("border", "5px solid #000");
            container.css("borderRadius", "5px");
            container.addClass("panel");
            container.addClass("panel-default");

                var headWrap = $(document.createElement("DIV"));
                headWrap.addClass("panel-heading");

                    var heading = $(document.createElement("H3"));
                    heading.addClass("panel-title");

                        heading.append(document.createTextNode("Bank Account"));

                        var close = $(document.createElement("A"));
                        close.attr("href", "javascript:;");
                        close.append(document.createTextNode(" (Close)"));
                        close.click(
                            function() {
                                container.remove();
                            });
                        heading.append(close);

                    headWrap.append(heading);

                container.append(headWrap);

                var tbl = $(document.createElement("TABLE"));
                tbl.addClass("table");

                function addCell(row, content, type) {
                    var cell = $(document.createElement(type));
                    cell.html(content);
                    row.append(cell);
                }

                var desc = $(document.createElement("TR"));
                addCell(desc, "Player", "TH");
                addCell(desc, "Action", "TH");
                addCell(desc, "Amount", "TH");
                addCell(desc, "Timestamp", "TH");
                tbl.append(desc);

                for (var idx in data) {
                    var entry = data[idx];

                    var row = $(document.createElement("TR"));

                    addCell(row, entry["player_name"], "TD");
                    addCell(row, entry["transaction_type"] + " ", "TD");
                    addCell(row, entry["transaction_amount"] + " ", "TD");
                    addCell(row, entry["transaction_timestamp"] + " ", "TD");

                    tbl.append(row);
                }

                var tblWrap = $(document.createElement("DIV"));
                tblWrap.append(tbl);
                tblWrap.addClass("table-responsive");
                container.append(tblWrap);

            $("body").append(container);
        });
}

