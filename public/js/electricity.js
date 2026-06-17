let secondesRestantes = 900;
let timerInterval = setInterval(() => {
    secondesRestantes--;

    const min = Math.floor(secondesRestantes / 60).toString().padStart(2, '0');
    const sec = (secondesRestantes % 60).toString().padStart(2, '0');

    const timerEl = document.getElementById('timer');
    if(timerEl) timerEl.textContent = `${min}:${sec}`;

    if (secondesRestantes <= 60) timerEl.classList.add('urgent');

    if (secondesRestantes <= 0) {
        clearInterval(timerInterval);
        document.getElementById('overlay-bloque').classList.add('show');
    }
}, 1000);

function afficherIndice(num, question) {
    const display = document.getElementById('indice-display');
    display.classList.remove('hidden');
    display.innerHTML = `<strong>Indice ${num} :</strong> ${question}`;
}

function focusNext(current, nextId) {
    if (current.value.length === 1) {
        document.getElementById(nextId)?.focus();
    }
}

function validerCode() {
    const code = ['p1','p2','p3','p4'].map(id => document.getElementById(id).value).join('');
    const msg = document.getElementById('message-resultat');

    fetch('electricity_router.php?page=electricity&action=valider', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code: code })
    })
        .then(r => r.json())
        .then(data => {
            msg.classList.remove('hidden', 'success', 'error');
            if (data.succes) {
                ['p1','p2','p3','p4'].forEach(id => {
                    document.getElementById(id).classList.remove('rouge');
                    document.getElementById(id).classList.add('vert');
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

function deverrouillerBouton() {
    document.querySelectorAll('.dot').forEach(d => d.classList.add('vert'));
    const btn = document.getElementById('bouton-poussoir');
    btn.disabled = false;
    btn.classList.remove('locked');
    btn.classList.add('unlocked');
    btn.textContent = '⚡ ACTIVER LA LUMIÈRE';
    btn.onclick = () => {
        fetch('electricity_router.php?action=activer_led', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ led: 1 })
        })
            .then(r => r.json())
            .then(data => {
                if (data.succes) {
                    btn.textContent = '💡 LUMIÈRE ALLUMÉE !';
                    btn.style.background = '#4cff4c';
                }
            });
    };
}