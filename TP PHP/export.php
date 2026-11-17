<?php
require_once __DIR__ . '/functions.php';
exiger_connexion();
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="etudiants.csv"');
$csv = fopen('php://output', 'w');
fwrite($csv, "\xEF\xBB\xBF");
fputcsv($csv, ['ID', 'Nom', 'Prénom', 'Filière', 'Note', 'Photo', 'Email'], ';', '"', '');
foreach (lire_etudiants() as $e) {
    foreach ($e as $champ => $valeur) {
        if (preg_match('/^[=+\-@]/', (string) $valeur)) {
            $e[$champ] = "'" . $valeur;
        }
    }
    fputcsv($csv, array_values($e), ';', '"', '');
}
fclose($csv);
