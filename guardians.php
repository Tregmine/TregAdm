<?php
require_once '_init.php';
require_once '_check.php';
require_once '_perm.php';

if (!array_key_exists("senioradmin", $_SESSION)) {
    header('Location: index.php');
    exit;
}

$sql  = "SELECT player_id, player_name, CAST(property_value AS UNSIGNED) rank, count(login_id) logins, "
      . "IF(NOT login_timestamp IS NULL, from_unixtime(max(login_timestamp)), 'N/A') last_login "
      . "FROM player_property ";
$sql .= "INNER JOIN player USING (player_id) ";
$sql .= "LEFT JOIN player_login USING (player_id) ";
$sql .= "WHERE property_key = 'guardian' ";
$sql .= "GROUP BY (player_id) ORDER BY rank";

$stmt = $conn->prepare($sql);
$stmt->execute();

$guardians = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Tregmine Admin Tool</title>
    <style type="text/css">
    @import 'style.css';
    </style>
</head>
<body>
    <div id="layout_wrapper">
        <h1 id="banner"><span>Tregmine Admin Tool</span></h1>

        <?php require 'menu.php'; ?>

        <h2 class="info">Guardians</h2>

        <table class="info">
            <tr>
                <th>Player</th>
                <th>Rank</th>
                <th>Logins</th>
                <th>Last Login</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($guardians as $player): ?>
            <tr>
                <td>
                    <a <?php echo userCSSColor($player["player_id"]); ?>" href="search.php?q=<?php echo $player["player_name"]; ?>"> <?php echo $player["player_name"]; ?></a>
                </td>
                <td><?php echo $player["rank"]; ?></td>
                <td><?php echo $player["logins"]; ?></td>
                <td><?php echo $player["last_login"]; ?></td>
                <td>
                    &raquo; <a href="player_perm?id=<?php echo $player["player_id"]; ?>">Permissions</a><br />
                    <!--&raquo; <a href="player_perm?id=<?php echo $player["player_id"]; ?>">Move Up</a><br />
                    &raquo; <a href="player_perm?id=<?php echo $player["player_id"]; ?>">Move Down</a>-->
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
