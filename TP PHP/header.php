<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($titre) ?> · Gestion des étudiants</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body class="<?= ($_COOKIE['theme'] ?? '') === 'sombre' ? 'sombre' : '' ?>">
<header>
    <div class="barre">
        <strong>Gestion des étudiants</strong>
        <?php if (connecte()): ?>
            <nav aria-label="Menu principal">
                <a href="liste.php">Étudiants</a>
                <a href="ajouter.php">Ajouter</a>
                <a href="logout.php">Déconnexion</a>
            </nav>
        <?php endif; ?>
    </div>
</header>
<main>
    <form action="theme.php" method="post">
        <?php $sombre = ($_COOKIE['theme'] ?? '') === 'sombre'; ?>
        <button name="theme" value="<?= $sombre ? 'clair' : 'sombre' ?>"><?= $sombre ? 'Thème clair' : 'Thème sombre' ?></button>
    </form>
