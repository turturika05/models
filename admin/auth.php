<?php
ob_start();

session_start();

if (!isset($_SESSION['user_id'])) {
    ob_flush();
    echo '<meta http-equiv="refresh" content="0; URL=../../admin/login.php">';
    exit();
}

ob_end_flush();
?>
