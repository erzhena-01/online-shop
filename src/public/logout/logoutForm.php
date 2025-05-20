<?php

session_start();

$__SESSION = [];

session_destroy();

header("Location: /login.php");
exit;