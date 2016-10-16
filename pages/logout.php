<?php

setcookie("tregadm_login_nonce", "", 0);
session_destroy();
header('Location: /index.php');
