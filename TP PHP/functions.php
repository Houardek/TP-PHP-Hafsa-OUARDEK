<?php
const FICHIER = __DIR__ . '/data/etudiants.txt';
const UPLOADS = __DIR__ . '/uploads/';
const FILIERES = ['GI', 'IDIA', 'GE', 'GC'];
const UTILISATEURS = ['admin' => 'admin123'];
session_start();

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function rediriger(string $page): void {
    header('Location: ' . $page);
    exit;
}

function connecte(): bool {
    return isset($_SESSION['login']);
}

function exiger_connexion(): void {
    if (!connecte()) {
        rediriger('index.php');
    }
}

function nettoyer(string $s): string {
    return trim(str_replace([";", "\r", "\n"], ['', '', ' '], $s));
}

function lire_etudiants(): array {
    $liste = [];
    foreach (file(FICHIER, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $ligne) {
        [$id, $nom, $prenom, $filiere, $note, $photo, $email] = array_pad(explode(';', $ligne), 7, '');
        $id = (int) $id;
        $liste[$id] = compact('id', 'nom', 'prenom', 'filiere', 'note', 'photo', 'email');
    }
    return $liste;
}

function sauver_etudiants(array $liste): void {
    $lignes = [];
    foreach ($liste as $e) {
        $lignes[] = implode(';', [$e['id'], $e['nom'], $e['prenom'], $e['filiere'], $e['note'], $e['photo'], $e['email'] ?? '']);
    }
    file_put_contents(FICHIER, implode(PHP_EOL, $lignes), LOCK_EX);
}

function supprimer_photo(string $photo): void {
    if ($photo !== '' && file_exists(UPLOADS . basename($photo))) {
        unlink(UPLOADS . basename($photo));
    }
}
