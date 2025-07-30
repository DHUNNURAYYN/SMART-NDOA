<?php
session_start();
if(!$_SESSION['role'] == 'admin' || !$_SESSION['role'] == 'lecture'){
        header("location:index.php");
    exit;
}

?>