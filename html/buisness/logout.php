<?php
session_start();
unset($_SESSION["buisness_id"]);
header("Location: login.php"); 
exit();
?>
