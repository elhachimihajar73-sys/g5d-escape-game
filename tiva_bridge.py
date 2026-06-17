import serial
import requests
import re
import time

PORT_COM    = "COM3"        # ← change selon ton port
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

while True:
    try:
        ligne = ser.readline().decode('utf-8').strip()
        if not ligne:
            continue

        print(f"← TIVA : {ligne}")

        # Bouton appuyé → LIGHT_ON
        if ligne == "REACTOR_ACTIVATED":
            etat = "LIGHT_ON"
            valeur = 4095

        # Valeur LDR brute → on calcule l'état
        elif ligne.startswith("LDR = "):
            match = re.search(r"LDR = (\d+)", ligne)
            if not match:
                continue
            valeur = int(match.group(1))
            etat = "LIGHT_ON" if valeur > 3500 else "LIGHT_OFF"

        else:
            continue

        # Envoie seulement si l'état change
        if etat != dernier_etat:
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
            dernier_etat = etat

    except Exception as e:
        print(f"✗ Erreur lecture : {e}")
        time.sleep(1)