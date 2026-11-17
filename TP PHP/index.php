<?php
require_once __DIR__ . '/functions.php';
if (connecte()) {
    rediriger('liste.php');
}
$titre = 'Connexion';
$login = $_COOKIE['dernier_user'] ?? '';
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $mdp = $_POST['mdp'] ?? '';
    if (isset(UTILISATEURS[$login]) && UTILISATEURS[$login] === $mdp) {
        session_regenerate_id(true);
        $_SESSION['login'] = $login;
        setcookie('dernier_user', $login, time() + 7 * 86400);
        rediriger('liste.php');
    }
    $erreur = 'Identifiants incorrects.';
}
include __DIR__ . '/header.php';
?>
<section class="carte formulaire">
    <h1>Connexion</h1>
    <?php if ($erreur): ?><p class="erreur" role="alert"><?= h($erreur) ?></p><?php endif; ?>
    <form method="post">
        <label>Identifiant <input name="login" value="<?= h($login) ?>" autocomplete="username" required></label>
        <label>Mot de passe <input name="mdp" type="password" autocomplete="current-password" required></label>
        <button>Se connecter</button>
    </form>
</section>
<?php include __DIR__ . '/footer.php'; ?>
