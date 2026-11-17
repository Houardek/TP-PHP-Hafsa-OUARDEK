<?php
require_once __DIR__ . '/functions.php';
$_SESSION = [];
session_destroy();
rediriger('index.php');
