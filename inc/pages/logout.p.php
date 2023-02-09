<?php
unset($_SESSION['user']);
session_destroy();
header('Location: ' . Config::$_PAGE_URL . '');
?>