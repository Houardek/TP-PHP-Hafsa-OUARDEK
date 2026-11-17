<?php
require_once __DIR__ . '/functions.php';
exiger_connexion();
$id = (int) ($_GET['id'] ?? 0);
$modification = isset($_GET['id']);
$liste = lire_etudiants();
if ($modification && !isset($liste[$id])) {
    $_SESSION['flash'] = 'Étudiant introuvable.';
    rediriger('liste.php');
}
$titre = $modification ? 'Modifier un étudiant' : 'Ajouter un étudiant';
$valeurs = $liste[$id] ?? ['nom' => '', 'prenom' => '', 'filiere' => '', 'note' => '', 'photo' => ''];
$valeurs['email'] = $valeurs['email'] ?? '';
$erreurs = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['nom', 'prenom', 'filiere', 'note', 'email'] as $champ) {
        $valeurs[$champ] = nettoyer(($_POST[$champ] ?? ''));
    }
    if ($valeurs['nom'] === '') {
        $erreurs[] = 'Le nom est obligatoire.';
    }
    if ($valeurs['prenom'] === '') {
        $erreurs[] = 'Le prénom est obligatoire.';
    }
    if (!in_array($valeurs['filiere'], FILIERES, true)) {
        $erreurs[] = 'Choisissez une filière valide.';
    }
    if (($valeurs['email'] !== '' || !$modification) && !filter_var($valeurs['email'], FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'Saisissez un email valide.';
    }
    if (!$modification) {
        foreach ($liste as $e) {
            if (strcasecmp($e['nom'], $valeurs['nom']) === 0 && strcasecmp($e['prenom'], $valeurs['prenom']) === 0) {
                $erreurs[] = 'Cet étudiant existe déjà.';
                break;
            }
        }
    }
    $note = $valeurs['note'];
    if (!is_numeric($note) || (float) $note < 0 || (float) $note > 20) {
        $erreurs[] = 'La note doit être un nombre entre 0 et 20.';
    }
    $fichier = $_FILES['photo'] ?? ['error' => UPLOAD_ERR_NO_FILE];
    $nouvellePhoto = '';
    if ($fichier['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            $erreurs[] = 'Le téléchargement de la photo a échoué.';
        } else {
            $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
            $types = ['jpg' => IMAGETYPE_JPEG, 'jpeg' => IMAGETYPE_JPEG, 'png' => IMAGETYPE_PNG];
            $image = @getimagesize($fichier['tmp_name']);
            if (!isset($types[$extension]) || !$image || $image[2] !== $types[$extension]) {
                $erreurs[] = 'Choisissez une image JPG, JPEG ou PNG valide.';
            }
            if ($fichier['size'] > 1024 * 1024) {
                $erreurs[] = 'La photo doit faire au maximum 1 Mo.';
            }
            if (!$erreurs) {
                $nouvellePhoto = uniqid('etu_') . '.' . $extension;
                if (!move_uploaded_file($fichier['tmp_name'], UPLOADS . $nouvellePhoto)) {
                    $erreurs[] = 'Impossible d’enregistrer la photo.';
                    $nouvellePhoto = '';
                }
            }
        }
    }
    if (!$erreurs) {
        $cle = $id;
        if (!$modification) {
            $cle = $liste ? max(array_keys($liste)) + 1 : 1;
        }
        $anciennePhoto = $valeurs['photo'];
        if ($nouvellePhoto !== '') {
            $valeurs['photo'] = $nouvellePhoto;
        }
        $liste[$cle] = ['id' => $cle] + $valeurs;
        sauver_etudiants($liste);
        if ($nouvellePhoto !== '') {
            supprimer_photo($anciennePhoto);
        }
        $_SESSION['flash'] = $modification ? 'Étudiant modifié.' : 'Étudiant ajouté.';
        rediriger('liste.php');
    }
}
include __DIR__ . '/header.php';
?>
<section class="carte formulaire">
    <h1><?= h($titre) ?></h1>
    <?php if ($erreurs): ?>
        <ul class="erreur" role="alert">
            <?php foreach ($erreurs as $erreur): ?><li><?= h($erreur) ?></li><?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <label>Nom <input name="nom" value="<?= h($valeurs['nom']) ?>" required></label>
        <label>Prénom <input name="prenom" value="<?= h($valeurs['prenom']) ?>" required></label>
        <label>Email <input type="email" name="email" value="<?= h($valeurs['email']) ?>" <?= $modification ? '' : 'required' ?>></label>
        <label>Filière
            <select name="filiere" required>
                <option value="">Choisir une filière</option>
                <?php foreach (FILIERES as $filiere): ?>
                    <option <?= $valeurs['filiere'] === $filiere ? 'selected' : '' ?>><?= h($filiere) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Note sur 20 <input name="note" inputmode="decimal" value="<?= h($valeurs['note']) ?>" required></label>
        <?php if ($valeurs['photo'] !== ''): ?>
            <img class="photo" src="uploads/<?= rawurlencode($valeurs['photo']) ?>" alt="Photo actuelle">
        <?php endif; ?>
        <label>Photo (facultative, JPG ou PNG, 1 Mo maximum)
            <input type="file" name="photo" accept=".jpg,.jpeg,.png">
        </label>
        <div class="actions"><button>Enregistrer</button><a href="liste.php">Annuler</a></div>
    </form>
</section>
<?php include __DIR__ . '/footer.php'; ?>
