<?php
session_start();
unset($_SESSION["user"]);
unset($_SESSION["role"]);
session_destroy(); // Clear session
header("Location: index.php");
exit;
?>