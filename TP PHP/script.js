document.querySelectorAll('[data-suppression]').forEach(function (lien) {
    lien.addEventListener('click', function (event) {
        if (!confirm('Supprimer cet étudiant ?')) {
            event.preventDefault();
        }
    });
});

const recherche = document.querySelector('input[name="q"]');

if (recherche) {
    recherche.addEventListener('input', function () {
        const texte = recherche.value.trim().toLowerCase();
        let nombre = 0;
        let somme = 0;
        let meilleure = 0;

        document.querySelectorAll('tr[data-nom]').forEach(function (ligne) {
            const nom = ligne.dataset.nom.toLowerCase();
            const prenom = ligne.dataset.prenom.toLowerCase();
            ligne.hidden = !nom.includes(texte) && !prenom.includes(texte);

            if (!ligne.hidden) {
                const note = Number(ligne.dataset.note);
                nombre++;
                somme += note;
                meilleure = Math.max(meilleure, note);
            }
        });

        document.querySelector('#vide').hidden = nombre > 0;
        document.querySelector('#nombre').textContent = nombre;
        document.querySelector('#moyenne').textContent = nombre ? (somme / nombre).toFixed(2).replace('.', ',') : '—';
        document.querySelector('#meilleure').textContent = nombre ? meilleure : '—';
    });
}
