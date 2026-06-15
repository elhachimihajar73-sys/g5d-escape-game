// TIMER 15 minutes = 900 secondes
let secondesRestantes = 15 * 60;
let timerInterval = setInterval(() => {
    secondesRestantes--; // enlève 1 seconde à chaque fois

    // Formate en MM:SS (ex: 14:59)
    const min = Math.floor(secondesRestantes / 60).toString().padStart(2, '0');
    const sec = (secondesRestantes % 60).toString().padStart(2, '0');

    const timerEl = document.getElementById('timer');
    timerEl.textContent = `${min}:${sec}`; // met à jour l'affichage

    // Fait clignoter le timer quand moins d'1 minute
    if (secondesRestantes <= 60) timerEl.classList.add('urgent');

    // Temps écoulé : affiche l'overlay de blocage
    if (secondesRestantes <= 0) {
        clearInterval(timerInterval); // arrête le timer
        document.getElementById('overlay-bloque').classList.remove('hidden');
    }
}, 1000); // s'exécute toutes les 1000ms = 1 seconde

// Affiche la question quand on clique sur un indice
function afficherIndice(num, question) {
    const display = document.getElementById('indice-display');
    display.classList.remove('hidden'); // rend visible
    display.innerHTML = `<strong>Indice ${num} :</strong> ${question}`;
}

// Passe automatiquement à la case suivante du PIN
function focusNext(current, nextId) {
    if (current.value.length === 1) { // si une lettre est tapée
        document.getElementById(nextId)?.focus(); // focus sur la case suivante
    }
}

// Envoie le code au serveur PHP pour vérification
function validerCode() {
    // Récupère les 4 chiffres et les colle ensemble
    const code = ['p1','p2','p3','p4'].map(id => document.getElementById(id).value).join('');
    const msg = document.getElementById('message-resultat');

    // Envoie au Controller en PHP via fetch (AJAX)
    fetch('/g5d-escape-game/?page=electricity&action=valider', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code: code }) // envoie le code en JSON
    })
        .then(r => r.json()) // lit la réponse JSON du PHP
        .then(data => {
            msg.classList.remove('hidden', 'success', 'error');

            if (data.succes) {
                // Code correct : cases deviennent vertes
                ['p1','p2','p3','p4'].forEach(id => {
                    document.getElementById(id).classList.remove('rouge');
                    document.getElementById(id).classList.add('vert');
                });
                msg.classList.add('success');
                msg.textContent = '✅ Code correct ! N\'oubliez pas d\'appuyer sur le bouton poussoir !';
                deverrouillerBouton(); // débloque le bouton
                clearInterval(timerInterval); // arrête le timer
            } else {
                // Code incorrect : message rouge
                msg.classList.add('error');
                msg.textContent = '❌ Code incorrect. Réessayez !';
            }
        });
}

// Débloque le bouton poussoir
function deverrouillerBouton() {
    const btn = document.getElementById('bouton-poussoir');
    btn.disabled = false; // retire le blocage
    btn.classList.remove('locked');
    btn.classList.add('unlocked'); // applique le style jaune
    btn.textContent = '⚡ ACTIVER LA LUMIÈRE';

    // Quand on appuie sur le bouton
    btn.onclick = () => {
        btn.textContent = '💡 LUMIÈRE ALLUMÉE !';
        btn.style.background = '#4cff4c'; // devient vert
    };
}