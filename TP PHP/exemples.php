<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require_once __DIR__ . '/functions.php';

$exemples = [
    1 => ['id' => 1, 'nom' => 'benali', 'prenom' => 'sara', 'filiere' => 'GI', 'note' => '14.5', 'photo' => ''],
    2 => ['id' => 2, 'nom' => 'alami', 'prenom' => 'youssef', 'filiere' => 'IDIA', 'note' => '17', 'photo' => ''],
    3 => ['id' => 3, 'nom' => 'mansouri', 'prenom' => 'ines', 'filiere' => 'GE', 'note' => '8.5', 'photo' => ''],
];
if (!lire_etudiants()) {
    sauver_etudiants($exemples);
}
readfile(FICHIER);
