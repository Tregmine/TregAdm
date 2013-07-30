<?php
require_once '_init.php';
require_once '_check.php';
require_once '_perm.php';


$hits = array();
$q = "";
if (array_key_exists("q", $_GET)) {
    $q = $_GET["q"];
    $stmt = $conn->prepare("SELECT * FROM player WHERE player_name LIKE ? ORDER BY player_name LIMIT 20");
    $stmt->execute(array($q));

    $hits = $stmt->fetchAll();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Tregmine Admin Tool</title>
    <style type="text/css">
    @import 'style.css';
    </style>
    <link rel="stylesheet" href="jquery-ui.css" />
    <script src="jquery-1.9.1.js"></script>
    <script src="jquery-ui.js"></script>
    <script type="text/javascript">
    $(document).ready(
        function() {
            $("#player_search")
                .autocomplete({
                    "source": function(req, res) {
                        $.getJSON('player_autocomplete.php?q=' +
                                  encodeURIComponent(req.term), res);
                    }
                });
        });
    </script>
</head>
<body>
    <div id="layout_wrapper">
        <h1 id="banner"><span>Tregmine Admin Tool</span></h1>

        <?php require 'menu.php'; ?>

        <h2 class="info">User search</h2>

        <form method="get" action="search.php">
            <div class="field">
                <label for="player_search">User</label>
                <div class="element">
                    <input type="text" name="q" id="player_search" value="<?php echo htmlspecialchars($q); ?>"/>
                </div>
                    <div class="end">&nbsp;</div>
            </div>

            <div class="button">
                <button type="submit">Search</button>
            </div>
        </form>

        <?php if ($q): ?>
            <h3 class="infoHeader">Results for "<?php echo htmlspecialchars($q); ?>"</h3>

            <?php if ($hits): ?>
                <table style="width: 100%;" class="info">
                    <cols>
                        <col style="width: 50px;" />
                        <col />
                        <col style="width: 200px;" />
                        <col style="width: 150px;" />
                        <col style="width: 200px;" />
                    </cols>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Member Since</th>
                        <th>Wallet</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($hits as $hit): ?>
                    <tr>
                        <td>
                            <?php echo $hit["player_id"]; ?>
                        </td>
                        <td>
                            <div <?php echo userCSSColor($hit["player_name"]); ?>><?php echo $hit["player_name"]; ?></div>
                        </td>
                        <td>
                            <?php echo $hit["player_created"]; ?>
                        </td>
                        <td>
                            <?php echo $hit["player_wallet"]; ?> tregs
                        </td>
                        <td>
                            &raquo; <a href="player_report.php?id=<?php echo $hit["player_id"]; ?>">Reports</a><br />
                            &raquo; <a href="player_stats.php?id=<?php echo $hit["player_id"]; ?>">Stats</a><br />
                            <?php if (array_key_exists("senioradmin", $_SESSION)): ?>
                            &raquo; <a href="player_perm.php?id=<?php echo $hit["player_id"]; ?>">Permissions</a><br />
                            <?php endif; ?>
                            <?php if (array_key_exists("admin", $_SESSION)): ?>
                            &raquo; <a href="player_zones.php?id=<?php echo $hit["player_id"]; ?>">Zones and Lots</a><br />
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No Hits.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
