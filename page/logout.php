<?php
require_once('page/functions_users.php');
global $soderganie, $prefix, $db, $design;
user_logout();
Header("Location: /");
?>