<?php
session_start();

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/header.php';
}

session_destroy();
header("Location: " . BASE_URL . "/index.php");
exit;