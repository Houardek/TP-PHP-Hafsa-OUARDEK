<?php
require_once __DIR__ . '/functions.php';
exiger_connexion();
$titre = 'Étudiants';
$q = trim($_GET['q'] ?? '');
$tri = $_GET['tri'] ?? 'ajout';
$tris = ['ajout' => 'Ordre d’ajout', 'nom' => 'Nom', 'filiere' => 'Filière', 'note' => 'Note décroissante'];
$tri = isset($tris[$tri]) ? $tri : 'ajout';
$liste = array_values(lire_etudiants());
if ($tri === 'nom') {
    usort($liste, function ($a, $b) {
        return strcasecmp($a['nom'], $b['nom']);
    });
} elseif ($tri === 'filiere') {
    usort($liste, function ($a, $b) {
        return strcasecmp($a['filiere'], $b['filiere']);
    });
} elseif ($tri === 'note') {
    usort($liste, function ($a, $b) {
        return $b['note'] <=> $a['note'];
    });
}
$visibles = array_filter($liste, function ($e) use ($q) {
    return $q === '' || stripos($e['nom'], $q) !== false || stripos($e['prenom'], $q) !== false;
});
$parFiliere = [];
foreach ($liste as $e) {
    $parFiliere[$e['filiere']][] = $e['note'];
}
$notes = array_column($visibles, 'note');
$nombre = count($notes);
$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
include __DIR__ . '/header.php';
?>
<h1>Étudiants</h1>
<?php if ($flash): ?><p class="succes" role="status"><?= h($flash) ?></p><?php endif; ?>
<section class="carte">
    <form method="get" class="filtres">
        <label>Rechercher <input type="search" name="q" value="<?= h($q) ?>" placeholder="Nom ou prénom"></label>
        <label>Trier par
            <select name="tri">
                <?php foreach ($tris as $valeur => $libelle): ?>
                    <option value="<?= h($valeur) ?>" <?= $tri === $valeur ? 'selected' : '' ?>><?= h($libelle) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button>Appliquer</button>
    </form>
    <p class="stats" aria-live="polite">
        Étudiants affichés <strong id="nombre"><?= $nombre ?></strong>
        · Moyenne <strong id="moyenne"><?= $nombre ? number_format(array_sum($notes) / $nombre, 2, ',', ' ') : '—' ?></strong>
        · Meilleure note <strong id="meilleure"><?= $nombre ? h((string) max($notes)) : '—' ?></strong>
    </p>
    <p><a href="export.php">Télécharger le CSV</a></p>
    <h2>Moyenne par filière</h2>
    <ul>
        <?php foreach (FILIERES as $filiere): ?>
            <?php $groupe = $parFiliere[$filiere] ?? []; ?>
            <li><?= h($filiere) ?> · <?= $groupe ? number_format(array_sum($groupe) / count($groupe), 2, ',', ' ') : '—' ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="tableau">
        <table>
            <thead><tr><th>Photo</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Filière</th><th>Note / 20</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($liste as $index => $e): ?>
                <tr data-nom="<?= h($e['nom']) ?>" data-prenom="<?= h($e['prenom']) ?>" data-note="<?= h($e['note']) ?>"
                    <?= isset($visibles[$index]) ? '' : 'hidden' ?>>
                    <td><?php if ($e['photo'] !== ''): ?>
                        <img class="photo" src="uploads/<?= rawurlencode($e['photo']) ?>" alt="">
                    <?php else: ?>—<?php endif; ?></td>
                    <td><?= h(strtoupper($e['nom'])) ?></td>
                    <td><?= h(ucfirst($e['prenom'])) ?></td>
                    <td><?= h($e['email']) ?></td><td><?= h($e['filiere']) ?></td>
                    <td class="<?= (float) $e['note'] >= 10 ? 'note-ok' : 'note-basse' ?>"><?= h($e['note']) ?></td>
                    <td class="actions">
                        <a href="ajouter.php?id=<?= $e['id'] ?>">Modifier</a>
                        <a data-suppression href="supprimer.php?id=<?= $e['id'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr id="vide" <?= $nombre ? 'hidden' : '' ?>><td colspan="7">Aucun étudiant</td></tr>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/footer.php'; ?>
