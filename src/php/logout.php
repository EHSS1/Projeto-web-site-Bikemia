<?php
// filepath: src/php/logout.php
session_start();
session_destroy();
header("Location: ../login.html");
?>