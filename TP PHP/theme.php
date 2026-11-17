<?php
require_once __DIR__ . '/functions.php';
$theme = $_POST['theme'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($theme, ['clair', 'sombre'], true)) {
    setcookie('theme', $theme, time() + 30 * 86400);
}
rediriger('index.php');
