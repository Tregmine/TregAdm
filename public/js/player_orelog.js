function show_coords(id, timestamp) {
    var url = "/index.php/player/orelog/coords?id=" + id + 
              "&timestamp=" + timestamp;
    $.getJSON(url,
        function(data) {
            var container = $(document.createElement("DIV"));
            container.css("position", "absolute");
            container.css("backgroundColor", "#fff");
            container.css("width", "50%");
            container.css("height", "500px");
            container.css("overflow", "auto");
            container.css("top", "500px");
            container.css("left", "25%");
            container.css("right", "25%");
            container.css("border", "5px solid #000");
            container.css("borderRadius", "5px");

                var heading = $(document.createElement("H3"));
                heading.append(document.createTextNode("Orelog Coordinates"));

                    var close = $(document.createElement("A"));
                    close.attr("href", "#");
                    close.append(document.createTextNode(" (Close)"));
                    close.click(
                        function() {
                            container.remove();
                        });
                    heading.append(close);

                heading.addClass("infoHeader");
                container.append(heading);

                var tbl = $(document.createElement("TABLE"));
                tbl.addClass("info");

                function addCell(row, content, type) {
                    var cell = $(document.createElement(type));
                    cell.append(document.createTextNode(content));
                    row.append(cell);
                }

                var desc = $(document.createElement("TR"));
                addCell(desc, "Timestamp", "TH");
                addCell(desc, "X", "TH");
                addCell(desc, "Y", "TH");
                addCell(desc, "Z", "TH");
                addCell(desc, "Ore", "TH");
                tbl.append(desc);

                for (var idx in data) {
                    var entry = data[idx];

                    var row = $(document.createElement("TR"));

                    addCell(row, entry["timestamp"], "TD");
                    addCell(row, entry["x"] + " ", "TD");
                    addCell(row, entry["y"] + " ", "TD");
                    addCell(row, entry["z"], "TD");
                    addCell(row, entry["name"], "TD");

                    tbl.append(row);
                }

                container.append(tbl);

            $("body").append(container);
        });
}
