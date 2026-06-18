let secondesRestantes = 900;
let timerInterval = setInterval(() => {
    secondesRestantes--;

    const min = Math.floor(secondesRestantes / 60).toString().padStart(2, '0');
    const sec = (secondesRestantes % 60).toString().padStart(2, '0');

    const timerEl = document.getElementById('timer');
    if (timerEl) timerEl.textContent = `${min}:${sec}`;

    if (secondesRestantes <= 60) timerEl.classList.add('urgent');

    if (secondesRestantes <= 0) {
        clearInterval(timerInterval);
        clearInterval(pollingInterval); // ← arrêter le polling aussi
        document.getElementById('overlay-bloque').classList.add('show');
    }
}, 1000);

// ─── Polling LDR : vérifie toutes les 3s si la lumière est détectée ───────────
let reacteurStabilise = false;

const pollingInterval = setInterval(async () => {
    if (reacteurStabilise) return; // stop une fois stabilisé

    try {
        const r = await fetch('/api_capteur.php?action=get_commande');
        const data = await r.json();

        if (data.allumer_led) {
            stabiliserReacteur();
        }
    } catch (e) {
        console.warn('Polling LDR échoué :', e);
    }
}, 3000);

function stabiliserReacteur() {
    reacteurStabilise = true;
    clearInterval(pollingInterval);

    const statut = document.getElementById('statut-reacteur');
    if (statut) {
        statut.textContent = '🟢 Réacteur Stabilisé';
        statut.classList.remove('rouge');
        statut.classList.add('vert');
    }
}

// ─── Indices ──────────────────────────────────────────────────────────────────
function afficherIndice(num, question) {
    const display = document.getElementById('indice-display');
    display.classList.remove('hidden');
    display.innerHTML = `<strong>Indice ${num} :</strong> ${question}`;
}

// ─── Navigation champs PIN ────────────────────────────────────────────────────
function focusNext(current, nextId) {
    if (current.value.length === 1) {
        document.getElementById(nextId)?.focus();
    }
}

// ─── Validation code PIN ──────────────────────────────────────────────────────
function validerCode() {
    const code = ['p1','p2','p3','p4']
        .map(id => document.getElementById(id).value)
        .join('');
    const msg = document.getElementById('message-resultat');

    fetch('/electricity_router.php?page=electricity&action=valider', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code })
    })
        .then(r => r.json())
        .then(data => {
            msg.classList.remove('hidden', 'success', 'error');

            if (data.succes) {
                ['p1','p2','p3','p4'].forEach(id => {
                    const el = document.getElementById(id);
                    el.classList.remove('rouge');
                    el.classList.add('vert');
                });
                msg.classList.add('success');
                msg.textContent = '✅ Code correct ! Appuyez sur le bouton poussoir !';
                deverrouillerBouton();
                clearInterval(timerInterval);
            } else {
                msg.classList.add('error');
                msg.textContent = '❌ Code incorrect. Réessayez !';
            }
        })
        .catch(() => {
            msg.classList.remove('hidden');
            msg.classList.add('error');
            msg.textContent = '❌ Erreur de connexion.';
        });
}

// ─── Déverrouillage bouton après code correct ─────────────────────────────────
function deverrouillerBouton() {
    document.querySelectorAll('.dot').forEach(d => d.classList.add('vert'));

    const btn = document.getElementById('bouton-poussoir');
    btn.disabled = false;
    btn.classList.remove('locked');
    btn.classList.add('unlocked');
    btn.textContent = '⚡ ACTIVER LA LUMIÈRE';

    btn.onclick = async () => {
        btn.disabled = true; // éviter double-clic
        btn.textContent = '⏳ Activation...';

        try {
            // ✅ Appel vers api_capteur.php avec etat=ACTIVATE_LED
            const r = await fetch('/api_capteur.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    cle:    'g5d_escape_2024',
                    etat:   'ACTIVATE_LED',
                    valeur: 0
                })
            });
            const data = await r.json();

            if (data.succes) {
                btn.textContent = '💡 Signal envoyé — en attente de la lumière...';
                // Le polling détectera la LDR et affichera 🟢 automatiquement
            } else {
                btn.textContent = '⚡ ACTIVER LA LUMIÈRE';
                btn.disabled = false;
                console.error('Erreur activation :', data);
            }
        } catch (e) {
            btn.textContent = '⚡ ACTIVER LA LUMIÈRE';
            btn.disabled = false;
            console.error('Erreur réseau :', e);
        }
    };
}