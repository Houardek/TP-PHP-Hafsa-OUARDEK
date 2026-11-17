<?php
require_once __DIR__ . '/functions.php';
exiger_connexion();
$id = (int) ($_GET['id'] ?? 0);
$etudiant = lire_etudiants()[$id] ?? null;
if (!$etudiant) {
    $_SESSION['flash'] = 'Étudiant introuvable.';
    rediriger('liste.php');
}
$titre = 'Supprimer un étudiant';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $liste = lire_etudiants();
    unset($liste[$id]);
    sauver_etudiants($liste);
    supprimer_photo($etudiant['photo']);
    $_SESSION['flash'] = 'Étudiant supprimé.';
    rediriger('liste.php');
}
include __DIR__ . '/header.php';
?>
<section class="carte formulaire">
    <h1><?= h($titre) ?></h1>
    <p>Supprimer la fiche de <strong><?= h($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></strong> ?</p>
    <form method="post">
        <div class="actions"><button class="danger">Supprimer</button><a href="liste.php">Annuler</a></div>
    </form>
</section>
<?php include __DIR__ . '/footer.php'; ?>
