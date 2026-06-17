import serial
import requests
import re
import time

PORT_COM    = "COM3"
BAUD_RATE   = 9600
URL_API     = "https://g5d-escape-game.onrender.com/api_capteur.php"
CLE_SECRETE = "g5d_escape_2024"

print("Démarrage du pont TIVA → Render...")

try:
    ser = serial.Serial(PORT_COM, BAUD_RATE, timeout=2)
    print("✓ TIVA connectée !")
except Exception as e:
    print(f"✗ Erreur port série : {e}")
    exit()

dernier_etat = None
led_allumee  = False
compteur     = 0  # pour vérifier commande toutes les 4 boucles (~2 sec)

def envoyer_etat(etat, valeur):
    payload = {
        "cle":    CLE_SECRETE,
        "etat":   etat,
        "valeur": valeur
    }
    try:
        r = requests.post(URL_API, json=payload, timeout=5)
        rep = r.json()
        if rep.get('succes'):
            print(f"✓ Envoyé : {etat} (LDR={valeur})")
        else:
            print(f"✗ Erreur API : {rep}")
    except Exception as e:
        print(f"✗ Erreur réseau : {e}")

def verifier_commande_led():
    """Vérifie si le site web a demandé d'allumer la LED"""
    global led_allumee
    try:
        r = requests.get(URL_API + "?action=get_commande", timeout=3)
        data = r.json()
        if data.get('allumer_led') and not led_allumee:
            ser.write(b'ALLUMER\n')  # envoie commande à la TIVA
            led_allumee = True
            print("[CMD] → TIVA : ALLUMER LED")
    except Exception as e:
        print(f"✗ Erreur commande LED : {e}")

while True:
    try:
        # Vérifie commande site → TIVA toutes les ~2 secondes
        compteur += 1
        if compteur >= 4:
            verifier_commande_led()
            compteur = 0

        ligne = ser.readline().decode('utf-8').strip()
        if not ligne:
            continue

        print(f"← TIVA : {ligne}")

        # Bouton physique appuyé
        if ligne == "REACTOR_ACTIVATED":
            etat   = "LIGHT_ON"
            valeur = 4095

        # Valeur LDR brute (accepte "LDR=2150" et "LDR = 2150")
        elif "LDR" in ligne:
            match = re.search(r"LDR\s*=\s*(\d+)", ligne)
            if not match:
                continue
            valeur = int(match.group(1))
            etat   = "LIGHT_ON" if valeur > 2500 else "LIGHT_OFF"

        else:
            continue

        # Envoie à l'API seulement si l'état change
        if etat != dernier_etat:
            envoyer_etat(etat, valeur)
            dernier_etat = etat

    except Exception as e:
        print(f"✗ Erreur lecture : {e}")
        time.sleep(1)