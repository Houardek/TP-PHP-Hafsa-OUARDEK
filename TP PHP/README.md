# TP PHP · Gestion des étudiants

## Lancer avec XAMPP

1. Copiez le dossier `correction` dans le dossier `htdocs` de XAMPP.
2. Démarrez Apache.
3. Ouvrez [le test PHP](http://localhost/correction/test.php) puis [la page de connexion](http://localhost/correction/index.php).
4. Connectez-vous avec l’identifiant `admin` et le mot de passe `admin123`.

Vous pouvez aussi lancer le serveur PHP depuis le dossier `correction`.

```powershell
& C:/xampp/php/php.exe -S 127.0.0.1:8000
```

Ouvrez alors [l’application](http://127.0.0.1:8000/).

## Tester avec trois étudiants

Le fichier `data/etudiants.txt` est livré vide. Pour ajouter les trois exemples depuis un tableau PHP, exécutez cette commande dans le dossier `correction`.

```powershell
& C:/xampp/php/php.exe exemples.php
```

Le script affiche le contenu du fichier et n’ajoute les exemples que si le fichier est vide. Les douze exercices et les cinq bonus sont inclus.

