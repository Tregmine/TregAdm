<ul id="menu">
    <li><a href="start.php">Start</a></li>
    <li><a href="staff.php">Staff</a></li>
    <?php if (array_key_exists("admin", $_SESSION)): ?>
    <li><a href="chatlog.php">Chat Logs</a></li>
    <li><a href="zones.php">Zones</a></li>
    <?php endif; ?>
    <li><a href="reports.php">Reports</a></li>
    <li style="float: right"><a href="logout.php">Logout</a></li>
    <li style="float: right">Logged in as: <a href="search.php?q=<?php echo $_SESSION["name"]; ?>"><?php echo $_SESSION["name"] ?></a> </li>
</ul>

