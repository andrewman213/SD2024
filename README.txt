je zult waarschijnlijk manually in je terminal toestemming moeten geven om images te kunnen plaatsen, dit doe je als volgt:


Ontdek welke gebruiker Apache gebruikt:

Open de Terminal op je Mac.
Voer het volgende commando in en druk op Enter:
bash
Copy code
cat /Applications/XAMPP/xamppfiles/etc/httpd.conf | grep -E '^(User|Group)'
Onthoud de gebruikersnaam en groep die wordt weergegeven. Dit zijn degenen die Apache gebruikt.
Ga naar de map met uploads:

Open een nieuw Finder-venster.
Ga naar de map waar je XAMPP-websitebestanden zich bevinden. Dit is meestal iets als /Applications/XAMPP/xamppfiles/htdocs/SD2024.
Verander de eigendom van de map:

Ga terug naar de Terminal.
Typ het volgende commando en druk op Enter:
bash
Copy code
sudo chown [gebruikersnaam]:[groep] uploads
Vervang [gebruikersnaam] en [groep] door de waarden die je hebt ontdekt in stap 1.