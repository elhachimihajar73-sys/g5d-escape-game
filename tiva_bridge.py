import serial
import requests
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

led_allumee         = False
compteur            = 0
derniere_valeur_ldr = None

def envoyer_ldr(valeur):
    payload = {
        "cle":    CLE_SECRETE,
        "etat":   "LIGHT_ON" if valeur > 2000 else "LIGHT_OFF",
        "valeur": valeur
    }
    try:
        r = requests.post(URL_API, json=payload, timeout=5)
        rep = r.json()
        if rep.get('succes'):
            print(f"✓ LDR envoyée : {valeur}")
        else:
            print(f"✗ Erreur API : {rep}")
    except Exception as e:
        print(f"✗ Erreur réseau : {e}")

def verifier_commande_led():
    global led_allumee
    try:
        r = requests.get(URL_API + "?action=get_commande", timeout=3)
        data = r.json()

        allumer = data.get('allumer_led', False)

        if allumer and not led_allumee:
            ser.write(b'ALLUMER\n')
            led_allumee = True
            print("[CMD] → TIVA : ALLUMER LED")

        elif not allumer and led_allumee:
            led_allumee = False
            print("[RESET] Progression remise à 0")

    except Exception as e:
        print(f"✗ Erreur commande LED : {e}")

while True:
    try:
        compteur += 1
        if compteur >= 4:
            verifier_commande_led()
            compteur = 0

        ligne = ser.readline().decode('utf-8').strip()
        if not ligne:
            continue

        print(f"← TIVA : {ligne}")

        if ligne.startswith("LDR ="):
            try:
                valeur_ldr = int(ligne.split("=")[1].strip())

                if derniere_valeur_ldr is None or abs(valeur_ldr - derniere_valeur_ldr) > 50:
                    envoyer_ldr(valeur_ldr)
                    derniere_valeur_ldr = valeur_ldr

            except ValueError:
                pass

        elif ligne == "REACTOR_ACTIVATED":
            envoyer_ldr(4095)
            derniere_valeur_ldr = 4095

    except Exception as e:
        print(f"✗ Erreur lecture : {e}")
        time.sleep(1)